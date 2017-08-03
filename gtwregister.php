<?php
/*
Plugin Name: GoToWebinar Registration
Plugin URI: http://www.asandia.com/wordpress-plugins/gtwregister/
Description: Short Code to take URL parameters and process a GoToWebinar webinar registration
Version: 1.2
Author: Jeremy B. Shapiro
Author URI: http://www.asandia.com/wordpress-plugins/
*/

/*
GoToWebinar Registration (Wordpress Plugin)
Copyright (C) 2011-2012 Jeremy B. Shapiro
*/

//tell wordpress to register the demolistposts shortcode
add_shortcode("gtwregister", "gtwregister");

// patches for upgrades
if(!get_option('gtw_servernum')) {
  update_option('gtw_servernum', 2);
}
if(!get_option('gtw_field_servernum')) {
  update_option('gtw_field_servernum', 'ServerNum,_ServerNum,inf_custom_ServerNum,Contact0_ServerNum,GTWServerNum,Contact0_GTWServerNum,inf_custom_GTWServerNum');
}


function gtwregister($atts) {
  $atts = shortcode_atts(array(
        'servernum' 	      	=> '',
        'gtwid' 	      	=> '',
	"firstname" 		=> '',
	"lastname"		=> '',
	"email"			=> '',
	"phone"			=> '',
	'commenterror'		=> 0,
	'commentsuccess'	=> 1,
	'url'			=> ''
        ), $atts);

  foreach(array('firstname', 'lastname', 'email', 'phone', 'servernum', 'gtwid') as $field)
  {
	foreach(preg_split('/\,\s*/', get_option('gtw_field_'.$field) ) as $param)
	{
		if(!$atts[$field] && $_REQUEST[$param])
		{
			$atts[$field] = $_REQUEST[$param];
		}
	}
  }

  # If the servernum wasn't hard coded or passed as a param, then use the default servernum
  $atts['servernum'] = $atts['servernum'] ? $atts['servernum'] : get_option('gtw_servernum');

  # If the url wasn't already set, Now that we have the server num, let's set our URL
  $atts['url'] = $atts['url'] ? $atts['url'] : "https://www".$atts['servernum'].".gotomeeting.com/en_US/island/webinar/registration.flow";

  $postvars = array(
	"Name_First" 	=> $atts['firstname'],
	"Name_Last"	=> $atts['lastname'],
	"Email"		=> $atts['email'],
	"PhoneNumber"	=> $atts['phone'],
	"WebinarKey"	=> $atts['gtwid'],
	"Template"	=> "island/webinar/registration.tmpl",
	"Form"		=> 'webinarRegistrationForm',
	"ViewArchivedWebinar" =>	"false",
	"registrant"	=> '',
	"RegistrantTimeZoneKey"	=> 61
	);

  $error .= ((!$atts['gtwid']) ? "Webinar ID is required. ":'');
  $error .= ((!$atts['firstname']) ? "First Name is required. ":'');
  $error .= ((!$atts['lastname']) ? "Last Name is required. ":'');
  $error .= ((!$atts['email']) ? "Email is required. ":'');
  $error .= ((!$atts['servernum'] || !is_numeric($atts['servernum'])) ? "A Valid Server Number is required. ":'');
	
  if (!$error)
  {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $atts['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
#	curl_setopt($ch, CURLOPT_HEADER, true); // Display headers
#	curl_setopt($ch, CURLOPT_VERBOSE, true); // Display communication with server

  	$result = curl_exec($ch);

  	if (curl_errno($ch))
  	{
		$error = curl_error($ch);
  	} elseif (preg_match('/Webinar Unavailable/i', $result)) {
		$error = "Webinar unavailable";
  	} elseif (preg_match('/This Webinar is over/i', $result)) {
		$error = "This webinar is already over";
  	} elseif (preg_match('/Please correct the fields marked in red/', $result, $matches)) {
		$error = $matches[0];
 	}
  } # all required fields

  if ($error) {
	return ($atts['commenterrors']) ? "<!-- Error: $error -->" : "<div class=\"gtw-error\">Error: $error</div>";
  }

# return "<pre>All went well. Here is what I got back: $result ".print_r(curl_getinfo($ch), true)."</pre>"; 

  curl_close($ch); 
  return ($atts['commentsuccess']) ? "<!-- Registered! -->" : "<div class=\"gtw-success\">Registered!</div>";
}

function gtwregister_ajaxreg() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://asandia.infusionsoft.com/app/form/process/bd263214d17cb0c6ee09e53ac57fcc65');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  	$result = curl_exec($ch);
	if (preg_match('/app\/form\/success\//', $result)) {
		echo "Success";
		update_option('gtw_registered', md5($_POST['inf_field_Website']));
	} else {
		echo "Error!";
	}
	die(); // this is required to return a proper result
}


function gtwregister_isregistered() {
	return (md5(get_bloginfo('url')) == get_option('gtw_registered'));
}

function activate_gtwregister() {
  add_option('gtw_servernum', 2);
  add_option('gtw_field_firstname', 'FirstName,Contact0FirstName,inf_field_FirstName');
  add_option('gtw_field_lastname', 'LastName,Contact0LastName,inf_field_LastName');
  add_option('gtw_field_email', 'Email,Contact0Email,inf_field_Email');
  add_option('gtw_field_phone', 'Phone,Phone1,Contact0Phone1,inf_field_Phone1');
  add_option('gtw_field_servernum', 'ServerNum,_ServerNum,inf_custom_ServerNum,Contact0_ServerNum,GTWServerNum,Contact0_GTWServerNum,inf_custom_GTWServerNum');
  add_option('gtw_field_gtwid', 'GoToWebinarID,WebinarID,GTWID,_GoToWebinarID,_WebinarID,_GTWID,inf_custom_GoToWebinarID,inf_custom_WebinarID,inf_custom_GTWID');
}

function deactivate_gtwregister() {
  # for now, deactivate shouldn't do anything
}

function uninstall_gtwregister() {
  delete_option('gtw_servernum');
  delete_option('gtw_field_firstname');
  delete_option('gtw_field_lastname');
  delete_option('gtw_field_email');
  delete_option('gtw_field_phone');
  delete_option('gtw_field_servernum');
  delete_option('gtw_field_gtwid');
  delete_option('gtw_registered');
}

function admin_init_gtwregister() {
  register_setting('gtwregister', 'gtw_servernum', 'absint');
  register_setting('gtwregister', 'gtw_field_firstname');
  register_setting('gtwregister', 'gtw_field_lastname');
  register_setting('gtwregister', 'gtw_field_email');
  register_setting('gtwregister', 'gtw_field_phone');
  register_setting('gtwregister', 'gtw_field_servernum');
  register_setting('gtwregister', 'gtw_field_gtwid');
}

function options_page_gtwregister() {
  include(dirname(__FILE__).'/options.php');
}

function admin_menu_gtwregister() {
  add_options_page('GTW Register', 'GTW Register', 8, 'gtwregister', 'options_page_gtwregister');
} 

function gtwregister_plugin_action_links( $links, $file ) {
        if ( $file == plugin_basename( dirname(__FILE__).'/gtwregister.php' ) ) {
                $links[] = '<a href="options-general.php?page=gtwregister">'.__('Settings').'</a>';
		if(!gtwregister_isregistered()) {
			$links[] = '<a href="options-general.php?page=gtwregister#register">'.__('Register').'</a>';
		}
        }
    
        return $links;
}
 
add_filter( 'plugin_action_links', 'gtwregister_plugin_action_links', 10, 2 );

if (is_admin()) {
  add_action('admin_init', 'admin_init_gtwregister');
  add_action('admin_menu', 'admin_menu_gtwregister');
  add_action('wp_ajax_gtwregister_reg', 'gtwregister_ajaxreg');
}

register_activation_hook(__FILE__,     'activate_gtwregister');
register_deactivation_hook(__FILE__, 'deactivate_gtwregister');
register_uninstall_hook(__FILE__, 'uninstall_gtwregister');

?>
