<?php
/**
 * Custom Columns for Recipe Hero Likes
 *
 * @package   Recipe Hero Likes
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 */

if ( ! class_exists( 'RH_Likes_Columns' ) ) :

class RH_Likes_Columns {

    protected static $instance = null;

    function __construct() {
 
        add_filter( 'manage_edit-recipe_columns', array( $this, 'column_header' ), 30, 2 ) ;
        add_action( 'manage_recipe_posts_custom_column', array( $this, 'column_content' ), 30, 2 );

    }

    /**
     * Start the Class when called
     *
     * @package Recipe Hero Likes
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

    // Column Header

    public function column_header( $columns ) {

        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Recipe Title' ),
            'id'            => __( 'ID' ),
            'course'        => __( 'Course' ),
            'cuisine'       => __( 'Cuisine' ),
            'ingredients'   => __( 'Ingredients' ),
            'photo'         => __( 'Photo' ),
            'author'        => __( 'Author' ),
            'comments'      => '<span class="dashicons dashicons-admin-comments"></span>',
            'likes'         => __( 'Likes' ),
            'date'          => __( 'Date' )
        );

        return $columns;
    }

    // Column Content

    public function column_content( $column, $post_id ) {
        
        global $post;

        switch( $column ) {

            case 'likes' :
                echo recipe_hero_likes_admin_output();
                break;

        }
        
    }

}

endif;