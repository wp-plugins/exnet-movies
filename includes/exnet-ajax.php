<?php

/* function to star rate */
function exnet_rate() {

	if(isset($_POST['id']) AND isset($_POST['value'])) {
		$id = abs(intval($_POST['id']));
		$rating = abs(floatval($_POST['value']));

		if( $rating >= 0.5 AND $rating <= 5.00 ) {
			$current_rating = get_post_meta( $id, 'exnet_rating', true );
			if(!$current_rating OR $current_rating == 'N/A') $new_rating = $rating;
			if($current_rating > 0) $new_rating = ($current_rating + $rating) / 2;
			update_post_meta($id, 'exnet_rating', $new_rating);
			echo 'Thank you for rating';
		}else{
			echo $rating . ' invalid';
		}
	}else{
		echo 'Nothing to do';
	}

	die();

}

/* function to link rate works|broken */
function exnet_link_rate() {

	global $wpdb;

	if(isset($_POST['id']) AND isset($_POST['value'])) {
		$id = abs(intval($_POST['id']));
		$value = (string) ($_POST['value']);
		$ip = ip2long($_SERVER['REMOTE_ADDR']);

		if( $id > 0 ) {
			if($value == 'ok' OR $value == 'broken') {
				$cookie_link_rated = sprintf('exnet_link_rated_%d_%d', $id, $ip);

				if(get_option($cookie_link_rated)) {
					echo 'Already Rated';
				}else{
					echo 'Thank you';

					//update link rating
					$link_status = sprintf("link_%s", $value);
					$wpdb->query(
						$wpdb->prepare("UPDATE exnet_links 
										SET $link_status = $link_status+1 
										WHERE linkID = %d", $id)
						);

					//prevent multiple on same link
					update_option($cookie_link_rated, $value);
				}
			}else{
				printf("link value invalid %s", $value);	
			}
		}else{
			printf("link id invalid %d", $id);
		}
	}else{
		echo 'Nothing to do';
	}

}

//add_shortcode( 'exnet_star_rating', 'exnet_rate' );

/* function to return filtered results */
function exnet_ajax() {
    
	$args = array();

	if(isset($_POST)) {

		$args['post_type'] = 'watch';
		$args['post_status'] = 'publish';
		$args['posts_per_page'] = exnet_ITEMS_PER_PAGE;
		$args['paged'] = isset($_POST['exnet-paged']) ? abs(intval($_POST['exnet-paged'])) : 1;

		/* add title/description filter */
		if(isset($_POST['exnet_keyword']) AND trim(strip_tags($_POST['exnet_keyword'])) != "") {
			global $wpdb;

			$title = '%' . trim(strip_tags($_POST['exnet_keyword'])) . '%';

			$the_ids = $wpdb->get_col($wpdb->prepare( "select ID from $wpdb->posts 
														where post_type = 'watch' AND 
														(post_title LIKE %s OR post_content LIKE %s) ", $title, $title));
			
			if(count($the_ids)) {
    			$args['post__in'] = $the_ids;
    		}else{
    			echo '<p>Sorry, no results found.</p>';
    			exit;
    		}

        }

		// add kind
		if(isset($_POST['watch-by-type'])) {
			if(array_search('all', $_POST['watch-by-type']) === FALSE) {
				if(count($_POST['watch-by-type']) > 1) {
					$args['watch-by-type'] = implode(",", $_POST['watch-by-type']);
				}else{
					$args['watch-by-type'] = $_POST['watch-by-type'][0];
				}
			}
		}



		/* add genre */
		if(isset($_POST['exnet-genre']) AND ($_POST['exnet-genre'] != 'all')) {
			$args['tax_query'] = 
				array('relation' => 'AND', 
					array('taxonomy' => 'genre',
						 'field' => 'slug',
						  'terms' => array( $_POST['exnet-genre'] )));
		}

		/* add actor */
		if(isset($_POST['exnet_actor']) AND trim(strip_tags($_POST['exnet_actor'])) != "")  {
			$args['meta_query'] = array(
									array('key' => 'exnet_actors',
								    'value' => trim(strip_tags($_POST['exnet_actor'])),
								    'compare' => 'LIKE'));
		}
	

		$the_query = new WP_Query( $args );


		if ( $the_query->have_posts() ) :

			echo '<div class="exnet-items-ajax">';

		    $i = 0;
			while ( $the_query->have_posts() ) : $the_query->the_post();
			$i++;

			echo '<div class="exnet-small-item">';
				if(has_post_thumbnail( get_the_ID() )) {
					echo '<a href="' . get_permalink() . '">';
					echo preg_replace('/class=".*?"/', '', get_the_post_thumbnail( get_the_ID() ));
					echo '</a><br/>';
			 	}

			 	$rating = get_post_meta( get_the_ID(), 'exnet_rating', true );
		     	if( $rating !== FALSE AND $rating != 'N/A' ) 
		     		$rating = floatval($rating);
		     	else
		     		$rating = 0;

			 	echo '<span data-movieid="' . get_the_ID(). '" class="rateit" data-rateit-value="' . $rating . '"></span>';

		 		echo '<br/>';
	    		echo '<a href="' . get_permalink() . '">' .  get_the_title() . '</a>';
	    	echo '</div>';

	    	if($i%exnet_ITEMS_PER_ROW==0) echo '<div style="clear:both;"></div>';
	    	
	  		endwhile;

	  		echo '<div style="clear:both;"></div>';
	  		    if($args['paged'] > 1) {
	  				echo '<a href="javascript:void(0);" class="exnet-load-more" id="' . ($args['paged']-1) . '-exnet-page">&#171; Previous Page</a> - ';
	  			}

	  			$next_page = $args;
	  			$next_page['paged'] = $args['paged']+1;
	  			$next_query = new WP_Query( $next_page );

				if ( $next_query->have_posts() ) {
  					echo '<a href="javascript:void(0);" class="exnet-load-more" id="' . ($args['paged']+1) . '-exnet-page">&#0187; Next Page</a>';
  				}
  			echo '</div>';//exnet items ajax

		else:
	  		echo '<p>Sorry, no movies/tv shows found.</p>';
	  		echo '<a href="javascript:void(0);" class="exnet-load-more" id="' . ($args['paged']-1) . '-exnet-page">&#0187; Previous Page</a>';
		endif;


	}//if $_POST received

	die();

}

add_filter( 'page_template', 'exnet_page_template' );
function exnet_page_template( $page_template )
{
    if ( is_page( 'exnet-ajax' ) ) {
        $page_template = dirname( __FILE__ ) . '/blank-ajax-template.php';
    }

    if ( is_page( 'exnet-redirect' ) ) {
        $page_template = dirname( __FILE__ ) . '/blank-ajax-template.php';
    }

    return $page_template;
}
