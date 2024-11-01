<?php 
/**
* Plugin Name:wp shortcut link and advertisement baner 
* Plugin URI: 
* Description:An plugin  to create a shortcut link
* Version: 1.2.0
* Author:Behzad rohozadeh
*
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPSLAB_CatShortcode' ) ) :
class WPSLAB_CatShortcode 
{
	function __construct()
	{
	  register_activation_hook( __FILE__,array(&$this, 'wpslab_activate_pliugin' )); 
	  add_action('plugins_loaded',array(&$this, 'wpslab_localization_init_textdomain'));
  
     include_once('menueadmin.php');
     include_once('wp-block.php');
     include_once('shortcode.php');
     include_once('client.php');

	}

	function wpslab_localization_init_textdomain()
	{
		$path = dirname(plugin_basename( __FILE__ )) . '/lang/';
	    $loaded = load_plugin_textdomain( 'wpslab', false, $path);
	}


	function wpslab_activate_pliugin() 
	{
		 global $wpdb;
         $table=$wpdb->prefix."wpslab";
		  if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) 
		  {
			$wpdb->query("CREATE TABLE `$table` (
				 `id_wpslab` int(255) NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `jsdata` longtext COLLATE utf8mb4_bin,
				   PRIMARY KEY (`id_wpslab`)
				)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin");

		    }



		     $table=$wpdb->prefix."wpslab_client";
		  if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) 
		  {
			$wpdb->query("CREATE TABLE `$table` (
				  `id` int(255) NOT NULL AUTO_INCREMENT,
				  `id_wpslab` int(255) NOT NULL,
				  `urlpage` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `classel` varchar(255) CHARACTER SET utf8 NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");

		    }


	}
}

new WPSLAB_CatShortcode();
endif;?>