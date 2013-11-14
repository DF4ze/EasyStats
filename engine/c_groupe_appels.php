<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_10");
class groupe_appels {	public $liste_appels;	private $client;	private $date_debut;	private $date_fin;			public function __construct( datetime $debut, datetime $fin, client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_9");
			$this->date_debut = $debut;		$this->date_fin = $fin;		$this->client = $client;		$this->liste_appels = new ClassArray();				$reqLine = "SELECT * FROM Table_InboundVoiceCalls_Blagnac WHERE CallTime > '".$debut->format('Y/d/m H:i:s')."'  AND CallTime < '".$fin->format('Y/d/m H:i:s')."' AND CallServiceID = '$client' ORDER BY CallTime";		$req = sqlsrv_query($_SESSION['odbc_connect'], $reqLine );				while( $data = sqlsrv_fetch_array( $req ))		{				$this->liste_appels->add( new appel( $data['CallTime'], $data['CallServiceID'], $data['CallCLID'], $data['CallWaitingDuration'], $data['CallAgentCommunicationDuration'], $data['AgentID'], $data['AgentName'] ) );		}			}	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_8");
		$list = '';		$i = 0;		while( $i < $this->liste_appels->count() )		{			$list = $list."[".$i."]:".$this->liste_appels[$i]."  ";			$i++;		}		return $list;	}	public function add_appel( appel $appel ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_7");
		$this->liste_appels->add( $appel );	}	public function del_appel( appel $appel ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_6");
		$this->liste_appels->del( $appel );	}	public function get_nb_appel( appel $appel ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_5");
		return $this->liste_appels->count();	}	public function get_date_debut(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_4");
		return $this->date_debut;	}	public function get_date_fin(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_3");
		return $this->date_fin;	}	public function get_client(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_2");
		return $this->client;	}		public function moyenne_tps_av_decro(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_1");
		if( $this->liste_appels->count() != 0 )		{			$somme = 0;			$i = 0;						while( $i < $this->liste_appels->count())			{				$somme += $this->liste_appels[$i]->tps_attente_av_decro;				$i++;			}						return $somme/$this->liste_appels->count();		}		else			return 0;	}	public function nb_total_abandon(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_appels.php_0");
		$i = 0;		$nb_abandons = 0;				while( $i < $this->liste_appels->count() )		{			if( $this->liste_appels[$i]->poste_operateur == "" )				$nb_abandons++;							$i++;		}				return $nb_abandons;	}}?>