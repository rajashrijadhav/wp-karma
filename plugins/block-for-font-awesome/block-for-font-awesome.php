<?php
/**
 * Plugin Name: Block for Font Awesome
 * Plugin URI: https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/
 * Description: Display a Font Awesome 5 icon in a Gutenberg block.
 * Version: 1.1.2
 * Author: Ciprian Popescu
 * Author URI: https://getbutterfly.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html

 * Font Awesome Free (c) (https://fontawesome.com/license)

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! function_exists( 'add_filter' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );

    exit();
}

define( 'GBFA_PLUGIN_VERSION', '1.1.2' );
define( 'GBFA5_VERSION', '5.15.4' );

require_once 'block/index.php';

function getbutterfly_fa_enqueue() {
    wp_enqueue_script( 'fa5', 'https://use.fontawesome.com/releases/v' . GBFA5_VERSION . '/js/all.js', [], GBFA5_VERSION, true );
}

if ( (int) get_option( 'fa_enqueue_fe' ) === 1 ) {
    add_action( 'wp_enqueue_scripts', 'getbutterfly_fa_enqueue' );
}

/**
 * Register/enqueue plugin scripts and styles (back-end)
 */
function getbutterfly_fa_enqueue_scripts() {
    wp_enqueue_style( 'wpfcs', plugins_url( 'assets/css/admin.css', __FILE__ ), [], GBFA_PLUGIN_VERSION );

    if ( (int) get_option( 'fa_enqueue_be' ) === 1 ) {
        wp_enqueue_script( 'fa5', 'https://use.fontawesome.com/releases/v' . GBFA5_VERSION . '/js/all.js', [], GBFA5_VERSION, true );
    }
}
add_action( 'admin_enqueue_scripts', 'getbutterfly_fa_enqueue_scripts' );


add_action( 'init', 'getbutterfly_fa_block_init' );

add_filter( 'block_categories', 'getbutterfly_block_categories', 10, 2 );
add_action( 'enqueue_block_editor_assets', 'getbutterfly_fa_block_enqueue' );

add_shortcode( 'fa', 'getbutterfly_fa_block_render' );



register_activation_hook( __FILE__, 'getbutterfly_fa_on_activation' );

function getbutterfly_fa_on_activation() {
    add_option( 'fa_enqueue_fe', 1 );
    add_option( 'fa_enqueue_be', 1 );
}



function getbutterfly_fa_menu_links() {
    add_options_page( 'Font Awesome Settings', 'Font Awesome', 'manage_options', 'fa', 'getbutterfly_fa_build_admin_page' );
}

add_action( 'admin_menu', 'getbutterfly_fa_menu_links', 10 );

function getbutterfly_fa_build_admin_page() {
    $tab     = ( filter_has_var( INPUT_GET, 'tab' ) ) ? filter_input( INPUT_GET, 'tab' ) : 'dashboard';
    $section = 'admin.php?page=fa&amp;tab=';
    ?>
    <div class="wrap">
        <h1>Font Awesome Settings</h1>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo $section; ?>dashboard" class="nav-tab <?php echo $tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">Dashboard</a>
            <a href="<?php echo $section; ?>help" class="nav-tab <?php echo $tab === 'help' ? 'nav-tab-active' : ''; ?>">Help</a>
        </h2>

        <?php
        if ( $tab === 'dashboard' ) {
            global $wpdb;

            if ( isset( $_POST['save_fa_settings'] ) ) {
                update_option( 'fa_enqueue_fe', (int) $_POST['fa_enqueue_fe'] );
                update_option( 'fa_enqueue_be', (int) $_POST['fa_enqueue_be'] );

                echo '<div class="updated notice is-dismissible"><p>Settings updated successfully!</p></div>';
            }
            ?>

            <h2><span class="dashicons dashicons-superhero"></span> Dashboard</h2>

            <div class="gb-ad" id="gb-ad">
                <h3 class="gb-ad--header"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 68 68"><defs/><rect width="100%" height="100%" fill="none"/><g class="currentLayer"><path fill="#fff" d="M34.76 33C22.85 21.1 20.1 13.33 28.23 5.2 36.37-2.95 46.74.01 50.53 3.8c3.8 3.8 5.14 17.94-5.04 28.12-2.95 2.95-5.97 5.84-5.97 5.84L34.76 33"/><path fill="#fff" d="M43.98 42.21c5.54 5.55 14.59 11.06 20.35 5.3 5.76-5.77 3.67-13.1.98-15.79-2.68-2.68-10.87-5.25-18.07 1.96-2.95 2.95-5.96 5.84-5.96 5.84l2.7 2.7m-1.76 1.75c5.55 5.54 11.06 14.59 5.3 20.35-5.77 5.76-13.1 3.67-15.79.98-2.69-2.68-5.25-10.87 1.95-18.07 2.85-2.84 5.84-5.96 5.84-5.96l2.7 2.7" class="selected"/><path fill="#fff" d="M33 34.75c-11.9-11.9-19.67-14.67-27.8-6.52-8.15 8.14-5.2 18.5-1.4 22.3 3.8 3.79 17.95 5.13 28.13-5.05 3.1-3.11 5.84-5.97 5.84-5.97L33 34.75"/></g></svg> Thank you for using Block for Font Awesome!</h3>
                <div class="gb-ad--content">
                    <p>If you enjoy this plugin, do not forget to <a href="https://wordpress.org/support/plugin/block-for-font-awesome/reviews/?filter=5" rel="external">rate it</a>! We work hard to update it, fix bugs, add new features and make it compatible with the latest web technologies.</p>
                    <p>Have you tried our other <a href="https://getbutterfly.com/wordpress-plugins/">WordPress plugins</a>?</p>
                </div>
                <div class="gb-ad--footer">
                    <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>.<br>Built by <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <a href="https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/">Documentation</a> &middot; <small>Code wrangling since 2005</small></p>

                    <p>
                        <small>You are using PHP <?php echo PHP_VERSION; ?> and MySQL <?php echo $wpdb->db_version(); ?>.</small>
                    </p>
                </div>
            </div>

            <form method="post">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label>Font Awesome <?php echo GBFA5_VERSION; ?></label></th>
                            <td>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_fe" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_fe' ) ); ?>> Enqueue on front-end
                                </p>
                                <p>
                                    <input type="checkbox" class="wppd-ui-toggle" name="fa_enqueue_be" value="1" <?php checked( 1, (int) get_option( 'fa_enqueue_be' ) ); ?>> Enqueue on back-end
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><input type="submit" name="save_fa_settings" class="button button-primary" value="Save Changes"></th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        <?php } elseif ( $tab === 'help' ) { ?>
            <h2><span class="dashicons dashicons-editor-help"></span> Help</h2>

            <p>This plugin allows you to display any Font Awesome 5 icon as an editor block (Gutenberg).</p>
            <p>You can also display inline icons by using the <code>[fa class="fas fa-fw fa-3x fa-phone"]</code> shortcode.</p>

            <p><a href="https://getbutterfly.com/wordpress-plugins/block-for-font-awesome/" class="button button-primary">Documentation</a></p>

            <hr>
            <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>. If you enjoy this plugin, don't forget to rate it. Also, try our other WordPress plugins at <a href="https://getbutterfly.com/wordpress-plugins/" rel="external" target="_blank">getButterfly.com</a>.</p>
            <p>&copy;<?php echo gmdate( 'Y' ); ?> <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <small>Code wrangling since 2005</small></p>
        <?php } ?>
    </div>
    <?php
}
