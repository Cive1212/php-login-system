<!DOCTYPE HTML>
<?php
 SESSION_START();
 if(!isset($_SESSION['zalogowany']))
 {
 header('Location:index.php');
 exit();
 }
?>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Osadnicy</title>
</head>

<body>

<?php

echo "<p>witaj ".$_SESSION['user']."!</p>";
echo "twoje zasoby to:<br/>";
echo "<p>drewno:".$_SESSION['drewno']." | kamien:".$_SESSION['kamien']." | zboże:".$_SESSION['zboze']."</p>";
echo "<br></br> email:".$_SESSION['email'];
echo "<br/> dni premium:".$_SESSION['dnipremium'];


?>
<form action="logout.php">
<input type="submit" value="logout"/>
</form>
</body>
</html>