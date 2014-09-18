<?php

require("database.php");

// überprüfe ob user in einer wg ist TODO


	
// headerzeile der tabelle schreiben 
echo "<thead><tr>";
// Datenbankanfrage die alle Mitbewohner zurückgibt
foreach (getMitbewohner("Sophisticates") as $item) {
	echo "<th>".$item["user_name"]."</th>";
}
echo "</tr></thead>";
		

// echo "<tbody><tr>";
// Datenbankanfrage die alle tasks_done zurückgibt 			
foreach ($getDoneTasks as $task) {
	// berechne column anhand person 						TODO!!!!
	// trage in nächste leere zelle aufgabe ein

}
// echo "</tr></tbody>";
	


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