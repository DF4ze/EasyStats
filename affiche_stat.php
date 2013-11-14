<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\affiche_stat.php_0");
 session_start();
	require_once("class.php");
	
	
	//echo "coucou affich stat : ".$_GET['valider'];
	
	
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

		

		
	///////////////////////////////
	//	Si Valider : Ajout des clients s�lectionn� aux tableaux temporaires.
	if( isset($_GET['valider']) or isset($_GET['ajouter']))
	{
		// Par contre il faut remettre le tableau "temporaire" a Zero.
		if( isset($_GET['valider']))
		{
			$groupe->liste_clients->vider();
			$tab_date_max->vider();
			$tab_date_min->vider();
		}
		
		$i = 0;
		while( $i < $appli->stat->liste_clients->count() )
		{
			$client = $appli->stat->liste_clients[$i];
			$nom_check_box = $client->nick.'_checkbox';
			
			if( isset( $_GET["$nom_check_box"] ) )
			{
				$groupe->add_client($client);
				$client->is_select = true;
				$tab_date_min->add( $_SESSION['DateMin'] );
				$tab_date_max->add( $_SESSION['DateMax'] );
				
			}
			else
				$client->is_select = false;
				
			$i++;
		}
	}	
		
		
		
	if( !isset( $_SESSION['DateMin'] ) or !isset( $_SESSION['DateMax'] ) or $_SESSION['DateMin'] == ""  or $_SESSION['DateMax'] == "")
	{
		// echo '<div class="centre"><div class="cadre"><br/><h2>Merci de selectionner un intervalle de 2 dates.</h2></div></div>';
		// echo '<br/><br/><br/><br/><br/><br/><br/>';
	}
	else
	{
		if( !$appli->stat->have_equipes_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] ) )
		{
			//echo '<div class="centre"><div class="cadre"><br/><h2>Il n\'y a pas de clients pour les dates demand�es.</h2></div></div>';
		}
		else
		{
			// Si le groupe possede des clients ... c'est qu'il a �t� demand� des stats.
			if( $appli->stat->is_clients_select() != 0 )
			{
				$ma_requete = new requete();

				$i =0;
				while( $i < $groupe->liste_clients->count() )
				{
					$ma_requete->add_requete( $groupe->liste_clients[$i], new datetime( $tab_date_min[$i] ), new datetime( $tab_date_max[$i] ));
					$i++;
				}
		
				$le_resultat = new ClassArray();
				$ma_requete->get_stats($le_resultat);
	 
				$ma_requete->show_result( $le_resultat );
			}
		}	
	}
	
	$_SESSION['appli'] = serialize( $appli );
	$_SESSION['groupe'] = serialize( $groupe );
	$_SESSION['tab_date_min'] = serialize( $tab_date_min );
	$_SESSION['tab_date_max'] = serialize( $tab_date_max );

	?>