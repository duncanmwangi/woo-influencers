<?php
/**
 * @package dim-woocommerce-influencers-by-coupon-code
 */
/*
Plugin Name: Woocommerce Influencers By Coupon Code
Plugin URI: https://akismet.com/
Description: Woocommerce Influencers By Coupon Code
Version: 1.0.0
Author: Duncan I. Mwangi
Author URI: https://gmarkhosting.com/
License: GPLv2 or later
Text Domain: dim-woocommerce-influencers-by-coupon-code
*/

//load database class
require_once('inc/WI_DB.php');

//load helper functions
require_once('inc/wi-helper-functions.php');

//Add shortcode to addd the frontend system
require_once('inc/woo_influencer_frontend_shortcode.php');

//load admin pages
require_once('admin/index.php');

//hook to award commissions on order payment
add_action( 'woocommerce_payment_complete', 'wi_payment_complete' );


//Add shortcode to addd the frontend system 
add_shortcode('woo_influencer_frontend', 'woo_influencer_frontend_shortcode'); 

//force users to login before accessing page
add_shortcode('woo_influencer_frontend_force_login', 'woo_influencer_frontend_force_login_shortcode'); 


add_action( 'woocommerce_coupon_options', 'wi_add_coupon_influencer_description_textarea', 10, 0 );

add_action( 'woocommerce_coupon_options_save', 'wi_save_coupon_influencer_description');


add_action( 'restrict_manage_posts', 'wi_filter_coupons_by_influencers' ); 

add_filter( 'pre_get_posts', 'wi_process_filter_coupons_by_influencers' );

// Force login for protected pages
add_action( 'template_redirect', 'wi_force_login_for_protected_pages');


add_filter('wp_nav_menu_args', 'wi_change_menu_by_user_logged_in', 10, 1);


add_action('init', 'wi_init_action_hook');

