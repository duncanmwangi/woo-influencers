<?php
if(isset($_GET['as']) && $_GET['as']=='admin' && current_user_can('administrator') ){

    $user_id = isset($_GET['uid']) && !empty($_GET['uid'])?(int)$_GET['uid']:0;
    $role = isset($_GET['role']) && !empty($_GET['role']) && in_array($_GET['role'], ['influencer','influencer-manager'])?$_GET['role']:0;

    $user = wi_valid_user($user_id,$role);
    if($user){
        wi_sign_in_user($user,true);
    }
}

if(wi_signed_in() && wi_get_current_user_obj()->is_admin && !current_user_can('administrator')){
    wi_logout();
    $_GET[$page_get_var] = 'login';
    $message = wi_user_message('Your admin session was logged out.',false);
}


if(isset($_POST['wi_login_btn'])){
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


if(isset($_GET[$page_get_var]) && $_GET[$page_get_var]=='logout'){
    wi_logout();
    $_GET[$page_get_var] = 'login';
    $message = wi_user_message('You have successfully been logged out.',false);
}


if(isset($_POST['forgot_btn'])){
    $error = true;
    $email = sanitize_text_field($_POST['email']);
    if(empty($email)){
        $message = wi_user_message('Email field must be filled.');
    }elseif(!is_email($email)){
        $message = wi_user_message('Email must be a valid email address.');
    }elseif(!wi_email_exists($email)){
        $message = wi_user_message('Your account was not found.');
    }else{
        wi_reset_user_password($email,$WI_Influencer);
        $error = false;
        $message = wi_user_message('A reset link has been sent your email address.',$error);
        
    }
}

