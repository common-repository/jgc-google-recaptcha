<?php
/**
 * Creates options page.
 *
 * @since 1.0.0
 * @package JGC Google reCAPTCHA
 * @link https://galussothemes.com
 */

add_action( 'admin_menu', 'jgcgrc_add_options_page' );
/**
 * Adds options page.
 *
 * @since 1.0.0
 */
function jgcgrc_add_options_page() {

	add_options_page( 'JGC Google reCAPTCHA', 'JGC Google reCAPTCHA', 'manage_options', 'jgcgrc-settings', 'jgcgrc_setting_page' );

	add_action( 'admin_init', 'jgcgrc_register_setting' );

}

/**
 * Register plugin options.
 *
 * @since 1.0.0
 */
function jgcgrc_register_setting() {

	register_setting( 'jgcgrc_grupo_opciones', 'jgcgrc_opciones', 'jgcgrc_sanitize_options' );

}

/**
 * Sanitize plugin options.
 *
 * @since 1.0.0
 *
 * @param array $input Options array.
 * @return array $input Sanitized option values.
 */
function jgcgrc_sanitize_options( $input ) {

	$input['jgcgrc_site_key']                    = sanitize_text_field( $input['jgcgrc_site_key'] );
	$input['jgcgrc_secret_key']                  = sanitize_text_field( $input['jgcgrc_secret_key'] );
	$input['jgcgrc_comments_recaptcha']          = ( 'on' === $input['jgcgrc_comments_recaptcha'] ) ? 'on' : '';
	$input['jgcgrc_comments_recaptcha_location'] = $input['jgcgrc_comments_recaptcha_location'];
	$input['jgcgrc_login_recaptcha']             = ( 'on' === $input['jgcgrc_login_recaptcha'] ) ? 'on' : '';
	$input['jgcgrc_register_recaptcha']          = ( 'on' === $input['jgcgrc_register_recaptcha'] ) ? 'on' : '';
	$input['jgcgrc_lostpassword_recaptcha']      = ( 'on' === $input['jgcgrc_lostpassword_recaptcha'] ) ? 'on' : '';
	$input['jgcgrc_theme']                       = $input['jgcgrc_theme'];
	$input['jgcgrc_lang']                        = sanitize_text_field( $input['jgcgrc_lang'] );

	return $input;

}

/**
 * Create setting page.
 *
 * @since 1.0.0
 */
function jgcgrc_setting_page() {
	?>

	<style type="text/css">
		.settings-page-heading{
			background-color:#0073AA;
			color:white;
			line-height:2;
			margin:0 0 40px -20px;
			padding-left:20px;
			padding-top: 5px;
			padding-bottom: 5px;
		}
		.settings-page-heading h1, .settings-page-heading h3{
			display: inline;
			margin:0;
			color:white;
		}
		.settings-page-heading .author{
			float:right;
			padding-right: 28px;
			padding-top:7px;
		}
		.wrap{
			box-sizing: border-box;
		}
		.wrap .col-left{
			box-sizing: border-box;
		}
		.wrap .col-left h2{
			margin-top: 0;
			font-size: 24px;
		}
		.wrap .col-right{
			box-sizing: border-box;
		}
		.wrap .col-right .info-box{
			background-color: white;
			border: 1px solid #e5e5e5;
			box-shadow: 0 1px 1px rgba(0,0,0,.04);
		}
		.wrap .col-right .info-box-heading{
			padding: 10px;
			font-size: 16px;
			font-weight: bold;
			border-bottom: 1px solid #eeeeee;
		}
		.wrap .col-right .info-box-content{
			padding: 20px;
		}
		.full-width-link{
			width: 100%;
			text-align: center !important;
		}
		@media screen and (min-width: 640px) {
			.wrap .col-left{
				float:left;
				width: 70%;
			}
			.wrap .col-right{
				float:right;
				width: 28%;
			}
		}
	</style>

	<?php
	$url_logo = plugins_url( '../img/gt-logo-196x35.png', __FILE__ );
	?>

	<div class="settings-page-heading">
		<h1><?php echo JGCGRC_PLUGIN_NAME; ?></h1>&nbsp;&nbsp;&nbsp;<?php echo JGCGRC_VERSION; ?>
		<div class="author"><img src="<?php echo esc_url( $url_logo ); ?>"></div>
	</div>

	<div class="wrap">

		<div class="col-left">

			<div><h2><?php esc_html_e( 'Settings', 'jgc-google-recaptcha' ); ?></h2><hr></div>

			<form method="post" action="options.php">

				<?php
				settings_fields( 'jgcgrc_grupo_opciones' );
				$jgcgrc_opciones = get_option( 'jgcgrc_opciones' );
				?>

				<h3><?php esc_html_e( 'Authentication', 'jgc-google-recaptcha' ); ?></h3>

				<p><?php esc_html_e( 'To use Google reCAPTCHA you should get a site key and a secret key in', 'jgc-google-recaptcha' ); ?> <a href="https://www.google.com/recaptcha/admin#list" target="_blank">Google reCAPTCHA</a>.<br>
				<?php esc_html_e( 'When you get the keys, write them in the text boxes below.', 'jgc-google-recaptcha' ); ?></p>

				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Site key', 'jgc-google-recaptcha' ); ?></th>
						<td valign="top">
						<input type="text"
						name="jgcgrc_opciones[jgcgrc_site_key]"
						value="<?php echo esc_attr( $jgcgrc_opciones['jgcgrc_site_key'] ); ?>"
						size="80" >
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Secret key', 'jgc-google-recaptcha' ); ?></th>
						<td valign="top">
						<input type="text"
						name="jgcgrc_opciones[jgcgrc_secret_key]"
						value="<?php echo esc_attr( $jgcgrc_opciones['jgcgrc_secret_key'] ); ?>"
						size="80" >
						</td>
					</tr>
				</table>

				<hr>

				<h3><?php esc_html_e( 'Options', 'jgc-google-recaptcha' ); ?></h3>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable reCAPTCHA for', 'jgc-google-recaptcha ' ); ?></th>
						<td valign="top">

						<p><input name="jgcgrc_opciones[jgcgrc_login_recaptcha]" id="jgcgrc_login_recaptcha"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_login_recaptcha'], 'on', false ); ?>
						type="checkbox" /> <?php esc_html_e( 'Login form', 'jgc-google-recaptcha' ); ?></p>

						<p><input name="jgcgrc_opciones[jgcgrc_register_recaptcha]" id="jgcgrc_register_recaptcha"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_register_recaptcha'], 'on', false ); ?>
						type="checkbox" /> <?php esc_html_e( 'Register form', 'jgc-google-recaptcha' ); ?></p>

						<p><input name="jgcgrc_opciones[jgcgrc_lostpassword_recaptcha]" id="jgcgrc_lostpassword_recaptcha"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_lostpassword_recaptcha'], 'on', false ); ?>
						type="checkbox" /> <?php esc_html_e( 'Lost password form', 'jgc-google-recaptcha' ); ?></p>

						<p><input name="jgcgrc_opciones[jgcgrc_comments_recaptcha]" id="jgcgrc_comments_recaptcha"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_comments_recaptcha'], 'on', false ); ?>
						type="checkbox" /> <?php esc_html_e( 'Comments form', 'jgc-google-recaptcha' ); ?>:<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<input type="radio"
						name="jgcgrc_opciones[jgcgrc_comments_recaptcha_location]"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_comments_recaptcha_location'], 'before_submit_button', false ); ?>
						value="before_submit_button" /> <?php esc_html_e( 'Before submit button', 'jgc-google-recaptcha' ); ?>&nbsp;

						<input type="radio"
						name="jgcgrc_opciones[jgcgrc_comments_recaptcha_location]"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_comments_recaptcha_location'], 'after_submit_button', false ); ?>
						value="after_submit_button" /> <?php esc_html_e( 'After submit button', 'jgc-google-recaptcha' ); ?>
						</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'reCAPTCHA theme', 'jgc-google-recaptcha' ); ?></th>
						<td>
						<input type="radio"
						name="jgcgrc_opciones[jgcgrc_theme]"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_theme'], 'light', false ); ?>
						value="light" /> <?php esc_html_e( 'Light', 'jgc-google-recaptcha' ); ?>&nbsp;&nbsp;&nbsp;

						<input type="radio"
						name="jgcgrc_opciones[jgcgrc_theme]"
						<?php echo checked( $jgcgrc_opciones['jgcgrc_theme'], 'dark', false ); ?>
						value="dark" /> <?php esc_html_e( 'Dark', 'jgc-google-recaptcha' ); ?>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'reCAPTCHA language', 'jgc-google-recaptcha' ); ?></th>
						<td valign="top">
							<select name="jgcgrc_opciones[jgcgrc_lang]" >
								<option value="site_lang" <?php echo @selected( $jgcgrc_opciones['jgcgrc_lang'], 'site_lang', false ); ?>><?php esc_html_e( 'Site language', 'jgc-google-recaptcha' ); ?></option>

								<option value="user_lang" <?php echo @selected( $jgcgrc_opciones['jgcgrc_lang'], 'user_lang', false ); ?>><?php esc_html_e( 'User language', 'jgc-google-recaptcha' ); ?></option>
							</select>
							<p class="description"><?php esc_html_e( "Site language: Your site's language", 'jgc-google-recaptcha' ); ?></p>
							<p class="description"><?php esc_html_e( "User language: The user's language auto-detected by Google.", 'jgc-google-recaptcha' ); ?></p>
						</td>
					</tr>
				</table>

				<hr>

				<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save changes', 'jgc-google-recaptcha' ); ?>" />

			</form>

		</div><!-- .col-left -->

		<div class="col-right">
			<div class="info-box">
				<div class="info-box-heading"><?php esc_html_e( 'Links', 'jgc-google-recaptcha' ); ?></div>
				<div class="info-box-content">
					<?php esc_html_e( 'Please, if you are happy with the plugin, say it on wordpress.org and give it a nice review. Thank you.', 'jgc-google-recaptcha' ); ?>
					<p><a class="button-secondary full-width-link" href="https://wordpress.org/support/plugin/jgc-google-recaptcha/reviews/" target="_blank"><?php esc_html_e( 'Rate/Review', 'jgc-google-recaptcha' ); ?></a></p>
					<hr>
					<p><a class="button-secondary full-width-link" href="https://wordpress.org/support/plugin/jgc-google-recaptcha" target="_blank"><?php esc_html_e( 'Support forum', 'jgc-google-recaptcha' ); ?></a></p>

					<p><a class="button-secondary full-width-link" href="https://galussothemes.com/wordpress-themes" target="_blank"><?php esc_html_e( 'Our WordPress Themes', 'jgc-google-recaptcha' ); ?></a></p>
				</div>
			</div><!-- .info-box -->
		</div><!-- .col-right -->

	</div><!-- .wrap -->
<?php
}
