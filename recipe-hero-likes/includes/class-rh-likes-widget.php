<?php
/**
 * @package   Recipe Hero Likes
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Widget to display posts by likes popularity
 *
 * @package Recipe Hero
 * @author  Captain Theme <info@captaintheme.com>
 * @TODO Make if Class Exists (?)
 */

class Recipe_Hero_Likes_Widget extends WP_Widget {

	function __construct() {
		parent::WP_Widget( 'recipe_hero_likes_widget', 'Recipe Hero Likes', array( 'description' => __( 'Displays your most popular recipes sorted by most liked', 'recipe-hero-likes' ) ) );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$desc = $instance['description'];
		$posts = empty( $instance['posts'] ) ? 1 : $instance['posts'];
		$display_count = $instance['display_count'];

		if ( get_option( 'rhlikes_heart_style' ) ) {
			$heart_class = get_option( 'rhlikes_heart_style' );
		} else {
			$heart_class = 'dashicons dashicons-heart';
		}

		// Output our widget
		echo $before_widget;
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;

		if ( $desc ) echo '<p>' . $desc . '</p>';

		$likes_posts_args = array(
			'numberposts' => $posts,
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
			'meta_key' => '_recipe_hero_likes',
			'post_type' => 'recipe',
			'post_status' => 'publish'
		);
		$likes_posts = get_posts( $likes_posts_args );

		echo '<ul class="recipe-hero-likes-popular-posts">';
		foreach( $likes_posts as $likes_post ) {
			$count_output = '';
			if ( $display_count ) {
				$count = get_post_meta( $likes_post->ID, '_recipe_hero_likes', true );
				$count_output = ' <span class="recipe-hero-likes-count"><span class="heart-icon ' . $heart_class . '"></span>' . $count . '</span>';
			}
			echo '<li><a href="' . get_permalink( $likes_post->ID ) . '">' . get_the_title( $likes_post->ID ) . '</a>' . $count_output . '</li>';
		}
		echo '</ul>';

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['description'] = strip_tags( $new_instance['description'], '<a><b><strong><i><em><span>' );
		$instance['posts'] = strip_tags( $new_instance['posts'] );
		$instance['display_count'] = strip_tags( $new_instance['display_count'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args(
			( array ) $instance
		);

		$defaults = array(
			'title' => __( 'Popular Posts', 'recipe-hero-likes' ),
			'description' => '',
			'posts' => 5,
			'display_count' => 1
		);

		$instance = wp_parse_args( ( array ) $instance, $defaults );

		$title = $instance['title'];
		$description = $instance['description'];
		$posts = $instance['posts'];
		$display_count = $instance['display_count'];
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'recipe-hero-likes' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description:', 'recipe-hero-likes' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo $description; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts' ); ?>"><?php _e( 'Posts:', 'recipe-hero-likes' ); ?></label> 
			<input id="<?php echo $this->get_field_id( 'posts' ); ?>" name="<?php echo $this->get_field_name( 'posts' ); ?>" type="text" value="<?php echo $posts; ?>" size="3" />
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'display_count' ); ?>" name="<?php echo $this->get_field_name( 'display_count' ); ?>" type="checkbox" value="1" <?php checked( $display_count ); ?>>
			<label for="<?php echo $this->get_field_id( 'display_count' ); ?>"><?php _e( 'Display like counts', 'recipe-hero-likes' ); ?></label>
		</p>

		<?php
	}
}