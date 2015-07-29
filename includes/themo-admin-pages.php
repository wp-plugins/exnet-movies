<?php
require_once 'themo-autofetch.php';
require_once 'themo-manage-links.php';

/* settings page */
function themo_settings() {
	echo '<div class="wrap themo-wrapper">';
	echo '<h2>Watch Movie - Items Per Page / Row</h2>';

	if(isset($_POST['sb_themo'])) {
		update_option('themo_movies_per_page', abs(intval($_POST['themo_movies_per_page'])));
		update_option('themo_movies_per_row', abs(intval($_POST['themo_movies_per_row'])));
		update_option('themo_homepage_items', abs(intval($_POST['themo_homepage_items'])));

		echo '<div class="updated below-h2">Updated</div>';

	}

	$themo_movies_per_page = get_option('themo_movies_per_page', 10); 
	$themo_movies_per_row = get_option('themo_movies_per_row', 5); 
	$themo_homepage_items = get_option('themo_homepage_items', 5);
	?>

	<form method="POST">
	<label>Items Per Page: </label>
		<input type="number" value="<?=$themo_movies_per_page ?>" name="themo_movies_per_page" />	

	<br />

	<label>Items Per Row: </label>
		<input type="number" value="<?=$themo_movies_per_row ?>" name="themo_movies_per_row" />
	
	<br />

	<label>Items Per Homepage: </label>
		<input type="number" value="<?=$themo_homepage_items ?>" name="themo_homepage_items" />
	
	<br />

	<input type="submit" name="sb_themo" value="Save" class="button-primary" />
	</form>
	<?php
	echo '</div>';
}

/* create admin menu */
function themo_admin_menu() {
	add_submenu_page( 'edit.php?post_type=watch', 'Watch Movie Autofetch', 'Autofetch Movie Info', 'manage_options', 'themo-autofetch', 'themo_auto_fetch' );
	add_submenu_page( 'edit.php?post_type=watch', 'Watch Movie Links', 'Movie Links', 'manage_options', 'themo-links', 'themo_manage_links' );
    add_submenu_page( 'edit.php?post_type=watch', 'Watch Movie Settings', 'Items Per Page/Row', 'manage_options', 'themo-settings', 'themo_settings' );
}

add_action('admin_menu', 'themo_admin_menu');