<!DOCTYPE HTML>
<?php
SESSION_START();

 if(isset($_POST['mail']))
 {
	 $success=true;
	 
	 //nickname
	 $nick=$_POST['nick'];
	 
	 if((strlen($nick)<3) or (strlen($nick)>20))
	 {
		$success=false;
		$_SESSION['err_nick']="Name must contain from 3 to 20 characters!";
	 }
	 
	 if(ctype_alnum($nick)==false)
	 {
		 $success=false;
		 $_SESSION['err_nick']="Name cannot contain any special characters!";
	 }
	 
	 //mail
	 
	 $email=$_POST['mail'];
	 $safe_email=filter_var($email,FILTER_SANITIZE_EMAIL);
	 
	 if((filter_var($safe_email,FILTER_VALIDATE_EMAIL)==false) or ($email!=$safe_email))
	 {
		 $success=false;
		 $_SESSION['err_mail']="email is incorect!";
	 }
	 
	 //password
	 
	 $password=$_POST['haslo1'];
	 $password2=$_POST['haslo2'];
	 
	 if ((strlen($password)<8) or (strlen($password)>16))
	 {
		 $success=false;
		 $_SESSION['err_pass']="password must contain from 8 to 16 characters!";
	 }
	 
	 if ($password!=$password2)
	 {
		 $success=false;
		 $_SESSION['err_pass']="password's does not match!";
	 }
	 
	 $hashed_pass= password_hash($password,PASSWORD_DEFAULT);
	 
	 //regulamin
	 
	 if(!isset($_POST['regulamin']))
	 {
		 $success=false;
		 $_SESSION['err_rules']="you must accept our terms!";
	 }
	 
	 // captcha
	 
	 $key="6LdMdPkaAAAAAMxJSvhm7BMRfiTbq5Bt7hWI4C7a";
	 $captcha_check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$key.'&response='.$_POST['g-recaptcha-response']);
	 
	 $result = json_decode($captcha_check);
	 
	 if ($result->success==false)
	 {
		 $success=false;
		 $_SESSION['err_captcha']="are you robot sir?";
	 }
	 
	 require_once "connect.php";
	 mysqli_report(MYSQLI_REPORT_STRICT);
	 
	 try
	 {
		 $connect = new mysqli($host, $db_user, $db_password, $db_name);
	
		if($connect->connect_errno!=0)
		{
			throw new Exception(mysqli_connect_errno());
		}
		else
		{
			//sprawdzenie czy w bazie jest juz taki email
			$result = $connect->query("SELECT id FROM uzytkownicy WHERE email='$email'");
			
			if(!$result) throw new Exception($connect->error);
			
			$mail_numbers = $result->num_rows;
			if($mail_numbers>0)
			{
				$success=false;
				$_SESSION['err_mail']="Email is bussy";
			}
			
			//sprawdzenie czy w bazie jest juz taki login
			$result = $connect->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
			
			if(!$result) throw new Exception($connect->error);
			
			$nick_numbers = $result->num_rows;
			if($nick_numbers>0)
			{
				$success=false;
				$_SESSION['err_nick']="Nickname is bussy";
			}
			
			 if ($success==true)
			 {
				 $result = $connect->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$hashed_pass', '$email', 100, 100, 100, 10)");
				 if(!$result)
				 {
					 throw new Exception($connect->error);
				 }
				 else
				 {
					 $_SESSION['registersucc']=true;
					 header('Location:welcome.php');
				 }
			 }
			
			$connect->close();
		}
	 }
	 
	 catch(Exception $e)
	 {
		 echo'<span style="color:red;">An error occured please try again later</span>';
		 echo'<br/>dev info: '.$e;
	 }
 }
?>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Osadnicy- rejestracja</title>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	
	<style>
	.error
	{
		color:red;
		margin-top: 10px;
		margin-borrom: 10px;
	}
	</style>
</head>

<body>
<form method="post">

Nickname: <br/> <input type="text" name="nick"><br/>

<?php
if (isset($_SESSION['err_nick']))
{
	echo'<div class="error">'.$_SESSION['err_nick'].'</div>';
	unset($_SESSION['err_nick']);
}
?>

e-mail: <br/> <input type="text" name="mail"><br/>

<?php
if (isset($_SESSION['err_mail']))
{
	echo'<div class="error">'.$_SESSION['err_mail'].'</div>';
	unset($_SESSION['err_mail']);
}
?>

password: <br/> <input type="password" name="haslo1"><br/>


repeat your password: <br/> <input type="password" name="haslo2"><br/>

<?php
if (isset($_SESSION['err_pass']))
{
	echo'<div class="error">'.$_SESSION['err_pass'].'</div>';
	unset($_SESSION['err_pass']);
}
?>
<label>
<input type="checkbox" name="regulamin"> do you accept our terms?
</label>
<?php
if (isset($_SESSION['err_rules']))
{
	echo'<div class="error">'.$_SESSION['err_rules'].'</div>';
	unset($_SESSION['err_rules']);
}
?>
<div class="g-recaptcha" data-sitekey="6LdMdPkaAAAAAHpGKtbn8j-1-FF5truOzbOjXrL_"></div>
<?php
if (isset($_SESSION['err_captcha']))
{
	echo'<div class="error">'.$_SESSION['err_captcha'].'</div>';
	unset($_SESSION['err_captcha']);
}
?>
<br/>
<input type="submit" name="zarejestruj"> </input>
</form>
</body>
</html>