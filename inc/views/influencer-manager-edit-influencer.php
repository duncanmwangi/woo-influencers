<?php

    $influencer_id = isset($_GET['infid'])? (int)$_GET['infid']:0;

    $user = wi_valid_user($influencer_id,'influencer');

if(isset($_POST['wi_save_changes'])){
	$error = true;
	$firstname = sanitize_text_field($_POST['firstname']);
	$lastname = sanitize_text_field($_POST['lastname']);
	$email = sanitize_text_field($_POST['email']);
	$phone = sanitize_text_field($_POST['phone']);
	$platform = sanitize_text_field($_POST['platform']);
	$platform_url_handle = sanitize_text_field($_POST['platform_url_handle']);
	$shipping_address = sanitize_text_field($_POST['shipping_address']);
	$paid_by = sanitize_text_field($_POST['paid_by']);
	$dovetale_link = sanitize_text_field($_POST['dovetale_link']);
	$paypal_email = sanitize_text_field($_POST['paypal_email']);
	$currentpassword = sanitize_text_field($_POST['currentpassword']);
	$password = sanitize_text_field($_POST['password']);

	if(empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($platform) || empty($platform_url_handle) || empty($shipping_address) || empty($paid_by) ||  ($paid_by=='paypal' && empty($paypal_email)) || ($paid_by=='bank_acount' && empty($dovetale_link))  ){
		$message = wi_user_message('All fields must be filled');
    }
    elseif(!is_email($email)){
        $message = wi_user_message('Email address must be a valid email address');
    }
    elseif(!empty($paypal_email) && !is_email($paypal_email)){
        $message = wi_user_message('Paypal Email address must be a valid email address');
    }
    elseif(wi_email_exists($email) && strtolower($email)!=strtolower($user->email) ){
        $message = wi_user_message('Email address already taken');
    }
    elseif(!empty($password) && strlen($password)<6 ){
        $message = wi_user_message('Password must be atleast 5 characters in length');
    }else{
		$error = false;
		$data = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone' => $phone,
            'platform' => $platform,
            'user_role' => 'influencer',
            'platform_url_handle' => $platform_url_handle,
            'shipping_address' => $shipping_address,
            'paid_by' => $paid_by,
            'dovetale_link' => $dovetale_link,
            'paypal_email' => $paypal_email,
            'user_id' => wi_get_current_user_id()
        ];

        if(!empty($password)){
        	$data['password'] = sha1($password);
        }
		$WI_Influencer->db->update($WI_Influencer->users_tbl,$data,['id'=>$user->id]);
		$message = wi_user_message(wi_influencer_display_name().' account has been created successfully', false);
	}

}

$user = wi_valid_user($user->id,'influencer');

if(!$user){
    $message = wi_admin_message('This is not a valid user!');
}


?>

<?=wi_heading('Edit '.wi_influencer_display_name())?>

<?php if(!empty($message)): ?>
<div class="row mx-0 mt-3">
	<div class="col-md-12 mx-0 px-0">
		<?=$message?>
	</div>
</div>
 <?php endif; ?>
<div class="row mx-0"> 
	<div class="col-md-8 mt-3 px-0">
		
		<form action="" method="post">

		  <div class="form-group row">
		    <label for="staticEmailfirstname" class="col-sm-4 col-form-label">First Name</label>
		    <div class="col-sm-8">
		      <input type="text" class="form-control-plaintext" id="firstname" name="firstname" value="<?=($error)?$firstname:$user->firstname?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="lastname" class="col-sm-4 col-form-label">Last Name</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="lastname" name="lastname" value="<?=($error)?$lastname:$user->lastname?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="email" class="col-sm-4 col-form-label">Email Address</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="email" name="email" value="<?=($error)?$email:$user->email?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="phone" class="col-sm-4 col-form-label">Phone Number</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="phone" name="phone" value="<?=($error)?$phone:$user->phone?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="platform" class="col-sm-4 col-form-label">Platform</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="platform" name="platform" value="<?=($error)?$platform:$user->platform?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="platform_url_handle" class="col-sm-4 col-form-label">Platform Handle/URL</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="platform_url_handle" name="platform_url_handle" value="<?=($error)?$platform_url_handle:$user->platform_url_handle?>">
		    </div>
		  </div>

		  <div class="form-group row">
		    <label for="shipping_address" class="col-sm-4 col-form-label">Shipping Address</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="shipping_address" name="shipping_address" value="<?=($error)?$shipping_address:$user->shipping_address?>">
		    </div>
		  </div>

		<div class="form-group row">
		    <label for="paid_by" class="col-sm-12 col-form-label font-weight-bold text-muted">In order to be paid commissions, please enable a payment method below.</label>
		</div>
		  <div class="form-group row">
		    <label for="paid_by" class="col-sm-4 col-form-label">Paid By</label>
		    <div class="col-sm-8">
		      <select name="paid_by" id="wi_paid_by">
		      	<?php 
		      		$pay_methods = ['paypal' => 'PAYPAL','bank_acount'=>'Bank Acct/Debit Card '];
		      		$paid_by = ($error)?$paid_by:$user->paid_by;
		      		foreach ($pay_methods as $key => $value) {
		      			$selected = $paid_by==$key ? ' selected="selected" ':'';
		      			echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
		      		}
		      	?>
		      </select>
		    </div>
		  </div>

		  <div class="form-group row wi_paypal_email">
		    <label for="paid_by" class="col-sm-12 col-form-label text-muted">Enter your paypal email address below</label>
		</div>
		  <div class="form-group row wi_paypal_email">
		    <label for="paypal_email" class="col-sm-4 col-form-label">Paypal Email</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="paypal_email" name="paypal_email" value="<?=($error)?$paypal_email:$user->paypal_email?>">
		    </div>
		  </div>


		
		  
			<div class="form-group row wi_dovetail_link">
			    <label for="paid_by" class="col-sm-12 col-form-label text-muted">To be paid by Bank Account/Debit Card you will need to click the link to our Influencer Program Management Vendor, Dovetale, below and create an account. We use their system integration with Stripe Connect to issue easy and fast payments directly to your bank or debit card.</label>
			</div>

		  <div class="form-group row wi_dovetail_link">
		    <label for="dovetale_link" class="col-sm-4 col-form-label">Dovetale Signup Link</label>
		    <div class="col-sm-8">
		      <input type="text"  class="form-control-plaintext" id="dovetale_link" name="dovetale_link" value="<?=($error)?$dovetale_link:$user->dovetale_link?>">
		    </div>
		  </div>


		  <div class="form-group row">
		    <h5 style="font-weight: bold;" class="ml-3">Fill in below field to change influencer password</h5>
		</div>


		  <div class="form-group row">
		    <label for="password" class="col-sm-4 col-form-label">New Password</label>
		    <div class="col-sm-8">
		      <input type="password" class="form-control-plaintext" id="password" name="password" value="">
		    </div>
		</div>
		  <div class="form-group row">
		  	<div class="col-sm-8">
			  	<button type="submit" class="btn btn-primary" name="wi_save_changes">Update Influencer</button>
			  </div>
		  </div>
		  
		</form>
	</div>

</div>

<script type="text/javascript">
	jQuery(document).ready(function(){

			jQuery('#wi_paid_by').change(function(){
				let paid_by = jQuery('#wi_paid_by').val();
				if(paid_by=='paypal'){
					jQuery('.wi_dovetail_link').hide();
					jQuery('.wi_paypal_email').show();
				}
				if(paid_by=='bank_acount'){
					jQuery('.wi_dovetail_link').show();
					jQuery('.wi_paypal_email').hide();
				}
			});

			let paid_by = jQuery('#wi_paid_by').val();
			if(paid_by=='paypal'){
				jQuery('.wi_dovetail_link').hide();
				jQuery('.wi_paypal_email').show();
			}
			if(paid_by=='bank_acount'){
				jQuery('.wi_dovetail_link').show();
				jQuery('.wi_paypal_email').hide();
			}
});

</script>