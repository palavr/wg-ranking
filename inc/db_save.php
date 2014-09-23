<?php

/* 	id: welche db aktion durchführen
 *	1: neuen erledigten task eintragen
 *	2: neuen task eintragen
 *  3: erledigten task löschen
 *  4: task löschen
 */

require_once("database.php");

	echo "<pre>";
	var_dump($_POST);

	if (isset($_POST)) {
		switch ($_POST['id']) {


	
	// neuer erledigter task
	case 1:

		// abfrage ob task_done schon existiert
		if($_POST['task_id'] == null || $_POST['task_id'] == -1) {
		    
		    // abfrage ob task_done schon in task eingetragen ist
		    echo "its new";

		    // no entry found, new entry
			try {
				$pdo = $db->prepare("	INSERT INTO tasks_done(task_name, user_name, wg_name, date)
										VALUES (:task_name, :user_name, :wg_name, curdate());");
				
				$pdo->bindParam(':task_name', $_POST['task_name']);
				$pdo->bindParam(':user_name', $_POST['user_name']);
				$pdo->bindParam(':wg_name', $_POST['wg_name']);
				$pdo->execute();
				echo "DB erfolgreich aktualisiert.";
			} catch (Exception $e) {
				echo $e->getMessage();
			}
			exit;
		} else {
			// entry found, update entry
			try {
				$pdo = $db->prepare("	UPDATE tasks_done
										SET task_name=:task_name, user_name=:user_name, wg_name=:wg_name
										WHERE id = ". $_POST['task_id'] . ";");
				$pdo->bindParam(':task_name', $_POST['task_name']);
				$pdo->bindParam(':user_name', $_POST['user_name']);
				$pdo->bindParam(':wg_name', $_POST['wg_name']);
				$pdo->execute();
				echo "DB erfolgreich aktualisiert.";

			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		break;
			

			/*
	// neuer task
	case 2:
		// abfrage ob bereits vorhanden 
			// falls ja

			// falls nein
			$db->prepare("	INSERT INTO tasks(task_name, wg_name, default_points) 
							VALUES (:task_name, :wg_name, :default_points)");
			$db->bindParam(':task_name', $_POST['task_name']);
			$db->bindParam(':default_points', $_POST['default_points']);
			$db->bindParam(':wg_name', $_POST['wg_name']);
			$db->execute();*/
		}
	}
?>