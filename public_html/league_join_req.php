<?php
include '../db_connect.php'; 
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	$secret_key = $_POST["secret_key"];

	//check secret_key is not empty
	if ($secret_key == "")
	{
		echo "Le secret KEY ne peut pas être vide.";
		?> <a href="league_join_frm.php">Retour</a> <?php
		exit;
	}
	
	// check that a league exist with this key
	$sql_league_exist = "select id_league
	from lp_league
	where secret_key_league='$secret_key'";
	$result1 = mysqli_query($conn, $sql_league_exist);
	if (mysqli_num_rows($result1) == 0) {
		echo "La ligue avec ce secret KEY n'existe pas.";
		?><a href="league_join_frm.php">Retour</a><?php
		exit;
	}

	$row = mysqli_fetch_assoc($result1);
	$id_league = $row["id_league"];		
	$id_user = $_SESSION["id_user"];
	
	//check that user not already in this league
	$sql_user_joined = "select lu.id_user
	from lp_league_users lu
	where id_league='$id_league' and id_user='$id_user'";
	$result2 = mysqli_query($conn, $sql_user_joined);
	if (mysqli_num_rows($result2) > 0) {
		echo "Vous faites déjà partie de cette ligue.";
		?><a href="league_join_frm.php">Retour</a><?php
		exit;
	}

	//add user to the league
	$sql_insert = "insert into lp_league_users (id_league, id_user) values ('$id_league', '$id_user')";
	if ($conn->query($sql_insert) === TRUE) {
		header('Location: /pronos_update_frm.php?msg=4');	 // league joined
	}
	else
	{
		header('Location: /pronos_update_frm.php?msg=3');   // error
	}	
?>
