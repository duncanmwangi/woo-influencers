<?php 
    if(isset($_GET['ntl']) && $_GET['ntl']==1){
        $message = wi_user_message('You need to login as a '.wi_influencer_display_name().' to access the page.');
    }
    if(isset($_GET['ntl']) && $_GET['ntl']==2){
        $message = wi_user_message('You need to login as a '.wi_influencer_manager_display_name().' to access the page.');
    }
?>
<div class="container-fluid  wi-main-wrapper">
    <div class="row">
        <div class="col">
        </div>
        <div class="col-4 p-3 login-div">
            <h4 class="text-center">Login</h4>
            <?php if(!empty($message)) echo $message; ?>
            <form method="post" action="<?=$WI_Influencer->get_url('login')?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="wi_login_btn">Login</button>
                </div>  
                <div class="text-center mt-3"><a href="<?=$WI_Influencer->get_url('forgot')?>">Forgot password?</a></div>              
            </form>
        </div>
        <div class="col">
        </div>
    </div>    
</div>