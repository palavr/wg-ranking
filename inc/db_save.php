<?php

/* 	id: welche db aktion durchführen
 *	1: neuen erledigten task eintragen
 *	2: neuen task eintragen
 *  3: erledigten task löschen
 *  4: task löschen
 */

require_once("database.php");

	if (isset($_POST)) {
		switch ($_POST['id']) {


	
		// neuer erledigter task
		case 1:

			// abfrage ob task_done schon existiert
			if($_POST['task_id'] == null || $_POST['task_id'] == -1) {
			    
			    // abfrage ob task_done schon in task eingetragen ist 				TODO

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

				
		// neuer task
		case 2:
			// get task entries 
			$result;
			try {
				$temp = $db->query("	SELECT * 
										FROM tasks 
										WHERE wg_name='" . "Sophisticates" . "'");
				$result = $temp->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) {
				echo "Could not get data.";
				exit;
			} 

			// gehe alle entries durch 
			foreach ($result as $entry) {

				if ($_POST['task_name'] == $entry['task_name']) {
					return;
				}
			}
		
			// falls noch nicht in db eingetragen		
			try {		
				// schreibe in db
				$pdo = $db->prepare("	INSERT INTO tasks(task_name, wg_name, default_points) 
										VALUES (:task_name, :wg_name, :default_points);");
				$pdo->bindParam(':task_name', $_POST['task_name']);
				$pdo->bindParam(':default_points', $_POST['default_points']);
				$name = "Sophisticates";
				$pdo->bindParam(':wg_name', $name);
				$pdo->execute();
			} catch (Exception $e) {
				echo $e->getMessage();
			}		 
		

		// neuen user anlegen
		case 3: 
			// prüfe ob user schon existiert
			// get user entries 
			$result;
			try {
				$temp = $db->query("	SELECT * 
										FROM users ;");
				$result = $temp->fetchAll(PDO::FETCH_ASSOC);
			} catch (Exception $e) {
				echo "Could not get data.";
				exit;
			} 

			foreach ($result as $entry) {
				if ($_POST['user_name'] = $entry['user_name']) {
					// evtl fehlermeldung
					return;
				}
			}

			// falls nicht 
			// lege neuen user mit daten an
			try {
			 	$pdo = $db->prepare("	INSERT INTO users (user_name, password)
			 							VALUES (:user_name, :password)");
				$pdo->bindParam(':user_name', $_POST['user_name']);
				$pdo->bindParam(':password', $_POST['password']);
				$pdo->execute();
			 } catch (Exception $e) {
			 	echo $e->getMessage();	
			 } 

			// login 

		}

	}
	
?>