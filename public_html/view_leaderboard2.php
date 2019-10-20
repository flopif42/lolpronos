<?php include '../db_connect.php';
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	$id_user = $_SESSION["id_user"];
	
		// get latest gameweek
	$sql_lgw = "select max(id_gameweek) as latest
		from lp_player_points_gameweek
		where nb_points is not null";
	$res_lgw = mysqli_query($conn, $sql_lgw);
	$row_lgw = mysqli_fetch_assoc($res_lgw);
	$latest = $row_lgw["latest"]; 
	
	if (isset($_GET["id_gw"]))
	{
		$id_gw = $_GET["id_gw"];
	}
	else
	{
		$id_gw = $latest;
	}
	include 'header.php';
	print_header("Classement par journée");
?>
<body>
<?php include("menu.php");

if ($id_gw == 1)
{
	$previous_gw = "";
}
else
{
	$id_previous = $id_gw - 1;
	$previous_gw = "<a href=\"?id_gw=$id_previous\">[précédente]</a>";
}
if ($id_gw == $latest)
{
	$next_gw = "";
}
else
{
	$id_next = $id_gw + 1;
	$next_gw = "<a href=\"?id_gw=$id_next\">[suivante]</a>";
}

function get_ranks($conn, $id_gameweek) {
	$sql1 = "select u.id_user, u.name_user, sum(nb_points) as points
	        from lp_user u
			left join lp_player_points_gameweek po
			on po.id_player=u.id_user and po.id_gameweek<=$id_gameweek
			where b_admin=0
			group by u.name_user
			order by 3 desc";

	$res1 = mysqli_query($conn, $sql1) or die("Error");
	$numrows = mysqli_num_rows($res1);
	
	$rank = 1;
	$nb_player = 1;
	$previous_player_points = -1;

	if ($numrows > 0)
	{
		while($row = mysqli_fetch_assoc($res1))
		{
			$id_player = $row["id_user"];
			$name_user = $row["name_user"];
			$points = round($row["points"], 3);
			if ($points < $previous_player_points)
			{
				$rank = $nb_player;
			}
			
			$ranks_data[$id_player]["rank"] = $rank;
			$ranks_data[$id_player]["name"] = $name_user;
			$ranks_data[$id_player]["points"] = $points;
			
			$nb_player++;
			$previous_player_points = $points;
		}	
	}
	
	// retrieve previous rank if applicable
	if ($id_gameweek > 1)
	{
		$id_prev_gameweek = $id_gameweek - 1;
		$sql2 = "select u.id_user, u.name_user, sum(nb_points) as points
				from lp_user u
				left join lp_player_points_gameweek po
				on po.id_player=u.id_user and po.id_gameweek<=$id_prev_gameweek
				where b_admin=0
				group by u.name_user
				order by 3 desc";

		$res2 = mysqli_query($conn, $sql2) or die("Error");
		$numrows = mysqli_num_rows($res2);
		
		$rank = 1;
		$nb_player = 1;
		$previous_player_points = -1;

		if ($numrows > 0)
		{
			while($row = mysqli_fetch_assoc($res2))
			{
				$id_player = $row["id_user"];
				$points = round($row["points"], 3);
				if ($points < $previous_player_points)
				{
					$rank = $nb_player;
				}
				
				$ranks_data[$id_player]["prev_rank"] = $rank;
				
				$nb_player++;
				$previous_player_points = $points;
			}	
		}
	}
	
	return $ranks_data;
}

?>
	<div class="player_name">Classement journée <?php echo $id_gw; ?></div>
	<div class="journee"><?php echo $previous_gw; ?><?php echo $next_gw; ?></div>
	<br>
	<table>
	<tr>
<th <?php if ($id_gw > 1) { echo "colspan=2"; } ?>>#</th><th>Joueur</th><th>Points</th></tr>
<?php
	$ranks_data = get_ranks($conn, $id_gw);
	foreach ($ranks_data as $id_player => $data)
	{
		$rank = $data["rank"];
		$prev_rank = $data["prev_rank"];
		$name_user = $data["name"];
		$points = $data["points"];

		if ($id_gw > 1)
		{
			if ($prev_rank == $rank)
			{
				$sign = "same";
			}
			else if ($prev_rank > $rank)
			{
				$sign = "green_arrow";
			}
			else // $prev_rank < $rank
			{
				$sign = "red_arrow";
			}
			$txt_sign = "<img src=\"img/$sign.png\">";
		}
?>
			<tr>
				<td><?php echo $rank; ?></td>
				<?php if ($id_gw > 1) { echo "<td>".$txt_sign."</td>"; } ?>
				<td class="team_name"><a href="/view_player_history.php?id=<?php echo $id_player; ?>"><?php echo $name_user; ?></a></td>
				<td><?php echo $points; ?></td>
			</tr>
<?php
	}
?>
	</table>
	</body>
</html>
