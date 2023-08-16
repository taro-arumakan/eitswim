<?php
/**
 * Luxeritas WordPress Theme - free/libre wordpress platform
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @copyright Copyright (C) 2015 Thought is free.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @author LunaNuko
 * @link https://thk.kanzae.net/
 * @translators rakeem( http://rakeem.jp/ )
 */

class luxe_customize {
	private $_active	= '';
	private $_page		= '';
	private $_tabs		= array();

	public function __construct() {
	}

	public function luxe_custom_form() {
		$this->_page = isset( $_GET['page'] ) ? $_GET['page'] : 'luxe';

		echo '<div class="wrap narrow">';

		if( $this->_page === 'luxe' ) {
			$this->_active = isset( $_GET['active'] ) ? $_GET['active'] : 'welcome';

			$this->_tabs = array(
			  'welcome'  => 'Welcome',
				'header'	=> 'Head ' . __( 'tag', 'studioelc' ),
				'title'		=> 'Title ' . __( 'tag', 'studioelc' ),
				'optimize'	=> __( 'Compression and optimization', 'studioelc' ),
//				'style'		=> 'CSS',
//				'script'	=> 'Javascript',
				'copyright'	=> __( 'Copyright', 'studioelc' ),
				'backup'	=> __( 'Backup', 'studioelc' ),
				'reset'		=> __( 'Reset', 'studioelc' ),
			);

            echo	'<h2 class="luxe-customize-title">', esc_html( str_replace( array( '<h2>', '</h2>' ), '', get_admin_page_title() ) ),
            '<spna class="luxe-title-button-block"><span class="luxe-title-button"><a href="', esc_url( admin_url( 'customize.php?return=/system/wp-admin/admin.php' . '?page=luxe' ) ) ,'" ',
            'class="button button-primary">', __( 'Customizing the Appearance', 'studioelc' ), '</a></span>',
            '</h2>';
		}
		elseif( $this->_page === 'luxe_sns' ) {
			$this->_active = isset( $_GET['active'] ) ? $_GET['active'] : 'sns_post';

			$this->_tabs = array(
				'sns_post'	=> __( 'Post page', 'studioelc' ),
				'sns_page'	=> __( 'Static page', 'studioelc' ),
				'sns_home'	=> __( 'Top page', 'studioelc' ),
				'sns_setting'	=> __( 'Settings', 'studioelc' ),
				'sns_csv'	=> 'CSV',
				'sns_get'	=> __( 'All caches restructure', 'studioelc' ),
			);

			echo	'<h2>SNS ' . __( 'Counter', 'studioelc' ) . ' ' . __( 'Cache', 'studioelc' ) . ' ' . __( 'Control', 'studioelc' ) . '</h2>';
		}
		else {
			$this->_active = isset( $_GET['active'] ) ? $_GET['active'] : 'edit_style';

			$this->_tabs = array(
				'edit_style'		=> 'style.css',
				'edit_script'		=> 'Javascript',
				'edit_header'		=> 'Head ' . __( 'tag', 'studioelc' ),
				'edit_footer'		=> __( 'Footer', 'studioelc' ),
				'edit_analytics'	=> __( 'Access Analytics', 'studioelc' ),
				'edit_functions'	=> 'functions.php',
				'edit_amp'		=> __( 'Stylesheet for AMP', 'studioelc' ),
			);

			echo	'<h2>' . __( 'Child Theme Editor', 'studioelc' ) . '</h2>';
		}

		echo	'<h2 class="nav-tab-wrapper">';

		foreach( $this->_tabs as $key => $val ) {
			register_setting( $key, $key, 'esc_attr' );
			echo	'<a href="', esc_url( admin_url( 'admin.php?page=' . $this->_page . '&active=' . $key ) ) ,'" ',
				'class="nav-tab', $this->_active === $key ? ' nav-tab-active' : '', '">', esc_html( $val ), '</a>';
		}
		echo	'</h2>';

		settings_errors( 'luxe-custom' );

		$form = false;
		if(
			$this->_active !== 'backup'   &&
			$this->_active !== 'htaccess' &&
			$this->_active !== 'version'  &&
			$this->_active !== 'sns_post' &&
			$this->_active !== 'sns_page' &&
			$this->_active !== 'sns_home' &&
			$this->_active !== 'sns_csv'  &&
			$this->_active !== 'sns_get'
		) $form = true;

		// options.php は経由しないので、nonce のチェックは check_admin_referer でやる
		if( $form === true ) echo '<form id="luxe-customize" method="post" action="">';

		$func = '_' . $this->_active . '_section';
		if( method_exists( $this, $func ) === true ) {
			$this->$func();
		}
		else {
			$this->_empty_section();
		}

		settings_fields( $this->_active );

		ob_start();
		do_settings_sections( $this->_active );
		$settings = ob_get_clean();

		$settings = str_replace( '<h2>', '<fieldset class="luxe-field"><legend><h2 class="luxe-field-title">', $settings );
		$settings = str_replace( '</h2>', '</h2></legend>', $settings );
		echo $settings;

		if( $_GET['page'] === 'luxe_edit' ) {
			submit_button( '', 'primary', 'edit_save', true, array( 'disabled' => 1 ) );
			echo '</form>';
		}
		elseif( $form === true ) {
			submit_button( '', 'primary', 'save', true, array( 'disabled' => 1 ) );
			echo '</form>';
		}
?>
</div>
<script>
var og_img_change = null
,   amp_logo_change = null;
jQuery(document).ready(function($) {
	$('#luxe-customize').bind('keyup change', function() {
		$("#save").prop("disabled", false);
	});
	$('.luxe-field-title').click(function() {
		$(this).parent().nextAll().toggle();
	});
<?php
		if(
			isset( $_GET['page'] ) && $_GET['page'] === 'luxe' &&
			(
				( !isset( $_GET['active'] ) || isset( $_GET['active'] ) ) &&
				( $_GET['active'] === 'header' || $_GET['active'] === 'amp' )
			)
		) {
?>
	var intrval = function() {
		if( og_img_change !== $('#og-img-view').html() ) {
			if( og_img_change !== null ) {
				$("#save").prop("disabled", false);
			}
			og_img_change = $('#og-img-view').html();
		}
		if( amp_logo_change !== $('#amp-logo-view').html() ) {
			if( amp_logo_change !== null ) {
				$("#save").prop("disabled", false);
			}
			amp_logo_change = $('#amp-logo-view').html();
		}
	};
	setInterval(intrval, 1000);
<?php
		}
?>
});
</script>
<?php
	}

	public function sections( $args ) {
		get_template_part( 'inc/sections/' . $args['id'] );
		echo '</fieldset>';
	}

  private function _welcome_section() {
    $suffix = get_locale() === 'ja' ? '' : '';
    add_settings_section( 'welcome', 'WELCOME' . $suffix, array( $this, 'sections' ), $this->_active );
  }

	private function _header_section() {
		$suffix = get_locale() === 'ja' ? ' 関連' : '';
		add_settings_section( 'seo', 'SEO' . $suffix, array( $this, 'sections' ), $this->_active );
		add_settings_section( 'ogp', 'OGP' . $suffix, array( $this, 'sections' ), $this->_active );
	}
/**
	private function _amp_section() {
		add_settings_section( 'amp', 'AMP', array( $this, 'sections' ), $this->_active );
	}
*/
	private function _title_section() {
		add_settings_section( 'title', sprintf( __( 'Setting of %s', 'studioelc' ), 'Title ' . __( 'tag', 'studioelc' ) ), array( $this, 'sections' ), $this->_active );
	}

	private function _optimize_section() {
		add_settings_section( 'optimize-html', __( 'Compression of HTML', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		/**
		add_settings_section( 'optimize-css', __( 'Optimization of CSS', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'optimize-script', __( 'Optimization of Javascript', 'studioelc' ), array( $this, 'sections' ), $this->_active );
     */
	}

	private function _style_section() {
		add_settings_section( 'mode-select', __( 'Mode select', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'css-to-style', __( 'Direct output of external CSS', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'child-css', __( 'CSS of child theme', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'fontawesome', __( 'CSS of icon fonts', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'widget-css', __( 'CSS of widgets', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _script_section() {
		add_settings_section( 'jquery', 'jQuery', array( $this, 'sections' ), $this->_active );
		add_settings_section( 'bootstrap', 'Bootstrap ' . __( 'Plugins', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'script', __( 'Other setting of Javascript', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _search_section() {
		add_settings_section( 'search', sprintf( __( 'Setting of %s', 'studioelc' ), __( 'Search Widget', 'studioelc' ) ), array( $this, 'sections' ), $this->_active );
	}

	private function _captcha_section() {
		add_settings_section( 'captcha', sprintf( __( 'Setting of %s', 'studioelc' ), __( 'CAPTCHA', 'studioelc' ) ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'recaptcha', sprintf( __( 'Setting of %s', 'studioelc' ), 'Google reCAPTCHA ' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'securimage', sprintf( __( 'Setting of %s', 'studioelc' ), 'Securimage PHP CAPTCHA ' ), array( $this, 'sections' ), $this->_active );
	}

	private function _copyright_section() {
		add_settings_section( 'copyright', __( 'Format of copyright', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _others_section() {
		add_settings_section( 'others', sprintf( __( 'Setting of %s', 'studioelc' ), __( 'Others', 'studioelc' ) ), array( $this, 'sections' ), $this->_active );
	}

	private function _htaccess_section() {
		add_settings_section( 'htaccess', __( 'htaccess for speed boost', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _backup_section() {
		add_settings_section( 'backup', __( 'Back up or restore Supreme all settings', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		//add_settings_section( 'restore', __( 'Restore Luxeritas', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'backup-appearance', __( 'Back up or restore Supreme appearance settings', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'backup-child', __( 'Back up Child Theme', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _reset_section() {
		add_settings_section( 'all_clear', __( 'RESET all the customizations of Supreme', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'sns-cache-cleanup', __( 'Clean up of SNS count cache', 'studioelc' ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'blogcard-cache-cleanup', __( 'Clean up of Blog Card cache', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _version_section() {
		add_settings_section( 'version', '', array( $this, 'sections' ), $this->_active );
	}

	private function _sns_setting_section() {
		add_settings_section( 'sns-cache-setting', sprintf( __( 'Setting of %s', 'studioelc' ), __( 'cache', 'studioelc' ) ), array( $this, 'sections' ), $this->_active );
		add_settings_section( 'sns-cache-cleanup', __( 'Clean up of SNS count cache', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _sns_csv_section() {
		add_settings_section( 'sns-cache-csv', __( 'Download CSV', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _sns_get_section() {
		add_settings_section( 'sns-cache-all-get', __( 'Restructure of all SNS count caches', 'studioelc' ), array( $this, 'sections' ), $this->_active );
	}

	private function _sns_post_section() {
		get_template_part( 'inc/sns-count-view' );
		$cache_view = new cache_control();
		add_settings_section( 'sns-cache-list', '', array( $cache_view, 'sns_cache_list' ), $this->_active );
	}

	private function _sns_page_section() {
		$this->_sns_post_section();
	}

	private function _sns_home_section() {
		$this->_sns_post_section();
	}

	private function _edit_style_section() {
		get_template_part( 'inc/theme-editor' );
	}

	private function _edit_script_section() {
		$this->_edit_style_section();
	}

	private function _edit_header_section() {
		$this->_edit_style_section();
	}

	private function _edit_footer_section() {
		$this->_edit_style_section();
	}

	private function _edit_analytics_section() {
		$this->_edit_style_section();
	}

	private function _edit_functions_section() {
		$this->_edit_style_section();
	}

	private function _edit_amp_section() {
		$this->_edit_style_section();
	}

	private function _empty_section() {
		return;
	}
}
