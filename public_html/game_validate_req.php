<?php include '../db_connect.php'; 
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	
	if ($_SESSION["admin"] == 0)
	{
		header('Location: /');
		exit;
	}
	
	$id_game = $_POST["id_game"];
	$score_team_a = $_POST["ra"];
	$score_team_b = $_POST["rb"];

	$sql = "update lp_game
	        set score_team_a=$score_team_a, score_team_b=$score_team_b
			where id_game=$id_game";
	
	//echo $sql;
	mysqli_query($conn, $sql);
	header('Location: /game_validate_frm.php');
?>
