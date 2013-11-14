<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_12");

class stat extends equipe
{
	public $liste_equipes;	
	public $liste_clients;	
	
	public function __construct( $from_bdd = 0 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_11");

		$this->liste_equipes = new ClassArray(); 
		$this->liste_clients = new ClassArray(); 
		$this->maj_bases();
		
		if( $from_bdd == 1 )
		{
			// Acces a la BDD pour lister les �quipes et clients... + creer les objets
			$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT equipe FROM clients ORDER BY equipe ASC" )or die(mysql_error()); 
			while( $donnees = mysql_fetch_array($requete) )
			{
				$this->add_equipe( new equipe( $donnees['equipe'], 1 ));
			}
		}
	}
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_10");

		return "Stat fait Coucou!<br/>";
	}
	public function add_equipe( equipe $equipe ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_9");

		// SI l'�quipe n'existe pas ... alors on l'ajoute ... ainsi que ses clients.
		if( $this->liste_equipes->find( $equipe ) == -1 )
		{
			$indice = $this->liste_equipes->count();
			$this->liste_equipes->add($equipe);
			
			// On ajoute les clients de l'�quipe au tableau client 
			/////////// Ceci est une action inutile ... et surtout non r�alisable.....
			// Je m'explique :
			// Au dessus on ajoute l'�quipe dans notre tableau de liste d'�quipe.
			// Mais ... les classes en PHP sont g�r�es avec des pointeurs.... 
			// cad exemple : $new = $classe_existante;
			// Nous ne venons pas de cr�er une nouvelle classe nomm�e $new ... 
			// mais nous venons de creer un pointeur VERS la "classe_existante".... 
			// ainsi, si cette classe_existante possede un tableau ... il y sera toujours ..!
			
			$i_client = 0;
			//echo "Stat::Add_equipe : Nombre de client dans la nouvelle �quipe : ".$equipe->liste_clients->count()."<br/>";
			while( $i_client < $equipe->liste_clients->count() )
			{
				//echo " i= $i_client / Nb Clients : ".$equipe->liste_clients->count()."<br/>";
				// $this->liste_equipes[$indice]->add_client($equipe->liste_clients[$i_client]);
				
				// On ajoute juste les clients a la liste_clients de la class Stat.
				$this->liste_clients->add( $equipe->liste_clients[$i_client] );
				$i_client++;
			}
		
		}
		else
		{
			$indice = $this->liste_equipes->find( $equipe );
		
			// On ajoute les clients de l'�quipe au tableau client 
			$i = 0;
			while( $i < $equipe->liste_clients->count() )
			{
				$this->liste_equipes[$indice]->add_client($equipe->liste_clients[$i]);
				$this->liste_clints->add($equipe->liste_clients[$i]);
				$i++;
			}			
		}
		
	}
	public function del_equipe( equipe $equipe ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_8");

		$this->liste_equipes->del( $equipe );	
	}	
	public function get_nb_equipe(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_7");

		return $this->liste_equipes->count();
	}
	public function maj_bases(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_6");

	
		// MAJ base Client
		$this->maj_base_clients();
		$this->vider_dates();
		$this->maj_base_dates();
	}
	public function vider_dates(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_5");

		Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE dates") or die(mysql_error());
	}
	public function maj_base_clients(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_4");

		
		$requete = sqlsrv_query( $_SESSION['odbc_connect'], "SELECT DISTINCT CallServiceID FROM Table_InboundVoiceCalls_Blagnac");
		while( $donnees = sqlsrv_fetch_array($requete) )
		{
			$client = $donnees['CallServiceID'];
			if( !mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT client FROM clients WHERE client='$client' ") ) )
				Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO clients VALUES('', '$client', 'NoTeam', '08:00', '18:00', '80', '21', '30', '21', '30', '0', '0', '000000' )") or die( "Class Stat Function maj_base_clients() | Erreur d'insertion du client $client dans la BDD : ".mysql_error()." line 2740" );
		}
	}
	public function maj_base_dates(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_3");

		
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT DISTINCT client FROM clients");
		while( $donnees = mysql_fetch_array($requete) )
		{
			$client = $donnees['client'];
			// Un client peut etre "" si on a cr�� une �quipe ... dans laquelle il n'y a pas de client ... 
			// Mais il y a forc�ment au moins un client par �quipe ... si �quipe vide .. alors client = "";
			if( $client != "" )
			{
				$donnees1 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MIN( CallTime )as min FROM Table_InboundVoiceCalls_Blagnac WHERE CallServiceID = '$client'")) or die( "Pb MAJ Dates Clients : ".mysql_error() );	
				$donnees2 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MAX( CallTime )as max FROM Table_InboundVoiceCalls_Blagnac WHERE CallServiceID = '$client'"));
				
				$dateMin = $donnees1['min']->format('Y/m/d'); 
				$dateMax = $donnees2['max']->format('Y/m/d'); 
				// $dateMin = $donnees1['min']; 
				// $dateMax = $donnees2['max']; 
				
				Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO dates VALUES( '', '$client', '$dateMin', '$dateMax' )");	
			}
		}
	}
	public function have_equipes_dispo( $date1, $date2 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_2");

	
		$SomeOneDispo = false;
		
		$i=0;
		while( $i < $this->liste_equipes->count() )
		{
			$dispo = $this->liste_equipes[$i]->have_clients_dispo( $date1, $date2 );
			if( $dispo == true )
				$SomeOneDispo = true;
			$i++;
		}

		return $SomeOneDispo;
	}	
	public function is_clients_select(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_1");

	
		$ClientSelect = false;
		
		$i=0;
		while( $i < $this->liste_clients->count() )
		{
			$dispo = $this->liste_clients[$i]->is_select;
			if( $dispo == true )
				$ClientSelect = true;
			$i++;
		}

		return $ClientSelect;
	}	
	public function unselect_clients(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_stat.php_0");

			
		$i=0;
		while( $i < $this->liste_clients->count() )
		{
			$this->liste_clients[$i]->is_select = 0;

			$i++;
		}
	}	
	


}


?>