<?php
/*
Plugin Name: ExNet Movies
Description: ExNet Movies plugin let's you run your own Movies & TV Shows listing website with embed/external links to watch.
Version: 1.1
Author: Shameem Reza
Author URI: http://shameemreza.info
*/


/**
 * Install/Remove Database Table
 */
require_once 'includes/exnet-install-db.php';

register_activation_hook( __FILE__, 'exnet_install_db' );
register_deactivation_hook( __FILE__, 'exnet_remove_db' );

/* Define pagination stuff */
define( 'exnet_ITEMS_PER_PAGE', get_option('exnet_movies_per_page', 10) );
define( 'exnet_ITEMS_PER_ROW', get_option('exnet_movies_per_row', 5) );
define( 'EXNET_HOMEPAGE_PER_PAGE', get_option('exnet_homepage_items', 5));

/* Load Plugin JS/CSS */
function exnet_CSS() {

	wp_register_style( 'exnet_rateIt_CSS', plugins_url( 'rate-it/rateit.css', __FILE__ ) );
	wp_enqueue_style( 'exnet_rateIt_CSS' );

	wp_register_style( 'exnet_plugin_CSS', plugins_url( 'css/exnet-css.css', __FILE__ ) );
	wp_enqueue_style( 'exnet_plugin_CSS' );

}

add_action( 'wp_head', 'exnetCallbackToAddMeta' );
function exnetCallbacktoAddMeta() {
	echo "<script type='text/javascript'>";
	echo 'var blog_url = "' . get_bloginfo( 'url' ) . '";';
	echo '</script>';
}

function exnet_JS() {

	wp_enqueue_script( 'jquery' );

	wp_register_script( 'exnet_rateIt_JS', plugins_url( $path ='rate-it/jquery.rateit.min.js', __FILE__ ) );
	wp_enqueue_script( 'exnet_rateIt_JS' );

	wp_register_script( 'exnet_jForm', plugins_url( $path ='js/jquery.form.min.js', __FILE__ ) );
	wp_enqueue_script( 'exnet_jForm' );

	wp_register_script( 'exnet_blockUI', 'http://malsup.github.io/jquery.blockUI.js' );
	wp_enqueue_script( 'exnet_blockUI' );

	wp_register_script( 'exnet_tabify', plugins_url( $path = 'js/jquery.tabify.js', __FILE__ ) );
	wp_enqueue_script( 'exnet_tabify' );

	wp_register_script( 'exnet_JS', plugins_url( $path = 'js/exnet-ajax.js', __FILE__ ) );
	wp_enqueue_script( 'exnet_JS' );


}

add_action( 'wp_enqueue_scripts', 'exnet_CSS', 99 );
add_action( 'wp_enqueue_scripts', 'exnet_JS' );

/**
 * Add custom post type movies
 */
require_once 'includes/exnet-custom-post-types.php';

/**
 * Add Metaboxes
 */
require_once 'includes/exnet-meta-boxes.php';

/**
 * Add WP-Admin Pages
 */
require_once 'includes/exnet-admin-pages.php';

/*
 * Custom template for single movie/tv show page
 * Includes archive/category/genre/type template as well
 */
require_once 'includes/exnet-template.php';
require_once 'includes/exnet-main-page.php';

add_shortcode( 'exnet_movies', 'exnet_main_page' );


/* 
 * Add tab groups - main plugin page
 */
require_once 'includes/exnet-shortcode.php';

/* AJAX Filtering Page */
require_once 'includes/exnet-ajax.php';
add_shortcode( 'exnet_ajax', 'exnet_ajax' );

//star rating
add_action( 'wp_ajax_exnet_rate', 'exnet_rate' );
add_action( 'wp_ajax_nopriv_exnet_rate', 'exnet_rate' );

//link rating
add_action( 'wp_ajax_exnet_link_rate', 'exnet_link_rate' );
add_action( 'wp_ajax_nopriv_exnet_link_rate', 'exnet_link_rate' );

/*Redirect page*/
require_once 'includes/exnet-redirect.php';

/*Include widgets*/
require_once 'includes/exnet-widget.php';

/*Add All "KINDS" + "GENRES" Pages*/
require_once 'includes/exnet-kinds.php';

function exnet_add_query_vars() {
    global $wp;

    $wp->add_query_var('kind');
    $wp->add_query_var('genre');
}

add_filter('init', 'exnet_add_query_vars');

function exnet_rewrite_rules() {
    add_rewrite_rule('watch-online/(.*)$', 'index.php?pagename=watch-online&kind=$matches[1]', 'top');
    add_rewrite_rule('watch-genre/(.*)$', 'index.php?pagename=watch-online&genre=$matches[1]', 'top');
}

add_action('init','exnet_rewrite_rules');