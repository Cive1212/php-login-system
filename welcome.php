<!DOCTYPE HTML>
<?php
SESSION_START();
if(!isset($_SESSION['registersucc']))
{
	header('Location: index.php');
	exit();
}
else
{
	unset($_SESSION['registersucc']);
}
?>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Osadnicy</title>
</head>

<body>

thanks for registering<br></br>

<a href="index.php">Login</a>
<br></br>



</body>
</html>