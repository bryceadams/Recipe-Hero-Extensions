<?php
/**
 * Recipe Hero Submit - Form
 *
 * @package   Recipe Hero Submit
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since     1.0.0
 * @todo      Debug for missing fields etc.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! class_exists( 'RH_Submit_Form' ) ) :

class RH_Submit_Form {

    public $prefix = '_recipe_hero_submit_';

    /**
     * Construct the class.
     */
    public function __construct() {

        if ( ! is_admin() ) {
            add_filter( 'cmb2_meta_boxes', array( $this, 'cmb2_metaboxes' ) );
            add_shortcode( 'recipe_hero_submit', array( $this, 'do_frontend_form' ) );
            add_action( 'init', array( $this, 'initialize_cmb2_meta_boxes' ), 9 );
        }

        add_action( 'wp_enqueue_scripts', array( $this, 'assets' ) );

    }

    /**
     * Assets
     */
    public function assets() {

        wp_register_style( 'rh-submit', plugin_dir_url( __FILE__ ) . '../assets/css/rh-submit-styles.css' );

    }


    /**
     * Define the metabox and field configurations.
     */
    public function cmb2_metaboxes( array $meta_boxes ) {

        // Start with an underscore to hide fields from custom fields list
        $prefix = '_recipe_hero_submit_';

        /**
         * Recipe Details
         */
        $meta_boxes['recipe_details_front'] = array(
            'id'         => 'details_container_front',
            'title'      => __( 'Recipe Details', 'recipe-hero-submit' ),
            'object_types'      => array( 'recipe', ), // Post type
            'context'    => 'normal',
            'priority'   => 'high',
            'show_names' => true, // Show field names on the left
            'fields'     => array(
                array(
                    'name' => __( 'Recipe Name', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_name',
                    'type' => 'text_medium',
                ),
                array(
                    'name' => __( 'Recipe Cuisine', 'recipe-hero-submit' ),
                    'desc' => __( 'What cuisine(s) does this recipe belong to?', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_cuisine',
                    'taxonomy' => 'cuisine',
                    'type' => 'taxonomy_multicheck',
                ),
                array(
                    'name' => __( 'Recipe Course', 'recipe-hero-submit' ),
                    'desc' => __( 'What course(s) does this recipe belong to?', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_course',
                    'taxonomy' => 'course',
                    'type' => 'taxonomy_multicheck',
                ),
                array(
                    'name' => __( 'Recipe Completed Photo', 'recipe-hero-submit' ),
                    'desc' => __( 'Upload an image using the media uploader (optional)', 'recipe-hero-submit' ),
                    'id'   => '_recipe_hero_completed_image',
                    'type' => 'file',
                    'allow' => array( 'attachment' ), // only attachments allowed --> no URLs
                ),
                 array(
                    'name' => __( 'Recipe Description', 'recipe-hero-submit' ),
                    'desc' => __( 'Maybe a story behind the recipe or a description of it.', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_description',
                    'type' => 'textarea',
                ),
                array(
                    'name' => __( 'Servings', 'recipe-hero-submit' ),
                    'desc' => __( 'A number for the servings amount', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_serves',
                    'type' => 'text_small',
                    'attributes' => array(
                        'placeholder' => 'eg. 4',
                    ),
                ),
                array(
                    'name' => __( 'Servings Type', 'recipe-hero-submit' ),
                    'desc' => __( 'The type of servings that relates to the Servings Amount, like how many people it will feed or how many burgers it can make, etc.', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_serves_type',
                    'type' => 'text_small',
                    'attributes' => array(
                        'placeholder' => __( 'eg. People', 'recipe-hero-submit' ),
                    ),
                ),
                array(
                    'name' => __( 'Preparation Time', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_prep_time',
                    'type' => 'text_small',
                    'before' => ' ',
                    'after' => __( 'Minutes', 'recipe-hero-submit' ),
                    'attributes' => array(
                        'placeholder' => 'eg. 15',
                    ),
                ),
                array(
                    'name' => __( 'Cooking Time', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_cook_time',
                    'type' => 'text_small',
                    'before' => ' ',
                    'after' => __( 'Minutes', 'recipe-hero-submit' ),
                    'attributes' => array(
                        'placeholder' => 'eg. 85',
                    ),
                ),
                array(
                    'name' => __( 'Equipment Needed', 'recipe-hero-submit' ),
                    'desc' => __( 'Any special equipment worthy of mentioning that is required for this recipe.', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_equipment',
                    'type' => 'text_medium',
                    'repeatable' => true,
                    'attributes' => array(
                        'placeholder' => 'eg. Thermomix, Slow-Cooker',
                    ),
                ),
                array(
                    'name' => __( 'Nutritional Info', 'recipe-hero-submit' ),
                    'desc' => __( 'A summary of nutrition information you would like to include.', 'recipe-hero-submit' ),
                    'id'   => $prefix . 'detail_nutrition',
                    'type' => 'textarea_small',
                    'attributes' => array(
                        'placeholder' => __( 'eg. Per serving: Calories (kcal) 657.7', 'recipe-hero-submit' ),
                    ),
                ),
                array(
                    'id'          => $prefix . 'ingredients_group',
                    'type'        => 'group',
                    'name'          => __( 'Ingredients', 'recipe-hero-submit' ),
                    'options'     => array(
                        'group_title'   => __( 'Ingredient {#}', 'recipe-hero-submit' ), // {#} gets replaced by row number
                        'add_button'    => __( 'Add Another Ingredient', 'recipe-hero-submit' ),
                        'remove_button' => __( 'Remove Ingredient', 'recipe-hero-submit' ),
                        'sortable'      => true,
                    ),
                    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
                    'fields'      => apply_filters( 'recipe_hero_meta_ingredients_fields', array(
                        array(
                            'name' => __( 'Quantity', 'recipe-hero-submit' ),
                            'id'   => 'quantity',
                            'type' => 'text_small',
                            // Should it be a text input and a select input for 1/2, 1/3 etc.? How do we take fractions - fraction or decimal?
                        ),
                        array(
                            'name'    => __( 'Amount', 'recipe-hero-submit' ),
                            'id'      => 'amount',
                            'type'    => 'select', // will use 'pw_select' when issue is fixed - https://github.com/mustardBees/cmb-field-select2/issues/10
                            'options' => apply_filters( 'recipe_hero_meta_ingredients_amount', array(
                                'gm'     => __( 'Gram (gm)', 'recipe-hero-submit' ),
                                'oz'     => __( 'Ounce (oz)', 'recipe-hero-submit' ),
                                'ml'     => __( 'Milliliter (ml)', 'recipe-hero-submit' ),
                                'ts'     => __( 'Teaspoon', 'recipe-hero-submit' ),
                                'tas'    => __( 'Tablespoon', 'recipe-hero-submit' ),
                                'cup'    => __( 'Cup', 'recipe-hero-submit' ),
                                'lt'     => __( 'Liter (L)', 'recipe-hero-submit' ),
                                'lb'     => __( 'Pound (lb)', 'recipe-hero-submit' ),
                                'kg'     => __( 'Kilogram (kg)', 'recipe-hero-submit' ),
                                'slice'  => __( 'Slices', 'recipe-hero-submit' ),
                                'piece'  => __( 'Pieces', 'recipe-hero-submit' ),
                                'none'   => __( 'None (blank)', 'recipe-hero-submit' ),
                            ) ),
                            'default'   => apply_filters( 'recipe_hero_meta_ingredients_amount_default', 'gm' ),
                            //'sanitization_cb' => 'pw_select2_sanitise',
                        ),
                        array(
                            'name' => __( 'Ingredient', 'recipe-hero-submit' ),
                            'id'   => 'name',
                            'type' => 'text_medium',
                            'attributes' => array(
                                'class' => 'ingredient_name_field',
                                'placeholder' => __( 'eg. Bacon, Celery, Flour', 'recipe-hero-submit' ),
                            ),
                        ),
                    ) ),
                ),
                array(
                    'id'          => $prefix . 'steps_group',
                    'type'        => 'group',
                    'name' => __( 'Steps / Instructions', 'recipe-hero-submit' ),
                    'options'     => array(
                        'group_title'   => __( 'Step {#}', 'recipe-hero-submit' ), // {#} gets replaced by row number
                        'add_button'    => __( 'Add Another Step', 'recipe-hero-submit' ),
                        'remove_button' => __( 'Remove Step', 'recipe-hero-submit' ),
                        'sortable'      => true,
                    ),
                    // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
                    'fields'      => apply_filters( 'recipe_hero_meta_steps_fields', array(
                        array(
                            'name' => __( 'Instructions', 'recipe-hero-submit' ),
                            'id'   => '_recipe_hero_step_instruction',
                            'type' => 'textarea',
                        ),
                        array(
                            'name' => __( 'Step Photo', 'recipe-hero-submit' ),
                            'desc' => __( 'Upload an image using the media uploader (optional)', 'recipe-hero-submit' ),
                            'id'   => '_recipe_hero_step_image',
                            'type' => 'file',
                            'allow' => array( 'attachment' ), // only attachments allowed --> no URLs
                        ),
                        // Add time per step?
                    ), $prefix ),
                ),
            ),
        );

        return $meta_boxes;
    
    }


    /**
     * Shortcode to display a CMB form for a post ID.
     */
    public function do_frontend_form() {

        $roles = get_option( 'rhs_user_levels' );

        // Check if guest or if user role matches @todo move to its own method
        if ( in_array( 'guest', $roles ) ) {
            $display = true;
        } else {
            if ( $this->check_user_role( $roles ) ) {
                $display = true;
            } else {
                $display = false;
            }
        }
       
        // Only display if we should
        if ( $display ) {

            // Enqueue Assets
            wp_enqueue_style( 'rh-submit' );

            // Metabox ID
            $metabox_details = 'recipe_details_front';

            // Get all metaboxes
            $meta_boxes = apply_filters( 'cmb2_meta_boxes', array() );

            // Start post id at 0
            $post_id = 0;

            // If create post successful
            if ( $new_id = $this->intercept_post_id() ) {
                $post_id = $new_id;
                echo apply_filters( 'recipe_hero_submit_thankyou_message', __( 'Thank You for submitting your recipe!', 'recipe-hero-submit' ) );
            } else {
                if ( $_POST ) {
                    echo apply_filters( 'recipe_hero_submit_sorry_message', __( 'Sorry! There was a problem submitting your recipe.', 'recipe-hero-submit' ) );
                }
            }

            // Shortcodes need to return their data, not echo it.
            $echo = false;

            // Get our form
            $form = cmb2_metabox_form( $meta_boxes[ $metabox_details ], $post_id, $echo );

            return $form;

        }

    }

    /**
     * Get data before saving to CMB.
     */
    public function intercept_post_id() {

        // Check for $_POST data
        if ( empty( $_POST ) ) {
            $return = false;
        }

        // @todo Check nonce

        // Setup and sanitize data
        if ( isset( $_POST[ $this->prefix . 'detail_name' ] ) ) {

            add_filter( 'user_has_cap', array( $this, 'grant_publish_caps' ), 0,  3);

            $insert_args = array(
                'post_title'            => sanitize_text_field( $_POST[ $this->prefix . 'detail_name' ] ),
                'post_author'           => get_current_user_id(),
                'post_type'             => 'recipe',
                'post_content_filtered' => wp_kses( $_POST[ $this->prefix . 'detail_description' ], '<b><strong><i><em><h1><h2><h3><h4><h5><h6><pre><code><span>' ),
            );

            if ( get_option( 'rhs_default_status' ) ) {
                $insert_args['post_status'] = get_option( 'rhs_default_status' );
            } else {
                $insert_args['post_status'] = 'draft'; // Default set to draft so we can review first
            }

            $this->new_submission = wp_insert_post( $insert_args, true );

            // If no errors, save the data into a new post draft
            if ( ! is_wp_error( $this->new_submission ) ) {
                $return = $this->new_submission;
            } else {
                $return = false;
            }

        } else {

            $return = false;

        }

        // If return is ok (no errors), do some more stuff
        if ( $return ) {

            // Save Thumbnail
            if ( isset( $_POST['_recipe_hero_completed_image_id'] ) ) {
                update_post_meta( $return, '_thumbnail_id', sanitize_text_field( $_POST['_recipe_hero_completed_image_id'] ) );
            }

            // Set cuisine terms
            if ( isset( $_POST['_recipe_hero_submit_detail_cuisine'] ) ) {
                $cuisines = $_POST['_recipe_hero_submit_detail_cuisine'];
                $cuisines_ids = array();
                foreach ( $cuisines as $cuisine ) {
                    $cuisines_term = get_term_by( 'slug', $cuisine, 'cuisine', 'ARRAY_A' );
                    $cuisines_ids[] = $cuisines_term['term_taxonomy_id'];
                }
                $cuisines_ids = array_map( 'intval', $cuisines_ids );
                $cuisines_ids = array_unique( $cuisines_ids );
                wp_set_object_terms( $return, sanitize_text_field( $cuisines_ids ), 'cuisine' );
            }

            // Set course terms
            if ( isset( $_POST['_recipe_hero_submit_detail_course'] ) ) {
                $courses = $_POST['_recipe_hero_submit_detail_course'];
                $courses_ids = array();
                foreach ( $courses as $course ) {
                    $courses_term = get_term_by( 'slug', $course, 'course', 'ARRAY_A' );
                    $courses_ids[] = $courses_term['term_taxonomy_id'];
                }
                $courses_ids = array_map( 'intval', $courses_ids );
                $courses_ids = array_unique( $courses_ids );
                wp_set_object_terms( $return, sanitize_text_field( $courses_ids ), 'course' );
            }

            // Save Serves Amount
            if ( isset( $_POST['_recipe_hero_submit_detail_serves'] ) ) {
                update_post_meta( $return, '_recipe_hero_detail_serves', sanitize_text_field( $_POST['_recipe_hero_submit_detail_serves'] ) );
            }

            // Save Serves Type
            if ( isset( $_POST['_recipe_hero_submit_detail_serves_type'] ) ) {
                update_post_meta( $return, '_recipe_hero_detail_serves_type', sanitize_text_field( $_POST['_recipe_hero_submit_detail_serves_type'] ) );
            }

            // Prep Time
            if ( isset( $_POST['_recipe_hero_submit_detail_prep_time'] ) ) {
                update_post_meta( $return, '_recipe_hero_detail_prep_time', sanitize_text_field( $_POST['_recipe_hero_submit_detail_prep_time'] ) );
            }

            // Cook Time
            if ( isset( $_POST['_recipe_hero_submit_detail_cook_time'] ) ) {
                update_post_meta( $return, '_recipe_hero_detail_cook_time', sanitize_text_field( $_POST['_recipe_hero_submit_detail_cook_time'] ) );
            }

            // Equipment
            if ( isset( $_POST['_recipe_hero_submit_detail_equipment'] ) ) {
                $equipment_post = $_POST['_recipe_hero_submit_detail_equipment'];
                $equipment_post = array_filter( $equipment_post );
                if ( ! empty( $equipment_post ) ) {
                    update_post_meta( $return, '_recipe_hero_detail_equipment', wp_kses( $equipment_post, '<b><strong><i><em><h1><h2><h3><h4><h5><h6><pre><code><span>' ) );
                }
            }

            // Nutrition
            if ( isset( $_POST[ $this->prefix . 'detail_nutrition' ] ) ) {
                $nutrition_post = wp_kses( $_POST[ $this->prefix . 'detail_nutrition' ], '<b><strong><i><em><h1><h2><h3><h4><h5><h6><pre><code><span>' );
                update_post_meta( $return, '_recipe_hero_detail_nutrition', $nutrition_post );
            }

            // Ingredients
            if ( isset( $_POST['_recipe_hero_submit_ingredients_group'] ) ) {
                $ingredients_post = $_POST['_recipe_hero_submit_ingredients_group'];
                $ingredients_post = array_filter( $ingredients_post );
                if ( ! empty( $ingredients_post ) ) {
                    $ingredients_mapped = $this->array_sanitize( 'esc_textarea', $ingredients_post );
                    update_post_meta( $return, '_recipe_hero_ingredients_group', $ingredients_mapped );
                }
            }

            // Steps
            if ( isset( $_POST['_recipe_hero_submit_steps_group'] ) ) {
                $steps_post = $_POST['_recipe_hero_submit_steps_group'];
                $steps_post = array_filter( $steps_post );
                if ( ! empty( $steps_post ) ) {
                    $steps_mapped = $this->array_sanitize( 'esc_textarea', $steps_post );
                    update_post_meta( $return, '_recipe_hero_steps_group', $steps_mapped );
                }
            }

        }

        return $return;
    }

    // Helper method for sanitizing multi-dimensional arrays
    public function array_sanitize( $func, $arr ) {

        $newArr = array();

        foreach( $arr as $key => $value ) {
            $newArr[ $key ] = ( is_array( $value ) ? $this->array_sanitize( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
        }

        return $newArr;

    }

    /**
     * Checks if a particular user has a role. 
     * Returns true if a match was found.
     *
     * @param string $role Role name.
     * @param int $user_id (Optional) The ID of a user. Defaults to the current user.
     * @return bool
     */
    public function check_user_role( $roles, $user_id = null ) {
     
        if ( is_numeric( $user_id ) ) {
            $user = get_userdata( $user_id );
        } else {
            $user = wp_get_current_user();
        }
     
        if ( empty( $user ) ) {
            return false;
        }

        $user_role = implode( '', $user->roles );
     
        return in_array( $user_role, $roles );
    }
     

    /**
     * Grant temporary permissions to subscribers.
     */
    public function grant_publish_caps( $caps, $cap, $args ) {

        if ( 'edit_post'  == $args[0] ) {
            $caps[$cap[0]] = true;
        }

        return $caps;
    }

    /**
     * Initialize CMB.
     */
    public function initialize_cmb2_meta_boxes() {

        if ( ! class_exists( 'CMB2' ) ) {
            require_once RH_CMB2_DIR;
        }

    }

}

endif;

$RH_Submit_Form = new RH_Submit_Form();