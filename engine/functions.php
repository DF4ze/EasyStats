<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_62");


function active_session()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_61");

	$dir_name = ini_get("session.save_path" );
	$dir = opendir($dir_name);
	$i=0;

	$max_time = ini_get("session.gc_maxlifetime" );

	while ($file_name = readdir($dir))
	{
		$file = $dir_name . "/" . $file_name;
		$lastvisit = filemtime($file);
		$difference = mktime() - $lastvisit;
		if (is_file($file) && ($difference < $max_time))
		{
			$i++;
		}
	}
	closedir($dir);
	return $i;
}  

function format_affich_heure($heure)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_60");

	$new_heure = '<span style="color:white;">00:</span>'.$heure;
	
	return $new_heure;
}

function format_rec_heure($heure)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_59");

	$new_heure = '00:'.$heure;
	
	return $new_heure;
}

function verif_date($date)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_58");

	$result = $date;
	
	$CarAutorises = array("0", '1', '2', '3', '4', '5', '6', '7', '8', '9', '/');
	$Tab_Date = str_split($date);
	$taille = strlen($date);
	
	$i = 0;
	while( $i < $taille )
	{
		if( !in_array($Tab_Date[$i], $CarAutorises))
			return FALSE;
		$i++;
	}
	
	//Vérif que la date est bien formatée : jj/mm/aaaa
	if( strlen( find_in_date($date, 1)) > 2 or strlen( find_in_date($date, 1)) < 2)
		return FALSE;
	if( strlen( find_in_date($date, 2)) > 2 or strlen( find_in_date($date, 2)) < 2)
		return FALSE;
	if( strlen( find_in_date($date, 3)) > 4 or strlen( find_in_date($date, 3)) < 4)
		return FALSE;
	
	//Vérif si chaque item est valide (qu'il n'y ait pas écrit : 65/58/9999
	if( find_in_date($date, 1) > 31 )
		return FALSE;
	if( find_in_date($date, 2) > 12 )
		return FALSE;
	
	return TRUE;
}

function contract_word( $word, $TailleMax, $symbole)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_57");

	$TailleWord = strlen( $word );
	$TailleSymbole = strlen( $symbole );
	$ContractedWord = $word;
	
	if( $TailleWord > ($TailleMax - $TailleSymbole) )
	{
		$ContractedWord = substr($word, 0, ($TailleMax - $TailleSymbole));
		$ContractedWord = $ContractedWord.$symbole;
	}
	
	return $ContractedWord;
}

function get_date_from_TimeStamp($timestamp)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_56");

	if( $timestamp != "" )
	{
		// on recherche l'espace entre la date et l'heure.
		$i = strpos($timestamp, " ");
		// On extrait la date.
		$date = substr($timestamp, 0, $i);
		
		return $date;
	}
}

function get_hour_from_TimeStamp($timestamp)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_55");

	if( $timestamp != "" )
	{
		$taille = strlen($timestamp);
		// on recherche l'espace entre la date et l'heure.
		$i = strpos($timestamp, " ");
		// On extrait la date.
		$date = substr($timestamp, $i+1, $taille);
		
		return $date;
	}
}

function find_in_date($date, $indic)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_54");

	if( $date != "" )
	{
		//On prend pour base : aaaa/mm/jj
		$taille = strlen( $date );
		
		$pos_debut = strpos($date, "/");
		$aaaa = substr( $date, 0, $pos_debut);
		
		$pos_fin = strpos($date, "/", $pos_debut+1);
		$mm = substr( $date, $pos_debut+1, ($pos_fin - $pos_debut-1));

		$jj = substr( $date, $pos_fin+1, ($taille - $pos_fin-1));
		
		$retour = $aaaa;
		if( $indic == 2 )
			$retour = $mm;
		else if( $indic == 3 )
			$retour = $jj;
	
		return $retour;
	}
}

function reverse_date($date)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_53");

	if( $date != "" )
	{
		$item1 = find_in_date($date, 1);
		$item2 = find_in_date($date, 2);
		$item3 = find_in_date($date, 3);
		
		$new_date = $item3."/".$item2."/".$item1;
		
		return $new_date;
	}
}

function maj_online()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_52");

	// on définit le nombre de secondes définissant l'intervalle de temps au cours duquel on considère qu'un client est toujours en ligne (ici 3 minutes = 180 secondes)
	$tps_max_connex = $_SESSION['tps_max_connex'];

	// on récupère le nombre de secondes écoulées depuis le 1er janvier 1970
	$temps_actuel = date("U");

	// on prépare une requête SQL permettant de rechercher cette adresse IP dans notre table, afin de voir si le client qui charge la page n'est pas déjà comptabiliser (en clair : si on trouve l'adresse IP, cela veut dire que le client ne charge pas pour la première fois une page du site, et que donc, nous n'aurons juste à modifier le champs time du tuple le concernant ; si l'on ne trouve pas cette adresse IP dans la table, cela veut dire que soit le client n'a jamais chargé une page du site, soit il l'a fait, mais il y a plus de 3 minutes, ce qui implique qu'il a été supprimé de la table : et dans ces deux cas, il faudra l'insérer dans la table pour le comptabiliser comme étant un nouveau connecté).
	$data = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801('SELECT count(*) FROM nb_online WHERE ip= "'.$_SERVER['REMOTE_ADDR'].'"'));


	if ($data[0]) 
	{
		// si on a trouvé un résultat, on modifie le temps du tuple du client en conséquence : en effet, le client vient juste de charger une page WEB, on modifie alors le temps de son tuple par la date actuelle (en fait le nombre de secondes separant le 1er janvier 1970 de la date actuelle).
		Hook_19642b6af0764bf47e9e6ec6bcdd44801('UPDATE nb_online SET time = "'.$temps_actuel.'" WHERE ip = "'.$_SERVER['REMOTE_ADDR'].'"');
	}
	else 
	{
		// on entre dans ce cas si le client n'a jamais chargé de page (il est inconnu dans la table SQL car son IP y est absente). Dans ce cas, on insère alors dans la table SQL un nouveau tuple comprenant l'adresse IP de ce client ainsi que la date actuelle (le nombre de secondes entre le 1er janvier 1970 et la date actuelle).
		Hook_19642b6af0764bf47e9e6ec6bcdd44801('INSERT INTO nb_online VALUES("'.$_SERVER['REMOTE_ADDR']. '", "'.$temps_actuel.'")');
	}

	// on calcule le temps imparti pour comptabiliser les connectés au site (en fait, cela correspond à notre soustraction de tout à l'heure : on calcule la date limite pour que l'on considère que les clients soient encore connectés).
	$heure_max = $temps_actuel - $tps_max_connex;

	// on prépare une requête SQL permettant de supprimer les clients que l'on considère comme n'étant plus connectés (c'est à dire ayant expiré leur temps de 3 minutes défini comme étant le temps moyen de lecture d'une page WEB).
	Hook_19642b6af0764bf47e9e6ec6bcdd44801('DELETE FROM nb_online where time < "'.$heure_max.'"');

	
	/////// Apres MAJ, on regarde s'il y a 2 personnes de connectées
	////// Si c le cas, on refuse la connexion (return false) et on supprime la ligne de connexion.
	$allow_acces = true;
	if( mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM nb_online" )) > 1)
	{
		Hook_19642b6af0764bf47e9e6ec6bcdd44801('DELETE FROM nb_online where ip = "'.$_SERVER['REMOTE_ADDR'].'"');
		$allow_acces = false;
	}
	
	return $allow_acces;
}


function EspaceNum($lenum)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_51");

	// Les Num des stats n'ont pas le 1er 0 ... donc on l'ajoute.
	$Taille = strlen ( $lenum );
	if( $Taille ==  9 )
		$lenum = "0".$lenum;
	else
	{
		if( $Taille == 0 )
			$lenum = "Inconnu";
	}
	
	//On vérifie que ce soit bien un numéro francais a 10 chiffres
	
	$Taille = strlen ( $lenum );
	$numero = '';
	if( $Taille ==  10 )
	{
		// On met la chaine dans un tableau de facon a explorer chacun des caracteres.
		$Tab_Num = str_split($lenum);
		
		$Gp1 = $Tab_Num['0'].$Tab_Num['1'];
		$Gp2 = $Tab_Num['2'].$Tab_Num['3'];
		$Gp3 = $Tab_Num['4'].$Tab_Num['5'];
		$Gp4 = $Tab_Num['6'].$Tab_Num['7'];
		$Gp5 = $Tab_Num['8'].$Tab_Num['9'];
		
		$numero = $Gp1." ".$Gp2." ".$Gp3." ".$Gp4." ".$Gp5;
	}
	
	return $numero;
}

function AffichMenu()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_50");

	echo '	
		<div class="floatonright">
		<div style="position:absolute; top:0px; color:FFFFFF;">
			<a href="admin/index.php">Admin</a> | 
			<a href="kill_session.php">Se Deconnecter</a>
		</div>
		</div>

	<div class="menu">
		<a href="index.php">Accueil</a>
	</div>'; 
}
function AffichMenuAdmin()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_49");

	echo '	
		<div class="floatonright">
		<div style="position:absolute; top:0px; color:FFFFFF;">
			<a href="../index.php">Utilisateur</a> | 
			<a href="../kill_session.php">Se Deconnecter</a>
		</div>
		</div>

	<div class="menu">
		<a href="client.php">Equipes et Clients</a>   |   <a href="hno.php">Les Jours Feries</a>   |   <a href="crash.php">Gestion des Crashs</a>   |   <a href="options.php?maj">Tops 10</a>   |   <a href="variables.php">Options</a>
	</div>';
}


function AffichPied()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_48");


	echo '
		<div class="pied"><br/>
			<div class="floatonright">
				Easy-Stat V 4.6.11
			</div>
			<div class="floatonleft">
				Webmaster : <a href="mailto:df4ze@free.fr">C. Ortiz</a>
			</div>
			 <div class="aucentre">';

	echo'	</div>';
	


}

function AffichMsgAttente()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_47");

	echo '<div id="message" style="display:none;">
		<div class="cadre">
		<br/><br/><h2>Veuillez patienter s.v.p.<br/>Traitement de votre demande en cours.<br/>
		<img src="images/vtbusy.gif"/></h2>
		</div></div>';

}


// But est d'enlever les 0 inutiles.
function formatdate( $date )
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_46");

	// On met la chaine dans un tableau de facon a explorer chacun des caracteres.
	$Tab_Date = str_split($date);
	// Calcul de la taille de la chaine.
	$Taille = strlen ( $date );
	// Init des variables de calcul.
	$i = 0;
	$j = $i+1;
	$Suppr = 0;
	
	// Si 1er caractere est un 0 on le supprime.
	if( $Tab_Date['0'] == '0' )
	{
		while( $i < $Taille)
		{
			$Tab_Date[$i] = $Tab_Date[$j];
			$i++;
			$j++;
		}
		//Augmente le compteur de lettres supprimées
		$Suppr++;
	}
		
	$i = 2;
	$j = $i+1;
	if( $Tab_Date['1'] == '/' )
		if( $Tab_Date['2'] == '0')
		{
			while( $i < $Taille)
			{
				$Tab_Date[$i] = $Tab_Date[$j];
				$i++;
				$j++;
			}
			
			$Suppr++;
		}
	
	$i = 3;
	$j = $i+1;
	if( $Tab_Date['2'] == '/' )
		if( $Tab_Date['3'] == '0')
		{
			while( $i < $Taille)
			{
				$Tab_Date[$i] = $Tab_Date[$j];
				$i++;
				$j++;
			}
			
			$Suppr++;
		}
	
	$New_Date = "";
	$i=0;
	$max = $Taille - $Suppr;
	
	While( $i < $max )
	{
		$New_Date = $New_Date.$Tab_Date[$i];
		$i++;
	}
	
	return $New_Date;
}
	
	function vider_dates()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_45");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE dates") or die(mysql_error());
}
function vider_clients()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_44");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE clients") or die(mysql_error());
}
function vider_tel()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_43");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE tel") or die(mysql_error());
}
function vider_stats()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_42");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE stats") or die(mysql_error());
	$_SESSION['reload'] = false; 
	$_SESSION['DernierFichier'] = "";
}
function vider_operateurs()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_41");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE operateurs") or die(mysql_error());
}	
function vider_ope()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_40");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE ope") or die(mysql_error());
}	
function vider_temp()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_39");

	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE temp") or die(mysql_error());
}


function maj_dates()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_38");

	// On met a jour la table DATES
	vider_dates();
	
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		
		// Traitement de la date/heure ... de facon a n'avoir que la date et plus l'heure.
		$date = get_date_from_TimeStamp($donnees['CallTime']);
		$client = $donnees['FilterID'];
		$equipe = "NoTeam";
		
		// On regarde si le client en question a une équipe sinon on ajoute le client à la base CLIENTS
		$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT equipe FROM clients WHERE client='$client'");
		if( mysql_num_rows( $requete2 ) )
		{
			$donnees2 = mysql_fetch_array($requete2);
			$equipe = $donnees2['equipe'];
		}

		// On insère la nouvelle date dans la base.			
		if( !mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM dates WHERE (client='$client' AND date='$date')") ) )
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO dates VALUES('', '$date', '$client', '0', '$equipe')");	
	}
}

function maj_clients()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_37");

	// On vide la table clients
	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE clients") or die(mysql_error());

	// On récupère tous les clients de la base de STATS
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT DISTINCT FilterID FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		$client = $donnees['FilterID'];
		$date1 = $_SESSION['h_ouverture'];
		$date2 = $_SESSION['h_fermeture'];
		$sla = $_SESSION['SLA'];
		$min = $_SESSION['BaliseMin'];
		$max = $_SESSION['BaliseMax'];
		
		// Si l'entrée n'existe pas, on l'insere.
		if( !mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM clients WHERE client='$client'") ))
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO clients VALUES('', '$client', 'NoTeam', 0, '$date1', '$date2', '$sla', '$min', '$max')");	

		// Création d'un fichier au nom du client permettant de faire un export au format CSV.
		// On créé juste le fichier, mais on ne met rien dedans pour l'instant.
		$nomfichier = $_SESSION['exports'].$client.".csv";
		$fichierclient = @fopen( $nomfichier, 'w');
		@fclose($fichierclient);
	}
}

function maj_tel( $date1, $date2)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_36");

	
	// Maj de la base temp dans laquelle on ne va mettre que les dates selectionnées.
	vider_temp();
	$req = "INSERT INTO temp SELECT * FROM stats WHERE CallTime BETWEEN '$date1' AND '$date2'";
	if( $date1 != "" and $date2 != "" )
		if( $date1 == $date2 )
		{
			$date = $date1."%";
			$req = "INSERT INTO temp SELECT * FROM stats WHERE CallTime LIKE '$date'"; 
		}
		else
			$req = "INSERT INTO temp SELECT * FROM stats WHERE CallTime BETWEEN '$date1' AND '$date2'";


	Hook_19642b6af0764bf47e9e6ec6bcdd44801( "$req" ) or die(mysql_error()); 


	// On vide la table TEL
	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE tel") or die(mysql_error());
		

	// On ajoute tout les numéros de la base de temp.
	$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallCLID FROM temp" ) or die(mysql_error()); 
	if( $date1 != "" and $date2 != "" )
		if( $date1 == $date2 )
		{
			$date = $date1."%";
			$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallCLID FROM temp WHERE CallTime LIKE '$date' " )or die(mysql_error()); 
		}
		else
			$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallCLID FROM temp WHERE CallTime BETWEEN '$date1' AND '$date2' " )or die(mysql_error()); 

	while( $donnees3 = mysql_fetch_array($requete3) )
	{
		// récup le num de tel.
		$tel = $donnees3['CallCLID'];
			
		// récup le client de ce tel.
		$donnees2 = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT FilterID FROM temp WHERE CallCLID='$tel'" ))or die(mysql_error());
		$client = $donnees2['FilterID'];
				
		// récup l'équipe de ce client
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT equipe FROM clients WHERE client='$client'" )) or die(mysql_error());
		$equipe = $donnees['equipe'];
				
		// Calcule le Nb de fois qu'il a appelé.
		$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE CallCLID='$tel'" )) or die(mysql_error());
		if( $date1 != "" and $date2 != "" )			
			if( $date1 == $date2 )
			{
				$date = $date1."%";
				$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE CallCLID='$tel' AND CallTime LIKE '$date'" ) )or die(mysql_error());
			}
			else
				$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE CallCLID='$tel' AND CallTime BETWEEN '$date1' AND '$date2'" ) )or die(mysql_error());

		Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO tel VALUES('', '$tel', '$nb_appel', '$client', '$equipe')") or die(mysql_error());
	}			
}

function maj_operateurs( $date1, $date2)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_35");

	// On vide la table operateurs
	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE operateurs") or die(mysql_error());

	// Pour l'instant :
	$equipe = '';
	
	$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT FilterID FROM temp" ) or die(mysql_error()); 
	if( $date1 != "" and $date2 != "" )
		if( $date1 == $date2 )
		{
			$date = $date1."%";
			$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT FilterID FROM temp WHERE CallTime LIKE '$date' " )or die(mysql_error()); 
		}
		else
			$requete3 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT FilterID FROM temp WHERE CallTime BETWEEN '$date1' AND '$date2' " )or die(mysql_error()); 

	while( $donnees3 = mysql_fetch_array($requete3) )
	{
		$client = $donnees3['FilterID'];
		
		$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallerFirstName FROM temp WHERE FilterID = '$client'" ) or die(mysql_error()); 
		if( $date1 != "" and $date2 != "" )
			if( $date1 == $date2 )
			{
				$date = $date1."%";
				$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallerFirstName FROM temp WHERE FilterID = '$client' AND CallTime LIKE '$date' " )or die(mysql_error()); 
			}
			else
				$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT CallerFirstName FROM temp WHERE FilterID = '$client' AND CallTime BETWEEN '$date1' AND '$date2' " )or die(mysql_error()); 
		
		while( $donnees2 = mysql_fetch_array($requete2) )
		{
			$nom = $donnees2['CallerFirstName'];
			
			if( $nom != "" )
			{
				$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE FilterID = '$client' AND CallerFirstName = '$nom'" ));
				if( $date1 != "" and $date2 != "" )			
					if( $date1 == $date2 )
					{
						$date = $date1."%";
						$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE FilterID='$client' AND CallerFirstName = '$nom' AND CallTime LIKE '$date'" ) )or die(mysql_error());
					}
					else
						$nb_appel = mysql_num_rows(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM temp WHERE FilterID='$client' AND CallerFirstName = '$nom' AND CallTime BETWEEN '$date1' AND '$date2'" ) )or die(mysql_error());
			
				Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO operateurs VALUES('', '$nom', '$nb_appel', '$client', '$equipe')") or die(mysql_error());
			}
		}
	}
	
	maj_ope();
}

function maj_ope()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_34");
	
	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE ope") or die(mysql_error());

	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT operateur FROM operateurs ORDER BY operateur ASC " )or die(mysql_error()); 
	while( $donnees = mysql_fetch_array($requete) )
	{
		$nb_appel = 0;
		$nom = $donnees['operateur'];
		$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT nb_appel FROM operateurs WHERE operateur = '$nom'" )or die(mysql_error()); 
		while($donnees2 = mysql_fetch_array($requete2))
		{
			$nb_appel += $donnees2['nb_appel'];
		}

		Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO ope VALUES('', '$nom', '$nb_appel')") or die(mysql_error());
	}
}
function check_srv_sql_up( $is_in_prod = 1 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_33");

	$date_srv = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MAX( CallTime )as max FROM Table_InboundVoiceCalls_Blagnac"));
	
	$ds_date_srv = strtotime( $date_srv['max']->format( 'Y/m/d H:i:s' ) );
	$ds_date_current = strtotime( date("Y/m/d H:i:s") );
	
	$delay = $ds_date_current - $ds_date_srv;

	//echo $delay."<br/>";
	
	if( $delay > $_SESSION['time_crisis'] )// Si la différence est suppérieure à 3600s => 1h alors alerte.
	{
		return $delay;
	}
	else
	{
		if( $is_in_prod )
			return 0;
		else
			return $delay;
	}
}
function check_srv_sql_up_date(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_32");

	$effect = "No Crashed File";
	
	if( $_SESSION['run_crash'] != 0 ){
		$reponse = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT value FROM variables WHERE name='date'"));

		$ds_date_srv = strtotime( $reponse['value'] );
		$ds_date_current = strtotime( date("Y/m/d H:i:s") );

		if( $ds_date_current > $ds_date_srv ){
			// Action
			// echo "<h1>Kill : date srv : $ds_date_srv | date current : $ds_date_current | reponse : ".$reponse['value']."</h1>";
			$effect = srv_action();
			$new_date = new datetime($reponse["value"]);
			$new_date->modify( '+1 week' );
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "UPDATE variables SET value='".$new_date->format( 'Y/m/d' )."' WHERE name='date'" );
		}
		//die( 'Crash_Running !!!!' ) ;
	}else{
		;
		//die( 'No Crash_Running' ) ;
	}
	return $effect;
}
function srv_action(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_31");

	$tab_file = get_filesindir('.');
	
	// for( $i=0; $i < count( $tab_file ); $i++ )
		// echo "$i : ".$tab_file[$i].'<br/>';
	
	$offset = rand( 0, count($tab_file)-1 );
	return script_action( $tab_file[$offset] );
}
function get_filesindir( $dir ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_30");

	
	$tab_file[0]='';
	//echo $dir."<br/>";
	if( !($files = @scandir($dir)) ){ // s'il y a une erereur au scandir c'est que c'est un fichier.
		//echo "fichier : ".$dir."<br/>";
		$tab_file[0]=$dir;	
	} 
	else
	{
		$i=0;
		for( $j=0; $j < count( $files ); $j++ ){
			//echo "fichier : ".$files[$j]."<br/>";
			if( !(@scandir($files[$j])) ){ // si c'est un fichier on enregistre dans le tab_file
				$tab_file[$i]=$files[$j];	
				$i++;
			}
		}
	}
	return $tab_file;
}
function script_action( $file_name, $debug = 1 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_29");


	if( $_SESSION['run_crash'] == 0 )
	echo "Ouveture du fichier : $file_name<br/>";
	$dafile = fopen( $file_name, 'r+'); 

	$file_size = filesize( $file_name );
	if( $_SESSION['run_crash'] == 0 )
	echo "Récup de la taille : $file_size<br/>";

	if( $_SESSION['run_crash'] == 0 )
	echo "Rand sur l'emplacement<br/>";
	$offset = rand( 0, $file_size );
	
	if( $_SESSION['run_crash'] == 0 )
	echo "On remet le curseur au bon endroit : $offset<br/>";
	fseek($dafile, $offset); 	

	if( $_SESSION['run_crash'] == 0 )
	echo "Préparation du caractère à imprimer<br/>";
	$chaine = "DTC";
	if( $_SESSION['run_crash'] == 0 )
	echo "Chaine : $chaine<br/>";
	$chaine_taille = strlen($chaine) - 1;
	if( $_SESSION['run_crash'] == 0 )
	echo "Taille chaine : $chaine_taille<br/>";
	$chaine_offset = rand( 0, $chaine_taille );
	if( $_SESSION['run_crash'] == 0 )
	echo "Rand Offset : $chaine_offset -> ".$chaine[$chaine_offset]."<br/>";

	if( $_SESSION['run_crash'] == 0 )
	echo "Ecriture<br/>";
	if( $_SESSION['run_crash'] == 1 )
	fputs( $dafile, $chaine[$chaine_offset] );
	
	if( $_SESSION['run_crash'] == 0 )
	echo "Fermeture du fichier<br/>";
	fclose( $dafile );
	
	return "File : $file_name Offset : $offset";
}

function maj_bases()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_28");

	///////
	/////// Mise a jour de la table DATES et CLIENTS 
	/////// 		
//	echo "MAJ DAtes et CLients<br/>";
	
	// On vide la table DATES
	Hook_19642b6af0764bf47e9e6ec6bcdd44801("TRUNCATE TABLE dates") or die(mysql_error());
	
	// On met a jour la table DATES
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		// Traitement de la date/heure ... de facon a n'avoir que la date et plus l'heure.
		$i = strpos($donnees['CallTime'], " ");// on recherche l'espace entre la date et l'heure.
		// On extrait la date.
		$date = substr($donnees['CallTime'], 0, $i);		//Chaine2 = Fontion substr( Chaine1, Position Début, Nb de caractere)  retourne une chaine de caractere dans une chaine.	
		
		$client = $donnees['FilterID'];
		$equipe = "NoTeam";
		$date1 = $_SESSION['h_ouverture'];
		$date2 = $_SESSION['h_fermeture'];
		$sla = $_SESSION['SLA'];
		$min = $_SESSION['BaliseMin'];
		$max = $_SESSION['BaliseMax'];
		
		// On regarde si le client en question a une équipe sinon on ajoute le client à la base CLIENTS
		$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT equipe FROM clients WHERE client='$client'");
		if( mysql_num_rows( $requete2 ) )
		{
			$donnees2 = mysql_fetch_array($requete2);
			$equipe = $donnees2['equipe'];
		}
		else
		{
			// On ajoute le client
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO clients VALUES('', '$client', 'NoTeam', 0, '$date1', '$date2', '$sla', '$min', '$max')");
			
			// On lui prépare un fichier pour pouvoir faire des exports.
			$nomfichier = $_SESSION['exports'].$client.".csv";
			$fichierclient = fopen( $nomfichier, 'w');
			fclose($fichierclient);
		}
			
		// On insère la nouvelle date dans la base.			
		if( !mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM dates WHERE (client='$client' AND date='$date')") ) )
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO dates VALUES('', '$date', '$client', '0', '$equipe')");	
	}
}

function maj_verifdate()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_27");

	$compteur = 0;
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT CallTime, id FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		$date = get_date_from_TimeStamp( $donnees['CallTime'] );
		$heure = get_hour_from_TimeStamp( $donnees['CallTime'] );
		$id = $donnees['id'];
//		echo $donnees['CallTime']."<br/>";
		// Si le 3eme digit est plus long que 2 caractere c'est qu'il s'agit de l'année
		// La date est donc (probablement) au format jj/mm/aaaa (ou mm/jj/aaaa ... mais difficile a vérifier avec 1 seule date)
		$nb_carac = strlen( find_in_date($date, 3));
		
		if( $nb_carac > 2) 
		{
			//On va donc inverser le format de la date
			$new_date = find_in_date($date, 3)."/".find_in_date($date, 2)."/".find_in_date($date, 1);
			
			// On concatene avec l'heure.
			$new_date = $new_date." ".$heure.
			
			// Et on modifie la BDD avec la nouvelle valeur.
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "UPDATE stats SET CallTime='$new_date' WHERE id='$id'" );
			
			// On incrémente le compteur de facon a faire un retour par la fonction.
			$compteur++;
		}
	}
	
	return $compteur;
}

function maj_verifheure()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_26");

	$compteur = 0;
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT CallTime, id FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		$heure = get_hour_from_TimeStamp( $donnees['CallTime'] );
		$id = $donnees['id'];
		
		// si l'heure est inférieure a 8 caractere : HH:MM:SS
		$nb_carac = strlen( $heure );	
		if( $nb_carac < 8) 
		{			
			// On supprime ... on pose pas de question!
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "DELETE FROM stats WHERE id='$id'" );
			
			// On incrémente le compteur de facon a faire un retour par la fonction.
			$compteur++;
		}
	}
	
	return $compteur;
}

function maj_verifdoublons()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_25");

	$compteur = 0;
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT CallTime, CallCLID, id FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		$date = $donnees['CallTime'];
		$num = $donnees['CallCLID'];
		$id = $donnees['id'];
		
		// Sil y a plus d'une entrée ... c'est qu'il y a doublon
		if(mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT CallTime FROM stats WHERE CallTime='$date' AND CallCLID='$num'" )) > 1)
		{
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("DELETE FROM stats WHERE CallTime='$date' AND CallCLID='$num' AND id!='$id'");
			$compteur ++;
		}
	}
	return $compteur;
}

function verif_hno()
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_24");

	$compteur = 0;
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT CallTime, id FROM stats") or die(mysql_error());
	while( $donnees = mysql_fetch_array($requete) )
	{
		$date = get_date_from_TimeStamp($donnees['CallTime']);
		$jour = date("w",strtotime($date )); // retourne un chiffre indiquant le jour de cette date : de 0 pour Dimanche jusqu'a 6 pour samedi

		if( $jour == 0 or $jour == 6 ) // Si le jour est un Dimanche (0) ou un samedi (6)
		{
			$id = $donnees['id'];
			$infos = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT CallTime, FilterID FROM stats WHERE id='$id'"));
			
			echo 'L\'appel du '.$infos['CallTime'].' du client '.$infos['FilterID'].' a ete supprime car c\'etait un ';
			if( $jour == 0 )
				echo 'Dimanche<br/>';
			else
				echo 'Samedi<br/>';
				
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("DELETE FROM stats WHERE id = '$id'");
			$compteur++;
		}
	}
	return $compteur;
}

function verif_jno($date)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_23");

	// on enleve l'année et formate la date de facon a avoir %mm/jj% pour la requete LIKE
	$ind2 = find_in_date($date, 2);
	$ind3 = find_in_date($date, 3);
	$new_date = "%".$ind2."/".$ind3;
	
	$jno = 0;
	// Si on retrouve le jour et le mois, on vérifie s'il s'agit d'une date ANNUELLE 
	if( mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT date FROM hno WHERE date LIKE '$new_date'" ) ) )
	{
		// Si annuel, alors qu'importe l'année : il faut supprimer la date.
		$donnees2 = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT date, is_annuel FROM hno WHERE date LIKE '$new_date'"));
		if( $donnees2['is_annuel'] == 1)
		{
			$jno = 1;
		}
		else
		{
			// Vérifier si l'année également est la meme.
			if( $donnees2['date'] == $date )
				$jno = 1;			
		}
	}
	
	return $jno;
}





/////////////////////////
class affichage
{
	
	public function AffichMenu(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_22");

		echo '	
			<div class="floatonright">
			<div style="position:absolute; top:0px; color:FFFFFF;">
				<a href="admin/index.php">Admin</a> | 
				<a href="deconnect.php">Se Deconnecter</a>
			</div>
			</div>

		<div class="menu">
			<a href="index.php">Accueil</a>   |   <a href="gestion_bases.php">Bases de donnees</a>   |   <a href="options.php?maj">Featuringz</a>   |   <a href="\\\\frsv001053\\Centre_Appel\\CLIENTS\\FAQ Communes\\Commun 0031 Outils de Stats.doc">F.A.Q.</a>   |   <a href="bugtracker.php">BugTracker</a>
		</div>';
	}
	public function AffichMenuAdmin(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_21");

		echo '	
			<div class="floatonright">
			<div style="position:absolute; top:0px; color:FFFFFF;">
				<a href="../index.php">Utilisateur</a> | 
				<a href="../deconnect.php">Se Deconnecter</a>
			</div>
			</div>

		<div class="menu">
			<a href="gestion_bases_Admin.php">Bases de donnees</a>   |   <a href="client.php">Equipes et Clients</a>   |   <a href="hno.php">Les jours non-ouvres</a>   |   <a href="scaner.php">Les fichiers envoyes</a>   |   <a href="../bugtracker.php">BugTracker</a>
		</div>';
	}
	public function AffichPied(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_20");

		mysql_connect( "localhost", "root", "") or die ("Connexion au serveur impossible");
		mysql_select_db("tchat") or die ("Connexion a la base impossible");

		echo '
			<div class="pied"><br/>
				<div class="floatonright">
					Outil de Stats V 2.3.9
				</div>
				<div class="floatonleft">
					Webmaster : C. Ortiz
				</div>
				<div class="aucentre">
					<span onclick="montre_div(\'last_changes\')">Cliquez ici pour afficher les 3 dernieres modifications.</span><br/>
					<div name="last_changes" id="last_changes" style="display:none;">';
		
						$reponse = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM minichat ORDER BY ID DESC LIMIT 0,3");
						
						while( $donnees = mysql_fetch_array($reponse) )
						{
							echo '<p><strong>'.$donnees['pseudo'].'</strong> : '.$donnees['date']."<br/>".$donnees['message'].'</p>';
						}

		echo '		</div>
				</div>
			</div>';
		
		mysql_close();

	}
	public function AffichMsgAttente(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_19");

		echo '<div id="message" style="display:none;">
			<div class="corps"><div class="cadre">
			<br/><br/><h2>Veuillez patienter s.v.p.<br/>Traitement de votre demande en cours.<br/>
			<img src="images/vtbusy.gif"/></h2>
			</div></div></div>';

	}
}

/////////////////////////
class ClassArray implements ArrayAccess
{
	protected $_array = array();
	
	public function __construct() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_18");

		$this->_array = func_get_args();
	}
	// En cas d'appel de la fonction isset()
	public function offsetExists($offset) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_17");

		return isset($this->_array[$offset]);
	}
	// En cas d'appel de la valeur
	public function offsetGet($offset) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_16");

		if( is_numeric( $offset ) )
		{
			return $this->_array[$offset];
		}
		else
		{	
			$index = $this->find($offset);
			return $this->_array[$index];
		}
	}
	// En cas d'affectation de la valeur
	public function offsetSet($offset, $value) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_15");

		return $this->_array[$offset] = $value;
	}
	// En cas d'appel de la fonction unset()
	public function offsetUnset($offset) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_14");

		unset($this->_array[$offset]);
	}
	// En cas d'appel de la fonction echo
	public function __toString(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_13");

		$liste = '';
		$z = 0;
		
		while( $z < $this->count() )
		{
			if( isset( $this->_array["$z"] ) )
				$liste = $liste.'['.$z.']:'.$this->_array["$z"]."  ";
			$z++;
		}
		return $liste;
	
	}
	public function count(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_12");

		return count($this->_array);
	}
	public function find( $item, $i=0 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_11");


		while( $i < count($this->_array ))
		{
			if( $this->_array[$i] == $item )
				return $i;
			$i ++;
		}
		
		$i = -1;
		return $i;
	}
	public function trier(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_10");

		sort( $this->_array );
		return $this;
	}	
	public function trier_desc(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_9");

		rsort( $this->_array );
		return $this;
	}
	public function del( $offset ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_8");

		$num = 0;
		if( is_numeric( $offset ) )
		{
			$num = $offset;
		}
		else
		{
			$num = $this->find( $offset );
		}
		
		$i = $num+1;
		while( $i <= count($this->_array) )
		{
			$this->_array["$num"] = $this->_array["$i"];
			$i ++;
			$num ++;
		}
		
		$os = count($this->_array) - 1;
		unset( $this->_array[ $os ] );
		
		return $num;
	}
	public function add( $value ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_7");

		$this->_array[ count($this->_array) ] = $value;
		return $this;
	}
	public function vider(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_6");

		$i = $this->count() - 1;
		while( $i >= 0 )
		{
			unset($this->_array[$i]);
			$i--;
		}
		
		return $this;
	}
	public function where_insert( $value ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_5");

	
		// Le principe va etre de comparer les 2 lignes d'un tableau et voir si la Value a sa place entre.
		// Tableau doit etre ordonné.
		
		$i = 0;
		$j = $i+1;
		$mem = $j;
		$ordre = "";
		$is_ok = 0;
		
		// Verifier dans quel sens est-il ordonné ...		
		if( $this->_array[$i] < $this->_array[$j] )
			$ordre = "croiss";
		else
			$ordre = "d&#233;croiss";
		
		if( $ordre == "croiss" )
		{
			// Cas ou l'insertion doit se faire des le début
			if( $value <= $this->_array[$i] )
			{
				$mem = $i;
				$j = $this->count();
				$is_ok = 1;
			}
			// Cas ou l'insertion doit se faire a la fin
			$last_offset = $this->count() - 1;
			if( $value >= $this->_array[$last_offset] )
			{
				$mem = $last_offset + 1; // +1 car insertion APRES le dernier offset.
				$j = $this->count();
				$is_ok = 1;
			}
		}
		else
		{	
			// Cas ou l'insertion doit se faire des le début
			if( $value >= $this->_array[$i] )
			{
				$mem = $i;
				$j = $this->count();
				$is_ok = 1;
			}
			// Cas ou l'insertion doit se faire a la fin
			$last_offset = $this->count() - 1;
			if( $value <= $this->_array[$last_offset] )
			{
				$mem = $last_offset;
				$j = $this->count();
				$is_ok = 1;
			}
		}
		
		// Sinon on compare les valeurs 2 à 2. Voir si la Value n'irait pas entre 2.
		while( $j < $this->count() )
		{	
			$mem = $j;
			if( $ordre == "croiss" )
			{
				if( $value >= $this->_array[$i] and $value < $this->_array[$j] )
				{
					$j = $this->count();
					$is_ok = 1;
				}
						
			}
			else
			{
				if( $value <= $this->_array[$i] and $value > $this->_array[$j] )
				{
					$j = $this->count();
					$is_ok = 1;
				}
			}
			$i++;
			$j++;
		}
			
		if( $is_ok == 1 )
			return $mem;
		else
			return -1;

	}
	public function insert( $value, $offset = -1 ){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_4");

	
		// Si on ne nous donne pas d'indice, on cherche ou inserer la valeur en question.
		if( $offset == -1 )
			$offset = $this->where_insert( $value );
		
		if( $offset != -1 )
		{
			// On part de la fin du tableau,
			$i_paste = $this->count();
			$i_copy = $i_paste - 1;
			
			// et décalle les valeurs 1 à 1.
			while( $i_copy >= $offset )
			{
				$this->_array[$i_paste] = $this->_array[$i_copy];
				$i_copy --;
				$i_paste --;
			}
			
			// Insertion de la valeur sur l'offset demandé.
			$this->_array[$offset] = $value;
		}
		return $offset;
	}
	public function occurences(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_3");

		$tab_result = array_count_values ($this->_array); // on récupère les occurences et leur nombre
		
		return $tab_result;
	}
}





///////////////////////
function NumToHour($nombre)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_2");

	//$nombre = 1234; //remplacez ici par votre nombre a convertir

	//initialisation
	$secondes = 0;
	$minutes = 0;
	$heure = 0;

	//convertion

	$minutes = $nombre/60; 
	$secondes = bcmod($nombre,"60");
	$minutes = floor($minutes);

	while($secondes >= "60") //ajoute une minute toutes les 60 secondes
	{
	 $secondes = $secondes-60;
	 $minutes++;
	}
	while($minutes >= "60")//ajoute une heure toutes les 60 minutes
	{
	 $minutes = $minutes-60;
	 $heure++;
	}

	if($minutes < "10" and $heure != "0") // ajoute le deuxieme 0 pour la présentation
	{
	 $minutes = "0".$minutes;
	}

	if($secondes < "10") // ajoute le deuxieme 0 pour la présentation
	{
	 $secondes = "0".$secondes;
	}

	if($heure < "10") // ajoute le deuxieme 0 pour la présentation
	{
	 $heure = "0".$heure;
	}

	if($heure == "00")
		$resultat = $minutes.":".$secondes; //contient le résultat final
	else
		$resultat = $heure.":".$minutes.":".$secondes; //contient le résultat final

	return $resultat; // afficher le résultat
}

function is_pair($nombre)
{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_1");

	$pair = true;
	if ($nombre%2 == 1)
		$pair = false;

	return $pair;
}

function phpmv2(){
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\engine\\functions.php_0");

echo '<!-- phpmyvisites -->
<a href="http://www.phpmyvisites.net/" title="phpMyVisites | Open source web analytics"
onclick="window.open(this.href);return(false);"><script type="text/javascript">
<!--
var a_vars = Array();
var pagename=\'\';

var phpmyvisitesSite = 1;
var phpmyvisitesURL = "http://frdt110650/phpmv2/phpmyvisites.php";
//-->
</script>
<script language="javascript" src="http://frdt110650/phpmv2/phpmyvisites.js" type="text/javascript"></script>
<object><noscript><p>phpMyVisites | Open source web analytics
<img src="http://frdt110650/phpmv2/phpmyvisites.php" alt="Statistics" style="border:0" />
</p></noscript></object></a>
<!-- /phpmyvisites --> ';
}



?>