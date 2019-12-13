<?php

//style admin messages for error and success messages
function wi_admin_message($message='', $error = true){
    $css_class = $error?'warning':'success';
    return '<div class="notice is-dismissible notice-'.$css_class.'">
                <p><strong>'.$message.'</strong></p>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            </div>';
}

//style admin messages for error and success messages
function wi_user_message($message='', $error = true){
    $css_class = $error?'danger':'success';
    return '<div class="alert alert-'.$css_class.'" role="alert">'.$message.'</div>';
}



function wi_email_exists($email=''){
    $WI_Influencer = new WI_Influencer();
    $user_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $user_tbl WHERE email LIKE '$email' LIMIT 0,1");
}

function wi_influencer_managers_array(){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_results(
        "SELECT * FROM $users_tbl WHERE user_role LIKE 'influencer-manager' ORDER BY firstname ASC"
    );
}

function wi_influencer_manager_influencers_array($influencer_manager_id=0){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_results(
        "SELECT * FROM $users_tbl WHERE user_id = $influencer_manager_id AND user_role LIKE 'influencer' ORDER BY firstname ASC"
    );
}

function wi_all_influencers_array(){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_results(
        "SELECT * FROM $users_tbl WHERE user_role LIKE 'influencer' ORDER BY firstname ASC"
    );
}

function wi_get_order_by_get_vars($current_order_by = '',$order_by='',$current_order = ''){
    return '&orderby='.$order_by.'&order='.wi_get_order_type($current_order_by,$order_by,$current_order);
}

function wi_get_order_type($current_order_by = '',$order_by='',$current_order = ''){
    return ($order_by==$current_order_by && $current_order=='asc')?'desc':'asc';
}

function wi_get_inverse_order_type($current_order_by = '',$order_by='',$current_order = ''){
    return wi_get_order_type($current_order_by,$order_by,$current_order)=='asc'?'desc':'asc';
}


function wi_payment_complete($order_id){

    $order = wc_get_order( $order_id );
    $coupons = $order->get_used_coupons();
    if(count($coupons) && !wi_has_commission_been_awarded_on_order($order_id )){
        foreach($coupons as $coupon_code){
            $coupon_id =  wc_get_coupon_id_by_code($coupon_code);
            $data = wi_is_influencer_coupon($coupon_id);
            if($data){
                wi_award_influencer_commission($data,$order);
            }
        }
    }
}




function wi_is_influencer_coupon($coupon_id){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    $influencer_coupons_tbl = $WI_Influencer->influencer_coupons_tbl;
    return $WI_Influencer->db->get_row("SELECT i.id as influencer_coupon_id, u.id as influencer_id, u.user_id as influencer_manager_id FROM $influencer_coupons_tbl as i
    LEFT JOIN $users_tbl as u ON u.id = i.user_id
     WHERE coupon_id = $coupon_id LIMIT 0,1");

}

function wi_award_influencer_commission($influencer,$order){

    $WI_Influencer = new WI_Influencer();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $order_id = $order->get_id();
    $order_amount = $order->get_total();

    $users = [];
    $users[] = (object)['id'=>$influencer->influencer_id, 'role'=>'influencer'];
    $users[] = (object)['id'=>$influencer->influencer_manager_id, 'role'=>'influencer-manager'];

    foreach ($users as $user) {
        if(!wi_user_has_commission_been_awarded_on_this_order($order_id,$user->id)){
            $commission=0;
            $commission_percentage = $user->role=='influencer'?
            get_option('wi_influencer_commission_percent'): get_option('wi_influencer_manager_commission_percent');
            if($commission_percentage<=0 || $commission_percentage>=100) $commission=0;
            else $commission =  number_format($order_amount*($commission_percentage/100),2);
            wi_award_user_commission($user->id,$order_id,$commission,$influencer->influencer_coupon_id);
        }
    }
    

}


function wi_award_user_commission($user_id,$order_id,$commission,$influencer_coupon_id){
    $WI_Influencer = new WI_Influencer();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    return $WI_Influencer->db->insert($commissions_tbl,['user_id'=> $user_id,'order_id'=>$order_id,'amount'=>$commission,'influencer_coupon_id'=>$influencer_coupon_id,'date_added'=>current_time('mysql')]);
}

function wi_has_commission_been_awarded_on_order($order_id=0){
    $WI_Influencer = new WI_Influencer();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $commissions_tbl WHERE order_id = $order_id LIMIT 0,1");
}

function wi_user_has_commission_been_awarded_on_this_order($order_id=0,$user_id=0){
    $WI_Influencer = new WI_Influencer();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $commissions_tbl WHERE order_id = $order_id AND user_id = $user_id LIMIT 0,1");
}

function wi_signed_in(){
    return isset($_SESSION['WI_USER']) && wi_valid_user($_SESSION['WI_USER']->id,$_SESSION['WI_USER']->role);
}

function wi_logout(){
    unset($_SESSION['WI_USER']);
}
function wi_get_current_user_id(){
    return $_SESSION['WI_USER']->id;
}
function wi_get_current_user_obj(){
    return $_SESSION['WI_USER'];
}
function wi_valid_user($user_id=0,$role=''){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $users_tbl WHERE id = $user_id AND user_role LIKE '$role' LIMIT 0,1");
}

function wi_log_user_login(){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->update($WI_Influencer->users_tbl, ['last_login'=>current_time('mysql')],['id'=>wi_get_current_user_id()]);
}
function wi_validate_login($email='',$password=''){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $users_tbl WHERE email LIKE '$email' AND password = SHA1('$password') LIMIT 0,1");
}
function wi_validate_login_with_id($id=0,$password=''){
    $WI_Influencer = new WI_Influencer();
    $users_tbl = $WI_Influencer->users_tbl;
    return $WI_Influencer->db->get_row("SELECT * FROM $users_tbl WHERE id = $id AND password = SHA1('$password') LIMIT 0,1");
}

function wi_sign_in_user($user,$as_admin=false){
    if(wi_valid_user($user->id,$user->user_role)){

        $_SESSION['WI_USER'] = (object) ['id'=>$user->id, 'role'=>$user->user_role,'is_admin'=>$as_admin];
    }
    return $_SESSION['WI_USER'];        
}

function wi_get_current_ledger_balance($user_id = 0){
    $WI_Influencer = new WI_Influencer();
    $payouts_tbl = $WI_Influencer->payouts_tbl;
    $commissions_tbl = $WI_Influencer->commissions_tbl;

    $payouts_sql = "SELECT user_id, (0-amount)as amount FROM $payouts_tbl";
    $commissions_sql = "SELECT user_id, amount FROM $commissions_tbl";
    $sql = "SELECT SUM(amount) as balance FROM (($payouts_sql) UNION ALL ($commissions_sql)) as ledger WHERE user_id = $user_id GROUP BY user_id";
    $results = $WI_Influencer->db->get_row($sql);
    if($results) return number_format($results->balance,2);
    return number_format(0,2);
}

function wi_is_influencer_manager(){
    return wi_signed_in() && $_SESSION['WI_USER']->role=='influencer-manager';
}

function wi_is_influencer(){
    return wi_signed_in() && $_SESSION['WI_USER']->role=='influencer';
}


function wi_reset_user_password($email='',$WI_Influencer){
    $token = wp_generate_password(80,false);
    $WI_Influencer->db->update($WI_Influencer->users_tbl,['reset_token'=>$token,'reset_token_expiry'=>current_time('mysql')],['email'=>$email]);
    wi_send_password_reset_email($email,$token,$WI_Influencer);

}


function wi_send_password_reset_email($email,$token,$WI_Influencer){
    $password_reset_url = $WI_Influencer->get_url('reset','&rtn='.$token);
    $subject = 'Password Reset ['.get_bloginfo('name').']';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>';
    ob_start();
    require_once('views/password-reset-email.php');
    $body = ob_get_contents();
    ob_end_clean();
    
    wp_mail( $email, $subject, $body, $headers );
}


function wi_load_view($view='', $WI_Influencer){
    require_once('views/top-wrapper.php');

    if(wi_is_influencer_manager()){
        require_once('views/influencer-manager-menu.php');
    }elseif(wi_is_influencer()){
        require_once('views/influencer-menu.php');
    }

    require_once('views/'.$view);

    if(wi_is_influencer()){
        require_once('views/influencer-footer.php');
    }

    require_once('views/bottom-wrapper.php');

}


function wi_coupon_code_from_id($coupon_id=0){
    $coupon = new WC_Coupon($coupon_id);
    if(!$coupon) return '';
    return $coupon->get_code();
}

function wi_date($mysql_date=''){

    if($mysql_date=='') $mysql_date = current_time('mysql');

    return date('m/d/Y', strtotime($mysql_date));
}

function wi_create_influencer_coupon_code($current_user_id,$coupon_code,$description){
    $amount = get_option('wi_coupon_discount_percent',10); // Amount
    $discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product
                        
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => $description,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type'     => 'shop_coupon'
    );
                        
    $new_coupon_id = wp_insert_post( $coupon );
                        
    // Add meta
    update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
    update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
    update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', '' );
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
    update_post_meta( $new_coupon_id, 'wi_type', 'influencer_coupon' );
    update_post_meta( $new_coupon_id, 'wi_influencer_id', $current_user_id );
    update_post_meta( $new_coupon_id, 'wi_coupon_description', $description );

    $WI_Influencer = new WI_Influencer();

    $WI_Influencer->db->insert($WI_Influencer->influencer_coupons_tbl,['user_id'=>$current_user_id,'coupon_id'=>$new_coupon_id, 'date_added'=>current_time('mysql')]);


    return new WC_Coupon($new_coupon_id);

}

function wi_create_influencer_personal_coupon($user_id)
{
    $user = wi_valid_user($user_id,'influencer');

    $coupon_code = wi_get_influencer_personal_coupon_code($user);

    $amount = get_option('wi_influencer_personal_coupon_discount_percent',30); // Amount

    $discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product, percent_product

    $description = "Being personal influencer coupon for $user->firstname $user->lastname";
                        
    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => $description,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type'     => 'shop_coupon'
    );
                        
    $new_coupon_id = wp_insert_post( $coupon );
                        
    // Add meta
    update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
    update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
    update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
    update_post_meta( $new_coupon_id, 'customer_email', $user->email );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', '' );
    update_post_meta( $new_coupon_id, 'expiry_date', '' );
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
    update_post_meta( $new_coupon_id, 'wi_type', 'influencer_personal_coupon' );
    update_post_meta( $new_coupon_id, 'wi_influencer_id', $user_id );
    update_post_meta( $new_coupon_id, 'wi_coupon_description', $description );

    $WI_Influencer = new WI_Influencer();

    $WI_Influencer->db->update($WI_Influencer->users_tbl,['personal_coupon_code'=>$coupon_code],['id'=>$user_id]);


    return $coupon_code;
}



function wi_get_influencer_personal_coupon_code($user)
{
    $coupon_code = strtoupper($user->firstname.'-'.wp_generate_password(4,false));

    if(wi_coupon_exists($coupon_code)) return wi_get_influencer_personal_coupon_code($user);

    return $coupon_code;
}



function wi_coupon_exists($coupon_code=''){
    global $wpdb;
    $return = $wpdb->get_row( "SELECT ID FROM wp_posts WHERE post_title = '" . $coupon_code . "' && post_type = 'shop_coupon' ", 'ARRAY_N' );
    if( empty( $return ) ) {
        return false;
    } else {
        return true;
    }
}

function wi_tran_id($record)
{
    if($record->type=='COMMISSION') return 'COMM-1000'.$record->tran_id;
    if($record->type=='PAYOUT') return 'POUT-2000'.$record->tran_id;
}

function wi_ledger_amount($record,$column = 'IN')
{
    if($record->type=='COMMISSION' && $column == 'IN') return wi_money_prefix().number_format($record->amount,2);
    elseif($record->type=='PAYOUT' && $column == 'OUT') return wi_money_prefix().number_format($record->amount,2);
    else return '';
}

function wi_money_prefix(){
    return 'US$ ';
}

function wi_heading($heading='')
{
    return '<div class="row mt-3 mx-0 head-wrapper">
                <h3 class="wi_heading">'.$heading.'</h3>
            </div>';
}

function wi_influencer_display_name($singular=true){
    if(!$singular) return 'Brand Influencers';
    return 'Brand Influencer';
}

function wi_influencer_manager_display_name($singular=true){
    if(!$singular) return 'Brand Managers';
    return 'Brand Manager';
}

function wi_add_coupon_influencer_description_textarea() { 
    global $post, $woocommerce;
    $is_influencer_coupon = get_post_meta($post->ID, 'wi_type',true) == 'influencer_coupon'?true:false;
    if($is_influencer_coupon){
        woocommerce_wp_textarea_input(array('id' => 'wi_coupon_description', 'label' => __('Influencer Description', 'woocommerce'), 'placeholder' => '', 'value' => get_post_meta($post->ID, 'wi_coupon_description',true),'desc_tip' => 'true', 'description' => __('This description was entered while creating an influencer coupon', 'woocommerce')));
    }
    
    
}

function wi_save_coupon_influencer_description( $post_id ) {
    $is_influencer_coupon = get_post_meta($post_id, 'wi_type',true) == 'influencer_coupon'?true:false;
    if($is_influencer_coupon){
        $wi_coupon_description = sanitize_text_field( $_POST['wi_coupon_description'] );
        update_post_meta( $post_id, 'wi_coupon_description', $wi_coupon_description );
    }
    
}

function woo_influencer_frontend_force_login_shortcode()
{
    ob_start();

    if(!wi_signed_in()){
        $WI_Influencer = new WI_Influencer();
        $url = $WI_Influencer->get_url('login');
        echo '<script>window.location.replace("'.$url.'");</script>';
    }

    $output = ob_get_contents();

    ob_end_clean();

    return $output;
}



function wi_filter_coupons_by_influencers(){
    global $typenow;  
    if( $typenow == 'shop_coupon' ){
        ?>
        <select name="wi_coupon_type" id="wi_coupon_type">
            <option value="1" <?php if(isset($_GET['wi_coupon_type']) && $_GET['wi_coupon_type']=='1'): ?> selected="selected" <?php endif;?>>All Coupons</option>
            <option value="2" <?php if(isset($_GET['wi_coupon_type']) && $_GET['wi_coupon_type']=='2'): ?> selected="selected" <?php endif;?>>Only Influencer Coupons</option>
            <option value="3" <?php if(isset($_GET['wi_coupon_type']) && $_GET['wi_coupon_type']=='3'): ?> selected="selected" <?php endif;?>>Exclude Influencer Coupons</option>
        </select>

        <select name="wi_influencer_id" id="wi_influencer_id">
            <option value="">All Influencers</option>
            <?php 
                $influencers = wi_all_influencers_array();
                if($influencers){
                    foreach ($influencers as $influencer) {
                        $selected = isset($_GET['wi_influencer_id']) && in_array($_GET['wi_coupon_type'], [2,4]) && $_GET['wi_influencer_id']==$influencer->id?'selected="selected"':'';
                        echo '<option value="'.$influencer->id.'" '.$selected.'>'.$influencer->firstname.' '.$influencer->lastname.'</option>';
                    }
                }
            ?>
        </select>

        <script>
        jQuery(document).ready(function(){
            let wi_coupon_type = jQuery('#wi_coupon_type').val();
            let wi_influencer_id = jQuery('#wi_influencer_id').val();
            if(wi_coupon_type!=2 && wi_coupon_type!=4){
                jQuery('#wi_influencer_id').hide();
            }else{
                jQuery('#wi_influencer_id').show();
            }

            jQuery('#wi_coupon_type').change(function(){
                wi_coupon_type = jQuery('#wi_coupon_type').val();
                if(wi_coupon_type!=2 && wi_coupon_type!=4){
                    jQuery('#wi_influencer_id').hide();
                }else{
                    jQuery('#wi_influencer_id').show();
                }
            });
        })
        </script>
        <?php

    }
}

function wi_process_filter_coupons_by_influencers( $query ) {
    
if (isset($_GET['wi_coupon_type']) && !empty($_GET['wi_coupon_type']) && $query->query_vars['post_type']=='shop_coupon') {
    $wi_coupon_type = (int)$_GET['wi_coupon_type'];
    $wi_influencer_id = isset($_GET['wi_influencer_id']) && !empty($_GET['wi_influencer_id'])? (int)$_GET['wi_influencer_id']:0;

    if($wi_coupon_type==1){

    }elseif($wi_coupon_type==3){
        $query->set( 'meta_query', 
              [
                [
                  'key' => 'wi_type',
                  'compare' => 'NOT EXISTS',
                ]
              ]
    );

    }elseif($wi_coupon_type==2){
        if($wi_influencer_id==0){
            $query->set( 'meta_query', [ [ 'key' => 'wi_type', 'value' => 'influencer_coupon', 'compare' => '=' ] ] );

        }else{
            $query->set( 'meta_query', 
              [     'relation' => 'AND',
                [
                  'key' => 'wi_type',
                  'value' => 'influencer_coupon',
                  'compare' => '='
                ],
                [
                  'key' => 'wi_influencer_id',
                  'value' => $wi_influencer_id,
                  'compare' => '='
                ]
              ]
            );

        }
    }elseif($wi_coupon_type==4){
        if($wi_influencer_id==0){
            $query->set( 'meta_query', [ [ 'key' => 'wi_type', 'value' => 'influencer_personal_coupon', 'compare' => '=' ] ] );

        }else{
            $query->set( 'meta_query', 
              [     'relation' => 'AND',
                [
                  'key' => 'wi_type',
                  'value' => 'influencer_personal_coupon',
                  'compare' => '='
                ],
                [
                  'key' => 'wi_influencer_id',
                  'value' => $wi_influencer_id,
                  'compare' => '='
                ]
              ]
            );

        }
    }

      
  }
  return $query;
}

function wi_force_login_for_protected_pages()
{
    if(is_page()){
        global $wp_query;
        $page_id = $wp_query->post->ID;
        $wi_influencer_protected_pages = explode(',', get_option('wi_influencer_protected_page_ids',false));
        $wi_influencer_manager_protected_pages = explode(',', get_option('wi_influencer_manager_protected_page_ids',false));
        if(in_array($page_id, $wi_influencer_protected_pages) && !wi_is_influencer()){
            $WI_Influencer = new WI_Influencer();
            wp_redirect($WI_Influencer->get_url('login','&ntl=1') ); die;
        }
        if(in_array($page_id, $wi_influencer_manager_protected_pages) && !wi_is_influencer_manager()){
            $WI_Influencer = new WI_Influencer();
            wp_redirect($WI_Influencer->get_url('login','&ntl=2') ); die;
        }
    }
}

function wi_pagination($sql,$page = 1, $url="")
{
    $adjacents = 3;

$WI_Influencer = new WI_Influencer();
$results = $WI_Influencer->db->get_results($sql);
$items = $results ? count($results) : 0;
$total_pages = ceil($items/$WI_Influencer->items_per_page);

$previous_page = ($page > 1) ? $page-1:1;
$next_page = ($page < $total_pages) ? $page+1:$total_pages;

$start = 1;
$end   = $total_pages;
if($total_pages <= (1+($adjacents * 2))) {
        $start = 1;
        $end   = $total_pages;
      } else {
        if(($page - $adjacents) > 1) { 
          if(($page + $adjacents) < $total_pages) { 
            $start = ($page - $adjacents);            
            $end   = ($page + $adjacents);         
          } else {             
            $start = ($total_pages - (1+($adjacents*2)));  
            $end   = $total_pages;               
          }
        } else {               
          $start = 1;                                
          $end   = (1+($adjacents * 2));             
        }
      }

 ob_start();
 if($total_pages > 1) {
    ?>
<nav aria-label="...">
  <ul class="pagination ml-0">
    <li class="page-item <?=($page <= 1)?'disabled':''?>">
      <a class="page-link" href="<?=$url.'&cpg='.$previous_page?>" tabindex="-1">Previous</a>
    </li>
    <?php for($i=$start; $i<=$end; $i++) { ?>
    <li class="page-item <?=($page == $i)?'active':''?>">
      <a class="page-link" href="<?=$url.'&cpg='.$i?>"><?=$i?></a>
    </li>
    <?php } ?>
    <li class="page-item <?=($page >= $total_pages)?'disabled':''?>">
      <a class="page-link" href="<?=$url.'&cpg='.$next_page?>">Next</a>
    </li>
  </ul>
</nav>
    <?php
}
    $output = ob_get_contents();

    ob_end_clean();

    //if(!current_user_can('administrator')) return '';

    return $output;
    
}


function wi_change_menu_by_user_logged_in($args){

    $wi_influencer_manager_menu_id = get_option('wi_influencer_manager_menu_id','');

    $wi_influencer_menu_id = get_option('wi_influencer_menu_id','');

    if(in_array($args['menu'], [$wi_influencer_manager_menu_id,$wi_influencer_menu_id])){

        if(wi_is_influencer() && !empty($wi_influencer_menu_id)){
            $args['menu'] = $wi_influencer_menu_id;
        }

        elseif(wi_is_influencer_manager() && !empty($wi_influencer_manager_menu_id)){
            $args['menu'] = $wi_influencer_manager_menu_id;
        }
        else{
            return false;
        }
    }
    

    return $args;

}



function wi_init_action_hook(){

    $WI_Influencer = new WI_Influencer();

    if(isset($_POST['wi_login_btn'])){

        $WI_Influencer = new WI_Influencer();

        $error = true;
        $email = sanitize_text_field($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        if(empty($email)){
            $message = wi_user_message('Email field must be filled.');
        }elseif(!is_email($email)){
            $message = wi_user_message('Email must be a valid email address.');
        }elseif(empty($password)){
            $message = wi_user_message('Password field must be filled.');
        }elseif(strlen($email)<6){
            $message = wi_user_message('Password must be more than 6 characters.');
        }else{
            $user = wi_validate_login($email,$password);
            if($user){
                wi_sign_in_user($user);
                wi_log_user_login();
                $url = wi_is_influencer_manager()?$WI_Influencer->get_url('my-influencers'):$WI_Influencer->get_url('coupons');
                $error = false;
                $message = wi_user_message('Login successful: We are logging you in a moment. <script>window.location.replace("'.$url.'");</script>',$error);
            }else{
                $message = wi_user_message('Email OR/AND Password are incorrect.');
            }
            
        }
    }


    if(isset($_GET['as']) && $_GET['as']=='admin' && current_user_can('administrator') ){

        $user_id = isset($_GET['uid']) && !empty($_GET['uid'])?(int)$_GET['uid']:0;
        $role = isset($_GET['role']) && !empty($_GET['role']) && in_array($_GET['role'], ['influencer','influencer-manager'])?$_GET['role']:0;

        $user = wi_valid_user($user_id,$role);
        if($user){
            wi_sign_in_user($user,true);
        }
    }




    if(isset($_GET[$WI_Influencer->page_get_var]) && $_GET[$WI_Influencer->page_get_var]=='logout'){
        wi_logout();
        $_GET[$WI_Influencer->page_get_var] = 'login';
        $message = wi_user_message('You have successfully been logged out.',false);
    }


}