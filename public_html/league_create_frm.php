<?php
include '../db_connect.php';
	session_start();
	if (!isset($_SESSION["id_user"]) || ($_SESSION["id_user"] == "")) {
		header('Location: /');
		exit;
	}
	$id_user = $_SESSION["id_user"];
	include 'header.php';
	print_header("Créer une ligue");
?>
<body>
<?php include("menu.php"); ?>
<form method="post" action="league_create_req.php">
	Nom de la ligue : <input name="league_name" type="text">
	<input type="submit" value="Créer">
</form>
