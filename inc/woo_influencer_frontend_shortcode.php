<?php
function woo_influencer_frontend_shortcode(){
    global $post;
    $current_page_id = $post->ID;
    
    $WI_Influencer = new WI_Influencer($current_page_id);

    $page_get_var = $WI_Influencer->page_get_var;

    $auth_pages = ['login','forgot','reset','logout'];

    require_once('auth-processes.php');

    $influencer_pages = ['dashboard', 'coupons', 'coupon-orders', 'orders', 'ledger','profile'];
    $influencer_manager_pages = ['my-dashboard', 'my-influencers', 'my-coupons', 'my-orders', 'my-ledger','my-profile','my-influencer-coupons','my-influencer-orders','my-influencer-coupon-orders','add-influencer','edit-influencer','influencer-ledger'];

    if(!isset($_GET[$page_get_var]) || empty($_GET[$page_get_var])){
        $_GET[$page_get_var] = 'login';
        if(wi_is_influencer()){
            $_GET[$page_get_var] = 'coupons';
        }
        if(wi_is_influencer_manager()){
            $_GET[$page_get_var] = 'my-influencers';
        }
    }
    $page = isset($_GET[$page_get_var]) && in_array($_GET[$page_get_var],array_merge($influencer_manager_pages, $influencer_pages,$auth_pages)) ? $_GET[$page_get_var]:'unknown_page';

    if($page!=='unknown_page' && !wi_signed_in() && !in_array($page,$auth_pages)){
        $login_message = 'Login required to access resource';
        $_SESSION['WI_REDIRECT_PATH'] = $page;
        $page = 'login';
    }
    elseif($page!=='unknown_page' && wi_is_influencer() && !in_array($page,array_merge($auth_pages,$influencer_pages))){
        $error_message = 'Sorry, No privileges to access resource';
        $page = '403';
    }
    elseif($page!=='unknown_page' && wi_is_influencer_manager() && !in_array($page,array_merge($auth_pages,$influencer_manager_pages))){
        $error_message = 'Sorry, No privileges to access resource';
        $page = '403';
    }

    $WI_Influencer->current_page = $page;

    ob_start();
    
    echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';

    echo '<link rel="stylesheet" href="'.plugins_url('style.css',__FILE__ ).'">';


    switch($page)
    {
        case 'login': require_once('views/login.php'); break;
        case 'forgot': require_once('views/forgot.php'); break;
        case 'reset': require_once('views/reset.php'); break;


        case 'dashboard': wi_load_view('influencer-dashboard.php',$WI_Influencer); break;
        case 'orders': wi_load_view('influencer-orders.php',$WI_Influencer); break;
        case 'coupons': wi_load_view('influencer-coupons.php',$WI_Influencer); break;
        case 'coupon-orders': wi_load_view('influencer-coupon-orders.php',$WI_Influencer); break;
        case 'ledger': wi_load_view('influencer-ledger.php',$WI_Influencer); break;
        case 'profile': wi_load_view('influencer-profile.php',$WI_Influencer); break;



        case 'my-dashboard': wi_load_view('influencer-manager-dashboard.php',$WI_Influencer); break;
        case 'my-influencers': wi_load_view('influencer-manager-influencers.php',$WI_Influencer); break;
        case 'my-orders': wi_load_view('influencer-manager-orders.php',$WI_Influencer); break;
        case 'my-coupons': wi_load_view('influencer-manager-coupons.php',$WI_Influencer); break;
        case 'my-ledger': wi_load_view('influencer-manager-ledger.php',$WI_Influencer); break;
        case 'my-profile': wi_load_view('influencer-manager-profile.php',$WI_Influencer); break;
        case 'my-influencer-coupons': wi_load_view('influencer-manager-influencer-coupons.php',$WI_Influencer); break;
        case 'my-influencer-orders': wi_load_view('influencer-manager-influencer-orders.php',$WI_Influencer); break;
        case 'my-influencer-coupon-orders': wi_load_view('influencer-manager-influencer-coupon-orders.php',$WI_Influencer); break;
        case 'add-influencer': wi_load_view('influencer-manager-add-influencer.php',$WI_Influencer); break;
        case 'edit-influencer': wi_load_view('influencer-manager-edit-influencer.php',$WI_Influencer); break;
        case 'influencer-ledger': wi_load_view('influencer-manager-influencer-ledger.php',$WI_Influencer); break;


        case '403': require_once('views/403.php'); break;
        case 'unknown_page': require_once('views/404.php'); break;

    }


    $output = ob_get_contents();

    ob_end_clean();

    return $output;

}