<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\conf.php_0");

	session_start();
	
	// Serveur Central
	$_SESSION['bd_serveur'] = "10.210.137.15";
	$_SESSION['bd_user'] = "easy-stat";
	$_SESSION['bd_pwd'] = "easy-stat";
	$_SESSION['bd_base'] = "class_stats";
	
	// Serveur BackUp
	$_SESSION['bd_serveur_bk'] = "localhost";
	$_SESSION['bd_user_bk'] = "root";
	$_SESSION['bd_pwd_bk'] = "";
	$_SESSION['bd_base_bk'] = "class_stats";

	// M$ sql
	$_SESSION['odbc_serveur'] = "frsv001355";
	$_SESSION['odbc_user'] = "stat7480";
	$_SESSION['odbc_pwd'] = "stat7480";
	$_SESSION['odbc_base'] = "NCP7480";

	// variable qui permet de forcer la connexion a la base locale plutot que la g�n�rale/centrale.
	$_SESSION['force_local_mysql'] = true;
?>