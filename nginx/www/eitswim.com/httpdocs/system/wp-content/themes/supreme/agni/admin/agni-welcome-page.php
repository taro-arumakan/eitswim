<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'system_status';

function agni_let_to_num( $size ) {
	$l = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );
	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}
	return $ret;
}
?>
<div class="wrap about-wrap agni-welcome-page-wrap">
	<h1><?php esc_html_e( 'Welcome to ', 'cookie' ); echo '<span style="text-transform:capitalize;">'.wp_get_theme()->get('Name').'</span>'; ?></h1>
	<p class="about-text">
		<?php esc_html_e( 'Thank you so much for using Studioelc. We really work hard to provide an awesome product which helps you to achieve the high quality website with less amount of time.', 'cookie' ); ?>
	</p>
	<div class="wp-badge cookie-admin-badge">
		<?php esc_html_e( 'Version ', 'cookie' ); echo wp_get_theme()->get('Version'); ?>
	</div>
    <h2 class="nav-tab-wrapper wp-clearfix">
        <a href="<?php echo esc_url( admin_url() ); ?>admin.php?page=cookie&tab=system_status" class="nav-tab nav-tab-active"><?php esc_html_e( 'System Status', 'cookie' ); ?></a>
    </h2>

    <?php if( $active_tab == 'system_status' ) {
      global $wpdb;
    	?>
        <div id="system-status" class="feature-section agni-system-status">
			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th colspan="3" data-export-label="Theme"><strong><?php esc_html_e( 'Theme', 'cookie' ); ?></strong></th>
					</tr>
				</thead>
					<?php
					include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );

					$active_theme         = wp_get_theme();
					$theme_version        = $active_theme->Version;
					?>
				<tbody>
					<tr>
						<td data-export-label="Name"><?php esc_html_e( 'Name', 'cookie' ); ?>:</td>
						<td><?php echo esc_html( $active_theme->Name ); ?></td>
					</tr>
					<tr>
						<td data-export-label="Version"><?php esc_html_e( 'Version', 'cookie' ); ?>:</td>
						<td><?php
							echo esc_html( $theme_version );
						?></td>
					</tr>
					<tr>
						<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'cookie' ); ?>:</td>
						<td><?php echo esc_url( $active_theme->{'Author URI'} ); ?></td>
					</tr>
					<tr>
						<td data-export-label="Child Theme"><?php esc_html_e( 'Child Theme', 'cookie' ); ?>:</td>
						<td><?php
							echo is_child_theme() ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<span class="dashicons dashicons-no-alt"></span>';
						?></td>
					</tr>
					<?php
					if( is_child_theme() ) :
						$parent_theme         = wp_get_theme( $active_theme->Template );
					?>
					<tr>
						<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent Theme Name', 'cookie' ); ?>:</td>
						<td><?php echo esc_html( $parent_theme->Name ); ?></td>
					</tr>
					<tr>
						<td data-export-label="Parent Theme Version"><?php esc_html_e( 'Parent Theme Version', 'cookie' ); ?>:</td>
						<td><?php
							echo esc_html( $parent_theme->Version );
						?></td>
					</tr>
					<tr>
						<td data-export-label="Parent Theme Author URL"><?php esc_html_e( 'Parent Theme Author URL', 'cookie' ); ?>:</td>
						<td><?php echo esc_url($parent_theme->{'Author URI'} ); ?></td>
					</tr>
					<?php endif ?>
				</tbody>
			</table>
        	<table class="widefat" cellspacing="0" id="status">
				<thead>
					<tr>
						<th colspan="3" data-export-label="WordPress Environment"><strong><?php esc_html_e( 'WordPress Environment', 'cookie' ); ?></strong></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-export-label="Home URL"><?php esc_html_e( 'Home URL', 'cookie' ); ?>:</td>
						<td><?php form_option( 'home' ); ?></td>
					</tr>
					<tr>
						<td data-export-label="Site URL"><?php esc_html_e( 'Site URL', 'cookie' ); ?>:</td>
						<td><?php form_option( 'siteurl' ); ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Version"><?php esc_html_e( 'WP Version', 'cookie' ); ?>:</td>
						<td><?php bloginfo('version'); ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Multisite"><?php esc_html_e( 'WP Multisite', 'cookie' ); ?>:</td>
						<td><?php if ( is_multisite() ) echo '<span class="dashicons dashicons-yes"></span>'; else echo '&ndash;'; ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Debug Mode"><?php esc_html_e( 'WP Debug Mode', 'cookie' ); ?>:</td>
						<td>
							<?php if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) : ?>
								<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
							<?php else : ?>
								<mark class="no">&ndash;</mark>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td data-export-label="Language"><?php esc_html_e( 'Language', 'cookie' ); ?>:</td>
						<td><?php echo get_locale(); ?></td>
					</tr>
				</tbody>
			</table>
			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th colspan="3" data-export-label="Server Environment"><strong><?php esc_html_e( 'Server Environment', 'cookie' ); ?></strong></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td data-export-label="WP Memory Limit"><?php esc_html_e( 'PHP Memory Limit', 'cookie' ); ?>:</td>
						<td><?php
							$memory = agni_let_to_num( WP_MEMORY_LIMIT );

							if ( function_exists( 'memory_get_usage' ) ) {
								$system_memory = agni_let_to_num( @ini_get( 'memory_limit' ) );
								$memory        = max( $memory, $system_memory );
							}

							if ( $memory < 134217728 ) {
								echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( wp_kses( __( '%s - We recommend setting memory to at least 128MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), size_format( $memory ), esc_url( 'https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) ) . '</mark>';
							} else {
								echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Max Upload Size"><?php esc_html_e( 'PHP Upload Max Size', 'cookie' ); ?>:</td>
						<td><?php 
						$max_upload_size_int = (int) size_format( wp_max_upload_size() );
						$max_upload_size = size_format( wp_max_upload_size() );
						if ( $max_upload_size_int < 32 ) {
							echo '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 32 MB.  <a href="%s" target="_blank">Here</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_upload_size ), esc_url( 'https://premium.wpmudev.org/blog/increase-memory-limit/' ) ) . '</mark>';
						} else {
							echo '<mark class="yes">' . esc_html( $max_upload_size ) . '</mark>';
						} ?></td>
					</tr>
					<?php if ( function_exists( 'ini_get' ) ) : ?>
						<tr>
							<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size', 'cookie' ); ?>:</td>
							<td><?php 
								$post_max_size = size_format( agni_let_to_num( ini_get( 'post_max_size' ) ) );
								if ( $post_max_size < 32 ) {
									echo '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 32 MB. See: <a href="%s" target="_blank">Here</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $post_max_size ), esc_url( '<a href="https://premium.wpmudev.org/blog/increase-memory-limit/' ) ) . '</mark>';
								} else {
									echo '<mark class="yes">' . esc_html( $post_max_size ) . '</mark>';
								} 
							?></td>
						</tr>
						<tr>
							<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Max Execution Time', 'cookie' ); ?>:</td>
							<td><?php 
								$max_exec_time = ini_get('max_execution_time');
								if ( $max_exec_time < 300 ) {
									echo '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 300. See: <a href="%s" target="_blank">Here</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_exec_time ), esc_url( 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded' ) ) . '</mark>';
								} else {
									echo '<mark class="yes">' . esc_html( $max_exec_time ) . '</mark>';
								}
							?></td>
						</tr>
						<tr>
							<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'cookie' ); ?>:</td>
							<td><?php 
								$max_input_var = ini_get( 'max_input_vars' );
								if ( $max_input_var < 2000 ) {
									echo '<mark class="error">' . sprintf( wp_kses( __( '%s - We recommend at least 2000. See: <a href="%s" target="_blank">Here</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $max_input_var ), esc_url( 'http://docs.woothemes.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/#section-2' ) ) . '</mark>';
								} else {
									echo '<mark class="yes">' . esc_html( $max_input_var ) . '</mark>';
								} 
							?></td>
						</tr>
						<tr>
							<td data-export-label="cURL Version"><?php esc_html_e( 'cURL Version', 'cookie' ); ?>:</td>
							<td><?php
								if ( function_exists( 'curl_version' ) ) {
									$curl_version = curl_version();
									echo esc_html( $curl_version['version'] ) . ', ' . esc_html( $curl_version['ssl_version'] );
								} else {
									esc_html_e( 'N/A', 'cookie' );
								}
							  ?></td>
						</tr>
					<?php endif;

					if ( $wpdb->use_mysqli ) {
						$ver = mysqli_get_server_info( $wpdb->dbh );
					} else {
						$ver = mysql_get_server_info();
					}

					if ( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) : ?>
						<tr>
							<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version', 'cookie' ); ?>:</td>
							<td><?php echo esc_html( $wpdb->db_version() ); ?>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<td data-export-label="PHP Version"><?php esc_html_e( 'PHP Version', 'cookie' ); ?>:</td>
						<td><?php
							// Check if phpversion function exists.
							if ( function_exists( 'phpversion' ) ) {
								$php_version = phpversion();

								if ( version_compare( $php_version, '5.6', '<' ) ) {
									echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( wp_kses( __( '%s - We recommend a minimum PHP version of 5.6. See: <a href="%s" target="_blank">How to update your PHP version</a>', 'cookie' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_html( $php_version ), esc_url( 'https://docs.woocommerce.com/document/how-to-update-your-php-version/' ) ) . '</mark>';
								} else {
									echo '<mark class="yes">' . esc_html( $php_version ) . '</mark>';
								}
							} else {
								esc_html_e( "Couldn't determine PHP version because phpversion() doesn't exist.", 'cookie' );
							}
							?></td>
					</tr>
					<tr>
						<td data-export-label="Server Info"><?php esc_html_e( 'Server Info', 'cookie' ); ?>:</td>
						<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
					</tr>
				</tbody>
			</table>
			<table class="widefat" cellspacing="0">
				<thead>
					<tr>
						<th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><strong><?php esc_html_e( 'Active Plugins', 'cookie' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$active_plugins = (array) get_option( 'active_plugins', array() );

					if ( is_multisite() ) {
						$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
						$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
					}

					foreach ( $active_plugins as $plugin ) {

						$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$version_string = '';
						$network_string = '';

						if ( ! empty( $plugin_data['Name'] ) ) {

							// Link the plugin name to the plugin url if available.
							$plugin_name = esc_html( $plugin_data['Name'] );

							if ( ! empty( $plugin_data['PluginURI'] ) ) {
								$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage' , 'cookie' ) . '" target="_blank">' . $plugin_name . '</a>';
							}

							?>
							<tr>
								<td><?php echo wp_kses( $plugin_name, array( 'a' => array( 'href' => array(), 'title' => array(), 'target' => array() ) ) ); ?></td>
								<td><?php echo sprintf( _x( 'by %s', 'by author', 'cookie' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
        </div>
    <?php } ?>

</div>


