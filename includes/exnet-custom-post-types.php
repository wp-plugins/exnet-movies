<?php

//register our "Movies" custom post type
function exnet_custom_init() {
  $labels = array(
    'name' => 'Movies',
    'singular_name' => 'Movie',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Movie',
    'edit_item' => 'Edit Movie',
    'new_item' => 'New Movie',
    'all_items' => 'All Movies',
    'view_item' => 'View Movie',
    'search_items' => 'Search Movies',
    'not_found' =>  'No Movies found',
    'not_found_in_trash' => 'No Movies found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Movies'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'watch' ),
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments' )
  ); 

  register_post_type( 'watch', $args );

}
add_action( 'init', 'exnet_custom_init' );

/*
 * Add movie categories as well
 */
add_action( 'init', 'create_watch_taxonomies', 0 );

// create two taxonomies, genres and movie type
function create_watch_taxonomies() {
	// Add genres
	$labels = array(
		'name'              => _x( 'Genres', 'taxonomy general name' ),
		'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Genres' ),
		'all_items'         => __( 'All Genres' ),
		'parent_item'       => __( 'Parent Genre' ),
		'parent_item_colon' => __( 'Parent Genre:' ),
		'edit_item'         => __( 'Edit Genre' ),
		'update_item'       => __( 'Update Genre' ),
		'add_new_item'      => __( 'Add New Genre' ),
		'new_item_name'     => __( 'New Genre Name' ),
		'menu_name'         => __( 'Genre' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'genre' ),
	);

	register_taxonomy( 'genre', array( 'watch' ), $args );

	//add type: movie/tv-show
	$labels = array(
		'name'              => _x( 'Kinds', 'taxonomy general name' ),
		'singular_name'     => _x( 'Kind', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Kinds' ),
		'all_items'         => __( 'All Kinds' ),
		'parent_item'       => __( 'Parent Kind' ),
		'parent_item_colon' => __( 'Parent Kind:' ),
		'edit_item'         => __( 'Edit Kind' ),
		'update_item'       => __( 'Update Kind' ),
		'add_new_item'      => __( 'Add New Kind' ),
		'new_item_name'     => __( 'New Kind Name' ),
		'menu_name'         => __( 'Kind' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'watch-by-type' ),
	);

	register_taxonomy( 'watch-by-type', array( 'watch' ), $args );
}

/*
 * Flush rewrite rules
 */
function exnet_flush_rewrite()
{
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'exnet_flush_rewrite');