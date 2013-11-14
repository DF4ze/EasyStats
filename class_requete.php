<?php
//////////////////////////////////////////////////////////////////////
//		Class.php
//		14/09/2011
//		CA. ORTIZ
//	
//		
//////////////////////////////////////////////////////////////////////

class c_virtual
{
	protected $nom;
	protected $show_error;
	protected $show_warning;
	
	public function __construct( $_Params = '' ){
	
		// Préparation des variables globales pour savoir si on active l'affichage des erreurs et/ou  des warning.
		if( !isset( $this->show_error ) ){
			global $show_error;
			$this->show_error = &$show_error;
		}
		if( !isset( $this->show_warning ) ){
			global $show_warning;
			$this->show_warning = &$show_warning;
		}
		
		// Affectation des valeurs si définies dans le $_Params.
		$this->nom = '';
		if( $_Params != '' ){
			if( isset($_Params['nom']) )
				$this->nom = $_Params['nom'];
			if( isset($_Params['show_error']) )
				$this->show_error = $_Params['show_error'];
			if( isset($_Params['show_warning']) )
				$this->show_warning = $_Params['show_warning'];
		}	
	}

	public function __toString(){
		return $this->nom;
	}

	public function add_error( $_Params = '' ){
		if( $this->show_error ){
			if( $_Params != '' ){
				$text_error = 'Erreur : <br/>';
				
				// S'il y a le nom du fichier
				if( isset( $_Params['file'] ) ){
					$text_error .= ' - '.$_Params['file'];
					// S'il y a le numéro de la ligne
					if( isset( $_Params['line'] ) )
						$text_error .= ' sur la ligne '.$_Params['line'];
					$text_error .= '<br/>';
				}
				// S'il y a le nom de la class
				if( isset( $_Params['class'] ) )
					$text_error .= ' - Class : '.$_Params['class'].'<br/>';
				// S'il y a le nom de la fonction
				if( isset( $_Params['function'] ) )
					$text_error .= ' - Fonction : '.$_Params['function'].'<br/>';
				// Si un ID a été donné, on cherche dans la BDD le message d'erreur associé.
				if( isset( $_Params['id'] ) ){
					$donnees = mysql_fetch_array( mysql_query( "SELECT * FROM ".$_SESSION['erreurs']." WHERE id='".$_Params['id']."'" ));					
					$text_error .= ' - ID '.$_Params['id'].' : '.$donnees['message'].'<br/>';
				}
				// S'il y a le message d'erreur
				if( isset( $_Params['msg'] ) )
					$text_error .= ' - Message : '.$_Params['msg'].'<br/>';

				
				echo $text_error;
			}else{
				echo '<br/>Add_Error appelé sans argument...<br/>';
			}
		}
	}
	public function add_warning( $_Params = '' ){
		if( $this->show_warning ){
			if( $_Params != '' ){
				$text_error = 'Warning : <br/>';
				
				// S'il y a le nom du fichier
				if( isset( $_Params['file'] ) ){
					$text_error .= ' - '.$_Params['file'];
					// S'il y a le numéro de la ligne
					if( isset( $_Params['line'] ) )
						$text_error .= ' sur la ligne '.$_Params['line'];
					$text_error .= '<br/>';
				}
				// S'il y a le nom de la class
				if( isset( $_Params['class'] ) )
					$text_error .= ' - Class : '.$_Params['class'].'<br/>';
				// S'il y a le nom de la fonction
				if( isset( $_Params['function'] ) )
					$text_error .= ' - Fonction : '.$_Params['function'].'<br/>';
				// Si un ID a été donné, on cherche dans la BDD le message d'erreur associé.
				if( isset( $_Params['id'] ) ){
					$donnees = mysql_fetch_array( mysql_query( "SELECT * FROM ".$_SESSION['erreurs']." WHERE id='".$_Params['id']."'" ));					
					$text_error .= ' - ID '.$_Params['id'].' : '.$donnees['message'].'<br/>';
				}
				// S'il y a le message d'erreur
				if( isset( $_Params['msg'] ) )
					$text_error .= ' - Message : '.$_Params['msg'].'<br/>';

				
				echo $text_error;
			}else{
				echo '<br/>Add_Error appelé sans argument...<br/>';
			}
		}
	}

	public function set_show_error( $echo ){
		$this->show_error = $echo;
	}
	public function set_show_warning( $echo ){
		$this->show_warning = $echo;
	}
	public function set_nom( $nom ){
		$this->nom = $nom;
	}
	public function get_show_error(){
		return $this->show_error;
	}
	public function get_show_warning(){
		return $this->show_warning;
	}
	public function get_nom(){
		return $this->nom;
	}

	protected function find( $srcvalue, $tab ){
		foreach( $tab as $key => $value  ){
			if( $srcvalue == $value )
				return $key;		
		} 
		// for( $i=0; $i < count( $tab ); $i++ ){
			// if( $value == $tab[$i] )
				// return $i;
		// }
		return -1;
	}
}

class c_req_template extends c_virtual
{
	// ici nous aurons les "morceaux" de requetes qu'il faudra concatener dans une classe dérivée.
	
	public function select( $select = '' ){
		if( $select == '' ){
			return " SELECT Table_InboundVoiceCalls_Blagnac.CallTime AS heure,
						Table_InboundVoiceCalls_Blagnac.CallServiceID AS client ";
		}else{
			return " SELECT ".$select." ";
		}
	}
	public function from( $from = '' ){
		if( $from == '' ){
			return " FROM Table_InboundVoiceCalls_Blagnac ";
		}else{
			return " FROM ".$from." ";
		}
	}
	public function where_client_nom( $nom = 'no_nom' ){
		if( isset( $nom ) )
			if( $nom == 'no_nom' )
				return '';
			else
				return " Table_InboundVoiceCalls_Blagnac.CallServiceID = '$nom' ";
		else
			return -1;
	}
	public function where_client_horaire( $debut, $fin ){
		// Les horaires doivent etre au format : 1900-01-01THH:MM:00.000
		if( isset( $debut ) and isset( $fin ) ){
			return " ( CONVERT(DATETIME, CONVERT(CHAR(12), Table_InboundVoiceCalls_Blagnac.CallTime , 114), 114)
							BETWEEN '".$debut."' AND  '".$fin."' ) ";
		}else
			return -1;
	}
	public function where_client_date( $debut, $fin ){
		// Début et Fin doivent contenir les horaires d'ouvertures et date : jj/mm/yyyy hh:mm.
		if( isset( $debut ) and isset( $fin ) ){
			return " (Table_InboundVoiceCalls_Blagnac.CallTime 
						BETWEEN '$debut' AND '$fin') ";
		}else
			return -1;	
	}
	public function where_client_no_we(){
		return " ( DATEPART( dw, Table_InboundVoiceCalls_Blagnac.CallTime ) != ".$_SESSION['samedi']."
					AND DATEPART( dw, Table_InboundVoiceCalls_Blagnac.CallTime ) != ".$_SESSION['dimanche']." ) ";
	}
	public function where_client_no_jf( $exclusion_days ){
		if( @count( $exclusion_days ) ){
			$reqLine = '(';
			for( $i=0; $i < count( $exclusion_days ); $i++ ){	
				$reqLine .= " ( CONVERT(DATETIME, CONVERT(CHAR(10), Table_InboundVoiceCalls_Blagnac.CallTime , 103), 103) != '".$exclusion_days[$i]."') ";
				if( $i != count( $exclusion_days ) - 1 )
					$reqLine .= " AND ";
			}
			$reqLine .= ')';

			return $reqLine;
		}else{
			return -1;
		}
	}
	public function where_client_condition( $condition ){
		// ...
		return " ".$condition." ";
	}
	public function where_agentid( $agentid = 'decro' ){
		if( $agentid != 'decro' )
			if( $agentid == 'no_agentid' )
				return '';
			else
				if( $agentid == 'abandons' )
					return " Table_InboundVoiceCalls_Blagnac.AgentID = '' ";
				else
					return " Table_InboundVoiceCalls_Blagnac.AgentID = '".$agentid."' ";
		else
			return " Table_InboundVoiceCalls_Blagnac.AgentID != '' ";
	}
	public function select_exclude_crash( $select = '' ){
		if( $select == '' ){
			return " SELECT Table_InboundVoiceCalls_Blagnac.CallTime AS heure ";
		}else{
			return " SELECT ".$select." ";
		}
	}
	public function where_exclude_plage_crashs( $TabCrash ){
		$reqLine = '';
		if( count( $TabCrash ) != 0 ){
			$reqLine .= ' (';
			for( $i=0; $i < count( $TabCrash ); $i++ ){
				$reqLine .= " (Table_InboundVoiceCalls_Blagnac.CallTime BETWEEN '".$TabCrash[$i]['CallTime_begin_crash']."' AND '".$TabCrash[$i]['CallTime_end_crash']."') ";
				if( $i !=  count( $TabCrash ) - 1)
					$reqLine .= ' OR ';
			}
			$reqLine .= ')';
		}
		return $reqLine;
	}
}

class c_req_part extends c_req_template
{
	///////////////////////////////////
	// Cette classe va mettre bout a bout des morceaux de requete.
	// Attention au "jointures" avec les AND, OR, WHERE, ... par exemple.
	// Mais ces "bout à bout" ne sont pas forcément exécutable.
	// Ces "bout a bout" se parametrent en fonction du $_Params fourni.
	
 	protected $client; // class CLIENT
	protected $dates; // $this->dates['debut'],$this->dates['fin'] au format jj/mm/aaaa
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params );
		if( $_Params != '' ){
			if( isset( $_Params['client'] ) )
				$this->client = $_Params['client'];
			else
				return -1;
			
			if( isset( $_Params['dates'] ) )
				$this->dates = $_Params['dates'];
			else
				return -1;
	// Il faudrait vérif s'il y a des jour férié dans la période demandée... avant d'appeler la requete.... sinon ca plante :)
	// --> MAJ des exclusion_days du client.

		}
	}

	/////////////////////////
	// Méthodes concernant les propriétés de la classe.
	public function set_client( $client ){
		$this->client = $client;
		
		// Ici il faut faire un reset sous Condition :
		// CAD, faire un reset que si ... le tableau de résultat n'est pas sur les dates actuelles.
		// C'est con de reset le tableau pour recreer le meme juste apres...!
		// Car, pour affiche le tableau complet du client, il va falloir lancer les requettes une a une ...
		// et apres pour avoir les totaux équipe ... il va falloir réatribuer le client a la requette ... ce qui va de nouveau refaire le calcul ...
		// => Un calcul a l'affichage du tableau du client,
		// => Un autre calcul pour les totaux equipe....
		// =======> Mise en cache???!
		
		$this->client->init_tab();
	}
	public function set_dates( $dates ){
		$this->dates = $dates;
		// Réinitialisation du tableau de résultat si on change de date!
		$this->client->init_tab();
	}
	public function get_client(){
		return $this->client;
	}
	public function get_dates(){
		return $this->dates;
	}
	
 
	////////////////////////
	// Construction de requete Principale... 
	public function select_from( $_Params = '' ){
	// Va empiler le SELECT xxxxx avec le FROM xxxx. avec des personnalisations s'ils sont en parametres.
		$reqLine = '';
		if( isset( $_Params['select'] ) )
			$reqLine .= $this->select( $_Params['select'] );
		else
			$reqLine .= $this->select();
		if( isset( $_Params['from'] ) )
			$reqLine .= $this->from( $_Params['from'] );
		else
			$reqLine .= $this->from();
		return $reqLine;
	}
	public function where_base( $_Params = '' ){
	// Empile les filtres de bases.
	// Nom client, Dates, Horaires, WE et JF
	// (pas de gestion des crash pour l'instant)
		$reqLine = '';

		// Filtre sur le nom du client si l'option "sans client" n'est pas activée
		if( !isset($_Params['no_client']) ){
			$reqLine .= $this->where_client_nom( $this->client->get_nom() )." AND "; // exceptionnellement on met le AND a la fin ... ca évite un bug si on appelle pas de client.
	//		echo " Pas de NO CLIENT | reqLine : $reqLine<br/>";
		}else
			if( $_Params['no_client'] != true )
				$reqLine .= $this->where_client_nom( $this->client->get_nom() )." AND "; // exceptionnellement on met le AND a la fin ... ca évite un bug si on appelle pas de client.
		
		// Filtre sur les dates demandées.
		$_Formated_Params = '';
		if( isset( $_Params['CallTime_begin'] )	and isset( $_Params['CallTime_end'] ) and isset( $_Params['hour_begin'] ) and isset( $_Params['hour_end'] )){
			$_Formated_Params = $this->format_date_req( $_Params['CallTime_begin'], $_Params['CallTime_end'], $_Params['hour_end'], $_Params['hour_end'] );
		}else{
			$_Formated_Params = $this->format_date_req( $this->dates['debut'], $this->dates['fin'], $this->client->get_h_ouverture(), $this->client->get_h_fermeture() );
		}
		$reqLine .= $this->where_client_date( $_Formated_Params['CallTime_begin'], $_Formated_Params['CallTime_end'] );
		$reqLine .= " AND ".$this->where_client_horaire( $_Formated_Params['hour_begin'], $_Formated_Params['hour_end'] );
	//	echo " Apres date | reqLine : $reqLine<br/>";
		
		// Filtre sur les WE
		$reqLineTemp =  '';
		if( isset( $_Params['work_we'] ) ){
			if( $_Params['work_we'] != true )
				$reqLineTemp = " AND ".$this->where_client_no_we();
		}else{
			if( !$this->client->get_work_we() ){
				$reqLineTemp = " AND ".$this->where_client_no_we();
			}
		}
		$reqLine .= $reqLineTemp;
		//echo " Apres WE | reqLine : $reqLine<br/>";
		
		// Filtre sur les JF
		// Est-ce que le client travaille les JF dans les Params?
		$reqLineTemp = '';
		if( isset( $_Params['work_jf'] )){
			if( $_Params['work_jf'] != true ){
				// Y a-t-il des JF de renseignés?
				if( count( $_Params['exclusion_days'] ) != 0 ){
					$reqLineTemp = " AND ".$this->where_client_no_jf( $_Params['exclusion_days'] );
				}
			}
		}else{
			// Est-ce que le client travaille les JF ... dans les Params client.
			if( $this->client->get_work_jf() != true ){
				// Y a-t-il des JF de renseignés?
				// MAJ du tab de JF pour qu'il ne contienne que les JF compris entre les 2 date demandées.
				$this->client->maj_exclusion_days( $this->dates['debut'], $this->dates['fin'] );
				
				if( count( $this->client->get_exclusion_days() ) != 0 )
					$reqLineTemp = " AND ".$this->where_client_no_jf( $this->client->get_exclusion_days() );
			}
		}
		$reqLine .= $reqLineTemp;
		
		
		// Filtre sur le décroché ou abandons
		if( isset( $_Params['agentid'] ) ){
//			echo "<h1> WHERE BASE : agentid isset. </h1>";
			if( $_Params['agentid'] != 'no_agentid' )
				$reqLine .= ' AND '.$this->where_agentid( $_Params['agentid'] );
		}else
			$reqLine .= ' AND '.$this->where_agentid();
		
		return $reqLine;
	}

	////////////////////////
	// Construction de requete de crash... 
	public function where_base_crash( $_Params = '' ){
	// Empile les filtres de bases.
	// Nom client, Dates, Horaires, WE et JF
	
	// ATTENTION : les $_Params[['CallTime_begin_crash'] et $_Params['CallTime_end_crash'] sont obligatoire pour réaliser la requete.
	// ET        : Doivent etre au format "jj/mm/yyyy hh:mm"
	
		$reqLine = '';

		if( isset( $_Params['CallTime_begin_crash'] ) and isset( $_Params['CallTime_end_crash'] ) ){
			// Filtre sur le nom du client si l'option "sans client" n'est pas activée
			if( !isset($_Params['no_client']) )
				if( $_Params['no_client'] == false )
					$reqLine .= $this->where_client_nom( $this->client->get_nom() );
			
			// Filtre sur les dates demandées. 
			// en fait on s'en fou de CallTime_begin_crash et CallTime_end_crash... car ils sont deja au bon format. cf notes au début de la méthode.
			$_Formated_Params = $this->format_date_req( $_Params['CallTime_begin_crash'], $_Params['CallTime_end_crash'], $this->client->get_h_ouverture(), $this->client->get_h_fermeture() );
			// Plage de crash
			$reqLine .= " AND ".$this->where_client_date( $_Params['CallTime_begin_crash'], $_Params['CallTime_end_crash'] );
			// Sur les heures ouvrées du client.
			$reqLine .= " AND ".$this->where_client_horaire( $_Formated_Params['hour_begin'], $_Formated_Params['hour_end'] );
			
			// Filtre sur les WE
			if( !$this->client->get_work_we() ){
				$reqLine .= " AND ".$this->where_client_no_we();
			}
			// Filtre sur les JF
			if( !$this->client->get_work_jf() ){
				// Il faudrait vérif s'il y a des jour férié dans la période demandée... avant d'appeler la requete.... sinon ca plante :)
				if( count( $this->client->get_exclusion_days() ) != 0 )
					$reqLine .= " AND ".$this->where_client_no_jf( $this->client->get_exclusion_days() );
			}

			// Filtre sur le décroché ou abandons
			if( isset( $_Params['agentid'] ) ){
//				echo "<h1> WHERE CRASH : agentid isset. </h1>";
				if( $_Params['agentid'] != 'no_agentid' )
					$reqLine .= ' AND '.$this->where_agentid( $_Params['agentid'] );
			}else{
				$reqLine .= ' AND '.$this->where_agentid();
			}
		}else
			return -1;
			
		return $reqLine;
	}
	public function where_base_exclude( $_Params = '' ){
		$reqLine = ' WHERE ';
		// Filtre sur le nom du client
		$reqLine .= $this->where_client_nom( $this->client->get_nom() );
		
		$reqLineTemp = ' AND '.$this->where_exclude_plage_crashs( $this->client->get_crash() );
		if( isset( $_Params['crash'] ) )
			$reqLineTemp = ' AND '.$this->where_exclude_plage_crashs( $_Params['crash'] );
			
		$reqLine .= $reqLineTemp;
		
		return $reqLine." ";
	}
	public function where_not_in( $_Params = '' ){
		$reqLine = '';
		
		$reqLine .= ' Table_InboundVoiceCalls_Blagnac.CallTime NOT IN ';
		$reqLine .= '(';
		$reqLine .= $this->req_exclude_crash( $_Params );
		$reqLine .= ')';
		
		return $reqLine;
	}
	public function where_gestion_crash( $_Params = '' ){
		$reqLine = '';
		
		// Récupération du tableau de crash.
		$TabCrash = $this->client->get_crash();
		if( isset( $_Params['crash'] ) )
			$TabCrash = $_Params['crash'];

		if( count( $TabCrash ) != 0 ){
			// On appelle l'exclusion des crashs.
			$reqLine .= $this->where_not_in( $_Params );
			// Une fois exclu, il faut ajouter les requetes de crashs.
			// Chacune séparée par un UNION.
			for( $i=0 ; $i < count( $TabCrash ) ; $i++ ){
				$TabCrash[$i]['agentid'] = $_Params['agentid'];
				$reqLine .= ' UNION ';
				$reqLine .= '( '.$this->req_base_crash_condition( $TabCrash[$i] ).' )';
			}
		}
		return $reqLine;
	}

	/////////////////////////////
	// Requetes de base (exécutable)
	public function req_base( $_Params = '' ){
		$reqLine = '';
		$reqLine .= $this->select_from( $_Params );
		$reqLine .= ' WHERE '.$this->where_base( $_Params );
		return $reqLine;
	}
	public function req_base_condition( $_Params = '' ){
		$reqLine = '';
		$reqLine .= $this->req_base( $_Params );
		if( isset( $_Params['condition'] ) )
			$reqLine .= $this->where_client_condition( ' AND '.$_Params['condition'] );
		return $reqLine;
	}
	public function req_base_crash_condition( $_Params = '' ){
		$reqLine = '';
		if( isset( $_Params['CallTime_begin_crash'] ) and isset( $_Params['CallTime_end_crash'] ) ){
			$reqLine .= $this->select_from( $_Params );
			$reqLine .= ' WHERE '.$this->where_base_crash( $_Params );
		
			if( isset( $_Params['condition_crash'] ) )
				$reqLine .= ' AND '.$this->where_client_condition( $_Params['condition_crash'] );
		}else{
			return -1;
		}
		return $reqLine;		
	}
	public function req_exclude_crash( $_Params = '' ){
		$reqLine = '';

		
		$reqLine .= $this->select_exclude_crash( $_Params['select_exclude'] );
		$reqLine .= $this->from( $_Params['from'] );
		$reqLine .= $this->where_base_exclude( $_Params );
		
		return $reqLine;
	}
	

	///////////////////////
	// fonction intermédiaire
	protected function format_date_req( $date_debut, $date_fin, $horaire_debut, $horaire_fin ){
	// Va formater les variables pour qu'elles correspondent aux normes SQL.
	// Format d'entrée :
	//	- $date_debut et $date_fin : jj/mm/aaaa
	//	- $horaire_debut et $horaire_fin : hh:mm:ss
	
		// date_debut et date_fin doivent etre au format : 24/01/2011 07:30
		$tab_result['CallTime_begin'] 	= $date_debut.' '.$horaire_debut;
		$tab_result['CallTime_end'] 	= $date_fin.' '.$horaire_fin;
		// les horaires doivent etre au format : 1900-01-01T07:30:00.000
		$tab_result['hour_begin'] 	= '1900-01-01T'.$horaire_debut.':00.000';
		$tab_result['hour_end'] 	= '1900-01-01T'.$horaire_fin.':00.000';

		return $tab_result;
	}
}

class c_req_template_finale extends c_req_part
{
	
	/////////////////////////////
	// Bout de Requete qui permet de compter les appels
	public function start_groupby( $select = '' ){
		$reqLine = '';
		if( $select != '' ){
			$reqLine .= "SELECT ".$select." FROM ";
		}else{
			$reqLine .= "SELECT CONVERT(varchar(10), heure, 103) AS jour
							, COUNT(heure) as NbAppels
							, client 
						FROM ";
		}
		return $reqLine;
	}
	public function end_groupby( $gpby = '' ){
		$reqLine = '';
		if( $gpby != '' ){
			$reqLine .= "GROUP BY ".$gpby." ";
		}else{
			$reqLine .= "GROUP BY CONVERT(varchar(10), heure, 103), client";
		}
		return $reqLine;		
	}
	public function orderby( $orderby = '' ){
		$reqLine = '';
		if( $orderby != '' ){
			$reqLine .= " ORDER BY ".$orderby." ";
		}else{
			$reqLine .= " ORDER BY jour ";
		}
		return $reqLine;		
	}
	
	/////////////////////////////
	// Requete finale qui permet de compter les appels par jours
	public function count_req( $_Params = '' ){
		$reqLine = '';
		if( $_Params != '' ){
			if( isset( $_Params['select_gpby'] ) ){
				$reqLine .= $this->start_groupby( $_Params['select_gpby'] );
			}else{
				$reqLine .= $this->start_groupby();
			}
			
			if( isset( $_Params['req_init'] ) ){
				$reqLine .= '('.$_Params['req_init'].') AS NewTable ';				
			}else{
				return -1;
			}
			
			if( isset( $_Params['end_gpby'] ) ){
				$reqLine .= $this->end_groupby( $_Params['end_gpby'] );
			}else{
				$reqLine .= $this->end_groupby();			
			}
			
			if( isset( $_Params['orderby'] ) ){
				if( $_Params['orderby'] != 'no_orderby' )
					$reqLine .= $this->orderby( $_Params['orderby'] );
			}else{
				$reqLine .= $this->orderby();
			}
			return $reqLine;			
		}else{
			return -1;
		}
	}
	public function count_req_base( $_Params = '' ){
		// Va lancer une requete de base comptant tout les appels entre 2 dates pour un client
		// tous 2 préalablement initialisé dans cette classe.
		
		$reqLine = '';
		
		if( $_Params != '' ){
			if( isset( $_Params['req_init'] ) ){
				$reqLine .= $this->count_req( $_Params );
			}else{
				$_Params['req_init'] = $this->req_base( $_Params );
				$reqLine .= $this->count_req( $_Params );
			}	
		}else{
			$_Params['req_init'] = $this->req_base();
			$reqLine .= $this->count_req($_Params);
		}
		return $reqLine;
	}
	public function count_req_base_totale( $_Params = '' ){
		// Va lancer une requete de base comptant tout les appels entre 2 dates pour un client
		// tous 2 préalablement initialisé dans cette classe.
		
		$reqLine = '';
		
		if( $_Params != '' ){
			if( isset( $_Params['req_init'] ) ){
				$reqLine .= $this->count_req( $_Params );
			}else{
				$_Params['req_init'] = $this->req_base_totale( $_Params );
				$reqLine .= $this->count_req( $_Params );
			}	
		}else{
			$_Params['req_init'] = $this->req_base_totale();
			$reqLine .= $this->count_req($_Params);
		}
		return $reqLine;
	}

	public function req_base_totale( $_Params = '' ){
		$reqLine = '';
		
		$reqLine .= $this->req_base_condition( $_Params );
		
		$TabCrash = $this->client->get_crash();
		if( isset( $_Params['crash'] ) ){
			$TabCrash = $_Params['crash'];
		}

		if( count( $TabCrash ) != 0 and $_Params['dont_write_crash'] != true )
			$reqLine .= ' AND '.$this->where_gestion_crash( $_Params );
		
		return $reqLine;
	}
	
}

class c_reqs_principales extends c_req_template_finale
{
	////////////////////////////////////////////
	// Class qui va faire une méthode par type de colonne.
	// Attention, il ne s'agit que des requetes principales
	// Et non pas les requetes calculée a partir d'autre colonne 
	// ... commme la base statistique par exemple ... qui est : Nb total recu - nb Abandons<borne min. (donc une soustraction de 2 précédentes req).

	public function req_nb_total_appels( $_Params = '' ){
		$_Params['agentid'] = 'no_agentid';
		$_Params['dont_write_crash'] = true;
		return $this->count_req_base_totale( $_Params );
	}
	public function req_nb_decroches( $_Params = '' ){
		// On ne veut que les décrochés
		$_Params['agentid'] = 'decro';
		// Nous cherchons le nombre total d'appels décrochés donc pas besoin des crash.
		// a part si on force ceci. --> cas pour le temps moyen avant décro.
		if( !isset( $_Params['dont_write_crash'] ) )
			$_Params['dont_write_crash'] = true;
		
		return $this->count_req_base_totale( $_Params );		
	}	
	public function req_nb_abandons( $_Params = '' ){
		$_Params['agentid'] = 'abandons';
		$_Params['dont_write_crash'] = true;
		return $this->count_req_base_totale( $_Params );		
	}
	
	public function req_nb_decro_inf_borne( $borne, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration <= ".($borne + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );
		
		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'decro';
		$_Params['condition'] = "CallWaitingDuration <= $borne";
		
		return $this->count_req_base_totale( $_Params );		
	}
	public function req_nb_decro_sup_borne( $borne, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration > ".($borne + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );

		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'decro';
		$_Params['condition'] = "CallWaitingDuration > $borne";
		return $this->count_req_base_totale( $_Params );		
	}
	public function req_nb_decro_entre_borne( $borne1, $borne2, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration > ".($borne1 + $TabCrash[$i]['duree'])." AND CallWaitingDuration <= ".($borne2 + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );

		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'decro';
		$_Params['condition'] = "CallWaitingDuration > $borne1 AND CallWaitingDuration <= $borne2";
		return $this->count_req_base_totale( $_Params );		
	}

	public function req_nb_abandons_inf_borne( $borne, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration <= ".($borne + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );

		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'abandons';
		$_Params['condition'] = "CallWaitingDuration <= $borne";
		return $this->count_req_base_totale( $_Params );		
	}
	public function req_nb_abandons_sup_borne( $borne, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration > ".($borne + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );

		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'abandons';
		$_Params['condition'] = "CallWaitingDuration > $borne";
		return $this->count_req_base_totale( $_Params );		
	}
	public function req_nb_abandons_entre_borne( $borne1, $borne2, $_Params = ''  ){
		// Parametrage de la condition pour le crash.
		$TabCrash = $this->client->get_crash();
		for( $i = 0 ; $i < count( $TabCrash ); $i++ ){
			$TabCrash[$i]['condition_crash'] = "CallWaitingDuration > ".($borne1 + $TabCrash[$i]['duree'])." AND CallWaitingDuration <= ".($borne2 + $TabCrash[$i]['duree']);
		}
		$this->client->set_crash( $TabCrash );

		// Parametrage de la condition pour la requete de base.
		$_Params['agentid'] = 'abandons';
		$_Params['condition'] = "CallWaitingDuration > $borne1 AND CallWaitingDuration <= $borne2";
		return $this->count_req_base_totale( $_Params );		
	}

	public function req_moy_temps_decro( $_Params = '' ){
		// on reparametre la requete pour qu'elle calcule le temps moyen d'attente avant décroché.
		
		// Modification du début du GROUP BY.
		$_Params['select_gpby'] = "CONVERT(varchar(10), heure, 103) AS jour, AVG(attente) as NbAppels"; // On laisse NbAppels ... pour que la fonction execute_req puisse retrouver les valeurs.
		// Fin du GROUP BY
		$_Params['end_gpby'] = "CONVERT(varchar(10), heure, 103)";
		// SELECT de la requete de base.
		$_Params['select'] = "CallTime AS heure, CallWaitingDuration AS attente";

		// Parametrage des plages crash.
		// Obligé de prendre les parametres crash du clients... que l'on réinjecte une fois modifié.
		// Ainsi on ne modifie les params client que de facon temporaire. (surtout concernant le SELECT.)

		// Deja on force l'écriture des crashs
		$_Params['dont_write_crash'] = false;
		// SELECT de la partie exclusion
		$_Params['select_exclude'] = "CallTime AS heure";
		
		// On récupère le tableau des crash.
		$TabCrash = $this->client->get_crash();
		// On fait le tour du tableau.
		for( $i=0; $i < count( $TabCrash ) ; $i++ ){
			// On récup le temps du crash.
			$temps = $TabCrash[$i]['duree'];
			// On prépare le SELECT
			$select = "CallTime AS heure, CallWaitingDuration - $temps as attente";
			// On prépare la CONDITION
			$condition = "CallWaitingDuration > $temps"; // Pas besoin de mettre le AgentID
			// On injecte dans les parametres du crash
			$TabCrash[$i]['select'] = $select;
			$TabCrash[$i]['condition_crash'] = $condition;
		}
		// On met le TabCrash dans les Params
		$_Params['crash'] = $TabCrash;
		
		// création de la requete et retour.
		return $this->count_req_base_totale( $_Params );
	} 	
	public function req_moy_temps_communication( $seuil = '', $_Params = '' ){
		// on reparametre la requete pour qu'elle calcule le temps moyen de communication.
		
		if( $seuil == '' )
			$seuil = $_SESSION['seuilmoytpscom'];
		
		// Modification du début du GROUP BY.
		$_Params['select_gpby'] = "CONVERT(varchar(10), heure, 103) AS jour, AVG(com) as NbAppels"; // On laisse NbAppels ... pour que la fonction execute_req puisse retrouver les valeurs.
		// Fin du GROUP BY
		$_Params['end_gpby'] = "CONVERT(varchar(10), heure, 103)";
		// SELECT de la requete de base.
		$_Params['select'] = "CallTime AS heure, CallAgentCommunicationDuration AS com";

		// Le temps de communication n'a aucun rapport avec les crash... donc on retire l'écriture des crash
		$_Params['dont_write_crash'] = true;

		
		// Par contre il faut pouvoir réaliser cette moyenne en retirant les appels qui ont duré moins de X seconde.
		// 5 seconde par defaut ... si n'est pas deja renseigné
		if( !isset( $_Params['condition'] ) )
			$_Params['condition'] = "CallAgentCommunicationDuration > $seuil";
		
		// création de la requete et retour.
		return $this->count_req_base_totale( $_Params );		
	} 	
}

class c_req_execute extends c_reqs_principales
{
	////////////////////////////////////
	// Class qui va exécuter les requetes et mettre le résultat dans un tableau.
	
	public function execute_req( $reqLine ){
		// met les résultats d'une req. (passé en parametre) dans un tableau : tab[date] = value. 
		$Tab_Result = array();
		$req = sqlsrv_query($_SESSION['odbc_connect'], $reqLine );
//		$i=0;
		while( $data = sqlsrv_fetch_array( $req )){
			$Tab_Result[ $data[ 'jour' ] ] = $data['NbAppels'];
//			$i++;
		}	
//		echo "<br/>I : $i<br/>";
		ksort( $Tab_Result );
		return  $Tab_Result;
	}
	public function concat_tab( $tab_init, $tab_toadd ){
		// tab_init doit etre de la forme : tab[date][x] = value
		// tab_toadd doit etre de la forme : tab[date] = value
		
		// recherche de l'offset ou inserer le tab_toadd.
		// Comme on ne connait pas les dates... et que c'est la 1ere dim du tableau...
		// On passe tout le tab en revu ... et on regarde OU il y a le plus de tab...
		// car il peut tres bien y avoir un jour ou un tab n'a pas eu de résultat
		$offset = 0;
		foreach( $tab_init as $date => $value ){
			$off_temp = count( $tab_init[$date] );
			if( $off_temp > $offset )
				$offset = $off_temp;
		}

		// On ajoute le tab_toadd au tab_init
		foreach( $tab_toadd as $date => $value ){
			$tab_init[ $date ][$offset] = $value;
		}
		// On range par date.
		ksort( $tab_init );
		
		// on retourne.
		return $tab_init;
	}
}

class c_req_execute_optionsclient extends c_req_execute
{
	//protected $tab_result; // retient les résultats des colonnes. (faciliter le calcul des totaux)
	
	// Classe qui génère un tableau de résultats (uniquement les résultat du coeur du tableau -> sans les totaux)
	// depuis le tableau d'options du client.
	
	public function execute_req_options_bases( $options = '' ){		
		// On récup les options du client. Si elles ne sont pas def dans les params.
		if( $options == '' )
			$options = $this->client->get_options();
//		echo "<br/>Count Options!! ".count( $options );
		
		// Pour chaque colonne/option, on met leur résultat dans un tableau.
		$Tab_Result = array();
		for( $i=0; $i < count( $options ); $i++ ){
			$Tab_Temp = array();
			// En fonction des options de la colonne
			switch( $options[$i]['type'] ){
				case 'nbappels':
					$Tab_Temp = $this->execute_req( $this->req_nb_total_appels() );
					break;
					
				case 'nbdecro':
				case 'nbdecroches':
					$Tab_Temp = $this->execute_req( $this->req_nb_decroches() );
					break;
					
				case 'nbabandons':
				case 'nbabs':
					$Tab_Temp = $this->execute_req( $this->req_nb_abandons() );
					break;
					
				// S'il s'agit d'une comparaison pour le décroché
				case 'compdecro':
					// On a besoin de savoir dans quel sens la comparaison se fait.
					switch( $options[$i]['sens'] ){
						case 'inf':
							$Tab_Temp = $this->execute_req( $this->req_nb_decro_inf_borne( $options[$i]['borne1'] ) );
							//echo "compdecro : Inf";
							break;
							
						case 'sup':
							$Tab_Temp = $this->execute_req( $this->req_nb_decro_sup_borne( $options[$i]['borne1'] ) );
							break;
							
						case 'entre':
							$Tab_Temp = $this->execute_req( $this->req_nb_decro_entre_borne( $options[$i]['borne1'], $options[$i]['borne2'] ) );
							break;
							
						default;
							//echo "compdecro : DEFAUT";
							break;
					}
					break;
					
				// S'il s'agit d'une comparaison pour les abandons
				case 'compabs':
				case 'compabandons':
					// On a besoin de savoir dans quel sens la comparaison se fait.					
					switch( $options[$i]['sens'] ){
						case 'inf':
							$Tab_Temp = $this->execute_req( $this->req_nb_abandons_inf_borne( $options[$i]['borne1'] ) );
							break;
							
						case 'sup':
							$Tab_Temp = $this->execute_req( $this->req_nb_abandons_sup_borne( $options[$i]['borne1'] ) );
							break;
							
						case 'entre':
							$Tab_Temp = $this->execute_req( $this->req_nb_abandons_entre_borne( $options[$i]['borne1'], $options[$i]['borne2'] ) );
							break;
							
						default;
							break;
					}
					break;

				case 'personnalise' :
					// On récup les options définies dans le CODE.
					$Temp_options = $this->get_options_by_code( $options[$i]['code'] );
					
					if( $Temp_options != false ){
						// on récup les résultats.
						$Tab_Result_Temp = $this->execute_req_options_bases( $Temp_options );
						
					//	echo "Count Tab_Result_Temp : ".count( $Tab_Result_Temp ).'<br/>';
						
						// On réalise les calculs demandés dans le CODE.
						$Tab_Temp = $this->get_result_by_code( $options[$i]['code'], $Tab_Result_Temp );
						
					}else
						return false;
					break;
				
				case 'num' : 
				case 'numeric' : 
					// Il faut créer un tableau avec ['value'] comme valeur dans toute les lignes.
					$Tab_Temp = $this->create_num_tab( $options[$i]['value'] );
					break;
					
				case 'tpsmoyattente' :
				case 'moytpsattente' :
					$Tab_Temp = $this->execute_req( $this->req_moy_temps_decro() );
					// echo "count attente : ".count( $Tab_Temp ).'<br/>';
					// foreach( $Tab_Temp as $key => $value  ){
						// echo $key.' | '.$value.'<br/>';
					// } 
					break;
								
				case 'tpsmoycom' :
				case 'moytpscom' :
					if( isset( $options[$i]['seuilmoytpscom'] ))
						$Tab_Temp = $this->execute_req( $this->req_moy_temps_communication( $options[$i]['seuilmoytpscom'] ));
					else
						$Tab_Temp = $this->execute_req( $this->req_moy_temps_communication() );
						
					// echo "count com : ".count( $Tab_Temp ).'<br/>';
					// foreach( $Tab_Temp as $key => $value  ){
						// echo $key.' | '.$value.'<br/>';
					// } 
					break;
					
				default;
					echo "<h3>execute_req_options_bases : Switch : DEFAUT : Type : ".$options[$i]['type']."</h3>";
					break;
			}
			$Tab_Result = $this->concat_tab( $Tab_Result, $Tab_Temp );
		}
		
		// Enregistrement du tableau 
		$this->client->set_tab_result( $Tab_Result );
		
		// On retourne le tableau final
		// echo "<br/>Count Result!! ".count( $Tab_Result[0] )."<br/>";
		return $Tab_Result;
	}
		
	public function get_result_by_code( $code, $tab ){
//		echo "get_result_by_code CODE : $code";
	
		// On récupère les opérateurs
		$Operateurs = $this->get_operateurs_by_code( $code );
		
		// Préparation du tab de résultat de facon a ce qu'il ait une date, a chaque date de chacun des tableaux de résultat.
		$Tab_Result = array();
		for($i=0; $i<count( $tab );$i++){
			foreach( $tab as $date => $value  ){
				$Tab_Result[$date] = 0;
			}
		}
		ksort( $Tab_Result );
		
		// On boucle sur le tableau d'opérateur.
		//echo "Count Opé : ".count( $Operateurs )."<br/>";
		for( $i=0; $i < count( $Operateurs ); $i++ ){
			switch( $Operateurs[$i] ){
				case '+' :
					// On boucle sur le tableau pour additionner toute les colonnes.
					foreach( $Tab_Result as $date => $value ){
						// echo "Add : ".$Tab_Result[$date]." + ".$tab[$date][$i]." = ";
						$Tab_Result[$date] = $Tab_Result[$date] + $tab[$date][$i];
						// echo $Tab_Result[$date]."<br/>";
					}
					break;
				case '-' :
					// On boucle sur le tableau pour additionner toute les colonnes.
					foreach( $Tab_Result as $date => $value ){
						// echo "Sous : ".$Tab_Result[$date]." - ".$tab[$date][$i]." = ";
						$Tab_Result[$date] = $Tab_Result[$date] - $tab[$date][$i];
						//echo $Tab_Result[$date]."<br/>";
					}
					break;
				case '*' :
					// On boucle sur le tableau pour additionner toute les colonnes.
					foreach( $Tab_Result as $date => $value ){
						$Tab_Result[$date] = $Tab_Result[$date] * $tab[$date][$i];
					}
					break;
				case '/' :
					// On boucle sur le tableau pour additionner toute les colonnes.
					foreach( $Tab_Result as $date => $value ){
						if( $tab[$date][$i] != 0 )
							$Tab_Result[$date] = $Tab_Result[$date] / $tab[$date][$i];
						else
							$Tab_Result[$date] = 0;
					}
					break;
				default;
					echo "<h3>get_result_by_code : Switch : DEFAUT : i : $i Operateurs : ".$Operateurs[$i]."</h3>";
					break;
			}
		}
		
		return $Tab_Result;
	}
	public function get_operateurs_by_code_old( $code ){
		//echo "get_operateurs_by_code : Code : $code<br/>";
		// Function abandonnées car probleme de gestion des parenthèse, crochet, etc...
	
		$Operateurs = array();
		
		$Operateurs[0] = '+'; // Triche pour la fonction get_result_by_code qui sera plus simple a coder.
		
		// Récup d'un tableau avec les opérateurs entre les bornes.
		$pos_debut = -1;
		for( $i=1; $pos_fin !== false; $i++ ){
			$pos_debut = strpos( $code, $_SESSION['delim_instruct_fin'], $pos_debut+1 );
			$pos_fin   = strpos( $code, $_SESSION['delim_instruct_debut'], $pos_debut+1 );
			if( $pos_fin !== false )
				$Operateurs[$i] = substr( $code, $pos_debut+1, $pos_fin-$pos_debut-1 ); // +1; -1 ... pour ne pas prendre les crochets.
		}	
		
		// On applique l'opérateur
		return $Operateurs;
	}
	public function get_operateurs_by_code( $code ){
	// /!\ il ne faut pas prendre les opérateurs qui sont compris entre parenthèse!!!!!!!
	// Ils seront calculé plus tard...
	
		$Operateurs = array();
		$Operateurs[0] = '+'; // Triche pour la fonction get_result_by_code qui sera plus simple a coder.
		$nb_par_ouverte = 0;
		for( $i=0; $i < strlen( $code ); $i++ ){
			switch( $code[$i] ){
				case '+':
				case '-':
				case '/':
				case '*':
					if( $nb_par_ouverte == 0 )
						$Operateurs[count( $Operateurs )] = $code[$i];
					break;
				case '(':
					$nb_par_ouverte++;
					break;
				case ')':
					$nb_par_ouverte--;
					break;
				default;
					break;
			}
		} 
		return $Operateurs;
	}
	public function get_options_by_code( $code ){
		// But de cette fonction est de lire le code e tretourner un tableau d'option ... 
		// Dans ce code nous pouvons avoir l'addition/soustraction de plusieurs colonne
		// Mais ces colonnes (de calcul) ne sont pas forcément demandées à l'affichage...
		// Nous allons donc générer un tableau d'options ... pour avoir ces colonnes (le tableau général)
		// Ainsi on pourra faire le calcul demandé.
		// Exemple de code : [compdecro;inf;20;]-[compabs;entre;20;50;]+[compdecro;sup;20;]/[100]
		
		// Définition des regles du codage :
		// - chaque colonne doit etre encadrée par des [].       (avec [ = $_SESSION['delim_instruct_debut'], ] = $_SESSION['delim_instruct_fin'])
		// - chaque colonne doit correspondre au TYPE deja renseigné dans les options de bases.
		// - pour les comparaisons de bornes, la nomenclature doit etre comme suit : [type;sens;borne1;borne2] (avec ; = $_SESSION['delim_options'])
		
		/////////////////////
		// Vérification de la bonne mise en forme du code... si non valide on retourne FALSE pour arreter la fonction.
		if( !$this->verif_validite_code( $code ) )
			return false;
		
		// On va deja extraire toute les chaines de caractère qu'il y a entre les crochets.
		$TabParam = $this->get_ligne_commande_by_code( $code );
		
		// On met ces ligne de commande dans un tab d'options. Que execute_req_options_bases() pourra executer.
		$Tab_Options = array();
		for( $i=0; $i < count( $TabParam ); $i++ ){
			$Tab_Options[$i] = $this->return_options( $TabParam[$i] );
		}
		return $Tab_Options;
	}
	
	public function create_num_tab( $value ){
		// Va creer un tableau avec des dates allant du début jusqu'a la fin ... :)
		// et la meme valeur dans chacune des colonnes;
		// /!\ aux WE et JF !!!
		$tab = array();
		$date_fin = new datetime( reverse_date($this->dates['fin']) );
		// On scan les dates de la plus petite a la plus grande.
		for( $p_date = new datetime( reverse_date($this->dates['debut']) ); $p_date <= $date_fin; $p_date->modify( '+1 day' ) ){
			// Le client travaille le WE?
			if( $this->client->get_work_we() == false ){
				// Si NON, est-ce que la date actuelle est un WE?
				if( isWeekend( $p_date->format( 'Y/m/d' )) == false ){
					// NON, alors est-ce qu'il travaille les JF?
					if( $this->client->get_work_jf() == false ){
						// NON, est-ce que la date actuelle est un JF?
						$this->client->maj_exclusion_days( $this->dates['debut'], $this->dates['fin'] );
						if( $this->find( $p_date->format( 'd/m/Y' ), $this->client->get_exclusion_days() ) == -1 )
							// NON, donc on insere la value.
							$tab[ $p_date->format( 'd/m/Y' ) ] = $value;
					}else
						// OUI, donc on ne cherche pas a savoir s'il s'agit d'un JF, On insere la value.
						$tab[ $p_date->format( 'd/m/Y' ) ] = $value;
				}
			}else{
				// OUI, travaille-t-il les JF alors?
				if( $this->client->get_work_jf() == false ){
					// NON, est-ce que la date actuelle est un JF?
					$this->client->maj_exclusion_days( $this->dates['debut'], $this->dates['fin'] );
					if( $this->find( $p_date->format( 'd/m/Y' ), $this->client->get_exclusion_days() ) == -1 )
						// NON, donc on insere la value.
						$tab[ $p_date->format( 'd/m/Y' ) ] = $value;
				}else
					// OUI, donc on ne cherche pas a savoir s'il s'agit d'un JF, On insere la value.
					$tab[ $p_date->format( 'd/m/Y' ) ] = $value;		
			}
		}
		return $tab;
	}
	public function verif_validite_code( $code ){
		$bool = false;
		$regex = "#^\(?\[[a-z0-9]+(;?[a-z0-9]+;){0,2}\](\)?[+*/-]\(?\[[a-z0-9]+(;?[a-z0-9]+;){0,2}\]\)?)*$#";
		if( preg_match( $regex, $code ) )
			$bool = true;
		// Les regex ne peuvent pas gérer le "comptage" des parenthèses,
		// il peut juste confirmer le bon emplacement des parenthèses...
		// Mais ne peut pas dire si la parenthèse est bien fermée ou préalablement ouverte
		if( $bool == true ){
			$nb_ouvert = 0;
			$nb_fermer = 0;
			$Tab_Pos_Ouvert = array();
			$Tab_Pos_Fermer = array();
			for( $i=0; $i < strlen( $code ); $i++ ){
				switch( $code[$i] ){
					case '(' :
						$Tab_Pos_Ouvert[ count( $Tab_Pos_Ouvert ) ] = $i;
						$nb_ouvert++;
						break;
					case ')' :
						$Tab_Pos_Fermer[ count( $Tab_Pos_Fermer ) ] = $i;
						$nb_fermer++;
						break;
				}
			} 
			
			if( $nb_ouvert != $nb_fermer )
				$bool = false;
			else{
				$bool_temp = true;
				for( $i=0; $i < count( $Tab_Pos_Ouvert ) ; $i++ ){
					if( $Tab_Pos_Ouvert[$i] > $Tab_Pos_Fermer[$i] )
						$bool_temp = false;
				} 
				$bool = $bool_temp;
			}
		}
		return $bool;
	}
	public function verif_validite_code_old( $code ){
		// Vérif des [].
		$pos = -1; // -1 car +1 un peu apres ... si on part de 0 ... +1 ... on zappe le 1er caractere.
		$count_ouvert = 0;
		while( $pos !== false ){
			$pos = strpos( $code, $_SESSION['delim_instruct_debut'], $pos+1 ); // +1 sinon on retombe pile a l'endroit ou le caractere existe ... et on tourne en rond..
			if( $pos !== false )
				$count_ouvert++;
		}
		$pos = -1;
		$count_ferme = 0;
		while( $pos !== false ){
			$pos = strpos( $code, $_SESSION['delim_instruct_fin'], $pos+1 );
			if( $pos !== false )
				$count_ferme++;
		}
		// S'il n'y a pas autant d'ouverture que de fermeture ... on annule la fonction.
		//echo "Ouvert : $count_ouvert | Fermé : $count_ferme<br/>";
		if( $count_ouvert != $count_ferme )
			return false;
		else 
			return true;
	}
	public function get_ligne_commande_by_code_bis( $string ){
		// va retourner un tableau avec les différentes ligne de commande comprisent entre [].
		// Function qui pose probleme pour la gestion des parenthèse... ici nous cherchons le caractère spécifique ...
		// Il est donc difficile de gérer les parenthèse....
		// Je refait la fonction get_ligne_commande_by_code_BIS
		
		$TabParam = array();
		$i=0;
		$pos_debut = -1;
		
	//	echo "get_ligne_commande_by_code STRING : $string <br/>";
		
		while( $pos_debut !== false ){
			$pos_debut = strpos( $string, $_SESSION['delim_instruct_debut'], $pos_debut+1 );
			$pos_fin   = strpos( $string, $_SESSION['delim_instruct_fin'], $pos_debut+1 );
			if( $pos_debut !== false )
				$TabParam[$i] = substr( $string, $pos_debut+1, $pos_fin-$pos_debut-1 ); // +1; -1 ... pour ne pas prendre les crochets.
			$i++;
		}		
		return $TabParam;
	}
	public function get_ligne_commande_by_code( $code ){
		// On va scanner caractere par caractere...
		// de facon a faire ressortir les params entre crochets si pas de parenthèse ...
		// ressortir les parenthèses si necessaire...
		$crochet_ouvert = false;
		$parenthese_ouverte = false;
		$tab = array();
		for( $i=0; $i < strlen( $code ); $i++ ){
			switch( $code[$i] ){
				case '[':
					if( $parenthese_ouverte === false ){
						$crochet_ouvert = $i;
					}
					break;
				case ']':
					if( $crochet_ouvert !== false ){
						$tab[count($tab)] = substr( $code, $crochet_ouvert+1, $i-$crochet_ouvert-1);
						$crochet_ouvert = false;
					}
					break;
				case '(':
					$parenthese_ouverte = $i;
					break;
				case ')':
					$tab[count($tab)] = substr( $code, $parenthese_ouverte+1, $i-$parenthese_ouverte-1);
					$parenthese_ouverte = false;					
					break;
				default;
					break;
			}
		}
		return $tab;
	}
	public function return_options( $string ){
		// Fonction qui va récupérer une instruction qu'il y avait dans le 'code'.
		// Ca peut etre une instruction concernant une requette... 					--> compabs;inf;20;
		// Comme une imbrication d'instructions (initialement compris entre () )	--> [compabs;entre;20;50;]+[compdecro;sup;20;]
		// et va retourner le tableau d'option, qui sera désigné par le code inscrit dans $STRING.
		$Tab_Options = array();
		
		// Check des le début s'il s'agit d'une instruction simple ou d'une imbrication.
		if( strpos( $string, $_SESSION['delim_instruct_debut'] ) !== false ){
			// S'il y a un délimteur d'instruction ... alors c'est une req personnalisée
			$Tab_Options['type'] = 'personnalise';
			$Tab_Options['code'] = $string;
		
		}else{
			// il n'y a pas de délimiteur d'instruction ... donc on décrypte le code.
			
			// On recherche le symbole ; qui sépare les différentes options.
			$pos_debut = 0;
			$pos = strpos( $string, $_SESSION['delim_options'], $pos_debut );
			
			// Si on ne retrouve pas de délimiteur, alors le STRING est directement une option.
			if( $pos === false ){
				// echo "Single Options<br/>";
				if( is_numeric( $string )  ){
					$Tab_Options['type'] = 'num';
					$Tab_Options['value'] = $string;
				}else			
					$Tab_Options['type'] = $string;
			}else{
				// Sinon, c'est qu'il y a un SENS ... et il y aura au moins une borne.
				// on enregistre deja le TYPE
				// echo "TYPE ok<br/>";
				$Tab_Options['type'] = substr( $string, 0, $pos );
				
				// On recherche s'il y a un autre délimiteur (normalement OUI)
				$pos_debut = $pos+1;
				$pos = strpos( $string, $_SESSION['delim_options'], $pos_debut );
				
				// Si pas de délimiteur ... alors il y a une erreur ...
				if( $pos === false ){
					// echo "SENS Nok<br/>";
					return false;
				}else{
					// Si oui, alors on enregistre le SENS.
					$Tab_Options['sens'] = substr( $string, $pos_debut, $pos-$pos_debut);
					// echo "SENS ok<br/>";
					
					// On recherche la borne de référence.
					$pos_debut = $pos+1;
					$pos = strpos( $string, $_SESSION['delim_options'], $pos_debut );
					
					// Si pas de délimiteur ... alors il y a une erreur ...
					if( $pos === false ){
						// echo "BORNE1 Nok<br/>";
						return false;
					}else{
						// Si oui, alors on enregistre la BORNE1.
						$Tab_Options['borne1'] = substr( $string, $pos_debut, $pos-$pos_debut);
						// echo "BORNE1 ok<br/>";
						
						// On recherche la 2eme borne de référence.
						$pos_debut = $pos+1;
						$pos = strpos( $string, $_SESSION['delim_options'], $pos_debut );
						
						if( $pos !== false ){
							$Tab_Options['borne2'] = substr( $string, $pos_debut, $pos-$pos_debut);
							// echo "BORNE2 ok<br/>";
						}
					}
				}
			}
		}
		
		return $Tab_Options;
	}

}

class c_req_execute_total_client extends c_req_execute_optionsclient
{
	// Class qui va générer les totaux des tableaux en fonction des options demandées par le client.
	public function get_totaux_client( $options = '' ){
		
		// Récupération des options.
		if( $options == '' )
			$options = $this->client->get_options();
		// Récupération du tableau de résultat	
		$result = $this->client->get_tab_result();
		// Sinon on demande le calcul.
		if( count( $result ) == 0 )
			$result = $this->execute_req_options_bases( $options );
		
		$totaux = array();
		// On fait le tour des options/colonnes
		for( $i=0; $i < count( $options ); $i++ ){		
			// S'il s'agit d'une "option simple" alors on peut lancer le calcul via les requetes.
			// Sinon on fait le calcul a partir du tableau entier.
			switch( $options[$i]['total'] ){
				case 'moyenne':
				case 'moy':
				case 'average':
				case 'avg':
					if( isset( $options[$i]['seuil'] ) )
						$totaux[$i] = $this->get_avg( $this->create_single_tab( $result, $i ), $options[$i]['seuil']);
					else
						$totaux[$i] = $this->get_avg( $this->create_single_tab( $result, $i ));
					break;
				case 'somme':
				case 'som':
				case 'sum':
					if( isset( $options[$i]['seuil'] ) )
						$totaux[$i] = $this->get_sum( $this->create_single_tab( $result, $i ), $options[$i]['seuil']);
					else
						$totaux[$i] = $this->get_sum( $this->create_single_tab( $result, $i ));
					break;
				case 'minimum':
				case 'min':
					$totaux[$i] = $this->get_min( $this->create_single_tab( $result, $i ));
					break;
				case 'maximum':
				case 'max':
					$totaux[$i] = $this->get_max( $this->create_single_tab( $result, $i ));
					break;
				case 'no_total' :
					break;
				default;
					echo "<h3>get_totaux_client : Switch : DEFAUT : Type : ".$options[$i]['total']."</h3>";
					break;
			}
		}
		
		// Savegarde des totaux chez le client.
		$this->client->set_tab_totaux( $totaux );
		// On retourne les totaux.
		return $totaux;
	}

	public function get_sum( $tab, $seuil = -1 ){
		// Va retourner la somme des values du tab,
		// ne compte pas les valeurs inférieures ou égale au seuil.
		$temp_sum = 0;
		foreach( $tab as $key => $value ){
			if( $value > $seuil )
				$temp_sum += $value;
		}
		return $temp_sum;
	}
	public function get_avg( $tab, $seuil = -1 ){
		// Va retourner la moyenne des values du tab,
		// ne compte pas les valeurs inférieures ou égale au seuil.
		$temp_sum = 0;
		$count = 0;
		foreach( $tab as $key => $value ){
			if( $value > $seuil ){
				$temp_sum += $value;
				$count++;
			}
		}
		if( $count != 0 )
			return $temp_sum/$count;
		else
			return 0;
	}
	public function get_min( $tab ){
		// Va retourner la valeur minimale du tab,
		$i=0;
		foreach( $tab as $key => $value ){
			// Init de MIN
			if( $i == 0 )
				$min = $value;
			// S'il existe plus petit
			if( $value < $min )
				$min = $value;
			$i++;
		}
		return $min;
	}
	public function get_max( $tab ){
		// Va retourner la valeur maximale du tab,
		$i=0;
		foreach( $tab as $key => $value )
			// Init de MAX
			if( $i == 0 )
				$max = $value;
			// S'il existe plus grand
			if( $value > $max )
				$max = $value;
			$i++;
		return $max;
	}

	public function create_single_tab( $result, $indice ){
		// Le tableau de résultat est sous la forme tab[date][colonne] = valeur,
		// Or les get_avg, etc... ne prennent que des tab tab[date] = valeur.
		$tab = array();	
		foreach( $result as $key => $value ){
			$tab[$key] = $result[$key][$indice];
		}
		return $tab;
	}
}

class c_calcul_totaux_equipe extends c_req_execute_total_client
{
	// Va réaliser le calcul des totaux pour les équipes.
	// Implicitement, va lancer le calcul des clients.
	
	protected $equipe;
	
	public function __construct( $_Params = '' ){
		parent::__construct( $_Params );
		
		if( isset( $_Params['equipe'] ) )
			$this->equipe = $_Params['equipe'];
		
	}
	
	public function set_equipe( $equipe ){
		$this->equipe = $equipe;
	} 
	public function get_equipe(){
		return $this->equipe;
	} 
	
	// Le tab de résult sera de la forme : $result[colonne] = value
	public function get_totaux_equipe( $options_clients = '' ){
		
		if( isset( $this->equipe )){
			$Resultat_clients = array();
			
			//!\ Il faut récupérer le tableau de résultat et non les totaux (de ce tableau de résultat)...
			// Si un total fait une moyenne ... la "moyenne des moyennes" n'est pas égale a la "moyenne du tout"!
			
			// On fait le tour des clients CHECKED pour récupérer leurs résultat.
			$list_clients = $this->equipe->get_checked_clients();
			foreach( $list_clients as $key => $client  ){
				// On prépare la class requete avec le client en question : (les dates sont deja parametrées)
				$this->set_client( $client );
				
				// Récupération du tableau de résultat	
				$result = $this->client->get_tab_result();
				// Si il est vide, on demande le calcul.
				if( count( $result ) == 0 ){
					if( $options_clients != '' )
						$result = $this->execute_req_options_bases( $options_clients["$client"] );
					else
						$result = $this->execute_req_options_bases();
				}

				// Résultat que l'on dépose dans le tableau général.(tagué avec le nom du client)
				$Resultat_clients[ "$client" ] = $result;
			}
			// Maintenant on calcule et on retourne
			return $this->calc_totaux_equipe( $Resultat_clients );
			
		}else{
			echo "get_totaux_equipe() : L'équipe n'a pas été définie, la fonction ne s'est pas réalisée.<br/>";
		}
	}
	protected function calc_totaux_equipe( $Tab_Result ){
		// Va faire le calcul des totaux de l'équipe en fonction des corrélations et des options de calcul.
		
		// correl_Tab[N° correlation][clients][colonne] = true
		// $options_totaux[ N°Correl ]['type'] = sum,avg,min, max
		
		$result = array();
		// On fait le tour de toute les colonnes des totaux.
		$options_totaux = $this->equipe->get_options_totaux();
		echo "Count Options Totaux : ".count( $options_totaux ).'<br/>';
		$correl_Tab = $this->equipe->get_correl_tab();
		echo "Count Correl Tab : ".count( $correl_Tab ).'<br/>';
		echo "Count Tab_Result : ".count( $Tab_Result ).'<br/>';
		
		foreach( $options_totaux as $Num_Correl => $type  ){
			switch( $options_totaux[ $Num_Correl ]['type'] ){
				case 'sum' : // Il faut additionner les colonnes du correl_Tab qui ont le N° correlation = $key
					$buffer = 0;
					
					$seuil = -1;
					if( isset( $options_totaux[ $Num_Correl ]['seuil'] ) )
						$seuil = $options_totaux[ $Num_Correl ]['seuil'];
						
						
					// On fait le tour des Numéro de Corrélation == colonnes du total équipe.
					//foreach( $correl_Tab[ $Num_Correl ] as $Client => $Tab_colonne  ){
					
					//!\ il ne faut prendre QUE les clients qui ont été cochés!
					foreach( $Tab_Result as $Client => $OnSenFou  ){	
						// On fait le tour de tout les clients ayant une corrélation pour récuperer la colonne à calculer
						foreach( $correl_Tab[ $Num_Correl ][ $Client ] as $Colonne => $bool  ){
							
							if( $_SESSION['debug_mode'] ) 
								echo "Type : SUM | Num_Correl : $Num_Correl | Client : $Client | Colonne : $Colonne | Seuil : $seuil".'<br/>';
							
							// On fait le tour des dates du client pour la colonne donnée de facon a additionner les valeurs.
							foreach( $Tab_Result[ $Client ] as $date => $value  ){
								if( $Tab_Result[ $Client ][ $date ][ $Colonne ] > $seuil )
									$buffer += $Tab_Result[ $Client ][ $date ][ $Colonne ];
							} 
						} 
					} 
					$result[ $Num_Correl ] = $buffer;
					break;
					
					
				case 'avg' : 
					$buffer = 0;
					$count = 0;
					$seuil = -1;
					if( isset( $options_totaux[ $Num_Correl ]['seuil'] ) )
						$seuil = $options_totaux[ $Num_Correl ]['seuil'];
						
					foreach( $Tab_Result as $Client => $OnSenFou  ){	
						foreach( $correl_Tab[ $Num_Correl ][ $Client ] as $Colonne => $bool  ){
							if( $_SESSION['debug_mode'] ) 
								echo "Type : AVG | Num_Correl : $Num_Correl | Client : $Client | Colonne : $Colonne | Seuil : $seuil<br/>";
							
							foreach( $Tab_Result[ $Client ] as $date => $value  ){
								if( $Tab_Result[ $Client ][ $date ][ $Colonne ] > $seuil ){
									$buffer += $Tab_Result[ $Client ][ $date ][ $Colonne ];
									$count++;
								}
							} 
						} 
					} 
					
					if( $count != 0 )
						$result[ $Num_Correl ] = $buffer/$count;
					else
						$result[ $Num_Correl ] = 0;
					break;
				case 'min' : 
					$min = 10000; // a init avec la valeur la plus grande...
					foreach( $Tab_Result as $Client => $OnSenFou  ){	
						foreach( $correl_Tab[ $Num_Correl ][ $Client ] as $Colonne => $bool  ){
							if( $_SESSION['debug_mode'] ) 
								echo "Type : MIN | Num_Correl : $Num_Correl | Client : $Client | Colonne : $Colonne <br/>";
							
							foreach( $Tab_Result[ $Client ] as $date => $value  ){
								if( $Tab_Result[ $Client ][ $date ][ $Colonne ] < $min )
									$min = $Tab_Result[ $Client ][ $date ][ $Colonne ];
							} 
						} 
					} 		
					$result[ $Num_Correl ] = $min;
					break;
					
				case 'max' : 
					$max = 0; // a init avec la valeur la plus petite...
					foreach( $Tab_Result as $Client => $OnSenFou  ){	
						foreach( $correl_Tab[ $Num_Correl ][ $Client ] as $Colonne => $bool  ){
							if( $_SESSION['debug_mode'] ) 
								echo "Type : MAX | Num_Correl : $Num_Correl | Client : $Client | Colonne : $Colonne <br/>";
							
							foreach( $Tab_Result[ $Client ] as $date => $value  ){
								if( $Tab_Result[ $Client ][ $date ][ $Colonne ] > $max )
									$max = $Tab_Result[ $Client ][ $date ][ $Colonne ];
							} 
						} 
					} 					
					$result[ $Num_Correl ] = $max;
					break;
				default :
					break;
			} 
		} 
		
		return $result;
	} 
}






class requete extends c_calcul_totaux_equipe
{
	// Class Maitre.
	// Herite de la derniere class.
}












function find_in_date($date, $indic)
{
	if( $date != "" )
	{
		//On prend pour base : aaaa/mm/jj
		$taille = strlen( $date );
		
		$pos_debut = strpos($date, "/");
		$aaaa = substr( $date, 0, $pos_debut);
		
		$pos_fin = strpos($date, "/", $pos_debut+1);
		$mm = substr( $date, $pos_debut+1, ($pos_fin - $pos_debut-1));

		$jj = substr( $date, $pos_fin+1, ($taille - $pos_fin-1));
		
		$retour = $aaaa;
		if( $indic == 2 )
			$retour = $mm;
		else if( $indic == 3 )
			$retour = $jj;
	
		return $retour;
	}
}

function reverse_date($date)
{
	if( $date != "" )
	{
		$item1 = find_in_date($date, 1);
		$item2 = find_in_date($date, 2);
		$item3 = find_in_date($date, 3);
		
		$new_date = $item3."/".$item2."/".$item1;
		
		return $new_date;
	}
}

function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}

function second_to_time( $second ){
	$sec = 0;
	$min = 0;
	$hour = 0;
	$temp = $second;
	$i=0;
	
	// compte les minutes.
	for( $i=0; $temp >= 0; $i++ ){
		$temp -= 60;
	}
	$i --;
	$sec = $temp + 60;
	$min = $i;
	
	// compte les heures.
	$temp = $min;
	for( $i=0; $temp >= 0; $i++ ){
		$temp -= 60;
	} 
	$i --;
	$min = $temp+60;
	$hour = $i;
	
	if( $hour < 10 )
		$hour = '0'.$hour;
	if( $min < 10 )
		$min = '0'.$min;
	if( $sec < 10 )
		$sec = '0'.$sec;
	
	return $hour.':'.$min.':'.$sec;
}
?>