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
?>

<html>
	<head link rel="icon" type="image/png" href="img/taric.png" />
		<title>Valider un match</title>
	</head>
	<body>
	<?php include("menu.php"); ?>
	
<?php
	$sql = "select id_game, datetime_game, ta.abrev_team as teama, tb.abrev_team as teamb
			from lp_game g
			join lp_team ta
			on g.id_team_a=ta.id_team
			join lp_team tb
			on g.id_team_b=tb.id_team
			where now() ".$timezone_offset." > datetime_game
			and g.score_team_a is null
			order by datetime_game";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$id_game = $row["id_game"];
			$name_team_a = $row["teama"];
			$name_team_b = $row["teamb"];
			$timestamp_game = strtotime($row["datetime_game"]);
			$date_time = date("d/m/y H:i", $timestamp_game);
			
?>
<div>
<form method="post" action="game_validate_req.php">
<input name="id_game" type="hidden" value="
<?php echo $id_game; ?>
"><?php echo $date_time; ?>
<input size=3 type="text" name="ra">
<?php echo $name_team_a." vs ".$name_team_b; ?>
<input size=3 type="text" name="rb">
<input type="submit" value="Valider">
</form>
</div>
<?php
		}
	}
	?>
	</body>
</html>
