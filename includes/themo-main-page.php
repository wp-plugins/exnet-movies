<?php
/* main plugin page with all the filtering options */

function themo_main_page() {
	global $wpdb;

	// the query
	$args['post_type'] = 'watch';
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = THEMO_ITEMS_PER_PAGE;
	$args['paged'] = 1;

	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :

	?>
	<div id="domMessage" style="display:none;"> 
	    <h3><img src="<?php echo plugins_url('../img/ajax-loader.gif', __FILE__) ?>" /> Filtering Results...</h3> 
	</div> 

	<h3>Filter Results (<a href="javascript:void(0);" class="themo-filter-toggle">Toggle Options</a>)</h3>

	<div id="themo-filtering-options">
		<form id="themo-filter" action="<?= bloginfo('url') ?>/themo-ajax" method="POST">
		<input type="hidden" name="themo-paged" class="themo-paged" value="1" />

		<dl>
	    	<dt>
	    		<label>By Keyword: </label>
	    	</dt>
			<dd>
				<input type="text" name="themo_keyword" />
			</dd>

			<dt>
				<label>By Kind: </label>
			</dt>
			<dd>
			<?php
			$terms = get_terms("watch-by-type", "hide_empty=0");
			$type_count = count($terms);
			if ( $type_count > 0 ){
				echo "<input type=\"checkbox\" name=\"watch-by-type[]\" value=\"all\" checked>All ";

			    foreach ( $terms as $term ) {
			      echo "<input type=\"checkbox\" name=\"watch-by-type[]\" value=\"" . esc_html($term->name) . "\">" . $term->name . " ";
			    }
			}
	 		?>
			</dd>

			<dt>
				<label>By Genre:</label>
			</dt>
			<dd>
			<?php
			$terms = get_terms("genre", "hide_empty=0");
			$genre_count = count($terms);
			if ( $genre_count > 0 ){
			    echo "<select name=\"themo-genre\">";
			    echo "<option value=\"all\">All</option>";
			    foreach ( $terms as $term ) {
			      echo "<option value=\"" . esc_html($term->name) . "\">" . $term->name . "</option>";
			    }
			    echo "</select>";
			}
	 		?>
			</dd>

			<dt>
				<label>By Actor: </label>
			</dt>
			<dd>
				<input type="text" name="themo_actor" />
			</dd>

			<dt>&nbsp;</dt>
			<dd><input type="submit" name="themo-sb-filter" value="Filter Results" /></dd>
		</dl>
		</form>

	</div><!--filtering options-->

	<hr style="clear:both"/>

	<?php

	echo '<div class="themo-items-ajax">';
	echo '</div>';

	else:
  		echo '<p>Sorry, no movies/tv shows found.</p>';
	endif;

}
 