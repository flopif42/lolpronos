<?php include '../db_connect.php';
	session_start();
	$username = $_POST["username"];
	$passwd = $_POST["passwd"];

	#check username is not empty
	if ($username == "")
	{
		echo "Le nom d'utilisateur ne peut pas être vide.";
		?> <a href="/">Retour</a> <?php
		exit;
	}
	
	#check password is not empty
	if ($passwd == "")
	{
		echo "Le mot de passe ne peut pas être vide.";
		?> <a href="/">Retour</a> <?php
		exit;
	}
	
	#check user credentials
	$md5_passwd = md5($passwd);
	$sql = "SELECT id_user, name_user, b_admin
	  FROM lp_user
	  WHERE name_user='$username'
	  AND passwd_user='$md5_passwd'";
	  
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 0)
	{
		echo "Nom d'utilisateur ou mot de passe incorrect.";
		?><a href="/">Retour</a><?php
		exit;
	}
	
	$row = mysqli_fetch_assoc($result);
	
	$_SESSION["id_user"] = $row["id_user"];
	$_SESSION["username"] = $row["name_user"];
	$_SESSION["admin"] = $row["b_admin"];
	header('Location: /pronos_update_frm.php');	
?>
