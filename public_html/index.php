<?php
	session_start();

	if (isset($_SESSION["id_user"]) && ($_SESSION["id_user"] != ""))
	{
		header('Location: /pronos_update_frm.php');
	}
	else
	{		
		include 'header.php';
		print_header("Connexion");
	?>
	<body>
LoL pronostics<br>
<br>
<form method="post" action="user_login_req.php">Nom d'utilisateur: <input type="text" name="username" size=36><br>
Mot de passe: <input type="password" name="passwd" size=36><br>		
<input type="submit" value="Se connecter">		
</form>		
<a href="user_create_frm.php">Créer un compte</a>
	<?php
	}	
	?>	
	</body>
</html>
