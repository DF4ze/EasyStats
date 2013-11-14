<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_8");

class interval_jours //extends jour
{
	public $liste_jours;
	private $client;
	private $date_debut;
	private $date_fin;
	
	public function __construct( datetime $ladate1, datetime $ladate2, client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_7");

		$this->date_debut = new datetime( $ladate1->format('Y/m/d'));
		$this->date_fin = new datetime( $ladate2->format('Y/m/d'));
		$this->client = $client;
		$this->liste_jours = new ClassArray();
		
		$date_pivot = new datetime( $this->date_debut->format('Y/m/d'));
		while( $date_pivot->format("Y/m/d") <=  $this->date_fin->format("Y/m/d") )
		{
			// Oblig� de faire ceci car ... si on passe un datetime dans le tableau ... et qu'on modify ce datetime par la suite (+1 j par exemple;))
			// Et bien la valeur dans le tableau se modify aussi .... donc tout le tableau se retrouve a la derniere valeur du tableau.....!
			//$date_saisie = new datetime( $date_pivot->format('Y/m/d H:i:s') );

			$this->liste_jours->add( new jour( new datetime( $date_pivot->format('Y/m/d H:i:s') )/*$date_saisie*/, $client ));
			$date_pivot->modify ('+1 day');
		}
		//echo "Construct Intervals : Date1 : ".$this->date_debut->format('Y/m/d')." Date2 : ".$this->date_fin->format('Y/m/d')."<br/>";
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_6");

		/*$list = '';
		$i = 0;
		while( $i < $this->liste_jours->count() )
		{
			$list = $list."[".$i."] ".$this->liste_jours[$i]."  ";
			$i++;
		}
		return $list;
		*/
		$last_offset = $this->liste_jours->count() - 1;
		$texte = $this->liste_jours[0].'->'.$this->liste_jours[$last_offset];
		return $texte;
		
	}
	public function add_jour( jour $jour ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_5");

		$this->liste_jours->add( $jour );
	}
	public function del_jour( jour $jour ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_4");

		
		$this->liste_jours->del( $jour );
		
		/*$num = get_offset_jour( $jour );

		$i = $num+1;
		while( $i <= $this->nb_jours )
		{
			$this->liste_jours["$num"] = $this->liste_jours["$i"];
			$i ++;
			$num ++;
		}
		unset( $this->liste_jours[ $this->nb_jours ] );
		$this->nb_jours --;
		*/
	}
	public function trier_jour( $tri = "ASC" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_3");

		if( $tri == "ASC" )
		{
			$this->$liste_jours->trier();
		}
		else
		{
			$this->$liste_jours->trier_desc();
		}
	}
	public function get_date_debut(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_2");

		return $this->date_debut;
	}
	public function get_date_fin(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_1");

		return $this->date_fin;
	}
	public function get_client(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_interval_jours.php_0");

		return $this->client;
	}
}

?>