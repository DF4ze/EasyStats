<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\alert.php_0");

	session_start();
	require_once("class.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Easy Stat - Alerte !</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/esico.ico" />
	<link rel="icon" type="image/png" href="images/esico.ico" />
</head>
<body>
<?php		
	echo '<div class="cadre" style="padding-left:10px">';
		echo "<h1>!! Votre attention !!</h1>".
		"Le dernier appel enregistr� est vieux de ".NumToHour( $_GET['time'] )." s.<br/>".
		"Il est susceptible qu'il y ait un probl�me d'enregistrement des appels dans la base SQL.<br/><br/>";
		echo "Informations techniques : <br/>";
			echo "- Serveur : ".$_SESSION['odbc_serveur']."<br/>";
			echo "- Table   : ".$_SESSION['odbc_base']."<br/>";
	echo '</div>';

	phpmyvisit();

?>

</body>
</html>