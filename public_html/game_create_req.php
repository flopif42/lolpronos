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
	
	$id_team_a     = $_POST["team_a"];
	$id_team_b     = $_POST["team_b"];
	$odds_team_a   = $_POST["odds_a"];
	$odds_team_b   = $_POST["odds_b"];
	$datetime_game = $_POST["datetime"];
	$bo_number     = $_POST["bo"];

	if ($odds_team_a == "")
	{
		$odds_team_a = "1.0";
	}
	
	if ($odds_team_b == "")
	{
		$odds_team_b = "1.0";
	}
	
	$sql = "insert into lp_game (id_team_a, id_team_b, datetime_game, odds_team_a, odds_team_b, bo_number)
	        values ($id_team_a, $id_team_b, str_to_date(\"$datetime_game\", \"%d/%m/%Y %H:%i\"), $odds_team_a, $odds_team_b, '$bo_number')";
	$result = mysqli_query($conn, $sql);
	header('Location: /game_create_frm.php');
?>
