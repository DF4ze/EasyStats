<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\LOGIN.PHP_0");

	session_start(); // On d�marre la session AVANT toute chose
	//require_once("../bugtracker/config/config.php");
	require_once("../class.php");
	
	if( isset( $_SESSION['isadmin'] ) )
		if( $_SESSION['isadmin'] == 1 )
			header( "Location:accueil.php" );
			
	// $_SESSION['bd_serveur'] = 'localhost';
	// $_SESSION['bd_user'] = 'root';
	// $_SESSION['bd_pwd'] = '';
	// $_SESSION['bd_base'] = 'class_stats';

?>



<html>
<head>
	
	<title>Easy-Stat - Interface Admin</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <!--link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" /-->
    <link rel="stylesheet" media="screen" type="text/css" title="Designe" href="design_admin.css" />
	<link rel="shortcut icon" type="image/x-icon" href="../images/esico.ico" />
	<link rel="icon" type="image/png" href="../images/esico.ico" />
	<style type="text/css">
	<!--
	.input {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12pt; color: #FFFFFF; text-decoration: none; background-color: #333333; border: #000000; border-top-width: thin; border-right-width: thin; border-bottom-width: thin; border-left-width: thin}
	-->
	</style>


</head>

<body>
<?php affichMenuAdmin(); ?>

    <div id="en_tete">
		<!-- entete d�finie dans le CSS -->
	</div>

	
<div class="ombre">	
<div class="corps2">
<?php 
// Cr�ation d'une variable Url_Redir qui detient l'adresse de redirection. (par defaut celle d'erreur)
$url_redir = "location:login.php?erreur=1";

// Si erreur : Affiche un message
if(isset($_GET['erreur']))
{
	if( $_GET['erreur'] == 1 )
		echo '<div align="center"><p><font face="Verdana, Arial, Helvetica, sans-serif" size="4"> !!! Utilisateur ou mot de passe incorrect !!!</font></p> </div>';
	else if( $_GET['erreur'] == 2 )
		echo '	<div align="center"><p><font face="Verdana, Arial, Helvetica, sans-serif" size="4"> !!! Vous n\'avez pas les droits d\'acces administrateur !!!</font></p> </div>';
}




// S''il ya des VAR, c'est qu'on est deja pass� par cette page, sinon : affichage du formulaire.
if( isset( $_POST['user'] ) and isset( $_POST['passe'] ))
{	
	$user = trim($_POST['user']); // TRIM efface les espace en d�but et fin de chaine
	$passe = trim($_POST['passe']);
	
	// On se connecte � MySQL :
	mysql_connect($_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur impossible");
	mysql_select_db( $_SESSION['bd_base'] ) or die ("Connexion a la base impossible");
		
	// s'il y a une r�ponse c'est que l'USER est reconnu.
	if( mysql_num_rows( Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM logins WHERE user='$user' AND mdp='$passe'")))	
	{
		
		
		// On met les informations de login dans les variables "SUPER-GLOBALES"
		$_SESSION['user'] = $user;
		$_SESSION['pwd'] = $passe;
				
		// On r�cup�re les infos utilisateur de la base
		$donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT * FROM logins WHERE user='$user'"));

		//v�rif si utilisateur est admin.
		if( $donnees['isadmin'] == 1)
		{
			// On change l'adresse de redirection
			$url_redir = "Location:accueil.php";
			$_SESSION['isadmin'] = 1;
		}
		else
		{
			// On change l'adresse de redirection
			$url_redir = "location:login.php?erreur=2";
			$_SESSION['isadmin'] = 0;
		}

	}
	// On se d�connecte de MySQL
	mysql_close();
	
	//si user non authentifi�, $url_redir est deja sur l'url d'erreur.
	header($url_redir);
	//echo "url : ".$url_redir."<br/>";
}
?>


  <div class="adroite">
	<a href="../index.php" Title="Retour aux Stats">Retour aux Stats</a>
  </div>
  
  <div class="aucentre">
	<div><font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	  <p>
	  <br/><strong>
	  Bonjour et bienvenue dans la partie Administrateur<br/>
	  Venez vous identifier :</strong></font> 
	  </p>	
	</div>

	<form method="post" action="login.php">
	<div><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">
		Nom d'utilisateur<br>
		<input type="text" name="user"><br>
			<br>
		Mot de passe<br>
		<input type="password" name="passe"><br />

		<!--input type="checkbox" name="sesouvenir" /> Se souvenir de moi<br/><br/>

		<a href="pwd_lost.php" title="Vous ne vous rappelez plus de votre mot de passe?? C'est ici !">Perdu mon mot de passe</a><br />
		<a href="create_user.php" title="Bienvenue sur le !! Minichat !!">Cr�er un compte</a-->
		</font></b>
			<br>
			<br>
		<input type="submit" name="Submit" value="Entrer" class="input">
	</div>
	</form>
  </div>

</div>
</div>


</body>
</html>
