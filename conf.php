<?php
	session_start();

	require_once("class_requete.php");
	require_once("class_client.php");
	require_once("class_equipe.php");
		
/* 	// Serveur Central
	$_SESSION['bd_serveur'] = "10.210.137.15";
	$_SESSION['bd_user'] = "easy-stat";
	$_SESSION['bd_pwd'] = "easy-stat";
	$_SESSION['bd_base'] = "class_stats";
 */	


	// Pour les tests :
	$_SESSION['bd_serveur'] = "localhost";
	$_SESSION['bd_user'] = "root";
	$_SESSION['bd_pwd'] = "";
	$_SESSION['bd_base'] = "es_v5";


	// Serveur BackUp
	$_SESSION['bd_serveur_bk'] = "localhost";
	$_SESSION['bd_user_bk'] = "root";
	$_SESSION['bd_pwd_bk'] = "";
	$_SESSION['bd_base_bk'] = "es_v5";

	// M$ sql
/* 	$_SESSION['odbc_serveur'] = "frsv001355";
	$_SESSION['odbc_user'] = "stat7480";
	$_SESSION['odbc_pwd'] = "stat7480";
	$_SESSION['odbc_base'] = "NCP7480";

 */	$_SESSION['odbc_serveur'] = "frsv001355";
	$_SESSION['odbc_user'] = "SA";
	$_SESSION['odbc_pwd'] = "SAncp7480";
	$_SESSION['odbc_base'] = "NCP7480";

	// Nom des tables:
	$_SESSION['clients'] = 'clients';
	$_SESSION['erreurs'] = 'errors';

	// variable qui permet de forcer la connexion a la base locale plutot que la générale/centrale.
	$_SESSION['force_local_mysql'] = true;
	// Debug mode
	$_SESSION['debug_mode'] = true;
	
	//////////////////////////
	// variables par defaut.
	///////////////////////////
	
	// Client
	$_SESSION['h_ouverture'] = '07:30'; // Doivent couvrir la plage la plus large
	$_SESSION['h_fermeture'] = '20:00'; // 
	$_SESSION['borne_min_decro'] = 20;
	$_SESSION['borne_max_decro'] = 30;
	$_SESSION['borne_min_abandons'] = 20;
	$_SESSION['borne_max_abandons'] = 30;
	$_SESSION['work_we'] = false;
	$_SESSION['work_jf'] = false;
	$_SESSION['equipe_defaut'] = 'NoTeam';
	
	// Requete
	$_SESSION['samedi'] = 6;
	$_SESSION['dimanche'] = 7;
	// Préfixe des tables de cache, s'y rajoutera le nom du client.
	$_SESSION['prefix_cache'] = 'cache_';
	
	// Délimiteur du code requete.
	$_SESSION['delim_instruct_debut'] 	= '[';
	$_SESSION['delim_instruct_fin'] 	= ']';
	$_SESSION['delim_options'] 			= ';';
	
	// Seuil défaut temps de communication non compté dans les moyennes / sommes
	$_SESSION['seuilmoytpscom'] = 5; // en seconde.
	
	// Nom des tables :
	$_SESSION[ 'tb_clients' ] = "es_clients";
	$_SESSION[ 'tb_equipes' ] = "es_equipes";
	
	
?>