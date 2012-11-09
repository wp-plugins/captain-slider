<?php

/*
|--------------------------------------------------------------------------
| SETTINGS PAGE
|--------------------------------------------------------------------------
*/

function ctslider_options_each( $key ) {

    $slider_options = get_option( 'ctslider_all_options' );

     /* Define the array of defaults */ 
    $defaults = array(
        'width'     		=> 0,
        'height'     		=> 0,
        'effect'    		=> 'fade',
        'bullets'			=> 0,
        'arrows'			=> 0,
        'slidelength'		=> 6000,
        'animationlength'	=> 600,
        'automatic'			=> 0,
        'donation'			=> 0
    );

    $slider_options = wp_parse_args( $slider_options, $defaults );

    if( isset($slider_options[$key]) )
         return $slider_options[$key];

    return false;
}



/**
 * This function introduces the theme options into the 'Appearance' menu and into a top-level 
 * 'Sandbox Theme' menu.
 */
function ctslider_example_theme_menu() {

	add_submenu_page(
		'edit.php?post_type=slides',
		'Captain Slider Settings',
		'Settings',
		'manage_options',
		'ctslider_all_options',
		'ctslider_theme_display'
	);

}
add_action( 'admin_menu', 'ctslider_example_theme_menu' );

/**
 * Renders a simple page to display for the theme menu defined above.
 */
function ctslider_theme_display( $active_tab = '' ) {
	
	ob_start();
	?>

	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e('Captain Slider Settings', 'ctslider' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else {
			$active_tab = 'display_options';
		} // end if/else ?>
		
		<?php
		$donate = ctslider_options_each('donation');
		if ( $donate != 1 ) {
			echo '<p>';
			_e( 'If you love Captain Slider, any donation would be appreciated! It helps to continue the development and support of the plugin.', 'ctslider' );
			printf( __( '%sBut seriously, I just want to drink beer and coffee for free, so help a developer out.%sLove, Captain Theme (Bryce)%s', 'ctslider' ), '<br /><em>', '</em><br /><strong>', '</strong>' ); 
			echo '<br/><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="TB5K5RXN8Q2XG">
		<input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal: The safer, easier way to pay online.">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
		</form>';
			echo '</p>';
		}
		?>
		
		<h3><?php _e( 'Documentation', 'ctslider' ); ?></h3>
		<p><?php printf( __( 'I love writing documentation, so I wrote a lot of it for you: %sVisit Captain Slider Documentation%s.', 'ctslider' ), '<strong><a href="http://cpthe.me/sliderdocs">', '</a></strong>' ); ?></p>
		
		<form method="post" action="options.php">
			<?php

				if( $active_tab == 'display_options' ) {

					settings_fields( 'ctslider_all_options' );
					do_settings_sections( 'ctslider_all_options' );

				} // end if/else

				submit_button();
	
	echo ob_get_clean();
	
	
} // end ctslider_theme_display

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function ctslider_initialize_theme_options() {

	// If the theme options don't exist, create them.
	if( false == get_option( 'ctslider_all_options' ) ) {	
		add_option( 'ctslider_all_options' );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a 
	add_settings_section(
		'general_settings_section',
		__( 'Settings', 'ctslider' ),
		'ctslider_general_options_callback',
		'ctslider_all_options'
	);
	
	// Slide Width
	add_settings_field(	
		'width',						
		__( 'Width (px)', 'ctslider' ),							
		'ctslider_width_callback',	
		'ctslider_all_options',	
		'general_settings_section'			
	);
	
	// Slide Height
	add_settings_field(	
		'height',						
		__( 'Height (px)', 'ctslider' ),							
		'ctslider_height_callback',	
		'ctslider_all_options',	
		'general_settings_section'			
	);
	
	// Note
	add_settings_field(
		'note',
		'',
		'ctslider_note_callback',
		'ctslider_all_options',
		'general_settings_section'
	);
		
	// Slide Effect / Animation
	add_settings_field(
		'effect',
		__( 'Slide Effect / Animation', 'ctslider' ),
		'ctslider_effect_callback',
		'ctslider_all_options',
		'general_settings_section'
	);
	
	// Note #2
	add_settings_field(
		'note2',
		'',
		'ctslider_note2_callback',
		'ctslider_all_options',
		'general_settings_section'
	);

	// Slide Nav Bullets
	add_settings_field(	
		'bullets',
		__( 'Hide Bullets', 'ctslider' ),
		'ctslider_bullets_callback',
		'ctslider_all_options',
		'general_settings_section',
		array(								
			__( 'Check the box to hide the slide bullets.', 'ctslider' )
		)
	);
	
	// Slide Nav Arrows
	add_settings_field(	
		'arrows',						
		__( 'Hide Navigation Arrows', 'ctslider' ),				
		'ctslider_arrows_callback',	
		'ctslider_all_options',					
		'general_settings_section',			
		array(								
			__( 'Check the box to hide the navigation arrows.',  'ctslider' )
		)
	);
	
	/***/
	
	// Slide Display Length
	add_settings_field(	
		'slidelength',						
		__( 'Slide Length (ms)', 'ctslider' ),							
		'ctslider_slidelength_callback',	
		'ctslider_all_options',	
		'general_settings_section'			
	);
	
	// Animation Display Length
	add_settings_field(	
		'animationlength',						
		__( 'Animation Length (ms)', 'ctslider' ),							
		'ctslider_animationlength_callback',	
		'ctslider_all_options',	
		'general_settings_section'			
	);
	
	// Slider Automatic Play
	add_settings_field(	
		'automatic',						
		__( 'No Automatic Start', 'ctslider' ),				
		'ctslider_automatic_callback',	
		'ctslider_all_options',					
		'general_settings_section',			
		array(								
			__( 'If the box is checked the slider will not automatically start/play.', 'ctslider' )
		)
	);
	
	// Hide Donation Link
	add_settings_field(	
		'donation',						
		__( 'Hide Donation Link', 'ctslider' ),				
		'ctslider_donation_callback',	
		'ctslider_all_options',					
		'general_settings_section',			
		array(								
			__( 'Tick this box to hide the donation link above. ANY donations are appreciated!', 'ctslider' )
		)
	);

	// Finally, we register the fields with WordPress
	register_setting(
		'ctslider_all_options',
		'ctslider_all_options'
	);


} // end ctslider_initialize_theme_options
add_action( 'admin_init', 'ctslider_initialize_theme_options' );



/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function provides a simple description for the General Options page. 
 *
 * It's called from the 'ctslider_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */
function ctslider_general_options_callback() {
	echo '<p>';
	_e( 'You can use the following settings to configure the Captain Slider.', 'ctslider' );
	echo '</p>';
} // end ctslider_general_options_callback

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */

function ctslider_width_callback() {

	$options = get_option( 'ctslider_all_options' );

	$url = '';
	if( isset( $options['width'] ) ) {
		$url = intval( $options['width'] );
	} // end if

	// Render the output
	echo '<input type="text" id="width" name="ctslider_all_options[width]" value="' . $url . '" /><label for="width">&nbsp;';
	_e( 'If left at 0, the original image size will be used.', 'ctslider' );
	echo '</label>';

} // end ctslider_width_callback

function ctslider_height_callback() {

	// First, we read the social options collection
	$options = get_option( 'ctslider_all_options' );

	// Next, we need to make sure the element is defined in the options. If not, we'll set an empty string.
	$url = '';
	if( isset( $options['height'] ) ) {
		$url = intval( $options['height'] );
	} // end if

	// Render the output
	echo '<input type="text" id="height" name="ctslider_all_options[height]" value="' . $url . '" /><label for="height">&nbsp;';
	_e( 'If left at 0, the original image size will be used.', 'ctslider' );
	echo '</label>';

} // end ctslider_height_callback

function ctslider_note_callback() {
	
	_e( 'If you change the width/height above, you will need to regenerate the slider images.', 'ctslider' );
	echo '<br/>';
	printf( __( 'Either %sInstall the %sRegenate Thumbnails%s Plugin%s or reupload your slides.', 'ctslider' ), '<strong>', '<a href="http://cpthe.me/regenerate/">', '</a>', '</strong>' );
	
} // end ctslider_note_callback

function ctslider_effect_callback() {

	$options = get_option( 'ctslider_all_options' );

	$html = '<select id="effect" name="ctslider_all_options[effect]">';
		$html .= '<option value="fade"' . selected( $options['effect'], 'fade', false) . '>' . __( 'Fade', 'ctslider' ) . '</option>';
		$html .= '<option value="slideh"' . selected( $options['effect'], 'slideh', false) . '>' . __( 'Slide Horizontal', 'ctslider' ) . '</option>';
		$html .= '<option value="slidev"' . selected( $options['effect'], 'slidev', false) . '>' . __( 'Slide Vertical', 'ctslider' ) . '</option>';
	$html .= '</select>';
	
	echo $html;

} // end ctslider_effect_callback

function ctslider_note2_callback() {
	
	_e( 'If you select Slide Vertical, your slides must all share the same height or they will display strangely.', 'ctslider' );
	
} // end ctslider_note2_callback 

function ctslider_bullets_callback($args) {

	$options = get_option('ctslider_all_options');

	$html = '<input type="checkbox" id="bullets" name="ctslider_all_options[bullets]" value="1" ' . checked( 1, isset( $options['bullets'] ) ? $options['bullets'] : 0, false ) . '/>'; 

	$html .= '<label for="bullets">&nbsp;'  . $args[0] . '</label>'; 

	echo $html;

} // end ctslider_bullets_callback

function ctslider_arrows_callback($args) {

	$options = get_option('ctslider_all_options');

	$html = '<input type="checkbox" id="arrows" name="ctslider_all_options[arrows]" value="1" ' . checked( 1, isset( $options['arrows'] ) ? $options['arrows'] : 0, false ) . '/>'; 
	$html .= '<label for="arrows">&nbsp;'  . $args[0] . '</label>'; 

	echo $html;

} // end ctslider_arrows_callback

function ctslider_slidelength_callback() {

	$options = get_option( 'ctslider_all_options' );

	$url = '';
	if( isset( $options['slidelength'] ) ) {
		$url = intval( $options['slidelength'] );
	} else {
		$url = 6000; // default value
	}

	// Render the output
	echo '<input type="text" id="slidelength" name="ctslider_all_options[slidelength]" value="' . $url . '" /><label for="slidelength">&nbsp;';
	_e( 'Length each slide will play for in milliseconds. Default is 6000.', 'ctslider' );
	echo '</label>';

} // end ctslider_slidelength_callback


function ctslider_animationlength_callback() {

	$options = get_option( 'ctslider_all_options' );

	$url = '';
	if( isset( $options['animationlength'] ) ) {
		$url = intval( $options['animationlength'] );
	} else {
		$url = 600; // default value
	}

	// Render the output
	echo '<input type="text" id="animationlength" name="ctslider_all_options[animationlength]" value="' . $url . '" /><label for="animationlength">&nbsp;';
	_e( 'Length the slide animation/effect will go for in milliseconds. Default is 600.', 'ctslider' );
	echo '</label>';

} // end ctslider_animationlength_callback


function ctslider_automatic_callback($args) {

	$options = get_option('ctslider_all_options');

	$html = '<input type="checkbox" id="automatic" name="ctslider_all_options[automatic]" value="1" ' . checked( 1, isset( $options['automatic'] ) ? $options['automatic'] : 0, false ) . '/>'; 

	$html .= '<label for="automatic">&nbsp;'  . $args[0] . '</label>'; 

	echo $html;

} // end ctslider_automatic_callback


function ctslider_donation_callback($args) {

	$options = get_option('ctslider_all_options');

	$html = '<input type="checkbox" id="donation" name="ctslider_all_options[donation]" value="1" ' . checked( 1, isset( $options['donation'] ) ? $options['donation'] : 0, false ) . '/>'; 

	$html .= '<label for="donation">&nbsp;'  . $args[0] . '</label>'; 

	echo $html;

} // end ctslider_automatic_callback


function ctslider_textarea_element_callback() {

	$options = get_option( 'ctslider_all_options' );

	// Render the output
	echo '<textarea id="textarea_example" name="ctslider_all_options[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';

} // end ctslider_textarea_element_callback


// omit closing PHP tag for posterity (I honestly don't even know what that means - just sounds cool and important I guess)