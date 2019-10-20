<?php include '../db_connect.php';
	session_start();
	if (!isset($_SESSION["id_user"]) || ($_SESSION["id_user"] == "")) {
		header('Location: /');
		exit;
	}
	$id_user = $_SESSION["id_user"];
	include 'header.php';
	print_header("Mes pronostics");
?>
<body>
<?php
if (isset($_GET["msg"]))
{
	if ($_GET["msg"] == 1)
	{
		$msg = "Les pronostics ont été enregistrés.";
	}
	else if ($_GET["msg"] == 2)
	{
		$msg = "La ligue a bien été créée.";
	}
	else if ($_GET["msg"] == 3)
	{
		$msg = "Erreur";
	}
	else if ($_GET["msg"] == 4)
	{
		$msg = "Vous avez bien rejoint la ligue.";
	}
	else
	{
		$msg = "";
	}
?>
	<div id="top_msg"><?php echo $msg?></div>
<?php	
}
	include("menu.php");
?>
<form method="post" action="pronos_update_req.php" id="form1">
<?php
	include("prono_winner_frm.php");
	
	$sql = "SELECT g.id_game, g.bo_number, t1.name_team as team_a, t2.name_team as team_b, TRUNCATE(odds_team_a, 2) as odds_a, TRUNCATE(odds_team_b, 2) as odds_b,
	datetime_game, IFNULL(p.prono_team_a, 0) as prono_a, IFNULL(p.prono_team_b, 0) as prono_b, now() ".$timezone_offset." as mysql_now
	FROM lp_game g
	  join lp_team t1 on g.id_team_a = t1.id_team
	  join lp_team t2 on g.id_team_b = t2.id_team
      left join lp_pronostic p on g.id_game=p.id_game and p.id_player=".$id_user."
	  WHERE date_format(datetime_game, \"%j\") >= date_format(now() ".$timezone_offset." , \"%j\")
	  order by datetime_game";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0)
	{
		$previous_day = "";
	
		while($row = mysqli_fetch_assoc($result))
		{
			$id_game = $row["id_game"];
			$team_a = $row["team_a"];
			$team_b = $row["team_b"];
			$odds_team_a = $row["odds_a"];
			$odds_team_b = $row["odds_b"];
			$prono_a = $row["prono_a"];
			$prono_b = $row["prono_b"];
			$bo_number = $row["bo_number"];
			
			$timestamp_game = strtotime($row["datetime_game"]);
			$timestamp_now = strtotime($row["mysql_now"]);
			
			if ($timestamp_now > $timestamp_game)
			{
				$expired = TRUE;
			}
			else
			{
				$expired = FALSE;
			}
			
			$day = date("l j F", $timestamp_game);
			$time = date("H:i", $timestamp_game);

			if ($previous_day != $day)
			{
				if ($previous_day != "")
				{
					?></table><br><?php
				}
				?>
				<table>
				<tr><th colspan=6 align="center"><?php echo $day; ?></th></tr><?php
			}
		?>
		<tr>
		<?php
			if ($team_a == "TBD")
			{
				$team_name_class = "team_name_TBD";
			}
			else
			{
				$team_name_class = "team_name";
			}
			
			if ($expired)
			{
				echo "<td class=\"time_game_expired\"><a href=\"/view_game_pronos.php?id=$id_game\"><i>$time</i> fermé</a></td>";
				echo "<td class=\"$team_name_class\">$team_a</td>";
				echo "<td class=\"odds\"><b>$odds_team_a</b></td>";
				echo "<td class=\"expired\">$prono_a - $prono_b</td>";
			}
			else
			{
				echo "<td class=\"time_game\"><i>$time</i></td>";
				echo "<td class=\"$team_name_class\">$team_a</td>";
				echo "<td class=\"odds\"><b>$odds_team_a</b></td>";
				if ($bo_number == "BO1")
				{
					print_bo1($id_game, $prono_a, $prono_b);
				}
				else if ($bo_number == "BO3")
				{
					print_bo3($id_game, $prono_a, $prono_b);
				}
				else if ($bo_number == "BO5")
				{
					print_bo5($id_game, $prono_a, $prono_b);
				}
			}
			
			?>

			<td class="odds"><b><?php echo $odds_team_b; ?></b></td>
			<?php
			if ($team_b == "TBD")
			{
				$team_name_class = "team_name_TBD";
			}
			else
			{
				$team_name_class = "team_name";
			}
			echo "<td class=\"$team_name_class\">$team_b</td>";
		?>
		</tr>
		<?php
			$previous_day = $day;
		}
	?>
	</table>
	<br>
	<input type="submit" value="Enregistrer les modifications">
	<?php
	}
	else
	{
?>
<div class="player_name">Les matchs n'ont pas encore été annoncés. Revenez plus tard !</div>
<img class="teemo" src="https://vignette.wikia.nocookie.net/leagueoflegends/images/3/38/Zilean_GroovySkin.jpg/revision/latest/scale-to-width-down/640?cb=20180521005052">
<?php
	} 
?>
	</form>
	<br><br><br><br><br>
	</body>
</html>
