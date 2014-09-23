<?php
	header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>


<html>
	<head>
		<meta charset="uft-8">
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<title>WG-Ranking</title>
	</head>

	<body>
		<header>
			<h1>Deine WG - Latest Results</h1>
		</header>
		<nav>
			<ul>
				<li><a href="index.php" class="selected">Punktestand</a></li>
				<li><a href="verwaltung.php">WG-Verwaltung</a></li>
			</ul>
		</nav>
		<?php include("inc/loadActTable.php");?>	
		<div id="buttons">
			<input type="button" value="Save table" onclick="saveDB()">
			<input type="button" value="Load table" onclick="load('tableActivities', '#activities')">
			<input type="button" value="Neuer Monat -- RESET" onclick="resetTable()">
		</div>
	</body>
	<script src="http://code.jquery.com/jquery-1.11.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/input_change.js" type="text/javascript" charset="uft-8"></script>
<footer>
</footer>

</html>