<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\modif_visualisation.php_0");
	
	session_start();
	require_once("class.php");
//	require_once("functions.php");
	
	
	echo "coucou Modif_visu";
	
	
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




	/////////////////////////////
	// Si modif des options de visualisation	
	if( isset( $_GET['modifjfwe'] ) )
	{
		echo "reception : ModifJfWe<br/>";
		
		//$client = $_GET['client'];
		if( $_GET['we'] == "we_oui")
		{
			echo "WE : we_oui<br/>";
			
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$appli->stat->liste_clients[$i]->compte_we = 1;
				$i++;
			}
			$_SESSION['force_we'] = 1;
		}
		else
		{
			echo "WE != we_oui<br/>";
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$client = $appli->stat->liste_clients[$i];
				$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT compte_we FROM clients WHERE client='$client'"));

				if( $donnees['compte_we'] == 1 )
					$client->compte_we = 1;
				else
					$client->compte_we = 0;
				
				$i++;
			}
			$_SESSION['force_we'] = 0;
		}		

		
		if( $_GET['jf'] == "jf_oui" )
		{
			echo "JF = jf_oui<br/>";
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$appli->stat->liste_clients[$i]->compte_jf = 1;
				$i++;
			}
			$_SESSION['force_jf'] = 1;
		}
		else
		{
			echo "JF != jf_oui<br/>";
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$client = $appli->stat->liste_clients[$i];
				$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT compte_jf FROM clients WHERE client='$client'"));

				if( $donnees['compte_jf'] == 1 )
					$client->compte_jf = 1;
				else
					$client->compte_jf = 0;
				
				$i++;
			}
			$_SESSION['force_jf'] = 0;
		}
		
		if( $_GET['deploy'] == "deploy_oui" )
		{
			echo "Deploy = Oui<br/>";
			$_SESSION['deploy_result'] = 1;
		}
		else
		{
			echo "Deploy = Non<br/>";
			$_SESSION['deploy_result'] = 0;
		}
		
	}
	else
		echo 'ModifJfWe = non';
	
	$_SESSION['appli'] = serialize( $appli );
	$_SESSION['groupe'] = serialize( $groupe );
	$_SESSION['tab_date_min'] = serialize( $tab_date_min );
	$_SESSION['tab_date_max'] = serialize( $tab_date_max );
	
	?>