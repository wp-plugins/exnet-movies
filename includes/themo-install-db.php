<?php

/* Add links DB */
function themo_install_db() {
	
	global $wpdb;

	$sql = "CREATE TABLE `themo_links` (
  			`linkID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`linkBy` int(11) NOT NULL,
			`link_tab` varchar(255) NOT NULL,
			`link_title` varchar(255) NOT NULL,
			`link_destination` text NOT NULL,
			`link_ok` int(11) NOT NULL,
			`link_broken` int(11) NOT NULL,
			`status` enum('approved','pending') NOT NULL DEFAULT 'pending',
			`mID` int(11) NOT NULL,
			`link_type` enum('External','Embed') NOT NULL DEFAULT 'External',
			 PRIMARY KEY (`linkID`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
	
	$wpdb->query($sql);

	//create watch movies/tv shows page tilte : Movies & TV Shows | content : [themo_movies]
	$my_post = array(
	  'post_title'    => 'Search Movies & TV Shows',
	  'post_content'  => '[themo_movies]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create "homepage" content : [themo_generate_tabs]
	$my_post = array(
	  'post_title'    => 'Movies & TV Shows Homepage',
	  'post_content'  => '[themo_generate_tabs]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);

	//set as homepage
	$home = wp_insert_post( $my_post );
	update_option( 'page_on_front', $home );
	update_option( 'show_on_front', 'page' );

	//create ajax results page title : WATCH MOVIE AJAX | content : [themo_ajax] 
	$my_post = array(
	  'post_title'    => 'WATCH MOVIE AJAX',
	  'post_content'  => '[themo_ajax]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create themo_redirect page title : WATCH MOVIE REDIRECT | content [themo_redirect]
	$my_post = array(
	  'post_title'    => 'WATCH MOVIE REDIRECT',
	  'post_content'  => '[themo_redirect]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create "kinds" page
	$my_post = array(
	  'post_title'    => 'Watch Online',
	  'post_content'  => '[themo_all_kinds]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );


	//set permalink structure
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%category%/%postname%/' );

	$wp_rewrite->flush_rules(); 
	
}


/* Remove DB */
 function themo_remove_db() {

 	global $wpdb;

 	$wpdb->query("DROP TABLE IF EXISTS themo_links");

 }

 