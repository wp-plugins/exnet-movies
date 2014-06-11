<?php


/* the content filter */
function exnet_movie_page( $content ) {
	global $wpdb, $post;

	/* for single */
	if ( get_post_type() == 'watch' && is_single() && !is_admin() ) {
		ob_start();

		if ( has_post_thumbnail( $post->ID ) ) {
			the_post_thumbnail( $size = 'post-thumbnail',
				$attr = array( 'data' => 'movie-poster',
					'style' => 'float:left; margin: 5px 15px 5px 0;' ) );
		}
		?>

		<?php add_thickbox(); ?>

		<strong>Release Date: </strong>
	    <?php echo esc_html( get_post_meta( $post->ID, 'exnet_release', true ) ); ?>
	    <br />

	    <strong>Runtime: </strong>
	    <?php echo esc_html( get_post_meta( $post->ID, 'exnet_runtime', true ) ); ?>
	    <br />

	    <strong>Genres</strong>
	    <?php 
	    $genres = get_the_terms( $post->ID, 'genre'); 
	    if ( $genres && ! is_wp_error( $genres ) ) : 
	    	foreach( $genres as $g ) {
	    		echo '<a href="' . get_bloginfo('url') .'/watch-genre/' . $g->slug . '">' . $g->name . '</a> ';
	    	}
	    endif;
	    ?>
	    <br/>

	    <strong>Actors</strong>
	    <?php echo get_post_meta( $post->ID, 'exnet_actors', true ); ?>
	    <br/>

	    <!-- Display yellow stars based on rating -->
	    <strong>Rating: </strong>

	     <?php
		$rating = get_post_meta( $post->ID, 'exnet_rating', true );
		if ( $rating === FALSE or $rating == 'N/A' ) $rating = 0;
?>

	     <span data-movieid="<?php echo $post->ID ?>" class="rateit" data-rateit-value="<?php echo $rating ?>"></span>

		 <br/><strong>Description</strong>
		 <?php echo $content ?>

		 <h3><?php echo str_replace( array( "Watch ", "Online" ), array( "", "" ), get_the_title( $post->ID ) ) ?> Watch Links</h3>

		 <div id="exnet-movie-links">

		 <?php
		 $tabs = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT(link_tab) as tab_name FROM exnet_links WHERE mID = %d", $post->ID));
		 ?>
		  	<?php 
		  	if(count($tabs)): 

		  	echo '<ul id="exnet-tabs">';

		  	$i = 0;
		  	foreach($tabs as $tab):
		  	$i++;
		  	?>
			    <li<?php if($i == 1) echo ' class="active"'; ?>><a href="#exnet-tabs-<?php echo $i; ?>"><?= $tab->tab_name ?></a></li>
			<?php 
			endforeach;
				echo '</ul>';
		    else: 
		    ?>
			<?php endif; ?>
		  

		  <div style="clear:both;"></div>

		  <?php
		  if(count($tabs)):
	  	  $i = 0;
	  	  foreach($tabs as $t): 
	  	  $i++;
		  ?>
		  <div id="exnet-tabs-<?php echo $i; ?>">
		    <ul class="exnet-movie-links">
		    	<?php 
		    	$tab_links = $wpdb->get_results($wpdb->prepare("SELECT * FROM exnet_links WHERE 
		    													link_tab = %s AND status = 'approved' 
		    													AND mID = %d", $t->tab_name, $post->ID));

		    	$k = 0;
		    	foreach($tab_links as $t_link):
		    	$k++;

		    	$alt_bg = ($k%2 == 1) ? '#F2F2F2' : '#dcddcf';
		    	?>
		    	<li style="background-color:<?=$alt_bg ?>;">
		    		<div class="float-left">
		    			<a href="<?=get_option('url') ?>/exnet-redirect?link_id=<?=$t_link->linkID ?>"<?php if($t_link->link_type == 'Embed') echo ' class="thickbox"'; ?>><?=$t_link->link_title ?></a>
		    		</div>
		    		<div style="float-right">
		    			<a href="javascript:void(0);" style="color:green;" class="exnet-rate-link" id="exnetlinkok-<?=$t_link->linkID ?>">Works (<?=$t_link->link_ok ?>)</a>
		    			<a href="javascript:void(0);" style="color:#cc3333;" class="exnet-rate-link" id="exnetlinkbroken-<?=$t_link->linkID ?>">Broken (<?=$t_link->link_broken ?>)</a>
		    		</div>
		    	</li>
		    <?php endforeach; ?>
		    </ul>
		  </div>
		  <?php 
		  endforeach;
		  endif; 
		  ?>
		  
		 </div><!--movie links-->

		 <div style="clear:both;"></div>

		 <?php
		return ob_get_clean();

		/* for category/type/genre/archive */
	}elseif ( get_post_type() == 'watch' && is_category() && !is_admin() || get_post_type() == 'watch' && is_archive() && !is_admin() ) {

		ob_start();

		if ( has_post_thumbnail( $post->ID ) ) {
			the_post_thumbnail( $size = 'post-thumbnail',
				$attr = array( 'data' => 'movie-poster',
					'style' => 'float:left; margin: 5px 15px 5px 0;' ) );
		}
?>
		 <strong>Release Date: </strong>
	     <?php echo esc_html( get_post_meta( $post->ID, 'exnet_release', true ) ); ?>
	     <br />

	     <strong>Runtime: </strong>
	     <?php echo esc_html( get_post_meta( $post->ID, 'exnet_runtime', true ) ); ?>
	     <br />

	     <strong>Genres</strong>
	     <?php echo get_the_term_list( $post->ID, 'genre', '', ', ', '' ); ?>
	     <br/>

	     <strong>Actors</strong>
	     <?php echo get_post_meta( $post->ID, 'exnet_actors', true ); ?>
	     <br/>

	     <!-- Display yellow stars based on rating -->
	     <strong>Rating: </strong>

		 <?php
		$rating = get_post_meta( get_the_ID(), 'exnet_rating', true );
		if ( $rating !== FALSE and $rating != 'N/A' )
			$rating = floatval( $rating );
		else
			$rating = 0;

		echo '<span data-movieid="' . get_the_ID(). '" class="rateit" data-rateit-value="' . $rating . '"></span>';
?>


	    <div style="clear:both;"></div>
	    <?php

		return ob_get_clean();

		/* default ones */
	} else {
		return $content;
	}
}

add_filter( "the_content", "exnet_movie_page" ) ;
add_filter( "the_excerpt", "exnet_movie_page" ) ;


/* Hide default thumbnail when shown */
function exnet_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

	if ( get_post_type() == 'watch' && ( is_single() || is_category() || is_archive() ) && !is_admin() ) {
		if ( isset( $attr['data'] ) and $attr['data'] == 'movie-poster' )
			return $html;
		else
			return '';
	}

	return $html;
}

add_filter( 'post_thumbnail_html', 'exnet_thumbnail_html', 10, 5 );
