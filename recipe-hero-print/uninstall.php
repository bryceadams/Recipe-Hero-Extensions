<?php
/**
 * Runs on Uninstall of Recipe Hero Submit (deleted through WordPress admin)
 *
 * @package   Recipe Hero Submit
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 * @since 	  1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$options = array(
	'rhprint_text',
	'rhprint_icon',
	'rhprint_gallery',
	'rhprint_cat',
	'rhprint_details',
	'rhprint_description',
	'rhprint_ingredients',
	'rhprint_instructions',
	'rhprint_message',
);

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}