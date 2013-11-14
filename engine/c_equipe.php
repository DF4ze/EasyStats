<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_equipe.php_3");

class equipe extends gp_clients
{
	public function __construct( $nom_equipe="Nawak", $come_from_bdd = 0 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_equipe.php_2");

		$this->liste_clients = new ClassArray();
		
		// Si on a pas donn�e de nom ... alors on laisse par defaut...
		if( $nom_equipe == "Toto" )
		{
			$this->nom = $nom_equipe;
		}
		else
		{
			// SI on donne un nom alors on v�rifie si ce nom apparait dans les �quipes enregistr�es
			// Si oui -> On cr�� les clients
			// Si non -> On attribut juste le nom.
			$this->nom = $nom_equipe;
			
			// Si la requete est faite depuis la BDD ... alors on scanne les clients de cette �quipe.
			if( $come_from_bdd == 1 )
			{				
				$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM clients WHERE equipe='$nom_equipe' ORDER BY client ASC" )or die(mysql_error()); 
				$compte = 1;
				while( $donnees = mysql_fetch_array($requete) )
				{
					$this->add_client( new client( $donnees['client'], $donnees['h_ouverture'], $donnees['h_fermeture'], $donnees['equipe'], $donnees['compte_we'], $donnees['compte_jf'], $donnees['min'], $donnees['max'], $donnees['min_decro'], $donnees['max_decro'], $donnees['sla'], $donnees['color'], 1 ));
					//echo "Contruct Equipe : Client N�$compte ajout� : ".$donnees['client']."<br/>";
					$compte++;
				}			
			}
		}
	}
	public function add_client( client $client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_equipe.php_1");

		$this->liste_clients->add( $client );
		//$client->equipe = $this->nom;
	}
	public function have_clients_dispo( $date1, $date2 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_equipe.php_0");

	
		$SomeOneDispo = false;
		
		$i=0;
		while( $i < $this->liste_clients->count() )
		{
			$dispo = $this->liste_clients[$i]->is_dispo( $date1, $date2 );
			//if( $dispo > 0 ) // Dommage ... ca ne fonctionne pas... :(
			if( @($dispo == END_START_NOT_DISPO or $dispo == FULL_DISPO or $dispo == ONLY_END_DISPO or $dispo == ONLY_START_DISPO) )
				$SomeOneDispo = true;
				
			//echo "Dispo pr client : ".$this->liste_clients[$i]." : ".$dispo."<br/>";
			
			$i++;
		}

		return $SomeOneDispo;
	}
}

?>