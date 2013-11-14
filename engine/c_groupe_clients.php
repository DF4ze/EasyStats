<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_6");

class groupe_clients //extends interval_jours
{
	public $equipe;
	public $liste_clients;
	public $liste_intervals;
	private $date_debut;
	private $date_fin;
//	public $nb_clients;
	
	public function __construct( $equipe = "", $var_date_debut = "", $var_date_fin = "" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_5");

	
		if( $equipe == "" )
		{
			$this->equipe = new equipe();
			$this->liste_clients = new ClassArray();
			$this->liste_intervals = new ClassArray();
		}
		else
		{
			// Attibution des variables
			$this->liste_clients = new ClassArray();
			$i=0;
			while( $i < $equipe->liste_clients->count() )
			{
				$this->liste_clients[$i] = $equipe->liste_clients[$i];
				$i++;
			}
			// echo "constructeur gp_client : nb clients dans l'�quipe donn�e : ".$equipe->liste_clients->count()."<br/>";
			// echo "constructeur gp_client : nb clients dans la liste : ".$this->liste_clients->count()."<br/>";
			
			$this->equipe = $equipe;
		
				
			if( $var_date_debut != "" )
				$this->date_debut = new datetime( $var_date_debut->format( 'Y/m/d' ) );
				
			if( $var_date_fin != "" )
				$this->date_fin = new datetime( $var_date_fin->format( 'Y/m/d' ) );
			
			// echo "Construct GpClient : Nb Clients : ".$this->liste_clients->count().'<br/>';
			
			
			// Cr�ation d'un tableau pour stocker par rapport au client les Intervalles de jours.
			// Offset client = offset intervalle pour ce client
			$this->liste_intervals = new ClassArray();
			$i=0;
			while( $i < $this->liste_clients->count() )
			{
				//echo "constructeur gp_client : nb clients dans la liste : ".$this->liste_clients."<br/>";
				//echo "Construct GpClient : i:".$i.' date_debut: '.$var_date_debut->format( 'Y/m/d' ).' Date Fin: '.$var_date_fin->format( 'Y/m/d' ).' ListeClient[i]:'.$this->liste_clients[$i].'<br/>';
				$this->liste_intervals[$i] = new interval_jours( new datetime( $var_date_debut->format( 'Y/m/d' ) ) , new datetime( $var_date_fin->format( 'Y/m/d' ) ), $this->liste_clients[$i]);
				//echo "liste_intervals[i]:".this.'<br/>';
				$i++;
			}
		}
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_4");

		$list = '';
		$i = 0;
		while( $i < $this->liste_clients->count() )
		{
			$list = $list."[".$i."] ".$this->liste_clients[$i]."  ";
			$i++;
		}
		return $list;
	}
	public function add_client( datetime $date_debut, datetime $date_fin, client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_3");

		$this->equipe->add_client( $client );
		$this->liste_clients->add( $client );
		$this->liste_intervals->add( new interval_jours( new datetime( $date_debut->format( 'Y/m/d' )), new datetime( $date_fin->format( 'Y/m/d' )), $client) );
	}
	public function del_client( client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_2");

		$i = $this->liste_clients->del( $client );
		$this->liste_intervals->del($i);
	}
	public function get_date_debut(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_1");

		return $this->date_debut;
	}
	public function get_date_fin(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_groupe_clients.php_0");

		return $this->date_fin;
	}	
}

?>