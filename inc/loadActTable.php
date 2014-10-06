<?php 


require_once("database.php");


/********************************************************************************************
										WRITE TABLE
 ********************************************************************************************/
	
echo "<table id='activities'>";

/***********************			HEADER 			***********************/

$keys= array();
echo "<thead><tr>";
// Datenbankanfrage die alle Mitbewohner zurückgibt
foreach (getMitbewohner("Sophisticates") as $item) {
	echo "<th>".$item["user_name"]."</th>";
	$keys[]=$item["user_name"];
}
echo "</tr></thead>";



/***********************			FOOTER  			***********************/

echo "<tfoot><tr><td colspan='3'>Alle Angaben sind wie immer ohne Gew&aumlhr.</td></tr></tfoot>";



/***********************			BODY 	 			***********************/

// speichere erledigte Tasks in temp array zwischen
$temp = array_fill_keys($keys, null);

// Datenbankanfrage die alle tasks_done zurückgibt 			
foreach (getDoneTasks("Sophisticates") as $task) {
	// trage in temp array aufgabe ein
	foreach ($temp as $key => $value) {
		if ($key == $task["user_name"]){
			$temp[$key][]= Array("name"=> $task["task_name"], "id"=> $task["id"]);
		}
	}
}


// bestimme max einträge (= benötigte trs)
$counts=array_map('count', $temp);

$max_entries = max($counts);

echo "<tbody>";
for ($i = 0; $i <= $max_entries; $i++){
	echo "<tr class='editable'>";
	// lese i-ten eintrag aus jedem array aus
	foreach ($temp as $key => $entries) {

		if ($entries[$i]["name"] == null) {
			// schreibe leeres td falls null
			echo "<td";
			if ($counts[$key] == $i) {
				echo " contenteditable='true'";
			}
			echo "></td>";
		} else {
			// schreibe in td falls nicht null
			echo "<td task_id='". $entries[$i]["id"] . "'>".$entries[$i]["name"]."</td>";
		}
	}
	echo "</tr>";
}


/***********************	Punkteberechnung 	 ***********************/

echo "<tr id='pointsTotal'>";

//echo phpinfo();
$points= array();


foreach ($temp as $user_name=>$tasks) {
	$totalPts = 0;
	foreach ($tasks as $entry) {
		$totalPts += getPoints($entry["id"]);
	}
	echo "<td>" . $totalPts . "</td>";
	$points[$user_name]=$totalPts;

}

echo "</tr>";



echo "</tbody>";

echo "</table>";


/***********************	Auszeichnungen	 	 ***********************/

echo "<div id='honors'><h2>Fleißiges Bienchen der Woche ist somit: </h2><h1>" . getHero($points) . "</h1>";
			
echo '<h2>Die ehrenvolle Auszeichnung "faule Socke" geht diesmal an: </h2><h1>' . getVillain($points) . "</h1></div>";





/********************************************************************************************
											GETTER
 ********************************************************************************************/

// gibt gesuchten user zurück
function getUser($name) {
	require("database.php");

	try {
		$result = $db->query("	SELECT * 
								FROM users 
								WHERE user_name='" . $name . "'");
	} catch (Exception $e) {
		echo "Could not get data.";
		exit;
	} 
	return $result->fetch(PDO::FETCH_ASSOC);

}

// gibt gesuchte wg zurück
function getWG($name) {
	require("database.php");

	try {
		$result = $db->query("	SELECT * 
								FROM wgs 
								WHERE wg_name='" . $name . "'");
	} catch (Exception $e) {
		echo "Could not get data.";
		exit;
	} 
	return $result->fetch(PDO::FETCH_ASSOC);

}

// gibt alle erstellten Tasks für wg zurück
function getTasks($wg) {
	require("database.php");

	try {
		$result = $db->query("	SELECT * 
								FROM tasks 
								WHERE wg_name='" . $wg . "'");
	} catch (Exception $e) {
		echo "Could not get data.";
		exit;
	} 
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

// gibt alle erledigten tasks für wg zurück
function getDoneTasks($wg) {
	require("database.php");

	try {
		$result = $db->query("	SELECT * 
								FROM tasks_done 
								WHERE wg_name='" . $wg . "'");
	} catch (Exception $e) {
		echo "Could not get data.";
		exit;
	} 
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

// gibt alle mitbewohner der wg zurück
function getMitbewohner($wg) {
	require("database.php");

	try {
		$result = $db->query("	SELECT user_name 
								FROM users
								WHERE wg_name='" . $wg . "'");
	} catch (Exception $e) {
		echo "Could not get data.";
		exit;
	} 
	return $result->fetchAll(PDO::FETCH_ASSOC);
}

// gibt gespeicherte punkte für gesuchte aufgabe zurück
function getPoints($id) {
	require("database.php");

	try {
		// erste query falls points überschrieben sind
		$result = $db->query("	SELECT points_override 
								FROM tasks_done
								WHERE id='".$id."';");
		$result = $result->fetch();

		// zweite query falls default_points
		if ($result["points_override"] == null){
			$query = "	SELECT default_points
						FROM tasks
						WHERE task_name=(
								SELECT task_name 
								FROM tasks_done 
								WHERE id=". $id . ");";		
			$result = $db->query($query);
			$result = $result->fetch();

			return $result["default_points"];
		} else {
			echo "not false";
			return $result["points_override"];
		}
	} catch (Exception $e) {
		echo "Could not get points for " . $task . ".";
		echo $e->getMessage();
		exit;
	}
}

// ermittelt, wer am meisten punkte hat
function getHero($points) {
	// check if hero
	$maxPts = -1;
	$hero = array();
	foreach ($points as $user_name => $totalPts) {

		if ($maxPts < $totalPts) {
			// clear array
			unset($hero);
			$hero = array();

			$hero[]= $user_name;
			$maxPts = $totalPts;
		} elseif ($maxPts == $totalPts) {
			$hero[]= $user_name;
		}
	}

	if (count($hero) == 1) {
		return $hero[0];
	}

	$disp = "";
	$first = true;

	// convert array to string
	foreach ($hero as $h) {
		if (!$first) {
			$disp.=" und ";
		}
		$disp.=$h;
		$first= false;
	}
	
	return $disp;
}

// ermittelt, wer am wenigsten punkte hat
function getVillain($points) {

	$minPts = INF;
	$villain = array();


	foreach ($points as $user_name => $totalPts) {

		if ($minPts > $totalPts) {
			// clear array
			unset($villain);
			$villain = array();

			$villain[]= $user_name;
			$minPts = $totalPts;
		} elseif ($minPts == $totalPts) {
			$villain[]= $user_name;
		}
	}

	if (count($villain) == 1) {
		return $villain[0];
	}

	$disp = "";
	$first = true;

	// convert array to string
	foreach ($villain as $h) {
		if (!$first) {
			$disp.=" und ";
		}
		$disp.=$h;
		$first= false;
	}
	
	return $disp;

}

/********************************************************************************************
											SETTER
 ********************************************************************************************/


// erstellt neue wg
function createWG($wg_name) {
	require("database.php");

	try {
		$result = $db->query("	INSERT INTO wgs 
								VALUES('" . $wg_name ."')");
	} catch (Exception $e) {
		console.log($e->getMessage());
		console.log("Could not create new WG.");
		return false;
	} 
	return true;
}

// fügt user neuer wg hinzu
function chooseWG($wg_name, $user_name) {
	require("database.php");

	try {
		$result = $db->query("	INSERT INTO users(wg_name) 
								VALUES(".$wg_name.") 
								WHERE user_name='" . $user_name . "'");
	} catch (Exception $e) {
		echo "Could not set WG.";
		return false;
	} 
	return true;
}

// neuer task für wg 	
function insertTask($task_name, $wg_name, $points) {
	require("database.php");

	try {
		$result = $db->query("	INSERT INTO tasks 
								VALUES('" . $task_name ."','" . $wg_name ."','". $points ."')");
	} catch (Exception $e) {
		console.log($e->getMessage());
		console.log("Could not create new task.");
		return false;
	} 
	return true;
}

// trägt neuen erledigten task ein 
function taskDone($task_name, $user_name, $wg_name) {
require("database.php");

	try {
		$result = $db->query("	INSERT INTO tasks_done(task_name, user_name, wg_name, date) 
								VALUES('" . $task_name ."','" . $user_name."','".$wg_name ."', curdate())");
	} catch (Exception $e) {
		console.log($e->getMessage());
		console.log("Could not proceed done task.");
		return false;
	} 
	return true;
}


?>