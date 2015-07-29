<?php


/**
 * Genres Widget Class
 */
class themo_genres_widget extends WP_Widget {

    /** constructor */
    function themo_genres_widget() {
        parent::WP_Widget(false, $name = 'Movie Genres Widget');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
		global $wpdb;

        $title = apply_filters('widget_title', $instance['title']);
		$gravatar = $instance['gravatar'];
		$count = $instance['count'];

		if(!$size)
			$size = 40;

        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<?php 
              $terms = get_terms("genre", "hide_empty=0");
							$genre_count = count($terms);
							if ( $genre_count > 0 ){
								echo '<ul>';
							    foreach ( $terms as $term ) {
							      echo "<li><a href=\"".get_bloginfo('url')."/watch-genre/" . $term->slug . "\">" . $term->name . "</li>";
							    }
							    echo '</ul>';
							}
							?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	

        $title = esc_attr($instance['title']);

        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>


        <?php
    }

} // class utopian_recent_posts
add_action('widgets_init', create_function('', 'return register_widget("themo_genres_widget");'));
