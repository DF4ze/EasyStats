<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_23");

class requete //extends groupe_clients
{
	public $groupe_clients;
	public $tab_clients; // $tab_client[$i] nous donne le client a la case $i ... et $tab_equipe[$i] nous donne l'�quipe du client qui est a la case $i.
	public $tab_equipes; // autant d'�quipes que de clients... la ligne correspondant au client dans $tab_clients == ligne de l'�quipe de ce client dans $tab_clients
	public $liste_equipes; // Liste d'�quipes distinctes. (pas 2 fois la meme �quipe)
	//public $init;
	
	public function __construct(  $gp_clients = "", $ledebut = "", $lafin = "" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_22");

		
		// $this->init = true;
		// echo "Init class Requete<br/>";
		
		
		if( $gp_clients == "" )
		{
			$this->groupe_clients = new groupe_clients();
			$this->tab_clients = new ClassArray();
			$this->tab_equipes = new ClassArray();
			$this->liste_equipes = new ClassArray();
		}
		else
		{
			$this->groupe_clients = new groupe_clients(  $gp_clients, new datetime( $ledebut->format('Y/m/d')), new datetime( $lafin->format('Y/m/d')) );
			$this->tab_clients = $gp_clients->liste_clients;
			$this->tab_equipes = new ClassArray();
			$this->liste_equipes = new ClassArray();
			
			// Offset Tab_Client == Tab_Equipe
			$i=0;
			while( $i < $this->tab_clients->count() )
			{
				$this->tab_equipes[$i] = $this->tab_clients[$i]->equipe;
				$i++;
			}
			
			// Liste  de toute les �quipes Distinctes.
			$i=0;
			$j=0;
			while( $i < $this->tab_clients->count() )
			{
				if( $this->liste_equipes->find( $this->tab_clients[$i]->equipe ) == -1 )
				{
					$this->liste_equipes[$j] = $this->tab_clients[$i]->equipe;
					$j++;
				}
					
				$i++;
			}
		}
		// echo "Contruct Requete : Tab Equipes : ".$this->tab_equipes."<br/>";
		// echo "Contruct Requete : Tab Clients : ".$this->tab_clients."<br/>";
		// echo "Contruct Requete : Listes Equipes : ".$this->liste_equipes."<br/>";
		
	}
	public function get_jour( $i_equipe, $j_client, $k_date ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_21");

		// On r�cup l'�quipe
		$equipe = $this->liste_equipes[$i_equipe];
		
		$i_courrant = 0;
		$i_compte = 0;
		$i_memo = false;
		
		// Retrouver emplacement "client" dans le "tab_clients"
		// On fait le tour de ts les clients.
		while( $i_courrant < $this->tab_clients->count() )
		{
			// Si l'�quipe correspond... alors on compte ce client
			if( $this->tab_equipes[ $i_courrant ] == $equipe )
			{
				// Si le compte == num du client alors on m�morise la position dans le "tabclient"
				if( $i_compte == $j_client )
				{
					$i_memo = $i_courrant;
					$i_courrant = $this->tab_clients->count(); // Arrete la boucle.
				}
				else
					$i_compte++;
			}
			
			$i_courrant++;
		}
		
		
		if( $i_memo === false )
		{
			return -1;
		}
		else
		{
			return $this->groupe_clients->liste_intervals[ $i_memo ]->liste_jours[ $k_date ];
		}

		/*
		//On fait un scan du tab_client tant que Client/Equipe != $client et $equipe.
		$i=0;
		while( $i < $this->tab_clients->count() )
		{
			// Si Tabclient = Client et TabEquipe = Equipe alors on a trouv� le client demand�....
			if( $this->tab_clients[$i] == $client AND $this->tab_equipes[$i] == $equipe )
			{
				// Reste plus qu'a r�cup�r� la date � l'indice "Indice".
				return $this->groupe_clients->liste_intervals[$i]->liste_jours[$indice];
			}
			$i++;
		}
		*/
	}
	public function get_client( $i_equipe, $j_client ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_20");

	
		// On r�cup l'�quipe
		$equipe = $this->liste_equipes[$i_equipe];
		
		$i_courrant = 0;
		$i_compte = 0;
		$i_memo = false;
		
		// Retrouver emplacement "client" dans le "tab_clients"
		// On fait le tour de ts les clients.
		while( $i_courrant < $this->tab_clients->count() )
		{
			// Si l'�quipe correspond... alors on compte ce client
			if( $this->tab_equipes[ $i_courrant ] == $equipe )
			{
				// Si le compte == num du client alors on m�morise la position dans le "tabclient"
				if( $i_compte == $j_client )
				{
					$i_memo = $i_courrant;
					$i_courrant = $this->tab_clients->count(); // Arrete la boucle.
				}
				else
					$i_compte++;
			}
			
			$i_courrant++;
		}
		
		if( $i_memo === false )
		{
			return -1;
		}
		else
		{
			return $this->tab_clients[$i_memo];
		}
		
		
		
		
/*		$i_tabequipe = 0;
		$j = 0;
		// On passe tout le tableau des �quipes
		while( $i_tabequipe < $this->tab_equipes->count() )
		{	
			// Si �quipe du tableau == l'�quipe demand�e on compte les clients (j++)
			if( $this->tab_equipes[$i_tabequipe] == $equipe )
			{
				if( $indice == $j )
					return $this->tab_clients[$i_tabequipe];
				$j++;
			}
			
			$i_tabequipe++;
		}

		return -1;
*/
	}
	public function get_equipe( $i_equipe ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_19");

		return $this->liste_equipes[$i_equipe];
	}	
	public function get_courbe_jour( jour $jour, ClassArray $result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_18");

	
		// Pr�paration des dates.
		$debut = $jour->get_date_debut();
		$fin = $jour->get_date_fin();
		$date1 = new datetime( $debut->format( "Y/m/d H:i:s" ) );
		$date2 = new datetime( $date1->format( "Y/m/d H:i:s" ) );
		$date2->modify( "+30 min" );
		$dateMax = new datetime( $fin->format( "Y/m/d H:i:s" ) );
		
		// Pr�paration du tableau de r�sultats
		$result[0] = new ClassArray(); // Heure d'appel // A l'origine je codais : $result['value'] = .... et $result['date'] = ....
		$result[1] = new ClassArray(); // appel total // Mais semblerait que l'offset ne puisse etre que num�rique ... car au final le tableau �tait vide.
		$result[2] = new ClassArray(); // appels d�croch�s
		$result[3] = new ClassArray(); // Base statistique
		$result[4] = new ClassArray(); // ab < borne min
		$result[5] = new ClassArray(); // borne min < ab < borne max
		$result[6] = new ClassArray(); // borne max < ab
		$result[7] = new ClassArray(); // decro < borne min
		$result[8] = new ClassArray(); // borne min < decro < borne max
		$result[9] = new ClassArray(); // borne max < decro
		
		
		///////////////////////////////////////////////////////////////////////////
		/////////// Pour simplifier la recherche de ces r�sultats ... pour calculer le total par clients ...puis par �quipe
		$client = $jour->get_client();
		
		// On r�cup�re le dernier OFFSET 
		$offset = $client->graph_par_jour->count();
		
		// Cr�ation d'un Tab pour la journ�e en cours
		$client->graph_par_jour[$offset] = new ClassArray();
		// Pr�paration du tableau de r�sultats
		$client->graph_par_jour[$offset][0] = new ClassArray(); // A l'origine je codais : $result['value'] = .... et $result['date'] = ....
		$client->graph_par_jour[$offset][1] = new ClassArray(); // Mais semblerait que l'offset ne puisse etre que num�rique ... car au final le tableau �tait vide.
		$client->graph_par_jour[$offset][2] = new ClassArray(); 
		$client->graph_par_jour[$offset][3] = new ClassArray(); 
		$client->graph_par_jour[$offset][4] = new ClassArray(); // ab < borne min
		$client->graph_par_jour[$offset][5] = new ClassArray(); // borne min < ab < borne max
		$client->graph_par_jour[$offset][6] = new ClassArray(); // borne max < ab
		$client->graph_par_jour[$offset][7] = new ClassArray(); // decro < borne min
		$client->graph_par_jour[$offset][8] = new ClassArray(); // borne min < decro < borne max
		$client->graph_par_jour[$offset][9] = new ClassArray(); // borne max < decro
		///////////////////////////////////////////////////////////////////////////

		$i = 0;
		$memo = 0;
		$j = $memo;
		while( $date2->format( "Y/m/d H:i:s" ) <= $dateMax->format( "Y/m/d H:i:s" ) )
		{
			// $gp = new groupe_appels( new datetime( $date1->format("Y/m/d H:i:s") ), new datetime( $date2->format("Y/m/d H:i:s") ), $client );
						
			//echo "Date affich�e : ".$result[1][$i]." ".$result[0][$i]." appels entre ".$date1->format("H:i")." et ".$date2->format("H:i")." | Valeur i=$i <br/>";
			
			$compteur_total = 0;
			$compteur_abandons_total = 0;
			$compteur_ab_av_borne_min = 0;
			$compteur_decro = 0;
			$compteur_ab_entre_min_max = 0;
			$compteur_ab_apres_max = 0;
			$compteur_decro_av_min = 0;
			$compteur_decro_entre_min_max = 0;
			$compteur_decro_apres_max = 0;

			// On fait le tour des appels de la journ�e de facon a checker si l'appel est dans la tranche de 30min actuelle.
			// Si on �tait sure que les appels �taient rang� par ordre chrono... on pourrait chercher le 1er, le dernier ... et balayer cette plage ...
			// ...... pas envi de me prendre la tete pour l'instant :DD
			$j = $memo;
			while( $j < $jour->groupe_appels->liste_appels->count() )
			{
				$un_appel = $jour->groupe_appels->liste_appels[$j];
				
				// Pour test // qui confirme que les appels sont bien par ordre chrono
/* 				if( $i == 0 )
				{
					echo $un_appel->date->format( "Y/m/d H:i:s" )."<br/>";
				}
 */				
				
				
				
				
				// si appel dans la tranche on le compte.
				$H_appel = $un_appel->date->format( "Y/m/d H:i:s" );
				if( $H_appel >= $date1->format( "Y/m/d H:i:s" ) and $H_appel < $date2->format( "Y/m/d H:i:s" ) )
				{
					// Est-on sur une plage de crash?
					$borne_crash = 0;
					if( $un_appel->is_on_crash() ){
						$borne_crash = $un_appel->get_duree_crash();	
					}
					
					$compteur_total ++;
					// Si appel non d�croch�
					if( $un_appel->poste_operateur == "" )
					{
						$compteur_abandons_total ++;
						
						// Si appel abandonn� avant la borne min
						if( $un_appel->tps_attente_av_decro <= $client->borne_min + $borne_crash )
							$compteur_ab_av_borne_min++;
						// Si appel abandonn� entre min et max
						else if( $un_appel->tps_attente_av_decro > $client->borne_min+$borne_crash and $un_appel->tps_attente_av_decro <= $client->borne_max+$borne_crash)
							$compteur_ab_entre_min_max++;
						// Si appel abandonn� apres max
						else if( $un_appel->tps_attente_av_decro > $client->borne_max+$borne_crash )
							$compteur_ab_apres_max++;
					}
					// Sinon appel est d�croch�
					else 
					{
						$compteur_decro++;
						// Si appel d�cro avant la borne min
						if( $un_appel->tps_attente_av_decro <= $client->borne_min+$borne_crash )
							$compteur_decro_av_min++;
						// Si appel d�cro entre min et max
						else if( $un_appel->tps_attente_av_decro > $client->borne_min+$borne_crash and $un_appel->tps_attente_av_decro <= $client->borne_max+$borne_crash)
							$compteur_decro_entre_min_max++;
						// Si appel d�cro apres max
						else if( $un_appel->tps_attente_av_decro > $client->borne_max+$borne_crash )
							$compteur_decro_apres_max++;
					}
				}
				// Sinon on recale l'indicateur
				else if( $H_appel > $date2->format( "Y/m/d H:i:s" ) )
				{
					// On enregistre � partir de quel offset on a d�pass� la tranche... de facon a reparti sur cet offset la pour la tranche suivante.
					$memo = $j;
					// On arrete la boucle
					$j = $jour->groupe_appels->liste_appels->count();
				}
				$j++;
			}
			
			$result[0][$i] = $date1->format("H:i");
			$result[1][$i] = $compteur_total;
			$result[2][$i] = $compteur_decro;
			$result[3][$i] = $compteur_total - $compteur_ab_av_borne_min;
			$result[4][$i] = $compteur_ab_av_borne_min;
			$result[5][$i] = $compteur_ab_entre_min_max;
			$result[6][$i] = $compteur_ab_apres_max;
			$result[7][$i] = $compteur_decro_av_min;
			$result[8][$i] = $compteur_decro_entre_min_max;
			$result[9][$i] = $compteur_decro_apres_max;
			// Copie a l'identique de facon a retrouver les r�sultat plus facilement.(=> pour calcul total Client puis Total Equipe.)
			$client->graph_par_jour[$offset][0][$i] = $date1->format("H:i");
			$client->graph_par_jour[$offset][1][$i] = $compteur_total;
			$client->graph_par_jour[$offset][2][$i] = $compteur_decro;
			$client->graph_par_jour[$offset][3][$i] = $compteur_total - $compteur_ab_av_borne_min;
			$client->graph_par_jour[$offset][4][$i] = $compteur_ab_av_borne_min;
			$client->graph_par_jour[$offset][5][$i] = $compteur_ab_entre_min_max;
			$client->graph_par_jour[$offset][6][$i] = $compteur_ab_apres_max;
			$client->graph_par_jour[$offset][7][$i] = $compteur_decro_av_min;
			$client->graph_par_jour[$offset][8][$i] = $compteur_decro_entre_min_max;
			$client->graph_par_jour[$offset][9][$i] = $compteur_decro_apres_max;
			
			$date1->modify( "+30 min" );
			$date2->modify( "+30 min" );
			
			$i++;
		}
	}
	public function get_courbe_client( client $client, ClassArray $result, $moyenne = false ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_17");

	
		// Pr�paration des tableau de r�sultats
		$result[0] = new ClassArray(); // A l'origine je codais : $result['value'] = .... et $result['date'] = ....
		$result[1] = new ClassArray(); // Mais semblerait que l'offset ne puisse etre que num�rique ... car au final le tableau �tait vide.
		$result[2] = new ClassArray();
		$result[3] = new ClassArray();
		$result[4] = new ClassArray(); // ab < borne min
		$result[5] = new ClassArray(); // borne min < ab < borne max
		$result[6] = new ClassArray(); // borne max < ab
		$result[7] = new ClassArray(); // decro < borne min
		$result[8] = new ClassArray(); // borne min < decro < borne max
		$result[9] = new ClassArray(); // borne max < decro
		$client->graph_total[0] = new ClassArray(); 
		$client->graph_total[1] = new ClassArray(); 
		$client->graph_total[2] = new ClassArray(); 
		$client->graph_total[3] = new ClassArray(); 
		$client->graph_total[4] = new ClassArray(); // ab < borne min
		$client->graph_total[5] = new ClassArray(); // borne min < ab < borne max
		$client->graph_total[6] = new ClassArray(); // borne max < ab
		$client->graph_total[7] = new ClassArray(); // decro < borne min
		$client->graph_total[8] = new ClassArray(); // borne min < decro < borne max
		$client->graph_total[9] = new ClassArray(); // borne max < decro

		// Init � 0 + remplissage des dates // On part sur le fait qu'il y a forc�ment un jour ... tt facon : dans le ShowStat(), si pas de jour = pas d'affichage
		$j=0;
		$nb_tranches = $client->graph_par_jour[0][1]->count();
		while( $j < $nb_tranches )
		{
			// Pour le calcul du total Client
			$result[0][$j] = $client->graph_par_jour[0][0][$j];
			$result[1][$j] = 0;
			$result[2][$j] = 0;
			$result[3][$j] = 0;
			$result[4][$j] = 0;
			$result[5][$j] = 0;
			$result[6][$j] = 0;
			$result[7][$j] = 0;
			$result[8][$j] = 0;
			$result[9][$j] = 0;

			// Pour le Calcul du total Equipe
			$client->graph_total[0][$j] = $client->graph_par_jour[0][0][$j];
			$client->graph_total[1][$j] = 0;
			$client->graph_total[2][$j] = 0;
			$client->graph_total[3][$j] = 0;
			$client->graph_total[4][$j] = 0;
			$client->graph_total[5][$j] = 0;
			$client->graph_total[6][$j] = 0;
			$client->graph_total[7][$j] = 0;
			$client->graph_total[8][$j] = 0;
			$client->graph_total[9][$j] = 0;

			// Pour le calcul du moyenne Equipe
			$client->graph_total_moyenne[0][$j] = $client->graph_par_jour[0][0][$j];
			$client->graph_total_moyenne[1][$j] = 0;
			$client->graph_total_moyenne[2][$j] = 0;
			$client->graph_total_moyenne[3][$j] = 0;
			$client->graph_total_moyenne[4][$j] = 0;
			$client->graph_total_moyenne[5][$j] = 0;
			$client->graph_total_moyenne[6][$j] = 0;
			$client->graph_total_moyenne[7][$j] = 0;
			$client->graph_total_moyenne[8][$j] = 0;
			$client->graph_total_moyenne[9][$j] = 0;

			$j++;
		}
		
		// echo "Client : ".$client." Nb Tranches : ".$client->graph_par_jour[0][0]->count()."<br/>";
		// echo "Client : ".$client." Nb jours : ".$client->graph_par_jour->count()."<br/>";

		// Addition des valeurs
		$i_tranche = 0;
		while( $i_tranche < $nb_tranches )
		{
			$i_jour = 0;
			while( $i_jour <  $client->graph_par_jour->count() )
			{
				$result[1][$i_tranche] 				+= $client->graph_par_jour[$i_jour][1][$i_tranche];
				$client->graph_total[1][$i_tranche] += $client->graph_par_jour[$i_jour][1][$i_tranche];

				$result[2][$i_tranche] 				+= $client->graph_par_jour[$i_jour][2][$i_tranche];
				$client->graph_total[2][$i_tranche] += $client->graph_par_jour[$i_jour][2][$i_tranche];

				$result[3][$i_tranche] 				+= $client->graph_par_jour[$i_jour][3][$i_tranche];
				$client->graph_total[3][$i_tranche] += $client->graph_par_jour[$i_jour][3][$i_tranche];

				$result[4][$i_tranche] 				+= $client->graph_par_jour[$i_jour][4][$i_tranche];
				$client->graph_total[4][$i_tranche] += $client->graph_par_jour[$i_jour][4][$i_tranche];

				$result[5][$i_tranche] 				+= $client->graph_par_jour[$i_jour][5][$i_tranche];
				$client->graph_total[5][$i_tranche] += $client->graph_par_jour[$i_jour][5][$i_tranche];

				$result[6][$i_tranche] 				+= $client->graph_par_jour[$i_jour][6][$i_tranche];
				$client->graph_total[6][$i_tranche] += $client->graph_par_jour[$i_jour][6][$i_tranche];

				$result[7][$i_tranche] 				+= $client->graph_par_jour[$i_jour][7][$i_tranche];
				$client->graph_total[7][$i_tranche] += $client->graph_par_jour[$i_jour][7][$i_tranche];

				$result[8][$i_tranche] 				+= $client->graph_par_jour[$i_jour][8][$i_tranche];
				$client->graph_total[8][$i_tranche] += $client->graph_par_jour[$i_jour][8][$i_tranche];

				$result[9][$i_tranche] 				+= $client->graph_par_jour[$i_jour][9][$i_tranche];
				$client->graph_total[9][$i_tranche] += $client->graph_par_jour[$i_jour][9][$i_tranche];
				$i_jour ++;
			}
			
			// On divise la valeur par le nombre de jour pour avoir la moyenne
			if( $i_jour != 0 and $moyenne ){
				$result[1][$i_tranche] 	= $result[1][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[1][$i_tranche] = $result[1][$i_tranche]/$i_jour;

				$result[2][$i_tranche] 	= $result[2][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[2][$i_tranche] = $result[2][$i_tranche]/$i_jour;

				$result[3][$i_tranche] 	= $result[3][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[3][$i_tranche] = $result[3][$i_tranche]/$i_jour;

				$result[4][$i_tranche] 	= $result[4][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[4][$i_tranche] = $result[4][$i_tranche]/$i_jour;

				$result[5][$i_tranche] 	= $result[5][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[5][$i_tranche] = $result[5][$i_tranche]/$i_jour;

				$result[6][$i_tranche] 	= $result[6][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[6][$i_tranche] = $result[6][$i_tranche]/$i_jour;

				$result[7][$i_tranche] 	= $result[7][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[7][$i_tranche] = $result[7][$i_tranche]/$i_jour;

				$result[8][$i_tranche] 	= $result[8][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[8][$i_tranche] = $result[8][$i_tranche]/$i_jour;

				$result[9][$i_tranche] 	= $result[9][$i_tranche]/$i_jour;
				$client->graph_total_moyenne[9][$i_tranche] = $result[9][$i_tranche]/$i_jour;
			}
			
			$i_tranche++;
		}	
	}	
	public function get_courbe_equipe(  $equipe, ClassArray $result, $moyenne = false ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_16");


		// Pr�paration des tableau de r�sultats
		$result[0] = new ClassArray(); // A l'origine je codais : $result['value'] = .... et $result['date'] = ....
		$result[1] = new ClassArray(); // Mais semblerait que l'offset ne puisse etre que num�rique ... car au final le tableau �tait vide.
		$result[2] = new ClassArray();
		$result[3] = new ClassArray();
		$result[4] = new ClassArray();
		$result[5] = new ClassArray();
		$result[6] = new ClassArray();
		$result[7] = new ClassArray();
		$result[8] = new ClassArray();
		$result[9] = new ClassArray();
		
		// Tableau qui va contenir dans la 1ere dimension les tranche horaires 
		// dans la 2nd le nombre de clients possedant cette tranche.
		$tab_nb_client_tranche[0] = new ClassArray();
		$tab_nb_client_tranche[1] = new ClassArray();
		
		// Il faut trouver le client qui a la plage horaire la plus large.(parmi les clients s�lectionn�s)
		// Voir meme une cr�ation d'une plage car par exemple:
		// client 1 : 7h30-> 18h      et client2 8h->20h .... il faut une plage 7h30->20h.....!
		// Ts les r�sultats ont un tableau qui d�bute a l'indice 0 ... normal....
		// Mais le 0 pour le client2(8h->8h30)..... �quivaut au 1 du client1 (car d�bute � 7h30)............. :@:@:@
		// ... ptetre moyen de s'en sortir avec la date(l'heure) qui est enregistr� dans la 2nd dimension du tableau ;)...
		
		// D�finition de la plage horaire a utiliser:
		$debut_plage = "12:00";
		$fin_plage = "12:00";
		
		$i=0;
		// On fait le tour de la liste de clients
		while( $i < $this->tab_clients->count() )
		{
			// Sil fait partie de l'�quipe ...
			if( $this->tab_clients[$i]->equipe == $equipe )
			{
				if( $debut_plage > $this->tab_clients[$i]->H_ouverture )
					$debut_plage = $this->tab_clients[$i]->H_ouverture;
				
				if( $fin_plage < $this->tab_clients[$i]->H_fermeture )
					$fin_plage = $this->tab_clients[$i]->H_fermeture;
			}
			$i++;
		}
		
		// Init du tableau de r�sultats a 0 pour les valeurs et avec les dates pour ... les dates.
		$date = new datetime( $debut_plage );
		$j=0;
		while( $date->format("H:i") < $fin_plage )
		{
			$result[0][$j] = $date->format("H:i");
			$result[1][$j] = 0;
			$result[2][$j] = 0;
			$result[3][$j] = 0;
			$result[4][$j] = 0;
			$result[5][$j] = 0;
			$result[6][$j] = 0;
			$result[7][$j] = 0;
			$result[8][$j] = 0;
			$result[9][$j] = 0;
			$date->modify("+30 min");
			$j++;
		}

		
		// Addition des valeurs
		$i_tranche = 0;
		$date = new datetime( $debut_plage );
		while( $date->format("H:i") < $fin_plage )
		{
			// On fait le tour des clients de l'�quipe
			$i_client = 0;
			while( $i_client <  $this->tab_clients->count() )
			{
				$client = $this->tab_clients[$i_client];
				
				// Si le client en question est selectionn�
				if( $client->equipe == $equipe )
				{
					// Si on retrouve l'heure de r�f�rence ... alors on additionne la valeur.
					$offset = $client->graph_total[0]->find( $date->format( "H:i" ));
					if( $offset >= 0 )
					{
						if( $moyenne ){
							$result[1][$i_tranche] += $client->graph_total_moyenne[1][$offset];
							$result[2][$i_tranche] += $client->graph_total_moyenne[2][$offset];
							$result[3][$i_tranche] += $client->graph_total_moyenne[3][$offset];
							$result[4][$i_tranche] += $client->graph_total_moyenne[4][$offset];
							$result[5][$i_tranche] += $client->graph_total_moyenne[5][$offset];
							$result[6][$i_tranche] += $client->graph_total_moyenne[6][$offset];
							$result[7][$i_tranche] += $client->graph_total_moyenne[7][$offset];
							$result[8][$i_tranche] += $client->graph_total_moyenne[8][$offset];
							$result[9][$i_tranche] += $client->graph_total_moyenne[9][$offset];	
						}else{
							$result[1][$i_tranche] += $client->graph_total[1][$offset];
							$result[2][$i_tranche] += $client->graph_total[2][$offset];
							$result[3][$i_tranche] += $client->graph_total[3][$offset];
							$result[4][$i_tranche] += $client->graph_total[4][$offset];
							$result[5][$i_tranche] += $client->graph_total[5][$offset];
							$result[6][$i_tranche] += $client->graph_total[6][$offset];
							$result[7][$i_tranche] += $client->graph_total[7][$offset];
							$result[8][$i_tranche] += $client->graph_total[8][$offset];
							$result[9][$i_tranche] += $client->graph_total[9][$offset];
						}						
					}
				}
				$i_client ++;
			}
			
			$i_tranche++;
			$date->modify("+30 min");
		}
		
/* 		// Init du tableau de r�sultats DE MOYENNE a 0 pour les valeurs et avec les dates pour ... les dates.
		$date = new datetime( $debut_plage );
		$j=0;
		while( $date->format("H:i") < $fin_plage )
		{
			$result[0][$j] = $date->format("H:i");
			//$result[1][$j] = 0;
			
			// On fait le tour des clients de l'�quipe
			$i_client = 0;
			while( $i_client <  $this->tab_clients->count() )
			{
				$client = $this->tab_clients[$i_client];
				
				// Si le client en question est selectionn�
				if( $client->equipe == $equipe )
				{
					// Si on retrouve l'heure de r�f�rence ... alors on additionne la valeur.
					$offset = $client->graph_total[0]->find( $date->format( "H:i" ));
					if( $offset >= 0 )
					{
					}
				}
			}
			
			
			
			
			$date->modify("+30 min");
			$j++;
		} */
	}
	public function get_stats($tab_result){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_15");

			
	//	echo "Function GetStats : this->$tab_equipes"
		$i_equipe = 0;
		while( $i_equipe < $this->liste_equipes->count() )
		{
		
			// Variables pour les totaux Equipes
			$SEquipe_nb_appels_total 					= 0;
			$SEquipe_nb_decro_total 					= 0;
			$SEquipe_nb_abandons_total 					= 0;
			$SEquipe_nb_appels_stat 					= 0;
			$SEquipe_nb_abandons_dessous_borne_min 		= 0;
			$SEquipe_nb_abandons_entre_borne_min_max 	= 0;
			$SEquipe_nb_abandons_dessus_borne_max 		= 0;
			$SEquipe_nb_decro_dessous_borne_min 		= 0;
			$SEquipe_nb_decro_entre_borne_min_max 		= 0;
			$SEquipe_nb_decro_dessus_borne_max 			= 0;
			$SEquipe_somme_tps_com 						= 0;			
			$SEquipe_nb_appel_tps_com_dessus_5s 		= 0;
		
			// 2eme dimension // Pour les clients
			$tab_result[$i_equipe] = new ClassArray();
		
			// On fait le tour des clients ... 
			$i_client = 0;
			$i_tabclient = 0;
			while( $i_tabclient < $this->tab_clients->count() )
			{
				//echo "get_stats i_client=$i_client; i_equipe=$i_equipe Client=".$this->groupe_clients->liste_clients[$i_client].": equipe client = ".$this->tab_clients[$i_client]->equipe."; tab equipe =  ".$this->tab_equipes[$i_equipe]."<br/>";
				// Si le client fait partie de l'�quipe en question alors on le traite. (pour info : L'offset client == offset equipe... cad pour le meme offset on a l'�quipe ou le client...)
	//			if( $this->tab_equipes[$i_equipe] == $this->tab_clients[$i_tabclient]->equipe )
				if( $this->liste_equipes[$i_equipe] == $this->tab_clients[$i_tabclient]->equipe )
				{
					//echo " Equipes OK.<br/> ";
					//echo "get_stats : tab[$i_equipe][$i_client] : ".$this->get_client($i_client)."<br/>";
					
					// On cr�� la 3eme dimension...! pour les jours
					$tab_result[$i_equipe][$i_client] = new ClassArray();
						
					// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
					// $un_client = $this->groupe_clients->liste_clients[$i_client];
					$un_client = $this->groupe_clients->liste_clients[$i_tabclient];
					
					// Variables pour les totaux Clients
					$SClient_nb_appels_total 					= 0;
					$SClient_nb_decro_total 					= 0;
					$SClient_nb_abandons_total 					= 0;
					$SClient_nb_appels_stat 					= 0;
					$SClient_nb_abandons_dessous_borne_min 		= 0;
					$SClient_nb_abandons_entre_borne_min_max 	= 0;
					$SClient_nb_abandons_dessus_borne_max 		= 0;
					$SClient_nb_decro_dessous_borne_min 		= 0;
					$SClient_nb_decro_entre_borne_min_max 		= 0;
					$SClient_nb_decro_dessus_borne_max 			= 0;
					$SClient_somme_tps_com 						= 0;
					$SClient_nb_appel_tps_com_dessus_5s 		= 0;
					
					// On ouvre le fichier CSV permettant l'export.
					$nomfichier = $_SESSION['exports'].$un_client->nick.'.csv';
					$monfichier = fopen( $nomfichier, 'w'); // �criture seulement, cr�� le fichier si n'existe pas.
					fseek($monfichier, 0); // On remet le curseur au d�but du fichier (cas o� fichier existe et contient des infos => on �crase tout)

					/////// On �crit l'entete dans le fichier.
					// Pr�paration 
					$minAffich = $un_client->borne_min-1;
					$max = $un_client->borne_max;
					$aecrire = "Date;;Nb TOTAL d'Appels re�us;Nb total d'abandons;Base Statistique du nombre d'appels;Nb Total d'Appels d�croch�s;Nb d'Abandons <= ".$minAffich."s;Nb d'abandons >  ".$minAffich."s et <= ".$max." s;Nb Abandons > ".$max."s;Nb Appels d�croch�s <= ".$minAffich."s;Nb Appels d�croch�s > ".$minAffich."s et <= ".$max."s;Nb Appels d�croch�s > ".$max."s;Temps moyen conversation;Pris < ".$minAffich."s;SLA
";// Oblig� de faire un retour a la ligne pour que les lignes suivantes du fichier, soit bien a la ligne ;)
					fputs($monfichier, $aecrire); // On �crit dans le fichier
			
					// On fait le tour des dates.
					$i_date = 0; // Dans le tableau de r�sultats
					$i_tabdate = 0; // dans le tableau de Classes "Jour"
					while( $i_tabdate < $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours->count() )
					{
						//	echo "get_stats : tab[$i_equipe][$i_client][$i_date]<br/>";
						$un_jour = $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours[$i_tabdate];
						
						// Donn�es necessaires pour le tableau de r�cap des appels
						$session_jour = $un_client->nick.$un_jour;
						$session_client = $un_client->nick;
						$_SESSION["$session_jour"] = serialize($un_jour);
						$_SESSION["$session_client"] = serialize($un_client);
						// Pour contrer le bug du $_SESSION["$session_jour"] = serialize($un_jour);... qui n'enregistre pas les horaires d'appel lorsque je unserialize....
						// Enregistrement des horaires des appels dans un tableau que je serialize � la fin de la boucle sur les appels.
						$Tab_Horaires = new ClassArray();
						
						
						
						//echo "Jour actuel : ".$un_jour."<br/>";
						
						// Est-ce un jour f�ri� ou WE? et le client souhaite-t-il afficher les WE et JF?
						
						$show = true;
						// Si c'est un WE?
						if( $un_jour->is_we == 1 )
						{
							// Que le client veut afficher les WE
							if( $un_client->compte_we == 1 )
								$show = true;
							else
								$show = false;
						}
						// Si c'est pas un WE, c'est peut-etre un JF?
						if( $un_jour->is_jf == 1 )
						{
							// Que le client veut afficher les JF
							if( $un_client->compte_jf == 1 )
								$show = true;
							else
								$show = false;
						}				
						
						if( $show == true )
						{
						
							
							// On cr�� la 4eeme dimension...! pour les cellules du tableau.
							$tab_result[$i_equipe][$i_client][$i_date] = new ClassArray();
						
							// On fait le tour des appels. 
							// On pourrait tres bien appeler les fonctions des classes ... mais ceci ferait faire a chaque fonction, un tour de la liste d'appels... 14 colonnes = 14 fois le tour de tout les appels....
							// Avec la m�thode ci-dessous ... on fait 1 fois le tour des appels et on en retire les informations necessaires.
							
							$nb_appels_total = $un_jour->groupe_appels->liste_appels->count();
					
							$nb_decro_total 					= 0;
							$nb_abandons_total 					= 0;
							$nb_abandons_dessous_borne_min 		= 0;
							$nb_abandons_entre_borne_min_max 	= 0;
							$nb_abandons_dessus_borne_max 		= 0;
							$nb_decro_dessous_borne_min 		= 0;
							$nb_decro_entre_borne_min_max 		= 0;
							$nb_decro_dessus_borne_max 			= 0;
							$nb_appel_tps_com_dessus_5s 		= 0;
							$somme_tps_com 						= 0;
							
							$is_there_crash = false;
							$duree_crash = 0;
							$debut_crash = '';
							$fin_crash = '';
							$com_crash = '';
							
							// On fait le tour des appels de la journ�e
							$i_appel = 0;
							while( $i_appel < $un_jour->groupe_appels->liste_appels->count() )
							{
								// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
								$un_appel = $un_jour->groupe_appels->liste_appels[$i_appel];
								
								//echo $un_appel."<br/>";
								
								// Enregistrement de l'horaire d'appel pour contrer le bug du serialize qui n'enregistre pas ces horaires.
								$Tab_Horaires->add( $un_appel->date->format( 'Y/m/d H:i:s' ) );
								
								// Est-on sur une plage de crash?
								$borne_crash = 0;
								if( $un_appel->is_on_crash() ){
									//echo "Appel : ".$un_appel." est en crash<br/>";
									
									// $cmd_line = "SELECT duree FROM crashs WHERE client = '".$un_appel->nom_client."' AND date_debut < '".$un_appel->date->format('Y-m-d H:i:s')."' AND date_fin > '".$un_appel->date->format('Y-m-d H:i:s')."'";
									// $donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
									// $borne_crash = $donnees['duree'];
									
									$borne_crash = $un_appel->get_duree_crash();
									
									$is_there_crash = true;
									$duree_crash = $un_appel->get_duree_crash();
									$debut_crash = $un_appel->get_date_debut_crash();
									$fin_crash = $un_appel->get_date_fin_crash();
									$com_crash = $un_appel->get_commentaire_crash();
								}
								
								
								
								
								// Les abandons
								if( $un_appel->poste_operateur == "" )
								{
									// Nb abandons total.
									$nb_abandons_total ++;
									
									// Nb Abandons en dessous de la borne MIN du client
									if( $un_appel->tps_attente_av_decro < $un_client->borne_min + $borne_crash )
									{
										$nb_abandons_dessous_borne_min ++;
									}
									// Si pas abandonn� avant borne min mais abandonn� avant borne max 
									else if( $un_appel->tps_attente_av_decro < $un_client->borne_max + $borne_crash )
									{
										$nb_abandons_entre_borne_min_max ++;
									}
									// Sinon ... b� c'est un appel abandonn� apres la borne max...
									else 
									{	
										$nb_abandons_dessus_borne_max ++;
									}
								}
								else
								{	// Si c pas abandonn� alors c'est d�croch�s.
									$nb_decro_total ++;
									
									// Addition du temps de communication pour la moyenne. SIIII tps de com > 5sec.
									if( $un_appel->tps_com > 5 )
									{
										$somme_tps_com += $un_appel->tps_com;
										$nb_appel_tps_com_dessus_5s ++;
									}
									
									// Si d�cro avant la borne min du client
									if( $un_appel->tps_attente_av_decro <= $un_client->borne_min_decro + $borne_crash )
									{
										$nb_decro_dessous_borne_min ++;
									}
									// Si pas d�cro avant borne min mais d�cro avant borne max 
									else if( $un_appel->tps_attente_av_decro <= $un_client->borne_max_decro + $borne_crash )
									{
										$nb_decro_entre_borne_min_max ++;
									}
									// Sinon ... b� c'est un appel d�cro apres la borne max...
									else 
									{	
										$nb_decro_dessus_borne_max ++;
									}
								}
							
								$i_appel++;
							}
							
							// Enregistrement de l'horaire d'appel pour contrer le bug du serialize qui n'enregistre pas ces horaires.
							$session_horaires = $un_client->nick.$un_jour."_horaires";
							$_SESSION["$session_horaires"] = serialize( $Tab_Horaires );
							//echo $session_horaires."<br/>";
							
							/////// Toute ces colonnes sont relatives � 1 jour.
							$i_col = 0; // Index de la colonne du tableau de stat
							// 1ere colonne : La date
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $un_jour;
							fputs($monfichier, $un_jour.";"); // On �crit dans le fichier
							$i_col ++;
							
							// 2eme colonne : Vide
							if( $is_there_crash )
								$tab_result[$i_equipe][$i_client][$i_date][$i_col] = '<a href="admin/crash.php" title = "Debut : '.$debut_crash.' Fin : '.$fin_crash.' Duree : '.$duree_crash.'s.">Crash</a>';
							else
								$tab_result[$i_equipe][$i_client][$i_date][$i_col] = "";
							fputs($monfichier, ";"); // On �crit dans le fichier
							$i_col ++;

							// 3eme colonne : Nb Total d'appels recu
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_appels_total;
							fputs($monfichier, $nb_appels_total.";"); // On �crit dans le fichier
							$SClient_nb_appels_total += $nb_appels_total;
							$i_col ++;

							// 4eme colonne : Nb Total d'abandons
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_abandons_total;
							fputs($monfichier, $nb_abandons_total.";"); // On �crit dans le fichier
							$SClient_nb_abandons_total += $nb_abandons_total;
							$i_col ++;
							
							// 5eme colonne : Base Stat : Nb total - Nb abandons en dessous de la borne min du client. (20s la plus part du temps)
							$nb_appels_stat = $nb_appels_total - $nb_abandons_dessous_borne_min;
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_appels_stat;
							fputs($monfichier, $nb_appels_stat.";"); // On �crit dans le fichier
							$SClient_nb_appels_stat += $nb_appels_stat;
							$i_col ++;
							
							// 6eme colonne : Nb Total appels d�croch�s
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_decro_total;
							fputs($monfichier, $nb_decro_total.";"); // On �crit dans le fichier
							$SClient_nb_decro_total += $nb_decro_total;
							$i_col ++;
							
							// 7eme colonne : Nb abandons < borne min
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_abandons_dessous_borne_min;
							fputs($monfichier, $nb_abandons_dessous_borne_min.";"); // On �crit dans le fichier
							$SClient_nb_abandons_dessous_borne_min += $nb_abandons_dessous_borne_min;
							$i_col ++;
							
							// 8eme colonne : borne min < Nb abandons < borne_max 
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_abandons_entre_borne_min_max;
							fputs($monfichier, $nb_abandons_entre_borne_min_max.";"); // On �crit dans le fichier
							$SClient_nb_abandons_entre_borne_min_max += $nb_abandons_entre_borne_min_max;
							$i_col ++;
							
							// 9eme colonne :  Nb abandons > borne_max 
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_abandons_dessus_borne_max;
							fputs($monfichier, $nb_abandons_dessus_borne_max.";"); // On �crit dans le fichier
							$SClient_nb_abandons_dessus_borne_max += $nb_abandons_dessus_borne_max;
							$i_col ++;
							
							// 10eme colonne : Nb d�cro < borne min
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_decro_dessous_borne_min;
							fputs($monfichier, $nb_decro_dessous_borne_min.";"); // On �crit dans le fichier
							$SClient_nb_decro_dessous_borne_min += $nb_decro_dessous_borne_min;
							$i_col ++;
							
							// 11eme colonne : borne min < Nb d�cro < borne_max 
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_decro_entre_borne_min_max;
							fputs($monfichier, $nb_decro_entre_borne_min_max.";"); // On �crit dans le fichier
							$SClient_nb_decro_entre_borne_min_max += $nb_decro_entre_borne_min_max;
							$i_col ++;
							
							// 12eme colonne :  Nb d�cro > borne_max 
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $nb_decro_dessus_borne_max;
							fputs($monfichier, $nb_decro_dessus_borne_max.";"); // On �crit dans le fichier
							$SClient_nb_decro_dessus_borne_max += $nb_decro_dessus_borne_max;
							$i_col ++;
							
							// 13eme colonne :  Temps moyen de communication
							if( $nb_appel_tps_com_dessus_5s != 0 )
								$moyenne = $somme_tps_com / $nb_appel_tps_com_dessus_5s;
							else
								$moyenne = 0;
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = format_affich_heure(NumToHour($moyenne));
							fputs($monfichier, "00:".NumToHour($moyenne).";"); // On �crit dans le fichier
							$SClient_somme_tps_com += $somme_tps_com;
							$SClient_nb_appel_tps_com_dessus_5s += $nb_appel_tps_com_dessus_5s;
							
							// echo "Client : ".$un_client." nb_appel_tps_com_dessus_5s : $nb_appel_tps_com_dessus_5s<br/>";
							// echo "Somme tps_com_dessus_5s : $somme_tps_com<br/>";
							// echo "SClient_nb_appel_tps_com_dessus_5s : $SClient_nb_appel_tps_com_dessus_5s<br/>";
							// echo "Somme Client tps_com_dessus_5s : $SClient_somme_tps_com<br/><br/>";
							
		//					echo "Jour:".$un_jour." - Somme Tps:".$somme_tps_com." - pr Nb Appels > 5s:".$nb_appel_tps_com_dessus_5s." - SClient Tps:".$SClient_somme_tps_com." - SClient Appels > 5s:".$SClient_nb_appel_tps_com_dessus_5s."<br/>";
							$i_col ++;
							
							// 14eme colonne :  % d�cro en dessous borne_min
							if( $nb_decro_total != 0 )
								$pourcentage = ( $nb_decro_dessous_borne_min * 100 ) / $nb_appels_stat;
							else
								$pourcentage = 0;
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = number_format($pourcentage, 2, '.', ' ');
							fputs($monfichier, number_format($pourcentage, 2, '.', ' ').";"); // On �crit dans le fichier
							$i_col ++;
							
							// 15eme colonne :  SLA
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $un_client->SLA;	
							fputs($monfichier, $un_client->SLA.";"); // On �crit dans le fichier
							$i_col ++;
							
							
							// Derniere cellule permet d'afficher le graphique journali�.
							$tab_result[$i_equipe][$i_client][$i_date][$i_col] = new ClassArray();
							$this->get_courbe_jour( $un_jour, $tab_result[$i_equipe][$i_client][$i_date][$i_col] );
							
							//echo $tab_result[$i_equipe][$i_client][$i_date][$i_col]->count();
							
							//$this->init = false;
							
							fputs($monfichier, "
"); // Fin de la ligne journali�re.
							
							
							$i_date++;
						}
						$i_tabdate++;
					}
					
					// Calcul/Insertion tableau des totaux
					// Insertion des totaux dans le tableau ... a la date de "Derniere date+1"
					
					//echo "CLASS : Offset r�sultat : Client : ".$i_client." Date : ".$i_date."<br/>";
					
					fputs($monfichier, "
"); // On saute une ligne pour le total.
					$i_col = 0;
					$tab_result[$i_equipe][$i_client][$i_date] = new ClassArray();
					
					// 1ere cellule : Vide
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = "";
					fputs($monfichier, ";"); // On �crit dans le fichier
					$i_col ++;

					//2eme cellule : Total :
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = "Totaux";
					fputs($monfichier, "Totaux;"); // On �crit dans le fichier
					$i_col ++;
					
					//3eme cellule : Total Nb Appels Recus:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_appels_total;
					fputs($monfichier, $SClient_nb_appels_total.";"); // On �crit dans le fichier
					$SEquipe_nb_appels_total += $SClient_nb_appels_total;
					$i_col ++;
					
					//4eme cellule : Total Nb Appels abandonn�s:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_abandons_total;
					fputs($monfichier, $SClient_nb_abandons_total.";"); // On �crit dans le fichier
					$SEquipe_nb_abandons_total += $SClient_nb_abandons_total;
					$i_col ++;

					//5eme cellule : Total Base stat :
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_appels_stat;
					fputs($monfichier, $SClient_nb_appels_stat.";"); // On �crit dans le fichier
					$SEquipe_nb_appels_stat += $SClient_nb_appels_stat;
					$i_col ++;

					//6eme cellule : Total Nb appels d�cro:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_decro_total;
					fputs($monfichier, $SClient_nb_decro_total.";"); // On �crit dans le fichier
					$SEquipe_nb_decro_total += $SClient_nb_decro_total;
					$i_col ++;

					//7eme cellule : Total Abandons < Borne min
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_abandons_dessous_borne_min;
					fputs($monfichier, $SClient_nb_abandons_dessous_borne_min.";"); // On �crit dans le fichier
					$SEquipe_nb_abandons_dessous_borne_min += $SClient_nb_abandons_dessous_borne_min;
					$i_col ++;

					//8eme cellule : Total Borne min < Abandons < Borne Max :
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_abandons_entre_borne_min_max;
					fputs($monfichier, $SClient_nb_abandons_entre_borne_min_max.";"); // On �crit dans le fichier
					$SEquipe_nb_abandons_entre_borne_min_max += $SClient_nb_abandons_entre_borne_min_max;
					$i_col ++;

					//9eme cellule : Total NB Abandons > Born Max :
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_abandons_dessus_borne_max;
					fputs($monfichier, $SClient_nb_abandons_dessus_borne_max.";"); // On �crit dans le fichier
					$SEquipe_nb_abandons_dessus_borne_max += $SClient_nb_abandons_dessus_borne_max;
					$i_col ++;

					//10eme cellule : Total Nb D�cro < Borne Min:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_decro_dessous_borne_min;
					fputs($monfichier, $SClient_nb_decro_dessous_borne_min.";"); // On �crit dans le fichier
					$SEquipe_nb_decro_dessous_borne_min += $SClient_nb_decro_dessous_borne_min;
					$i_col ++;

					//11eme cellule : Total Borne min < Nb D�cro < Borne Max:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_decro_entre_borne_min_max;
					fputs($monfichier, $SClient_nb_decro_entre_borne_min_max.";"); // On �crit dans le fichier
					$SEquipe_nb_decro_entre_borne_min_max += $SClient_nb_decro_entre_borne_min_max;
					$i_col ++;

					//12eme cellule : Total Nb D�cro > Borne Max:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $SClient_nb_decro_dessus_borne_max;
					fputs($monfichier, $SClient_nb_decro_dessus_borne_max.";"); // On �crit dans le fichier
					$SEquipe_nb_decro_dessus_borne_max += $SClient_nb_decro_dessus_borne_max;
					$i_col ++;

					//13eme cellule : Total Moyenne Tps de communication:
					if( $SClient_nb_appel_tps_com_dessus_5s != 0 )
						$moyenne = $SClient_somme_tps_com/$SClient_nb_appel_tps_com_dessus_5s;
					else 
						$moyenne = 0;
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = format_affich_heure( NumToHour( $moyenne ));
					fputs($monfichier, "00:".NumToHour( $moyenne ).";"); // On �crit dans le fichier
					$SEquipe_somme_tps_com += $SClient_somme_tps_com;
					$SEquipe_nb_appel_tps_com_dessus_5s += $SClient_nb_appel_tps_com_dessus_5s;
					$i_col ++;
	//				echo "Client:".$un_client." - Somme Tps:".$SClient_somme_tps_com." - pr Nb Appels > 5s:".$SClient_nb_appel_tps_com_dessus_5s." - SEquipe Tps:".$SEquipe_somme_tps_com." - SEquipe Appels > 5s:".$SEquipe_nb_appel_tps_com_dessus_5s."<br/>";

					//14eme cellule : % de d�cro < 20s :
					if( $SClient_nb_appels_stat != 0 )
						$pourcentage = ( $SClient_nb_decro_dessous_borne_min * 100 ) / $SClient_nb_appels_stat;
					else
						$pourcentage = 0;
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = number_format($pourcentage, 2, '.', ' ');
					fputs($monfichier, number_format($pourcentage, 2, '.', ' ').";"); // On �crit dans le fichier
					$i_col ++;
					
					//15eme cellule : Rappel SLA:
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = $un_client->SLA;
					fputs($monfichier, $un_client->SLA.";"); // On �crit dans le fichier
					$i_col ++;

					
					
					// Derniere cellule permet d'afficher le graphique Total Client.
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = new ClassArray();
					$this->get_courbe_client( $un_client, $tab_result[$i_equipe][$i_client][$i_date][$i_col] );
					// Calcul de la moyenne.
					$i_col++;
					$tab_result[$i_equipe][$i_client][$i_date][$i_col] = new ClassArray();
					$this->get_courbe_client( $un_client, $tab_result[$i_equipe][$i_client][$i_date][$i_col], true );
					
					
					
					// Fin du fichier
					fclose($monfichier);
					
					
					$i_client++;
				}
				$i_tabclient++;
			}
			// Calcul/Insertion tableau des totaux
			// Insertion des totaux dans le tableau ... a la date de "Derniere date+1"
					
			$i_col = 0;
			$tab_result[$i_equipe][$i_client] = new ClassArray();
					
			// 1ere cellule : Vide
			//$tab_result[$i_equipe][$i_client][$i_date][$i_col] = "";
			$tab_result[$i_equipe][$i_client][$i_col] = "";
			$i_col ++;

			//2eme cellule : Total :
			$tab_result[$i_equipe][$i_client][$i_col] = "Totaux";
			$i_col ++;
					
			//3eme cellule : Total Nb Appels Recus:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_appels_total;
			$i_col ++;
					
			//4eme cellule : Total Nb Appels abandonn�s:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_abandons_total;
			$i_col ++;

			//5eme cellule : Total Base stat :
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_appels_stat;
			$i_col ++;

			//6eme cellule : Total Nb appels d�cro:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_decro_total;
			$i_col ++;

			//7eme cellule : Total Abandons < Borne min
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_abandons_dessous_borne_min;
			$i_col ++;

			//8eme cellule : Total Borne min < Abandons < Borne Max :
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_abandons_entre_borne_min_max;
			$i_col ++;

			//9eme cellule : Total NB Abandons > Born Max :
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_abandons_dessus_borne_max;
			$i_col ++;

			//10eme cellule : Total Nb D�cro < Borne Min:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_decro_dessous_borne_min;
			$i_col ++;

			//11eme cellule : Total Borne min < Nb D�cro < Borne Max:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_decro_entre_borne_min_max;
			$i_col ++;

			//12eme cellule : Total Nb D�cro > Borne Max:
			$tab_result[$i_equipe][$i_client][$i_col] = $SEquipe_nb_decro_dessus_borne_max;
			$i_col ++;

			//13eme cellule : Total Moyenne Tps de communication: (pour les appels dont tps de com > 5s)
			if( $SEquipe_nb_appel_tps_com_dessus_5s != 0 )
				$moyenne = $SEquipe_somme_tps_com/$SEquipe_nb_appel_tps_com_dessus_5s;
			else 
				$moyenne = 0;
			$tab_result[$i_equipe][$i_client][$i_col] = format_affich_heure( NumToHour( $moyenne ));
			$i_col ++;

			//14eme cellule : % de d�cro < 20s :
			if( $SEquipe_nb_appels_stat != 0 )
				$pourcentage = ( $SEquipe_nb_decro_dessous_borne_min * 100 ) / $SEquipe_nb_appels_stat;
			else
				$pourcentage = 0;
			$tab_result[$i_equipe][$i_client][$i_col] = number_format($pourcentage, 2, '.', ' ');
			$i_col ++;
					
			//15eme cellule : Rappel SLA: ... mais pour une �quipe il peut y avoir plusieur SLA diff�rente ... donc on affiche rien...
			$tab_result[$i_equipe][$i_client][$i_col] = "";
			$i_col ++;
			
			// Derniere colonne pour afficher le graphique de total par Equipe.
			$tab_result[$i_equipe][$i_client][$i_col] = new ClassArray();
			$equipe = $this->get_equipe($i_equipe);
			//echo "GetStats : Equipe : ".$equipe."<br/>";
			$this->get_courbe_equipe( $equipe, $tab_result[$i_equipe][$i_client][$i_col] );
			
			$i_equipe++;
		}
		
		return $tab_result;
	}
	public function show_result( $le_resultat, $class = "cadre" ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_14");

	
		// R�cup�ration des r�sultats pour le graphique de charge par jour de semaine.
		$Resultat_Charge_Semaine = new ClassArray();
		$this->get_graph_charge_semaine( $Resultat_Charge_Semaine );
		
		// Pour test :
		//echo "test : ".$Resultat_Charge_Semaine;
		
		
		
		// D�but d'affichage des tableaux de stats.
		$h = 0;
		while( $h < $le_resultat->count()  )
		{		
			// On m�morise les resultats clients.
			$T_Mem_Result = new ClassArray();
			$T_mem = new ClassArray();

				$mon_equipe = $this->liste_equipes[$h];
			echo '<div class="'.$class.'"> <strong>Equipe : '.$mon_equipe.'</strong>';
			//echo 'Il y a '.$le_resultat[$h]->count().' clients dans cette �quipe<br/>';
			
			$i = 0;
			// Cr�ation d'un tableau par client
			while( $i < ($le_resultat[$h]->count()-1)) //-1 car la derniere colonne contient les totaux de l'�quipe.
			{
				
				// R�cup du client en cours.
				$client_cours = $this->get_client($h, $i);
					
				// Pr�paration de l'�tiquette lorsqu'on laisse la souris sur l'entete du tableau.
				$etiquette = "H Ouverture: ".$client_cours->H_ouverture." H Fermeture: ".$client_cours->H_fermeture." We: ";
				if( $client_cours->compte_we == 1 )
					$etiquette = $etiquette."Oui";
				else
					$etiquette = $etiquette."Non";
					
				$etiquette = $etiquette." Jours F&#233;ri&#233;s: ";
				if( $client_cours->compte_jf == 1 )
					$etiquette = $etiquette."Oui";
				else
					$etiquette = $etiquette."Non";
				
					
				// Affichage de l'entete
				echo '<a name="'.$client_cours->nick.'1"></a>';
				echo '<table id="'.$client_cours->nick.'"';

				if( $_SESSION['deploy_result'] == 0)
					echo ' style="display:none"';
				
				echo '><br/><br/>
					<caption title="'.$etiquette.'"> Client : <strong>'.$client_cours.'</strong><br/>
					<a href="'.$_SESSION['exports'].$client_cours->nick.'.csv">T&#233;l&#233;charger</a><br/>
					<a href="#'.$client_cours->nick.'2" onclick="montre_div(\''.$client_cours->nick.'\');montre_div(\''.$client_cours->nick.'_synth\');">Replier les r&#233;sultats</a>
					</caption>
				<tr>';
				echo "
					<th>Date</th>
					<th> </th>
					<th>Nb TOTAL d'Appels re&#231;us</th>
					<th>Nb total d'abandons</th>
					<th>Base Statistique du nombre d'appels</th>
					<th>Nb Total d'Appels d&#233;croch&#233;s</th>";
				$AffichMin = $client_cours->borne_min-1;
				$AffichMin_decro = $client_cours->borne_min_decro-1;
				echo "
					<th>Nb d'Abandons < ".$AffichMin."s</th>
					<th>Nb d'abandons >  ".$AffichMin."s et < ".$client_cours->borne_max."s</th>
					<th>Nb Abandons > ".$client_cours->borne_max."s</th>
					<th>Nb Appels d&#233;croch&#233;s < ".$AffichMin_decro."s</th>
					<th>Nb Appels d&#233;croch&#233;s > ".$AffichMin_decro."s et < ".$client_cours->borne_max_decro."s</th>
					<th>Nb Appels d&#233;croch&#233;s > ".$client_cours->borne_max_decro."s</th>
					<th>Temps moyen conversation</th>";
				echo "
					<th>% Pris < ".$AffichMin."s</th>
					<th>SLA</th>
				</tr>";			
				
				//echo '<div id="'.$client_cours->nick.'" style="color:blue;"> d�but div';
				
				
				$j = 0;
				while( $j < ($le_resultat[$h][$i]->count()-1)) // -1 car la derniere colonne sert aux r�sultat totaux du client.
				{
					// R�cup du jour en cours.
					//$jour_cours = $this->get_jour($h, $i, $j );
					//echo "Jour en cours : ".$jour_cours." Client en cours : ".$client_cours." Equipe en cours : ".$mon_equipe." Affiche WE : ".$client_cours->compte_we." Affiche JF : ".$client_cours->compte_jf."<br/>";
					
					//Affiche t on la ligne en question?
					// Si j=WE le cient a t il besoin d'afficher les WE?
					// Si j=JF le cient a t il besoin d'afficher les Jours Feries?
					////// Option annul�e par la suite, est g�r�e dans la partie calcul (get_stats)
					// On ne g�re ici que l'affichage de la ligne en jaune si WE et orange si JF
					$show = true;

					// SI on a determin� qu'on affichait ... alors on affiche :)
					if( $show == true )
					{
						echo '<tr id="'.$client_cours->nick.$j.'">';
						$k = 0;
						while( $k < $le_resultat[$h][$i][$j]->count()-1) // - 1 car le dernier sert aux graphiques journali�s.
						{
							// On change la couleur de fond de cellule si c'est un WE ou un JF.
							$appel_balise = "<td>";
							if( /*$jour_cours*/$le_resultat[$h][$i][$j][0]->is_jf == 1 )
								$appel_balise = '<td bgcolor="orange">';
							else if( /*$jour_cours*/$le_resultat[$h][$i][$j][0]->is_we == 1 )
								$appel_balise = '<td bgcolor="yellow">';
							
							$lien = "";
							$fin_lien = "";
							// Lien permettant d'avoir le detail des appels.
							if( $k >= 2 and $k <= 11 ) // 2 �quivaut a la cellule "Nb Total d'appel" et 11 "Nb Appels D�cro >30s."
							{
								$lien = '<a href="#" onClick="window.open(\'drawdayarray.php?type='.$k.'&client='.$client_cours->nick.'&dda='.$client_cours->nick.$le_resultat[$h][$i][$j][0]->date->format("d/m").'&date='.$le_resultat[$h][$i][$j][0]->date->format("d/m/Y").'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=990, height=500\');return(false)">';
								$fin_lien = '</a>';
							}
								
							
							// Affiche la cellule.
							echo $appel_balise.$lien.$le_resultat[$h][$i][$j][$k].$fin_lien."</td>";
							
							
							$k++;
						}
						//Affichage d'une icone pour demander le graphique JOURNEE
						$_SESSION["graph$h$i$j"] = serialize($le_resultat[$h][$i][$j][$k]);
						echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=journee&dg=graph'.$h.$i.$j.'&client='.$client_cours.'&date='.$le_resultat[$h][$i][$j][0]->date->format("d/m/Y").'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Nombre d\'appels par tranche horaire de 30min"/></a></td>';
						echo "</tr>";
					}
					$j++;
				}
				
				echo '<input type="hidden" id="text_'.$client_cours->nick.'" name="text_'.$client_cours->nick.'" value="'.$j.'"/>';
				
				// Fin div id="'.$client_cours->nick.'"
				//echo 'fin div</div >';

				// Saffiche ici le total par clients.
				$k = 0;
				$T_Mem_Result[$i] = new ClassArray();
				while( $k < $le_resultat[$h][$i][$j]->count() - 2) // -2 car derniere cellule sert a stocker les info pour les graphiques.
				{
					// Affiche la cellule.
					echo "<th>".$le_resultat[$h][$i][$j][$k]."</th>";
							
					// On m�morise pour pouvoir afficher la synth�se du client.
					$T_mem[$k] = $le_resultat[$h][$i][$j][$k];
					
					$T_Mem_Result[$i][$k] = $T_mem[$k];
					
					$k++;
				}
				// On �crase la 1ere cellule (qui est vide) avec le nom du client => pratique pour savoir a qui appartiennent ces r�sultats.
				$T_Mem_Result[$i][0] = $client_cours;
				
				
				//Affichage d'une icone pour demander le graphique. TOTAL CLIENT
				$_SESSION["graphtot$h$i"] = serialize($le_resultat[$h][$i][$j][$k]);
				echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=journee&dg=graphtot'.$h.$i.'&client='.$client_cours.'&date=Total Client\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Nombre d\'appels par tranche horaire de 30min"/></a></td>';
				// $i++;
				$k++;
				$_SESSION["graphtotmoy$h$i"] = serialize($le_resultat[$h][$i][$j][$k]);
				echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=journeemoy&dg=graphtotmoy'.$h.$i.'&client='.$client_cours.'&date=Total Client\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Moyenne d\'appels par tranche horaire de 30min"/></a></td>';
				
				echo "</table>";
				


				// Synth�se Client (pour l'affichage au d�part, n'affiche que l'entete du tableau + son total uniquement.)
				echo '<a name="'.$client_cours->nick.'2"></a>';
				echo '<table id="'.$client_cours->nick.'_synth"';
				
				if( $_SESSION['deploy_result'] == 1 )
					echo ' style="display:none"';

				
				echo '>
					<caption title="'.$etiquette.'"> Client : <strong>'.$client_cours.'</strong><br/>
					<a href="'.$_SESSION['exports'].$client_cours->nick.'.csv">T&#233;l&#233;charger</a><br/>
					<a href="#'.$client_cours->nick.'1" onclick="montre_div(\''.$client_cours->nick.'\');montre_div(\''.$client_cours->nick.'_synth\');">D&#233;ployer les r&#233;sultats</a>
					</caption>
				<tr>';
				echo "
					<th>Date</th>
					<th> </th>
					<th>Nb TOTAL d'Appels re&#231;us</th>
					<th>Nb total d'abandons</th>
					<th>Base Statistique du nombre d'appels</th>
					<th>Nb Total d'Appels d&#233;croch&#233;s</th>";
				$AffichMin = $client_cours->borne_min-1;
				$AffichMin_decro = $client_cours->borne_min_decro-1;
				echo "
					<th>Nb d'Abandons < ".$AffichMin."s</th>
					<th>Nb d'abandons >  ".$AffichMin."s et < ".$client_cours->borne_max."s</th>
					<th>Nb Abandons > ".$client_cours->borne_max."s</th>
					<th>Nb Appels d&#233;croch&#233;s < ".$AffichMin_decro."s</th>
					<th>Nb Appels d&#233;croch&#233;s > ".$AffichMin_decro."s et < ".$client_cours->borne_max_decro."s</th>
					<th>Nb Appels d&#233;croch&#233;s > ".$client_cours->borne_max_decro."s</th>
					<th>Temps moyen conversation</th>";
				echo "
					<th>% Pris < ".$AffichMin."s</th>
					<th>SLA</th>
				</tr>";	
				
				// Saffiche ici le total par clients.
				$k = 0;
				while( $k < $T_mem->count() )
				{
					// Affiche la cellule.
					echo "<th>".$T_mem[$k]."</th>";
					
					$k++;
				}

				//Affichage d'une icone pour demander le graphique. TOTAL CLIENT
				$_SESSION["graphtot$h$i"] = serialize($le_resultat[$h][$i][$j][$k]);
				echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=journee&dg=graphtot'.$h.$i.'&client='.$client_cours.'&date=Total Client\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Nombre d\'appels par tranche horaire de 30min"/></a></td>';
				
				$k++;
				$_SESSION["graphtotmoy$h$i"] = serialize($le_resultat[$h][$i][$j][$k]);
				echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=journeemoy&dg=graphtotmoy'.$h.$i.'&client='.$client_cours.'&date=Total Client\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Moyenne d\'appels par tranche horaire de 30min"/></a></td>';

				echo "</table>";


			
				//Affichage d'une icone pour demander le graphique. CHARGE / JOUR
				$_SESSION["charge_semaine$h$i"] = serialize($Resultat_Charge_Semaine[$h][$i]);
				echo '<a href="#" onClick="window.open(\'drawgraph.php?type=charge_semaine&dg=charge_semaine'.$h.$i.'&client='.$client_cours.'&date=Total Client\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=230\');return(false)">Moyenne du nombre d\'appel par jour : <img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Moyenne du nombre d\'appels par jour"/></a>';
				
				
				
				
				$i++;
			}
			// Saffiche ici le total par �quipe
			echo "<br/><br/><br/><br/><table><caption> Totaux Equipe : <strong>".$mon_equipe."</strong></caption>";
			echo '<tr>';
				echo "
					<th>Date</th>
					<th> </th>
					<th>Nb TOTAL d'Appels re&#231;us</th>
					<th>Nb total d'abandons</th>
					<th>Base Statistique du nombre d'appels</th>
					<th>Nb Total d'Appels d&#233;croch&#233;s</th>";
				echo "
					<th>Nb d'Abandons < Min</th>
					<th>Nb d'abandons >  Min et < Max</th>
					<th>Nb Abandons > Max</th>
					<th>Nb Appels d&#233;croch&#233;s < Min</th>
					<th>Nb Appels d&#233;croch&#233;s > Min et < Max</th>
					<th>Nb Appels d&#233;croch&#233;s > Max</th>
					<th>Temps moyen conversation</th>";
				echo "
					<th>% Pris < ".$AffichMin."s</th>
					<th>SLA</th>
				</tr>";	


			echo "<tr>";
			
			$k = 0;
			// echo "APPEL : Offset R�sultat : Client : ".$i." Date : ".$j."<br/>";	
			while( $k < $le_resultat[$h][$i]->count()-1) // -1 car cellule pour les graphiques.
			{
				// Affiche la cellule.
				echo "<th>".$le_resultat[$h][$i][$k]."</th>";
				
				$k++;
			}
			//Affichage d'une icone pour demander le graphique.
				$_SESSION["graphsupertot$h$i"] = serialize($le_resultat[$h][$i][$k]);
				echo '<td style="border=none;"><a href="#" onClick="window.open(\'drawgraph.php?type=totaljournees&nbclient='.$i.'&equipe='.$h.'&dg=graphsupertot'.$h.$i.'&client=&date=Total Equipe '.$mon_equipe.'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=380\');return(false)"><img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Nombre d\'appels par tranche horaire de 30min"/></a></td>';
				
			echo "</tr>";
			echo "</table>";
			

			//Affichage d'icones pour demander le graphique.
			// Moyenne d'appel /jour/client
			$_SESSION["charge_semaine$h$i"] = serialize($Resultat_Charge_Semaine[$h][$i]);
			echo '<a href="#" onClick="window.open(\'drawgraph.php?type=charge_semaine&dg=charge_semaine'.$h.$i.'&client=Moyenne Charge Semaine par client&date=Total Equipe '.$mon_equipe.'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=230\');return(false)">Moyenne Appels /jour et /client : <img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Moyenne du nombre d\'appels par jour"/></a>';
			
			// Moyenne d'apppel /jour pour l'�quipe
			$i++;
			$_SESSION["charge_semaine_equipe$h$i"] = serialize($Resultat_Charge_Semaine[$h][$i]);
			echo ' <a href="#" onClick="window.open(\'drawgraph.php?type=charge_semaine&dg=charge_semaine_equipe'.$h.$i.'&client=Moyenne Charge Semaine&date=Total Equipe '.$mon_equipe.'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=230\');return(false)">Moyenne Appels /jour pour l\'&#233;quipe : <img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Moyenne du nombre d\'appels par jour"/></a>';
			
			// Camembert de charge client / equipe.
			$i++;
			$_SESSION["TabTotaux$h"] = serialize($T_Mem_Result);
			echo ' <a href="#" onClick="window.open(\'drawgraph.php?type=camembert&dg=TabTotaux'.$h.'&client=Charge Client/Equipe &date=Total Equipe '.$mon_equipe.'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=230\');return(false)">Charge Clients /l\'&#233;quipe : <img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Camembert de charge des &#233;quipes"/></a>';

			// Courbe charge des clients par tranche horaire.
			$i++;
			$T_envoi_clients = new ClassArray();
			$i_tab = 0;
			while( $i_tab < $this->tab_clients->count() )
			{
				if( $this->tab_equipes[$i_tab] == $mon_equipe)
				{
					// $tmp_client = $this->tab_clients[$i_tab];
					// $T_envoi_clients->add( new client( $tmp_client->nom, $tmp_client->H_ouverture, $tmp_client->H_fermeture, $tmp_client->equipe, $tmp_client->compte_we, $tmp_client->compte_jf, $tmp_client->borne_min, $tmp_client->borne_max, $tmp_client->borne_min_decro, $tmp_client->borne_max_decro, $tmp_client->SLA, $tmp_client->couleur, 0));
					$T_envoi_clients->add( $this->tab_clients[$i_tab]);
				}	
				$i_tab++;
			}
			$_SESSION["listeclients$mon_equipe"] = serialize($T_envoi_clients);
			echo ' <a href="#" onClick="window.open(\'drawgraph.php?type=totalequipe&equipe='.$mon_equipe.'\',\'_blank\',\'toolbar=0, location=0, directories=0, status=0, scrollbars=1, resizable=1, copyhistory=0, menuBar=0, width=730, height=330\');return(false)">Charge Clients /tranche horaire : <img src="images/icone_graphique.jpg" WIDTH="25" HEIGHT="27" alt="Graph" title="Camembert de charge des &#233;quipes"/></a>';
			
			
			
			
			echo '</div>';
			$h++;
		}
	}
	public function add_requete( client $client, datetime $ledebut, datetime $lafin ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_13");

		// On ajoute le client au Tab_Client
		$this->tab_clients->add( $client );
		
		// On ajoute l'�quipe au Tab_Equipe
		$this->tab_equipes->add( $client->equipe );
		
		// Si elle n'existe pas, on l'ajoute a la liste ordonn�e des equipes.
		if( $this->liste_equipes->find( $client->equipe ) == -1 )
		{
			$this->liste_equipes->add( $client->equipe );
		}
		
		// On ajoute au Groupe_Client
		$this->groupe_clients->add_client( $ledebut, $lafin, $client );
		
		// On r�initialise les tableaux de r�sultats du client (celui qui permet de stocker les r�sultats pour plus facilement retrouver les valeur pour faire les calculs pour les graphiques /j /client /equipe
		$client->graph_par_jour = new ClassArray();
		$client->graph_total = new ClassArray();

		//echo "Add Requete : Client : ".$client." equipe : ".$client->equipe."<br/>";
	}
	public function resume_requete( $max = 0){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_12");

	echo '<table>'; //<caption> R�sum� de la requ�te en cours</caption>';
	echo '<tr>';
	echo "		<th>Equipe</th>
				<th>Client</th>
				<th>Dates</th>";

	echo "</tr>";			
	
	if( $max == 0 )
		$max = $this->tab_clients->count();
	else if( $max >  $this->tab_clients->count() )
		$max = $this->tab_clients->count();
		
	$i=0;
	while( $i < $max )
	{
		echo '<tr>';
			echo '<td>'.$this->tab_equipes[$i].'</td>';
			echo '<td>'.$this->tab_clients[$i].'</td>';
			echo '<td>'.$this->groupe_clients->liste_intervals[$i].'</td>';
		echo '</tr>';
		$i++;
	}
	
	echo "</table>";
	}
	
	public function test_num_appelant( ClassArray $result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_11");

		$i=0;
		while( $i < $this->groupe_clients->liste_intervals[0]->liste_jours[0]->groupe_appels->liste_appels->count() )
		{
			$result[$i] = $this->groupe_clients->liste_intervals[0]->liste_jours[0]->groupe_appels->liste_appels[$i]->num_appelant;
			$i++;
		}
	}
	
	public function get_graph_charge_equipe( ClassArray $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_10");

		
		
	}
	public function get_graph_charge_semaine( ClassArray $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_9");

		$i_equipe = 0;
		while( $i_equipe < $this->liste_equipes->count() )
		{
			// 2eme dimension // Pour les clients
			$tab_result[$i_equipe] = new ClassArray();
		
			// Init des sommes des moyennes.
			$sm_lundi = 0;
			$sm_mardi = 0;
			$sm_mercredi = 0;
			$sm_jeudi = 0;
			$sm_vendredi = 0;
			$sm_samedi = 0;
			$sm_dimanche = 0;

			// On fait le tour des clients ... 
			$i_client = 0;
			$i_tabclient = 0;
			while( $i_tabclient < $this->tab_clients->count() )
			{
				//echo "get_stats i_client=$i_client; i_equipe=$i_equipe Client=".$this->groupe_clients->liste_clients[$i_client].": equipe client = ".$this->tab_clients[$i_client]->equipe."; tab equipe =  ".$this->tab_equipes[$i_equipe]."<br/>";
				// Si le client fait partie de l'�quipe en question alors on le traite. (pour info : L'offset client == offset equipe... cad pour le meme offset on a l'�quipe ou le client...)
				if( $this->liste_equipes[$i_equipe] == $this->tab_clients[$i_tabclient]->equipe )
				{
					// On cr�� la 3eme dimension...! pour les jours
					$tab_result[$i_equipe][$i_client] = new ClassArray();
						
					// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
					// $un_client = $this->groupe_clients->liste_clients[$i_client];
					$un_client = $this->groupe_clients->liste_clients[$i_tabclient];
					
					$nb_lundi = 0;
					$nb_mardi = 0;
					$nb_mercredi = 0;
					$nb_jeudi = 0;
					$nb_vendredi = 0;
					$nb_samedi = 0;
					$nb_dimanche = 0;
					
					$nb_appel_lundi = 0;
					$nb_appel_mardi = 0;
					$nb_appel_mercredi = 0;
					$nb_appel_jeudi = 0;
					$nb_appel_vendredi = 0;
					$nb_appel_samedi = 0;
					$nb_appel_dimanche = 0;
			
					$m_lundi = 0;
					$m_mardi = 0;
					$m_mercredi = 0;
					$m_jeudi = 0;
					$m_vendredi = 0;
					$m_samedi = 0;
					$m_dimanche = 0;


					// On fait le tour des dates.
					$i_date = 0; // Dans le tableau de r�sultats
					$i_tabdate = 0; // dans le tableau de Classes "Jour"
					while( $i_tabdate < $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours->count() )
					{
						//	echo "get_stats : tab[$i_equipe][$i_client][$i_date]<br/>";
						$un_jour = $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours[$i_tabdate];
						
						// Est-ce un jour f�ri� ou WE? et le client souhaite-t-il afficher les WE et JF?						
						$show = true;
						// Si c'est un WE?
						if( $un_jour->is_we == 1 )
						{
							// Que le client veut afficher les WE
							if( $un_client->compte_we == 1 )
								$show = true;
							else
								$show = false;
						}
						// Si c'est pas un WE, c'est peut-etre un JF?
						if( $un_jour->is_jf == 1 )
						{
							// Que le client veut afficher les JF
							if( $un_client->compte_jf == 1 )
								$show = true;
							else
								$show = false;
						}				
						
						if( $show == true )
						{	
							// On cr�� la 4eeme dimension...! pour les cellules du tableau.
							// $tab_result[$i_equipe][$i_client][$i_date] = new ClassArray();
						
							// On fait le tour des appels. 
							$nb_appels_total = $un_jour->groupe_appels->liste_appels->count();					
							$nb_abandons_dessous_borne_min 		= 0;
							
							$i_appel = 0;
							while( $i_appel < $un_jour->groupe_appels->liste_appels->count() )
							{
								// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
								$un_appel = $un_jour->groupe_appels->liste_appels[$i_appel];
								
								// Les abandons
								if( $un_appel->poste_operateur == "" )
								{								
									// Nb Abandons en dessous de la borne MIN du client
									if( $un_appel->tps_attente_av_decro < $un_client->borne_min )
									{
										$nb_abandons_dessous_borne_min ++;
									}
								}				
								
								$i_appel++;
							}
							
							//echo "Get_Graph : QuelJour? ".$un_jour->quel_jour."<br/>";
							if( $un_jour->quel_jour == "Lundi" )
							{
								$nb_lundi ++;
								$nb_appel_lundi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Mardi" )
							{
								$nb_mardi ++;
								$nb_appel_mardi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Mercredi" )
							{
								$nb_mercredi ++;
								$nb_appel_mercredi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Jeudi" )
							{
								$nb_jeudi ++;
								$nb_appel_jeudi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Vendredi" )
							{
								$nb_vendredi++;
								$nb_appel_vendredi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Samedi" )
							{
								$nb_samedi ++;
								$nb_appel_samedi += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							else if( $un_jour->quel_jour == "Dimanche" )
							{
								$nb_dimanche ++;
								$nb_appel_dimanche += ($nb_appels_total - $nb_abandons_dessous_borne_min);
							}
							
							$i_date++;
						}
						$i_tabdate++;
					}
					
					// On calcule les moyennes d'appel par jour.
					if( $nb_lundi != 0 )
						$m_lundi = $nb_appel_lundi / $nb_lundi;
					else
						$m_lundi = 0;

					if( $nb_mardi != 0 )
						$m_mardi = $nb_appel_mardi / $nb_mardi;
					else
						$m_mardi = 0;

					if( $nb_mercredi != 0 )
						$m_mercredi = $nb_appel_mercredi / $nb_mercredi;
					else
						$m_mercredi = 0;

					if( $nb_jeudi != 0 )
						$m_jeudi = $nb_appel_jeudi / $nb_jeudi;
					else
						$m_jeudi = 0;

					if( $nb_vendredi != 0 )
						$m_vendredi = $nb_appel_vendredi / $nb_vendredi;
					else
						$m_vendredi = 0;

					if( $nb_samedi != 0 )
						$m_samedi = $nb_appel_samedi / $nb_samedi;
					else
						$m_samedi = 0;

					if( $nb_dimanche != 0 )
						$m_dimanche = $nb_appel_dimanche / $nb_dimanche;
					else
						$m_dimanche = 0;
					
					// Attribution au tableau de r�sultat.
					$tab_result[$i_equipe][$i_client][0] = $m_lundi;
					$tab_result[$i_equipe][$i_client][1] = $m_mardi;
					$tab_result[$i_equipe][$i_client][2] = $m_mercredi;
					$tab_result[$i_equipe][$i_client][3] = $m_jeudi;
					$tab_result[$i_equipe][$i_client][4] = $m_vendredi;
					$tab_result[$i_equipe][$i_client][5] = $m_samedi;
					$tab_result[$i_equipe][$i_client][6] = $m_dimanche;
					
					// Pour le total/moyenne par �quipe
					$sm_lundi += $m_lundi;
					$sm_mardi += $m_mardi;
					$sm_mercredi += $m_mercredi;
					$sm_jeudi += $m_jeudi;
					$sm_vendredi += $m_vendredi;
					$sm_samedi += $m_samedi;
					$sm_dimanche += $m_dimanche;
					
					$i_client++;
				}
				$i_tabclient++;				
			}
			
			// Total pour l'�quipe
			$tab_result[$i_equipe][$i_client] = new ClassArray();
			$nb_clients = $this->tab_clients->count();
			
			//echo "Nb Client : ".$nb_clients." sm_lundi ".$sm_lundi."<br/>";
			if( $nb_clients != 0 )
			{
				$tab_result[$i_equipe][$i_client][0] = $sm_lundi 	/ $nb_clients;
				$tab_result[$i_equipe][$i_client][1] = $sm_mardi 	/ $nb_clients;
				$tab_result[$i_equipe][$i_client][2] = $sm_mercredi / $nb_clients;
				$tab_result[$i_equipe][$i_client][3] = $sm_jeudi 	/ $nb_clients;
				$tab_result[$i_equipe][$i_client][4] = $sm_vendredi / $nb_clients;
				$tab_result[$i_equipe][$i_client][5] = $sm_samedi 	/ $nb_clients;
				$tab_result[$i_equipe][$i_client][6] = $sm_dimanche / $nb_clients;
			}
			else
			{
				$tab_result[$i_equipe][$i_client][0] = 0;
				$tab_result[$i_equipe][$i_client][1] = 0;
				$tab_result[$i_equipe][$i_client][2] = 0;
				$tab_result[$i_equipe][$i_client][3] = 0;
				$tab_result[$i_equipe][$i_client][4] = 0;
				$tab_result[$i_equipe][$i_client][5] = 0;
				$tab_result[$i_equipe][$i_client][6] = 0;
			} 

			// Pour le total �quipe
			$i_client++;
			$tab_result[$i_equipe][$i_client] = new ClassArray();
			$tab_result[$i_equipe][$i_client][0] = $sm_lundi;
			$tab_result[$i_equipe][$i_client][1] = $sm_mardi;
			$tab_result[$i_equipe][$i_client][2] = $sm_mercredi;
			$tab_result[$i_equipe][$i_client][3] = $sm_jeudi;
			$tab_result[$i_equipe][$i_client][4] = $sm_vendredi;
			$tab_result[$i_equipe][$i_client][5] = $sm_samedi;
			$tab_result[$i_equipe][$i_client][6] = $sm_dimanche;
			
			
			$i_equipe++;
		}
		
		return $tab_result;
	}



	public function show_featuringz( $max_top ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_8");

	
		// Pr�paration du tableau de r�sultat.
		// - TOP 10 appelant
		// 		- Sur le total de la requete.
		//		- Par �quipe
		//		- Par Client
		// - TOP 10 D�croch�s
		// 		- nb Decro / op�rateur.
		//		- nb decro / client (puis /op�)
		// 		- nb d�cro /op� (puis /client)
		// - TOP Appels
		//		- Top 10 appels les plus long
		//		- TOP 10 appel ayant attendu le plus longtemps.
		//		- Temps moyen de communication/op�rateur.
		//		- tps moyen de d'attente avant d�croch� / op�rateur
		//		- Nb appels HNO/equipe/client.
		
		// D'ou le tableau : $tab_result[top][categorie][value]
		//	Taille du (sous)tableau :	  3		   5	$max_top
	}
	
	public function get_moyennes_ope( ClassArray $tab_vrac_appels, $TabOccurencesOpe, ClassArray $TabResult ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_7");
	
	
		// On fait le tour du tableau d'occurence des op�rateurs
		$j=0;
		foreach ($TabOccurencesOpe as $Operateur => $NbAppels) 
		{
			$i=0;
			$MoyenneAtt = 0;
			$MoyenneCom = 0;
			$CumulTpsAtt = 0;
			$CumulTpsCom = 0;
			// On additionne les temps d'attentes pour l'op�rateur en question.
			while( $i < $tab_vrac_appels->count() )
			{
				if( $tab_vrac_appels[$i][5] == $Operateur )
				{
					$CumulTpsAtt += $tab_vrac_appels[$i][1];
					$CumulTpsCom += $tab_vrac_appels[$i][2];
				}
				$i++;
			}
			
			if( $NbAppels != 0 )
			{
				$MoyenneAtt = $CumulTpsAtt/$NbAppels;
				$MoyenneCom = $CumulTpsCom/$NbAppels;
			}
			
			$TabResult[$j] = new ClassArray();
			$TabResult[$j][0] = $Operateur;
			$TabResult[$j][1] = $MoyenneAtt;
			$TabResult[$j][2] = $MoyenneCom;
			
			$j++;
		}
		
	}
	public function get_combien_decro_operateur( ClassArray $tab_vrac_appels ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_6");

		// On met les op� dans un tab
		$tab_ope = new ClassArray();
		$this->put_ope_tab( $tab_vrac_appels, $tab_ope );
		
		// On r�cup les occurences.
		$TabOccurencesOpe = $tab_ope->occurences();
		
		// On tri les valeurs par Nombre de fois qu'apparait l'occurence.
		arsort( $TabOccurencesOpe );
	
		return $TabOccurencesOpe;
	}
	public function get_combien_fois_num_appelle( ClassArray $tab_vrac_appels ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_5");


		// On met les num�ros de t�l�phone dans un tableau.
		$tab_num = new ClassArray();
		$this->put_num_tab( $tab_vrac_appels, $tab_num );
		
		// On r�cup�re les occurences et leur nombre
		$TabOccurencesNum = $tab_num->occurences();
		
		// On tri les valeurs par Nombre de fois qu'apparait l'occurence.
		arsort( $TabOccurencesNum );

		return $TabOccurencesNum;
	}
	public function get_appels_attendu_plus_longtemps( ClassArray $tab_vrac_appels, ClassArray $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_4");


		// Inserer petit � petit les appels par ordre d�croissant de dur�e d'appel
		$i = 0;
		$tab_temps = new ClassArray();
		$tab_num = new ClassArray();
		$tab_ope = new ClassArray();
		$tab_temps[0] = 0.001;
		$tab_temps[1] = 0; // Permet de donner un sens d'organisation du tableau.=> �vite que l'insert se fasse en ordre croissant alors qu'on veut du d�croissant.
		
	//	echo  'Nombre appel en vrac : '.$tab_vrac_appels->count().'<br/>';
		while( $i < $tab_vrac_appels->count() )
		{
			// 6 �tant l'emplacement du num�ro appelant.// 1 �tant l'emplacement du temps d'attente avant d�cro.// 5 �tant l'emplacement le nom de l'op�rateur
			// De facon a dire : Le num XXX a attendu XX secondes avant que l'op�rateur XX D�croche. (Si Op� vide : appel non d�cro.)
 
			$offset = $tab_temps->insert( $tab_vrac_appels[$i][1] );
			if( $offset == -1 )
			{
				echo 'erreur d\'insertion<br/>';
				break;
			}
			$tab_num->insert( $tab_vrac_appels[$i][6], $offset );
			$tab_ope->insert( $tab_vrac_appels[$i][5], $offset );
 
		//	echo  'Valeur de I : '.$i.'    Valeur de OFFSET : '.$offset.' $tab_vrac_appels[$i][5] : '.$tab_vrac_appels[$i][5].'<br/>';

			$i++;
		}
	
		// On bascule les 3 tableaux dans 1 => Le tab result
		$i=0;
		while( $i < $tab_temps->count() )
		{	
			$tab_result[$i] = new ClassArray();
			$tab_result[$i][1] = $tab_num[$i];
			$tab_result[$i][2] = $tab_temps[$i];
			$tab_result[$i][3] = $tab_ope[$i];
 			//echo "Ope[$i] : ".$tab_ope[$i].'<br/>';
			
			$i++;
		}
	}
	
	public function get_appels_les_plus_long( ClassArray $tab_vrac_appels, ClassArray $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_3");


		// Inserer petit � petit les appels par ordre d�croissant de dur�e d'appel
		$i = 0;
		$tab_temps = new ClassArray();
		$tab_num = new ClassArray();
		$tab_ope = new ClassArray();
		$tab_temps[0] = 0.001;
		$tab_temps[1] = 0; // Permet de donner un sens d'organisation du tableau.=> �vite que l'insert se fasse en ordre croissant alors qu'on veut du d�croissant.
		
	//	echo  'Nombre appel en vrac : '.$tab_vrac_appels->count().'<br/>';
		while( $i < $tab_vrac_appels->count() )
		{
			// 6 �tant l'emplacement du num�ro appelant.// 2 �tant l'emplacement du temps de com.// 5 �tant l'emplacement le nom de l'op�rateur
			// De facon a dire : Le num XXX a attendu XX secondes avant que l'op�rateur XX D�croche. (Si Op� vide : appel non d�cro.)
 
			$offset = $tab_temps->insert( $tab_vrac_appels[$i][2] );
			if( $offset == -1 )
			{
				echo 'erreur d\'insertion<br/>';
				break;
			}
			$tab_num->insert( $tab_vrac_appels[$i][6], $offset );
			$tab_ope->insert( $tab_vrac_appels[$i][5], $offset );
 
		//	echo  'Valeur de I : '.$i.'    Valeur de OFFSET : '.$offset.' $tab_vrac_appels[$i][5] : '.$tab_vrac_appels[$i][5].'<br/>';

			$i++;
		}
	
		// On bascule les 3 tableaux dans 1 => Le tab result
		$i=0;
		while( $i < $tab_temps->count() )
		{	
			$tab_result[$i] = new ClassArray();
			$tab_result[$i][1] = $tab_num[$i];
			$tab_result[$i][2] = $tab_temps[$i];
			$tab_result[$i][3] = $tab_ope[$i];
 			//echo "Ope[$i] : ".$tab_ope[$i].'<br/>';
			
			$i++;
		}
	}	public function put_ope_tab( $tab_appels, $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_2");

		$i = 0;
		while( $i < $tab_appels->count() )
		{
			$tab_result[$i] = $tab_appels[$i][5]; // 5 �tant l'emplacement le nom de l'op�rateur
			$i++;
		}
	}	
	public function put_num_tab( $tab_appels, $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_1");

		$i = 0;
		while( $i < $tab_appels->count() )
		{
			$tab_result[$i] = $tab_appels[$i][6]; // 6 �tant l'emplacement des num�ro de t�l�phone appelant.
			$i++;
		}
	}
	public function put_calls_tab( $tab_result ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\c_requete.php_0");

		//$tab_result = new ClassArray();
		
		$i_tab = 0;
		$i_equipe = 0;
		while( $i_equipe < $this->liste_equipes->count() )
		{
		
			// On fait le tour des clients ... 
			$i_client = 0;
			$i_tabclient = 0;
			while( $i_tabclient < $this->tab_clients->count() )
			{
				//echo "get_stats i_client=$i_client; i_equipe=$i_equipe Client=".$this->groupe_clients->liste_clients[$i_client].": equipe client = ".$this->tab_clients[$i_client]->equipe."; tab equipe =  ".$this->tab_equipes[$i_equipe]."<br/>";
				// Si le client fait partie de l'�quipe en question alors on le traite. (pour info : L'offset client == offset equipe... cad pour le meme offset on a l'�quipe ou le client...)
				if( $this->liste_equipes[$i_equipe] == $this->tab_clients[$i_tabclient]->equipe )
				{
						
					// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
					// $un_client = $this->groupe_clients->liste_clients[$i_client];
					$un_client = $this->groupe_clients->liste_clients[$i_tabclient];

					// On fait le tour des dates.
					$i_date = 0; // Dans le tableau de r�sultats
					$i_tabdate = 0; // dans le tableau de Classes "Jour"
					while( $i_tabdate < $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours->count() )
					{
						//	echo "get_stats : tab[$i_equipe][$i_client][$i_date]<br/>";
						$un_jour = $this->groupe_clients->liste_intervals[$i_tabclient]->liste_jours[$i_tabdate];
						
						// Est-ce un jour f�ri� ou WE? et le client souhaite-t-il afficher les WE et JF?						
						$show = true;
						// Si c'est un WE?
						if( $un_jour->is_we == 1 )
						{
							// Que le client veut afficher les WE
							if( $un_client->compte_we == 1 )
								$show = true;
							else
								$show = false;
						}
						// Si c'est pas un WE, c'est peut-etre un JF?
						if( $un_jour->is_jf == 1 )
						{
							// Que le client veut afficher les JF
							if( $un_client->compte_jf == 1 )
								$show = true;
							else
								$show = false;
						}				
						
						if( $show == true )
						{	
							// On cr�� la 4eeme dimension...! pour les cellules du tableau.
							// $tab_result[$i_equipe][$i_client][$i_date] = new ClassArray();
						
							
							
							// On fait le tour des appels. 
							$nb_appels_total = $un_jour->groupe_appels->liste_appels->count();					
							$nb_abandons_dessous_borne_min 		= 0;
							
							$i_appel = 0;
							while( $i_appel < $un_jour->groupe_appels->liste_appels->count() )
							{
								// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
								$un_appel = $un_jour->groupe_appels->liste_appels[$i_appel];
								
								// On cr�� une 2eme dimension au tab pour inserer les caract�ristiques de l'appel
								$tab_result[$i_tab] = new ClassArray();
								
								// On y insere les donn�es de l'appel
								$tab_result[$i_tab][0] = $un_appel->date;
								$tab_result[$i_tab][1] = $un_appel->tps_attente_av_decro;
								$tab_result[$i_tab][2] = $un_appel->tps_com;
								$tab_result[$i_tab][3] = $un_appel->nom_client;
								$tab_result[$i_tab][4] = $un_appel->poste_operateur;
								$tab_result[$i_tab][5] = $un_appel->nom_operateur;
								$tab_result[$i_tab][6] = $un_appel->num_appelant;
								
								$i_tab ++;
								$i_appel++;
							}
							
							$i_date++;
						}
						$i_tabdate++;
					}
					// Traitement Total pour client
					$i_client++;
				}
				$i_tabclient++;				
			}
			
			// Traitement Total pour l'�quipe
			
			$i_equipe++;
		}
		
		return $tab_result;	
	}
}

?>