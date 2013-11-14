<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\drawgraph.php_1");
 session_start();
	require_once("class.php");
	$appli = unserialize($_SESSION['appli']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo "Graphique ".$_GET['client']." - ".$_GET['date'] ?></title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/esico.ico" />
	<link rel="icon" type="image/png" href="images/esico.ico" />

	<script language="JavaScript">
	function retailler() 
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\drawgraph.php_0");

 		// if (document.body)
		// {
			//var larg = (document.body.clientWidth);
			// var haut = (document.body.clientHeight);
		// }
		// else
		// {
			//var larg = (window.innerWidth);
			// var haut = (window.innerHeight);
		// }

		// document.write("Cette fen�tre fait 730 de large et "+haut+" de haut");
		// if( haut = 691 )
			// haut = 780;
		// else
			// haut = 691;
 			
		this.resizeTo( 730, 780 );
		//document.write("Cette fen�tre fait 730 de large et "+haut+" de haut");
	}
	</script> 

</head>
<body>

	
<?php
	if( isset( $_GET['type'] ) )
	{
		if( $_GET['type'] == "journee" or $_GET['type'] == "journeemoy" or $_GET['type'] == "totaljournees" )
		{
			if(isset( $_GET['dg'] )) 
			{
				$session = $_GET['dg'];
				$result = unserialize( $_SESSION["$session"] );
				
				////////////////////////////////////////////////////////////////////////
				// Si aucun graphique de selectionn�, on s�lectionne d'office les 3 1er.
				$i=1;
				$one_graph_selected = false;
				while( $i < $result->count() )
				{
					$courbe = "courbe".$i;
					if( isset( $_GET["$courbe"] ) )
					{
						$one_graph_selected = true;
						// On arrete la boucle
						$i = $result->count();
					}
					$i++;
				}
				if( $one_graph_selected == false )
				{
					$_GET['courbe1'] = 1;
					$_GET['courbe2'] = 1;
					$_GET['courbe3'] = 1;
					$_GET['valeur2'] = 1;
					$_GET['valeur3'] = 1;
				}
				
				//////////////////////////////////////////////////////////////////////
				// Calcul de la valeur la plus elev�e de facon a calibrer le graphique.
				$i=0;
				$maxvalue = 0;
				while( $i < $result[1]->count() )
				{
					if( $result[1][$i] > $maxvalue )
						$maxvalue = $result[1][$i];
					$i++;
				}
								
				$maxvalue += $maxvalue/4; // Evite que la valeur Max se trouve coll�e au haut du graphique.
				$txt = '<img src="http://chart.apis.google.com/chart?&chds=0,'.$maxvalue.'&cht=lxy&chd=t:'; // chds est la barre d'ordonn�e, il faut donc la calibrer en fonction des valeurs a afficher.
				$test = 'img src="http://chart.apis.google.com/chart?&chds=0,'.$maxvalue.'&cht=lxy&chd=t:'; // cht barre horizontale : bvs affiche des batons, lc des courbes.
				
				///////////////////////////////////////////////////////////////////////
				// Inscription des valeurs pour chacune des courbes.
				$is_preums = true;
				$n_courbe = 1;
				while( $n_courbe < $result->count() )
				{
					// On v�rifie s'il faut afficher cette courbe
					$courbe = "courbe".$n_courbe;
					if( isset( $_GET["$courbe"] ) )
					{
						if( $is_preums == false )
						{
							$txt = $txt."|";
							$test = $test."|";						
						}
						$txt = $txt."-1|";
						$test = $test."-1|";
					
						$i=0;
						while( $i < $result[$n_courbe]->count() )
						{
							$txt = $txt.$result[$n_courbe][$i];
							$test = $test.$result[$n_courbe][$i];
							if( $i < $result[$n_courbe]->count()-1 )
							{
								$txt = $txt.",";
								$test = $test.",";
							}
							$i++;
						}
					
						$is_preums = false;
					}
					$n_courbe++;
				}
				
				if( !isset( $_GET['thumb'] ) )
				{
					/////////////////////////////////////////////////////////
					// Affichage des horaires
					$i=0;
					$j=0;
					$first = true;
					$txt = $txt."&chl=";
					$test = $test."&chl=";
					while( $i < $result[0]->count() )
					{
						if( $j == 2 or $first == true )
						{
							$txt = $txt.$result[0][$i];
							$test = $test.$result[0][$i];
							$j=0;
							$first = false;
						}
						
						if( $i < $result[0]->count()-1 )
						{
							$txt = $txt."|";
							$test = $test."|";
						}
						$i++;
						$j++;
					}
				}
				/////////////////////////////////////////////////////////////////////
				////// Options d'affichage du graph.
				$height = 700;
				$width = 350;
				if( isset( $_GET['thumb'] ) )
				{
					$height = 70;
					$width = 35;
				}
				$txt = $txt.'&chs='.$height.'x'.$width; // Def de la taille de l'image/graphique
				$txt = $txt.'&chbh=20,2,0'; // Def de l'�cartement entre les graphiques ... je crois ...
				
				if( !isset( $_GET['thumb'] ) )
				{
					$txt = $txt.'&chm='; // Affichage des valeurs sur le graph?		
					$i = 1;
					$j = 0;
					$firsttime = true;
					while( $i < $result->count() )
					{
						$valeur = 'valeur'.$i;
						$courbe = 'courbe'.$i;
						
						if( isset( $_GET["$courbe"] ) )
						{
							if( $firsttime == false )
								$txt = $txt.'|';
													
							if( isset( $_GET["$valeur"] ) )
								$txt = $txt.'N,000000,'.$j.',-1,11';
							else
								$txt = $txt.'N,000000,-1,-1,11';
								
							$firsttime = false;
							$j++;
						}
						$i++;	
					}
				
					
					
					////////////////////
					// Legende
					$txt = $txt.'&chdl='; // Def de la l�gende
					if( isset( $_GET['courbe1'] ) )
						$txt = $txt.'Total+Appels';
					if( isset( $_GET['courbe2'] ) and isset( $_GET['courbe1'] ) )
						$txt = $txt.'|';
					if( isset( $_GET['courbe2'] ) )
						$txt = $txt.'Appels+Decroches';
					if( isset( $_GET['courbe3'] ) and ( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) ))
						$txt = $txt.'|';
					if( isset( $_GET['courbe3'] ) )
						$txt = $txt.'Base+Statistique+(Total-Abandons+<+Borne+minimale)';
					if( isset( $_GET['courbe4'] ) and ( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) ) )
						$txt = $txt.'|';
					if( isset( $_GET['courbe4'] ) )
						$txt = $txt.'Abandons+<+Borne+minimale';
					if( isset( $_GET['courbe5'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) ) )
						$txt = $txt.'|';
					if( isset( $_GET['courbe5'] ) )
						$txt = $txt.'Borne+minimale+<+Abandons+<+Borne+maximale';
					if( isset( $_GET['courbe6'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) )  )
						$txt = $txt.'|';
					if( isset( $_GET['courbe6'] ) )
						$txt = $txt.'Borne+maximale+<+Abandons';
					if( isset( $_GET['courbe7'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] ) )  )
						$txt = $txt.'|';
					if( isset( $_GET['courbe7'] ) )
						$txt = $txt.'Decroches+<+Borne+minimale';
					if( isset( $_GET['courbe8'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] )or isset( $_GET['courbe7'] ) )  )
						$txt = $txt.'|';
					if( isset( $_GET['courbe8'] ) )
						$txt = $txt.'Borne+minimale+<+Decroches+<+Borne+maximale';
					if( isset( $_GET['courbe9'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] )or isset( $_GET['courbe7'] )or isset( $_GET['courbe8'] ) ))
						$txt = $txt.'|';
					if( isset( $_GET['courbe9'] ) )
						$txt = $txt.'Borne+maximale+<+Decroches';
					
				}	
					
					$txt = $txt.'&chco=';//Def des couleurs
/* 					'00FFFF,'. // Bleu  Clair pour les appels total
					'FF0000,'. // Rouge pour les appels d�croch�s total
					'0033FF,'. // Bleu/Fonc� pour la base stat
					'FFE600,'. // Jaune/Orange pour les ab < borne min
					'FFB300,'. // Jaune/Orange+ pour les ab entre min et max
					'FF8000,'. // Jaune/Orange++ pour les ab apres max
					'00FF00,'. // Vert pour les appels d�cro avant min
					'669900,'. // Vert/Orange pour les appels d�cro entre min et max
					'996600'.  // Vert/Orange++ pour les appels d�cro apres max
					 */
					if( isset( $_GET['courbe1'] ) )
						$txt = $txt.'00FFFF';
					if( isset( $_GET['courbe2'] ) and isset( $_GET['courbe1'] ) )
						$txt = $txt.',';
					if( isset( $_GET['courbe2'] ) )
						$txt = $txt.'FF0000';
					if( isset( $_GET['courbe3'] ) and ( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) ))
						$txt = $txt.',';
					if( isset( $_GET['courbe3'] ) )
						$txt = $txt.'0033FF';
					if( isset( $_GET['courbe4'] ) and ( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) ) )
						$txt = $txt.',';
					if( isset( $_GET['courbe4'] ) )
						$txt = $txt.'FFE600';
					if( isset( $_GET['courbe5'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) ) )
						$txt = $txt.',';
					if( isset( $_GET['courbe5'] ) )
						$txt = $txt.'FFB300';
					if( isset( $_GET['courbe6'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) )  )
						$txt = $txt.',';
					if( isset( $_GET['courbe6'] ) )
						$txt = $txt.'FF8000';
					if( isset( $_GET['courbe7'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] ) )  )
						$txt = $txt.',';
					if( isset( $_GET['courbe7'] ) )
						$txt = $txt.'00FF00';
					if( isset( $_GET['courbe8'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] )or isset( $_GET['courbe7'] ) )  )
						$txt = $txt.',';
					if( isset( $_GET['courbe8'] ) )
						$txt = $txt.'669900';
					if( isset( $_GET['courbe9'] ) and( isset( $_GET['courbe1'] ) or isset( $_GET['courbe2'] ) or isset( $_GET['courbe3'] ) or isset( $_GET['courbe4'] ) or isset( $_GET['courbe5'] ) or isset( $_GET['courbe6'] )or isset( $_GET['courbe7'] )or isset( $_GET['courbe8'] ) ))
						$txt = $txt.',';
					if( isset( $_GET['courbe9'] ) )
						$txt = $txt.'996600';		
						
					if( isset( $_GET['thumb'] ) )
						$txt = $txt.'&chdlp=b&chma=5,0,0,5"/>'; // Def des marges.
					else
						$txt = $txt.'&chdlp=b&chma=20,20,20,30|80,20"/>'; // Def des marges.
					
				$test = $test.'&chs=700x250&chm=N*c1*,000000,0,-1,11&chbh=20,2,0'. // Def de la taille de l'image/graphique
					'&chdl=Total+Appels|Appels+D�croches|Appels+Theoriques&chco=FFFF00,FF0000,33FF00'. // Def de la l�gende
					'&chdlp=b&chma=20,20,20,30|80,20"';
				
				
				//echo $test;
				echo $txt;
				
				if( !isset( $_GET['thumb'] ) )
				{
					echo '<br/><a href="#" onclick="retailler();">Plus d\'options</a><br/><br/>';
					
					echo '<div class="cadre"><strong>Changer les courbes affich�es</strong>';
					
					///////////////////////////////////////////////
					// Formulaire de changement de type de courbe
					////////////////////////////////////////////////
					echo '<br/><form id="form" name="form" method="get" action="drawgraph.php" >'; 
					echo '<table>';
					echo '<tr>';
						echo '<th></th>';
						echo '<th>Nom de la courbe</th>';
						echo '<th>Afficher Valeurs</th>';
					echo '</tr>';
					
					$i=1;
					while( $i < $result->count() )
					{
						$courbe = 'courbe'.$i;
						$valeur = 'valeur'.$i;
						
						echo '<tr>';
							echo '<td><INPUT type="checkbox" name="'.$courbe.'" value="1" ';
								if( isset( $_GET["$courbe"] ) )
									echo ' checked="checked" ';
								echo '></td><td>';
								
								if( $i == 3 )
									echo 'Base statistique du nombre d\'appels';
								else if( $i == 1 )
									echo 'Nombre Total d\'appels';
								else if( $i == 2 )
									echo 'Nombre Total d\'Appels d�croch�s';
								else if( $i == 4 )
									echo 'Nombre d\'abandons en dessous de la borne minimale';
								else if( $i == 5 )
									echo 'Nombre d\'abandons entre la borne minimale et maximale';
								else if( $i == 6 )
									echo 'Nombre d\'abandons apr�s la borne maximale';
								else if( $i == 7 )
									echo 'Nombre d\'Appels d�croch�s avant la borne minimale';
								else if( $i == 8 )
									echo ' Nombre d\'Appels d�croch�s entre la borne minimale et maximale ';
								else if( $i == 9 )
									echo 'Nombre d\'Appels d�croch�s apr�s la borne maximale';
								
								echo '</td>';
							
							echo '<td><INPUT type="checkbox" name="'.$valeur.'" value="1" ';
								if( isset( $_GET["$courbe"] ) )
								{
									if( isset( $_GET["$valeur"] ) )
										echo ' checked="checked" ';
								}
								else
								{
									echo ' disabled="disabled" ';
								}
								echo '></td>';
						echo '</tr>';
					
						$i++;
					}					
					echo '</table>';

						// Les champs suivant permettent d'authentifier le graph a afficher.
					echo '<INPUT type="hidden" name="type" value="'.$_GET['type'].'">'.
						'<INPUT type="hidden" name="dg" value="'.$_GET['dg'].'">'.
						'<INPUT type="hidden" name="client" value="'.$_GET['client'].'">'.
						'<INPUT type="hidden" name="date" value="'.$_GET['date'].'">'.
						
						'<br/><INPUT type="submit" name="submit" value="Modifier">'.
						'<form/>';

					echo '</div>';
				}
			}
		}
		else if( $_GET['type'] == "totalequipe" )
		{
			if( isset( $_GET['equipe'] ) )
			{
				$AppelListe = "listeclients".$_GET['equipe'];
				$Liste_Clients = unserialize( $_SESSION["$AppelListe"] );
				
				///////////////////////////////////////////////////////////////////////////////////////////
				// Si c'est la 1ere fois qu'on appelle le graph, alors les clients ne sont pas selectionn�.
				// => On les s�l�ctionne tous.
				$i=0;
				$one_graph_selected = false;
				while( $i < $Liste_Clients->count() )
				{
					$client = "client".$i;
					if( isset( $_GET["$client"] ) )
					{
						$one_graph_selected = true;
						// On arrete la boucle
						$i = $Liste_Clients->count();
					}
					$i++;
				}
				if( $one_graph_selected == false )
				{
					$i=0;
					while( $i < $Liste_Clients->count() )
					{
						$client = "client".$i;
						$_GET["$client"] = 1;
						$i++;
					}
					// Par defaut le calcul se fera sur la base stat.
					$_GET["radio"] = 3;
					// On affiche la valeur de la derniere courbe.
					$offset = $Liste_Clients->count() - 1;
					$valeur = 'valeur'.$offset;
					$_GET["$valeur"] = 1;
				}		
				
				
				
				/////////////////////////////////////////////////////////
				// Affichage des horaires
				
				// Faut dabord connaitre la plage ................. tt client confondu ...
				// Il en a peut-etre qui commencent tres tot et finissent tres tot ... et inversement....
				// Il faut donc cr�� une plage qui commence en meme temps que le plus tot ... et fini en meme temps que le plus tard.
				
				// On fait le tour des clients.
				$i = 0;
				$debut_plage = "12:00";
				$fin_plage = "12:00";
				while( $i < $Liste_Clients->count() )
				{
					// Si cet horaire et plus tot que l'horaire de base, on remplace l'horaire de base.
					if( $Liste_Clients[$i]->graph_total[0][0] < $debut_plage )
					{
						$debut_plage = $Liste_Clients[$i]->graph_total[0][0];
						//echo '$debut_plage : '.$debut_plage."<br/>";
					}
			
					// Si cet horaire et plus tard que l'horaire de base, on remplace l'horaire de base.
					//echo "Count sur Graph Total: ".$Liste_Clients[$i]->graph_total->count();
					
					$offset = $Liste_Clients[$i]->graph_total[0]->count() - 1;
					if( $Liste_Clients[$i]->graph_total[0][$offset] > $fin_plage )
					{
						$fin_plage = $Liste_Clients[$i]->graph_total[0][$offset];
					//	echo '$fin_plage : '.$fin_plage."<br/>";
					}
					
					$i++;
				}

				// Reste plus qu'a creer un tableau contenant la plage maxi.
				$PlageHoraire = new ClassArray();
				// Cr�ation des sous-dimensions qui stockerons en 0 la date, puis 1 et + :  les valeurs concernant le d�croch� total, d�croch� < borne min, etc...
				$i=0;
				while( $i < $Liste_Clients[0]->graph_total->count() )
				{
					$PlageHoraire[$i] = new ClassArray(); // Offset 0 = heure ; les autres offset contiendrons les valeurs additionn�es
					$i++;
				}
				
				// Inscriptions des dates dans l'offset 0.
				$date = new DateTime( $debut_plage );			
				$i = 0;
				while( $date->format( 'H:i' ) <= $fin_plage )
				{
					$PlageHoraire[0][$i] = $date->format( "H:i" );
					$date->modify( "+30 min" );
					$i++;
					//echo $date->format( 'H:i' )."<br/>";
				}
				
 				// Init � 0 des valeurs... de facon a pouvoir additionner les valeurs par la suite.
				// Si pas a 0 on risque de partir d'un autre chiffre que 0 et ainsi fausser les r�sultats affich�s
				$i=1; // depart a 1 car 0 = dates => deja enregistr�e ... il ne faut pas les �ffac�es car elles nous servent de base pour savoir combien de plage nous avons.
				while( $i < $PlageHoraire->count() )
				{
					$j=0;
					while( $j < $PlageHoraire[0]->count() )
					{
						$PlageHoraire[$i][$j] = 0;
						$j++;
					}
					$i++;
				}
			
				/////////////////////////////////////////////////////
				// Appel de l'API google
				$txt = '<img src="http://chart.apis.google.com/chart?';
				$test = 'img src="http://chart.apis.google.com/chart?';
			
			
				if( !isset( $_GET['thumb'] ) )
				{
					///////////////////////////////////////////////////
					////////// Inscription des valeurs DATE
					$i=0;
					$j=0;
					$first = true;
					$txt = $txt."&chl=";
					$test = $test."&chl=";
					// On fait le tour de la plage nouvellement cr��e
					while( $i < $PlageHoraire[0]->count() )
					{
						if( $j == 2 or $first == true )
						{
							$txt = $txt.$PlageHoraire[0][$i];
							$test = $test.$PlageHoraire[0][$i];
							$j=0;
							$first = false;
						}
						
						// Tant qu'il ne s'agit pas de la derniere valeur on ajoute un | de s�paration.
						if( $i < $PlageHoraire[0]->count()-1 )
						{
							$txt = $txt."|";
							$test = $test."|";
						}
						$i++;
						$j++;
					}	
				}

				/////////////////////////////////////////////////////////
				// Incription des valeurs GRAPH
				$txt = $txt.'&cht=lxy&chd=t:';
				$test = $test.'&cht=lxy&chd=t:';
		
				$is_preums = true;
				$NumClient = 0;
				$mem_max_value = 0;
				// On fait le tour des clients
				while( $NumClient < $Liste_Clients->count() )
				{
					// On v�rifie s'il faut afficher ce client
					$client = "client".$NumClient;
					if(  isset( $_GET["$client"] ) )
					{
						// On d�fini quel type de valeur vont afficher les courbes (Appels total recu, Appel total d�cro, etc..)
						// Par d�faut 3 = Base statistique
						$type = 3;
						if( isset( $_GET["radio"] ) )
						{
							$type = $_GET['radio'];
						}	
						
						// Si 1ere valeur alors on affiche pas de s�parateur
						if( $is_preums == false )
						{
							$txt = $txt."|";
							$test = $test."|";						
						}
						// Axe des x ... a -1 : on ne s'occupe pas des x L'emplacement se fera a interval r�gulier.
						// si on sp�cifie les X alors on casse l'emplacement "automatique"
						$txt = $txt."-1|";
						$test = $test."-1|";
					
						
						// On recherche la plage horaire de d�part .... Peut-etre que ce client commence 30min plus tard que celui qui a la plage la plus large...
						// Ainsi il faut mettre sa courbe a 0 tant qu'il n'est pas dans sa 1ere tranche horaire
						$i = 0;
						while( $PlageHoraire[0][$i] < $Liste_Clients[$NumClient]->graph_total[0][0] )
						{
							$txt = $txt.$PlageHoraire[$type][$i].',';   
							$test = $test.$PlageHoraire[$type][$i].','; 
							$i++;
						}

						$j = 0;
						// On fait le tour des valeurs
						while( $i <  $Liste_Clients[$NumClient]->graph_total[$type]->count()  )
						{
							//echo 'Plage Base: '.$PlageHoraire[0][$i].' Plage Client : '.$Liste_Clients[$NumClient]->graph_total[0][$j]."<br/>";
							$PlageHoraire[$type][$i] += $Liste_Clients[$NumClient]->graph_total[$type][$j];
							$txt = $txt.$PlageHoraire[$type][$i];
							$test = $test.$PlageHoraire[$type][$i]; 
							
							if( $PlageHoraire[$type][$i] > $mem_max_value )
								$mem_max_value = $PlageHoraire[$type][$i];
							
							// On ne met pas de s�parateur pour la derniere valeur.
							if( $i < $Liste_Clients[$NumClient]->graph_total[$type]->count()-1 )
							{
								$txt = $txt.",";
								$test = $test.",";
							}
							$i++;
							$j++;
						}
						
						// Si le client a une plage qui ne termine pas en meme temps que la plage maximale ... alors il faut ajouter des valeur nulle a la fin,
						// Sinon son graph est plus petit ... et L'API Google l'�tend jusqu'a arriv� au bout du graph ... ainsi les graph ne se superposent plus...
						while( $i < $PlageHoraire[0]->count() )
						{
							$txt = $txt.','.$PlageHoraire[$type][$i];
							$test = $test.','.$PlageHoraire[$type][$i]; 
							$i++;
						}

					
						$is_preums = false;
					}
					$NumClient++;
				}				
				
				////////////////////////////////////
				// D�finition de l'�chelle du graph.
				$mem_max_value += $mem_max_value/4; // Evite que la valeur Max se trouve coll�e au haut du graphique.
				
				$txt = $txt.'&chds=0,'.$mem_max_value;
				$test = $test.'&chds=0,'.$mem_max_value;
				
				////////////////////////////////////
				// Def couleurs
				$txt = $txt.'&chco=';//FF0000,33FF00,FFB300,FF8000,00FF00,669900,996600';
				$test = $test.'&chco=';//FF0000,33FF00,FFB300,FF8000,00FF00,669900,996600';
			
				$i = 0;
				while( $i < $Liste_Clients->count() )
				{
					$client = 'client'.$i;
					if( isset( $_GET["$client"] ) )
					{
						// $txt = $txt.$Liste_Clients[$i];
						// $test = $test.$Liste_Clients[$i];
						$txt = $txt.$Liste_Clients[$i]->couleur;
						$test = $test.$Liste_Clients[$i]->couleur;
					
						// y a til un autre client de selectionn� apres?
						$j = $i+1;
						$is_there_other = false;
						while( $j < $Liste_Clients->count() )
						{
							$client = 'client'.$j;
							if( isset( $_GET["$client"] ) )
								$is_there_other = true;
							$j++;
						}
						
						// S'il y a quelqu'un apres alors on met un s�parateur
						if( $is_there_other )
						{
							$txt = $txt.",";
							$test = $test.",";
						}
					}
					$i++;
				}


				////////////////////////////////
				// Marges autour du graph et l�gende 
				// 
				if( isset( $_GET['thumb'] ) )
				{
					$txt = $txt.'&chdlp=b&chma=2,0,0,2';
					$test = $test.'&chdlp=b&chma=2,0,0,2';
					
					/////////////////////////////////
					// Affichage des valeurs sur le graph?
					$txt = $txt.'&chm='; 		
					$test = $test.'&chm='; 		
					$i = 0;
					$j = 0;
					$firsttime = true;
					while( $i < $Liste_Clients->count() )
					{
						$valeur = 'valeur'.$i;
						$client = 'client'.$i;
						
						if( isset( $_GET["$client"] ) )
						{
							
							// Affiche les couleurs
							if( $firsttime == true )
								$txt = $txt.'B,';							
							else
								$txt = $txt.'|b,';
				
							$txt = $txt.$Liste_Clients[$i]->couleur;
							$test = $test.$Liste_Clients[$i]->couleur;

							
							$k=$j-1;
							$txt = $txt.','.$j.','.$k.',0';
							$text = $text.','.$j.','.$k.',0';
								
							$firsttime = false;
							$j++;
						}
						$i++;	
					}				
				}
				else
				{
					$txt = $txt.'&chdlp=b&chma=20,20,20,30|80,20';
					$test = $test.'&chdlp=b&chma=20,20,20,30|80,20';
				
					////////////////////
					// Legende
					$txt = $txt.'&chdl='; 
					$test = $test.'&chdl=';

					$i = 0;
					while( $i < $Liste_Clients->count() )
					{
						$client = 'client'.$i;
						if( isset( $_GET["$client"] ) )
						{
							$txt = $txt.$Liste_Clients[$i];
							$test = $test.$Liste_Clients[$i];
							
							// y a til un autre client de selectionn� apres?
							$j = $i+1;
							$is_there_other = false;
							while( $j < $Liste_Clients->count() )
							{
								$client = 'client'.$j;
								if( isset( $_GET["$client"] ) )
									$is_there_other = true;
								$j++;
							}
							
							// S'il y a quelqu'un apres alors on met un s�parateur
							if( $is_there_other )
							{
								$txt = $txt."|";
								$test = $test."|";
							}
						}
						$i++;
					}
				//	echo $test;

					/////////////////////////////////
					// Affichage des valeurs sur le graph?
					$txt = $txt.'&chm='; 		
					$test = $test.'&chm='; 		
					$i = 0;
					$j = 0;
					$firsttime = true;
					while( $i < $Liste_Clients->count() )
					{
						$valeur = 'valeur'.$i;
						$client = 'client'.$i;
						
						if( isset( $_GET["$client"] ) )
						{
							if( $firsttime == false )
							{
								$txt = $txt.'|';
								$test = $test.'|';
							}
								
							// Affiche les valeurs
							if( isset( $_GET["$valeur"] ) )
							{
								$txt = $txt.'N,000000,'.$j.',-1,11';
								$test = $test.'N,000000,'.$j.',-1,11';
							}
							else
							{
								$txt = $txt.'N,000000,-1,-1,11'; 
								$test = $test.'N,000000,-1,-1,11'; 
							}
							
							// Affiche les couleurs

							if( $firsttime == true )
								$txt = $txt.'|B,';							
							else
								$txt = $txt.'|b,';
				
							$txt = $txt.$Liste_Clients[$i]->couleur;
							$test = $test.$Liste_Clients[$i]->couleur;

							
							$k=$j-1;
							$txt = $txt.','.$j.','.$k.',0';
							//$text = $text.','.$j.','.$k.',0';
								
							$firsttime = false;
							$j++;
						}
						$i++;	
					}

				}


				//////////////////////////////////
				// Taille de l'image
				$width = 700;
				$height = 300;
				if( isset( $_GET['thumb'] ) )
				{
					$width = 70;
					$height = 30;
				}
				
				$txt = $txt.'&chs='.$width.'x'.$height;
				$test = $test.'&chs='.$width.'x'.$height;			
				

				////////////////////////////////////
				// Borne de fin
				$txt = $txt.'"/>';
				$test = $test.'"/>';
				
				//echo $test;
				echo $txt;
				
				


				if( !isset( $_GET['thumb'] ) )
				{
					////////////////////////////////////////
					// Options
					echo '<br/><a href="#" onclick="retailler();">Plus d\'options</a><br/><br/>';
					
					echo '<div class="cadre"><strong>Changer les courbes affich�es</strong>';
					
					///////////////////////////////////////////////
					// Formulaire de changement de type de courbe
					////////////////////////////////////////////////
					echo '<br/><form id="form" name="form" method="get" action="drawgraph.php" >'; 
					
					// On fait un tableau pour contenir ... les 2 tableaux
					echo '<table>';
					echo '<tr>';
					echo '<td style="border:white; padding:5px">';
					
					echo '<table>';
					echo '<tr>';
						echo '<th></th>';
						echo '<th>Nom du client</th>';
						echo '<th>Afficher Valeurs</th>';
					echo '</tr>';
					
					$i=0;
					while( $i < $Liste_Clients->count() )
					{
						$client = 'client'.$i;
						$valeur = 'valeur'.$i;
						
						echo '<tr>';
							echo '<td><INPUT type="checkbox" name="'.$client.'" value="1" ';
								if( isset( $_GET["$client"] ) )
									echo ' checked="checked" ';
								echo '></td><td>';
								
								echo $Liste_Clients[$i];
								
								echo '</td>';
							
							echo '<td><INPUT type="checkbox" name="'.$valeur.'" value="1" ';
								if( isset( $_GET["$client"] ) )
								{
									if( isset( $_GET["$valeur"] ) )
										echo ' checked="checked" ';
								}
								else
								{
									echo ' disabled="disabled" ';
								}
								echo '></td>';
						echo '</tr>';
					
						$i++;
					}					
					echo '</table>';
					echo '</td>';

					
					
					//////////////////////////
					// Tableau du type de calcul
					echo '<td style="border:white; padding:5px">';
					echo '<table>';
					echo '<tr>';
						echo '<th></th>';
						echo '<th>Nom de la courbe</th>';
						//echo '<th>Afficher Valeurs</th>';
					echo '</tr>';
					
					$i=1;
					while( $i < $Liste_Clients[0]->graph_total->count() )
					{
						
						echo '<tr>'; //<input type="radio" name="choix" value="mi">mignons
							echo '<td><INPUT type="radio" name="radio" value="'.$i.'" ';
								if( isset( $_GET["radio"] ) )
								{
									if( $_GET['radio'] == $i )
										echo ' checked="checked" ';
								}
								echo '></td><td>';
								
								if( $i == 3 )
									echo 'Base statistique du nombre d\'appels';
								else if( $i == 1 )
									echo 'Nombre Total d\'appels';
								else if( $i == 2 )
									echo 'Nombre Total d\'Appels d�croch�s';
								else if( $i == 4 )
									echo 'Nombre d\'abandons en dessous de la borne minimale';
								else if( $i == 5 )
									echo 'Nombre d\'abandons entre la borne minimale et maximale';
								else if( $i == 6 )
									echo 'Nombre d\'abandons apr�s la borne maximale';
								else if( $i == 7 )
									echo 'Nombre d\'Appels d�croch�s avant la borne minimale';
								else if( $i == 8 )
									echo ' Nombre d\'Appels d�croch�s entre la borne minimale et maximale ';
								else if( $i == 9 )
									echo 'Nombre d\'Appels d�croch�s apr�s la borne maximale';
								
								echo '</td>';
							
						echo '</tr>';
					
						$i++;
					}					
					echo '</table>';
					echo '</td>';
					
					echo '</tr>';
					echo '</table>';
					
					
					
						// Les champs suivant permettent d'authentifier le graph a afficher.
					echo '<INPUT type="hidden" name="type" value="'.$_GET['type'].'">'.
						'<INPUT type="hidden" name="equipe" value="'.$_GET['equipe'].'">'.
						
						
						'<br/><INPUT type="submit" name="submit" value="Modifier">'.
						'<form/>';

					echo '</div>';
				}
			}
		}
		else if( $_GET['type'] == "charge_semaine" )
		{
			if(isset( $_GET['dg'] )) 
			{
				$session = $_GET['dg'];
				$result = unserialize( $_SESSION["$session"] );
				
				// Calcul de la valeur la plus elev�e de facon a calibrer le graphique.
				$i=0;
				$maxvalue = 0;
				while( $i < $result->count() )
				{
					if( $result[$i] > $maxvalue )
						$maxvalue = $result[$i];
					$i++;
				}
				
				$maxvalue += $maxvalue/4; // Evite que la valeur Max se trouve coll�e au haut du graphique.
				$txt = '<img src="http://chart.apis.google.com/chart?&chds=0,'.$maxvalue.'&cht=bvs&chd=t:'; // chds est la barre d'ordonn�e, il faut donc la calibrer en fonction des valeurs a afficher.
				$test = 'img src="http://chart.apis.google.com/chart?&chds=0,'.$maxvalue.'&cht=bvs&chd=t:'; // cht barre horizontale : bvs affiche des batons, lc des courbes.
				$i=0;
				while( $i < $result->count() )
				{
					$txt = $txt.$result[$i];
					$test = $test.$result[$i];
					if( $i < $result->count()-1 )
					{
						$txt = $txt.",";
						$test = $test.",";
					}
					$i++;
				}
				

				if( !isset( $_GET['thumb'] ) )
				{
					$txt = $txt."&chl=";
					$test = $test."&chl=";
					// Valeurs horizontales
					$txt = $txt."Lundi|Mardi|Mercredi|Jeudi|Vendredi";//|Samedi|Dimanche";
					$test = $test."Lundi|Mardi|Mercredi|Jeudi|Vendredi";//|Samedi|Dimanche";
				}
				
				$height = 200;
				$width = 700;
				if( isset( $_GET['thumb'] ) )
				{
					$height = 20;
					$width = 70;	
				}
				$txt = $txt.'&chbh=a&chs='.$width.'x'.$height;
				$test = $test.'&chbh=a&chs='.$width.'x'.$height; //'&chm=N*c1*,000000,0,-1,11"'; //&chbh=20,2,0
				if( !isset( $_GET['thumb'] ) )
					$txt = $txt.'&chm=N*c1*,000000,0,-1,11';
				$txt = $txt.'"/>';
				
				//echo "<strong>".$_GET['client']."</strong> en date du <strong>".$_GET['date']."</strong><br/>";
				
				//echo $test;
				echo $txt;
			}
		}
		else if( $_GET['type'] == "camembert" )
		{
			if(isset( $_GET['dg'] )) 
			{
				$session = $_GET['dg'];
				$result = unserialize( $_SESSION["$session"] );
				
				/////////// Il faut pr�alablement �tablir des pourcentages
				// Calcul de la somme.
				$i=0;
				$som = 0;
				while( $i < $result->count() )
				{
					$som += $result[$i][2];
					$i++;
				}
				
				//Calcul pourcentage / client
				$tab_pourcent = new ClassArray();
				$i=0;
				if( $som != 0 )
					while( $i < $result->count() )
					{
						$tab_pourcent[$i] = $result[$i][2]*100/$som;
						$i++;
					}
				
				/////// Formatage de la ligne de commande du graphique.
				$text = '<img src="http://chart.apis.google.com/chart?cht=p3&chd=t:';
				$test = 'http://chart.apis.google.com/chart?cht=p3&chd=t:';
				// R�cup des Pourcentages
				$i=0;
				while( $i < $result->count() )
				{
					$text = $text.$tab_pourcent[$i];
					$test = $test.$tab_pourcent[$i];
					
					if( $i < $result->count() -1 )
					{
						$text =  $text.",";
						$test =  $test.",";
					}
					$i++;
				}
				
				////////////////////////////////////
				// Def couleurs
			
				$text = $text.'&chco=';//FF0000,33FF00,FFB300,FF8000,00FF00,669900,996600';
				$test = $test.'&chco=';//FF0000,33FF00,FFB300,FF8000,00FF00,669900,996600';
 				$i = 0;
				while( $i < $result->count() )
				{
					// $client = 'client'.$i;
					// if( isset( $_GET["$client"] ) )
					// {
						// $txt = $txt.$Liste_Clients[$i];
						// $test = $test.$Liste_Clients[$i];
						$text = $text.$result[$i][0]->couleur;
						$test = $test.$result[$i][0]->couleur;
					
						// // y a til un autre client de selectionn� apres?
						// $j = $i+1;
						// $is_there_other = false;
						// while( $j < $result->count() )
						// {
							// $client = 'client'.$j;
							// if( isset( $_GET["$client"] ) )
								// $is_there_other = true;
							// $j++;
						// } 
						
						// S'il y a quelqu'un apres alors on met un s�parateur
						if(  $i < $result->count()-1 )// $is_there_other  )
						{
							$text = $text.",";
							$test = $test.",";
						}
					//}
					$i++;
				} 
				
				
				$width = 700;
				$height = 200;
				if( isset( $_GET['thumb'] ) )
				{
					$width = 50;
					$height = 50;
				}
				$text = $text.'&chs='.$width.'x'.$height; //Moi|Vous"/>';
				$test = $test.'&chs='.$width.'x'.$height.'&chl='; //Moi|Vous"/>';

				if( !isset( $_GET['thumb'] ) )
				{
					$text = $text.'&chl=';
					$i =0;
					while( $i < $result->count() )
					{
						//echo $result[$i][0]->couleur;
						$text = $text.$result[$i][0]." (".$result[$i][2]." appels, ".number_format($tab_pourcent[$i], 2, ',', ' ')."%)"; 
						$test = $test.$result[$i][0]." (".$result[$i][2]." appels, ".number_format($tab_pourcent[$i], 2, ',', ' ')."%)"; 
						
						if( $i < $result->count() -1 )
						{
							$text =  $text."|";
							$test =  $test."|";
						}
						
						$i++;
					}
					
				}
				else
				{
					$text = $text.'&chma=0,0,0,0|0,0';					
				}

				$text =  $text.'"/>';
				//$test =  $test.'"/>';

				
				echo $text;
				//echo $test.'<br/>';
				
				//echo $result;
			}
		}
		else
		{
			echo "Erreur d'appel de cette page, aucun graphique ne peut etre affich� : Probleme de type.";
		}
	}
	else
	{
		echo "Erreur d'appel de cette page, aucun graphique ne peut etre affich� : type non sp�cifi�.";
	}
	
?>





</body>
</html>