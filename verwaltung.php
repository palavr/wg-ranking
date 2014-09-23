<?php
	header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="uft-8">
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/verwaltung.css">
		
		<title>WG-Ranking</title>
	</head>

	<body>
		<header>
			<h1>Deine WG - Lege Punkte fest</h1>
		</header>
		<nav>
			<ul>
				<li><a href="index.php">Punktestand</a></li>
				<li><a href="verwaltung.php" class="selected">WG-Verwaltung</a></li>
			</ul>
		</nav>
		<?php include("inc/loadPointsTable.php"); ?>

		<div id="dialog">
			<input type="text">
			<input type="text">
			<input type="button" value="Aufgabe hinzufügen" onclick="addNewActivity()">
		</div>
		<div id="pointBtn">
			<input type="button" id="showDialogBtn" value="Neue Aufgabe hinzufügen" onclick="showDialog()">
			<input type="button" id="savePunkte" value="Tabelle speichern" onclick="savePtsDB()">
		</div>
	</body>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/input_change.js" type="text/javascript" charset="uft-8"></script>
<footer>
</footer>

</html>