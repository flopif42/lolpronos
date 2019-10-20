<?php
include '../db_connect.php'; 
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	$league_name = $_POST["league_name"];

	//check league_name is not empty
	if ($league_name == "")
	{
		echo "Le nom de la ligue ne peut pas être vide.";
		?> <a href="league_create_frm.php">Retour</a> <?php
		exit;
	}
	
	//check that this name is available
	$sql = "SELECT id_league FROM lp_league WHERE name_league='$league_name'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		echo "Ce nom de ligue n'est pas disponible.";
		?><a href="league_create_frm.php">Retour</a><?php
		exit;
	}
	
	//create league
	$id_creator = $_SESSION["id_user"];
	$secret_key = uniqid();
	$sql = "INSERT INTO lp_league (name_league, secret_key_league, id_creator) VALUES ('$league_name', '$secret_key', '$id_creator')";
	if ($conn->query($sql) != TRUE)
	{
		header('Location: /pronos_update_frm.php?msg=3');   // error
	}

	// get the id_league of the created league
	$sql2 = "SELECT id_league FROM lp_league WHERE name_league='$league_name'";
	$result2 = mysqli_query($conn, $sql2);
	if (mysqli_num_rows($result2) == 1) {
		$row = mysqli_fetch_assoc($result2);
		$id_league = $row["id_league"];
		
		//add creator to the league
		$sql3 = "insert into lp_league_users (id_league, id_user) values ('$id_league', '$id_creator')";
		if ($conn->query($sql3) === TRUE) {
			header('Location: /pronos_update_frm.php?msg=2');	 // league created
		}
		else
		{
			header('Location: /pronos_update_frm.php?msg=3');   // error
		}	
	}
	else
	{
		header('Location: /pronos_update_frm.php?msg=3');   // error
	}
?>
