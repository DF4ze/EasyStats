<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\CLIENT.PHP_3");
	session_start();
		require_once("../class.php");

		if( !isset($_SESSION['user']) )
			header("Location:login.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Easy-Stat</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design_admin.css" />
	<link rel="shortcut icon" type="image/x-icon" href="../images/esico.ico" />
	<link rel="icon" type="image/png" href="../images/esico.ico" />

	<script type="text/javascript">
	function montre_div(nom_div)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\CLIENT.PHP_2");

		if( document.getElementById(nom_div).style.display == "none" )
			document.getElementById(nom_div).style.display="block";
		else
			document.getElementById(nom_div).style.display="none";
	}
	function Couleur() 
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\CLIENT.PHP_1");

		fenetreA=window.open("couleurs.php","Couleur","status=no,location=no,toolbar=no,directories=no,resizable=yes,left=100,top=10,width=200,height=200,top=100,left=100");
		fenetreA.focus();
	} 
	function modif_couleur(value)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\CLIENT.PHP_0");

		document.getElementById("color").value = value;
		color = "#"+value;
		document.getElementById("area").style.backgroundColor = color;
		
	}	
	</script>
	<style type="text/css">
	td
	{
		border: 1px solid white; 
		text-align: left;
		padding: 0;
	}
	</style>
</head>
<body>
<?php
	AffichMenuAdmin();



	// On se connecte � MySQL :
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion a la base impossible");



	if(isset($_POST['ValidEquipe']))
	{
		if(isset($_POST['NvlEquipe']))
		{
			if( $_POST['NvlEquipe'] != '')
			{
				$equipe = $_POST['NvlEquipe'];
				$date1 = $_SESSION['h_ouverture'];
				$date2 = $_SESSION['h_fermeture'];
				$sla = $_SESSION['SLA'];
				$min = $_SESSION['BaliseMin'];
				$max = $_SESSION['BaliseMax'];
				Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO clients VALUES('', '', '$equipe', '$date1', '$date2', '$sla', '$min', '$max', '$min', '$max', 0, 0, '000000')") or die ( "Echec lors de la cr�ation du client: ".mysql_error() );
			
				$_SESSION['message'] = "L'equipe <strong>".$equipe."</strong> vient d'�tre cr��.";
			}
		}
	}
	
	if(isset($_POST['modifheures']))
		if(isset($_POST['h_ouverture']) and isset($_POST['h_fermeture']))
			if($_POST['h_ouverture'] != "" and $_POST['h_fermeture'] != "")
			{
				$h_ouverture = $_POST['h_ouverture'];
				$h_fermeture = $_POST['h_fermeture'];
				$client = $_POST['client'];
				
				Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET h_ouverture='$h_ouverture', h_fermeture='$h_fermeture'  WHERE client = '$client'") or die(mysql_error());
			
				$_SESSION['message'] = "Le client <strong>".$client."</strong> ouvre maintenant � <strong>".$h_ouverture."</strong> et ferme � <strong>".$h_fermeture."</strong>.";
			}
	

	
	if(isset($_POST['validsuppr']))
	{
		$equipe = $_POST['equipe'];
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET equipe = 'NoTeam' WHERE equipe = '$equipe'") or die(mysql_error());
		
		// Suppression des lignes ou PAS de client et PAS d'�quipe.
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("DELETE FROM clients WHERE (equipe = 'NoTeam' or equipe = '') AND client = ''");

		$_SESSION['message'] = "L'equipe <strong>".$equipe."</strong> est maintenant supprim�e.";
	}
	
	if(isset($_POST['ValidRenEquipe']))
	{
		$NewEquipe = $_POST['NewEquipe'];
		$OldEquipe = $_POST['OldEquipe'];
		
		
		// MAJ base CLIENTS
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET equipe = '$NewEquipe' WHERE equipe = '$OldEquipe'") or die(mysql_error());

		$_SESSION['message'] = "L'equipe <strong>".$OldEquipe."</strong> est maintenant renomm�e en <strong>".$NewEquipe."</strong>.";
	}
	
	if(isset($_GET['ChangEquipe']) and isset($_GET['LeClient']))
	{
		$NewEquipe = $_GET['ChangEquipe'];
		$client = $_GET['LeClient'];
	
		
		// Mise a jour de la base Clients
		Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET equipe = '$NewEquipe' WHERE client = '$client'") or die(mysql_error());
		
		// On v�rifie s'il y a des clients '' dans l'�quiep d'arriv�e
		if( mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM clients WHERE client='' AND equipe='$NewEquipe'" ) ) )
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "DELETE FROM clients WHERE client='' AND equipe='$NewEquipe'");
		
		$_SESSION['message'] = 'Le client <strong>'.$client.'</strong> est maintenant dans l\'�quipe <strong>'.$NewEquipe.'</strong>.<br/>';
	}
	
	
	if(isset($_POST['modif_sla']))
	{
		if( $_POST['sla'] != "" )
		{
			$client = $_POST['client'];
			$sla = $_POST['sla'];
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET sla = '$sla' WHERE client = '$client'");
			
			$_SESSION['message'] = 'Le client <strong>'.$client.'</strong> a maintenant un SLA de <strong>'.$sla.'</strong>.<br/>';
		}
	}
	
	
	if(isset($_POST['modif_bornes']))
	{
		if( $_POST['min'] != "" and $_POST['max'] != "")
		{
			$client = $_POST['client'];
			$min = $_POST['min']+1; //+1 pour que les appels tombant sur la borne minimale soient comptabilis�s comme en dessous
			$max = $_POST['max'];
			$min_decro = $_POST['min_decro']+1; //+1 pour que les appels tombant sur la borne minimale soient comptabilis�s comme en dessous
			$max_decro = $_POST['max_decro'];
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET min = '$min', max = '$max', min_decro = '$min_decro', max_decro = '$max_decro' WHERE client = '$client'");

			$_SESSION['message'] = "Le client <strong>".$client."</strong> est calcul� celon les bornes : <br/><strong>".$_POST['min']."</strong> et <strong>".$max."</strong> pour les abandons.<br/><strong>".$_POST['min_decro']."</strong> et <strong>".$max_decro."</strong> pour les d�croch�s.";
		}
	}
	
	if( isset( $_POST['modifjfwe'] ) )
	{
		$client = $_POST['client'];
		if( $_POST['we'] == "we_oui")
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_we = 1 WHERE client = '$client'");
		else
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_we = 0 WHERE client = '$client'");
			
		if( $_POST['jf'] == "jf_oui" )
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_jf = 1 WHERE client = '$client'");
		else
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET compte_jf = 0 WHERE client = '$client'");

		$_SESSION['message'] = "Les modifications sont prises en compte.";
	}
	
	if( isset( $_POST['modif_color'] ) )
	{
		if( isset( $_POST['color'] ))
		{
			$client = $_POST['client'];
			$color = $_POST['color'];
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE clients SET color = '$color' WHERE client = '$client'");

			$_SESSION['message'] = "Les modifications sont prises en compte.";
		}
	}

	

	echo '<div class="resume">';
	echo '<div class="cadre">
		<strong>Organisation actuelle</strong>
		<div style="padding:5px">';	
		
	$requete1 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT equipe FROM clients ORDER BY equipe ASC" )or die(mysql_error()); // on r�cup�re les clients
	while( $donnees1 = mysql_fetch_array($requete1) )
	{
		echo '<strong>'.$donnees1['equipe'].'</strong>';
		
		echo '<ul id="'.$donnees1['equipe'].'" style="display:block;">';
		$equipe = $donnees1['equipe'];
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT client FROM clients WHERE equipe = '$equipe' ORDER BY client ASC" )or die(mysql_error());
		// On affiche et met a jour la base de client.
		while( $donnees = mysql_fetch_array($requete) )
		{
			if( $donnees['client'] != ' ' ) // �vite d'afficher un point dans les �quipes ou il n'y a pas de client
				echo '<li><a href="client.php?client='.$donnees['client'].'">'.$donnees['client'].'</a></li>';
		}	
		echo "</ul>";
	}

	echo '</div></div>';
	echo '</div>';// Fin du div r�sum�.
	
	echo '<div class="options">';
	
	echo '<div class = "cadre"><strong>Options</strong>
		<div style="padding:5px">';
	
	echo "Cr�er une nouvelle �quipe<br/>";
	echo '<form method="post" id="form" name="form" action="client.php"> <p>
		<input type="text" name="NvlEquipe" id="NvlEquipe">';
	echo '<input type="submit" name="ValidEquipe" id="ValidEquipe" value="Cr�er">
		</p></form>';
		
		
	echo '<br/><br/>Supprimer une �quipe : <br/>';
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT equipe FROM clients ORDER BY equipe ASC" )or die(mysql_error());
	// On affiche et met a jour la base de client.
	while( $donnees = mysql_fetch_array($requete) )
	{
		if( $donnees['equipe'] != "NoTeam" )
			echo '- <a href="client.php?supequip='.$donnees['equipe'].'">'.$donnees['equipe'].'</a><br/>';
	}
	
	echo '<br/><br/>Renommer une �quipe : <br/>';
	$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT equipe FROM clients ORDER BY equipe ASC" )or die(mysql_error());
	// On affiche et met a jour la base de client.
	while( $donnees = mysql_fetch_array($requete) )
	{
		if( $donnees['equipe'] != "NoTeam" )
			echo '- <a href="client.php?renequip='.$donnees['equipe'].'">'.$donnees['equipe'].'</a><br/>';
	}	
	
	
	echo '</div></div></div>';
	
	if( isset( $_SESSION['message'] ) ){
		if( $_SESSION['message'] != '' )
		{
			echo '<div class="centre" id="message" ><div class="cadre">';
			echo '<span onclick="montre_div(\'message\')" style="float:right;"><label>X</label></span>';
			echo '<strong>Message :</strong>';
			echo '<p>'.$_SESSION['message'].'</p>';
			echo '</div></div>';
			$_SESSION['message'] = '';
		}
	}

	
	if(isset($_GET['client']))
	{
		$client = $_GET['client'];

		echo '<div class="centre">';
		echo '<div class="cadre">';
		echo "<strong>Modifier les propri�t�s du client : ".$client."</strong><br/>";		
		
		echo '<div style="padding:5px;">';		
		
		echo "<strong>Selectionner parmi une �quipe existante :</strong> <br/>";
		
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT DISTINCT equipe FROM clients ORDER BY equipe ASC" )or die(mysql_error());
		while( $donnees = mysql_fetch_array($requete) )
		{
			if( $donnees['equipe'] != "NoTeam" )
				echo '- <a href="client.php?ChangEquipe='.$donnees['equipe'].'&LeClient='.$client.'&client='.$client.'">'.$donnees['equipe'].'</a><br/>';
		}
		
		
		echo "<br/><br/><strong>Selectionner les horaires d'ouverture et fermeture : </strong><br/>";
		
		// Recherche des horaires de facon a les afficher.
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT h_ouverture, h_fermeture FROM clients WHERE client='$client'" ));
		
		echo '<form method="post" id="horaire" name="horaire" action="client.php?client='.$client.'"> 
		<strong> format HH:MM </strong><br/> 
		<table >
		<tr >
			<td>Heure d\'ouverture : </td> <td> <input type="text" name="h_ouverture" id="h_ouverture" value="'.$donnees['h_ouverture'].'" style="width:35px;"></td>
		</tr>
		<tr>
			<td>Heure de fermeture : </td> <td> <input type="text" name="h_fermeture" id="h_fermeture" value="'.$donnees['h_fermeture'].'" style="width:35px;"></td>
		</tr>
		<input type="hidden" name="client" id="client" value="'.$client.'">
		<tr>
			<td><input type="submit" name="modifheures" id="modifheures" value="Modifier"></td>
		</tr>
		</table>
		</form>';

		
		
		echo '<br/><br/><strong>Selectionner le SLA : </strong><br/>';
		
		// Rechercher dans la BD le SLA de ce client de facon a l'afficher.
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT sla FROM clients WHERE client='$client'" ));
		
		echo'<form method="post" id="form_sla" name="form_sla" action="client.php?client='.$client.'">
		SLA : <input type="text" name="sla" id="sla" value="'.$donnees['sla'].'" style="width:20px;"><br/>
		<input type="hidden" name="client" id="client" value="'.$client.'">
		<input type="submit" name="modif_sla" id="modif_sla" value="Modifier">
		</form>';
	
		
		
		echo "<br/><br/><strong>Selectionner les bornes Minimum et Maximum : </strong><br/>";
		
		// Recherche des horaires de facon a les afficher.
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT min, max, min_decro, max_decro FROM clients WHERE client='$client'" ));
		
		$affichMin = $donnees['min'] - 1;
		$affichMin_decro = $donnees['min_decro'] - 1;
		echo '<form method="post" id="bornes" name="bornes" action="client.php?client='.$client.'"> 
		<table>
		<tr>
			<td>Borne MIN Abandons : </td><td><input type="text" name="min" id="min" value="'.$affichMin.'" style="width:20px;"></td>
		</tr>
		<tr>
			<td>Borne MAX Abandons : </td><td><input type="text" name="max" id="max" value="'.$donnees['max'].'" style="width:20px;"></td>
		</tr>
		<tr>
			<td>Borne MIN D�croch�s : </td><td><input type="text" name="min_decro" id="min_decro" value="'.$affichMin_decro.'" style="width:20px;"></td>
		</tr>
		<tr>
			<td>Borne MAX D�croch�s : </td><td><input type="text" name="max_decro" id="max_decro" value="'.$donnees['max_decro'].'" style="width:20px;"></td>
		<tr>
		<input type="hidden" name="client" id="client" value="'.$client.'">
		<tr>
			<td><input type="submit" name="modif_bornes" id="modif_bornes" value="Modifier"></td>
		</tr>
		</table>
		</form>';
		
		
		echo "<br/><br/><strong>Selectionner le mode de Jours Ouvr�s : </strong><br/>";
		
		// Recherche des horaires de facon a les afficher.
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT compte_we, compte_jf FROM clients WHERE client='$client'" ));
		
		echo '<form method="post" id="horaire" name="horaire" action="client.php?client='.$client.'"> 
		<table>
		<tr>
			<td>Ouvert les Week-End : </td><td><input type="radio" name="we" id="we_oui" value="we_oui" ';
			if( $donnees['compte_we'] == 1 )
				echo 'checked="checked"';
			echo ' /> <label for="we_oui">Oui</label>';
			
			echo'<input type="radio" name="we" value="we_non" id="we_non" ';
			if( $donnees['compte_we'] == 0 )
				echo 'checked="checked"';
			echo '/> <label for="we_non">Non</label></td>
		</tr>
		<tr>
		<td>Ouvert les Jours F�ri�s : </td><td><input type="radio" name="jf" id="jf_oui" value="jf_oui"  ';
		if( $donnees['compte_jf'] == 1 )
			echo 'checked="checked"';
		echo'/> <label for="jf_oui">Oui</label>';
			
		echo '<input type="radio" name="jf" value="jf_non" id="jf_non" ';
		
		if( $donnees['compte_jf'] == 0 )
			echo 'checked="checked"';
		echo '/> <label for="jf_non">Non</label></td>
		</tr>
		<tr>
			<td><input type="hidden" name="client" id="client" value="'.$client.'">
			<input type="submit" name="modifjfwe" id="modifjfwe" value="Modifier"></td>
		</tr>
		</table>
		</form>';
		
		
		echo "<br/><br/><strong>Selectionner la couleur pour ce Client : </strong><br/>";
		
		// Recherche des horaires de facon a les afficher.
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT color FROM clients WHERE client='$client'" ));
		
		echo '<form method="post"  action="client.php?client='.$client.'"> 
		<table>
		<tr>
			<td style="border:1px solid white;">Couleur : <input type="hidden" name="color" id="color" value="'.$donnees['color'].'" style="width:50px;" ></td>
			<td id="area" name="area" style="padding:0px; border:1px solid white; background-color:#'.$donnees['color'].'"><a href="javascript:Couleur();"><img src="../images/dummy_large.gif" alt="couleur" border="0" width="25" height="25"></a></td>
		</tr>
		<tr>
			<td style="border:1px solid white;text-align:left"><input type="hidden" name="client" id="client" value="'.$client.'">
			<input type="submit" name="modif_color" id="modif_color" value="Modifier"></td>
		</tr>
		</form>';
		
		
		echo '</div>';
		echo '</div>';
		echo '</div>';	
	}	

	
	if(isset($_GET['renequip']))
	{
		echo '<div class="centre">';
		echo '<div class="cadre">';
		echo "Veuillez saisir un nouveau nom pour cette �quipe : <strong>".$_GET['renequip']."</strong><br/>";
		
		echo '<form method="post" id="form" name="form" action="client.php" style="text-align:center;"> <p>
		<input type="hidden" name="OldEquipe" id="OldEquipe" value="'.$_GET['renequip'].'">
		<input type="text" name="NewEquipe" id="NewEquipe">
		<input type="submit" name="ValidRenEquipe" id="ValidRenEquipe" value="Renommer">
		</p></form>';
		
		echo '</div>';
		echo '</div>';
	}
	
	if(isset($_GET['supequip']))
	{
		echo '<div class="centre">';
		echo '<div class="cadre">';
		echo 'Etes-vous s�r de vouloir supprimer l\'�quipe <strong>'.$_GET['supequip'].'</strong>?<br/>';
		
		echo '<form method="post"  action="client.php" style="text-align:center;"> <p>';
		echo '<input type="hidden" name="equipe" id="equipe" value="'.$_GET['supequip'].'">';
		echo '<input type="submit" name="validsuppr" id="validsuppr" value="oui"> ';
		echo '<input type="submit" name="non" value="non">';
		echo '</p></form> ';
		echo '</div>';
		echo '</div>';
	}

	
	// On se d�connecte de MySQL
	mysql_close();

	?>

</body>
</html>

