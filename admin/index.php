<?php

//add admin menu

add_action( 'admin_menu', 'wi_add_admin_menu');

function wi_add_admin_menu(){
    add_menu_page( 'Woo Influencers', 'Woo Influencers', 'manage_options', 'woocommerce-influencers', 'wi_influencers_page', 'dashicons-megaphone', 58 ); 
    add_submenu_page('woocommerce-influencers', wi_influencer_display_name(false), wi_influencer_display_name(false), 'manage_options', 'woocommerce-influencers' );
    add_submenu_page('woocommerce-influencers', 'Add '.wi_influencer_display_name(), 'Add '.wi_influencer_display_name(), 'manage_options', 'wi-add-influencer','wi_add_influencer_page' );
    add_submenu_page('woocommerce-influencers', wi_influencer_manager_display_name(false), wi_influencer_manager_display_name(false), 'manage_options', 'wi-managers','wi_influencer_managers_page' );
    add_submenu_page('woocommerce-influencers', 'Add '.wi_influencer_manager_display_name(), 'Add '.wi_influencer_manager_display_name(), 'manage_options', 'wi-add-influencer-manager','wi_add_influencer_manager_page' );
    add_submenu_page('woocommerce-influencers', 'Settings', 'Settings', 'manage_options', 'wi-settings','wi_settings_page' );

}

function wi_influencers_page(){
    $action = isset($_GET['action'])?$_GET['action']:'';
    $id = isset($_GET['id'])?(int)$_GET['id']:0;
    if($id!=0 && $action=='edit' && wi_valid_user($id,'influencer')){
        require_once('influencer-edit.php');
    }
    elseif($id!=0 && $action=='payout' && wi_valid_user($id,'influencer')){
        require_once('payout.php');
    }else{
        require_once('influencers-list.php');
    }
    
}

function wi_add_influencer_page(){
    require_once('influencer-add.php');
}

function wi_influencer_managers_page(){
    $action = isset($_GET['action'])?$_GET['action']:'';
    $id = isset($_GET['id'])?(int)$_GET['id']:0;
    if($id!=0 && $action=='edit' && wi_valid_user($id,'influencer-manager')){
        require_once('influencer-manager-edit.php');
    }elseif($id!=0 && $action=='payout' && wi_valid_user($id,'influencer-manager')){
        require_once('payout.php');
    }else{
        require_once('influencer-managers-list.php');
    }
    
}

function wi_add_influencer_manager_page(){
    require_once('influencer-manager-add.php');
}

function wi_settings_page(){
    require_once('settings.php');
}