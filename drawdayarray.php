<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\drawdayarray.php_0");
 session_start();
	require_once("class.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo "Detail ".$_GET['client']." - ".$_GET['date'] ?></title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/esico.ico" />
	<link rel="icon" type="image/png" href="images/esico.ico" />

</head>
<body>
<?php

	$tab_temp_jour = new ClassArray();
	
	if( isset( $_GET['type'] ) ) // 
	{
		if(isset( $_GET['dda'] )) 
		{
			//////////////////////////////////
			// Calcul des appels demand�s.
			
			// la journ�e entiere est enregistr�e dans une variable de SESSION.
			// Ainsi que le client concern�.
			// On les r�cup�re via les $_GET['dda'] et $_GET['client']
			$session_jour = $_GET['dda'];
			$session_client = $_GET['client'];
			$session_horaires = $_GET['dda']."_horaires";
			
			
			//echo $session_horaires."<br/>";
			
			$un_jour = unserialize( $_SESSION["$session_jour"] );
			$un_client = unserialize( $_SESSION["$session_client"] );
			$Horaires = unserialize( $_SESSION["$session_horaires"] );
					
			for( $i = 0; $i < $Horaires->count(); $i++ ){
				//echo $Horaires[$i];//->format( 'Y/m/d H:i:s' );
				$un_jour->groupe_appels->liste_appels[$i]->date = new DateTime( $Horaires[$i] );				
			}
			//echo $_SESSION["$session_jour"]."<br/>";
			
			// $_GET['type'] == � la colonne du tableau.
			// 2 = NB total appels
			// 3 = NB total abandons
			// 4 ...
			
			// On fait le tour des appels de la journ�e
			$i_appel = 0;
			while( $i_appel < $un_jour->groupe_appels->liste_appels->count() )
			{
				// Permet de simplifier les lignes de requetes ci-dessous... sinon il faut tout r��crire ... fioulala ...! ;)
				$un_appel = $un_jour->groupe_appels->liste_appels[$i_appel];
		
				// Pour test
				//echo $un_appel."<br/>";
					
				// Est-on sur une plage de crash?
				mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur MySql impossible");
				mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql impossible");

				$borne_crash = 0;
				if( $un_appel->is_on_crash() ){
//					echo "Appel : ".$un_appel." est en crash<br/>";
									
					$cmd_line = "SELECT duree FROM crashs WHERE client = '".$un_appel->nom_client."' AND date_debut < '".$un_appel->date->format('Y-m-d H:i:s')."' AND date_fin > '".$un_appel->date->format('Y-m-d H:i:s')."'";
					$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line ));
					$borne_crash = $donnees['duree'];
				}

				
				if( $_GET['type'] == 2 or $_GET['type'] == 4)
					$tab_temp_jour->add($un_appel);
					
				// Les abandons
				if( $un_appel->poste_operateur == "" )
				{
					// Nb abandons total.
					//$nb_abandons_total ++;
					if( $_GET['type'] == 3 )
						$tab_temp_jour->add($un_appel);
					
					// Nb Abandons en dessous de la borne MIN du client
					if( $un_appel->tps_attente_av_decro < $un_client->borne_min + $borne_crash )
					{
						//$nb_abandons_dessous_borne_min ++;
						if( $_GET['type'] == 6 )
							$tab_temp_jour->add($un_appel);
					}
					// Si pas abandonn� avant borne min mais abandonn� avant borne max 
					else if( $un_appel->tps_attente_av_decro < $un_client->borne_max + $borne_crash )
					{
						//$nb_abandons_entre_borne_min_max ++;
						if( $_GET['type'] == 7 )
							$tab_temp_jour->add($un_appel);
					}
					// Sinon ... b� c'est un appel abandonn� apres la borne max...
					else 
					{	
						//$nb_abandons_dessus_borne_max ++;
						if( $_GET['type'] == 8 )
							$tab_temp_jour->add($un_appel);
					}
				}
				else
				{	// Si c pas abandonn� alors c'est d�croch�s.
					//$nb_decro_total ++;
					if( $_GET['type'] == 5 )
						$tab_temp_jour->add($un_appel);
							
					// Addition du temps de communication pour la moyenne. SIIII tps de com > 5sec.
					/* if( $un_appel->tps_com > 5 )
					{
						$somme_tps_com += $un_appel->tps_com;
						$nb_appel_tps_com_dessus_5s ++;
					} */
					
					// Si d�cro avant la borne min du client
					if( $un_appel->tps_attente_av_decro <= $un_client->borne_min_decro + $borne_crash )
					{
						//$nb_decro_dessous_borne_min ++;
						if( $_GET['type'] == 9 )
							$tab_temp_jour->add($un_appel);
					}
					// Si pas d�cro avant borne min mais d�cro avant borne max 
					else if( $un_appel->tps_attente_av_decro <= $un_client->borne_max_decro + $borne_crash )
					{
						//$nb_decro_entre_borne_min_max ++;
						if( $_GET['type'] == 10 )
							$tab_temp_jour->add($un_appel);
					}
					// Sinon ... b� c'est un appel d�cro apres la borne max...
					else 
					{	
						//$nb_decro_dessus_borne_max ++;
						if( $_GET['type'] == 11 )
							$tab_temp_jour->add($un_appel);
					}
				}
				$i_appel++;
			}	
			
			// Si on demande la base statistique, nous avons dans le tableau le nombre total d'appels...
			// Il faut retirer les appel abandonn�s < borne_min
			if( $_GET['type'] == 4)
			{
				$i=0;
				while( $i < $tab_temp_jour->count() )
				{
					// Si appel non d�croch�
					if( $tab_temp_jour[$i]->poste_operateur == "" )
					{
						// si non d�croch� avant borne min
						if( $tab_temp_jour[$i]->tps_attente_av_decro < $un_client->borne_min )
						{
							// Alors on l'enleve de la liste.
							$tab_temp_jour->del($i);
							// On recule d'un cran car on va avancer apres ... et on va sauter une valeur vu que le DEL remet le tableau de facon continue.
							// (il n'y aura pas de trou � l'emplacement $i)
							$i--;
						}
					}
					$i++;
				}
			}
				
			/////////////////////////////////
			// Affichage du tableau
			
			//echo $tab_temp_jour->count();
			
			// Saffiche ici le total par �quipe
			echo '<div class="cadre"><br/><table>';
			echo '<tr>';
				echo "<th>Date</th>".
					"<th>Temps d'attente</th>".
					"<th>Temps de communication</th>".
					"<th>Nom du client</th>".
					"<th>Poste de l'op�rateur</th>".
					"<th>Nom de l'op�rateur</th>".
					"<th>Num�ro de l'appelant</th>".
					"<th>Crash?</th>";
			echo '</tr>';
			
			$i_appel=0;
			while( $i_appel < $tab_temp_jour->count() )
			{
				echo '<tr>';
					echo '<td>'.$tab_temp_jour[$i_appel].'</td>';//'.$tab_temp_jour[$i_appel]->date->format('d/m').'
					echo '<td>'.NumToHour($tab_temp_jour[$i_appel]->tps_attente_av_decro).'</td>';
					echo '<td>'.NumToHour($tab_temp_jour[$i_appel]->tps_com).'</td>';
					echo '<td>'.$tab_temp_jour[$i_appel]->nom_client.'</td>';
					echo '<td>'.$tab_temp_jour[$i_appel]->poste_operateur.'</td>';
					echo '<td>'.$tab_temp_jour[$i_appel]->nom_operateur.'</td>';
					echo '<td>'.EspaceNum( $tab_temp_jour[$i_appel]->num_appelant).'</td>';
					if( $tab_temp_jour[$i_appel]->is_on_crash() )
						echo '<td>Oui</td>';
					else
						echo '<td>Non</td>';
						
				echo '</tr>';
				$i_appel++;
			}
			echo "</table></div>";
			

			
			
			
			
			
			
			
		}
		else
			echo "Erreur d'appel � cette page, dda incorrect.";
	}
	else
	{
		echo "Erreur d'appel de cette page, aucun tableau ne peut etre affich� : type non sp�cifi�.";
	}
	
?>
</body>
</html>