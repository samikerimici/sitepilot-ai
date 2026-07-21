<?php
/**
 * Plugin Name: SitePilot AI
 * Plugin URI: https://lorraine.digital/
 * Description: Le copilote IA qui analyse, conseille et accompagne la gestion de votre site WordPress.
 * Version: 1.1.0-rc1
 * Author: Lorraine Digital
 * Author URI: https://lorraine.digital/
 * Text Domain: sitepilot-ai
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * License: GPL v2 or later
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
define( 'SPAI_VERSION', '1.1.0-rc1' );
define( 'SPAI_FILE', __FILE__ );
define( 'SPAI_DIR', plugin_dir_path( __FILE__ ) );
define( 'SPAI_URL', plugin_dir_url( __FILE__ ) );
require_once SPAI_DIR . 'app/Core/Plugin.php';
register_activation_hook( __FILE__, array( 'SitePilotAI\\Core\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'SitePilotAI\\Core\\Plugin', 'deactivate' ) );
SitePilotAI\Core\Plugin::instance()->boot();
