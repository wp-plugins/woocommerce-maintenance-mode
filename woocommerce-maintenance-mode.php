<?php
/*
   Plugin Name: WooCommerce Maintenance Mode
   Version: 1.1
   Description: Add a message or redirect on Woocommerce pages only, not affecting any other parts of your website. Logged in admins will not see anything.
   Plugin URI: http://www.mattroyal.co.za/plugins/woocommerce-maintenance-mode/
   Author: Matt Royal
   Author URI: http://www.mattroyal.co.za/
   Requires at least: 3.8
   Tested up to: 3.9.1
   Text Domain: woocommerce-maintenance-mode
   License: GPLv3
  */

if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
	function woocommerce_maintmode_i18n_init() {
		$pluginDir = dirname(plugin_basename(__FILE__));
		load_plugin_textdomain('woocommerce-maintenance-mode', false, $pluginDir . '/lang/');
	}
	
	woocommerce_maintmode_i18n_init();
	
	
	// Add Settings Page Link
	function woocommerce_maintmode_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=woocommerce_maintmode_plugin_options">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}
	
	$plugin = plugin_basename( __FILE__ );
	
	add_filter( "plugin_action_links_$plugin", 'woocommerce_maintmode_settings_link' );
	
	
	// include plugin settings page/options
	require_once ( "admin/admin.php" );
	
	
	// Display admin notice when activated
	$options = get_option('woo_maint');
		
	if ( $options['activation'] == 1 ) {
		
		function woocommerce_maintmode_admin_notice() {
			
			global $current_user ;
			$user_id = $current_user->ID;
			
			if ( ! get_user_meta($user_id, 'woocommerce_maintmode_ignore_notice') ) {
				echo '<div class="error"><p>';
				printf(__('WooCommerce Maintenance/Message mode is Active! | <a href="options-general.php?page=woocommerce_maintmode_plugin_options">Turn Off</a><!-- | <a href="%1$s">Hide Notice</a> -->'), '?woocommerce_maintmode_nag_ignore=0');
				echo "</p></div>";
			}
		}
		
		add_action('admin_notices', 'woocommerce_maintmode_admin_notice');
		
			/**
			// Allow admin notice to be dismissed 
			function woocommerce_maintmode_nag_ignore() {
			
			global $current_user;
			$user_id = $current_user->ID;
			
			if ( isset($_GET['woocommerce_maintmode_nag_ignore']) && '0' == $_GET['woocommerce_maintmode_nag_ignore'] ) {
				 add_user_meta($user_id, 'woocommerce_maintmode_ignore_notice', 'true', true);
			}
		}
		
		add_action('admin_init', 'woocommerce_maintmode_nag_ignore'); */
			
	}
	
		
	// Register and Enqueue Theme Scripts
	function woocommerce_maintmode_scripts() {
		
		global $woocommerce;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			
		wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
		
		wp_register_style( 'woo_maint_prettyPhotocss', plugins_url( 'assets/prettyPhoto/css/prettyPhoto.css' , __FILE__ ), '', '', 'all' );
		wp_enqueue_style( 'woo_maint_prettyPhotocss' ); 

		wp_register_script( 'woo_maint_cookiesjs', plugins_url( 'assets/jquery.cookie.js' , __FILE__ ),array('jquery'), '1.0', false );
		wp_enqueue_script( 'woo_maint_cookiesjs' );	
		
	}
	
	// only add scripts to site if active
	function woocommerce_maintmode_scripts_init(){
		
		$options = get_option('woo_maint');
		
		if ( ($options['activation'] == 1) && ! current_user_can( 'manage_woocommerce' ) ) {
			add_action( 'wp_enqueue_scripts', 'woocommerce_maintmode_scripts' );
		}
	}
	
	add_action( 'init', 'woocommerce_maintmode_scripts_init' );
	
	// Place some stuff into the websites pages
	function woocommerce_maintmode_header() {
		
		// check to see if users capabilities are less than woocommerce shop manager and only dispaly on Woocommerce pages
		if( is_woocommerce() or is_shop() or is_product_category() or is_product() or is_cart() or is_checkout() or is_account_page() && ! current_user_can( 'manage_woocommerce' ) ) {
			
			// Get some settings
			$options = get_option('woo_maint');
			$options['end_date'];
			
			// Set Default Time Zone based on WordPress settings
			$wp_gmt_offset = get_option('gmt_offset');
			$wp_timezone = get_option('timezone_string');
			date_default_timezone_set($wp_timezone);
			
			// Today's date time stamp
			$today_date = date("Y-m-d"); 
			$today_string = strtotime($today_date);
		
			// End date time stamp
			$timer_date = $options['end_date'];
			$end_string = strtotime($timer_date);
			$end_date = date("Y-m-d", $end_string); 
		
			// Difference between the two strings
			$time_diff_string = $end_string - $today_string;
			$time_diff = floor($time_diff_string/(60*60*24));
			
			$output .= '
			
			<style type="text/css">
			
				.woo_maint_page {
					border: 3px dashed #999; 
					margin: 20px 0 50px; 
					padding: 20px; 
					text-align: center;
				}
				
			</style>
			
			<script>
			
				jQuery(document).ready(function(){
					
				   var check_cookie = jQuery.cookie("lightbox_cookie");
					if(check_cookie == null){
						
					jQuery.cookie("lightbox_cookie", "woo_maintmode_on", { expires: '.$options['cookie_expire'].', path: "/" });';
					
					if ( $time_diff >= 0 ) {
						$output .= 'jQuery(".royal-prettyPhoto").prettyPhoto({
										theme: "pp_default",
										social_tools: ""
									}).trigger("click");';
					}
					
				   $output .= '}
				   				   
				});
				
			</script>';
					
			echo $output;
			
			// Check if lightbox / page or content	
			if ( $options['position'] == 'Lightbox' && ! current_user_can( 'manage_woocommerce' ) ) {
				
				// Check if internal or external page
					if ( $options['message'] == '' ) {
						$page_id = $options['redirect_page'];
						
						// ...and get + display message in pages
						$page = $page_id;
						$page_data = get_post( $page );
						$page_content = $page_data->post_content;
						//$title = $page_data->post_title;
						
						$content = '
						<a class="royal-prettyPhoto" href="#woo_maint_lightbox" style="display: none;">Inline</a>
						<div id="woo_maint_lightbox" style="display: none;">'.$page_content.'</div>';
					
						echo $content;
					
					}
					
					else {
				
					$content = '
						<a class="royal-prettyPhoto" href="#woo_maint_lightbox" style="display: none;">Inline</a>
						<div id="woo_maint_lightbox" style="display: none;">'.$options['message'].'</div>';
					
					echo $content;
					
					}
			
			} 
		
		}
	
	}
	
	// Initialize fuction to add stuff to header
	function woocommerce_maintmode_header_init(){
		
		$options = get_option('woo_maint');
		
		if ( ($options['activation'] == 1) && ($options['position'] == 'Page' or $options['position'] == 'Lightbox') && ! current_user_can( 'manage_woocommerce' ) ) {
			add_filter('wp_head', 'woocommerce_maintmode_header');
		}
	}
	
	add_action( 'init', 'woocommerce_maintmode_header_init' );
	
			
	
	// Redirect Function
	function woocommerce_maintmode_redirect(){
		
		// Check if redirect option set
		$options = get_option('woo_maint');
		$options['end_date'];
			
		// Set Default Time Zone based on WordPress settings
		$wp_gmt_offset = get_option('gmt_offset');
		$wp_timezone = get_option('timezone_string');
		date_default_timezone_set($wp_timezone);
		
		// Today's date time stamp
		$today_date = date("Y-m-d"); 
		$today_string = strtotime($today_date);
	
		// End date time stamp
		$timer_date = $options['end_date'];
		$end_string = strtotime($timer_date);
		$end_date = date("Y-m-d", $end_string); 
	
		// Difference between the two strings
		$time_diff_string = $end_string - $today_string;
		$time_diff = floor($time_diff_string/(60*60*24));
		
		// Check if internal or external page
		if ( $options['redirect_url'] == '' ) {
			$page_url = get_permalink( $options['redirect_page'] );
			$redirection = $page_url;
		} else {
			$redirection = $options['redirect_url'];
		}
		
		// Conditions to validate against before redirecting the user
		if ( ( $options['position'] == 'Redirect' && $time_diff >= 0 ) && (is_woocommerce() or is_shop() or is_product_category() or is_product() or is_cart() or is_checkout() or is_account_page() ) && ! current_user_can( 'manage_woocommerce' ) ) {
			
			// Check if cookie is set for the user
			if ( ! isset( $_COOKIE['redirect_cookie'] ) ) {
				
				$days = $options['cookie_expire'];
				$cookie = 'woo_maintmode_on';
				
				// Set the cookie for the user
				setcookie('redirect_cookie',$cookie,time() + (86400 * $days)); // 86400 = 1 day
					
				// ...and Redirect user
				wp_redirect( $redirection, 302 ); exit();
			} 
		}
		
	}
	
	add_action( 'wp', 'woocommerce_maintmode_redirect' );
	
	
	// Add to just Woocommerce pages
	function woocommerce_maintmode_page() {
		
		// Check if redirect option set
		$options = get_option('woo_maint');
		$options['end_date'];
			
		// Set Default Time Zone based on WordPress settings
		$wp_gmt_offset = get_option('gmt_offset');
		$wp_timezone = get_option('timezone_string');
		date_default_timezone_set($wp_timezone);
		
		// Today's date time stamp
		$today_date = date("Y-m-d"); 
		$today_string = strtotime($today_date);
	
		// End date time stamp
		$timer_date = $options['end_date'];
		$end_string = strtotime($timer_date);
		$end_date = date("Y-m-d", $end_string); 
	
		// Difference between the two strings
		$time_diff_string = $end_string - $today_string;
		$time_diff = floor($time_diff_string/(60*60*24));
	
		// Conditions to validate against before redirecting the user
		if ( ( $options['position'] == 'Page' && $time_diff >= 0 ) && (is_woocommerce() or is_shop() or is_product_category() or is_product() or is_cart() or is_checkout() or is_account_page() ) && ! current_user_can( 'manage_woocommerce' ) ) {
			
			// Check if cookie is set for the user
			if ( ! isset( $_COOKIE['page_cookie'] ) ) {
				
				$days = $options['cookie_expire'];
				$cookie = 'woo_maintmode_on';
				
				// Set the cookie for the user
				setcookie('page_cookie',$cookie,time() + (86400 * $days)); // 86400 = 1 day
				
				function woocommerce_maintmode_page_message($content) {	
				
					$options = get_option('woo_maint');
				
					// Check if internal or external page
					if ( $options['message'] == '' ) {
						$page_id = $options['redirect_page'];
						
						// ...and get + display message in pages
						$page = $page_id;
						$page_data = get_post( $page );
						$page_content = $page_data->post_content;
						//$title = $page_data->post_title;
						
						$content.= '<div class="woo_maint_page">';
						$content.= '<p>'.$page_content.'</p>';
						$content.= '</div>';
							
						return $content;
				
					} else {
						
						$content.= '<div class="woo_maint_page">';
						$content.= '<p>'.$options['message'].'</p>';
						$content.= '</div>';
							
						return $content;
					}
				
				}
				
				add_filter ('the_content', 'woocommerce_maintmode_page_message');
			} 
		}
		   
	}
	
	add_action( 'wp', 'woocommerce_maintmode_page' );
	
} else {
	
	// If Woocommerce is not activated then admin error message	
	function woocommerce_maintmode_activate_admin_notice() {
		
		global $current_user ;
		$user_id = $current_user->ID;
		
		if ( ! get_user_meta($user_id, 'woocommerce_maintmode_activate_ignore_notice') ) {
			echo '<div class="error"><p>';
			printf(__('WooCommerce Plugin is not installed or activated. <!-- <a href="%1$s">Hide Notice</a> -->'), '?woocommerce_maintmode_activate_nag_ignore=0');
			echo "</p></div>";
		}
	}
	
	add_action('admin_notices', 'woocommerce_maintmode_activate_admin_notice');
	
	/**
	// Allow activation notice to be dismissed 
	function woocommerce_maintmode_activate_nag_ignore() {
		
		global $current_user;
		$user_id = $current_user->ID;
		
		if ( isset($_GET['woocommerce_maintmode__activate_nag_ignore']) && '0' == $_GET['woocommerce_maintmode__activate_nag_ignore'] ) {
			 add_user_meta($user_id, 'woocommerce_maintmode__activate_ignore_notice', 'true', true);
		}
	}
	
	add_action('admin_init', 'woocommerce_maintmode_activate_nag_ignore'); */

}
