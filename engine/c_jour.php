<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_8");

class jour //extends groupe_appels
{
	public $date;
	public $is_we;
	public $is_jf;
	public $quel_jour;
	public $groupe_appels;
	// A retirer a l'avenir
	private $date_debut;
	private $date_fin;
	private $client;
	
	public function __construct( datetime $jour, client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_7");

		 //parent::__construct(); // Appel du constructeur de la classe h�rit�e.
		$date1 = new datetime( $jour->format('Y/m/d')." ".$client->H_ouverture );
		$date2 = new datetime( $jour->format('Y/m/d')." ".$client->H_fermeture );
		
		// A zapper a l'avenir :
		$this->date_debut = new datetime( $date1->format('Y/m/d H:i:s'));
		$this->date_fin = new datetime( $date2->format('Y/m/d H:i:s'));
		$this->client = $client;
		
		
		
		$this->groupe_appels = new groupe_appels( $date1, $date2, $client );
		//$this->liste_appels = new ClassArray(); -> Pour l'heritage ;)
	
		$this->date = $jour;
		$this->client = $client;
				
		// Check si f�ri� ou we...
		$this->is_we = $this->is_weekend( $jour );
		$this->is_jf = $this->is_jour_ferie( $jour );
		$this->quel_jour = $this->quel_jour( $jour, 0 );
		
	//	echo $this->quel_jour."<br/>";
	//	echo "Construct Jour : Date : ".$this->date->format('Y/m/d')."<br/>";
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_6");

		return $this->date->format('d/m');
	}
	static function is_jour_ferie( datetime $date ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_5");

		// on enleve l'ann�e et formate la date de facon a avoir %mm/jj% pour la requete LIKE
		// $ind2 = find_in_date($date, 2);
		// $ind3 = find_in_date($date, 3);
		$new_date = "%".$date->format("m/d");
		
		$jno = 0;
		// Si on retrouve le jour et le mois, on v�rifie s'il s'agit d'une date ANNUELLE 
		if( mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT date FROM hno WHERE date LIKE '$new_date'" ) ) )
		{
			// Si annuel, alors qu'importe l'ann�e : il faut supprimer la date.
			$donnees2 = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT date, is_annuel FROM hno WHERE date LIKE '$new_date'"));
			if( $donnees2['is_annuel'] == 1)
			{
				$jno = 1;
			}
			else
			{
				// V�rifier si l'ann�e �galement est la meme.
				if( $donnees2['date'] == $date->format( "Y/m/d" ) )
					$jno = 1;			
			}
		}
		
		return $jno;
	}
	static function is_weekend( datetime $date ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_4");

		$is_we = 0;
		
		$Num_jour = date( "w", strtotime( $date->format( "Y/m/d" ))); 
		
		if( $Num_jour == 0 or $Num_jour == 6 )
			$is_we = 1;
		
		return $is_we;
	}
	static function quel_jour( datetime $date, $num = 1 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_3");

		$jour = -1;
		
		$Num_jour = date( "w", strtotime( $date->format( "Y/m/d" ))); 
		
		if( $num == 1 ) // On retourne un chiffre. 0 pour Lundi : 6 pour Dimanche
		{
			if( $Num_jour == 0 )
				$jour = 6;
			else if( $Num_jour == 1 )
				$jour = 0;
			else if( $Num_jour == 2 )
				$jour = 1;
			else if( $Num_jour == 3 )
				$jour = 2;
			else if( $Num_jour == 4 )
				$jour = 3;
			else if( $Num_jour == 5 )
				$jour = 4;
			else if( $Num_jour == 6 )
				$jour = 5;
		}
		else // Sinon on retourne le jour textuellement
		{
			if( $Num_jour == 0 )
				$jour = 'Dimanche';
			else if( $Num_jour == 1 )
				$jour = 'Lundi';
			else if( $Num_jour == 2 )
				$jour = 'Mardi';
			else if( $Num_jour == 3 )
				$jour = 'Mercredi';
			else if( $Num_jour == 4 )
				$jour = 'Jeudi';
			else if( $Num_jour == 5 )
				$jour = 'Vendredi';
			else if( $Num_jour == 6 )
				$jour = 'Samedi';			
		}

		//echo $jour."<br/>";
		
		return $jour;
	}
	public function get_date_debut(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_2");

		return $this->date_debut;
	}
	public function get_date_fin(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_1");

		return $this->date_fin;
	}
	public function get_client(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_jour.php_0");

		return $this->client;
	}
}

?>