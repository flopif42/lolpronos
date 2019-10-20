<?php
// Check if player has already chosen a winner
	$sql_1 = "select winning_team
	from lp_user
	where id_user='$id_user'
	and winning_team is not null";

	$res_1 = mysqli_query($conn, $sql_1);

	// a champion has been chosen
	if (mysqli_num_rows($res_1) > 0)
	{
		$row_1 = mysqli_fetch_assoc($res_1);
		$id_champion = $row_1["winning_team"];
	}
	else
	{
		$id_champion = -1;
	}

	// Is there still time to make the pronostic ?
	$sql_2 = "select now() ".$timezone_offset." as mysql_now";
	$res_2 = mysqli_query($conn, $sql_2);
	$row_2 = mysqli_fetch_assoc($res_2);
	$timestamp_now = strtotime($row_2["mysql_now"]);
	
	// Fill the list with teams and odds
	$sql_3 = "select id_team, name_team, TRUNCATE(winning_odds, 2) as odds
	from lp_team
	order by winning_odds";
	$res_3 = mysqli_query($conn, $sql_3);
	$timestamp_1st_game = strtotime("2019-10-12 14:00:00");

	if ($timestamp_now - $timestamp_1st_game > 0)
	{
		$expired_champion_prono = TRUE;
	}
	else
	{
		$expired_champion_prono = FALSE;
	}
		
?>
Pronostic du vainqueur : 
<?php
if ($expired_champion_prono)
{
	if ($id_champion == -1)
	{
		echo  "Non sélectionné<br>";
	}
	else
	{
		$sql_4 = "select name_team, winning_odds as odds
		from lp_team
		where id_team='$id_champion'";
		$res_4 = mysqli_query($conn, $sql_4);
		$row_4 = mysqli_fetch_assoc($res_4);
		echo "<div class=\"winner\">".$row_4["name_team"]." - ".$row_4["odds"]."</div><br>";
	}
}
else
{
?>
	<select name="champion">
		<option <?php if ($id_champion == -1) { echo "selected"; } ?> value="null" disabled> - </option> <!-- no selection yet -->
<?php
			while($row_3 = mysqli_fetch_assoc($res_3))
			{
				$id_team = $row_3["id_team"];
				$name_team = $row_3["name_team"];
				$odds = $row_3["odds"];
?><option <?php if ($id_champion == $id_team) { echo "selected"; } ?> value="<?php echo $id_team; ?>"><?php echo $name_team;  ?> - <?php echo $odds; ?></option><?php
			}
?></select><br><?php
}
?>
<br>
