<?php
	session_start();
	session_unset(); 
	session_destroy();
	echo "Vous vous êtes bien déconnecté. <a href=\"/\">Accueil</a>";
?>

