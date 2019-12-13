<div class="container-fluid">
    <div class="row">
        <div class="col">
        </div>
        <div class="col-4 p-3 login-div">
            <h4 class="text-center">Password Reset</h4>
            <?php if($message) echo $message; ?>
            <form method="post" action="<?=$WI_Influencer->get_url('reset')?>">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Password">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" name="reset_password_btn">Reset Password</button>
                </div>  
                <div class="text-center mt-3"><a href="<?=$WI_Influencer->get_url('login')?>">Login?</a></div>              
            </form>
        </div>
        <div class="col">
        </div>
    </div>    
</div>