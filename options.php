<?php

/**
 * Slash Admin Options page
 *
 * @package Slash Admin
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Slash_Admin_Options {

	private $sections;
	private $checkboxes;
	private $settings;

	/**
	 * Construct
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// This will keep track of the checkbox options for the validate_settings function.
		$this->checkboxes = array();
		$this->settings   = array();

		$this->sections['appearance']         = __( 'Frontend', 'slash-admin' );
		$this->sections['administration']     = __( 'Administration', 'slash-admin' );
		$this->sections['frontend_usability'] = __( 'Login screen', 'slash-admin' );
		$this->sections['backend_usability']  = __( 'Non-admins', 'slash-admin' );
		$this->sections['white_label']        = __( 'White label', 'slash-admin' );
		$this->sections['performance']        = __( 'Performance', 'slash-admin' );
		$this->sections['shortcodes']         = __( 'Shortcodes', 'slash-admin' );
		$this->sections['about']              = __( 'Documentation', 'slash-admin' );

		add_action( 'admin_menu', array( &$this, 'add_pages' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );

		if ( ! get_option( 'slashadmin_options' ) ) {
			$this->initialize_settings();
		}

	}

	/**
	 * Add options page
	 *
	 * @since 1.0
	 */
	public function add_pages() {

		$admin_page = add_management_page( __( 'Slash Admin Options', 'slash-admin' ),
			__( 'Slash Admin', 'slash-admin' ),
			'manage_options',
			'slashadmin-options',
			array(
				&$this,
				'display_page',
			) );

		add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'slashadmin_scripts' ) );
		add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'styles' ) );

	}

	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {

		$defaults = array(
			'id'      => 'default_field',
			'title'   => __( 'Default Field', 'slash-admin' ),
			'desc'    => __( 'This is a default description.', 'slash-admin' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'general',
			'choices' => array(),
			'class'   => '',
			'para'    => '',
		);

		extract( wp_parse_args( $args, $defaults ) );

		$field_args = array(
			'type'      => $type,
			'id'        => $id,
			'desc'      => $desc,
			'std'       => $std,
			'choices'   => $choices,
			'label_for' => $id,
			'class'     => $class,
			'paragraph' => $para,
		);

		if ( $type == 'checkbox' ) {
			$this->checkboxes[] = $id;
		}

		add_settings_field( $id,
			$title,
			array(
				$this,
				'display_setting',
			),
			'slashadmin-options',
			$section,
			$field_args );
	}

	/**
	 * Display options page
	 *
	 * @since 1.0
	 */
	public function display_page() {

		echo '<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h1 class="slash-admin-header">' . __( 'Slash Admin Options', 'slash-admin' ) . '</h1>';

		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
			echo '<div class="updated fade"><p>' . __( 'Options updated.', 'slash-admin' ) . '</p></div>';
		}

		echo '<form action="options.php" method="post">';

		settings_fields( 'slashadmin_options' );
		echo '<div class="ui-tabs">
			<ul class="ui-tabs-nav">';

		foreach ( $this->sections as $section_slug => $section ) {
			echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
		}

		echo '</ul>';

		do_settings_sections( $_GET['page'] );

		echo '</div>
		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes',
				'slash-admin' ) . '" /></p>
		
	</form>';

		/* Check WordPress version and set the h2 or h3 tag depending on whether it is v. 4.4 and above or earlier 
		 * (fixes "Error: jQuery UI Tabs: Mismatching fragment identifier")
		*/
		global $wp_version;
		$htag = ( $wp_version >= 4.4 ) ? 'h2' : 'h3';

		echo '<script type="text/javascript">
		jQuery(document).ready(function($) {
			"use strict";
			var sections = [];';

		foreach ( $this->sections as $section_slug => $section ) {
			echo "sections['$section'] = '$section_slug';";
		}

		echo 'var wrapped = $(".wrap ' . $htag . '").wrap("<div class=\"ui-tabs-panel\">");
			wrapped.each(function() {
				$(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
			});
			$(".ui-tabs-panel").each(function(index) {
				$(this).attr("id", sections[$(this).children("' . $htag . '").text()]);
				if (index > 0)
					$(this).addClass("ui-tabs-hide");
			});
			$(".ui-tabs").tabs({
				fx: { opacity: "toggle", duration: "fast" }
			});
			
			$("input[type=text], textarea").each(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
					$(this).css("color", "#999");
			});
			
			$("input[type=text], textarea").focus(function() {
				if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
					$(this).val("");
					$(this).css("color", "#000");
				}
			}).blur(function() {
				if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
					$(this).val($(this).attr("placeholder"));
					$(this).css("color", "#999");
				}
			});
		
			var localEnabled = $("#local_fonts_enabled");
			var urls = $("#local_fonts").parents("tr");
			var woff = $("#local_fonts_woff").parents("tr");
			var google = $("#custom_fonts").parents("tr");
			if(localEnabled.is(":checked")) {
				google.hide();
			} else {
				urls.hide();
		        woff.hide();
			}
			
			localEnabled.on("change", function() {
				if(this.checked) {
		            urls.show();
		            woff.show();
		            google.hide();
		        } else {
			        urls.hide();
			        woff.hide();
			        google.show();
		        }
			});
			
			$(".wrap ' . $htag . ', .wrap table").show();
		
			$(".warning").change(function() {
				if ($(this).is(":checked"))
					$(this).parent().css("background", "#c00").css("color", "#fff").css("fontWeight", "bold");
				else
					$(this).parent().css("background", "none").css("color", "inherit").css("fontWeight", "normal");
			});
		});
	</script>
</div>';

	}

	/**
	 * Description for section
	 *
	 * @since 1.0
	 */
	public function display_section() {
		// code
	}

	/**
	 * Description for About section
	 *
	 * @since 1.0
	 */
	public function display_about_section() {

		// This displays on the "About" tab. Echo regular HTML

	}

	/**
	 * HTML output for text field
	 *
	 * @since 1.0
	 */
	public function display_setting( $args = array() ) {

		extract( $args );

		$options = get_option( 'slashadmin_options' );

		if ( ! isset( $options[ $id ] ) && $type != 'checkbox' ) {
			$options[ $id ] = $std;
		} elseif ( ! isset( $options[ $id ] ) ) {
			$options[ $id ] = 0;
		}

		$field_class     = '';
		$slash_ffe_check = ( function_exists( 'format_for_editor' ) && ! is_array( $options[ $id ] ) ) ? format_for_editor( $options[ $id ] ) : '';
		if ( $class != '' ) {
			$field_class = ' ' . $class;
		}

		switch ( $type ) {

			case 'heading':
				echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4><p>' . $paragraph . '</p>';
				break;

			case 'checkbox':

				echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="slashadmin_options[' . $id . ']" value="1" ' . checked( $options[ $id ],
						1,
						false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

				break;

			case 'select':
				echo '<select class="select' . $field_class . '" name="slashadmin_options[' . $id . ']">';

				foreach ( $choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[ $id ],
							$value,
							false ) . '>' . $label . '</option>';
				}

				echo '</select>';

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;

			case 'multiple':
				echo '<select multiple class="multiple' . $field_class . '" name="slashadmin_options[' . $id . '][]">';
				foreach ( $choices as $value => $label ) {
					$selected = ( is_array( $options[ $id ] ) && in_array( $value,
							$options[ $id ] ) ) ? 'selected="selected"' : '';
					echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
				}

				echo '</select>';

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}
				break;

			case 'upload': // Media uploader (see: http://www.justinwhall.com/multiple-upload-inputs-in-a-wordpress-theme-options-page/)
				echo '<input id="' . $id . '" class="upload-url' . $field_class . '" type="text" name="slashadmin_options[' . $id . ']" value="' . esc_attr( $options[ $id ] ) . '" /><input id="st_upload_button" class="st_upload_button" type="button" name="upload_button" value="' . __( 'Upload',
						'slash-admin' ) . '" />';

				if ( $desc != '' ) {
					echo '<span class="description">' . $desc . '</span>';
				}

				break;

			case 'radio':
				$i = 0;
				foreach ( $choices as $value => $label ) {
					echo '<input class="radio' . $field_class . '" type="radio" name="slashadmin_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[ $id ],
							$value,
							false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
					if ( $i < count( $options ) - 1 ) {
						echo '<br />';
					}
					$i ++;
				}

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;

			case 'textarea':
				echo '<textarea class="' . $field_class . '" id="' . $id . '" name="slashadmin_options[' . $id . ']" placeholder="' . $std . '" rows="5" cols="30">' . $slash_ffe_check . '</textarea>';

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;

			case 'password':
				echo '<input class="regular-text' . $field_class . '" type="password" id="' . $id . '" name="slashadmin_options[' . $id . ']" value="' . esc_attr( $options[ $id ] ) . '" />';

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;

			case 'text':
			default:
				echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="slashadmin_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[ $id ] ) . '" />';

				if ( $desc != '' ) {
					echo '<br /><span class="description">' . $desc . '</span>';
				}

				break;

		}

	}


	/**
	 * Settings and defaults
	 *
	 * @since 1.0
	 */
	public function get_settings() {


		/* Appearance
		===========================================*/
		$this->settings['custom_splash'] = array( // Custom Splash page
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Custom Splash page', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Redirect anonymous users to a static splash page located on a folder in the root of your WordPress installation. In there you can put plain old HTML or PHP without restrictions and without having to pollute your theme\'s files with temporary code that should be removed when the site goes on air.',
				'slash-admin' ),
		);
		$this->settings['splash_enable'] = array( // Hide update notices for all but Admins
			'section' => 'appearance',
			'title'   => __( 'Enable', 'slash-admin' ),
			'desc'    => __( 'Enable the custom splash page', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['splash_path']   = array(
			'section' => 'appearance',
			'title'   => __( 'Custom splash page folder', 'slash-admin' ),
			'desc'    => __( 'Fill in the name of the folder where your splash page is. The folder should be on the root of your WordPress installation. If you leave this blank, the custom splash functionality won\'t work.',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => '',
		);

		$this->settings['opensans_fix'] = array( // Font fixes
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Fonts', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['local_fonts_enabled'] = array( // Local fonts
			'section' => 'appearance',
			'title'   => __( 'Local fonts', 'slash-admin' ),
			'desc'    => __( 'Download webfonts and host them locally using <a href="https://github.com/WPTT/webfont-loader" target="_blank">Webfont Loader</a>.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['local_fonts'] = array( // Local font URLs
			'title'   => __( 'Local fonts URLs', 'slash-admin' ),
			'desc'    => __( 'Paste the full URLs of the fonts. You can add more than one. Each URL should be on its own line. If you want to preload a font, add <code>&preload=true</code> at the end of the respective URL. <br>Example: <code>https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap&preload=true</code>',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'appearance',
		);

		$this->settings['local_fonts_woff'] = array( // WOFF support
			'section' => 'appearance',
			'title'   => __( 'Support Internet Explorer', 'slash-admin' ),
			'desc'    => __( 'The wptt_get_webfont_url will - by default - download .woff2 files. However, if you need to support IE you will need to use .woff files instead. To do that, enable this option',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['custom_fonts'] = array(
			'section' => 'appearance',
			'title'   => __( 'Google Fonts URL', 'slash-admin' ),
			'desc'    => __( 'Insert your Google Web Fonts here. You can declare more than one fonts, styles and language subsets. If you use the latest, CSS2 version of Google Fonts, you should copy/paste the full URL that it generates, like for example <code>https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@300;400;700&family=Roboto:wght@300;400;900&display=swap</code>.<br/> If you prefer the old API, then you must only fill in the part after the "http://fonts.googleapis.com/css?family=" like for example <code>Roboto+Slab:300,400,700|Roboto:300,400,900&display=swap</code>. View more information about the font\'s structure, subsets and weights at the <a href="https://developers.google.com/fonts/docs/getting_started#Overview" target="_blank">Google Fonts help page</a>.<br/> If your theme has it\'s own mechanism for embedding Google Fonts or if you prefer to do it manually, just leave it blank.',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => '',
		);


		$this->settings['cookielaw_header']   = array( // Cookie law
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Cookie Law consent message', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Show how your website complies with the EU Cookie Law. It displays a message at the bottom or the top of the page asking for the user\'s consent. Read more about <a href="http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm" target="_blank">EU legislation on cookies</a>',
				'slash-admin' ),
		);
		$this->settings['cookielaw_enable']   = array( // Hide update notices for all but Admins
			'section' => 'appearance',
			'title'   => __( 'Enable', 'slash-admin' ),
			'desc'    => __( 'Show the Cookie Law consent message', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['cookielaw_style']    = array(
			'section' => 'appearance',
			'title'   => __( 'Message style', 'slash-admin' ),
			'desc'    => __( 'Select the style of the message box.', 'slash-admin' ),
			'type'    => 'select',
			'std'     => 'dark',
			'choices' => array(
				'dark'   => __( 'Dark (default)', 'slash-admin' ),
				'light'  => __( 'Light', 'slash-admin' ),
				'custom' => __( 'None (I will style it my self)', 'slash-admin' ),
			),
		);
		$this->settings['cookielaw_position'] = array(
			'section' => 'appearance',
			'title'   => __( 'Position', 'slash-admin' ),
			'desc'    => __( 'Select the position of the message box.', 'slash-admin' ),
			'type'    => 'select',
			'std'     => 'bottom',
			'choices' => array(
				'bottom' => __( 'Bottom', 'slash-admin' ),
				'top'    => __( 'Top', 'slash-admin' ),
				'right'  => __( 'Bottom right', 'slash-admin' ),
			),
		);
		$this->settings['cookielaw_message']  = array(
			'section' => 'appearance',
			'title'   => __( 'Message', 'slash-admin' ),
			'desc'    => __( 'Change the default message. If you use WPML and you want a different message per language, you should enable "Get message from page" option below.',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => __( 'We use cookies. By browsing our site you agree to our use of cookies.', 'slash-admin' ),
		);
		$this->settings['cookielaw_accept']   = array(
			'section' => 'appearance',
			'title'   => __( 'Accept text', 'slash-admin' ),
			'desc'    => __( 'Change the "Accept" button text. If you use WPML and you want a different wording per language, go to your terms page (as set via "Read more page" option below) and fill in your new value in the custom field "slash_accept".',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => __( 'Accept', 'slash-admin' ),
		);
		$this->settings['cookielaw_readmore'] = array(
			'section' => 'appearance',
			'title'   => __( 'Read more', 'slash-admin' ),
			'desc'    => __( 'Change the "read more" text.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => __( 'Read more', 'slash-admin' ),
		);

		$this->settings['cookielaw_url'] = array(
			'section' => 'appearance',
			'title'   => __( 'Read more page', 'slash-admin' ),
			'desc'    => __( 'The page with the details about how your website uses cookies. If you don\'t select one, then no "read more" link will appear.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 'none',
			'choices' => slashadmin_get_pages(),
		);

		$this->settings['cookielaw_pagedata'] = array(
			'section' => 'appearance',
			'title'   => __( 'Get message from page', 'slash-admin' ),
			'desc'    => __( 'Retrieve the box message form from your "Read more" page\'s excerpt instead of the plugin\'s message field. You need to enable excerpt support to pages from the "Miscellaneous" section below. Check this option if you use WPML and you want to have different consent messages per language.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['loading_header']  = array( // Loading animation
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Loading animation', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Show a "Loading" animation which disappears when the page is fully loaded.',
				'slash-admin' ),
		);
		$this->settings['loading_enabled'] = array(
			'section' => 'appearance',
			'title'   => __( 'Enable loader', 'slash-admin' ),
			'desc'    => __( 'Select whether the loader should be enabled or not. "Loader with a logo" displays a circular spinner with your logo in the middle. If no image is uploaded, it gets the site icon uploaded in the theme customizer. If no image is uploaded there either, it shows the spinner with no image (practically, in that case it is the same as the "Empty loader" option). "Custom GIF" option disables the circular spinner and allows you to upload and use your own animated GIF.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 'disabled',
			'choices' => array(
				'disabled' => __( 'Disabled', 'slash-admin' ),
				'logo'     => __( 'Loader with a logo', 'slash-admin' ),
				'gif'      => __( 'Custom GIF', 'slash-admin' ),
				'empty'    => __( 'Empty loader', 'slash-admin' ),
			),
		);
		$this->settings['loading_img']     = array(
			'title'   => __( 'Image loader', 'slash-admin' ),
			'desc'    => __( 'If you use a site icon (via <strong>Appearance &rarr; Customize &rarr; Site Identity &rarr; Site Icon</strong>) it will be used as the loader\'s image. In that case, just leave this field empty. If you want to display a different image as your loader, upload it here. The image should be square and no more than 160x160 pixels.',
					'slash-admin' ) . '</span>',
			'std'     => '',
			'type'    => 'upload',
			'section' => 'appearance',
		);

		$this->settings['loading_bg_color']      = array(
			'section' => 'appearance',
			'title'   => __( 'Background color', 'slash-admin' ),
			'desc'    => __( 'The loader\'s background color. You can use hex (e.g. <code>#FFFFFF</code>) or rgb/rgba (e.g. <code>rgba(255,255,255,0.8)</code>).',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => '#FFFFFF',
		);
		$this->settings['loading_spinner_empty'] = array(
			'section' => 'appearance',
			'title'   => __( 'Spinner background', 'slash-admin' ),
			'desc'    => __( 'The background of the spinner.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => '#CCCCCC',
		);
		$this->settings['loading_spinner_full']  = array(
			'section' => 'appearance',
			'title'   => __( 'Spinner loading color', 'slash-admin' ),
			'desc'    => __( 'The color of the spinner\'s loading part.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => '#000000',
		);
		$this->settings['loading_text']          = array(
			'section' => 'appearance',
			'title'   => __( 'Alt text', 'slash-admin' ),
			'desc'    => __( 'The text to be displayed as the image\'s alt text.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => __( 'Loading...', 'slash-admin' ),
		);
		$this->settings['loading_hide']          = array(
			'section' => 'appearance',
			'title'   => __( 'Hide Loader', 'slash-admin' ),
			'desc'    => __( 'The text to be displayed if the spinner takes too long. When it gets clicked, it hides the spinner.',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => __( 'If the spinner keeps loading forever, click to hide it.', 'slash-admin' ),
		);
		$this->settings['loading_location']      = array(
			'section' => 'appearance',
			'title'   => __( 'Homepage only', 'slash-admin' ),
			'desc'    => __( 'If checked, it will show the loader only on the homepage. Leave it unchecked to show it on all your website\'s pages. This option gets ignored if you insert the function manually.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['loading_position'] = array(
			'section' => 'appearance',
			'title'   => __( 'Loading position', 'slash-admin' ),
			'desc'    => __( 'By default, the function utilizes WordPress\'s <code>wp_body_open()</code> hook to load the script right after the opening of the <code>&lt;body&gt;</code> tag. As this is a new addition to WordPress, though (since v. 5.2), older themes might not support the hook. If that is the case for you, you can either load it on the footer via the <code>wp_footer</code> hook or manually add it to your template using <code>&lt;?php do_action(\'slash_admin_loader\'); ?&gt;</code>, wherever you like.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 0,
			'choices' => array(
				'wp_body_open'       => __( 'Top of the page', 'slash-admin' ),
				'wp_footer'          => __( 'Footer', 'slash-admin' ),
				'slash_admin_loader' => __( 'Manual', 'slash-admin' ),
			),
		);

		$this->settings['frontend_misc']   = array( // Frontend Misc
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Miscellaneous', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['obfuscate_email'] = array( // Remove the category from the title
			'section' => 'appearance',
			'title'   => __( 'Obfuscate emails', 'slash-admin' ),
			'desc'    => __( 'Filters the_content to automatically obfuscate email addresses using the <a href="https://developer.wordpress.org/reference/functions/antispambot/" target="_blank">antispambot()</a> function.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);


		$this->settings['frontend_misc']   = array( // Frontend Misc
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Miscellaneous', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['remove_category'] = array( // Remove the category from the title
			'section' => 'appearance',
			'title'   => __( 'Remove prefix from archives', 'slash-admin' ),
			'desc'    => __( 'Gets rid of the word "Category:", "Tag:" etc in front of the Archive title.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['pages_excerpt'] = array( // Add excerpt support to pages
			'section' => 'appearance',
			'title'   => __( 'Excerpt to pages', 'slash-admin' ),
			'desc'    => __( 'Add excerpt support to pages.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['widget_shortcodes'] = array( // Enable shortcodes in widgets
			'section' => 'appearance',
			'title'   => __( 'Shortcodes in widgets', 'slash-admin' ),
			'desc'    => __( 'Enable use of shortcodes in widgets.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);


		$this->settings['old_ie_heading'] = array( // Compatibility
			'section' => 'appearance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Compatibility', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['oldie_warning'] = array( // Hide update notices for all but Admins
			'section' => 'appearance',
			'title'   => __( 'Old IE Warning', 'slash-admin' ),
			'desc'    => __( 'If the visitor uses Internet Explorer, display a warning.<br/> <code>Discreet</code> appears at the top of the page and can be closed by the user. <code>Aggressive</code> covers the entire page and cannot be closed.',
				'slash-admin' ),
			'type'    => 'select',
			'choices' => array(
				'disabled'   => __( 'Disabled', 'slash-admin' ),
				'discreet'   => __( 'Discreet', 'slash-admin' ),
				'aggressive' => __( 'Aggressive', 'slash-admin' ),
			),
			'std'     => 'disabled',
		);

		/* Administration
		===========================================*/
		$this->settings['google_analytics'] = array(
			'section' => 'administration',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Inject scripts', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Add code in the site\'s header (e.g. for Google Analytics), body and footer. You can load scripts only if a specific cookie is set. Example: <code>&lt;&lt;cookie=eucookie&gt;&gt;YOUR SCRIPT HERE&lt;&lt;/cookie&gt;&gt;</code>',
				'slash-admin' ),
		);
		$this->settings['analytics']        = array(
			'title'   => __( 'Header code', 'slash-admin' ),
			'desc'    => __( 'It will be inserted before the <code>&lt;/head&gt;</code> tag).', 'slash-admin' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'administration',
		);
		$this->settings['scripts_body']     = array(
			'title'   => __( 'Body code', 'slash-admin' ),
			'desc'    => __( 'It will be injected right after the <code>&lt;body&gt;</code> opening. It requires that the theme uses the <a href="https://developer.wordpress.org/reference/functions/wp_body_open/" target="_blank">wp_body_open</a> hook.',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'administration',
		);
		$this->settings['scripts_footer']   = array(
			'title'   => __( 'Footer code', 'slash-admin' ),
			'desc'    => __( 'It will be inserted at the footer before the closing of the <code>&lt;/footer&gt;</code> tag.',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'textarea',
			'section' => 'administration',
		);

		$this->settings['admin_overrides_header'] = array(
			'section' => 'administration',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Admin overrides', 'slash-admin' ),
			'type'    => 'heading',
		);

		$admins     = get_users();
		$admin_list = [
			'0' => __( 'None', 'slash-admin' ),
		];
		foreach ( $admins as $admin ) {
			if ( in_array( 'administrator', $admin->roles ) ) {
				$admin_list[ $admin->ID ] = $admin->user_login . ' (' . $admin->user_email . ')';
			}
		}
		$this->settings['slash_techie']        = array(
			'section' => 'administration',
			'title'   => __( 'Techie user', 'slash-admin' ),
			'desc'    => __( 'Assigning a user with the "Techie" attribute will hide parts of the admin from everybody else, except for the given user. This includes features like Site Health, Recovery mode email notifications, Admin update notices, plugin and theme auto-update email notifications and ACF Settings panel visibility.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => '0',
			'choices' => $admin_list,
		);
		$this->settings['recovery_mode_email'] = array(
			'title'   => __( 'Recovery mode email(s)', 'slash-admin' ),
			'desc'    => __( 'Since WordPress 5.2 there is a built-in feature that detects when a plugin or theme causes a fatal error on your site, and notifies you with this automated email. By default, it will be sent to the admin email, unless you have declared a "Techie". To override it, add the alternative address here. For multiple recipients, separate them with a comma (,).',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'text',
			'section' => 'administration',
		);

		// Taxonomies
		$this->settings['taxonomy_settings_heading'] = array(
			'title'   => '',
			'section' => 'administration',
			'desc'    => __( 'Taxonomies', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['taxonomy_order'] = array(
			'section' => 'administration',
			'title'   => __( 'Term order in posts', 'slash-admin' ),
			'desc'    => __( 'Set non-hierarchical taxonomies order based on the order added on a post',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['remove_tags'] = array(
			'section' => 'administration',
			'title'   => __( 'Tag support', 'slash-admin' ),
			'desc'    => __( 'Remove tags support', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['remove_categories'] = array(
			'section' => 'administration',
			'title'   => __( 'Category support', 'slash-admin' ),
			'desc'    => __( 'Remove category support', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['limit_revisions_header'] = array(
			'section' => 'administration',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Limit revisions', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['limit_revisions']        = array( // Limit Revisions
			'section' => 'administration',
			'title'   => __( 'Limit post revisions', 'slash-admin' ),
			'desc'    => __( 'Limit the number of revisions that WordPress keeps for each post type. By default, an infinite number of revisions are stored if a post type supports revisions. Keep in mind that if you restrict this number, WordPress will purge the older revisions only after the post is updated.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 'default',
			'choices' => array(
				'default' => __( 'WordPress default (unlimited revisions)', 'slash-admin' ),
				'one'     => __( '1 revision (practically disables it)', 'slash-admin' ),
				'two'     => __( '2 revisions', 'slash-admin' ),
				'three'   => __( '3 revisions', 'slash-admin' ),
				'four'    => __( '4 revisions', 'slash-admin' ),
				'five'    => __( '5 revisions', 'slash-admin' ),
				'ten'     => __( '10 revisions', 'slash-admin' ),
				'twenty'  => __( '20 revisions', 'slash-admin' ),
				'fifty'   => __( '50 revisions', 'slash-admin' ),
			),
		);

		$this->settings['prevent_editing'] = array( // Prevent updates on old posts
			'section' => 'administration',
			'title'   => __( 'Prevent updates on old posts', 'slash-admin' ),
			'desc'    => __( 'Block post updates and deletion if the post is older than a specific period of time. Applies only to editors (admins can still edit the post as usual).',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 'default',
			'choices' => array(
				'default'  => __( 'Disabled', 'slash-admin' ),
				'oneday'   => __( 'After one day', 'slash-admin' ),
				'oneweek'  => __( 'After one week (7 days)', 'slash-admin' ),
				'twoweeks' => __( 'After two weeks (14 days)', 'slash-admin' ),
				'onemonth' => __( 'After one month (30 days)', 'slash-admin' ),
			),
		);

		// Jetpack
		$this->settings['jetpack_header']           = array(
			'section' => 'administration',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Jetpack settings', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['jetpack_development_mode'] = array(
			'section' => 'administration',
			'title'   => __( 'Enable Jetpack development mode', 'slash-admin' ),
			'desc'    => __( 'With Development Mode, features that do not require a connection to WordPress.com servers can be activated on a localhost WordPress installation for testing. Dont\' forget to un-check it back when you go live.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['jetpack_move_share'] = array(
			'section' => 'administration',
			'title'   => __( 'Move Jetpack share and like buttons', 'slash-admin' ),
			'desc'    => __( 'Jetpack, by default, just attaches it\'s share and like buttons to two filters: the_content() and the_excerpt(). If you want to manually display them somewhere else, check this option to remove them. Then, to show them anywhere you like in your template, use: <br/><code>&lt;?php if ( function_exists( \'sharing_display\' ) ) { <br/> sharing_display( \'\', true ); <br/>}<br/> if ( class_exists( \'Jetpack_Likes\' ) ) {<br/> $custom_likes = new Jetpack_Likes;<br/> echo $custom_likes->post_likes( \'\' );<br/>}?></code>',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		// Maintenance mode
		$this->settings['maintenance_mode_header'] = array(
			'section' => 'administration',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Maintenance mode', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['maintenance_mode']        = array(
			'section' => 'administration',
			'title'   => __( 'Enable maintenance mode', 'slash-admin' ),
			'desc'    => __( 'If checked, non-Admins will not be able to acess the WordPress backend and they will see the following message instead. Admins can always login as usual. Dont\' forget to un-check it back when you are done.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);
		$this->settings['maintenance_mode_msg']    = array(
			'title'   => __( 'Maintenance message', 'slash-admin' ),
			'desc'    => __( 'The message to be displayed if an non-Admin tries to login while in maintenance mode.',
				'slash-admin' ),
			'std'     => sprintf( __( 'Site undergoing maintainance. Content uploading and editing will not be available for a while. If you have a question, please contact the website\'s <a href=\'mailto:%s\'>Administrator</a>.',
				'slash-admin' ),
				get_option( 'admin_email' ) ),
			'type'    => 'textarea',
			'section' => 'administration',
		);

		/* Login screen
		===========================================*/

		$this->settings['login_screen'] = array(
			'section' => 'frontend_usability',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Login screen', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['logo'] = array( // Logo
			'title'   => __( 'Upload your logo', 'slash-admin' ),
			'desc'    => __( 'Upload the logo to appear at the login screen of your website. The plugin will try to automatically adjust the width/height but the suggested dimensions are <strong>320x80 pixels</strong>.',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'upload',
			'section' => 'frontend_usability',
		);

		$this->settings['login_links'] = array(
			'section' => 'frontend_usability',
			'title'   => __( 'Fix links at the login screen', 'slash-admin' ),
			'desc'    => __( 'Fix the links at the wordpress login screen in order to link to your website instead of wordpress.org.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
		);

		$this->settings['homepage_redirect'] = array( // Redirect to the homepage after login
			'section' => 'frontend_usability',
			'title'   => __( 'Go to homepage after login', 'slash-admin' ),
			'desc'    => __( 'After log-in, redirect users to the website\'s homepage instead of their profile page.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['disable_adminbar'] = array( // Disable Admin Bar for non Admins.
			'section' => 'frontend_usability',
			'title'   => __( 'Disable Admin Bar for non Admins', 'slash-admin' ),
			'desc'    => __( 'Disable the Admin Bar for all users except Administrators (applies only to the front-end).',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['login_css']        = array(
			'section' => 'frontend_usability',
			'title'   => __( 'Custom CSS', 'slash-admin' ),
			'desc'    => __( 'Your custom styles for the login screen (quick referrence for tags you might want to use: <code>body.login.login-action-login, div#login, div#login h1 a, form#loginform, input#user_login.input, input#user_pass.input, input#wp-submit, p#nav, p#backtoblog</code>).',
				'slash-admin' ),
			'type'    => 'textarea',
		);

		/* Backend usability
		===========================================*/

		$this->settings['restrict_access'] = array(
			'section' => 'backend_usability',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Hide options for non-admins', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['exclude_media']    = array(
			'section' => 'backend_usability',
			'title'   => __( 'Media', 'slash-admin' ),
			'desc'    => __( 'Exclude media', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_comments'] = array(
			'section' => 'backend_usability',
			'title'   => __( 'Comments', 'slash-admin' ),
			'desc'    => __( 'Exclude comments', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_tools']    = array(
			'section' => 'backend_usability',
			'title'   => __( 'Tools', 'slash-admin' ),
			'desc'    => __( 'Exclude tools', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_profile']  = array(
			'section' => 'backend_usability',
			'title'   => __( 'Profile', 'slash-admin' ),
			'desc'    => __( 'Exclude profile', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_posts']    = array(
			'section' => 'backend_usability',
			'title'   => __( 'Posts', 'slash-admin' ),
			'desc'    => __( 'Exclude posts', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_pages']    = array(
			'section' => 'backend_usability',
			'title'   => __( 'Pages', 'slash-admin' ),
			'desc'    => __( 'Exclude pages', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_jetpack']  = array(
			'section' => 'backend_usability',
			'title'   => __( 'Jetpack', 'slash-admin' ),
			'desc'    => __( 'Exclude Jetpack plugin', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['exclude_custom']   = array(
			'section' => 'backend_usability',
			'title'   => __( 'Custom excludes', 'slash-admin' ),
			'desc'    => __( 'Exclude other options such as custom post types or even the Dashboard. To do so, add the names separated with commas and no blank spaces like for example <code>Dashboard,Pages,Media,Posts</code> etc. You can exclude <strong>up to 5 additional items</strong>.',
				'slash-admin' ),
			'type'    => 'text',
			'std'     => '',
		);

		$this->settings['css_excludes'] = array(
			'section' => 'backend_usability',
			'title'   => __( 'Exclude by CSS', 'slash-admin' ),
			'desc'    => __( 'If there is an section that you cannot hide with the options above, you can hide it by adding here its CSS id or class. You can hide multiple elements by separating their ids or classes with comma (e.g.: <code>#menu-item-id, #menu-item-2-id, .menu-item-3-class, .menu-item-4-class</code>). Please keep in mind that this only hides the options with CSS. You should only use it for usability purposes and not for security reasons.',
				'slash-admin' ),
			'type'    => 'text',
		);

		$this->settings['hide_update_notices'] = array( // Hide update notices for all but Admins
			'section' => 'backend_usability',
			'title'   => __( 'Hide update notices to non Admins', 'slash-admin' ),
			'desc'    => __( 'Hide the notices for updating Wordpress and other plugins for all users except from Admins',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		// Hide specific pages for non admins
		$this->settings['hide_specific_pages_header'] = array(
			'section' => 'backend_usability',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Hide specific pages from non admins', 'slash-admin' ),
			'type'    => 'heading',
		);

		$this->settings['hide_specific_pages'] = array(
			'section' => 'backend_usability',
			'title'   => __( 'Hide pages', 'slash-admin' ),
			'desc'    => __( 'Hide specific pages for non admins. Press "Ctrl/Cmd+Click" to select/deselect multiple pages.',
				'slash-admin' ),
			'type'    => 'multiple',
			'choices' => slashadmin_get_pages( true ),
		);


		$this->settings['editors_allow_header']               = array( // Show extra options to Editors
			'section' => 'backend_usability',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Show extra options to Editors', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['editors_allow_menus']                = array(
			'section' => 'backend_usability',
			'title'   => __( 'Menus', 'slash-admin' ),
			'desc'    => __( 'Allow Editors to manage the website\'s menus.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['editors_allow_widgets']              = array(
			'section' => 'backend_usability',
			'title'   => __( 'Widgets', 'slash-admin' ),
			'desc'    => __( 'Allow Editors to manage the website\'s widgets.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['editors_allow_customizer']           = array(
			'section' => 'backend_usability',
			'title'   => __( 'Customizer', 'slash-admin' ),
			'desc'    => __( 'Allow Editors access to the Theme Customizer.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['editors_allow_background']           = array(
			'section' => 'backend_usability',
			'title'   => __( 'Background', 'slash-admin' ),
			'desc'    => __( 'Allow Editors to change the website\'s background (check this only if your theme has support for WordPress\' <a href="http://codex.wordpress.org/Function_Reference/add_theme_support#Custom_Background" target="_blank">Custom Background</a> feature).',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['editors_gravityforms']               = array(
			'section' => 'backend_usability',
			'title'   => __( 'Gravity Forms', 'slash-admin' ),
			'desc'    => __( 'Allow Editors to view entries submitted via Gravity Forms (requires Gravity Forms plugin to be installed, obviously).',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['editors_gravityforms_remove_button'] = array(
			'section' => 'backend_usability',
			'title'   => __( 'Remove Gravity Forms "Add Form" button', 'slash-admin' ),
			'desc'    => __( ' Remove Gravity Forms\' "Add Form" button from all WYSIWYG editors (requires Gravity Forms plugin to be installed, obviously).',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		/* White label
		===========================================*/
		$this->settings['white_label_header'] = array(
			'section' => 'white_label',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'White label Backend', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['howdy']              = array(
			'section' => 'white_label',
			'title'   => __( 'Change "Howdy"', 'slash-admin' ),
			'desc'    => __( 'Change the "Howdy" message at the top right corner of the screen.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => '',
		);
		$this->settings['footer_txt']         = array(
			'section' => 'white_label',
			'title'   => __( 'Change footer text', 'slash-admin' ),
			'desc'    => __( 'Change the footer text (you can use HTML markup).', 'slash-admin' ),
			'type'    => 'textarea',
		);
		$this->settings['admin_logo']         = array( // Logo
			'title'   => __( 'Change the Admin logo', 'slash-admin' ),
			'desc'    => __( 'Replace the WordPress logo at the top left of the screen. Suggested image dimension is <strong>92x92 pixels</strong>.',
				'slash-admin' ),
			'std'     => '',
			'type'    => 'upload',
			'section' => 'white_label',
		);

		$this->settings['dashboard_welcome_header'] = array(
			'section' => 'white_label',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Welcome message', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Change the Dashboard welcome message. Removes the default welcome message at the Dashboard and replaces it with your own.',
				'slash-admin' ),
		);
		$this->settings['dashboard_welcome']        = array(
			'section' => 'white_label',
			'title'   => __( 'Your welcome message', 'slash-admin' ),
			'desc'    => __( 'Add the content of your custom welcome message (HTML markup is allowed).',
				'slash-admin' ),
			'type'    => 'textarea',
		);

		$this->settings['dashboard_widget_header']  = array(
			'section' => 'white_label',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Add a Dashboard widget', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Add a Dashboard Widget to provide general or commercial information to your clients (for example: your contact info or links to support documentation).',
				'slash-admin' ),
		);
		$this->settings['dashboard_widget_title']   = array(
			'section' => 'white_label',
			'title'   => __( 'Widget title', 'slash-admin' ),
			'desc'    => __( 'Add the title of the Dashboard widget.', 'slash-admin' ),
			'type'    => 'text',
			'std'     => '',
		);
		$this->settings['dashboard_widget_content'] = array(
			'section' => 'white_label',
			'title'   => __( 'Widget content', 'slash-admin' ),
			'desc'    => __( 'Add the content of the Dashboard widget (HTML markup is allowed).', 'slash-admin' ),
			'type'    => 'textarea',
		);

		$this->settings['admin_css_header'] = array(
			'section' => 'white_label',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Custom CSS', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['admin_css']        = array(
			'section' => 'white_label',
			'title'   => __( 'Custom CSS for the Admin', 'slash-admin' ),
			'desc'    => __( 'The styles that you put here affect only the Admin and they don\'t get called at the frontend. Quick referrence for tags you might want to use: <code>div#slashadmin_dashboard_widget</code> for the custom widget, <code>div#welcome-panel</code> for the welcome panel.',
				'slash-admin' ),
			'type'    => 'textarea',
		);

		/* Performance
		===========================================*/
		$this->settings['remove_scripts'] = array(
			'section' => 'performance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Remove scripts', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['remove_emojis']  = array(
			'section' => 'performance',
			'title'   => __( 'Emojis', 'slash-admin' ),
			'desc'    => __( 'Disable emojis (remove emojis scripts and styles).', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['remove_embeds'] = array(
			'section' => 'performance',
			'title'   => __( 'Disable oEmbed', 'slash-admin' ),
			'desc'    => __( 'If you don\'t use embedded content you can completely disable oEmbeds or load them only on specific pages.',
				'slash-admin' ),
			'type'    => 'select',
			'std'     => 'default',
			'choices' => array(
				'default'      => __( 'Keep it enabled (default)', 'slash-admin' ),
				'disable'      => __( 'Disable', 'slash-admin' ),
				'singles'      => __( 'Enable only on single posts, pages and custom posts', 'slash-admin' ),
				'home'         => __( 'Enable only on the Home Page', 'slash-admin' ),
				'not_archives' => __( 'Enable everywhere except from archives', 'slash-admin' ),
			),
		);

		// DNS Prefetching
		$this->settings['dns_prefetching_header'] = array(
			'section' => 'performance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'DNS prefetching', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['dns_prefetch']           = array(
			'section' => 'performance',
			'title'   => __( 'DNS prefetching', 'slash-admin' ),
			'desc'    => __( 'DNS prefetching notifies the client that there are assets we\'ll need later from a specific URL (outside our website\'s domain) so the browser can resolve the DNS as quickly as possible (<a href="https://developer.mozilla.org/en-US/docs/Web/HTTP/Controlling_DNS_prefetching" target="_blank">read more</a>). Enter here the  URLs to be prefetched (each URL in separate line). Here\'s some examples of sources that can be prefetched for an easy copy/paste: 
					</br><code>//fonts.googleapis.com</code> (Google web fonts)</br>
					<code>//google-analytics.com</code> & <code>//www.google-analytics.com</code> (Google Analytics)</br>
					<code>//platform.twitter.com</code> (Twitter)</br>
					<code>//maxcdn.bootstrapcdn.com</code> (MaxCDN, used by Font Awesome)',
				'slash-admin' ),
			'type'    => 'textarea',
		);

		$this->settings['prefetch_prerender_header'] = array(
			'section' => 'performance',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Prefetch and prerender pages', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Link prefetching is a browser mechanism, which utilizes browser idle time to download or prefetch documents that the user might visit in the near future. A web page provides a set of prefetching hints to the browser, and after the browser is finished loading the page, it begins silently prefetching specified documents and stores them in its cache. When the user visits one of the prefetched documents, it can be served up quickly out of the browser\'s cache. Prerendering downloads and renders the entire page and hides it from the user until it is requested, therefore, it should be used with caution.',
				'slash-admin' ),
		);
		$this->settings['prefetch_next']             = array(
			'section' => 'performance',
			'title'   => __( 'Prefetch next page', 'slash-admin' ),
			'desc'    => __( 'Prefetch next page when on archives.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['prerender_next']            = array(
			'section' => 'performance',
			'title'   => __( 'Prerender next page', 'slash-admin' ),
			'desc'    => __( 'Prerender next page when on archives.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['prefetch_home']             = array(
			'section' => 'performance',
			'title'   => __( 'Prefetch homepage', 'slash-admin' ),
			'desc'    => __( 'Prefetch homepage when on sigle posts and pages.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);
		$this->settings['prerender_home']            = array(
			'section' => 'performance',
			'title'   => __( 'Prerender homepage', 'slash-admin' ),
			'desc'    => __( 'Prerender homepage when on sigle posts and pages.', 'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		/* Shortcodes
		===========================================*/
		$this->settings['shortcodes_heading'] = array(
			'section' => 'shortcodes',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Shortcodes', 'slash-admin' ),
			'type'    => 'heading',
		);
		$this->settings['shortcode_mail']     = array( // Hide update notices for all but Admins
			'section' => 'shortcodes',
			'title'   => __( 'Protect mail from harvesters', 'slash-admin' ),
			'desc'    => __( 'To disguise an email address, use <code>[slash_mail address="yourmail@mail.com"]</code>.Use <code>[slash_mailto address="yourmail@mail.com"]</code> instead if you want to automatically make it a link as well. Using just <code>[slash_mail]</code> or <code>[slash_mailto]</code> will automatically display the post/page author\'s email address. For a more detailed explanation click on the Documentation tab.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['shortcode_phone'] = array( // Hide update notices for all but Admins
			'section' => 'shortcodes',
			'title'   => __( 'Show telephone numbers', 'slash-admin' ),
			'desc'    => __( 'Show a telephone number in a way that it is clickable. When clicked, if you are on a mobile device it opens the phone\'s dialer and if you are on a desktop computer it prompts to make a call via a related program (e.g. Skype). Here are some usage examples: <code>[slash_phone number="999999"]</code> would output a link "999999" which would make a phone call to 999999. <code>[slash_phone number="999999" prefix="+30"]</code> would output a link "999999" which would make a phone call to +30999999. <code>[slash_phone number="999999" prefix="+30" text="Call us"]</code> would output a link "Call us" which would make a phone call to +30999999.',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		$this->settings['shortcode_url'] = array( // Hide update notices for all but Admins
			'section' => 'shortcodes',
			'title'   => __( 'Enable relative URLs', 'slash-admin' ),
			'desc'    => __( 'If you develop your site on localhost or on a temporary URL, you might want to avoid absolute URLs inside posts and pages. That way you don\'t need to update your links after migrating to your actual domain.  The available options are:<br />
					<code>[slash_home]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/home_url" target="_blank">home URI</a> for the current site.<br />
					<code>[slash_theme]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/get_template_directory_uri" target="_blank">template directory URI</a> for the current theme.<br />
					<code>[slash_child]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/get_stylesheet_directory_uri" target="_blank">stylesheet directory URI</a> for the current theme/child theme.<br />
					Then, you can use them like so <code>&lt;img src="[slash_child]/images/image.png" /&gt;</code>, with which you would get <code>&lt;img src="http://www.yourdomain.com/wp-content/themes/childtheme/images/image.png" /&gt;</code>',
				'slash-admin' ),
			'type'    => 'checkbox',
			'std'     => 0,
		);

		/* FAQ
		===========================================*/

		$this->settings['q0_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'My theme also supports some of this plugin\'s features. Which one should I choose?',
				'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'It is up to you to decide whether you will use your theme\'s options or those provided by this plugin. It is recommended, though, that you keep those settings separated from your theme and the reason is simple: If at some point you decide to switch themes, those options will be lost and you have to remember to re-enter them. Keeping them in a plugin maintains the options between themes.',
				'slash-admin' ),
		);

		$this->settings['q1_1_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Which shortcodes are available?', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'You can enable the following shortcodes:
								<br />- <code>[slash_mail address="yourmail@mail.com"]</code>. If you manually include email addresses in your posts, you should consider disguising them in order to "fool" e-mail harvesters. This shortcode displays the "disguised" version of the given email as plain text.
								<br />- <code>[slash_mailto address="yourmail@mail.com"]</code> does the exact same thing, but it also transforms the text to a "mailto" link as well. 
								<br />- <code>[slash_mail]</code> and <code>[slash_mailto]</code> will automatically display the post/page author\'s email address. All the above shortcodes take advantage of the <code>&lt;?php echo antispambot(); ?&gt;</code> function. For a more in-depth explanation on why you should care about disquising your emails, check the <a href="http://codex.wordpress.org/Protection_From_Harvesters" target="_blank">WordPress Codex</a>.
								<br />- <code>[slash_home]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/home_url" target="_blank">home URI</a> for the current site. It is the equivalent of <code>&lt;?php echo home_url(); ?&gt;</code>. 
								<br />- <code>[slash_theme]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/get_template_directory_uri" target="_blank">template directory URI</a> for the current theme. It is the equivalent of <code>&lt;?php echo get_template_directory_uri(); ?&gt;</code>.
								<br />- <code>[slash_child]</code> retrieves the <a href="http://codex.wordpress.org/Function_Reference/get_stylesheet_directory_uri" target="_blank">stylesheet directory URI</a> for the current theme/child theme. It is the equivalent of <code>&lt;?php echo get_stylesheet_directory_uri(); ?&gt;</code>.
						',
				'slash-admin' ),
		);

		$this->settings['q1_1_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Available functions', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Slash Admin includes the following functions which you can use in your code:
								<br />- <code>slash_dump()</code>. You can use it instead of <code>var_dump()</code> to wrap the output in <code>&lt;pre&gt;&lt;/pre&gt;</code>tags, for better readability. <br />- <code>slash_admin_dump()</code> does the same thing, only this time the output is only visible to admins (can be handy if you want to debug a live site).
						',
				'slash-admin' ),
		);

		$this->settings['q2_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'What does hiding options for non-admins means?', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Sometimes you only use certain features of Wordpress. For example, your website might have comments disabled or not using a certain feature. Also, for better usability you might want to show your users only the options that concern them. Hiding those options won\'t remove them. You, as an administrator, will always see the full list of all the available options. An editor, though, won\'t see the hidden options, which helps him focus to only those that concern him.',
				'slash-admin' ),
		);

		$this->settings['q3_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Is it wise to hide update notices from my users?', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'Generally speaking, no. Wordpress\' default behaviour is probably the best, that\'s why the specific option is disabled by default. In some cases, though, users might get confused with those notifications or think that something is wrong with the website. In cases like that, you might want to keep the update notifications visible only for those who can apply them - namely the administrators. Keep in mind that, technically, selecting this option won\'t remove the notifications for the non-admins - it will just hide them via CSS.',
				'slash-admin' ),
		);

		$this->settings['q4_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'How does allowing access to appearance settings work?', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'You can allow editors access to one or more of the following sub-sections of the "Appearance" section: <br />- Customize<br />- Widgets<br />- Menus<br />- Background<br />Technically, by selecting even one of the above options you give editors access to the Appearance section. To prevent them from accessing unwanted subsections (e.g. you want them to see the Menus but not the Widgets) the plugin hides their links via CSS/JavaScript from both the backend and the frontend. If an editor knew the link for the Widgets subsection he/she could access it. By default the plugin respects the WordPress\' default behavior, keeping those options disabled (users have no access at all to the Appearance section).',
				'slash-admin' ),
		);

		$this->settings['q5_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Old browser warning behaves strangely with W3TC plugin', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'This is a known issue. When Page Caching is activated in the W3 Total Cache plugin, the old browser warning becomes unpredictable and it may appear not only in Internet Explorer but in Chrome. To deal with the problem you need to disable either the old IE warning or the W3TC Page Cache option.',
				'slash-admin' ),
		);

		$this->settings['q6_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Which are the minimum requirements to run this plugin?', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'This plugin has been tested with Wordpress 4.0 and above. It might probably work with older versions too, but you should always use the latest version of Wordpress.',
				'slash-admin' ),
		);
		$this->settings['q7_heading'] = array(
			'section' => 'about',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'I found a bug / I have a suggestion or a feature request.', 'slash-admin' ),
			'type'    => 'heading',
			'para'    => __( 'You can post a message at the plugin\'s <a target="_blank" href="http://wordpress.org/support/plugin/slash-admin">support forum</a> and I will to my best to help you out.',
				'slash-admin' ),
		);
	}

	/**
	 * Initialize settings to their default values
	 *
	 * @since 1.0
	 */
	public function initialize_settings() {

		$default_settings = array();
		foreach ( $this->settings as $id => $setting ) {
			if ( $setting['type'] != 'heading' ) {
				$default_settings[ $id ] = isset( $setting['std'] ) ? $setting['std'] : '';
			}
		}

		update_option( 'slashadmin_options', $default_settings );

	}

	/**
	 * Register settings
	 *
	 * @since 1.0
	 */
	public function register_settings() {

		register_setting( 'slashadmin_options', 'slashadmin_options', array( &$this, 'validate_settings' ) );

		foreach ( $this->sections as $slug => $title ) {
			if ( $slug == 'about' ) {
				add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'slashadmin-options' );
			} else {
				add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'slashadmin-options' );
			}
		}

		$this->get_settings();

		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}

	}

	/**
	 * jQuery Tabs
	 *
	 * @since 1.0
	 */
	public function slashadmin_scripts() {

		wp_enqueue_media();
		wp_register_script( 'slashadmin-upload',
			plugins_url( 'js/uploader.js', __FILE__ ),
			array(
				'jquery',
				'jquery-ui-tabs',
				'media-upload',
				'thickbox',
			) );
		$params = array(
			'txt' => __( 'Use this image', 'slash-admin' ),
		);
		wp_localize_script( 'slashadmin-upload', 'uploader', $params );

		wp_enqueue_script( 'slashadmin-upload' );

		//Media Uploader Style
		wp_enqueue_style( 'thickbox' );

	}

	/**
	 * Styling for the plugin options page
	 *
	 * @since 1.0
	 */
	public function styles() {

		wp_register_style( 'slashadmin-admin', plugins_url( 'css/slashadmin-options.css', __FILE__ ) );
		wp_enqueue_style( 'slashadmin-admin' );

	}

	/**
	 * Validate settings
	 *
	 * @since 1.0
	 */
	public function validate_settings( $input ) {

		if ( ! isset( $input['reset_plugin'] ) ) {
			$options = get_option( 'slashadmin_options' );

			foreach ( $this->checkboxes as $id ) {
				if ( isset( $options[ $id ] ) && ! isset( $input[ $id ] ) ) {
					unset( $options[ $id ] );
				}
			}

			return $input;
		}

		return false;

	}

}

$slashadmin_options = new Slash_Admin_Options();