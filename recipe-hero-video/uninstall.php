<?php
/**
 * Runs on Uninstall of Recipe Hero Video (deleted through WordPress admin)
 *
 * @package   Recipe Hero Video
 * @author    Captain Theme <info@captaintheme.com>
 * @license   GPL-2.0+
 * @link      http://captaintheme.com
 * @copyright 2014 Captain Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$options = array(
	'recipe_hero_video_archive';
);

foreach ( $options as $option ) {
	if ( get_option( $option ) ) {
		delete_option( $option );
	}
}