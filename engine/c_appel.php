<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_7");

class appel_simple
{
	public $date;
	public $tps_attente_av_decro;
	public $is_decro;
	public $tps_com;
	public $nom_client;
	public $poste_operateur;
	public $nom_operateur;
	public $num_appelant;
	
	public function __construct( $date1, $nom_client1, $num_appelant="", $tps_attente_av_decro1 = 0, $tps_com1 = 0, $poste_operateur1 = 0, $nom_operateur1 = ''){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_6");

		$this->date = $date1;
		$this->tps_attente_av_decro = $tps_attente_av_decro1;
		//$this->is_decro = $is_decro1;
		$this->tps_com = $tps_com1;
		$this->nom_client= $nom_client1;
		$this->poste_operateur = $poste_operateur1;
		$this->nom_operateur = $nom_operateur1;	
		$this->num_appelant = $num_appelant;
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_5");

		//return $this->date;
		return $this->date->format('Y/m/d H:i:s');
	}
}
// On ajoute la fonction de detection de CRASH.
class appel_bis extends appel_simple
{
	public function is_on_crash(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_4");

		// nous allons compar� la date de l'appel avec la BDD des crashs et ainsi �tablir si nous sommes en crash ou pas.
		$cmd_line = "SELECT * FROM crashs WHERE client = '".$this->nom_client."' AND date_debut < '".$this->date->format('Y-m-d H:i:s')."' AND date_fin > '".$this->date->format('Y-m-d H:i:s')."'";
		$nb = mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
		
		// echo "Client = ".$this->nom_client;
		// echo " Date = ".$this->date->format('Y-m-d H:i:s')." NB = $nb<br/>";
		
		
		if( $nb > 0 )
			return true;
		else
			return false;
	}
	public function get_duree_crash(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_3");

		$cmd_line = "SELECT duree FROM crashs WHERE client = '".$this->nom_client."' AND date_debut < '".$this->date->format('Y-m-d H:i:s')."' AND date_fin > '".$this->date->format('Y-m-d H:i:s')."'";
		$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
		return $donnees['duree'];	
	}
	public function get_date_debut_crash(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_2");

		$cmd_line = "SELECT date_debut FROM crashs WHERE client = '".$this->nom_client."' AND date_debut < '".$this->date->format('Y-m-d H:i:s')."' AND date_fin > '".$this->date->format('Y-m-d H:i:s')."'";
		$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
		return $donnees['date_debut'];	
	}	
	public function get_date_fin_crash(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_1");

		$cmd_line = "SELECT date_fin FROM crashs WHERE client = '".$this->nom_client."' AND date_debut < '".$this->date->format('Y-m-d H:i:s')."' AND date_fin > '".$this->date->format('Y-m-d H:i:s')."'";
		$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
		return $donnees['date_fin'];	
	}
	public function get_commentaire_crash(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_appel.php_0");

		$cmd_line = "SELECT commentaire FROM crashs WHERE client = '".$this->nom_client."' AND date_debut < '".$this->date->format('Y-m-d H:i:s')."' AND date_fin > '".$this->date->format('Y-m-d H:i:s')."'";
		$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
		return $donnees['commentaire'];	
	}
}

class appel extends appel_bis
{
	
}


?>