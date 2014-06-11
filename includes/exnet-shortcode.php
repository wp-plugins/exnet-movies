<?php

/* 
 * Add links table filter
 */
add_action('parse_query','set_use_linkcount_flag');
function set_use_linkcount_flag($query) {
	global $use_linkcount_flag;
	if (isset($query->query_vars['orderby']) AND ($query->query_vars['orderby'] == 'linkcount'))
		$use_linkcount_flag = true;
	else
		$use_linkcount_flag = false;
}
add_filter('posts_orderby','exnet_orderby_linkscount');
function exnet_orderby_linkscount( $arg ) {
	global $wpdb, $use_linkcount_flag;

	if($use_linkcount_flag) $arg = str_replace("$wpdb->posts.post_date","link_count",$arg);

	#$use_linkcount_flag = false;
	return $arg;
}
add_filter('posts_fields', 'exnet_fields');
function exnet_fields($fields){
	global $wp_query, $wpdb, $use_linkcount_flag;

	if($use_linkcount_flag) $fields .= ", (SELECT COUNT(*) FROM exnet_links WHERE mID = ID) AS link_count";

	return $fields;
}

/* function to generate tabs with 
 * latest/top rated/most commented/most links for every kind
 */

function exnet_generate_tabs() {

	//get all movie kinds
	$terms = get_terms("watch-by-type", "hide_empty=0");
	$type_count = count($terms);

	//define basic args
	$args['post_type'] = 'watch';
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = EXNET_HOMEPAGE_PER_PAGE;


	//if we have terms (movies/tv-shows/etc)
	if ( $type_count > 0 ){
	    foreach ( $terms as $term ) {
	    	
	    	$args['watch-by-type'] = $term->name;

	    	//create tabs for each kind
	      	echo '<div class="exnet-type-group">';
	      	echo '<div>';
	      	echo '<h2 style="display:inline;">' . $term->name . '</h2> - <a href="/watch-online/' . $term->slug . '">View All</a>';
	      	echo '</div>';

	      	echo '<ul class="exnet-tabs">';
	      	echo '<li class="active"><a href="#recent-'.$term->term_id.'">Recent</a></li>';
	      	echo '<li><a href="#top-rated-'.$term->term_id.'">Top Rated</a></li>';
	      	echo '<li><a href="#most-commented-'.$term->term_id.'">Most Commented</a></li>';
	      	echo '<li><a href="#most-links-'.$term->term_id.'">Most Links</a></li>';
	      	echo '</ul>';
	      	echo '<div style="clear:both;"></div>';
			
	      	$array = array('recent', 'top-rated', 'most-commented', 'most-links');

			foreach($array as $tab):

			echo '<div id="'.$tab.'-'.$term->term_id.'">';
			
	      	//get movies for each kind
			if($tab == 'top-rated') {
		      	$args['meta_query'] = array('relation' => 'AND',
										array('key' => 'exnet_rating',
		                					   'value' => 'N/A',
		                						'compare' => '!='));

		      	$args['meta_key'] = 'exnet_rating'; 
		      	$args['orderby'] = 'meta_value';
		      	$args['order'] = 'DESC';

		  	}elseif($tab == 'most-commented'){
		  		$args['meta_key'] = '';
		  		$args['meta_query'] = '';
				$args['orderby'] = 'comment_count';
				$args['order'] = '';
	      	}elseif($tab == 'most-links') {
	      		$args['meta_key'] = '';
		  		$args['meta_query'] = '';
		  		$args['orderby'] = 'linkcount';
		  		$args['order'] = 'DESC';
	      	}elseif($tab == 'recent') {
	      		$args['meta_key'] = '';
		  		$args['meta_query'] = '';
		  		$args['orderby'] = 'post_date';
		  		$args['order'] = '';
	      	}

	      	$the_query = new WP_Query( $args );
			
			if ( $the_query->have_posts() ) :
			$i = 0;
			while ( $the_query->have_posts() ) : $the_query->the_post();
			$i++;				

			echo '<div class="exnet-small-item">';

			//show thumbnail
			if(has_post_thumbnail( get_the_ID() )) {
				echo '<a href="' . get_permalink() . '">';
				echo preg_replace('/class=".*?"/', '', get_the_post_thumbnail( get_the_ID() ));
				echo '</a><br/>';
		 	}

		 	//get rating
		 	$rating = get_post_meta( get_the_ID(), 'exnet_rating', true );
	     	if( $rating !== FALSE AND $rating != 'N/A' ) 
	     		$rating = floatval($rating);
	     	else
	     		$rating = 0;

	     	//show rating stars
		 	echo '<span data-movieid="' . get_the_ID(). '" class="rateit" data-rateit-value="' . $rating . '"></span>';

		 	//show link to movie
	 		echo '<br/>';
    		echo '<a href="' . get_permalink() . '">' .  get_the_title() . '</a>';

    		//new row if items are 4 or 5 etc.
    		echo '</div>';//exnet-small-item
	    	if($i%exnet_ITEMS_PER_ROW==0) echo '<div style="clear:both;height:15px;"></div>';


			endwhile;//while posts

			//no entries for this term & tab combo
			else:
				echo 'No ' . $tab . ' ' . $term->name . ' to show yet!';
			endif;

			wp_reset_postdata();
			wp_reset_query();

			echo '<div style="clear:both;"></div>';

			echo '</div>';//#$tab-$term->id
			endforeach;//foreach tab

	      	echo '</div>';//EXNET-type-grou

	      }//foreach terms

	}else {//if there are no movie/tv shows -kinds added
		echo 'To populate this page, please create some <strong>Kinds</strong> by going to 
				<strong>WP Admin -> Movies -> Kinds</strong>';
	}//if have kinds	
	
}//function ends

add_shortcode('exnet_generate_tabs', 'exnet_generate_tabs');