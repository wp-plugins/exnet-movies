<?php
/* Redirect - Modal Window Link Code */
function exnet_redirect() {

	global $wpdb;

	if(isset($_GET['link_id'])):
		$link_id = abs(intval($_GET['link_id']));

		$link = $wpdb->get_row(
					$wpdb->prepare("SELECT link_type, link_destination FROM exnet_links 
									WHERE linkID = %d", $link_id));

		if($link) {
			if($link->link_type == 'External') {
				printf('<meta http-equiv="refresh" content="0; url=%s" />', $link->link_destination);
				exit;
			}else{
				echo stripslashes($link->link_destination);
			}
		}else{
			printf('Link #%d not found', $link_id);
		}
	else:
		echo 'Please <b>REMOVE</b> this page from the <b>NAVIGATION</b><hr/>';

		echo 'You reached this page in error: #no link_id passed';
	endif;

}

add_shortcode('exnet_redirect', 'exnet_redirect');