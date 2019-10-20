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
	
	// select all pronostics that need to be updated
	$sql = "select p.id_game, p.id_player, ifnull(p.prono_team_a,0) as prono_a, ifnull(p.prono_team_b,0) as prono_b,
	               g.score_team_a as score_a, g.score_team_b as score_b,
				   odds_team_a as odds_a, odds_team_b as odds_b,
				   bo_number
			from lp_pronostic p
			join lp_game g
			on p.id_game=g.id_game
			where points is null
			and g.score_team_a is not null";
	
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$id_game = $row["id_game"];
			$id_player = $row["id_player"];
			$prono_team_a = $row["prono_a"];
			$prono_team_b = $row["prono_b"];
			$score_team_a = $row["score_a"];
			$score_team_b = $row["score_b"];
			$odds_team_a = $row["odds_a"];
			$odds_team_b = $row["odds_b"];
			$bo_number = $row["bo_number"];
	
			// check which team is winner
			if ($score_team_a > $score_team_b)
			{
				$winner = "team_a";
				$odds_winner = $odds_team_a;
			}
			else
			{
				$winner = "team_b";
				$odds_winner = $odds_team_b;
			}
	
			// check if prono is correct
			if ($prono_team_a > $prono_team_b)
			{
				$pronosticked_winner = "team_a";
			}
			else if ($prono_team_b > $prono_team_a)
			{
				$pronosticked_winner = "team_b";
			}
			else
			{
				$points = 0;
			}
			
			// if pronostic is right
			if ($winner == $pronosticked_winner)
			{
				$points = $odds_winner;
				
				// when not BO1, check exact score for doubling points
				if (($bo_number != "BO1") && ($score_team_a == $prono_team_a) && ($score_team_b == $prono_team_b))
				{
					$points = $points * 2;
				}
			}
			else
			{
				$points = 0;
			}
	
			echo "game : $id_game, player : $id_player, prono : $prono_team_a - $prono_team_b, result $score_team_a - $score_team_b, points : $points <br>";
			
			$sql = "update lp_pronostic
				set points=$points
				where id_game=$id_game and id_player=$id_player";
			mysqli_query($conn, $sql);
		}
	}
	header('Location: /game_validate_frm.php');
?>
