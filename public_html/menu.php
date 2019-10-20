<?php include '../db_connect.php';
	//session_start();
	if (!isset($_SESSION["id_user"]) || ($_SESSION["id_user"] == "")) {
		header('Location: /');
		exit;
	}
	
	$sql_time = "select now() ".$timezone_offset." as now";
	$res_time = mysqli_query($conn, $sql_time);
	$row = mysqli_fetch_assoc($res_time);
	$now = $row["now"];
	
	echo "Connecté en tant que ".$_SESSION["username"]." [<a href=\"user_logout_req.php\">Se déconnecter</a>]";
	if ($_SESSION["admin"] == 1)
	{
		echo " (admin) [".$now."]<br><br>";
		
		$sql = "select p.id_game
			      from lp_pronostic p
			        join lp_game g on p.id_game=g.id_game
			    where points is null
			    and g.score_team_a is not null";
		
		$result = mysqli_query($conn, $sql);
		$nb_rows = mysqli_num_rows($result);
		if ($nb_rows > 0)
		{
			echo "Player points need to be updated ($nb_rows).<br>";
			?><a href="points_update_req.php">Update points (admin)</a><br><?php
		}
		else
		{
			echo "Points are up-to-date.<br>";
		}
		
		$sql2 = "select id_game
			from lp_game
			where now() ".$timezone_offset." > datetime_game
			and score_team_a is null";
		$result2 = mysqli_query($conn, $sql2);
		$nb_rows = mysqli_num_rows($result2);
		if ($nb_rows > 0)
		{
			echo "Some games need validation ($nb_rows).<br>";
			?><a href="game_validate_frm.php">Validate game (admin)</a><br><?php
		}
		else
		{
			echo "Games are up-to-date.<br>";
		}
		?><a href="game_create_frm.php">Create game (admin)</a><br><?php
	}
	echo "<br><br>";
?>
<a href="pronos_update_frm.php">Mes pronostics</a><br>
<!-- <a href="league_summary.php">Ligues</a>&nbsp;|&nbsp;<a href="league_create_frm.php">Créer</a>&nbsp;|&nbsp;<a href="league_join_frm.php">Rejoindre</a><br> -->
<a href="view_player_history.php?id=<?php echo $_SESSION["id_user"]; ?>">Mon historique</a><br>
<a href="view_leaderboard.php">Classement général</a><br>
<a href="view_leaderboard2.php">Classement par journée</a><br>
<br>
