<?php
	session_start();
	if((!isset($_POST['login'])) || (!isset($_POST['password'])))
	{
		header('Location:index.php');
		exit();
	}
	require_once "connect.php";
	
	$connect = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if($connect->connect_errno!=0)
	{
		echo "Error".$connect->connect_errno. "Description:". $connect->connect_error;
	}
	else
	{
		$login =$_POST['login'];
		$password =$_POST['password'];
		
		$login = htmlentities($login,ENT_QUOTES, "UTF-8");
		if ($result = @$connect->query(
			sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",
		mysqli_real_escape_string($connect,$login))));
		{
			$user_numb = $result->num_rows;
			if($user_numb >0)
			{
				$row = $result->fetch_assoc();
				
				if (password_verify($password,$row['pass']))
				{
					$_SESSION['zalogowany'] = true;
					$_SESSION['id'] = $row['id'];
					$_SESSION['user'] = $row['user'];
					$_SESSION['drewno'] = $row['drewno'];
					$_SESSION['kamien'] = $row['kamien'];
					$_SESSION['zboze'] = $row['zboze'];
					$_SESSION['email'] = $row['email'];
					$_SESSION['dnipremium'] = $row['dnipremium'];
					
					unset($_SESSION['error']);
					$result->free_result();
					header('Location: gra.php');
				}
				else
				{
					$_SESSION['error'] = '<span style="color:red">  no user with this id and password combination was found</span>';
					header('Location: index.php');
				}	
			}
			else
			{
				$_SESSION['error'] = '<span style="color:red">  no user with this id and password combination was found</span>';
				header('Location: index.php');
			}	
			
		}
		
		$connect->close();
	}
?>