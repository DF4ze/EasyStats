<?php

class regroup_clients extends base_client //c_virtual       L'extends de Base_Client permet de gérer le nick name ;) sans avoir a le réimplémenter :)
{
	// Contient la liste de tout les clients de l'équipe
	protected $list_clients;
	
	public function __construct( $_Params ){
		parent::__construct( $_Params );
		
		if( isset( $_Params['list_clients'] ) )
			if( count( $_Params['list_clients'] ) != 0 ){
				$this->list_clients = $_Params['list_clients'];
				sort( $this->list_clients );
			}
	}
	
	public function set_list_clients( $list_clients ){
		if( count( $list_clients ) != 0 ){
			$this->list_clients = $list_clients;
			sort( $this->list_clients ); // garde une unité dans le tableau... deplus ca simplifie la suppr_client ;)
		}
	}
	public function get_list_clients(){
		return $this->list_clients;
	}
	
	public function add_client( $client ){
		// On dit au client de quel équipe il fait parti.
		$client->set_equipe( $this->get_nom() );
		
		// !!!!! Clone ou pas clone??? ... je pense qu'il faut cloner ... car dans le suppr_client...
		// On va faire un UNSET ... ce qui va killer l'objet client instancié.
		$this->list_clients[ count($this->list_clients) ] = clone $client;
		
		// On range les clients par ordre alpha.
		sort( $this->list_clients );
	}
	public function suppr_client( $offset ){
		// Si l'offset est valide.
		if( isset ($this->list_clients[$offset]) ){
			unset( $this->list_clients[$offset] );
			sort( $this->list_clients );
		}
	}
	
	public function find_client( $nom_client ){
		// On va lancer une comparaison des NickNames ... donc on met le nom_client dans une classe client de facon a normalyzer le Nick.
		$client = new client( array( "nom" => $nom_client ));
		
		foreach( $this->list_clients as $key => $value  ){
			if( $value == $client->get_nick() )
				return $key;
		} 
		return false;
	}
}

class correlation_tab extends regroup_clients
{
	protected $correl_Tab; 	// Tab qui va contenir les corrélations entre les différentes colonnes des différents clients.
							// Sous forme : correl_Tab[N° correlation][clients][colonne] = true
							
	public function __construct( $_Params = '' ){
		// On appelle le parent d'abord, comme ca les clients seront intégrés si présent dans les params
		parent::__construct( $_Params );
		
		if( isset( $_Params['correl_Tab'] ) )
			$this->set_correl_tab( $_Params['correl_Tab'] );
		else{
			// Valeur par defaut : 
		}
	}
	
	public function set_correl_tab( $tab ){
		if( isset( $tab ) )
			if( $tab != "" )
				$this->correl_Tab = $tab;
	}
	public function get_correl_tab(){
		return $this->correl_Tab;
	}
	public function add_colonne_correlation( $num_correl, $nom_client, $num_colonne ){
		if( isset( $num_correl ) and isset( $nom_client ) and isset( $num_colonne ) ){
			// Si tout les champs sont bien rempli alors on attribu la corrélation.
				
			// Petite bidouille pour etre sûr que le nom_client soit bien formaté :pas d'accent etc... et tt en minuscule :
			$_Params['nom'] = $nom_client;
			$client = new client( $_Params );
			$this->correl_Tab[ $num_correl ][ "$client" ][ $num_colonne ] = true;
			// $this->correl_Tab[ $num_correl ][ "$client" ] = $num_colonne;

		}else{
			echo "add_colonne_correlation : Un des parametres n'est pas renseigné<br/>";
		}
	}	
	public function supp_colonne_correlation( $num_correl, $nom_client, $num_colonne ){
		if( isset( $num_correl ) and isset( $nom_client ) and isset( $num_colonne ) ){
				if( isset( $this->correl_Tab[ $num_correl ][ $nom_client ][ $num_colonne ] ) ){
					// Si ca existe, on le supprime :)
					// Comme il ne peut pas y avoir 2 colonnes pour un meme client ... on supprime le client
					unset( $this->correl_Tab[ $num_correl ][ $nom_client ] );
					echo "Le N° Correl $num_correl, NomClient $nom_client a été supprimé<br/>";
					
				}else{
					echo "Le N° Correl $num_correl, NomClient $nom_client, NumColonne $num_colonne n'existe pas";
				}
			// }
		}else{
			echo "Un parametre est manquant.";
		}
	}
	public function add_correlation( $num_correl, $tab_clients_colonnes ){
		if( isset( $num_correl ) and isset( $tab_clients_colonnes ) ){
			if( $num_correl != "" and $tab_clients_colonnes != "" ){
				$this->correl_Tab[ $num_correl ] = $tab_clients_colonnes;
			}
		}
	}
	public function supp_correlation( $num_correl ){
		if( isset( $num_correl ) ){
			if( $num_correl != ""  ){
				if( isset( $this->correl_Tab[ $num_correl ] ) )
					unset( $this->correl_Tab[ $num_correl ] );
			}
		}
	}
	
}

class compare_totaux extends correlation_tab
{
	// va comparer les options/colonnes des différents clients de facon a faire ressortir les colonnes similaires.
	// Au final ... option qui a une utilité limitée ... et demande beaucoup de code ...
	// L'option est donc actuellement abandonnée.
	
	public function compare_colonne(){
		// Va comparer les colonnes des différents clients.
		// retourne un tableau avec les corélations trouvées.
		
		
	}
}

class options_totaux extends compare_totaux
{
	// va détenir les options de calculs de la correlation_tab
	// Moyenne? Somme? Min? Max?
	// Tableau sous la forme : $options_totaux[ N°Correl ]['type'] = sum,avg,min ou max
	//													   ['seuil'] = Valeur seuil pour les calculs de moyen ou somme.
	//																	en dessous de cette valeur, ca ne sera pas compté
	protected $options_totaux;
	
	public function __construct( $_Params= '' ){
		parent::__construct( $_Params );
		
		if( isset( $_Params['equipe_options_totaux'] ) )
			$this->options_totaux = $_Params['equipe_options_totaux'];
	} 
	
	public function get_options_totaux(){
		return $this->options_totaux;
	} 
	public function set_options_totaux( $options ){
		$this->options_totaux = $options;
	} 
} 

class checked_clients extends options_totaux
{
	// Class qui va gerer les clients demandés pour les stats
	public function get_checked_clients(){
		$tab = array();
		
		$i = 0;
		foreach( $this->list_clients as $key => $client  ){
			if( $client->checked )
				$tab[$i++] = $client;
		} 		
		
		return $tab;
	}

	public function is_one_checked(){
		$is_checked = false;
		if( count( $this->list_clients ) != 0 ){
			foreach( $this->list_clients as $key => $client ){
				if( $client->is_checked() )
				$is_checked = true;
			}			
		}
		return $is_checked;
	}
}

class load_clients extends checked_clients
{
	public function load_checked_clients(){
		// On récup les clients checked
		$list = $this->get_checked_clients();
			
		// On charge leurs parametres
		$ok = false;
		$i=0;
		foreach( $list as $key => $client ){
			//echo "Client ".$client." Checked and : ";
			if( $list[$key]->load_from_bdd() ){ // ne pas mettre $client->load... car ca ne fonctionne pas
				$ok[$i] = $client;
				$i++;
			}else{
				;// erreur de chargement ... le client n'est pas dans la base...?
			}
		}
		
		return $ok;
	}
}

class stock_sql extends load_clients
{
	public function maj_bdd(){
		// Si le client existe : On le met a jour, sinon on le créé.
		if( @mysql_fetch_array( mysql_query("SELECT nomclient FROM ".$_SESSION[ 'tb_equipes' ]." WHERE nomequipe = '".$this->get_nick()."'")) ) {
			echo "<br/>MAJ<br/>";
			$this->maj_table_equipe();
		}else{
			echo "<br/>CREATE<br/>";
			$this->create_table_equipe();
		}
	}
	
	protected function create_table_equipe(){
		mysql_query("INSERT INTO ".$_SESSION[ 'tb_equipes' ]." VALUES
						('',
						'".$this->get_nick()."',
						'".$this->get_equipe()."',
						'".$this->get_h_ouverture()."',
						'".$this->get_h_fermeture()."',
						'".$this->get_work_jf()."',
						'".$this->get_work_we()."',
						'".serialize( $this->get_options() )."',
						'".$this->get_sla()."',
						'".$this->get_color()."')") or die( "Erreur de création du client : ".$this->get_nom()."<br/> Erreur : ".mysql_error()."<br/>" );
	}
	protected function maj_table_equipe(){
		mysql_query( "UPDATE ".$_SESSION[ 'tb_equipes' ]." SET 
						nomequipe = '".$this->get_equipe()."', 
						h_ouverture = '".$this->get_h_ouverture()."',
						h_fermeture = '".$this->get_h_fermeture()."',
						work_jf = '".$this->get_work_jf()."',
						work_we = '".$this->get_work_we()."',
						tabbornes = '".serialize( $this->get_options() )."',
						sla = '".$this->get_sla()."',
						color = '".$this->get_color()."'
						
						WHERE nomclient = '".$this->get_nick()."'" )or die( "Erreur de mise à jour du client : ".$this->get_nom()."<br/> Erreur : ".mysql_error()."<br/>" );
	}

}




class equipe extends stock_sql
{
	
}

?>