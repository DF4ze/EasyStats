<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\affiche_requete.php_0");

	session_start();
	require_once("class.php");

	// R�cup�ration des classes.
	$appli = unserialize( $_SESSION['appli'] );
	$groupe = new gp_clients();
	if( isset( $_SESSION['groupe'] ) )
		$groupe = unserialize( $_SESSION['groupe'] );

	$tab_date_min = new ClassArray();
	if( isset( $_SESSION['tab_date_min'] ) )
		$tab_date_min = unserialize( $_SESSION['tab_date_min'] );
		
	$tab_date_max = new ClassArray();
	if( isset( $_SESSION['tab_date_max'] ) )
		$tab_date_max = unserialize( $_SESSION['tab_date_max'] );


 	// Connexion aux bases de donn�es
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur MySql impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql impossible");
	
	$connectionInfo = array( "UID"=>$_SESSION['odbc_user'], "PWD"=>$_SESSION['odbc_pwd'], "Database"=>$_SESSION['odbc_base']);
	$_SESSION['odbc_connect'] = sqlsrv_connect( "frsv001355", $connectionInfo);
	if( $_SESSION['odbc_connect'] === false )
	{
		echo "Impossible de se connecter � la base 7480.</br>";
		die( print_r( sqlsrv_errors(), true));
	}
 	
	
	
	echo '<div class="requetecours" id="requete_cours"> <strong>Requ&#234;te en cours</strong><br/>';
	if( $appli->stat->is_clients_select() )
	{
		$ma_requete = new requete();
	
		$i =0;
		while( $i < $groupe->liste_clients->count() )
		{
			$ma_requete->add_requete( $groupe->liste_clients[$i], new datetime( $tab_date_min[$i] ), new datetime( $tab_date_max[$i] ));
			$i++;
		}
	
		if( !isset( $_GET['total_requete'] ) )
			$ma_requete->resume_requete(3);
		else
			$ma_requete->resume_requete();
			
		echo '<form id="reset" method="post" action="stat.php" >';
		if( !isset( $_GET['total_requete'] ) )
			echo '<a href="#" title="Afficher la requ&#234;te compl�te" onclick="affiche_requete(\'all\');return false;">...</a> ';
		echo '<input type="submit" name="reset" value="R&#233;initialiser"/>';
		echo '</form>';
	}
	else
		echo "Il n'y a pas de requ&#234;te en cours.<br/>";
	echo '</div>';
	
	phpmv2();
?>