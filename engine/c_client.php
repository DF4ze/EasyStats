<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_12");

class client
{
	public $nom;
	public $nick;
	public $H_ouverture;
	public $H_fermeture;
	public $compte_we;
	public $compte_jf;
	public $equipe;
	public $borne_min;
	public $borne_max;
	public $borne_min_decro;
	public $borne_max_decro;
	public $SLA;
	public $couleur;
	public $dispo_min;
	public $dispo_max;
	public $is_select;
	public $graph_par_jour;
	public $graph_total;
	public $graph_total_moyenne;
	
	const END_START_NOT_DISPO = 4;
	const FULL_DISPO = 3;
	const ONLY_END_DISPO = 2;
	const ONLY_START_DISPO = 1;
	const NOT_DISPO = 0;
	const ERROR = -1;
		
	public function __construct( $nom_client, $H_ouverture_client="08:00", $H_fermeture_client="18:00", $equipe_client="NoTeam", $compte_lewe=1, $compte_lesjours_feries=0, $laborne_min = 21, $laborne_max = 30, $laborne_min_decro = 21, $laborne_max_decro = 30,  $la_SLA = 80, $couleur = "000000", $come_from_bdd = 0 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_11");

		$this->nom = $nom_client;
		$this->nick = $this->sans_espaces( $nom_client ); 
		$this->H_ouverture = $H_ouverture_client;
		$this->H_fermeture = $H_fermeture_client;
		$this->equipe = $equipe_client;
		$this->compte_we = $compte_lewe;
		$this->compte_jf = $compte_lesjours_feries;
		$this->borne_min = $laborne_min;
		$this->borne_max = $laborne_max;
		$this->borne_min_decro = $laborne_min_decro;
		$this->borne_max_decro = $laborne_max_decro;
		$this->SLA = $la_SLA;
		$this->couleur = $couleur;
		$this->is_select = 0;
		$this->graph_par_jour = new ClassArray();
		$this->graph_total = new ClassArray();
		$this->graph_total_moyenne = new ClassArray();
		
		//mise a jour des dates en allant scanner la BDD
		if( $come_from_bdd == 1 )
		{
			$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM dates WHERE client='$nom_client'" ));
			$this->dispo_min = $donnees['date_debut'];
			$this->dispo_max = $donnees['date_fin'];
		}
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_10");

		return $this->nom;
	}
	public function is_dispo( $date1, $date2 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_9");

		// $D1_is_dessus = false;
		// $D2_is_dessous = false;
		@$resultat = NOT_DISPO;
		
		if( $date1 <= $date2 )
		{
			// Si 1ere date est dans l'interval
			if( $this->dispo_min <= $date1 and $date1 <= $this->dispo_max )
			{
				// Si 2eme date dans l'interval.
				if( $this->dispo_min <= $date2 and $date2 <= $this->dispo_max )
				{
					// Alors FULL_DISPO
					@($resultat = FULL_DISPO);			
				}
				else if( $date2 > $this->dispo_max )
				{
					// Date 1 OK mais date 2 NOK
					@$resultat = ONLY_START_DISPO;
				}
			}	
			// Si la 1ere date en dessous de l'interval
			else if( $date1 <= $this->dispo_min )
			{
				// Si 2eme date dans l'interval.
				if( $this->dispo_min <= $date2 and $date2 <= $this->dispo_max )
				{
					// Alors ONLY_END_DISPO
					@$resultat = ONLY_END_DISPO;			
				}
				// Si 2eme date au dessus de l'interval
				else if( $date2 > $this->dispo_max )
				{
					//les dates entourent l'interval.
					@$resultat = END_START_NOT_DISPO;
				}
				// Si 2eme date en dessous
				else if( $date2 < $this->dispo_min )
				{
					// Les 2 dates dont en dessous
					@$resultat = NOT_DISPO;
				}
			}
			// SI 1ere date au dessus de l'interval...
			else
				@$resultat = NOT_DISPO;
		}
		else
			@$resultat = ERROR;
		
		return $resultat;

	}
	public function sans_espaces( $chaine = "" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_8");

		if( $chaine == "" )
			$chaine = $this->nom;
		return str_replace( " ", "_", $chaine);
	}
	public function set_Houverture( $h_ouverture ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_7");

		$this->H_ouverture = $h_ouverture;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET h_ouverture='$h_ouverture'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_Hfermeture( $h_fermeture ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_6");

		$this->H_fermeture = $h_fermeture;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET h_fermeture='$h_fermeture'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_equipe( $equipe ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_5");

		$this->equipe = $equipe;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET equipe='$equipe'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_sla( $sla ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_4");

		$this->SLA = $sla;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET sla='$sla'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_borne_min( $min ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_3");

		$this->borne_min = $min;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET min='$min'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_borne_max( $max ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_2");

		$this->borne_max = $max;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET max='$max'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_jf( $jf ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_1");

		$this->compte_jf = $jf;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_jf='$jf'  WHERE client = '$client'") or die(mysql_error());
	}
	public function set_we( $we ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_client.php_0");

		$this->compte_we = $we;
		$client = $this->nom;
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_we='$we'  WHERE client = '$client'") or die(mysql_error());
	}
}

?>