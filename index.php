<!DOCTYPE HTML>
<?php
SESSION_START();
if(isset($_SESSION['zalogowany']) and ($_SESSION['zalogowany']=true))
{
	header('Location: gra.php');
	exit();
}
?>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Osadnicy</title>
</head>

<body>

abcdefg<br></br>

<a href="rejestracja.php">Nie posiadasz konta? Zarejestruj się!</a>
<br></br>

<form action="login.php" method="post">
	Login: <br/>
<input type="text" name="login"/>
<br></br>
	Password: <br/>
<input type="password" name="password"/>
<br></br>
<input type="submit" value="zaloguj sie" />
</form>
<?php
if(isset($_SESSION['error']))
echo $_SESSION['error'];

?>

</body>
</html>