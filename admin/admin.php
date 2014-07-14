<?php

add_action( 'admin_init', 'woocommerce_maintmode_options_init' );
add_action( 'admin_menu', 'woocommerce_maintmode_otions_page' );

// Init plugin options to white list our options
// register_setting( $option_group, $option_name, $sanitize_callback );
function woocommerce_maintmode_options_init() {
	register_setting( 'woocommerce_maintmode_options', 'woo_maint', 'woocommerce_maintmode_options_validate' );
}

// Load up the menu page
// add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);
function woocommerce_maintmode_otions_page() {
	add_options_page( __( 'Woocommerce Maintenance | Message Mode', 'woocommerce-maintenance-mode' ), __( 'WooMaintenance', 'woocommerce-maintenance-mode' ), 'manage_options', 'woocommerce_maintmode_plugin_options', 'woocommerce_maintmode_options_do_page' );
}

// Create the options page
function woocommerce_maintmode_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
    
	<div class="wrap">

		<?php screen_icon(); echo "<h2 class='woo_maint'>" . __( 'Woocommerce Maintenance | Message Mode <span id="donate"><a href="http://mattroyal.co.za/donate/" target="_blank">Want to say thanks?</a></span>', 'woocommerce-maintenance-mode' ) . "</h2>"; ?>

		<?php //if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<!-- <div class="updated fade"><p><strong><?php //_e( 'Options saved', 'woocommerce-maintenance-mode' ); ?></strong></p></div> -->
		<?php //endif; ?>

		<form method="post" action="options.php">

			<?php settings_fields( 'woocommerce_maintmode_options' ); ?>
			<?php $options = get_option( 'woo_maint' ); ?>

			<?php if ( get_option('timezone_string') == '' ) {
				echo '<div class="error"><p>';
				_e( 'Please Choose a city and not an offset. You can find your timezone settings <a href="'.site_url().'/wp-admin/options-general.php">here</a>.', 'woocommerce-maintenance-mode' ); 
				echo '</p></div>';
				}
			?>
            
			<table class="form-table woo_maint">

				<tr valign="top"><th scope="row"><?php _e( 'Activate:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
                    <label class="switch" for="woo_maint[activation]"><?php _e( '', 'woocommerce-maintenance-mode' ); ?>
						<input id="woo_maint_activation" class="switch-input" name="woo_maint[activation]" type="checkbox" value="1" <?php checked( '1', $options['activation'] ); ?> />
                        <span class="switch-label" data-on="On" data-off="Off"></span>
      					<span class="switch-handle"></span>
						</label>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'End Date:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<input id="woo_maint_end_date" class="regular-text custom_date" type="text" name="woo_maint[end_date]" value="<?php esc_attr_e( $options['end_date'] ); ?>" />
						<label class="description" for="woo_maint[end_date]"><?php _e( '', 'woocommerce-maintenance-mode' ); ?></label>
					</td>
				</tr>
                
                <tr valign="top"><th scope="row"><?php _e( 'Message Intervals:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<input id="woo_maint_cookie_expire" class="regular-text" width="60" type="text" name="woo_maint[cookie_expire]" value="<?php if( $options['cookie_expire'] != '' ) { esc_attr_e( $options['cookie_expire'] ); } else { echo 0; } ?>" />
						<label class="description" for="woo_maint[cookie_expire]"></label>
                        <p><span class="woo_maint_note">
                        <?php _e( 'Range: 0 - 365 | (0 - Displays message every visit, 1 - Displays 1x day, 365 - Displays 1x a year )', 'woocommerce-maintenance-mode' ); ?>
                        </span></p>
					</td>
				</tr>
                
                <tr valign="top"><th scope="row"><?php _e( 'Display Options:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<select id="woo_maint_position" name="woo_maint[position]">
							<?php
							
								// Create arrays for our select and radio options
								$position_options = array(
									'Page' => array(
										'value' =>	'Page',
										'label' => __( 'On Page', 'woocommerce-maintenance-mode' )
									),
									'Lightbox' => array(
										'value' =>	'Lightbox',
										'label' => __( 'Lightbox', 'woocommerce-maintenance-mode' )
									),
									'Redirect' => array(
										'value' => 'Redirect',
										'label' => __( 'Redirect', 'woocommerce-maintenance-mode' )
									)
								);
								
								$selected = $options['position'];
								$p = '';
								$r = '';

								foreach ( $position_options as $option ) {
									$label = $option['label'];
									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="woo_maint[position]"><?php _e( '', 'woocommerce-maintenance-mode' ); ?></label>
					</td>
				</tr>
                
                <tr valign="top"><th scope="row"><?php _e( 'Show Countdown:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
                    <label class="switch" for="woo_maint[countdown]"><?php _e( '', 'woocommerce-maintenance-mode' ); ?>
						<input id="woo_maint_countdown" class="switch-input" name="woo_maint[countdown]" type="checkbox" value="1" <?php checked( '1', $options['countdown'] ); ?> />
                        <span class="switch-label" data-on="Yes" data-off="No"></span>
      					<span class="switch-handle"></span>
						</label>
					</td>
				</tr>
                
                <tr valign="top" class="woo_maint_redirect" ><th scope="row"><?php _e( 'External Redirect URL:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<input id="woo_maint_redirect_url" class="regular-text" type="text" name="woo_maint[redirect_url]" value="<?php esc_attr_e( $options['redirect_url'] ); ?>" />
						<label class="description" for="woo_maint[redirect_url]"></label>
                        <p><span class="woo_maint_note">
                        <?php _e( 'Example: http://google.com or /contact-us/', 'woocommerce-maintenance-mode' ); ?>
                        </span></p>
					</td>
				</tr>
                
                <tr valign="top" class="woo_maint_redirect" ><th scope="row"><?php _e( 'Use Existing Page:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<?php
                        	$arg = array(
								'name' => 'woo_maint[redirect_page]',
								'selected' => $options['redirect_page']
								);
									
							wp_dropdown_pages($arg);
						?>
						
                        <label class="description" for="woo_maint[internal_redirect]"></label>
                        
						<p><span class="woo_maint_note">
                        <?php _e( 'Choose page to pull in content from or redirect to. The <strong>External Redirect URL field</strong> above or <strong>Store Message text area</strong> below must be empty to use the <strong>Existing Page option</strong>.', 'woocommerce-maintenance-mode' ); ?>
                        </span></p>
					</td>
				</tr>
                
				<tr valign="top" class="woo_maint_message"><th scope="row"><?php _e( 'Store Message:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
						<?php 
							$settings = array( 'textarea_name' => 'woo_maint[message]' );
							wp_editor( $options['message'], 'woo_maint_message', $settings ); 
                        ?>	
						<label class="description" for="woo_maint[message]"></label>
                        <p><span class="woo_maint_note"> <?php _e( 'This is the note that will be displayed on your Woocommerce store pages', 'woocommerce-maintenance-mode' ); ?></span></p>
					</td>
				</tr>
                <tr valign="top" class="woo_maint_message"><th scope="row"><?php _e( 'Delete Existing Cookies:', 'woocommerce-maintenance-mode' ); ?></th>
					<td>
                    	<a href="?page=woocommerce_maintmode_plugin_options&woo_maint_delete_cookies=1" class="button">Delete All Cookies</a><span class="woo_maint_note" style="line-height: 28px; margin-left: 20px">(Note: This will only affect your browser.)</span>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'woocommerce-maintenance-mode' ); ?>" />
			</p>

		</form>

	</div>

	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function woocommerce_maintmode_options_validate( $input ) {

	global $select_options, $radio_options;

	// Our checkbox value is either 0 or 1
	if ( ! isset( $input['option1'] ) )
		$input['option1'] = null;
		$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );

	// Say our text option must be safe text with no HTML tags
	//$input['sometext'] = wp_filter_nohtml_kses( $input['sometext'] );

	// Our select option must actually be in our array of select options
	//if ( ! array_key_exists( $input['selectinput'], $select_options ) )
		//$input['selectinput'] = null;

	// Our radio option must actually be in our array of radio options
	//if ( ! isset( $input['radioinput'] ) )
		//$input['radioinput'] = null;
	//if ( ! array_key_exists( $input['radioinput'], $radio_options ) )
		//$input['radioinput'] = null;

	// Say our textarea option must be safe text with the allowed tags for posts
	//$input['sometextarea'] = wp_filter_post_kses( $input['sometextarea'] );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/


// This will go on the plugin options page for the Date Selection
function woocommerce_maintmode_admin(){
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
}

add_action( 'admin_enqueue_scripts', 'woocommerce_maintmode_admin' );

function date_picker_admin_head(){
	
	// Admin Options Styling
	echo '<style>
		/*.woo_maint_redirect {
			display: none;
		} */
		
		#woo_maint_cookie_expire {
    		width: 60px;
		}
		
		.ui-datepicker {
			display: none;
		}
		
		h2.woo_maint {
			background: #333333;
			border-bottom: 2px solid #FFFFFF;
			border-radius: 6px 6px 0 0;
			color: #FFFFFF;
			margin: 0;
			padding: 20px !important;
			box-shadow: 2px 2px 10px #ccc;
		}
		
		#donate {
			float: right;
			font-size: 0.8em;
			line-height: 1.5em;
			text-align: right;
		}

		table.woo_maint {
			box-shadow: 2px 2px 10px #CCCCCC;
			margin-top: 0 !important;
		}

		.woo_maint th {
			background: #1E8CBE;
			color: #FFFFFF !important;
			padding-left: 20px !important;
		}
		
		.woo_maint td {
			background: #EEEEEE;
			padding-left: 20px !important;
		}
		
		.woo_maint_note {
			background: #CCCCCC;
			border-radius: 0 6px 6px 0;
			color: #333333;
			margin: 10px 0;
			padding: 4px 10px;
		}
		
		.switch {
		  position: relative;
		  display: inline-block;
		  vertical-align: top;
		  width: 56px;
		  height: 20px;
		  padding: 3px;
		  background-color: white;
		  border-radius: 18px;
		  box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
		  cursor: pointer;
		  background-image: -webkit-linear-gradient(top, #eeeeee, white 25px);
		  background-image: -moz-linear-gradient(top, #eeeeee, white 25px);
		  background-image: -o-linear-gradient(top, #eeeeee, white 25px);
		  background-image: linear-gradient(to bottom, #eeeeee, white 25px);
		}
		
		.switch-input {
			left: 5px;
			opacity: 0;
			position: absolute;
			top: 10px;
			width: 60px !important;
			z-index: 99999;
		}
		
		.switch-label {
		  position: relative;
		  display: block;
		  height: inherit;
		  font-size: 10px;
		  text-transform: uppercase;
		  background: #eceeef;
		  border-radius: inherit;
		  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
		  -webkit-transition: 0.15s ease-out;
		  -moz-transition: 0.15s ease-out;
		  -o-transition: 0.15s ease-out;
		  transition: 0.15s ease-out;
		  -webkit-transition-property: opacity background;
		  -moz-transition-property: opacity background;
		  -o-transition-property: opacity background;
		  transition-property: opacity background;
		}
		.switch-label:before, .switch-label:after {
		  position: absolute;
		  top: 50%;
		  margin-top: -.5em;
		  line-height: 1;
		  -webkit-transition: inherit;
		  -moz-transition: inherit;
		  -o-transition: inherit;
		  transition: inherit;
		}
		.switch-label:before {
		  content: attr(data-off);
		  right: 11px;
		  color: #aaa;
		  text-shadow: 0 1px rgba(255, 255, 255, 0.5);
		}
		.switch-label:after {
		  content: attr(data-on);
		  left: 11px;
		  color: white;
		  text-shadow: 0 1px rgba(0, 0, 0, 0.2);
		  opacity: 0;
		}
		.switch-input:checked ~ .switch-label {
		  background: #47a8d8;
		  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
		}
		.switch-input:checked ~ .switch-label:before {
		  opacity: 0;
		}
		.switch-input:checked ~ .switch-label:after {
		  opacity: 1;
		}
		
		.switch-handle {
		  position: absolute;
		  top: 4px;
		  left: 4px;
		  width: 18px;
		  height: 18px;
		  background: white;
		  border-radius: 10px;
		  box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
		  background-image: -webkit-linear-gradient(top, white 40%, #f0f0f0);
		  background-image: -moz-linear-gradient(top, white 40%, #f0f0f0);
		  background-image: -o-linear-gradient(top, white 40%, #f0f0f0);
		  background-image: linear-gradient(to bottom, white 40%, #f0f0f0);
		  -webkit-transition: left 0.15s ease-out;
		  -moz-transition: left 0.15s ease-out;
		  -o-transition: left 0.15s ease-out;
		  transition: left 0.15s ease-out;
		}
		.switch-handle:before {
		  content: "";
		  position: absolute;
		  top: 50%;
		  left: 50%;
		  margin: -6px 0 0 -6px;
		  width: 12px;
		  height: 12px;
		  background: #f9f9f9;
		  border-radius: 6px;
		  box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
		  background-image: -webkit-linear-gradient(top, #eeeeee, white);
		  background-image: -moz-linear-gradient(top, #eeeeee, white);
		  background-image: -o-linear-gradient(top, #eeeeee, white);
		  background-image: linear-gradient(to bottom, #eeeeee, white);
		}
		.switch-input:checked ~ .switch-handle {
		  left: 40px;
		  box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
		}
		
		.switch-green > .switch-input:checked ~ .switch-label {
		  background: #4fb845;
		}
	</style>';

	// Initialize date picker
	echo '<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".custom_date").datepicker({
				dateFormat : "yy-mm-dd"
				});
				
				// $("#woo_maint_position").change(function(){
					
					//if($(this).val() == "Page") {;
						//$(".woo_maint_redirect").hide(1500);
						//$(".woo_maint_message").show(1500);
					//}
					
					//if($(this).val() == "Lightbox") {;
						//$(".woo_maint_redirect").hide(1500);
						//$(".woo_maint_message").show(1500);
					//}
					
					//if($(this).val() == "Redirect") {;
						//$(".woo_maint_redirect").show(1500);
						//$(".woo_maint_message").hide(1500);
					//}
				//});
		
			});
		</script>';

}

add_action('admin_head', 'date_picker_admin_head');
