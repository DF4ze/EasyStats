<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\calendrier.php_3");
	session_start();
		require_once("../functions.php");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Easy-Stat</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design_admin.css" />
	<link rel="shortcut icon" type="image/x-icon" href="../images/esico.ico" />
	<link rel="icon" type="image/png" href="../images/esico.ico" />

	<script type="text/javascript">
	function montre_div(nom_div)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\calendrier.php_2");

		if( document.getElementById(nom_div).style.display == "none" )
			document.getElementById(nom_div).style.display="block";
		else
			document.getElementById(nom_div).style.display="none";
	}
	function Couleur() 
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\calendrier.php_1");

		fenetreA=window.open("couleurs.php","Couleur","status=no,location=no,toolbar=no,directories=no,resizable=yes,left=100,top=10,width=200,height=200,top=100,left=100");
		fenetreA.focus();
	} 
	function modif_couleur(value)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\calendrier.php_0");

		document.getElementById("color").value = value;
		color = "#"+value;
		document.getElementById("area").style.backgroundColor = color;
		
	}
	</script>

</style>

</head>
<body>
</body>