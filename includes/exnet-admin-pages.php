<?php
require_once 'exnet-autofetch.php';
require_once 'exnet-manage-links.php';

/* settings page */
function exnet_settings() {
	echo '<div class="wrap exnet-wrapper">';
	echo '<h2>ExNet Watch Movies - Items Per Page / Row</h2>';

	if(isset($_POST['sb_exnet'])) {
		update_option('exnet_movies_per_page', abs(intval($_POST['exnet_movies_per_page'])));
		update_option('exnet_movies_per_row', abs(intval($_POST['exnet_movies_per_row'])));
		update_option('exnet_homepage_items', abs(intval($_POST['exnet_homepage_items'])));

		echo '<div class="updated below-h2">Updated</div>';

	}

	$exnet_movies_per_page = get_option('exnet_movies_per_page', 10); 
	$exnet_movies_per_row = get_option('exnet_movies_per_row', 5); 
	$exnet_homepage_items = get_option('exnet_homepage_items', 5);
	?>

	<form method="POST">
	<label>Items Per Page: </label>
		<input type="number" value="<?=$exnet_movies_per_page ?>" name="exnet_movies_per_page" />	

	<br />

	<label>Items Per Row: </label>
		<input type="number" value="<?=$exnet_movies_per_row ?>" name="exnet_movies_per_row" />
	
	<br />

	<label>Items Per Homepage: </label>
		<input type="number" value="<?=$exnet_homepage_items ?>" name="exnet_homepage_items" />
	
	<br />

	<input type="submit" name="sb_exnet" value="Save" class="button-primary" />
	</form>
	<?php
	echo '</div>';
}

/* create admin menu */
function exnet_admin_menu() {
	add_submenu_page( 'edit.php?post_type=watch', 'ExNet Movies Autofetch', 'Autofetch Movie Info', 'manage_options', 'exnet-autofetch', 'exnet_auto_fetch' );
	add_submenu_page( 'edit.php?post_type=watch', 'ExNet Movies Links', 'Movie Links', 'manage_options', 'exnet-links', 'exnet_manage_links' );
    add_submenu_page( 'edit.php?post_type=watch', 'ExNet Movies Settings', 'Items Per Page/Row', 'manage_options', 'exnet-settings', 'exnet_settings' );   
}

add_action('admin_menu', 'exnet_admin_menu');