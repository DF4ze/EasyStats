<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\crash.php_1");
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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\crash.php_0");

		if( document.getElementById(nom_div).style.display == "none" )
			document.getElementById(nom_div).style.display="block";
		else
			document.getElementById(nom_div).style.display="none";
	}
	</script>
</head>


<body>

<?php
	AffichMenuAdmin();


	// On se connecte � MySQL :
	if( $_SESSION['debug_mode'] == 1 )
	echo "Serveur : ".$_SESSION['bd_serveur']."<br/>";
	
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion a la base impossible");

	/* $Access = maj_online(); */
	if( false/* !$Access */ )
	{
		echo '<div class="cadre">';
		echo "<br/><br/><h2>L'acc�s � la base a �t� refus�, un utilisateur est deja connect�<br/>Merci de tenter votre acces plus tard.</h2><br/>";
		echo '</div>';
	}
	else
	{
		// Ajout d'un crash
		if(isset( $_POST['ajouter'] ))
		{
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO crashs VALUES('', '".$_POST['client']."', '".$_POST['date_debut']."', '".$_POST['date_fin']."', '".$_POST['duree']."', '".$_POST['commentaire']."')");
			echo "Crash ajout� <br/> Client : ".$_POST['client']." du ".$_POST['date_debut']." au ".$_POST['date_fin']."<br/>";
		}
	
		// Supprimer un crash
		if( isset( $_GET['suppr'])){
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "DELETE FROM crashs WHERE id='".$_GET['suppr']."'");
			echo "Crash supprim�<br/>";
		}
		
		if( isset( $_POST['modifier'] ) ){
			Hook_19642b6af0764bf47e9e6ec6bcdd44801("UPDATE crashs SET client='".$_POST['client']."', date_debut='".$_POST['date_debut']."', date_fin='".$_POST['date_fin']."', duree='".$_POST['duree']."', commentaire='".$_POST['commentaire']."'  WHERE id = '".$_POST['id']."'") or die(mysql_error());
			echo "Crash modifi�<br/>";
		}
		// Modifier un crash
		if( isset( $_GET['modif']))
		{
			$id = $_GET['modif'];
			$donnees = mysql_fetch_array( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM crashs WHERE id='$id'") );		

			echo '<div class="centre">';
			echo '<div class="cadre">';

			echo '<strong>Modifier une plage de crash.</strong><br/>';
			
			echo '<div style="padding:5px;">';

			echo '<form method="post"  action="crash.php" > <p>';
			
			echo '<select name="client" id="client">';
			
			$cmd_line = "SELECT client FROM clients ORDER BY client ASC";
			$requete2 = Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line );
			while( $donnees2 = mysql_fetch_array($requete2) )
			{
				if( $donnees2['client'] == $donnees['client'] )
					echo '<option value="'.$donnees2['client'].'" selected="selected">'.$donnees2['client'].'</option>';
				else
					echo '<option value="'.$donnees2['client'].'">'.$donnees2['client'].'</option>';					
			}
			echo '</select> Client<br/>';			
			
//			echo '<input type="text" name="client" id="client" value="'.$donnees['client'].'"> Client<br/>';
			echo '<input type="text" name="date_debut" id="date_debut" value="'.$donnees['date_debut'].'"> Date de d�but<br/>';
			echo '<input type="text" name="date_fin" id="date_fin" value="'.$donnees['date_fin'].'"> Date de fin<br/>';
			echo '<input type="text" name="duree" id="duree" value="'.$donnees['duree'].'"> Dur�e<br/>';
			echo '<input type="text" name="commentaire" id="commentaire" value="'.$donnees['commentaire'].'"> Commentaire<br/>';
			echo '<input type="hidden" name="id" id="id" value="'.$donnees['id'].'"> ';
			echo '<input type="submit" name="modifier" value="Modifier">';
			
			echo '</p></form> ';

			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		else
		{
			// Ajouter une plage de crash
			
			echo '<div class="centre">';
			echo '<div class="cadre">';

			echo '<strong>Ajouter une plage de crash.</strong><br/>';
			
			echo '<div style="padding:5px;">';

			echo '<form method="post"  action="crash.php" > <p>';
			
			echo '<select name="client" id="client">';
			$cmd_line = "SELECT client FROM clients ORDER BY client ASC";
			$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line );
			while( $donnees = mysql_fetch_array($requete) )
			{
				echo '<option value="'.$donnees['client'].'">'.$donnees['client'].'</option>';
			}
			echo '</select> Client<br/>';
			
			
			//echo '<input type="text" name="client" id="client"> Client<br/>';
			echo '<input type="text" name="date_debut" id="date_debut" > Date de d�but <strong>aaaa/mm/jj hh:mm:ss</strong><br/>';
			echo '<input type="text" name="date_fin" id="date_fin" > Date de fin <strong>aaaa/mm/jj hh:mm:ss</strong><br/>';
			echo '<input type="text" name="duree" id="duree" > Dur�e (en seconde)<br/>';
			echo '<input type="text" name="commentaire" id="commentaire" > Commentaire<br/>';
			echo '<input type="submit" name="ajouter" value="Ajouter">';
			
			echo '</p></form> ';

			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		
		
		
		// Listing des jours non ouvr�s
		echo '<div class="centre">';
		echo '<div class="cadre">';
	
		echo '<span onclick="montre_div(\'tableau\');"> <strong>Listing des crashs </strong><em>(cliquer pour d�ployer)</em></span>';
		
		echo '<div style="text-align:center; padding:5px;">';
		
		echo '<table id="tableau" ';
/*		if( !isset( $_POST['ajouter']) and !isset( $_GET['suppr']))
			echo 'style="display:none"';
*/		echo '>';
		
		echo '<tr>';
			echo '<th></th>';
			echo '<th></th>';
			echo '<th><a href="crash.php?sort=client" >Client</a></th>';
			echo '<th><a href="crash.php?sort=date_debut" >Date D�but</a></th>';
			echo '<th><a href="crash.php?sort=date_fin" >Date Fin</a></th>';
			echo '<th><a href="crash.php?sort=duree" >Dur�e (en s.)</a></th>';
			echo '<th><a href="crash.php?sort=commentaire" >Commentaire</a></th>';
		echo ' </tr>';

		$cmd_line = "SELECT * FROM crashs ORDER by date_debut DESC";
		if( isset( $_GET['sort'] ) ){
			$cmd_line = "SELECT * FROM crashs ORDER by ".$_GET['sort']." ASC";			
		}
		
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( $cmd_line );
		while( $donnees = mysql_fetch_array($requete) )
		{
			echo '<tr>';
				echo '<td><a href="crash.php?suppr='.$donnees['id'].'">Suppr.</a></td>';
				echo '<td><a href="crash.php?modif='.$donnees['id'].'">Modif.</a></td>';
				echo '<td>'.$donnees['client'].'</td>';
				echo '<td>'.$donnees['date_debut'].'</td>';
				echo '<td>'.$donnees['date_fin'].'</td>';
				echo '<td>'.$donnees['duree'].'</td>';
				echo '<td>'.$donnees['commentaire'].'</td>';
			echo ' </tr>';
		}
	
		echo '</table>';

		echo '</div>';
		echo '</div>';
		echo '</div>';


	}
		
	// On se d�connecte de MySQL
	mysql_close();
	AffichPied();
?>


</body>
</html>