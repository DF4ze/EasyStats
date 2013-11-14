<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_24");
 session_start();
	require_once("../class.php");
	
	// S�curit� si pas authentifi�.
	if( !isset($_SESSION['user']) )
		header("Location:login.php");	
		
	// Si variable de session non initialis� => utilisateur a demand� la page options.php en direct sans passer par l'index ... donc on le r�oriente.
	if( !isset( $_SESSION['bd_serveur'] ) )
		header("Location:index.php");
	
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur MySql impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql impossible");
	
	$connectionInfo = array( "UID"=>$_SESSION['odbc_user'], "PWD"=>$_SESSION['odbc_pwd'], "Database"=>$_SESSION['odbc_base']);
	$_SESSION['odbc_connect'] = sqlsrv_connect( "frsv001355", $connectionInfo);
	if( $_SESSION['odbc_connect'] === false )
	{
		echo "Impossible de se connecter � la base 7480.</br>";
		die( print_r( sqlsrv_errors(), true));
	}
	
	$appli = unserialize( $_SESSION['appli'] );
	
	$groupe = new gp_clients();
	if( isset( $_SESSION['groupe'] ) )
		$groupe = unserialize( $_SESSION['groupe'] );
	
	$tab_date_min = new ClassArray();
	if( isset( $_SESSION['tab_date_min'] ) )
		$tab_date_min = unserialize( $_SESSION['tab_date_min'] );
		
	$tab_date_max = new ClassArray();
	if( isset( $_SESSION['tab_date_max'] ) )
		$tab_date_max = unserialize( $_SESSION['tab_date_max'] );

	// $i=0;
	// while( $i < $tab_date_max->count() )
	// {
		// echo 'tab_date_min : '.$tab_date_min[$i].'<br/>';
		// echo 'tab_date_max : '.$tab_date_max[$i].'<br/>';
		// $i++;
	// }
 

$nbsessions=active_session();
echo "Il y a <b>$nbsessions</b> personne(s) connect�e(s)<br/>";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Easy-Stat</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="../design.css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/esico.ico" />
	<link rel="icon" type="image/png" href="images/esico.ico" />
	<script type="text/javascript">
	<!--
	function montre_div(nom_div)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_23");

		if( document.getElementById(nom_div).style.display == "none" )
			document.getElementById(nom_div).style.display="block";
		else
			document.getElementById(nom_div).style.display="none";
	}
	function cocheequipe( equipe, IsCheck )
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_22");

		equipe = equipe + "_text";
		
		// On r�cup�re la textbox dans laquelle il y a tout les clients.
		LaChaine = document.getElementById(equipe).value;
		
		//On r�cup�re les Clients de la textbox.
		Pos_d = 0;
		Pos_f = 0;
		client = "";
		client_checkbox = "";

		while( LaChaine.indexOf("+", Pos_f) != -1 )
		{
			Pos_d = LaChaine.indexOf("+", Pos_f);
			Pos_f = LaChaine.indexOf("=", Pos_d);
			LeClient = LaChaine.substring( Pos_d+1, Pos_f);
			LeClient_checkbox = LeClient + "_checkbox";	
				
			if( IsCheck == 1 )
			{
				//cochetout(LeClient, 1);
				document.getElementById(LeClient_checkbox).checked = true;
			}
			else
			{
				//cochetout(LeClient, 0);
				document.getElementById(LeClient_checkbox).checked = false;
			}	
		}
	}
	function valid_clients()
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_21");

		document.getElementById('valider').onclick();
	}
	
	//-->
	</script>
</head>
<body>
<?php
////////////////////////////////////////////////////////////////////////////////
//////////////////		reception des variables	   		////////////////////////
////////////////////////////////////////////////////////////////////////////////

	if( isset( $_POST['modifjfwe'] ) )
	{
		$client = $_POST['client'];
		if( $_POST['we'] == "we_oui")
		{
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$appli->stat->liste_clients[$i]->compte_we = 1;
				$i++;
			}
			$_SESSION['force_we'] = 1;
		}
		else
		{
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$client = $appli->stat->liste_clients[$i];
				$donnees = $donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT compte_we FROM clients WHERE client='$client'"));

				if( $donnees['compte_we'] == 1 )
					$client->compte_we = 1;
				else
					$client->compte_we = 0;
				
				$i++;
			}
			$_SESSION['force_we'] = 0;
		}		

		
		if( $_POST['jf'] == "jf_oui" )
		{
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$appli->stat->liste_clients[$i]->compte_jf = 1;
				$i++;
			}
			$_SESSION['force_jf'] = 1;
		}
		else
		{
			$i=0;
			while( $i < $appli->stat->liste_clients->count() )
			{
				$client = $appli->stat->liste_clients[$i];
				$donnees = $donnees = mysql_fetch_array(Hook_19642b6af0764bf47e9e6ec6bcdd44801("SELECT compte_jf FROM clients WHERE client='$client'"));

				if( $donnees['compte_jf'] == 1 )
					$client->compte_jf = 1;
				else
					$client->compte_jf = 0;
				
				$i++;
			}
			$_SESSION['force_jf'] = 0;
		}
		
		if( $_POST['TOP'] )
			$_SESSION['TOP'] = $_POST['TOP'];
			
		$_SESSION['message'] = "Les modifications sont prises en compte.";
	}
	
	if( isset( $_POST['resetjfwe'] ) )
	{
		header( "Location:index.php" );
	}
	
	if( isset( $_POST['ChangeDate'] ) )
	{
		$_SESSION['DateMin'] = "";
		$_SESSION['DateMax'] = "";		

		if( isset( $_POST['PremDate'] )  and isset( $_POST['DeuxDate'] ) )
			if( verif_date($_POST['PremDate']) and verif_date($_POST['DeuxDate']))
			{
				$_SESSION['DateMin'] = reverse_date($_POST['PremDate']); 
				$_SESSION['DateMax'] = reverse_date($_POST['DeuxDate']); 
			}	
		
//		$_POST['valider'] = 'valider';
	}
	
	if( isset( $_POST['init'] ))
	{
		$_SESSION['DateMin'] = "";
		$_SESSION['DateMax'] = "";		

		$groupe->liste_clients->vider();
		$tab_date_max->vider();
		$tab_date_min->vider();
		$appli->stat->unselect_clients();
	}
	
	if( isset( $_POST['reset'] ))
	{	
		$groupe->liste_clients->vider();
		$tab_date_max->vider();
		$tab_date_min->vider();
		$appli->stat->unselect_clients();
	}
	
	if( isset($_POST['valider']) or isset($_POST['ajouter']))
	{
		// Deja init au tt d�buit de la page => probleme de dur�e de vie de l'objet.
		//$groupe = new gp_client();
		// Par contre il faut remettre le tableau "temporaire" a Zero.
		if( isset($_POST['valider']))
		{
			$groupe->liste_clients->vider();
			$tab_date_max->vider();
			$tab_date_min->vider();
		}
		
		$i = 0;
		while( $i < $appli->stat->liste_clients->count() )
		{
			$client = $appli->stat->liste_clients[$i];
			$nom_check_box = $client->nick.'_checkbox';
			
			if( isset( $_POST["$nom_check_box"] ) )
			{
				$groupe->add_client($client);
				$client->is_select = true;
				$tab_date_min->add( $_SESSION['DateMin'] );
				$tab_date_max->add( $_SESSION['DateMax'] );
				
			//	echo "Client ajout� : ".$client->nick." : ".$client."<br/>";
			}
			else
				$client->is_select = false;

			//	echo "Client ".$client." non selectionn�<br/>";
				
			$i++;
		}
		
		//echo "Liste Clients selectionn�s ".$groupe->liste_clients."<br/>";
	}
////////////////////////////////////////////////////////////////////////////////
////////////////////////	Affichage du menu		/////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	AffichMenuAdmin();
	
////////////////////////////////////////////////////////////////////////////////
//////////////////////////		Affichage	   		/////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
//////////////////
///////////////// Calendrier
/////////////////

?>
<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>

<script type="text/javascript">
// <!-- <![CDATA[

// Project: Dynamic Date Selector (DtTvB) - 2006-03-16
// Script featured on JavaScript Kit- http://www.javascriptkit.com
// Code begin...
// Set the initial date.
var ds_i_date = new Date();
ds_c_month = ds_i_date.getMonth() + 1;
ds_c_year = ds_i_date.getFullYear();

// Get Element By Id
function ds_getel(id) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_20");

	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_19");

	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
function ds_gettop(el) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_18");

	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}

// Output Element
var ds_oe = ds_getel('ds_calclass');
// Container
var ds_ce = ds_getel('ds_conclass');

// Output Buffering
var ds_ob = ''; 
function ds_ob_clean() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_17");

	ds_ob = '';
}
function ds_ob_flush() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_16");

	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_15");

	ds_ob += t;
}

var ds_element; // Text Element...

var ds_monthnames = [
'Janvier', 'F�vrier', 'Mars', 'Avril', 'Mai', 'Juin',
'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'D�cembre'
]; // You can translate it for your language.

var ds_daynames = [
'Dim', 'Lun', 'Mar', 'Me', 'Jeu', 'Ven', 'Sam'
]; // You can translate it for your language.

// Calendar template
function ds_template_main_above(t) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_14");

	return '<table cellpadding="3" cellspacing="1" class="ds_tbl">'
	     + '<tr>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_py();">&lt;&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_pm();">&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_hi();" colspan="3">[Fermer]</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_nm();">&gt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_ny();">&gt;&gt;</td>'
		 + '</tr>'
	     + '<tr>'
		 + '<td colspan="7" class="ds_head">' + t + '</td>'
		 + '</tr>'
		 + '<tr>';
}

function ds_template_day_row(t) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_13");

	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_12");

	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_11");

	return '<td class = "ds_blank_cell" colspan="' + colspan + '" ></td>'
}

function ds_template_day(d, m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_10");

	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_9");

	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_8");

	// First clean the output buffer.
	ds_ob_clean();
	// Here we go, do the header
	ds_echo (ds_template_main_above(ds_monthnames[m - 1] + ' ' + y));
	for (i = 0; i < 7; i ++) {
		ds_echo (ds_template_day_row(ds_daynames[i]));
	}
	// Make a date object.
	var ds_dc_date = new Date();
	ds_dc_date.setMonth(m - 1);
	ds_dc_date.setFullYear(y);
	ds_dc_date.setDate(1);
	if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		days = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		days = 30;
	} else {
		days = (y % 4 == 0) ? 29 : 28;
	}
	var first_day = ds_dc_date.getDay();
	var first_loop = 1;
	// Start the first week
	ds_echo (ds_template_new_week());
	// If sunday is not the first day of the month, make a blank cell...
	if (first_day != 0) {
		ds_echo (ds_template_blank_cell(first_day));
	}
	var j = first_day;
	for (i = 0; i < days; i ++) {
		// Today is sunday, make a new week.
		// If this sunday is the first day of the month,
		// we've made a new row for you already.
		if (j == 0 && !first_loop) {
			// New week!!
			ds_echo (ds_template_new_week());
		}
		// Make a row of that day!
		ds_echo (ds_template_day(i + 1, m, y));
		// This is not first loop anymore...
		first_loop = 0;
		// What is the next day?
		j ++;
		j %= 7;
	}
	// Do the footer
	ds_echo (ds_template_main_below());
	// And let's display..
	ds_ob_flush();
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// A function to show the calendar.
// When user click on the date, it will set the content of t.
function ds_sh(t) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_7");

	// Set the element to set...
	ds_element = t;
	// Make a new date, and set the current month and year.
	var ds_sh_date = new Date();
	ds_c_month = ds_sh_date.getMonth() + 1;
	ds_c_year = ds_sh_date.getFullYear();
	// Draw the calendar
	ds_draw_calendar(ds_c_month, ds_c_year);
	// To change the position properly, we must show it first.
	ds_ce.style.display = '';
	// Move the calendar container!
	the_left = ds_getleft(t);
	the_top = ds_gettop(t) + t.offsetHeight;
	ds_ce.style.left = the_left + 'px';
	ds_ce.style.top = the_top + 'px';
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// Hide the calendar.
function ds_hi() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_6");

	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_5");

	// Increase the current month.
	ds_c_month ++;
	// We have passed December, let's go to the next year.
	// Increase the current year, and set the current month to January.
	if (ds_c_month > 12) {
		ds_c_month = 1; 
		ds_c_year++;
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous month...
function ds_pm() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_4");

	ds_c_month = ds_c_month - 1; // Can't use dash-dash here, it will make the page invalid.
	// We have passed January, let's go back to the previous year.
	// Decrease the current year, and set the current month to December.
	if (ds_c_month < 1) {
		ds_c_month = 12; 
		ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the next year...
function ds_ny() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_3");

	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_2");

	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_1");

	// 2 digits month.
	m2 = '00' + m;
	m2 = m2.substr(m2.length - 2);
	// 2 digits day.
	d2 = '00' + d;
	d2 = d2.substr(d2.length - 2);
	// YYYY-MM-DD
//	return y + '-' + m2 + '-' + d2;
	return d2 + '/' + m2 + '/' + y;
}

// When the user clicks the day.
function ds_onclick(d, m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\admin\\OPTIONS.PHP_0");

	// Hide the calendar.
	ds_hi();
	// Set the value of it, if we can.
	if (typeof(ds_element.value) != 'undefined') {
		ds_element.value = ds_format_date(d, m, y);
	// Maybe we want to set the HTML in it.
	} else if (typeof(ds_element.innerHTML) != 'undefined') {
		ds_element.innerHTML = ds_format_date(d, m, y);
	// I don't know how should we display it, just alert it to user.
	} else {
		alert (ds_format_date(d, m, y));
	}
}

// And here is the end.

// ]]> -->
</script>



<?php
	//////////////
	////////////// Options en entete.
	//////////////
	
	// D�but du field autour des options
	// echo '<div class="cadre"> <strong>Actions</strong><br/>';	
	 echo '<fieldset style=" border: 0px solid white;">';

	// Formulaire pour le filtre de date.
// Formulaire pour le filtre de date.
	echo '<div class="filtredate"> <div style="text-align:left"><strong>Filtre par date</strong></div>';

	echo '<form id="FiltreDate" method="post" action="options.php" >';
	echo '<strong>saisir dates : jj/mm/aaaa</strong><br/>';
	
	echo '<table>';
	echo '<tr>';
	echo '<td class="td_organize">1ere date :</td><td class="td_organize"> <input type="text" style="width:80px" name="PremDate" id="PremDate" ';
	if(  $_SESSION['DateMin'] != "" )
		echo 'value="'.reverse_date($_SESSION['DateMin']).'"';
	echo '/></td><td class="td_organize"><a href="#" onclick="ds_sh(document.getElementById(\'PremDate\'));"><img src="images/date.gif" alt="Calendrier" style="border:0; width:30px; height:30px;"></a></td>';

	echo '</tr><tr><td class="td_organize"> 2eme date : </td><td class="td_organize"><input type="text" style="width:80px" name="DeuxDate" id="DeuxDate" ';
	if(  $_SESSION['DateMax'] != "" )
		echo 'value="'.reverse_date($_SESSION['DateMax']).'"';
	
	echo '/></td><td class="td_organize"><a href="#" onclick="ds_sh(document.getElementById(\'DeuxDate\'));"><img src="images/date.gif" alt="Calendrier" style="border:0; width:30px; height:30px;"></a></td><tr/>';
	echo '</tr></table><input type="submit" name="ChangeDate" value="Filtrer" onclick="valid_clients();"/>';
	
	if( isset( $_SESSION['DateMin'] ) AND isset( $_SESSION['DateMax'] ) )
		if( $_SESSION['DateMin'] != "" or  $_SESSION['DateMax'] != "" )
			echo ' <input type="submit" name="init" value="Annuler"/>';/* <a href="stat.php?init">D�sactiver le filtre </a>';*/
		
	echo '</form>';
	echo '</div>';
	
	// D�but des infos sur les dates pr�sentent dans la base
	echo '<div class="informations"> <strong>Informations</strong><br/>';

	$donnees1 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MIN( CallTime )as min FROM Table_InboundVoiceCalls_Blagnac"));	
	$donnees2 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MAX( CallTime )as max FROM Table_InboundVoiceCalls_Blagnac"));
	
	$DateMin = $donnees1['min']->format('d/m/Y'); 
	$DateMax = $donnees2['max']->format('d/m/Y'); 
	
	echo '<br/>Les dates pr�sentes dans la base s\'�chelonnent :<br/> du '.$DateMin.' au '.$DateMax.'<br/><br/>';

	echo '</div>';

	// Formulaire des options d'affichage
	echo '<div class="optionsaffichage"><div style="text-align:left;"> <strong>Options d\'affichage</strong></div><br/>';
	
		echo '<form method="post" id="wejf" name="wejf" action="options.php">'; 
		
		echo 'Week-Ends : <input type="radio" name="we" id="we_oui" value="we_oui" ';
		if( $_SESSION['force_we'] == 1 )
			echo 'checked="checked"';
		echo ' /> <label for="we_oui">Oui</label>';
		
		echo'<input type="radio" name="we" value="we_non" id="we_non" ';
		if( $_SESSION['force_we'] == 0 )
			echo 'checked="checked"';
		echo '/> <label for="we_non">Non</label><br/>';
		
		echo 'Jours F�ri�s : <input type="radio" name="jf" id="jf_oui" value="jf_oui"  ';
		if( $_SESSION['force_jf'] == 1 )
			echo 'checked="checked"';
		echo'/> <label for="jf_oui">Oui</label>';
			
		echo '<input type="radio" name="jf" value="jf_non" id="jf_non" ';
		
		if( $_SESSION['force_jf'] == 0 )
			echo 'checked="checked"';
		echo '/> <label for="jf_non">Non</label><br/>';
		
		echo 'TOP : <input type="text" name="TOP" value="'.$_SESSION['TOP'].'" style="width:30px;">';
		echo '<input type="submit" name="modifjfwe" id="modifjfwe" value="Modifier">
		<input type="submit" name="resetjfwe" id="resetjfwe" value="Annuler">
		</form>';		
	
	echo '</div>';
	
	
	// R�sum� de la requete en cours
	echo '<div class="requetecours"> <strong>Requ�te en cours</strong><br/>';
	if( $appli->stat->is_clients_select() )
	{
		$ma_requete = new requete();
	
		$i =0;
		while( $i < $groupe->liste_clients->count() )
		{
			$ma_requete->add_requete( $groupe->liste_clients[$i], new datetime( $tab_date_min[$i] ), new datetime( $tab_date_max[$i] ));
			$i++;
		}
	
		if( !isset( $_GET['total_requete'] ) )
			$ma_requete->resume_requete(3);
		else
			$ma_requete->resume_requete();
			
		echo '<form id="reset" method="post" action="options.php" >';
		if( !isset( $_GET['total_requete'] ) )
			echo '<a href="options.php?total_requete" title="Afficher la requ�te compl�te">...</a> ';
		echo '<input type="submit" name="reset" value="R�initialiser"/>';
		echo '</form>';
	}
	else
		echo "Il n'y a pas de requ�te en cours.<br/>";

	
	echo '</div>';
	
	
	
	echo '</fieldset>';
	// echo '</div>';


	///////////////////////////////////
	//////////// Affichage Equipes/Clients
	///////////////////////////////////////
	
	if( !isset( $_SESSION['DateMin'] ) or !isset( $_SESSION['DateMax'] ) or $_SESSION['DateMin'] == ""  or $_SESSION['DateMax'] == "")
	{
		echo '<div class="centre"><div class="cadre"><br/><h2>Merci de selectionner un intervalle de 2 dates.</h2></div></div>';
	}
	else
	{
		//////////////
		////////////// Affichage des �quipes.
		//////////////

		if( !$appli->stat->have_equipes_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] ) )
		{
			echo '<div class="centre"><div class="cadre"><br/><h2>Il n\'y a pas de clients pour les dates demand�es.</h2></div></div>';
		}
		else
		{
			echo '<fieldset style="border: 0px solid white">';
			echo '<div class="cadre" style="float:left"> <strong>Liste des clients disponibles </strong><br/>';	
			echo '<fieldset style="border: 0px solid white">';	
			echo '<form id="form" name="form" method="post" action="options.php" >';
			
			$i_equipe = 0;
			while( $i_equipe < $appli->stat->liste_equipes->count() )
			{
				//$equipe = $donnees1['equipe'];
				$equipe = $appli->stat->liste_equipes[$i_equipe];
				
				// SI l'�quipe a des clients de dispo ... alors on affiche.
				if( $equipe->have_clients_dispo( $_SESSION['DateMin'], $_SESSION['DateMin'] ))
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
						$text_dispo = "Disponibilit�s : du ".reverse_date($client->dispo_min)." au ".reverse_date($client->dispo_max);
						$dispo_client = $client->is_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] );
						
						// Si le client est dispo... on pr�scise par la suite.
						if( $dispo_client == FULL_DISPO or $dispo_client == ONLY_START_DISPO or $dispo_client == ONLY_END_DISPO or $dispo_client == END_START_NOT_DISPO )
						{
							echo '<div style="float:left" >'; // Permet de rassembler Coche et text ... sinon il se pt qu'une coche soit en haut a droite ... et le texte en bas a gauche.
							
							
							if( $client->is_select )
								echo '<input type="checkbox" id="'.$client.'_checkbox" name ="'.$client->nick.'_checkbox" checked="checked"/>';
							else
								echo '<input type="checkbox" id="'.$client.'_checkbox" name ="'.$client->nick.'_checkbox"/>';

							if( $dispo_client == FULL_DISPO )
							{
								echo '<label for="'.$client->nick.'" style="color:green; margin-right:15px;" title="'.$text_dispo.'" >'.$client."</label>";
							}
							else if( $dispo_client == ONLY_START_DISPO  or $dispo_client == ONLY_END_DISPO )
							{
								echo '<label for="'.$client->nick.'" style="color:orange; margin-right:15px;" title="'.$text_dispo.'" >'.$client."</label>";
							}
							else if( $dispo_client == END_START_NOT_DISPO )
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
					
					echo '<input type="hidden" id="'.$equipe.'_text" value="'.$RecapClient.'"/>';

					echo "</fieldset>";
				}
				$i_equipe++;
			}
			
			
			echo '</fieldset>';	
			echo '<fieldset style="border:0px solid white">';	
			echo '<input type="submit" name="valider" value="Valider" onclick="montre_div(\'message\');" style="float:right"/>';
			if( $appli->stat->is_clients_select() )
				echo '<input type="submit" name="ajouter" value="Ajouter" onclick="montre_div(\'message\');" style="float:right"/>';
			echo '</fieldset>';	
			echo '</form>';
			
			echo '</div>';
			echo '</fieldset>';	
		}
		// Message qui demande de patienter lorsqu'on demande les stats.
		AffichMsgAttente();	
		
/* 		///////////////////////
		///// Pour Test
		$value = 0.5;
		
		$tab = new ClassArray();
		$tab[0] = 0;
		$tab[1] = 1;
		$tab[2] = 2;
		$tab[3] = 3;
		$tab[4] = 4;
		$tab[5] = 5;
		
		$offset = $tab->where_insert($value);
		
		echo " Affichage du tableau ".$tab."<br/>";
		echo " $value est a inserer � l'indice ".$offset."<br/>";
		
		$tab->insert( $value );
		echo " Affichage du tableau apres insertion".$tab."<br/>";
		
		
		///////////////////////
 */		
		
		
		
		// Si le groupe possede des clients ... c'est qu'il a �t� demand� des stats.
		if( $appli->stat->is_clients_select() != 0 )
		{
			// Regroupement de la requete.
			$ma_requete = new requete();
			$i =0;
			while( $i < $groupe->liste_clients->count() )
			{
				$ma_requete->add_requete( $groupe->liste_clients[$i], new datetime( $tab_date_min[$i] ), new datetime( $tab_date_max[$i] ));
				$i++;
			}
	
			// Pr�paration du tableau contenant les appels pour les clients et la p�riode demand�e.
			$tab_vrac_appels = new ClassArray(); 
			$ma_requete->put_calls_tab( $tab_vrac_appels );

			////////////////////////////////////////////////////////////////
			// Calcul du nombre de fois qu'un num�ro de t�l�phone appelle.
			echo '<div class = "cadre"><strong>Les '.$_SESSION['TOP'].' num�ros qui ont le plus appel�.</strong><br/>';
			$result = $ma_requete->get_combien_fois_num_appelle( $tab_vrac_appels );
			
			$bool = true;
			$compte = 1; //sinon compte 1 de plus que le TOP
			while ($bool && (list($Numero, $Nb_Appels) = each($result)))
			{
				if( $Numero != '' )
				{
					echo EspaceNum($Numero).' a appel� '.$Nb_Appels.' fois<br>';
					if($compte == $_SESSION['TOP']) 
						$bool = false; 
					
					$compte++;
				}
			}	
			echo '</div>';



			////////////////////////////////////////////////////////////////
			// Calcul du nombre d'appel d�croch� par op�rateur.
			echo '<div class = "cadre"><strong>Les '.$_SESSION['TOP'].' op�rateurs qui ont le plus d�croch�.</strong><br/>';
			$TabOccurencesOpe = $ma_requete->get_combien_decro_operateur( $tab_vrac_appels );
			
			$bool = true;
			$compte = 1; //sinon compte 1 de plus que le TOP
			while ($bool && (list($Operateur, $Nb_Appels) = each($TabOccurencesOpe)))
			{
				if( $Operateur != '' )
				{
					echo $Operateur.' a d�croch� '.$Nb_Appels.' fois<br>';
					if($compte == $_SESSION['TOP']) 
						$bool = false; 
					
					$compte++;
				}
			}	
			echo '</div>';

				
			////////////////////////////////////////////////////////////////
			// Calcul des appels qui ont attendu le plus longtemps
			echo '<div class = "cadre"><strong>Les '.$_SESSION['TOP'].' appels qui ont attendu le plus longtemps.</strong><br/>';
			$result = new ClassArray();
			$ma_requete->get_appels_attendu_plus_longtemps( $tab_vrac_appels, $result );
			
/* 			/////// Pour test
			echo "Tous les op� renseign� :<br/>";
			$i=0;
			while( $i < $result->count() )
			{
				echo $result[$i][3]."<br/>";
				$i++;
			} */
			
			
			$i = 0;
			while( $i < $_SESSION['TOP'] )
			{
				// Si pas d'op�rateur c'est que l'utilisateur a raccroch�.
				if( $result[$i][3] != '' )
					echo 'Le num�ro '.EspaceNum($result[$i][1]).' a attendu '.NumToHour($result[$i][2]).' avant que l\'op�rateur '.$result[$i][3].' d�croche.<br/>';
				else
					echo 'Le num�ro '.EspaceNum($result[$i][1]).' a attendu '.NumToHour($result[$i][2]).' avant de raccrocher.<br/>';
				$i++;
			}
			echo '</div>';
			
			
			////////////////////////////////////////////////////////////////
			// Calcul des appels qui ont attendu le plus longtemps
			echo '<div class = "cadre"><strong>Les '.$_SESSION['TOP'].' appels qui ont dur� le plus longtemps.</strong><br/>';
			$result = new ClassArray();
			$ma_requete->get_appels_les_plus_long( $tab_vrac_appels, $result );
			
			$i = 0;
			while( $i < $_SESSION['TOP'] )
			{
				echo 'L\'op�rateur '.$result[$i][3].' est rest� '.NumToHour($result[$i][2]).' avec le num�ro '.EspaceNum($result[$i][1]).'<br/>';
				$i++;
			}
			echo '</div>';
			
			
			////////////////////////////////////////////////////////////////
			// Calcul Moyenne de temps d\'attente avant d�croch� par op�rateur
			echo '<div class = "cadre"><strong>Moyenne de temps d\'attente avant d�croch� par op�rateur</strong><br/>';
			$result = new ClassArray();
			$ma_requete->get_moyennes_ope( $tab_vrac_appels, $TabOccurencesOpe, $result );
			
			$i = 0;
			while( $i < $result->count() )
			{
				if( $result[$i][0] != '' )
					echo 'L\'op�rateur '.$result[$i][0].' d�croche en moyenne apres '.number_format($result[$i][1], 0, '.', ' ').'s d\'attente.<br/>';
				$i++;
			}
			
			echo '</div>';
			
			////////////////////////////////////////////////////////////////
			// Moyenne de temps de communication par op�rateur
			echo '<div class = "cadre"><strong>Moyenne de temps de communication par op�rateur</strong><br/>';
		
			$i = 0;
			while( $i < $result->count() )
			{
				if( $result[$i][0] != '' )
					echo 'L\'op�rateur '.$result[$i][0].' reste en moyenne '.NumToHour($result[$i][2]).' en communication.<br/>';
				$i++;
			}
			
			echo '</div>';
			
		}
	}
	
	// $_SESSION['requete'] = serialize( $ma_requete ); // trop volumineu pour etre serializ�
	$_SESSION['groupe'] = serialize( $groupe );
	$_SESSION['tab_date_max'] = serialize( $tab_date_max );
	$_SESSION['tab_date_min'] = serialize( $tab_date_min );
	$_SESSION['appli'] = serialize( $appli );
?>

</body>
</html>