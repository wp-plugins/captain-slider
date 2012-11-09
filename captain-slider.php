<?php
/*
Plugin Name: Captain Slider
Plugin URI: http://captaintheme.com/plugins/captain-slider/
Description: Allows you to easily create multiple jQuery sliders.
Author: Captain Theme
Author URI: http://captaintheme.com
Version: 1.0
Text Domain: ctslider
License: GNU GPL v2
*/


/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/

// Plugin Folder Path
if( !defined( 'CTSLIDER_PLUGIN_DIR' ) ) {
	define( 'CTSLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL
if ( ! defined( 'CTSLIDER_PLUGIN_URL' ) ) {
	define( 'CTSLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Version
define( 'CTSLIDER_VERSION', '1.0.0' );


/*
|--------------------------------------------------------------------------
| INCLUDES
|--------------------------------------------------------------------------
*/

/*---------------------*/
/* ADMIN
/*---------------------*/

// We only want to load these for admins, so let's do a conditional for just that:
if ( is_admin() ) {

	// Slider Sorter
	include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/sorter.php' );
	
	// Slider's User Interface
	include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/ui.php' );

}

// Register Some Settings
include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/settings.php' );

// Slider's Post Type
include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/post-types.php' );

// Slider's Taxonomies
include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/taxonomy.php' );

// Slider's Metabox
include_once( CTSLIDER_PLUGIN_DIR . 'includes/admin/meta-box.php' );
	
	
/*---------------------*/
/* FRONT END
/*---------------------*/

// Slider's Main Template
include_once( CTSLIDER_PLUGIN_DIR . 'includes/front-end/template.php' );

// Slider's Shortcode
include_once( CTSLIDER_PLUGIN_DIR . 'includes/front-end/shortcode.php' );

// Slider's Size
include_once( CTSLIDER_PLUGIN_DIR . 'includes/front-end/custom-size.php' );



/*
|--------------------------------------------------------------------------
| I18N - LOCALIZATION
|--------------------------------------------------------------------------
*/

load_plugin_textdomain( 'ctslider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/*
|--------------------------------------------------------------------------
| SCRIPTS/STYLES
|--------------------------------------------------------------------------
*/

// Register Scripts
function ctslider_register_scripts() {

	wp_register_style( 'flex-style',  CTSLIDER_PLUGIN_URL . 'css/flexslider.css', false, CTSLIDER_VERSION );
	wp_register_style( 'admin-styles', CTSLIDER_PLUGIN_URL . 'css/admin-styles.css', false, CTSLIDER_VERSION );
	
	wp_register_script( 'flex-script',  CTSLIDER_PLUGIN_URL .  'js/jquery.flexslider-min.js', false, CTSLIDER_VERSION, true );
		
	wp_register_script( 'fitvid',  CTSLIDER_PLUGIN_URL . 'js/jquery.fitvids.js', false, CTSLIDER_VERSION, true );
	
}
add_action( 'init', 'ctslider_register_scripts' );


// Enqueue Scripts/Styles
function ctslider_load_scripts() {

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'flex-script', array( 'jquery' ) );
	wp_enqueue_script( 'fitvid', array( 'jquery' ) );
		
	wp_enqueue_style( 'flex-style' );
	
}
add_action( 'wp_enqueue_scripts', 'ctslider_load_scripts' );


// Enqueue Admin Scripts/Styles   
function ctslider_load_admin_scripts($hook) {

	global $post;
    if( 'edit.php' === $hook && 'slides' === $post->post_type ) {
    	wp_enqueue_style( 'admin-styles' );
    }
    
}
add_action( 'admin_enqueue_scripts', 'ctslider_load_admin_scripts' );


function ctslider_slider_load() {

	$effect = ctslider_options_each('effect');
	$automatic = ctslider_options_each( 'automatic' );
	$controlnav = ctslider_options_each( 'bullets' );
	$arrownav = ctslider_options_each( 'arrows' );
	$slidespeed = ctslider_options_each( 'slidelength' );
	$anispeed = ctslider_options_each( 'animationlength' );
	
		ob_start();
	?>

		<script type="text/javascript">
		/* Slider Parameters */
		jQuery(window).load(function() {
		  
		  jQuery(".flexslider")
		    .fitVids()
		    .flexslider({
		     animation: '<?php if ( $effect == 'fade' ) { echo 'fade'; } else { echo 'slide'; } ?>', // Specify sets like: 'fade' or 'slide'
		     direction: '<?php if ( $effect == 'slideh' ) { echo 'horizontal'; } else { echo 'vertical'; } ?>',
		     slideshow: <?php if ( $automatic == 1 ) { echo 'false'; } else { echo 'true'; } ?>,
		     controlNav: <?php if ( $controlnav == 1 ) { echo 'false'; } else { echo 'true'; } ?>,
		     directionNav: <?php if ( $arrownav == 1 ) { echo 'false'; } else { echo 'true'; } ?>,
		     slideshowSpeed: <?php echo $slidespeed; ?>,
		     animationSpeed: <?php echo $anispeed; ?>,
		     useCSS: false,
		     animationLoop: true,
		     smoothHeight: true,
		     //controlNav: "thumbnails"
		  });
		  
		});
		</script>
		
	<?php
		echo ob_get_clean();
	
}
add_action( 'wp_head', 'ctslider_slider_load' );


/*
|--------------------------------------------------------------------------
| OTHER-FUNCTIONS
|--------------------------------------------------------------------------
*/

/*****
 * Add 'Settings' Link to Plugins Page
**/

function ctslider_settings_link($links, $file) {
	static $this_plugin;
	
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
	if ($file == $this_plugin){
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=slides&page=ctslider_all_options' ) . '">' . __("Settings", "eddslider") . '</a>';
		array_unshift($links, $settings_link);
	}
	
	return $links;
}
add_filter('plugin_action_links', 'ctslider_settings_link', 10, 2 );