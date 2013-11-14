<?php require_once('D:/donnees/Dropbox/sources/bases_perso/www/fern.php');
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_31");
 session_start();
	require_once("class.php");
	
	// Si variable de session non initialis� => utilisateur a demand� la page stat.php en direct sans passer par l'index ... donc on le r�oriente.
	if( !isset( $_SESSION['bd_serveur'] ) )
		header("Location:index.php");

		
		
		
		
	
	// Connexion aux bases de donn�es
	mysql_connect( $_SESSION['bd_serveur'], $_SESSION['bd_user'], $_SESSION['bd_pwd']) or die ("Connexion au serveur MySql impossible");
	mysql_select_db($_SESSION['bd_base']) or die ("Connexion � la base MySql impossible");
	
	$connectionInfo = array( "UID"=>$_SESSION['odbc_user'], "PWD"=>$_SESSION['odbc_pwd'], "Database"=>$_SESSION['odbc_base']);
	$_SESSION['odbc_connect'] = sqlsrv_connect( "frsv001355", $connectionInfo);
	if( $_SESSION['odbc_connect'] === false )
	{
		echo "Impossible de se connecter � la base 7480.</br>";
		die( print_r( sqlsrv_errors(), true));
	}
	
	// R�cup�ration des classes si elles ont �t� cr��s.
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


	// On v�rifie le dernier appel enregistr�, si plus vieux d'une heure ... on met une alerte(popup) indiquant un risque de probleme sur la base SQL.
	$is_alert = check_srv_sql_up();

$nbsessions = active_session();
$place_mysql = "sur le serveur central";
if( $_SESSION['bd_serveur'] == $_SESSION[ 'bd_serveur_bk' ] )
	$place_mysql = "sur le serveur de backup local";
	
echo "Il y a <b>$nbsessions</b> personne(s) connect�e(s) $place_mysql<br/>";


// Gestion du mode d�bug.
if( $_SESSION['debug_mode'] ){
	echo "Debug_mode : ON<br/>";
	echo "Crash_mode : ";
	if( $_SESSION['run_crash'] ){
		echo "ON<br/>";
		echo "Updated : ".$_SESSION['file_updated']."<br/>";
	}else
		echo "OFF<br/>";
}

////////////////////////////////////////////////////////////////////////////////
//////////////////		reception des variables	   		////////////////////////
////////////////////////////////////////////////////////////////////////////////

	
	if( isset( $_POST['resetjfwe'] ) )
	{
		header( "Location:index.php" );
	}
	
	if( isset( $_POST['reset'] ))
	{	
		$groupe->liste_clients->vider();
		$tab_date_max->vider();
		$tab_date_min->vider();
		$appli->stat->unselect_clients();
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Easy-Stat</title>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
	<link rel="shortcut icon" type="image/x-icon" href="images/esico.ico" />
	<link rel="icon" type="image/png" href="images/esico.ico" />
	
	
	<script type="text/javascript">
	function writediv(div_id, texte)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_30");

		document.getElementById(div_id).innerHTML = texte;
	}
	function affich_div(script, div_id)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_29");

		methode = "POST";
		mode = 0;
		donnees = '';
		
//		writediv(div_id, '<br/><br/><br/><div style="text-align:center"><h3>En cours de chargement</h3><img src="images/vtbusy.gif" height="32" width="32"/><br/><br/><br/><br/></div>');
	  // D�claration de notre objet ajax
	  var xhr_object = null;
	   
	  // On d�clare la variable de r�sultat
	  var resultat = null;
	 
	  // Contr�le de la compatibilit� navigateur
	  if(window.XMLHttpRequest)
	  {
		// Firefox
		xhr_object = new XMLHttpRequest();
	  }
	  else if(window.ActiveXObject)
	  {
		// Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP" );
	  }
	  else
	  {
		// XMLHttpRequest non support� par le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..." );
		return;
	  }
	 
	  // Si on a choisi le mode synchrone
	  if(mode == 1)
	  {
		 xhr_object.open(methode, script, false);
		 
		 if(methode.toUpperCase() == "POST" )
		 {
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded" );
		 }
	 
		 xhr_object.send(donnees);
		 resultat = xhr_object.responseText;
	  }
	  else
	  {
		 xhr_object.open(methode, script, true);
		 xhr_object.onreadystatechange = function()
		{
			if(xhr_object.readyState == 4)
			{
				writediv( div_id, xhr_object.responseText);					
			}
		}
										 
		 if(methode.toUpperCase() == "POST" )
		 {
			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded" );
		 }
	 
		 xhr_object.send(donnees);
	  }
	   
	  //return resultat;
	//	writediv(div_id, 'Donn�es recus Synch: ' + resultat);
	}
	
	function verifClientDispo()
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_28");

		writediv( 'clients_dispo', '<div class="cadre" ><strong>Liste des clients disponibles </strong><br/><br/><br/><div style="text-align:center"><h3>En cours de chargement</h3><img src="images/vtbusy.gif" height="32" width="32"/><br/><br/><br/><br/></div></div>');

		var date1 = document.getElementById('PremDate').value;
		var date2 = document.getElementById('DeuxDate').value;

		if( date1.length == 10 && date2.length == 10 ) // Si le texte fait la taille d'une date
		{
			affich_div('Affiche_clients_dispo.php?ChangeDate=1&PremDate='+date1+'&DeuxDate='+date2, 'clients_dispo');
		}
		else
			writediv('clients_dispo', '<div class="cadre"><strong>Liste des clients disponibles </strong><br/><h2>Merci de selectionner un intervalle de 2 dates.</h2></div><br/><br/><br/><br/><br/><br/><br/>');
	}

	function get_stats()
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_27");

		//document.write("merrrrde");
		writediv( 'affiche_result', '<div class="cadre" > <strong>R�sultats : </strong><br/><br/><br/><div style="text-align:center"><h3>En cours de chargement</h3><img src="images/vtbusy.gif" height="32" width="32"/><br/><br/><br/><br/></div></div>');

	//id : ts_client_text
		var adresse = "affiche_stat.php";
		var ligne_cmd = "?";
		
		// On r�cup�re la textbox dans laquelle il y a tout les clients.
		LaChaine = document.getElementById('ts_client_text').value;
		
		//On r�cup�re les Clients de la textbox.
		Pos_d = 0;
		Pos_f = 0;
		// client = "";
		// client_checkbox = "";
		var is_one_checked = false;
		while( LaChaine.indexOf("+", Pos_f) != -1 )
		{
			Pos_d = LaChaine.indexOf("+", Pos_f);
			Pos_f = LaChaine.indexOf("=", Pos_d);
			LeClient = LaChaine.substring( Pos_d+1, Pos_f);
			LeClient_checkbox = LeClient + "_checkbox";	
				
			if( document.getElementById(LeClient_checkbox).checked == true )
			{
				//cochetout(LeClient, 1);
				//document.getElementById(LeClient_checkbox).checked = true;
				ligne_cmd = ligne_cmd + "&" + LeClient_checkbox + "=true";
				is_one_checked = true;
			}	
		}

		if( is_one_checked )
		{
			ligne_cmd = ligne_cmd + "&" + 'valider' + "=true";
				
			affich_div( adresse + ligne_cmd, 'affiche_result' );
		}
	}

	function affiche_requete()
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_26");
	
		var Total = 'no';
		writediv( 'requete_cours', '<div class="requetecours" > <strong>Requ�te en cours </strong><br/><br/><div style="text-align:center"><h3>En cours de chargement</h3><img src="images/vtbusy.gif" height="32" width="32"/>');

		var attrib = '';
		if( Total != 'no' )
			attrib = '?total_requete=true';
			
		affich_div('affiche_requete.php'+attrib, 'requete_cours');
	}
	
	function modif_visualisation()
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_25");

		var attrib = '';
		if( document.getElementById('we_oui').checked )
			attrib = attrib + 'we=we_oui&';
		if( document.getElementById('jf_oui').checked )
			attrib = attrib + 'jf=jf_oui&';
		
		affich_div('modif_visualisation.php?modifjfwe&'+attrib, 'div_test');	
		get_stats();
	}

	function montre_div(nom_div)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_24");

		if( document.getElementById(nom_div).style.display == "none" )
			document.getElementById(nom_div).style.display="block";
		else
			document.getElementById(nom_div).style.display="none";
	}
	function montre_tab(TabClient)
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_23");

		TextBox = "text_" + TabClient;
		
		//document.write(TextBox); 
		
		Nb_Jours = document.getElementById(TextBox).value;
		
		//document.write(Nb_Jours); 
		
		LeJour = 0;
		while( LeJour < Nb_Jours )
		{
			Ligne = TabClient + LeJour;
			if( document.getElementById(Ligne).style.display == "none" )
				document.getElementById(Ligne).style.display="block";
			else
				document.getElementById(Ligne).style.display="none";
			
			LeJour++;
		}
	}
	function cocheequipe( equipe, IsCheck )
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_22");

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
	function alert_popup( page )
	{
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_21");

		window.open( page,'_blank','toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=1, copyhistory=0, menuBar=0, width=730, height=230');
		return(false);
	}
	//-->
	</script>
	
<style type="text/css">



</style>
</head>
<body <?php if( $is_alert ) echo ' onLoad="alert_popup(\'alert.php?time='.$is_alert.'\')"' ?> >



<?php
	

////////////////////////////////////////////////////////////////////////////////
////////////////////////	Affichage du menu		/////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	AffichMenu();
	
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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_20");

	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_19");

	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
function ds_gettop(el) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_18");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_17");

	ds_ob = '';
}
function ds_ob_flush() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_16");

	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_15");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_14");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_13");

	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_12");

	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_11");

	return '<td class = "ds_blank_cell" colspan="' + colspan + '" ></td>'
}

function ds_template_day(d, m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_10");

	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_9");

	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_8");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_7");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_6");

	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_5");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_4");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_3");

	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_2");

	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_1");

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
Annotation("D:\\donnees\\Dropbox\\sources\\bases_perso\\www\\anciens_projets\\es\\easy-stat_v4.6.11.05 Proper\\stat.php_0");

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
	
	verifClientDispo();
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
	echo '<div class="filtredate"> <div style="text-align:left"><strong>Filtre par date</strong></div>';

	echo '<form id="FiltreDate" method="post" action="stat.php" >';
	echo '<strong>saisir dates : jj/mm/aaaa</strong><br/>';
	
	echo '<table>';
	echo '<tr>';
	echo '
	<td class="td_organize">1ere date :</td><td class="td_organize"> <input type="text" style="width:80px" name="PremDate" id="PremDate" onKeyUp="javascript:verifClientDispo();return false"';
	if(  $_SESSION['DateMin'] != "" )
		echo 'value="'.reverse_date($_SESSION['DateMin']).'"';
	echo '/></td><td class="td_organize"><a href="#" onclick="ds_sh(document.getElementById(\'PremDate\'));"><img src="images/date.gif" alt="Calendrier" style="border:0; width:30px; height:30px;"></a></td>';

	echo '
	</tr><tr><td class="td_organize"> 2eme date : </td><td class="td_organize"><input type="text" style="width:80px" name="DeuxDate" id="DeuxDate" onKeyUp="javascript:verifClientDispo();return false"';
	if(  $_SESSION['DateMax'] != "" )
		echo 'value="'.reverse_date($_SESSION['DateMax']).'"';
	
	echo '/></td><td class="td_organize"><a href="#" onclick="ds_sh(document.getElementById(\'DeuxDate\'));"><img src="images/date.gif" alt="Calendrier" style="border:0; width:30px; height:30px;"></a></td><tr/>';
	echo '</tr></table>';//<input type="submit" name="ChangeDate" value="Filtrer" />';
	
	// if( isset( $_SESSION['DateMin'] ) AND isset( $_SESSION['DateMax'] ) )
		// if( $_SESSION['DateMin'] != "" or  $_SESSION['DateMax'] != "" )
			// echo ' <input type="submit" name="init" value="Annuler"/>';/* <a href="stat.php?init">D�sactiver le filtre </a>';*/
		
	echo '</form>';

	echo '</div>';

	
	// D�but des infos sur les dates pr�sentent dans la base
	echo '<div class="informations"> <strong>Informations</strong><br/>';

	$donnees1 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MIN( CallTime )as min FROM Table_InboundVoiceCalls_Blagnac"));	
	$donnees2 = sqlsrv_fetch_array(sqlsrv_query($_SESSION['odbc_connect'], "SELECT MAX( CallTime )as max FROM Table_InboundVoiceCalls_Blagnac"));
	
	$DateMin = $donnees1['min']->format('d/m/Y'); 
	$DateMax = $donnees2['max']->format('d/m/Y'); 
	
	echo '<br/>Les dates s\'�chelonnent :<br/> du '.$DateMin.' au '.$DateMax.'<br/><br/>';

	echo '</div>';

	// Formulaire des options d'affichage
	echo '<div class="optionsaffichage"> <div style="text-align:left"><strong>Options d\'affichage</strong></div>';
	
		echo '<form method="post" id="wejf" name="wejf" action="stat.php">'; 
		
		/////////// Les WE
		echo '<table>
			<tr><td class="td_organize">Week-Ends : </td><td class="td_organize"><input type="radio" name="we" id="we_oui" value="we_oui" ';
		if( $_SESSION['force_we'] == 1 )
			echo 'checked="checked"';
		echo ' /><label for="we_oui">Oui</label>';
		
		echo'</td><td class="td_organize"><input type="radio" name="we" value="we_non" id="we_non" ';
		if( $_SESSION['force_we'] == 0 )
			echo 'checked="checked"';
		echo '/><label for="we_non">Non</label></td></tr>';
		
		//////////// Les JF
		echo '<tr><td class="td_organize">Jours F�ri�s :</td><td class="td_organize"><input type="radio" name="jf" id="jf_oui" value="jf_oui"  ';
		if( $_SESSION['force_jf'] == 1 )
			echo 'checked="checked"';
		echo'/><label for="jf_oui">Oui</label>';
			
		echo '</td><td class="td_organize"><input type="radio" name="jf" value="jf_non" id="jf_non" ';
		
		if( $_SESSION['force_jf'] == 0 )
			echo 'checked="checked"';
		echo '/><label for="jf_non">Non</label></td></tr>';
		
		//////////////// D�ployer les r�sultats
		echo '<tr><td class="td_organize">D�p. R�sult. : </td><td class="td_organize"><input type="radio" name="deploy" id="deploy_oui" value="deploy_oui"  ';
		if( $_SESSION['deploy_result'] == 1 )
			echo 'checked="checked"';
		echo'/><label for="deploy_oui">Oui</label>';
			
		echo '</td><td class="td_organize"><input type="radio" name="deploy" value="deploy_non" id="deploy_non" ';
		
		if( $_SESSION['deploy_result'] == 0 )
			echo 'checked="checked"';
		echo '/><label for="deploy_non">Non</label></td></tr></table>';

		////////// Les boutons
		echo '<input type="button" value="Modifier"  name="valider"  style="float:right" onclick="modif_visualisation();return false"/>';
//		echo '<input type="submit" name="modifjfwe" id="modifjfwe" value="Modifier">';
		echo '<input type="submit" name="resetjfwe" id="resetjfwe" value="Annuler">';
		echo '</form>';		
	
	echo '</div>';
	
	
	// R�sum� de la requete en cours
	echo '<div id="requete_cours" class="requete_cours"> Requ�te en cours :';
	echo '</div>';
	
	// echo '<div id="div_test" class="requete_cours"> test affiche : ';
	// echo '</div>';
	
	
	
	echo '</fieldset>';
	// echo '</div>';


	///////////////////////////////////
	//////////// Affichage Equipes/Clients
	///////////////////////////////////////

	// Message qui demande de patienter lorsqu'on demande les stats.
//	AffichMsgAttente();	

	echo '<div id="clients_dispo">';
		echo '<div class="cadre" > <strong>Liste des clients disponibles </strong><br/><br/><br/>';
		echo '<div style="text-align:center"><h3>Veuillez s�lectionner 2 dates</h3><br/><br/><br/><br/></div></div>';
	echo '</div>';
	
	


	if( !isset( $_SESSION['DateMin'] ) or !isset( $_SESSION['DateMax'] ) or $_SESSION['DateMin'] == ""  or $_SESSION['DateMax'] == "")
	{
		// echo '<div class="centre"><div class="cadre"><br/><h2>Merci de selectionner un intervalle de 2 dates.</h2></div></div>';
		// echo '<br/><br/><br/><br/><br/><br/><br/>';
	}
	else
	{
		if( !$appli->stat->have_equipes_dispo( $_SESSION['DateMin'], $_SESSION['DateMax'] ) )
		{
			//echo '<div class="centre"><div class="cadre"><br/><h2>Il n\'y a pas de clients pour les dates demand�es.</h2></div></div>';
		}
		else
		{
			// Si le groupe possede des clients ... c'est qu'il a �t� demand� des stats.
			if( $appli->stat->is_clients_select() != 0 )
			{
				// echo '<div id="affiche_result">';
				// echo '</div>';


				echo ' <script type="text/javascript">
							get_stats();
						</script>';
			}
		}	
	}

			echo '<div id="affiche_result">';
			echo '</div>';
	AffichPied();
	
	// $_SESSION['requete'] = serialize( $ma_requete );
	$_SESSION['groupe'] = serialize( $groupe );
	$_SESSION['tab_date_max'] = serialize( $tab_date_max );
	$_SESSION['tab_date_min'] = serialize( $tab_date_min );
	$_SESSION['appli'] = serialize( $appli );
		
	
 	echo '<!-- Permet d\'afficher la requete en cours -->
				<script type="text/javascript">
				affiche_requete();
				</script>';
	echo '<!-- Permet d\'afficher la disponibilit� des clients ou une fenetre indiquant de selectionner 2 dates -->
			<script type="text/javascript">
			verifClientDispo();
			</script>';
			
	phpmv2();
 ?>


</body>
</html>