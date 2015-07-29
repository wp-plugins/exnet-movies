<?php
/*
 * This page is used for "all" kinds page: (ie. Movies/TV Shows) 
 * Includes ajax pagination
 */

function themo_all_kinds( $content ) {

	global $wpdb;

	if( get_query_var( 'kind' ) ) $fetch = get_query_var( 'kind' );
	if( get_query_var( 'genre' ) ) $fetch = get_query_var( 'genre' );

	if( isset($fetch) ) {
	?>
	

	<div id="domMessage" style="display:none;"> 
	    <h3><img src="<?php echo plugins_url('../img/ajax-loader.gif', __FILE__) ?>" /> Filtering Results...</h3> 
	</div> 

	<?php
	// the query
	$args['post_type'] = 'watch';
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = THEMO_ITEMS_PER_PAGE;
	$args['paged'] = isset($_POST['themo-paged']) ? abs(intval($_POST['themo-paged'])) : 1;

	if( get_query_var( 'kind' ) ) $args['watch-by-type'] = $fetch;
	if( get_query_var( 'genre' ) ) {
		$args['tax_query'] = array('relation' => 'AND', 
								array('taxonomy' => 'genre',
						 				'field' => 'slug',
						  				'terms' => array( $fetch )));
	}

	?>
	<form id="themo-filter" action="<?= bloginfo('url') ?>/themo-ajax" method="POST" style="display:none;">
	<input type="hidden" name="themo-paged" class="themo-paged" value="1" />
	<?php if(get_query_var('kind')) : ?>
		<input type="checkbox" name="watch-by-type[]" value="<?=esc_html($fetch)?>" checked="checked" />
	<?php elseif(get_query_var('genre')): ?>
		<input type="hidden" name="themo-genre" value="<?=esc_html($fetch)?>" />
	<?php endif; ?>
	</form>
	<?php

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
	
	echo '<div class="themo-items-ajax">';

	    $i = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
		$i++;

		echo '<div class="themo-small-item">';
			if(has_post_thumbnail( get_the_ID() )) {
				echo '<a href="' . get_permalink() . '">';
				echo preg_replace('/class=".*?"/', '', get_the_post_thumbnail( get_the_ID() ));
				echo '</a><br/>';
		 	}

		 	$rating = get_post_meta( get_the_ID(), 'themo_rating', true );
	     	if( $rating !== FALSE AND $rating != 'N/A' ) 
	     		$rating = floatval($rating);
	     	else
	     		$rating = 0;

		 	echo '<span data-movieid="' . get_the_ID(). '" class="rateit" data-rateit-value="' . $rating . '"></span>';

	 		echo '<br/>';
			echo '<a href="' . get_permalink() . '">' .  get_the_title() . '</a>';
		echo '</div>';

		if($i%THEMO_ITEMS_PER_ROW==0) echo '<div style="clear:both;"></div>';
		
	endwhile;

	echo '<div style="clear:both;"></div>';

    if($args['paged'] > 1)
		echo '<a href="javascript:void(0);" class="themo-load-more" id="' . ($args['paged']-1) . '-themo-page">&#171; Previous Page</a> - ';
				

	$next_page = $args;
	$next_page['paged'] = $args['paged']+1;
	$next_query = new WP_Query( $next_page );

	if ( $next_query->have_posts() )
		echo '<a href="javascript:void(0);" class="themo-load-more" id="' . ($args['paged']+1) . '-themo-page">&#0187; Next Page</a>';
				

	echo '</div>';//watch movie items ajax

	else:
  		echo '<p>Sorry, no ' . $fetch . ' posts found.</p>';
	endif;

	}else{

	echo 'Please remove this page from main menu! 
			It is used for showing individual "all" kinds (ie. Movies/TV-Shows. etc.)';

	}

}

add_shortcode( 'themo_all_kinds', 'themo_all_kinds' );


function themo_filter_title( $title ) {

	if(is_page('watch-online')) {
		if( get_query_var( 'kind' ) ) $fetch = 'Watch ' . get_query_var( 'kind' )  . '  ';
		if( get_query_var( 'genre' ) ) $fetch = 'Watch ' . get_query_var( 'genre' )  . '  ';
		return $fetch;
	}

	return $title;
}
add_filter('wp_title', 'themo_filter_title');