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
	
	$sql_teams = "select *
	              from lp_team
				  order by lower(name_team)";
	$result_teams = mysqli_query($conn, $sql_teams);
	while($row_teams = mysqli_fetch_assoc($result_teams))
	{
		$teams[$row_teams["id_team"]] = $row_teams["name_team"];
	}
	
?>
<html>
	<head>
		<title>Saisir un match</title>
	</head>
	<body>
	<?php include("menu.php");?>
	
		<form method="post" action="game_create_req.php">
			<table border=1>
				<tr>
					<td>Date et heure (dd/mm/yyyy hh:mm)<input name="datetime" type="text"></td>
					<td>BO:<select name="bo">
							<option value="BO1">Best of 1</option>
							<option value="BO3">Best of 3</option>
							<option value="BO5">Best of 5</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Equipe A<select name="team_a">
						<?php
						foreach($teams as $key => $value)
						{
							echo "<option value=\"$key\">$value</option>";
						}
						?>
					</select></td><td>Odds A<input name="odds_a" type="text"></td>
				</tr>
				<tr>
					<td>Equipe B<select name="team_b">
						<?php
						foreach($teams as $key => $value)
						{
							echo "<option value=\"$key\">$value</option>";
						}
						?>
					</select></td><td>Odds B<input name="odds_b" type="text"></td>
				</tr>
			</table>
			<input type="submit" value="Enregistrer">
		</form>
	</body>
</html>
