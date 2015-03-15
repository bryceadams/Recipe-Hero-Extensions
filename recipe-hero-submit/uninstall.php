<?php
/**
 * Runs on Uninstall of Recipe Hero Submit (deleted through WordPress admin)
 *
 * @package   Recipe Hero Submit
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$options = array(
	'rhs_default_status',
	'rhs_user_levels',
);

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}