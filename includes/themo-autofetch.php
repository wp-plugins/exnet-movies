<?php

function themo_auto_fetch() {
    ?>

    <h2>Autofetch &amp; Movie by IMDb URL</h2>

    <div class="updated below-h2">
    	Attention: use the url into this format: <strong>http://www.imdb.com/title/imdbID/</strong>. 
    	<br/>Extra parameters like ?ref=xx will cause the scrapper not work.
    </div>
    <br />

    <?php
    if(isset($_POST['themo_sb'])) {
    	if(isset($_POST['themo_url'])) {
    		$url = trim(strip_tags($_POST['themo_url']));
    		if(stristr($url, 'imdb.com')) {

    			require_once 'imdb.class.php';
    			
    			$IMDb = new IMDb;

    			$movie_data = $IMDb->scrapeMovieInfo($url, false);

    			if(count($movie_data) AND !isset($movie_data['error'])) {

    				// Create post object
					$my_post = array(
					  'post_title'    => $movie_data['title'],
					  'post_content'  => $movie_data['plot'],
					  'post_status'   => 'publish',
					  'post_author'   => 1,
					  'post_category' => array(),
					  'post_type' 	  => 'watch'
					);

					// Insert the post into the database
					$post_id = wp_insert_post( $my_post );

					// Upload poster
					$image_url = $movie_data['poster'];
					$upload_dir = wp_upload_dir();
					$image_data = file_get_contents($image_url);
					$filename = basename($image_url);
					if(wp_mkdir_p($upload_dir['path']))
					    $file = $upload_dir['path'] . '/' . $filename;
					else
					    $file = $upload_dir['basedir'] . '/' . $filename;
					file_put_contents($file, $image_data);

					$wp_filetype = wp_check_filetype($filename, null );
					$attachment = array(
					    'post_mime_type' => $wp_filetype['type'],
					    'post_title' => sanitize_file_name($filename),
					    'post_content' => '',
					    'post_status' => 'inherit'
					);
					$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
					require_once(ABSPATH . 'wp-admin/includes/image.php');
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
					wp_update_attachment_metadata( $attach_id, $attach_data );

					set_post_thumbnail( $post_id, $attach_id );

					// Create genres
					$terms = array();
					foreach($movie_data['genres'] as $term):
						$t_exists = term_exists( $term, 'genre' );
						if( !$t_exists ):
							$t = wp_insert_term( $term, 'genre' );
							$terms[] = $t['term_id'];
						else:
							$terms[] = $t_exists['term_id'];
						endif;
					endforeach;

					//add genres
					wp_set_post_terms( $post_id, $terms, 'genre' );

					//add type (movie/tv show etc.)
					if($_POST['themo_kind'] != 0)
						wp_set_post_terms( $post_id, (int) $_POST['themo_kind'], 'watch-by-type');

					//add imdb url
					add_post_meta( $post_id, 'themo_imdb_url', $url );

					//add release data
					add_post_meta( $post_id, 'themo_release', $movie_data['release_date'] );

					//add runtime
					add_post_meta( $post_id, 'themo_runtime', $movie_data['runtime'] . ' mins');

					//add initial meta rating & featured
					add_post_meta( $post_id, 'themo_rating', 'N/A');
					add_post_meta( $post_id, 'themo_featured', 'no');

					//add actors
					add_post_meta( $post_id, 'themo_actors', implode(", ", $movie_data['cast']) );


					if($post_id) echo '<h1><a href="post.php?post=' . $post_id . '&action=edit">Movie added. View in post editor.</a></h1>';

    			}
    		}else{
    			echo 'Failed to fetch info.';
    		}
    	}
    }
    ?>

    <form method="POST">
    	<input type="text" name="themo_url" size="50" placeholder="imdb url">
    	<select name="themo_kind">
    		<option value="0" selected="">Select Kind</option>
    		<?php
    		$kinds = get_terms('watch-by-type', 'hide_empty=0');
    		if(count($kinds)) {
    			foreach($kinds as $k) {
    				printf('<option value="%d">%s</option>', $k->term_id, $k->name);
    			}
    		}
    		?>
    	</select>
    	<input type="submit" name="themo_sb" class="button-primary" value="Fetch" />
	</form>


    <?php


}

