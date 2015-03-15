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
 * @package Recipe Hero
 * @author  Captain Theme <info@captaintheme.com>
 */

if ( ! class_exists( 'RH_Likes' ) ) :

class RH_Likes {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'recipe-hero-likes';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'body_class', array( &$this, 'body_class' ) );
        add_action( 'publish_post', array( &$this, 'setup_likes' ) );
        add_action( 'wp_ajax_recipe-hero-likes', array( &$this, 'ajax_callback' ) );
		add_action( 'wp_ajax_nopriv_recipe-hero-likes', array( &$this, 'ajax_callback' ) );
        add_action( 'widgets_init', create_function( '', 'register_widget( "Recipe_Hero_Likes_Widget" );' ) );
        add_shortcode( 'recipe_hero_likes', array( &$this, 'do_likes' ) );
	
	}

	/**
	 * Start the Class when called
	 *
	 * @package [Package Name]
	 * @author  Bryce Adams <bryce@bryce.se>
	 * @since   1.0.0
	 */

	public static function get_instance() {
	  // If the single instance hasn't been set, set it now.
	  if ( null == self::$instance ) {
		self::$instance = new self;
	  }
	  return self::$instance;
	}

	
	public function enqueue_scripts() {

		global $wp_styles;

		wp_enqueue_style( 'recipe-hero-likes', plugins_url( '/assets/css/recipe-hero-likes.css', __FILE__ ) );
		wp_enqueue_style( 'rhl-hearts', plugins_url( '/assets/css/rhl-hearts.css', __FILE__ ) );		
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'recipe-hero-likes', plugins_url( '/assets/js/recipe-hero-likes.js', __FILE__ ), array('jquery') );
		
		wp_localize_script( 'recipe-hero-likes', 'recipe_hero_likes', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	}
    
    public function admin_enqueue_scripts() {
	
        wp_enqueue_style( 'rhl-admin-css', plugins_url( '/assets/css/rhl-admin-css.css', __FILE__ ), array() );
        
    }
    
	public function the_content( $content ) {

		global $wp_current_filter;

		if ( in_array( 'get_the_excerpt', ( array ) $wp_current_filter ) ) {
			return $content;
		}
		
		$content = $this->do_likes();
		
		return $content;

	}
	
	public function setup_likes( $post_id ) {

		if ( ! is_numeric( $post_id ) ) return;
	
		add_post_meta( $post_id, '_recipe_hero_likes', '0', true );

	}
	
	public function ajax_callback($post_id) {

		if ( isset( $_POST['likes_id'] ) ) {
		    // Click event. Get and Update Count
			$post_id = str_replace( 'recipe-hero-likes-', '', $_POST['likes_id'] );
			echo $this->like_this( $post_id, 'update' );
		} else {
		    // AJAXing data in. Get Count
			$post_id = str_replace( 'recipe-hero-likes-', '', $_POST['post_id'] );
			echo $this->like_this( $post_id, 'get' );
		}
		
		exit;
	}
	
	public function like_this($post_id, $action = 'get') {
		
		if ( !is_numeric( $post_id ) ) return;
		$zero_postfix = rh_clean( get_option( 'rhlikes_postfix_none', __( 'Likes', 'recipe-hero-likes' ) ) );
		$one_postfix = rh_clean( get_option( 'rhlikes_postfix_single', __( 'Like', 'recipe-hero-likes' ) ) );
		$more_postfix = rh_clean( get_option( 'rhlikes_postfix_multiple', __( 'Likes', 'recipe-hero-likes' ) ) );		
		
		if ( get_option( 'rhlikes_heart_style' ) ) {
			$heart_class = get_option( 'rhlikes_heart_style' );
		} else {
			$heart_class = 'dashicons dashicons-heart';
		}

		switch( $action ) {
		
			case 'get':

				$likes = get_post_meta( $post_id, '_recipe_hero_likes', true );
				if( ! $likes ){
					$likes = 0;
					add_post_meta( $post_id, '_recipe_hero_likes', $likes, true );
				}
				
				if ( $likes == 0 ) {
					$postfix = $zero_postfix;
				} elseif ( $likes == 1 ) {
					$postfix = $one_postfix;
				} else {
					$postfix = $more_postfix;
				}
				
                return '<span class="heart-icon ' . $heart_class . '"></span><span class="recipe-hero-likes-count">'. $likes .'</span> <span class="recipe-hero-likes-postfix">'. $postfix .'</span>';
				break;
				
			case 'update':

				$likes = get_post_meta( $post_id, '_recipe_hero_likes', true );
				if ( isset( $_COOKIE['recipe_hero_likes_'. $post_id] ) ) return $likes;
				
				$likes++;
				update_post_meta( $post_id, '_recipe_hero_likes', $likes );
				setcookie( 'recipe_hero_likes_'. $post_id, $post_id, time()*20, '/' );
				
				if ( $likes == 0 ) {
					$postfix = $zero_postfix;
				} elseif ( $likes == 1 ) {
					$postfix = $one_postfix;
				} else {
					$postfix = $more_postfix;
				}
				
				return '<span class="heart-icon ' . $heart_class . '"></span><span class="recipe-hero-likes-count">'. $likes .'</span> <span class="recipe-hero-likes-postfix">'. $postfix .'</span>';
				break;
		
		}
	}
	
	public function do_likes() {

		global $post;

		$output = $this->like_this( $post->ID );
  
  		$class = 'recipe-hero-likes';
  		$title = __( 'Like this', 'Recipe_Hero_Likes' );
		if ( isset( $_COOKIE['recipe_hero_likes_'. $post->ID] ) ) {
			$class = 'recipe-hero-likes active';
			$title = __( 'You already like this', 'Recipe_Hero_Likes' );
		}
		
		return '<a href="#" class="'. $class .'" id="recipe-hero-likes-'. $post->ID .'" title="'. $title .'">'. $output .'</a>';
	
	}
	
    public function body_class( $classes ) {
        
       	$classes[] = 'ajax-recipe-hero-likes';
    	
    	return $classes;

    }
    
    function do_admin_likes() {

		global $post;

		$output = $this->like_this( $post->ID );
		
		return $output;
	
	}

}

endif;

/**
 * Template Tag
 */
function recipe_hero_likes_output() {

	$recipe_hero_likes = new RH_Likes();
    echo $recipe_hero_likes->do_likes();

}

function recipe_hero_likes_admin_output() {
    
    $recipe_hero_likes = new RH_Likes();
    echo $recipe_hero_likes->do_admin_likes();
    
}