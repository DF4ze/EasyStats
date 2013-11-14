<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\init.php_0");
 	session_start();
		require_once("class.php");	
		require_once("conf.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Statistiques</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
</head>
<body>
	<br/><br/><br/>
	<div class="cadre">
	<h1 style="text-align:center"> Bonjour et bienvenue sur Easy-Stat.<br/><br/> Une erreur s'est produite lors de l'initialisation,<br/> Veuillez contacter le <a href="mailto:df4ze@free.fr">Webmaster</a> et indiquez les messages ci-dessous</h1>
	<?php 
		
		// mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur MySql impossible");
		// mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql impossible");
		
		// L'option de forcer la connexion sur le serveur MySql local est elle activ�e?
		if( $_SESSION['force_local_mysql'] ){
			echo "Connexion forc�e sur la base MySql Locale<br/>";
			
				$_SESSION['bd_serveur'] = $_SESSION['bd_serveur_bk'];
				$_SESSION['bd_user'] 	= $_SESSION['bd_user_bk'];
				$_SESSION['bd_pwd'] 	= $_SESSION['bd_pwd_bk'];
				$_SESSION['bd_base'] 	= $_SESSION['bd_base_bk'];
				
				if( @mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) ){
					echo "Connexion au Serveur MySQL local avec succes.<br/>";
					// Alors on lance la connexion � la base.
					mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql locale impossible");
					echo "Connexion a la base MySQL locale avec succes.<br/>";
				}else
					die( "Erreur de connexion au serveur MySql Local. <br/>L'application ne pourra pas d�marrer sans serveur MySql.<br/> Veuillez desactiver l'option 'Forcer connexion Base Locale' et v�rifiez les parametres de connexion au serveur distant." );
		
		// Sinon on tente une connexion sur le serveur central ... si echec on tente une connexion sur le serveur local.
		}else{
			// Si on arrive a se connecter au serveur distant
			if( @mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd'])){
				echo "Connexion au Serveur MySQL distant avec succes.<br/>";
				// Alors on lance la connexion � la base.
				mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql distante impossible");
				echo "Connexion a la base MySQL distante avec succes.<br/>";
			}else{
				echo "Erreur de connexion au serveur MySql Central, <br/>Tentative de connexion au serveur local.<br/>";
				// Sinon, on tente une connexion sur le serveur de BackUp
				// On change la valeur des vairables de session, ainsi pas besoin d'appeler le bd_serveur_bk � la place du bd_serveur. =)
				$_SESSION['bd_serveur'] = $_SESSION['bd_serveur_bk'];
				$_SESSION['bd_user'] 	= $_SESSION['bd_user_bk'];
				$_SESSION['bd_pwd'] 	= $_SESSION['bd_pwd_bk'];
				$_SESSION['bd_base'] 	= $_SESSION['bd_base_bk'];
				
				if( @mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) ){
					echo "Connexion au Serveur MySQL local avec succes.<br/>";
					// Alors on lance la connexion � la base.
					mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql locale impossible");
					echo "Connexion a la base MySQL locale avec succes.<br/>";
				}else
					die( "Erreur de connexion au serveur MySql Local. L'application ne pourra pas d�marrer sans serveur MySql.<br/>" );
			}
		}
		$connectionInfo = array( "UID"=>$_SESSION['odbc_user'], "PWD"=>$_SESSION['odbc_pwd'], "Database"=>$_SESSION['odbc_base']);
		$_SESSION['odbc_connect'] = sqlsrv_connect( "frsv001355", $connectionInfo);
		if( $_SESSION['odbc_connect'] === false )
		{
			die( "Impossible de se connecter � la base 7480.</br>");
		}
		echo "Connexion a la base MS sql avec succes.<br/>";

	
		$appli = new application();
		$_SESSION['appli'] = serialize( $appli );
		
		if( !isset( $_SESSION['DateMin'] ) )
			$_SESSION['DateMin'] = '';
		if( !isset( $_SESSION['DateMax'] ) )
			$_SESSION['DateMax'] = '';
		
		
		$requete = Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM variables") or die( "erreur de r�cup�ration des variables d'initialisation" );
		while( $donnees = mysql_fetch_array($requete) )
		{
			$nom_var = $donnees['name'];
			
			$_SESSION["$nom_var"] = $donnees['value'];
		}

		$_SESSION['file_updated'] = check_srv_sql_up_date();
		header( "Location:stat.php" );

	?>
	</div>
	
		<?php AffichPied(); ?>

</body>
</html>