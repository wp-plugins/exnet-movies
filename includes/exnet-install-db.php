<?php

/* Add links DB */
function exnet_install_db() {
	
	global $wpdb;

	$sql = "CREATE TABLE `exnet_links` (
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

	//create watch movies/tv shows page tilte : Movies & TV Shows | content : [exnet_movies]
	$my_post = array(
	  'post_title'    => 'Search Movies & TV Shows',
	  'post_content'  => '[exnet_movies]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create "homepage" content : [exnet_generate_tabs]
	$my_post = array(
	  'post_title'    => 'Movies & TV Shows Homepage',
	  'post_content'  => '[exnet_generate_tabs]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);

	//set as homepage
	$home = wp_insert_post( $my_post );
	update_option( 'page_on_front', $home );
	update_option( 'show_on_front', 'page' );

	//create ajax results page title : exnet AJAX | content : [exnet_ajax] 
	$my_post = array(
	  'post_title'    => 'exnet AJAX',
	  'post_content'  => '[exnet_ajax]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create exnet_redirect page title : exnet REDIRECT | content [exnet_redirect]
	$my_post = array(
	  'post_title'    => 'exnet REDIRECT',
	  'post_content'  => '[exnet_redirect]',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'	  => 'page'
	);
	wp_insert_post( $my_post );

	//create "kinds" page
	$my_post = array(
	  'post_title'    => 'Watch Online',
	  'post_content'  => '[exnet_all_kinds]',
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
 function exnet_remove_db() {

 	global $wpdb;

 	$wpdb->query("DROP TABLE IF EXISTS exnet_links");

 }

 