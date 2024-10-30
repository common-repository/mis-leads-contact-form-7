<?php 
/*
Plugin Name: Mis Leads - Contact Form 7
Plugin URI: http://www.misleads.es/
Description: Contecta formularios de Contact Form 7 con Mis Leads
Author: ohayoweb
Version: 1.1
Author URI: http://www.ohayoweb.com/?utm_source=wordpress&utm_medium=plugin_uri&utm_campaign=wordpress_plugins&utm_term=mis_leads_cf7
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

include('includes/class.misleads-cf7.php');


function run_Mis_Leads_CF7_Plugin() {

	$misLeads = Mis_Leads_CF7::init();

}
run_Mis_Leads_CF7_Plugin();


?>
