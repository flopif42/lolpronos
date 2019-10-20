<?php include '../db_connect.php';
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	
	$id_user = $_SESSION["id_user"];
	$id_player = $_GET["id"];

	$sql1 = "select name_user
		from lp_user
		where id_user=$id_player";
		
	$result1 = mysqli_query($conn, $sql1);
	if (mysqli_num_rows($result1) == 1)
	{
		$row = mysqli_fetch_assoc($result1);
		$name_player = $row["name_user"];
	}
?>

<?php
	include 'header.php';
	print_header("Historique de ".$name_player);
?>
	<body>
	<?php include("menu.php"); ?>
	<div class="player_name">Historique de <?php echo $name_player; ?></div>
	<br>
	<table>
		<tr>
			<th>Date</th><th>Versus</th><th>Prono</th><th>Résultat</th><th>Points</th>
		</tr>
	<?php
	
	$sql = "select p.id_game, datetime_game,
	        t1.abrev_team as teama, t2.abrev_team as teamb,
	        IFNULL(prono_team_a, 0) as prono_a, IFNULL(prono_team_b, 0) as prono_b,
			IFNULL(g.score_team_a, -1) as score_a, IFNULL(g.score_team_b, -1) as score_b,
			IFNULL(points, -1) as points
		from lp_pronostic p
		right join lp_game g on g.id_game=p.id_game
		join lp_team t1 on g.id_team_a=t1.id_team
		join lp_team t2 on g.id_team_b=t2.id_team
		where id_player=$id_player and (g.score_team_a is not null or datetime_game < now() ".$timezone_offset.")
		order by datetime_game";
		
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$id_game = $row["id_game"];
			$timestamp_game = strtotime($row["datetime_game"]);
			$date_game = date("d/m/y H:i", $timestamp_game);
			
			$team_txt = $row["teama"]." - ".$row["teamb"];
			$prono_txt = $row["prono_a"]." - ".$row["prono_b"];
			
			$score_team_a = $row["score_a"];
			$score_team_b = $row["score_b"];
			
			$points = $row["points"];

			if ($score_team_a == -1)
			{
				$result_txt = "En cours";
			}
			else
			{
				$result_txt = $score_team_a." - ".$score_team_b;
			}
			
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
			
			echo "<tr><td>$date_game</td><td><a href=\"/view_game_pronos.php?id=$id_game\">$team_txt</a></td><td class=\"$tdclass\">$prono_txt</td><td>$result_txt</td><td>$points</td></tr>\r\n";
		}
	}
	else
	{
		echo "<tr><td colspan=6>Aucun résultat</td></tr>";
	}
	
?>
	</table>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	</body>
</html>
