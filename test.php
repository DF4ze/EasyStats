<?php 	session_start();	
		require_once("conf.php");

		
		
		///////////////////////////////////////////////////////
		// Connexion Ms SQL
		$connectionInfo = array( "UID"=>$_SESSION['odbc_user'], "PWD"=>$_SESSION['odbc_pwd'], "Database"=>$_SESSION['odbc_base']);
		$_SESSION['odbc_connect'] = sqlsrv_connect( "frsv001355", $connectionInfo);
		if( $_SESSION['odbc_connect'] === false )
		{
			die( "Impossible de se connecter à la base 7480.</br>");
		}
		echo "Connexion a la base MS sql avec succes.<br/>";
		///////////////////////////////////////////////////////

		///////////////////////////////////////////////////////
		// Connexion My SQL
		if( @mysql_connect( 'localhost', 'root', '') ){
			echo "Connexion au Serveur MySQL local avec succes.<br/>";
			// Alors on lance la connexion à la base.
			mysql_select_db('es_v5') or die ("Connexion à la base MySql locale impossible");
			echo "Connexion a la base MySQL locale avec succes.<br/>";
		}else
			die( "Erreur de connexion au serveur MySql Local." );
		///////////////////////////////////////////////////////

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo "Page de Test" ?></title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="../design.css" />
	
</head>
<body>	
<?php
	////////////////////////
	// test Req template 
	
	$_Params['nom'] = 'Aer_TLSE';
	$_Params['h_ouverture'] = '07:30';
	$_Params['h_fermeture'] = '20:00';
	$_Params['work_jf'] = true;
	$_Params['work_we'] = false;
	$_Params['exclusion_days'][0] = '09/10/2011';
 	$_Params['crash'][0]['CallTime_begin_crash'] = '06/10/2011 10:40';
	$_Params['crash'][0]['CallTime_end_crash']   = '06/10/2011 11:50';
	$_Params['crash'][0]['duree']   			 = 15;
/*	$_Params['crash'][0]['condition_crash'] = "CallWaitingDuration <= 30";
	$_Params['crash'][1]['CallTime_begin_crash'] = '10/10/2011 09:20';
	$_Params['crash'][1]['CallTime_end_crash']   = '10/10/2011 09:30';
	$_Params['crash'][1]['condition_crash'] = "CallWaitingDuration < 190 AND AgentID != ''";
	 */
	$client = new client( $_Params );
//	$client2 = new client( $_Params );
	$_Params['client'] = $client;
	$_Params['dates']['debut'] = '01/10/2011';
	$_Params['dates']['fin'] = '12/10/2011';
//	$_Params['condition'] = " AgentID != ''";
//	$_Params['no_client'] = true;

	$req = new requete( $_Params );
//	echo "<br/><strong> Template count_req_base_totale: </strong><br/>".$req->count_req_base_totale( $_Params )."<br/>";

	
	



	$code = "[nbappels]+([nbappels]-[compabs;inf;20;])-[nbappels]";
	$i=0;
	$Option6[$i]['nom'] = 'Base Stat';
	$Option6[$i]['type'] = 'personnalise';
	$Option6[$i]['code'] = $code;
	$Option6[$i]['total'] = 'sum';
	$i++;
	$Option6[$i]['nom'] = '% abs / BS';
	$Option6[$i]['type'] = 'personnalise';
	$code = "[compabs;inf;20;]*[100]/([nbappels]-[compabs;inf;20;])";
	$Option6[$i]['code'] = "[compabs;inf;20;]*[100]/([nbappels]-[compabs;inf;20;])";
	$Option6[$i]['total'] = 'avg';
	$Option6[$i]['seuil'] = 0; // ne prend pas en compte les 0 dans la moyenne.
	$i++;
	$Option6[$i]['nom'] = 'Moy Attente';
	$Option6[$i]['type'] = 'moytpsattente';
	$Option6[$i]['total'] = 'avg';
	$i++;
	$Option6[$i]['nom'] = 'Moy Com';
	$Option6[$i]['type'] = 'moytpscom';
//	$Option6[$i]['sens'] = 'inf';
//	$Option6[$i]['borne1'] = 20;
	$Option6[$i]['total'] = 'avg';
	$client->set_options( $Option6 );
	
	// Création des client2 et client3
	// $client2 = clone $client;
	// $client3 = clone $client;
	// $client2->set_nom("SDIS38");
	// $client3->set_nom("SIS");

	$_Params['nom'] = "SDIS38";
	$client2 = new client( $_Params );
	$client2->set_options( $Option6 );
	$_Params['nom'] = "SIS";
	$client3 = new client( $_Params );
	$client3->set_options( $Option6 );













	
	echo "<br/><strong> test execute_req_options_bases </strong> : $code<br/>";
	echo "<br/><strong> + test get_totaux_client </strong> <br/>";
	$taboptions = $client->get_options();
	echo "Compte OPTIONS : ".count( $taboptions )."<br/>";
	$TabResult = $req->execute_req_options_bases();
	$tab_totaux = $req->get_totaux_client();
	// Entete colonne
	echo "<h3>$client</h3>";
	echo '<strong>--- date ---  | '.$taboptions[0]['nom'].' | '.$taboptions[1]['nom'].' | '.$taboptions[2]['nom'].' | '.$taboptions[3]['nom'].' | '.$taboptions[4]['nom'].'</strong><br/>';
	// Corps tab.
	foreach( $TabResult as $key => $value ){
		echo $key." | ".$TabResult[$key][0]." | ".$TabResult[$key][1]." | ".$TabResult[$key][2]." | ".$TabResult[$key][3]." | ".$TabResult[$key][4]."<br/>";
	}
	// Totaux
	echo "<strong>-- Totaux -- |";
	// for( $i=0; $i < count( $Option6 ); $i++ ){ // /!\ Attention le count(tab_totaux) n'est pas valide ... si au milieu du tab, on ne ve pas un résultat ... il y aura "total-1" item ... mais il faut pourtant allez jusq'au dernier offset...
		// echo $tab_totaux[$i]." | ";
	// }
	$i=0;
	foreach( $tab_totaux as $key => $value  ){
		if( $i == $key ){
			echo $value." | ";
			$i++;
		}else{
			for( ; $i <= $key; $i++ ){
				echo " | ".$value;
			} 
			$i++;
		}
	} 
	echo "</strong><br/>";

	
	
	
	
	$req->set_client( $client2 );
	$TabResult = $req->execute_req_options_bases();
	$tab_totaux = $req->get_totaux_client();
	// Entete colonne
	echo "<h3>$client2</h3>";
	echo '<strong>--- date ---  | '.$taboptions[0]['nom'].' | '.$taboptions[1]['nom'].' | '.$taboptions[2]['nom'].' | '.$taboptions[3]['nom'].' | '.$taboptions[4]['nom'].'</strong><br/>';
	// Corps tab.
	foreach( $TabResult as $key => $value ){
		echo $key." | ".$TabResult[$key][0]." | ".$TabResult[$key][1]." | ".$TabResult[$key][2]." | ".$TabResult[$key][3]." | ".$TabResult[$key][4]."<br/>";
	}
	// Totaux
	echo "<strong>-- Totaux -- |";
	// for( $i=0; $i < count( $Option6 ); $i++ ){ // /!\ Attention le count(tab_totaux) n'est pas valide ... si au milieu du tab, on ne ve pas un résultat ... il y aura "total-1" item ... mais il faut pourtant allez jusq'au dernier offset...
		// echo $tab_totaux[$i]." | ";
	// }
	$i=0;
	foreach( $tab_totaux as $key => $value  ){
		if( $i == $key ){
			echo $value." | ";
			$i++;
		}else{
			for( ; $i <= $key; $i++ ){
				echo " | ".$value;
			} 
			$i++;
		}
	} 
	echo "</strong><br/>";
	
	
	
	
	
	
	
	$req->set_client( $client3 );
	$TabResult = $req->execute_req_options_bases();
	$tab_totaux = $req->get_totaux_client();
	// Entete colonne
	echo "<h3>$client3</h3>";
	echo '<strong>--- date ---  | '.$taboptions[0]['nom'].' | '.$taboptions[1]['nom'].' | '.$taboptions[2]['nom'].' | '.$taboptions[3]['nom'].' | '.$taboptions[4]['nom'].'</strong><br/>';
	// Corps tab.
	foreach( $TabResult as $key => $value ){
		echo $key." | ".$TabResult[$key][0]." | ".$TabResult[$key][1]." | ".$TabResult[$key][2]." | ".$TabResult[$key][3]." | ".$TabResult[$key][4]."<br/>";
	}
	// Totaux
	echo "<strong>-- Totaux -- |";
	// for( $i=0; $i < count( $Option6 ); $i++ ){ // /!\ Attention le count(tab_totaux) n'est pas valide ... si au milieu du tab, on ne ve pas un résultat ... il y aura "total-1" item ... mais il faut pourtant allez jusq'au dernier offset...
		// echo $tab_totaux[$i]." | ";
	// }
	$i=0;
	foreach( $tab_totaux as $key => $value  ){
		if( $i == $key ){
			echo $value." | ";
			$i++;
		}else{
			for( ; $i <= $key; $i++ ){
				echo " | ".$value;
			} 
			$i++;
		}
	} 
	echo "</strong><br/>";
	
	
	
	
	
	
	echo "<br/><strong> Test Class Client Checked: </strong><br/>";
	$_Params['list_clients'] = array( $client, $client3, $client2 );
	$equipe = new equipe( $_Params );
	$list_clients = $equipe->get_list_clients();
	echo "On coche les clients à l'offset 1 et 2<br/>";
	$list_clients[0]->checked = true;
	$list_clients[1]->checked = true;
	$list_clients[2]->checked = false;
	echo "Get Clients Checked : <br/>";
	$Checked_Clients = $equipe->get_checked_clients();
	foreach( $Checked_Clients as $key => $value  ){
		echo " - ".$value."<br/>";
	} 
	
	echo "On coche les clients à l'offset 1 et 3<br/>";
	$list_clients[0]->checked = true;
	$list_clients[1]->checked = false;
	$list_clients[2]->checked = true;
	echo "Get Clients Checked : <br/>";
	$Checked_Clients = $equipe->get_checked_clients();
	foreach( $Checked_Clients as $key => $value  ){
		echo " - ".$value."<br/>";
	} 
	
	
	
	echo "<br/><strong> Test Totaux équipe: </strong><br/>";
	// Préparation de l'équipe.
	$equipe2 = new equipe();
	$equipe2->set_nom( "Equipe DTC" );
	// On met 3 fois le client ATB... (pour ne pas a avoir a configurer les autres clients ... :)
	$equipe2->add_client($client);
	$equipe2->add_client($client2);
	$equipe2->add_client($client3);
	// On parametre les corrélations entre les colonnes des différents clients. On fait simple : On addtionne toute les colonnes à la verticale...
	// 1ere colonne
	$equipe2->add_colonne_correlation( 0, "aer_tlse", 0 );
	$equipe2->add_colonne_correlation( 0, "SDIS38", 0 );
	$equipe2->add_colonne_correlation( 0, "SIS", 0 );
	// 2eme colonne
	$equipe2->add_colonne_correlation( 1, "aer_tlse", 0 );
	$equipe2->add_colonne_correlation( 1, "SDIS38", 0 );
	$equipe2->add_colonne_correlation( 1, "SIS", 0 );
	// 3eme colonne
	$equipe2->add_colonne_correlation( 2, "aer_tlse", 0 );
	$equipe2->add_colonne_correlation( 2, "SDIS38", 0 );
	$equipe2->add_colonne_correlation( 2, "SIS", 0 );
	// 4eme colonne
	$equipe2->add_colonne_correlation( 3, "aer_tlse", 0 );
	$equipe2->add_colonne_correlation( 3, "SDIS38", 0 );
	$equipe2->add_colonne_correlation( 3, "SIS", 0 );
	
	// Préparation du tableau d'option de calcul des totaux :
	$options_totaux[0]['type'] = 'sum';
	$options_totaux[0]['seuil'] = 0;
	$options_totaux[1]['type'] = 'avg';
	$options_totaux[1]['seuil'] = 0;
	$options_totaux[2]['type'] = 'min';
	$options_totaux[3]['type'] = 'max';
	$equipe2->set_options_totaux( $options_totaux );

	// Définition de l'équipe
	$req->set_equipe( $equipe2 );
	
	$totaux = $req->get_totaux_equipe();
	
	foreach( $totaux as $key => $value  ){
		echo $key." : ".$value."<br/>";
		
	} 
	
	
	
	echo '<br/><a href="test.php?record=true">Enregistrer les données dans la BDD</a><br/>';
	
	if( isset( $_GET['record'] ) ){
		echo "Test d'enregistrement clients<br/>";
		
		foreach( $list_clients as $key => $client )
			$client->maj_bdd();
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>

</body>
</html>