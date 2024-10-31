<?php
/*
Plugin Name: FT NetBible Tagger
Plugin URI: http://fullthrottledevelopment.com/netbibletagger-for-wordpress
Description: This plugin enables the <a href='http://labs.bible.org/NETBibleTagger'>NETBible Tagger</a> on your WordPress site</a>
Version: 1.1
Author: FullThrottle Development, LLC
Author URI: http://fullthrottledevelopment.com/
Primary Developer: glenn@fullthrottledevelopment.com

Changelog
-------------
* 1.1 - Fixed bug to make it work in webkit browsers
* 1.0 - Inital release

*/

#### CONSTANTS ####
	define('FT_NBT_VERSION', '1.1');


#### JS AND CSS ####
	
	// JS to make it happen. JS taken from http://labs.bible.org/NETBibleTagger
	function ft_nbt_print_js_in_header() {
		if ( is_admin() )
			return; // Don't run in admin and don't run when not called

		$options = get_option( 'ft-nbt-options' );

		echo "<!-- Go Here: http://labs.bible.org/NETBibleTagger to add this to your site. -->\n\r";
		echo '<script type="text/javascript" defer="defer" src="http://labs.bible.org/api/NETBibleTagger/netbibletagger.js">';
		
		if ( isset( $options['voidOnMouseOut'] ) && 1 == $options['voidOnMouseOut'] )
			echo "\n\rorg.bible.NETBibleTagger.voidOnMouseOut = true;";
		
		if ( isset( $options['parseAnchors'] ) && 1 == $options['parseAnchors'] )
			echo "\n\rorg.bible.NETBibleTagger.parseAnchors = true;";
		
		if ( isset( $options['fontSize'] ) && 1 == $options['fontSize'] )
			echo "\n\rorg.bible.NETBibleTagger.fontSize = '" . esc_attr( $options['customFontSize'] ) . "';";
		
		if ( isset( $options['customCSS'] ) && 1 == $options['customCSS'] )
			echo "\n\rorg.bible.NETBibleTagger.customCSS = true;";
	
		echo "</script>\n\r";
		
	}
	add_action( 'wp_footer', 'ft_nbt_print_js_in_header' );

	// Custom CSS for NETBibleTagger
	function ft_nbt_print_css_in_header() {
		if ( is_admin()  )
			return; // Don't run in admin and don't run when not called
		
		$options = get_option( 'ft-nbt-options' );
				
		if ( isset( $options['customCSS'] ) && 1 == $options['customCSS'] && isset( $options['css'] ) && !empty( $options['css'] ) ) {
			echo "<style type='text/css'>";
			echo stripslashes( $options['css'] );
			echo "</style>";
		}
	}
	add_action( 'wp_head', 'ft_nbt_print_css_in_header' );


#### ADMIN PAGE ####

	// This function adds the admin page
	function ft_nbt_init_menus() {
		// Add submenu item
		add_submenu_page( 'options-general.php', 'NETBible Tagger', 'NETBible Tagger', 'manage_options', 'ft-nbt', 'ft_nbt_options_page' );
	}
	add_action( 'admin_menu', 'ft_nbt_init_menus' );
	
	// Options page
	function ft_nbt_options_page() {
		
		$options = get_option( 'ft-nbt-options' );
		
		?>
		<div class='wrap'>
			
			<?php 
			screen_icon( 'options-general' );
			echo "<h2>NETBible Tagger Options</h2>";
			?>
			
			<p>
				Use the options below to change the way NETBible Tagger works on your site. A detailed explanation of these options can be found <a href='http://labs.bible.org/blog/netbibletagger_configuration_options'>here</a>.
				<br />An overview of the way NETBible Tagger works can be found at the <a href='http://labs.bible.org/NETBibleTagger'>NETBible Tagger web site</a>.
				<br />Donations are appreciated to <a href='https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ACX79NUJ6C3DL'>help support this plugin</a>.
			</p>
			<form method='post' name='ft-nbt-options' action='' >
				<table class="form-table"> 
					<tr valign="top"> 
						<th scope="row"><label for="voidOnMouseOut">Popups</label></th> 
						<td><input name="voidOnMouseOut" type="checkbox" id="voidOnMouseOut" class="checkbox" <?php checked( $options['voidOnMouseOut'] ); ?> /> Remove the popup when the mouse leaves a link/popup?</td> 
					</tr> 
					<tr valign="top"> 
						<th scope="row"><label for="customCSS">CSS</label></th> 
						<td><input name="customCSS" type="checkbox" id="customCSS" class="checkbox" <?php checked( $options['customCSS'] ); ?> /> Override the default CSS and provide your own?</td> 
					</tr> 
					<tr valign="top"> 
						<th scope="row"><label for="parseAnchors">Existing links</label></th> 
						<td><input name="parseAnchors" type="checkbox" id="parseAnchors" class="checkbox" <?php checked( $options['parseAnchors'] ); ?> /> Make NETBibleTagger work with your existing links?</td> 
					</tr> 
					<tr valign="top"> 
						<th scope="row"><label for="fontSize">Font Size</label></th> 
						<td><input name="fontSize" type="checkbox" id="fontSize" class="checkbox" <?php checked( $options['fontSize'] ); ?> /> Use custom font size for text in popup?</td> 
					</tr>
					<tr valign="top"> 
						<th scope="row"><label for="fontSizeValue">Custom font size</label></th> 
						<td><input name="customFontSize" type="text" id="customFontSize" class="regular-text" value='<?php echo esc_attr( $options['customFontSize'] ); ?>' /> 
						<br /><span class="description">If 'font size' checkbox is checked, change 'small' to one of following: <code>xx-small</code>, <code>x-small</code>, <code>small</code>, <code>medium</code>, <code>large</code>, <code>x-large</code>, <code>xx-large</code>.</span></td> 
					</tr>
					<tr valign="top"> 
						<th scope="row"><label for="css">Custom CSS</label></th> 
						<td>
							<p><label for="css">This only works if the 'Override the default CSS and provide your own' checkbox above is checked. Here's some <a href='http://labs.bible.org/api/NETBibleTagger/netbibletagger.css'>default CSS</a>.</label></p>
							<p><textarea name="css" rows="10" cols="50" id="css" class="large-text large-text-code" ><?php echo stripslashes( $options['css'] ); ?></textarea></p>
						</td>
					</tr>
					<input type='hidden' name='ft-nbt-options-saved' value='true' />
				</table>
				<p class='submit'><input type='submit' class='button-primary' value='Save Changes' /></p>
			</form>
		</div>
		<?php
	}
	
	// This function fires on init and procces options page form
	function ft_nbt_process_options_form( $force=false ) {
		global $wpdb;

		if ( ! isset( $_POST['ft-nbt-options-saved'] ) && !$force )
			return;

		// Load Post data array
		$voidOnMouseOut = isset( $_POST['voidOnMouseOut'] ) ? 1 : 0;
		$customCSS 		= isset( $_POST['customCSS'] ) ? 1 : 0;
		$parseAnchors 	= isset( $_POST['parseAnchors'] ) ? 1 : 0;
		$fontSize 		= isset( $_POST['fontSize'] ) ? 1 : 0;
		$css			= isset( $_POST['css'] ) ? $_POST['css'] : '';
				
		if ( !$fontSize || !isset( $_POST['customFontSize'] ) || empty( $_POST['customFontSize'] ) )
			$customFontSize = 'small';
		else
			$customFontSize = $wpdb->prepare( $_POST['customFontSize'] );

		$options = compact( 'voidOnMouseOut', 'customCSS', 'parseAnchors', 'fontSize', 'css', 'customFontSize' );
		
		update_option( 'ft-nbt-options', $options );
				
	}
	add_action( 'admin_init', 'ft_nbt_process_options_form' );
	
	// Loads default options if they don't exist when plugin is activated
	function ft_nbt_load_defaults() {
		$options = get_option( 'ft-nbt-options' );

		if ( empty( $options ) || ! is_array( $options ) )
			ft_nbt_process_options_form( true );
	}
	add_action( 'init', 'ft_nbt_load_defaults', 8 );
?>