<?php
/**
Plugin Name: JGC Google reCAPTCHA
Description: This plugin adds Google reCAPTCHA to forms of your WordPress site and protect it from spam and abuse while letting real people pass through with ease.
Version: 1.2.4
Author: GalussoThemes
Author URI: https://galussothemes.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: jgc-google-recaptcha
Domain Path: /languages

JGC Google reCAPTCHA is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

JGC Google reCAPTCHA is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JGC Google reCAPTCHA. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

/**
 *
 * @package JGC Google reCAPTCHA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JGCGRC_PLUGIN_NAME', 'JGC Google reCAPTCHA' );
define( 'JGCGRC_VERSION', '1.2.4' );
define( 'JGCGRC_SITE_LANG', substr( get_bloginfo( 'language' ), 0, 2 ) );

/**
 * Set default options.
 *
 * @since 1.0.0
 */
function jgcgrc_set_default_options() {

	$options = array(
		'jgcgrc_site_key'                    => '',
		'jgcgrc_secret_key'                  => '',
		'jgcgrc_comments_recaptcha'          => 'on',
		'jgcgrc_comments_recaptcha_location' => 'before_submit_button',
		'jgcgrc_login_recaptcha'             => 'on',
		'jgcgrc_register_recaptcha'          => 'on',
		'jgcgrc_lostpassword_recaptcha'      => 'on',
		'jgcgrc_theme'                       => 'light',
		'jgcgrc_lang'                        => 'site_lang',
	);

	update_option( 'jgcgrc_opciones', $options );

}

register_activation_hook( __FILE__, 'jgcgrc_install' );
/**
 * Activation plugin.
 *
 * @since 1.0.0
 */
function jgcgrc_install() {
	if ( get_option( 'jgcgrc_opciones' ) === false ) {
		jgcgrc_set_default_options();
	}
}

add_action( 'init', 'jgcgrc_load_textdomain' );
/**
 * Load text domain.
 *
 * @since 1.0.0
 */
function jgcgrc_load_textdomain() {

	load_plugin_textdomain( 'jgc-google-recaptcha', false, basename( dirname( __FILE__ ) ) . '/languages' );

}

/**
 * Get option plugin value.
 *
 * @since 1.0.0
 *
 * @param string $option Option name.
 * @return mixed Option value.
 */
function jgcgrc_option( $option ) {

	$options = get_option( 'jgcgrc_opciones' );

	$option_value = ! empty( $options[ $option ] ) ? $options[ $option ] : '';

	return $option_value;

}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'jgcgrc_add_plugin_action_links' );
/**
 * Add link in plugins admin panel.
 *
 * @since 1.0.0
 *
 * @param array $links Links array.
 */
function jgcgrc_add_plugin_action_links( $links ) {

	$url = admin_url( 'options-general.php?page=jgcgrc-settings' );

	$custom_links = array( '<a href="' . $url . '">' . __( 'Settings', 'jgc-google-recaptcha' ) . '</a>' );
	$links        = array_merge( $custom_links, $links );

	return $links;

}

if ( ! is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'inc/jgcgrc-functions.php';
}

if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'inc/jgcgrc-options.php';
}
