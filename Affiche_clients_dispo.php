<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\Affiche_clients_dispo.php_0");

	session_start();
	require_once("class.php");

	$appli = unserialize($_SESSION['appli']);
	
	if( isset( $_GET['ChangeDate'] ) )
	{
		$_SESSION['DateMin'] = "";
		$_SESSION['DateMax'] = "";		

		if( isset( $_GET['PremDate'] )  and isset( $_GET['DeuxDate'] ) )
			if( verif_date($_GET['PremDate']) and verif_date($_GET['DeuxDate']))
			{
				$_SESSION['DateMin'] = reverse_date($_GET['PremDate']); 
				$_SESSION['DateMax'] = reverse_date($_GET['DeuxDate']); 
			}
	}

	if( !isset( $_SESSION['DateMin'] ) or !isset( $_SESSION['DateMax'] ) or $_SESSION['DateMin'] == ""  or $_SESSION['DateMax'] == "")
	{
		echo '<div class="cadre" > <strong>Liste des clients disponibles </strong><br/><br/><br/>';
		echo '<div style="text-align:center"><h3>Veuillez s&#233;lectionner 2 dates</h3><br/><br/><br/><br/></div></div>';
	}
	else
	{
		//////////////
		////////////// Affichage des &#233;quipes.
		//////////////

		if( !$appli->stat->have_equipes_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] ) )
		{
			echo '<div class="cadre"><strong>Liste des clients disponibles </strong><br/><h2>Il n\'y a pas de clients pour les dates demandees.</h2></div><br/><br/><br/><br/><br/>';
		}
		else
		{
			//echo '<fieldset style="border: 0px solid white">';
			echo '<div class="cadre" > <strong>Liste des clients disponibles </strong><br/>';	
			echo '<fieldset style="border: 0px solid white">';	
			echo '<form name="select_clients" id="select_clients" method="post" action="" >';
			//echo '<input type="hidden" name="valider" value="true" />';
			
			$i_equipe = 0;
			$RecapTotClient = "";
			while( $i_equipe < $appli->stat->liste_equipes->count() )
			{
				//$equipe = $donnees1['equipe'];
				$equipe = $appli->stat->liste_equipes[$i_equipe];
				
				// SI l'&#233;quipe a des clients de dispo ... alors on affiche.
				if( $equipe->have_clients_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] ))
				{
					echo '<fieldset style="margin-bottom:15px; background-color:#FFFFCC;">'
						.'<legend>'
							.'<input type="checkbox" id="'.$equipe.'_checkbox" onclick="if (this.checked){cocheequipe(\''.$equipe.'\',\'1\');}else{cocheequipe(\''.$equipe.'\',\'0\');}"/>'
							."<strong>".$equipe."</strong>"
						.'</legend>';
					
					
					//////////////
					////////////// Affichage des clients dispo.
					//////////////
			
					$RecapClient = "";
					$i_client = 0;
					while( $i_client < $equipe->liste_clients->count() )
					{
						$client = $equipe->liste_clients[$i_client];
						$text_dispo = "Disponibilites : du ".reverse_date($client->dispo_min)." au ".reverse_date($client->dispo_max);
						$dispo_client = $client->is_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] );
						
						// Si le client est dispo... on pr&#233;scise par la suite.
						if( @($dispo_client == FULL_DISPO or $dispo_client == ONLY_START_DISPO or $dispo_client == ONLY_END_DISPO or $dispo_client == END_START_NOT_DISPO) )
						{
							echo '<div style="float:left" >'; // Permet de rassembler Coche et text ... sinon il se pt qu'une coche soit en haut a droite ... et le texte en bas a gauche.
							
							
							if( $client->is_select )
								echo '<input type="checkbox" id="'.$client.'_checkbox" name ="'.$client->nick.'_checkbox" checked="checked"/>';
							else
								echo '<input type="checkbox" id="'.$client.'_checkbox" name ="'.$client->nick.'_checkbox"/>';

							if( @($dispo_client == FULL_DISPO) )
							{
								echo '<label for="'.$client->nick.'" style="color:green; margin-right:15px;" title="'.$text_dispo.'" >'.$client."</label>";
							}
							else if( @($dispo_client == ONLY_START_DISPO  or $dispo_client == ONLY_END_DISPO ))
							{
								echo '<label for="'.$client->nick.'" style="color:orange; margin-right:15px;" title="'.$text_dispo.'" >'.$client."</label>";
							}
							else if( @($dispo_client == END_START_NOT_DISPO) )
							{
								echo '<label for="'.$client->nick.'" style="color:red; margin-right:15px;" title="'.$text_dispo.'" >'.$client."</label>";
							}
					
							echo "</div>";
							
							$RecapClient = $RecapClient.'+'.$client.'=';		
						}
						// else
							// echo "Client ".$client.' non dispo<br/>';
						$i_client++;
					}
					
					// contient la liste des clients de l'&#233;quipe 
					echo '<input type="hidden" id="'.$equipe.'_text" value="'.$RecapClient.'"/>';
					$RecapTotClient = $RecapTotClient.$RecapClient;
					
					echo "</fieldset>";
				}
				$i_equipe++;
			}
			echo '<input type="hidden" id="ts_client_text" value="'.$RecapTotClient.'"/>';
			
			
			echo '</fieldset>';	
			echo '<fieldset style="border:0px solid white">';	
			//echo '<A HREF="javascript:document.select_clients.submit()">Valider</A> <a href="stat.php?valider=1">test</a>';
			
		
			echo '<input type="button" value="Valider"  name="valider"  style="float:right" onclick="get_stats();affiche_requete();return false"/>';
			if( $appli->stat->is_clients_select() )
				echo '<input type="submit" name="ajouter" value="Ajouter" onclick="montre_div(\'message\');" style="float:right"/>';
			echo '</fieldset>';	
			echo '</form>';
			
			echo '</div>';
			//echo '</fieldset>';	
		}
		// Message qui demande de patienter lorsqu'on demande les stats.
		//AffichMsgAttente();	
	}

	$_SESSION['appli'] = serialize( $appli );
	
	phpmv2();

?>