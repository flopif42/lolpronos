<?php include '../db_connect.php'; ?>

<?php
	$username = $_POST["username"];
	$passwd = $_POST["passwd"];
	$passcheck = $_POST["passcheck"];

	#check username is not empty
	if ($username == "")
	{
		echo "Le nom d'utilisateur ne peut pas être vide.";
		?> <a href="signup.php">Retour</a> <?php
		exit;
	}
	
	#check password is not empty
	if ($passwd == "")
	{
		echo "Le mot de passe ne peut pas être vide.";
		?> <a href="user_create_frm.php">Retour</a> <?php
		exit;
	}
	
	#check that passwords match
	if ($passwd != $passcheck)
	{
		echo "Les mots de passe ne correspondent pas.";
		?> <a href="user_create_frm.php">Retour</a> <?php
		exit;
	}
	
	#check that this username is available
	$sql = "SELECT id_user FROM lp_user WHERE name_user='$username'";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		echo "Ce nom d'utilisateur n'est pas disponible.";
		?><a href="user_create_frm.php">Retour</a><?php
		exit;
	}
	
	#create user
	$md5_passwd = md5($passwd);
	$sql = "INSERT INTO lp_user (name_user, passwd_user) VALUES ('$username', '$md5_passwd')";

	if ($conn->query($sql) === TRUE)
	{
		echo "Le compte a été créé. <a href=\"/\">Se connecter</a>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

?>
