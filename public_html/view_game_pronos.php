<?php include '../db_connect.php';
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	
	if (!isset($_GET["id"]))
	{
		header('Location: /');
		exit;
	}
	
	$id_user = $_SESSION["id_user"];
	$id_game = $_GET["id"];

	$sql1 = "select g.id_game, g.datetime_game,
	t1.abrev_team as teama, t2.abrev_team as teamb, IFNULL(g.score_team_a, -1) as score_a, IFNULL(g.score_team_b, -1) as score_b
	  from lp_game g
	    join lp_team t1 on g.id_team_a=t1.id_team
	    join lp_team t2 on g.id_team_b=t2.id_team
	where g.id_game=$id_game and (g.score_team_a is not null or datetime_game < now() ".$timezone_offset." )";
		
	$result1 = mysqli_query($conn, $sql1);
	if (mysqli_num_rows($result1) != 1) // invalid game ID
	{
		header('Location: /');
		exit;
	}
	$row1 = mysqli_fetch_assoc($result1);
	
	$timestamp_game = strtotime($row1["datetime_game"]);
	$date_game = date("d/m/y H:i", $timestamp_game);
	$score_team_a = $row1["score_a"];
	$score_team_b = $row1["score_b"];
	$team_txt = $row1["teama"]." - ".$row1["teamb"];
	if ($score_team_a == -1)
	{
		$result_txt = "En cours";
	}
	else
	{
		$result_txt = $score_team_a." - ".$score_team_b;
	}
	include 'header.php';
	print_header("Pronostics");
?>
	<body>
	<?php include("menu.php"); ?>
	<div class="player_name">Pronostics de <?php echo $team_txt." - ".$date_game;?></div>
	<div class="">Résultat : <?php echo $result_txt; ?></div>
	<br>
	<br>
	<table>
		<tr>
			<th>Joueur</th><th>Prono</th><th>Points</th>
		</tr>
	<?php

	$sql = "select p.id_player, pl.name_user, datetime_game,
		t1.abrev_team as teama, t2.abrev_team as teamb,
		IFNULL(prono_team_a, 0) as prono_a, IFNULL(prono_team_b, 0) as prono_b,
		IFNULL(g.score_team_a, -1) as score_a, IFNULL(g.score_team_b, -1) as score_b,
		IFNULL(points, -1) as points
	from lp_pronostic p
	right join lp_game g on g.id_game=p.id_game
	join lp_team t1 on g.id_team_a=t1.id_team
	join lp_team t2 on g.id_team_b=t2.id_team
	join lp_user pl on p.id_player=pl.id_user
	where p.id_game=$id_game and (g.score_team_a is not null or datetime_game < now() ".$timezone_offset." )
	order by name_user";
	
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$id_player = $row["id_player"];
			$name_player = $row["name_user"];
			$prono_txt = $row["prono_a"]." - ".$row["prono_b"];
			$points = $row["points"];
			
			if ($points == -1)
			{
				$points = "-";
				$tdclass = "en_cours";
			}
			else
			{
				$points = round($points, 3);
				if ($points == 0)
				{
					$tdclass = "lose";
				}
				else
				{
					if ($result_txt == $prono_txt)
					{
						$tdclass = "win_exact";
					}
					else
					{
						$tdclass = "win";
					}
				}
			}
			
			echo "<tr><td><a href=\"/view_player_history.php?id=$id_player\">$name_player</a></td><td class=\"$tdclass\">$prono_txt</td><td>$points</td></tr>\r\n";
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
