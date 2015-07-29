<?php
/*
Plugin Name: Watch Movie on WordPress
Plugin URI: http://themology.net/shop/wordpress-watch-movie-plugin
Description: Watch Movie plugin let's you run your own Movies & TV Shows listing website with embed/external links to watch.
Version: 1.0.0
Author: Themology
Author URI: http://themology.net
*/


/**
 * Install/Remove Database Table
 */
require_once 'includes/themo-install-db.php';

register_activation_hook( __FILE__, 'themo_install_db' );
register_deactivation_hook( __FILE__, 'themo_remove_db' );

/* Define pagination stuff */
define( 'THEMO_ITEMS_PER_PAGE', get_option('themo_movies_per_page', 10) );
define( 'THEMO_ITEMS_PER_ROW', get_option('themo_movies_per_row', 5) );
define( 'THEMO_HOMEPAGE_PER_PAGE', get_option('themo_homepage_items', 5));

/* Load Plugin JS/CSS */
function themo_CSS() {

	wp_register_style( 'themo_rateIt_CSS', plugins_url( 'rate-it/rateit.css', __FILE__ ) );
	wp_enqueue_style( 'themo_rateIt_CSS' );

	wp_register_style( 'themo_plugin_CSS', plugins_url( 'css/themo-css.css', __FILE__ ) );
	wp_enqueue_style( 'themo_plugin_CSS' );

}

add_action( 'wp_head', 'themoCallbackToAddMeta' );
function themoCallbackToAddMeta() {
	echo "<script type='text/javascript'>";
	echo 'var blog_url = "' . get_bloginfo( 'url' ) . '";';
	echo '</script>';
}

function themo_JS() {

	wp_enqueue_script( 'jquery' );

	wp_register_script( 'themo_rateIt_JS', plugins_url( $path ='rate-it/jquery.rateit.min.js', __FILE__ ) );
	wp_enqueue_script( 'themo_rateIt_JS' );

	wp_register_script( 'themo_jForm', plugins_url( $path ='js/jquery.form.min.js', __FILE__ ) );
	wp_enqueue_script( 'themo_jForm' );

	wp_register_script( 'themo_blockUI', 'http://malsup.github.io/jquery.blockUI.js' );
	wp_enqueue_script( 'themo_blockUI' );

	wp_register_script( 'themo_tabify', plugins_url( $path = 'js/jquery.tabify.js', __FILE__ ) );
	wp_enqueue_script( 'themo_tabify' );

	wp_register_script( 'themo_JS', plugins_url( $path = 'js/themo-ajax.js', __FILE__ ) );
	wp_enqueue_script( 'themo_JS' );


}

add_action( 'wp_enqueue_scripts', 'themo_CSS', 99 );
add_action( 'wp_enqueue_scripts', 'themo_JS' );

/**
 * Add custom post type movies
 */
require_once 'includes/themo-custom-post-types.php';

/**
 * Add Metaboxes
 */
require_once 'includes/themo-meta-boxes.php';

/**
 * Add WP-Admin Pages
 */
require_once 'includes/themo-admin-pages.php';

/*
 * Custom template for single movie/tv show page
 * Includes archive/category/genre/type template as well
 */
require_once 'includes/themo-template.php';
require_once 'includes/themo-main-page.php';

add_shortcode( 'themo_movies', 'themo_main_page' );


/* 
 * Add tab groups - main plugin page
 */
require_once 'includes/themo-shortcode.php';

/* AJAX Filtering Page */
require_once 'includes/themo-ajax.php';
add_shortcode( 'themo_ajax', 'themo_ajax' );

//star rating
add_action( 'wp_ajax_themo_rate', 'themo_rate' );
add_action( 'wp_ajax_nopriv_themo_rate', 'themo_rate' );

//link rating
add_action( 'wp_ajax_themo_link_rate', 'themo_link_rate' );
add_action( 'wp_ajax_nopriv_themo_link_rate', 'themo_link_rate' );

/*Redirect page*/
require_once 'includes/themo-redirect.php';

/*Include widgets*/
require_once 'includes/themo-widget.php';

/*Add All "KINDS" + "GENRES" Pages*/
require_once 'includes/themo-kinds.php';

function themo_add_query_vars() {
    global $wp;

    $wp->add_query_var('kind');
    $wp->add_query_var('genre');
}

add_filter('init', 'themo_add_query_vars');

function themo_rewrite_rules() {
    add_rewrite_rule('watch-online/(.*)$', 'index.php?pagename=watch-online&kind=$matches[1]', 'top');
    add_rewrite_rule('watch-genre/(.*)$', 'index.php?pagename=watch-online&genre=$matches[1]', 'top');
}

add_action('init','themo_rewrite_rules');