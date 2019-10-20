<?php 

	function print_header($page_title)
	{
?>
<html>
		<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-795564-8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-795564-8');
</script>
			<link rel="icon" type="image/png" href="img/logo.png" />
			<link rel="stylesheet" type="text/css" href="css/style.css">
			<title>Lol pronos - 
<?php echo $page_title; ?>
</title>
		</head>
<?php
		
	}

	// Create the listbox input for BO1 games
	function print_bo1($id_game, $score_team_1, $score_team_2) {
		$score = $score_team_1.$score_team_2;
		
		$opt_00 = "";
		$opt_10 = "";
		$opt_01 = "";
		
		if ($score == "00")
		{
			$opt_00 = "selected";
		}
		else if ($score == "10")
		{
			$opt_10 = "selected";
		}
		else if ($score == "01")
		{
			$opt_01 = "selected";
		}
		
		echo "<td>
			<select name=\"g".$id_game."\">
				<option $opt_00 value=\"null\" disabled> - </option>
				<option $opt_10 value=\"10\">1 - 0</option>
				<option $opt_01 value=\"01\">0 - 1</option>
			</select>
			</td>";
	}
	
	// Create the listbox input for BO3 games
	function print_bo3($id_game, $score_team_1, $score_team_2) {
		$score = $score_team_1.$score_team_2;
		
		$opt_00 = "";
		$opt_20 = "";
		$opt_21 = "";
		$opt_02 = "";
		$opt_12 = "";
		
		if ($score == "00")
		{
			$opt_00 = "selected";
		}
		else if ($score == "20")
		{
			$opt_20 = "selected";
		}
		else if ($score == "21")
		{
			$opt_21 = "selected";
		}
		else if ($score == "02")
		{
			$opt_02 = "selected";
		}
		else if ($score == "12")
		{
			$opt_12 = "selected";
		}
		
		echo "<td>
			<select name=\"g".$id_game."\">
				<option $opt_00 value=\"null\" disabled> - </option>
				<option $opt_20 value=\"20\">2 - 0</option>
				<option $opt_21 value=\"21\">2 - 1</option>
				<option $opt_02 value=\"02\">0 - 2</option>
				<option $opt_12 value=\"12\">1 - 2</option>
			</select>
			</td>";
	}
	
	// Create the listbox input for BO5 games
	function print_bo5($id_game, $score_team_1, $score_team_2) {
		$score = $score_team_1.$score_team_2;
		
		$opt_00 = "";
		$opt_30 = "";
		$opt_31 = "";
		$opt_32 = "";
		$opt_03 = "";
		$opt_13 = "";
		$opt_23 = "";
		
		if ($score == "00")
		{
			$opt_00 = "selected";
		}
		else if ($score == "30")
		{
			$opt_30 = "selected";
		}
		else if ($score == "31")
		{
			$opt_31 = "selected";
		}
		else if ($score == "32")
		{
			$opt_32 = "selected";
		}
		else if ($score == "03")
		{
			$opt_03 = "selected";
		}
		else if ($score == "13")
		{
			$opt_13 = "selected";
		}
		else if ($score == "23")
		{
			$opt_23 = "selected";
		}
		
		echo "<td>
			<select name=\"g".$id_game."\">
				<option $opt_00 value=\"null\" disabled> - </option>
				<option $opt_30 value=\"30\">3 - 0</option>
				<option $opt_31 value=\"31\">3 - 1</option>
				<option $opt_32 value=\"32\">3 - 2</option>
				<option $opt_03 value=\"03\">0 - 3</option>
				<option $opt_13 value=\"13\">1 - 3</option>
				<option $opt_23 value=\"23\">2 - 3</option>
			</select>
			</td>";
	}

?>
