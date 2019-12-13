<div class="container-fluid">
    <div class="row">
        <div class="col">
        </div>
        <div class="col-4 p-3 login-div">
            <h4 class="text-center">Forgot Password</h4>
            <?php if(!empty($message)) echo $message; ?>
            <form method="post" action="<?=$WI_Influencer->get_url('forgot')?>">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="forgot_btn">Reset Password</button>
                </div>  
                <div class="text-center mt-3"><a href="<?=$WI_Influencer->get_url('login')?>">Login?</a></div>              
            </form>
        </div>
        <div class="col">
        </div>
    </div>    
</div>