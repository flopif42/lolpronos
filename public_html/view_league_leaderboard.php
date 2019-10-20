<?php include '../db_connect.php';
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	$id_user = $_SESSION["id_user"];
	if (isset($_GET["id"]))
	{
		$id_player = $_GET["id"];
	}
	if (!isset($_GET["id_league"]))
	{
		header('Location: /');
		exit;
	}
	else
	{
		$id_league = $_GET["id_league"];
	}
	
	include 'header.php';
	print_header("League leaderboard");
?>
<body>
<?php include("menu.php");

	// check a league exists with this ID, if yes, retrieve the league name
	$sql_league = "select id_league, name_league
	from lp_league
	where id_league='$id_league'";
	$res_league = mysqli_query($conn, $sql_league);
	
	if (mysqli_num_rows($res_league) == 0)
	{
		header('Location: /');
		exit;
	}
	$row = mysqli_fetch_assoc($res_league);
	$name_league = $row["name_league"];
	
?>
	<div class="player_name">Leaderboard - [<?php echo $name_league;?>]</div>
	<br>
	<table>
	<tr><th>#</th><th>Joueur</th><th>Points</th><th colspan=2>Correct</th><th colspan=2>Exact</th></tr>
<?php
		$sql = "select ps.id_player as id_user, u.name_user,
			 sum(ps.nb_points) as points,
			 sum(ps.nb_pronos) as nb_pronos,
			 sum(ifnull(pc.nb_correct, 0)) as nb_correct , (sum(pc.nb_correct)*100 )/sum(ps.nb_pronos) as pct_correct,
			 sum(ifnull(pe.nb_exact, 0)) as nb_exact , (sum(pe.nb_exact)*100 )/sum(ps.nb_pronos) as pct_exact
		from lp_player_stats ps
		  left join lp_player_correct pc
			on ps.id_player=pc.id_player
		  left join lp_player_exact pe
			on ps.id_player=pe.id_player
		  left join lp_user u on ps.id_player=u.id_user
		where id_user in (select id_user from lp_league_users where id_league='$id_league')
		group by ps.id_player
		order by 3 desc, lower(name_user)";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0)
	{
		$i = 1;
		while($row = mysqli_fetch_assoc($result))
		{
			$id_player = $row["id_user"];
			$name_user = $row["name_user"];
			$points = round($row["points"], 3);
			$nb_pronos = $row["nb_pronos"];

			$nb_correct = $row["nb_correct"];
			$pct_correct = round($row["pct_correct"], 1);

			$nb_exact = $row["nb_exact"];
			$pct_exact = round($row["pct_exact"], 1);

			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td class="team_name"><a href="/view_player_history.php?id=
<?php echo $id_player; ?>
"><?php echo $name_user; ?></a></td>
				<td><?php echo $points; ?></td>
				<td class="numbers"><?php echo $nb_correct." / ".$nb_pronos; ?></td>
				<td class="numbers"><?php echo $pct_correct."%"; ?></td>
				<td class="numbers"><?php echo $nb_exact." / ".$nb_pronos; ?></td>
				<td class="numbers"><?php echo $pct_exact."%"; ?></td>
			</tr>
			<?php
			$i++;
		}
	}
	else
	{
		echo "<tr><td colspan=6>Aucun résultat</td></tr>";
	}

	?>
	</table>
	</body>
</html>
