<?php
namespace fmwp\admin;


if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'fmwp\admin\Upgrade' ) ) {


	/**
	 * Class Upgrade
	 *
	 * This class handles all functions that changes data structures and moving files
	 *
	 * @package fmwp\admin
	 */
	class Upgrade {


		/**
		 * @var null
		 */
		protected static $instance = null;


		/**
		 * @var
		 */
		var $update_versions;
		var $update_packages;
		var $necessary_packages;


		/**
		 * @var string
		 */
		var $packages_dir;


		/**
		 * Main Upgrade Instance
		 *
		 * Ensures only one instance of FMWP is loaded or can be loaded.
		 *
		 * @since 1.0
		 * @static
		 * @see FMWP()
		 * @return Upgrade - Main instance
		 */
		static public function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		/**
		 * Upgrade constructor.
		 */
		function __construct() {
			$this->packages_dir = plugin_dir_path( __FILE__ ) . 'packages' . DIRECTORY_SEPARATOR;
			$this->necessary_packages = $this->need_run_upgrades();

			if ( ! empty( $this->necessary_packages ) ) {
				add_action( 'admin_menu', [ $this, 'admin_menu' ], 999 );

				if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					$this->init_packages_ajax();

					add_action( 'wp_ajax_fmwp_run_package', [ $this, 'ajax_run_package'] );
					add_action( 'wp_ajax_fmwp_get_packages', [ $this, 'ajax_get_packages' ] );
				}
			}

			add_action( 'in_plugin_update_message-' . fmwp_plugin, [ $this, 'in_plugin_update_message' ] );
		}


		/**
         * Function for major updates
         *
		 * @param array $args
		 */
		function in_plugin_update_message( $args ) {
			$show_additional_notice = false;
			if ( isset( $args['new_version'] ) ) {
				$old_version_array = explode( '.', fmwp_version );
				$new_version_array = explode( '.', $args['new_version'] );

				if ( $old_version_array[0] < $new_version_array[0] ) {
					$show_additional_notice = true;
				} else {
					if ( $old_version_array[1] < $new_version_array[1] ) {
						$show_additional_notice = true;
					}
				}

			}

			if ( $show_additional_notice ) {
				ob_start(); ?>

				<style type="text/css">
					.fmwp_plugin_upgrade_notice {
						font-weight: 400;
						color: #fff;
						background: #d53221;
						padding: 1em;
						margin: 9px 0;
						display: block;
						box-sizing: border-box;
						-webkit-box-sizing: border-box;
						-moz-box-sizing: border-box;
					}

					.fmwp_plugin_upgrade_notice:before {
						content: "\f348";
						display: inline-block;
						font: 400 18px/1 dashicons;
						speak: none;
						margin: 0 8px 0 -2px;
						-webkit-font-smoothing: antialiased;
						-moz-osx-font-smoothing: grayscale;
						vertical-align: top;
					}
				</style>

				<span class="fmwp_plugin_upgrade_notice">
					<?php printf( __( '%s is a major update, and we highly recommend creating a full backup of your site before updating.', 'forumwp' ), $args['new_version'] ); ?>
				</span>

				<?php ob_get_flush();
			}
		}


		/**
		 * Get array of necessary upgrade packages
		 *
		 * @return array
		 */
		function need_run_upgrades() {
			$fmwp_last_version_upgrade = get_option( 'fmwp_last_version_upgrade', '1.0' );

			$diff_packages = [];

			$all_packages = $this->get_packages();
			foreach ( $all_packages as $package ) {
				if ( version_compare( $fmwp_last_version_upgrade, $package, '<' ) && version_compare( $package, fmwp_version, '<=' ) ) {
					$diff_packages[] = $package;
				}
			}

			return $diff_packages;
		}


		/**
		 * Get all upgrade packages
		 *
		 * @return array
		 */
		function get_packages() {
			$update_versions = [];
			$handle = opendir( $this->packages_dir );
			if ( $handle ) {
				while ( false !== ( $filename = readdir( $handle ) ) ) {
					if ( $filename != '.' && $filename != '..' ) {
						if ( is_dir( $this->packages_dir . $filename ) ) {
							$update_versions[] = $filename;
						}
					}
				}
				closedir( $handle );

				usort( $update_versions, [ &$this, 'version_compare_sort' ] );
			}

			return $update_versions;
		}


		/**
		 *
		 */
		function init_packages_ajax() {
			foreach ( $this->necessary_packages as $package ) {
				$hooks_file = $this->packages_dir . $package . DIRECTORY_SEPARATOR . 'hooks.php';
				if ( file_exists( $hooks_file ) ) {
					$pack_ajax_hooks = include_once $hooks_file;

					foreach ( $pack_ajax_hooks as $action => $function ) {
						add_action( 'wp_ajax_fmwp_' . $action, "fmwp_upgrade_$function" );
					}
				}
			}
		}


		/**
		 *
		 */
		function init_packages_ajax_handlers() {
			foreach ( $this->necessary_packages as $package ) {
				$handlers_file = $this->packages_dir . $package . DIRECTORY_SEPARATOR . 'functions.php';
				if ( file_exists( $handlers_file ) ) {
					include_once $handlers_file;
				}
			}
		}


		/**
		 * Add Upgrades admin menu
		 */
		function admin_menu() {
			add_submenu_page( 'forumwp', __( 'Upgrade', 'forumwp' ), '<span style="color:#ca4a1f;">' . __( 'Upgrade', 'forumwp' ) . '</span>', 'manage_options', 'fmwp_upgrade', [ &$this, 'upgrade_page' ] );
		}


		/**
		 * Upgrade Menu Callback Page
		 */
		function upgrade_page() {
			$fmwp_last_version_upgrade = get_option( 'fmwp_last_version_upgrade', __( 'empty', 'forumwp' ) ); ?>

			<div class="wrap">
				<h2><?php printf( __( '%s - Upgrade Process', 'forumwp' ), fmwp_plugin_name ) ?></h2>
				<p><?php printf( __( 'You have installed <strong>%s</strong> version. Your latest DB version is <strong>%s</strong>. We recommend creating a backup of your site before running the update process. Do not exit the page before the update process has complete.', 'forumwp' ), fmwp_version, $fmwp_last_version_upgrade ) ?></p>
				<p><?php _e( 'After clicking the <strong>"Run"</strong> button, the update process will start. All information will be displayed in the <strong>"Upgrade Log"</strong> field.', 'forumwp' ); ?></p>
				<p><?php _e( 'If the update was successful, you will see a corresponding message. Otherwise, contact technical support if the update failed.', 'forumwp' ); ?></p>
				<h4><?php _e( 'Upgrade Log', 'forumwp' ) ?></h4>
				<div id="upgrade_log" style="width: 100%;height:300px; overflow: auto;border: 1px solid #a1a1a1;margin: 0 0 10px 0;"></div>
				<div>
					<input type="button" id="run_upgrade" class="button button-primary" value="<?php esc_attr_e( 'Run', 'forumwp' ) ?>"/>
				</div>
			</div>

			<script type="text/javascript">
				var fmwp_request_throttle = 15000;
				var fmwp_packages;

				jQuery( document ).ready( function() {
					jQuery( '#run_upgrade' ).click( function() {
						jQuery(this).prop( 'disabled', true );

						fmwp_add_upgrade_log( 'Upgrade Process Started...' );
						fmwp_add_upgrade_log( 'Get Upgrades Packages...' );

						jQuery.ajax({
							url: '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ) ?>',
							type: 'POST',
							dataType: 'json',
							data: {
								action: 'fmwp_get_packages',
								nonce: fmwp_admin_data.nonce
							},
							success: function( response ) {
								fmwp_packages = response.data.packages;

								fmwp_add_upgrade_log( 'Upgrades Packages are ready, start unpacking...' );

								//run first package....the running of the next packages will be at each init.php file
								fmwp_run_upgrade();
							}
						});
					});
				});


				/**
				 *
				 * @returns {boolean}
				 */
				function fmwp_run_upgrade() {
					if ( fmwp_packages.length ) {
						// 30s between upgrades
						setTimeout( function () {
							var pack = fmwp_packages.shift();
							fmwp_add_upgrade_log( '<br />=================================================================' );
							fmwp_add_upgrade_log( '<h4 style="font-weight: bold;">Prepare package "' + pack + '" version...</h4>' );
							jQuery.ajax({
								url: '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ) ?>',
								type: 'POST',
								dataType: 'html',
								data: {
									action: 'fmwp_run_package',
									pack: pack,
									nonce: fmwp_admin_data.nonce
								},
								success: function( html ) {
									fmwp_add_upgrade_log( 'Package "' + pack + '" is ready. Start the execution...' );
									jQuery( '#run_upgrade' ).after( html );
								}
							});
						}, fmwp_request_throttle );
					} else {
						window.location = '<?php echo add_query_arg( [ 'page' => 'forumwp-settings', 'fmwp-msg' => 'updated' ], admin_url( 'admin.php' ) ) ?>'
					}

					return false;
				}


				/**
				 *
				 * @param line
				 */
				function fmwp_add_upgrade_log( line ) {
					var log_field = jQuery( '#upgrade_log' );
					var previous_html = log_field.html();
					log_field.html( previous_html + line + "<br />" );
				}


				function fmwp_wrong_ajax() {
					fmwp_add_upgrade_log( 'Wrong AJAX response...' );
					fmwp_add_upgrade_log( 'Your upgrade was crashed, please contact with support' );
				}


				function fmwp_something_wrong() {
					fmwp_add_upgrade_log( 'Something went wrong with AJAX request...' );
					fmwp_add_upgrade_log( 'Your upgrade was crashed, please contact with support' );
				}
			</script>

			<?php

		}


		/**
		 *
		 */
		function ajax_run_package() {
			FMWP()->ajax()->check_nonce( 'fmwp-backend-nonce' );

			if ( empty( $_POST['pack'] ) ) {
				exit('');
			} else {
				ob_start();
				include_once $this->packages_dir . sanitize_text_field( $_POST['pack'] ) . DIRECTORY_SEPARATOR . 'init.php';
				ob_get_flush();
				exit;
			}
		}


		/**
		 *
		 */
		function ajax_get_packages() {
			FMWP()->ajax()->check_nonce( 'fmwp-backend-nonce' );

			$update_versions = $this->need_run_upgrades();
			wp_send_json_success( [ 'packages' => $update_versions ] );
		}


		/**
		 * Parse packages dir for packages files
		 */
		function set_update_versions() {
			$update_versions = [];
			$handle = opendir( $this->packages_dir );
			if ( $handle ) {
				while ( false !== ( $filename = readdir( $handle ) ) ) {
					if ( $filename != '.' && $filename != '..' )
						$update_versions[] = preg_replace( '/(.*?)\.php/i', '$1', $filename );
				}
				closedir( $handle );

				usort( $update_versions, [ &$this, 'version_compare_sort' ] );

				$this->update_versions = $update_versions;
			}
		}


		/**
		 * Sort versions by version compare function
		 * @param $a
		 * @param $b
		 * @return mixed
		 */
		function version_compare_sort( $a, $b ) {
			return version_compare( $a, $b );
		}

	}
}