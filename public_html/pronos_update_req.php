<?php include '../db_connect.php'; 
	session_start();
	if ($_SESSION["id_user"] == "") {
		header('Location: /');
		exit;
	}
	
	$id_user = $_SESSION["id_user"];
	//echo $id_user;
	foreach($_POST as $key => $value)
	{
		// update the pronostic of the champion
		// make sure to disable the update function after the tournament starts.
		if ($key == "champion")
		{
			continue;

			/* if ($value != "null")
			{
				$sql_winner = "update lp_user set winning_team='$value'
				where id_user='$id_user'";
				$result = mysqli_query($conn, $sql_winner); 
				if (!$result)
				{
					printf("error: %s\n", mysqli_error($conn));
				}
			}
			continue; */
		}
		
		if ($value == "null")
		{
			continue;
		}
		
		$id_game = substr($key, 1);
		$prono_team_a = substr($value, 0, 1);
		$prono_team_b = substr($value, 1);
		
		//echo "Game id : $id_game, prono : $score_team_a - $score_team_b <br>";
		
		// check that the pronostic is made before the game schedule
		$sql_time = "select datetime_game - (now() ".$timezone_offset.") as remaining
					 from lp_game
					 where id_game=$id_game";
		$result_time = mysqli_query($conn, $sql_time);
		$row_time = mysqli_fetch_assoc($result_time);
		if (($_SESSION["admin"] != 1) && ($row_time["remaining"] < 0))
		{
			continue;
		}
		
 		// check if need insert or update
		$sql = "select id_game
		        from lp_pronostic
				where id_player=$id_user
				and id_game=$id_game";
		//echo $sql;
		
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0)
		{
			// update
			$sql = "update lp_pronostic
			set prono_team_a=$prono_team_a, prono_team_b=$prono_team_b
			where id_game=$id_game and id_player=$id_user";
		}
		else
		{
			// insert
			$sql = "insert into lp_pronostic (id_game, id_player, prono_team_a, prono_team_b)
			values ($id_game, $id_user, $prono_team_a, $prono_team_b)";
		}
		mysqli_query($conn, $sql); 
	}	
	header('Location: /pronos_update_frm.php?msg=1');	
?>

