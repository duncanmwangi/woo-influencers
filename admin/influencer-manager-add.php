<?php
$WI_Influencer = new WI_Influencer();

if(isset($_POST['wi_add_manager_submit'])){
    $error = true;
    $firstname = sanitize_text_field($_POST['firstname']);
    $lastname = sanitize_text_field($_POST['lastname']);
    $email = sanitize_text_field($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $paid_by = sanitize_text_field($_POST['paid_by']);
    $paypal_email = sanitize_text_field($_POST['paypal_email']);
    $dovetale_link = sanitize_text_field($_POST['dovetale_link']);
    $password = sanitize_text_field($_POST['password']);
    $headshot = $_FILES['headshot'];
    $checkImage = getimagesize($headshot["tmp_name"]);

    $upload_overrides = array( 'test_form' => false );



    if(empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($paid_by) ||  ($paid_by=='paypal' && empty($paypal_email)) || ($paid_by=='bank_acount' && empty($dovetale_link))  || empty($password)){
        $message = wi_admin_message('All fields must be filled');
    }
    elseif(!is_email($email)){
        $message = wi_admin_message('Email address must be a valid email address');
    }
    elseif(!empty($paypal_email) && !is_email($paypal_email)){
        $message = wi_admin_message('Paypal Email address must be a valid email address');
    }
    elseif(wi_email_exists($email)){
        $message = wi_admin_message('Email address already taken');
    }
    elseif($checkImage === false) {

        $message = wi_admin_message('Headshot should be an image');
    }
    else{
        $movefile = wp_handle_upload( $headshot, $upload_overrides );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            $headshot_path = substr($movefile['url'],strrpos($movefile['url'], '/wp-content/uploads/'));
            $result = $WI_Influencer->db->insert($WI_Influencer->users_tbl,[
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'user_role' => 'influencer-manager',
                'headshot_path' => $headshot_path,
                'paypal_email' => $paypal_email,
                'dovetale_link' => $dovetale_link,
                'password' => sha1($password),
                'date_added'=> current_time('mysql')
            ]);
            $error = false;
            $message = wi_admin_message(wi_influencer_manager_display_name().' has been saved successfully', $error);
        } else {
            $message = wi_admin_message('Headshot should be an image');
        }


        
    }
}
?>
<div class="wrap">
    <h1>Add <?=wi_influencer_manager_display_name()?></h1>
    <?php if(!empty($message)) echo $message?>
    <form method="post" action="<?=admin_url('admin.php?page=wi-add-influencer-manager')?>" enctype="multipart/form-data" novalidate="novalidate">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="firstname">First Name</label></th>
                    <td><input name="firstname" type="text" id="firstname" value="<?=($error)?$firstname:''?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="lastname">Last Name</label></th>
                    <td><input name="lastname" type="text" id="lastname" value="<?=($error)?$lastname:''?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">Email Address</label></th>
                    <td><input name="email" type="email" id="email" value="<?=($error)?$email:''?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone">Phone Number</label></th>
                    <td><input name="phone" type="text" id="phone" value="<?=($error)?$phone:''?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="headshot">Image</label></th>
                    <td><input name="headshot" type="file" id="headshot" class="regular-text" required></td>
                </tr>



                
                <tr>
                    <th scope="row"><label for="paid_by">Paid By</label></th>
                    <td>
                        <select name="paid_by" id="wi_paid_by">
                            <?php 
                                $pay_methods = ['paypal' => 'PAYPAL','bank_acount'=>'Bank Acct/Debit Card '];
                                $paid_by = ($error)? $paid_by: '';
                                foreach ($pay_methods as $key => $value) {
                                    $selected = $paid_by==$key ? ' selected="selected" ':'';
                                    echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                                }
                            ?>
                          </select>

                    </td>
                </tr>

                
                <tr class="wi_paypal_email">
                    <th scope="row"><label for="paypal_email">Paypal Email Address</label></th>
                    <td><input name="paypal_email" type="text" id="paypal_email" value="<?=($error)?$paypal_email:'';?>" class="regular-text"></td>
                </tr>

                
                <tr class="wi_dovetail_link">
                    <th scope="row"><label for="dovetale_link">Dovetale Signup Link</label></th>
                    <td><input name="dovetale_link" type="text" id="dovetale_link" value="<?=($error)?$dovetale_link:'';?>" class="regular-text"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="password">Password</label></th>
                    <td><input name="password" type="text" id="password" value="<?=($error)?$password:''?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>


        <p class="submit">
            <input type="submit" name="wi_add_manager_submit" id="submit" class="button button-primary" value="Add <?=wi_influencer_manager_display_name()?>">
        </p>
    </form>

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