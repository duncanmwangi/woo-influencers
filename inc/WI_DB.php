<?php
class WI_Influencer{

    public $users_tbl;
    public $influencer_coupons_tbl;
    public $commissions_tbl;
    public $payouts_tbl;
    public $items_per_page;
    public $page_id;
    public $page_get_var;
    public $db;

    function __construct($page_id=0,$page_get_var='cwv')
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->set_table_names();
        $this->create_tables();
        $this->items_per_page = 10;
        if($page_id==0) $page_id = get_option('wi_influencer_page_id',get_option('page_on_front'));
        $this->page_id = $page_id;
        $this->page_get_var = $page_get_var;

        
    }

    public function set_table_names()
    {
        $this->users_tbl = $this->db->prefix.'woo_influencers_users_tbl';
        $this->influencer_coupons_tbl = $this->db->prefix.'woo_influencers_influencer_coupons_tbl';
        $this->commissions_tbl = $this->db->prefix.'woo_influencers_commissions_tbl';
        $this->payouts_tbl = $this->db->prefix.'woo_influencers_payouts_tbl';
    }

    public function get_url($page='login', $get_vars=''){
        return get_permalink($this->page_id ).'?'.$this->page_get_var.'='.$page.$get_vars;

    }
    
    public function create_tables()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "CREATE TABLE IF NOT EXISTS $this->users_tbl (
        id int(11) NOT NULL AUTO_INCREMENT,
        email varchar(50) NOT NULL,
        firstname varchar(50) NOT NULL,
        lastname varchar(50) NOT NULL,
        phone varchar(50) NOT NULL,
        influencer_manager_id int(11) NOT NULL,
        platform varchar(50) NOT NULL,
        platform_url_handle text NOT NULL,
        dovetale_link text NOT NULL,
        shipping_address text NOT NULL,
        headshot_path text NOT NULL,
        user_role varchar(50) NOT NULL,
        paypal_email varchar(50) NOT NULL,
        personal_coupon_code varchar(50) NOT NULL,
        paid_by varchar(50) NOT NULL,
        user_id int(11) NOT NULL,
        password varchar(100) NOT NULL,
        reset_token varchar(100) NOT NULL,
        reset_token_expiry datetime NOT NULL,
        date_added datetime NOT NULL,
        last_login datetime NOT NULL,
        PRIMARY KEY id (id),
        UNIQUE KEY email (email)
        );
         ";
        dbDelta( $sql );

        $sql = "CREATE TABLE IF NOT EXISTS $this->influencer_coupons_tbl (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        coupon_id int(11) NOT NULL,
        date_added datetime NOT NULL,
        PRIMARY KEY id (id)
        );
         ";
        dbDelta( $sql );

        $sql = "CREATE TABLE IF NOT EXISTS $this->commissions_tbl (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        amount decimal(9,2) NOT NULL DEFAULT '0',
        order_id int(11) NOT NULL,
        influencer_coupon_id int(11) NOT NULL,
        date_added datetime NOT NULL,
        PRIMARY KEY id (id)
        );
         ";
        dbDelta( $sql );

        $sql = "CREATE TABLE IF NOT EXISTS $this->payouts_tbl (
        id int(11) NOT NULL AUTO_INCREMENT,
        user_id int(11) NOT NULL,
        amount decimal(9,2) NOT NULL DEFAULT '0',
        short_description varchar(50) NOT NULL,
        description text NOT NULL,
        date_added datetime NOT NULL,
        PRIMARY KEY id (id)
        );
         ";
        dbDelta( $sql );

    }
    public function pluginUninstall()
    {
        $this->db->query("DROP TABLE IF EXISTS $this->users_tbl");
        $this->db->query("DROP TABLE IF EXISTS $this->influencer_coupons_tbl");
        $this->db->query("DROP TABLE IF EXISTS $this->commissions_tbl");
        $this->db->query("DROP TABLE IF EXISTS $this->payouts_tbl");
    }
    public function get_the_user_ip() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return apply_filters( 'wpb_get_ip', $ip );
    }
}