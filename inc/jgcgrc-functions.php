<?php
/**
 * JGC Google reCAPTCHA plugin functions.
 *
 * @since 1.0.0
 * @package JGC Google reCAPTCHA
 * @link https://galussothemes.com
 */

/**
 * Add reCaptcha message.
 *
 * @since 1.0.0
 */
function jgcgrc_msg_click_recaptcha() {
	return '<div style="text-align:center; font-weight:bold;">' . __( 'Please click on the reCAPTCHA box.', 'jgc-google-recaptcha' ) . '</div>';
}

add_action( 'set_current_user', 'jgcgrc_recaptcha_run' );
/**
 * Run reCaptcha plugin if keys not empty.
 *
 * @since 1.0.0
 */
function jgcgrc_recaptcha_run() {

	$site_key   = jgcgrc_option( 'jgcgrc_site_key' );
	$secret_key = jgcgrc_option( 'jgcgrc_secret_key' );

	if ( ! empty( $site_key ) && ! empty( $secret_key ) ) {

		jgcgrc_comments_recaptcha_exec();
		jgcgrc_login_register_lostpw_recaptcha_exec();

	}

}

/**
 * ReCAPTCHA for comments
 */
function jgcgrc_comments_recaptcha_exec() {
	$captcha_for_comments = jgcgrc_option( 'jgcgrc_comments_recaptcha' );

	if ( 'on' === $captcha_for_comments && ! is_user_logged_in() ) {

		add_action( 'wp_enqueue_scripts', 'jgcgrc_google_recaptcha_script' );

		$captcha_location = jgcgrc_option( 'jgcgrc_comments_recaptcha_location' );

		if ( 'before_submit_button' === $captcha_location ) {
			add_filter( 'comment_form_default_fields', 'jgcgrc_add_comments_recaptcha_before' );
		} else {
			add_action( 'comment_form', 'jgcgrc_add_comments_recaptcha_after' );
		}

		add_filter( 'preprocess_comment', 'jgcgrc_verify_comment_recaptcha', 1, 1 );

	}
}

/**
 * Load Google reCaptcha scripts.
 *
 * @since 1.0.0
 */
function jgcgrc_google_recaptcha_script() {
	$lang = jgcgrc_option( 'jgcgrc_lang' );

	if ( 'user_lang' === $lang ) {
		wp_enqueue_script( 'jgcgrc-google-recaptcha', 'https://www.google.com/recaptcha/api.js' );
	} else {
		$recaptcha_script_url = 'https://www.google.com/recaptcha/api.js?hl=' . JGCGRC_SITE_LANG;
		wp_enqueue_script( 'jgcgrc-google-recaptcha', $recaptcha_script_url );
	}
}

function jgcgrc_add_comments_recaptcha_before( $fields ) {

	$site_key = jgcgrc_option( 'jgcgrc_site_key' );
	$theme    = jgcgrc_option( 'jgcgrc_theme' );

	$fields['jgcgrc_com_recaptcha'] = '<div style="margin:14px 0;" class="g-recaptcha" data-sitekey="' . $site_key . '" data-theme="' . $theme . '"></div>';

	return $fields;

}

function jgcgrc_add_comments_recaptcha_after() {

	$site_key = jgcgrc_option( 'jgcgrc_site_key' );
	$theme    = jgcgrc_option( 'jgcgrc_theme' );

	echo '<div style="margin:14px 0;" class="g-recaptcha" data-sitekey="' . esc_attr( $site_key ) . '" data-theme="' . esc_attr( $theme ) . '"></div>';

}

function jgcgrc_verify_comment_recaptcha( $commentdata ) {

	if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {

		// Secret key.
		$secret_key = jgcgrc_option( 'jgcgrc_secret_key' );

		// Get verify response data.
		$verify_response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response'] );

		$response_data = json_decode( $verify_response );

		if ( $response_data->success ) {
			return $commentdata;
		} else {
			wp_die( __( 'Robot verification failed, please try again.', 'jgc-google-recaptcha' ) );
		}
	} else {

		wp_die( jgcgrc_msg_click_recaptcha() . '<p style="text-align:center;"><a href="javascript:window.history.back();">' . __( 'Get back', 'jgc-google-recaptcha' ) . '</a></p>' );

	}

}

/************************************************
 * ReCAPTCHA for login, register and lostpassword
 ************************************************/

/**
 * Common
 */
function jgcgrc_login_register_lostpw_recaptcha_exec() {
	$jgcgrc_login_recaptcha        = jgcgrc_option( 'jgcgrc_login_recaptcha' );
	$jgcgrc_register_recaptcha     = jgcgrc_option( 'jgcgrc_register_recaptcha' );
	$jgcgrc_lostpassword_recaptcha = jgcgrc_option( 'jgcgrc_lostpassword_recaptcha' );

	if ( 'on' === $jgcgrc_login_recaptcha || 'on' === $jgcgrc_register_recaptcha || 'on' === $jgcgrc_lostpassword_recaptcha ) {

		add_action( 'login_enqueue_scripts', 'jgcgrc_load_login_scripts' );

		if ( 'on' === $jgcgrc_login_recaptcha ) {
			add_action( 'login_form', 'jgcgrc_add_login_recaptcha' );
			add_filter( 'wp_authenticate_user', 'jgcgrc_verify_login_recaptcha', 10, 2 ); // https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_authenticate_user.
		}

		if ( 'on' === $jgcgrc_register_recaptcha ) {
			add_action( 'register_form', 'jgcgrc_add_login_recaptcha' );
			add_filter( 'registration_errors', 'jgcgrc_verify_register_recaptcha', 10, 3 ); // https://codex.wordpress.org/Plugin_API/Filter_Reference/registration_errors.
		}

		if ( 'on' === $jgcgrc_lostpassword_recaptcha ) {
			add_action( 'lostpassword_form', 'jgcgrc_add_login_recaptcha' );
			add_action( 'lostpassword_post', 'jgcgrc_verify_lostpassword_recaptcha' );
		}
	}

}

/**
 * Load Google reCaptcha scripts.
 *
 * @since 1.0.0
 */
function jgcgrc_load_login_scripts() {

	$lang = jgcgrc_option( 'jgcgrc_lang' );

	if ( 'user_lang' === $lang ) {
		wp_enqueue_script( 'jgcgrc-google-recaptcha', 'https://www.google.com/recaptcha/api.js' );
	} else {
		$recaptcha_script_url = 'https://www.google.com/recaptcha/api.js?hl=' . JGCGRC_SITE_LANG;
		wp_enqueue_script( 'jgcgrc-google-recaptcha', $recaptcha_script_url );
	}

}

/**
 * Add Google reCaptcha (login, Register and Lost password forms).
 *
 * @since 1.0.0
 */
function jgcgrc_add_login_recaptcha() {

	$site_key = jgcgrc_option( 'jgcgrc_site_key' );
	$theme    = jgcgrc_option( 'jgcgrc_theme' );

	echo '<div class="g-recaptcha" data-sitekey="' . $site_key . '" data-theme="' . $theme . '" style="transform:scale(0.9);-webkit-transform:scale(0.9);transform-origin:0 0;-webkit-transform-origin:0 0;"></div>';

}

/**
 * Verify Google reCaptcha (Login form).
 *
 * @since 1.0.0
 *
 * @param string $user User name.
 * @param string $password User password.
 */
function jgcgrc_verify_login_recaptcha( $user, $password ) {

	if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {

		// Secret key.
		$secret_key = jgcgrc_option( 'jgcgrc_secret_key' );

		// Get verify response data.
		$verify_response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response'] );

		$response_data = json_decode( $verify_response );

		if ( $response_data->success ) {

			return $user;

		} else {

			wp_die( __( 'Robot verification failed, please try again.', 'jgc-google-recaptcha' ) );

		}
	} else {

		return new WP_Error( 'recaptcha_not_verified', jgcgrc_msg_click_recaptcha() );

	}

}

// Verify register recaptcha.
function jgcgrc_verify_register_recaptcha( $errors, $sanitized_user_login, $user_email ) {

	if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {

		// Secret key.
		$secret_key = jgcgrc_option( 'jgcgrc_secret_key' );

		// Get verify response data.
		$verify_response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response'] );

		$response_data = json_decode( $verify_response );

		if ( ! $response_data->success ) {
			wp_die( __( 'Robot verification failed, please try again.', 'jgc-google-recaptcha' ) );
		}
	} else {

		$errors->add( 'recaptcha_not_verified', jgcgrc_msg_click_recaptcha() );

	}

	return $errors;

}

// Verify lostpassword recaptcha.
function jgcgrc_verify_lostpassword_recaptcha( $errors ) {

	if ( isset( $_POST['g-recaptcha-response'] ) && ! empty( $_POST['g-recaptcha-response'] ) ) {

		// Secret key.
		$secret_key = jgcgrc_option( 'jgcgrc_secret_key' );

		// Get verify response data.
		$verify_response = file_get_contents( 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response'] );

		$response_data = json_decode( $verify_response );

		if ( ! $response_data->success ) {
			wp_die( __( 'Robot verification failed, please try again.', 'jgc-google-recaptcha' ) );
		}
	} else {

		$errors->add( 'recaptcha_not_verified', jgcgrc_msg_click_recaptcha() );

	}

	return $errors;

}
