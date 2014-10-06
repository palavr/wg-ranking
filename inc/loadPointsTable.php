<?php

require_once("database.php");


/********************************************************************************************
										WRITE TABLE
 ********************************************************************************************/
	
echo "<table id='punkte'>";

/***********************			HEADER 			***********************/

echo "<thead><tr><th>Aufgabe</th><th>Punkte</th></tr></thead>";


/***********************			BODY 	 			***********************/

echo "<tbody>";

include("database.php");

// gehe alle entries aus tasks durch
foreach (getTasks("Sophisticates") as $task) {
	echo "<tr class='editable'><td>" . $task["task_name"] . "</td><td>" . $task["default_points"] . "</td></tr>";
}



echo "</tbody>";

echo "</table>";

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



?>
