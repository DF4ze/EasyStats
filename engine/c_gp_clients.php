<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_6");

class gp_clients extends client
{
	public $nom;
	public $liste_clients;
	
	public function __construct( $nom_groupe="NoTeam" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_5");

		$this->liste_clients = new ClassArray(); // Ligne obligatoire !
		
		if( $nom_groupe == "NoTeam" )
		{
			$this->nom = $nom_groupe;
		}
		else
		{
			// SI on donne un nom alors on v�rifie si ce nom apparait dans les �quipes enregistr�es
			// Si oui -> On cr�� les clients
			// Si non -> On attribut juste le nom.
			$this->nom = $nom_groupe;
		}
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_4");

		return $this->nom;
	}
	public function add_client( client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_3");

		$this->liste_clients->add( $client );
	//	$client->equipe = $this->nom;
	}
	public function del_client( client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_2");

		$this->liste_clients->del( $client );
	}
	public function get_nb_client(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_1");

		return $this->liste_clients->count();
	}
	public function set_nom( $nom ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_gp_clients.php_0");

		$this->nom = $nom;
	}
}

?>