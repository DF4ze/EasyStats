<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\variables.php_0");
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
</head>
<body>


<?php
////////////////////////////////////////////////////////////////////////////////
////////////////////////	Affichage du menu		/////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	AffichMenuAdmin();
	
	// On se connecte � MySQL :
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion a la base impossible");


	
	echo '<div class="cadre">';
		echo "<strong>Variables d'initialisation de l'application.</strong> ";
		echo "!! Ne touchez � ces valeurs que si vous etes sur de ce que vous faites !!";

		if( isset( $_POST['valid'] ) )
		{
			$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM variables");
			while( $donnees = mysql_fetch_array($requete) )
			{
				$name = $donnees['name'];
				if( isset( $_POST["$name"] ) )
				{
					//echo "Variable : ".$name." ok <br/>";
					
					$value = $_POST["$name"];
					Hook_19642b6af0764bf47e9e6ec6bcdd44801( "UPDATE variables SET value='$value' WHERE name='$name'" );
				}
			}	
			echo '<br/><br/>Mise � Jour avec succes.<br/>';
		}




		echo '<br/><br/><form id="form" name="form" method="post" action="variables.php" >'; 
		
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801( "SELECT * FROM variables" );
		while( $donnees = mysql_fetch_array($requete) )
		{
		
			if( $donnees['visible'] == 'true' )
				echo $donnees['name'].' : <input type="text" name="'.$donnees['name'].'" value="'.$donnees['value'].'" > '.$donnees['com']."<br/><br/>";
/* 
			$nom_var = $donnees['name'];
			$value = $donnees['value'];
			$_SESSION["$nom_var"] = $value; */
		}
		echo '<input type="submit" value="Valider" name="valid">';
		echo '</form>'; 
		
	echo '</div>';
	

	
	// On se d�connecte de MySQL
	mysql_close();
	
	?>


</body>
</html>