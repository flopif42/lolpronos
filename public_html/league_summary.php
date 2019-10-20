<?php include '../db_connect.php';
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	
	$id_user = $_SESSION["id_user"];
	include 'header.php';
	print_header("Mes ligues ");
?>
	<body>
	<?php include("menu.php"); ?>
<?php

	$sql = "select a.id_league, b.secret_key_league as secret_key, name_league, u.id_user as id_participant, u.name_user as participant, creator.name_user as creator
	from lp_league_users a
	join lp_league b
	on a.id_league=b.id_league
	join lp_user u
	on a.id_user=u.id_user
    join lp_user creator on b.id_creator=creator.id_user
	where a.id_league in (select lu.id_league
	from lp_league_users lu
	where lu.id_user='$id_user')
	order by id_league";

  	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		$previous_name_league = "";
		
		while($row = mysqli_fetch_assoc($result))
		{
			$id_league = $row["id_league"];
			$secret_key = $row["secret_key"];
			$name_league = $row["name_league"];
			$id_player = $row["id_participant"];
			$participant = $row["participant"];
			$creator = $row["creator"];
			
			if ($name_league != $previous_name_league)
			{
				if ($previous_name_league != "")
				{
					// close previous table
					?></table><br><?php
				}
				// open new table
				$previous_name_league = $name_league;
				?>
				<div class="league_name"><a href="view_league_leaderboard.php?id_league=<?php echo $id_league; ?>"><?php echo $name_league; ?></a>&nbsp;(secret KEY : <?php echo $secret_key;?>)</div>
				<div><i>créée par <?php echo $creator; ?></i></div>				
				<?php
				?><br><table>
				<tr><th>Joueurs</th></tr><?php
			}
			// echo "<tr><td>$participant</td></tr>";
			?>
			<tr><td class="team_name"><a href="/view_player_history.php?id=<?php echo $id_player; ?>"><?php echo $participant; ?></a></td></tr>
<?php
		}
?>
</table><br>
<?php
	}
	else
	{
?>
Vous n'avez rejoint aucune ligue.
<?php
	}
?>	
	</body>
</html>
