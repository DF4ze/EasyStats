<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\HNO.PHP_1");
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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\HNO.PHP_0");

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

////////////////////////////////////////////////////////////////////////////////
////////////////////////	Verif personne en ligne			////////////////////
////////////////////////////////////////////////////////////////////////////////

	// On se connecte � MySQL :
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
		// Ajout d'une date 
		if(isset( $_POST['ajouter'] ))
		{
//			echo 'ajouter <br/>';
			if( isset($_POST['description']) and isset($_POST['date']))
			{
//				echo 'Description et Date envoy�s<br/>';
				if( $_POST['date'] != '' )
				{
//					echo 'date non vide<br/>';
					
					$date = reverse_date($_POST['date']);
					// v�rif si la date n'est pas deja entr�e
					if( !mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT date FROM hno WHERE date='$date'" ) ) )
					{
						$description = $message = str_replace("'", "\'", $_POST['description']);;
						$is_annuel = 0;
						if( isset($_POST['is_annuel']) )
						{
							$is_annuel = 1;
//							echo 'annuel coch�<br/>';
						}
						Hook_19642b6af0764bf47e9e6ec6bcdd44801("INSERT INTO hno VALUES('', '$date', '$description', '$is_annuel')");
//						echo 'insertion base ok<br/>';
					}
				}
			}
		}
	
		// Supprimer une date
		if( isset( $_GET['suppr']))
		{
			$date = $_GET['suppr'];
			Hook_19642b6af0764bf47e9e6ec6bcdd44801( "DELETE FROM hno WHERE date='$date'");
		}
	
	
		// Ajouter un jour NO
		
		echo '<div class="centre">';
		echo '<div class="cadre">';

		echo '<strong>Ajouter un nouveau jour non-ouvr�.</strong><br/>';
		
		echo '<div style="padding:5px;">';

		echo '<form method="post"  action="hno.php" > <p>';
		echo '<input type="text" name="date" id="date"> date<br/>';
		echo '<input type="text" name="description" id="description" > description<br/>';
		echo '<input type="checkbox" name="is_annuel" id="is_annuel" > Repeter tout les ans<br/>';
		echo '<input type="submit" name="ajouter" value="Ajouter">';
		echo '</p></form> ';

		echo '</div>';
		echo '</div>';
		echo '</div>';

		
		
		
		// Listing des jours non ouvr�s
		echo '<div class="centre">';
		echo '<div class="cadre">';
	
		echo '<span onclick="montre_div(\'tableau\');"> <strong>Listing des jours non ouvr�s </strong><em>(cliquer pour d�ployer)</em></span>';
		
		echo '<div style="text-align:center; padding:5px;">';
		
		echo '<table id="tableau" ';
/*		if( !isset( $_POST['ajouter']) and !isset( $_GET['suppr']))
			echo 'style="display:none"';
*/		echo '>';
		
		echo '<tr>';
			echo '<th>Date</th>';
			echo '<th>Description</th>';
			echo '<th>Repeter tout les ans</th>';
		echo ' </tr>';

		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM hno ORDER by date ASC");
		while( $donnees = mysql_fetch_array($requete) )
		{
			echo '<tr>';
				echo '<td><a href="hno.php?suppr='.$donnees['date'].'">'.reverse_date($donnees['date']).'</a></td>';
				echo '<td>'.$donnees['description'].'</td>';
				echo '<td>';
					if( $donnees['is_annuel'] == 1 )
						echo 'oui';
					else
						echo 'non';
				echo '</td>';
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