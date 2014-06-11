<?php

function exnet_manage_links() {
	global $wpdb;

	?>
	<div class="wrap">
		<?php if(!isset($_GET['do'])) : ?>

		<h2>Movie Links (<a href="edit.php?post_type=watch&amp;page=exnet-links&amp;do=new">Add new</a>)</h2>

		<form method="post">
		Filter by Movie: 
		<select name="movie-filter-id">
			<option value="0">--select movie--</option>
			<?php 
			$m = get_posts('post_type=watch&posts_per_page=-1&orderby=title&order=ASC');
			if(count($m)) {
				foreach($m as $movie) {
					printf('<option value="%d"%s>%s</option>', $movie->ID, $selected, $movie->post_title);
				}
			}
			?>
		</select>
		<input type="submit" name="sb-movie-filter" value="OK" class="button" />
		</form>
		<hr />

		<?php

		if(isset($_GET['act']) AND ($_GET['act'] == 'remove') AND isset($_GET['id'])) {
			$wpdb->delete( 'exnet_links', array( 'linkID' => (int) $_GET['id'] ), array( '%d' ) );
			echo '<div class="updated bellow-h2">Link removed</div>';
		}

		if(isset($_GET['act']) AND ($_GET['act'] == 'approve') AND isset($_GET['id'])) {
			$wpdb->update( 'exnet_links', array('status' => 'approved'), array( 'linkID' => (int) $_GET['id'] ) );
			echo '<div class="updated bellow-h2">Link approved</div>';
		}

		if(isset($_POST['movie-filter-id'])) 
			$movie_links = $wpdb->get_results($wpdb->prepare("SELECT * FROM exnet_links WHERE mID = %d ORDER BY linkID DESC", (int) $_POST['movie-filter-id']));
		else
			$movie_links = $wpdb->get_results("SELECT * FROM exnet_links ORDER BY linkID DESC");

		if(!count($movie_links)) {
			echo ' - None yet - ';
		}else{
		?>	
		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th>Movie</th>
					<th>Link Tab</th>
					<th>Link Title</th>
					<th>Destination</th>
					<th>OK / Broken</th>
					<th>Status</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($movie_links as $l) : ?>
					<?php
					$status = ($l->status == 'pending') ? "Pending => <a href='edit.php?post_type=watch&amp;page=exnet-links&amp;act=approve&amp;id=".$l->linkID."'>Approve</a>"  : 'Live';
					$l_movie = get_the_title($l->mID);
					?>
					<tr>
						<td><h3><?=$l_movie; ?></h3></td>
						<td><strong><?=$l->link_tab; ?></strong></td>
						<td><?=$l->link_title; ?></td>
						<td><?php
							if($l->link_type == 'External') : 
								printf('<a href="%s" target="_blank">%s</a>', $l->link_destination, $l->link_destination); 
							else:
								echo htmlspecialchars(stripslashes($l->link_destination));
							endif;
							?>
						</td>
						<td><?=$l->link_ok?> ok / <?=$l->link_broken?> broken</td>
						<td><?=$status?></td>
						<td><br />
							<a href="edit.php?post_type=watch&amp;page=exnet-links&amp;do=update&amp;id=<?=$l->linkID?>">Edit</a><br />
							<a href="edit.php?post_type=watch&amp;page=exnet-links&amp;act=remove&amp;id=<?=$l->linkID?>">Remove</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		}
		?>

		<?php 
		elseif (isset($_GET['do'])) :
			if($_GET['do'] == 'new') exnet_add_link();
			if($_GET['do'] == 'update' && isset($_GET['id'])) exnet_update_link((int) $_GET['id']);
		endif;
		?>

	</div>
	<?php
}

function exnet_add_link() {
	global $wpdb;

	echo '<h2>New link</h2>';

	if(isset($_POST['EXNET-sb-link'])) {
		if($_POST['mID'] > 0 AND $_POST['link_tab'] != "" AND $_POST['link_title'] != "" AND $_POST['link_destination'] != "") {
			unset($_POST['EXNET-sb-link']);
			$_POST['status'] = 'approved';

			if($wpdb->insert('exnet_links', $_POST)) {
				echo '<div class="updated bellow-h2">Link saved successfully</div>';	
			}else{
				echo '<div class="updated bellow-h2">WP DB Error!</div>';
				$wpdb->show_errors();
				$wpdb->print_error();
			}
		}else{
			echo '<div class="updated bellow-h2">Error! Please complete all fields</div>';
		}
	}

	?>

	<form method="post">
		Movie:<br/>
		<select name="mID">
			<option value="0">- Select Movie -</option>
			<?php 
			$m = get_posts('post_type=watch&posts_per_page=-1&orderby=title&order=ASC');
			if(count($m)) {
				foreach($m as $movie) printf('<option value="%d">%s</option>', $movie->ID, $movie->post_title);
			}
			?>
		</select><br/>
		Tab Name:<br/> <input type="text" name="link_tab" placeholder="Series 1, Series 2, etc." class="input-xxlarge" size="50"/><br/>
		Title:<br/> <input type="text" name="link_title" placeholder="HQ Server #1" class="input-xxlarge" size="50"/><br/>
		Type:<br/>
		<select name="link_type">
			<option>External</option>
			<option>Embed</option>
		</select>

		<br/>
		Link/Embed Code:<br/>
		<textarea name="link_destination" placeholder="http://www.example.com" class="input-xxlarge" rows="5" cols="60"/></textarea>

		<br/>
		<input type="submit" class="btn btn-medium btn-info" value="Save" name="EXNET-sb-link"/>
	</form>

	<?php

}

function exnet_update_link($id) {

	global $wpdb;

	echo '<h2>Update link</h2>';

	if(isset($_POST['EXNET-sb-link'])) {
		if($_POST['mID'] > 0 AND $_POST['link_tab'] != "" AND $_POST['link_title'] != "" AND $_POST['link_destination'] != "") {
			unset($_POST['EXNET-sb-link']);
			$_POST['status'] = 'approved';

			if($wpdb->update('exnet_links', $_POST, array('linkID' => $id))) {
				echo '<div class="updated bellow-h2">Link saved successfully</div>';	
			}else{
				echo '<div class="updated bellow-h2">WP DB Error!</div>';
				$wpdb->show_errors();
				$wpdb->print_error();
			}
		}else{
			echo '<div class="updated bellow-h2">Error! Please complete all fields</div>';
		}
	}

	$the_link = $wpdb->get_row($wpdb->prepare("SELECT * FROM exnet_links WHERE linkID = %d", $id));
	if(!$the_link) die("Link id #" . $id . " could not be found");

	?>

	<form method="post">
		Movie:<br/>
		<select name="mID">
			<?php 
			$m = get_posts('post_type=watch&posts_per_page=-1&orderby=title&order=ASC');
			if(count($m)) {
				foreach($m as $movie) {
					$selected = ($movie->ID == $the_link->mID) ? ' selected=""' : '';
					printf('<option value="%d"%s>%s</option>', $movie->ID, $selected, $movie->post_title);
				}
			}
			?>
		</select><br/>
		Tab Name:<br/> <input type="text" name="link_tab" value="<?=$the_link->link_tab ?>" class="input-xxlarge" size="50"/><br/>
		Title:<br/> <input type="text" name="link_title" value="<?=$the_link->link_title ?>" class="input-xxlarge" size="50"/><br/>
		Type:<br/>
		<select name="link_type">
			<option<?php if($the_link->link_type == 'External') echo ' selected=""'; ?>>External</option>
			<option<?php if($the_link->link_type == 'Embed') echo ' selected=""'; ?>>Embed</option>
		</select>

		<br/>
		Link/Embed Code:<br/>
		<textarea name="link_destination" class="input-xxlarge" rows="5" cols="60"/><?=stripslashes($the_link->link_destination) ?></textarea>

		<br/>
		<input type="submit" class="btn btn-medium btn-info" value="Save" name="EXNET-sb-link"/>
	</form>

	<?php

}