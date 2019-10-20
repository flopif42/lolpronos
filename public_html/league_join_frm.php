<?php
include '../db_connect.php';
	session_start();
	if (!isset($_SESSION["id_user"]) || ($_SESSION["id_user"] == "")) {
		header('Location: /');
		exit;
	}
	$id_user = $_SESSION["id_user"];
	include 'header.php';
	print_header("Rejoindre une ligue");
?>
<body>
<?php include("menu.php"); ?>
<form method="post" action="league_join_req.php">
	Secret KEY de la ligue : <input name="secret_key" type="text">
	<input type="submit" value="Rejoindre">
</form>
