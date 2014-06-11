<?php

/* IMDB URL metabox => Right Side */
function exnet_imdb_url() {
	global $post;
	
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="exnetmeta_noncename" id="exnetmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the imdb if its already been entered
	$imdb_url = get_post_meta( $post->ID, 'exnet_imdb_url', true );
	
	// Echo out the field
	echo '<label><h3 style="margin-left:0;padding-left:0;">Enter IMDB URL:</h3></label>';
	echo '<input type="text" name="exnet_imdb_url" value="' . $imdb_url  . '" class="widefat" placeholder="http://"/>';

}

/* Movie Details => Under Visual Editor */
function exnet_movie_details() {
	global $post;

	$runtime = get_post_meta( $post->ID, 'exnet_runtime', true );
	$release = get_post_meta( $post->ID, 'exnet_release', true );
	$rating = get_post_meta( $post->ID, 'exnet_rating', true );
	
	/*
	$featured = get_post_meta( $post->ID, 'exnet_featured', true );

	if($featured !== FALSE AND $featured == 'yes') {
		$exnet_is_featured = 'checked="checked"';
	}else{
		$exnet_not_featured = 'checked="checked"';
	}
	*/

	if(!$rating) $rating = 'N/A';

	?>
	<table width=860>
		<tr>
			<td><label><strong>Runtime:</strong> (ie. 121 mins)</label></td>
			<td><input type="text" name="exnet_runtime" value="<?= $runtime ?>" /></td>

			<td><label><strong>Release Date:</strong></label></td>
			<td><input type="text" name="exnet_release" value="<?= $release ?>" /></td>			
		</tr>
		<tr>
			<td>&nbsp;</td>
			<!--
			<td>
				<input type="radio" name="exnet_featured" value="yes" <?=@$exnet_is_featured ?>/> Yes 
				<input type="radio" name="exnet_featured" value="no" <?=@$exnet_not_featured ?>/> No 
			</td>
			-->
			<td>&nbsp;</td>
			<td><label><strong>Rating [0-5]:</strong>(calculated by star rating from users)</td>
			<td><input type="text" name="exnet_rating" value="<?= $rating ?>" /></td>
		</tr>
	</table>

	<?php 
}

// Add the Meta Boxes
function add_watch_metaboxes() {
	add_meta_box('wpwp_imdb_url', 'IMDB URL', 'exnet_imdb_url', 'watch', 'side', 'high');
	add_meta_box('wpwp_movie_details', 'Movie Details', 'exnet_movie_details', 'watch', 'normal', 'high');
}

add_action( 'add_meta_boxes', 'add_watch_metaboxes', 0 );

/* When the post is saved, saves our custom data */
function exnet_save_postdata( $post_id ) {
  // First we need to check if the current user is authorised to do this action. 
  if ( 'page' == @$_REQUEST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }
  $exnet_meta = array('exnet_runtime', 'exnet_release', 'exnet_featured', 'exnet_rating', 'exnet_imdb_url');

  //if saving in a custom table, get post_ID
  $post_ID = @$_POST['post_ID'];

  foreach ($exnet_meta as $meta_key) {
  	 if(isset($_POST[$meta_key])) update_post_meta($post_ID, $meta_key, $_POST[$meta_key]);
  }

}

add_action( 'save_post', 'exnet_save_postdata' );

/*
 * Admin Show Thumbnails into overview of movies/tv shows
 */
add_action("manage_posts_custom_column",  "exnet_custom_columns");
add_filter("manage_edit-watch_columns", "exnet_edit_columns");
 
function exnet_edit_columns($columns){
  $columns = array_merge(array("poster" => "Poster"), $columns);
  return $columns;

}
function exnet_custom_columns($column){
  global $post;
 
  switch ($column) {
  	case "poster":
  		echo get_the_post_thumbnail( $post->ID, array(50, 80) );
  	break;
  }
}