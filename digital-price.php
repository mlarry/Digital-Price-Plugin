<?php
/*
Plugin Name: Digital Price Plugin
Plugin URI: http://www.kevlaur.net
Description: This plugin performs digital pricing.
Author: Mary Barbour
Version: 1.0
Author URI: http://www.kevlaur.net/
 */

//function dprice_init()
//{
//    register_setting('dprice_options','dprice_cc_email');
//}
//add_action('admin_init','dprice_init');

function dprice_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix.'dprice';
        
    if ( $wpdb->get_var('SHOW TABLES LIKE "'. $table_name . '"') != $table_name )
    {
        $sql = 'CREATE TABLE IF NOT EXISTS '.$table_name.'(
        id INTEGER(10) UNSIGNED AUTO_INCREMENT,
        hit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        product_name VARCHAR(255),
        PRIMARY KEY (id) )';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option('dprice_database_version','1.0');
    }
}
register_activation_hook(__FILE__,'dprice_activate');

function dprice_option_page()
{
    echo '<div class="wrap">';
    echo '<h2>Digital Price Option Page</h2>';
    echo '<p>Welcome to the Digital Prices Plugin.</p>';
    echo '<form action="options.php" method="post" id="digital-prices-email-options-form">';
    echo settings_fields('dprice_options');
    echo '<h3><label for="dprice_cc_email">Email to send CC to: </label>';
    echo '<input type="text" id="dprice_cc_email" name="dprice_cc_email" value="';
    echo esc_attr( get_option('dprice_cc_email') );
    echo '"/></h3>';
    echo '<p><input type="submit" name="submit" value="Update Email" /></p>';
    echo '</form>';
    echo '</div>';	
}

function dprice_plugin_menu()
{
    add_options_page('Digital Prices Settings','Digital Prices', 'manage_options', 'digital-prices-plugin', 'dprice_option_page');
}
add_action('admin_menu', 'dprice_plugin_menu');

function digital_price()
{
    return '<p>Email: ' . esc_attr( get_option('dprice_cc_email') ) . '</p>';
}
add_shortcode('dprice', 'digital_price');