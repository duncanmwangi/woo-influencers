<?php
$WI_Influencer = new WI_Influencer();

$role = isset($_GET['role']) && in_array($_GET['role'], ['influencer-manager','influencer'])?$_GET['role']:'influencer';

$user = wi_valid_user($id,$role);


if(!$user){
    $message = wi_admin_message('This is not a valid user!');
}

if(isset($_POST['wi_payout_submit'])){
    $error = true;
    $amount = sanitize_text_field($_POST['amount']);
    $short_description = sanitize_text_field($_POST['short_description']);
    $description = sanitize_text_field($_POST['description']);
    if(!$user){
        $message = wi_admin_message('This is not a valid user!');
    }
    elseif(empty($amount) || empty($short_description) || empty($description) ){
        $message = wi_admin_message('All fields must be filled');
    }
    elseif($amount>wi_get_current_ledger_balance($user->id)){

        $message = wi_admin_message('Payout amount cannot be more than current ledger balance.');
    }
    
    else{

        $result = $WI_Influencer->db->insert($WI_Influencer->payouts_tbl,[
                'amount' => $amount,
                'short_description' => $short_description,
                'description' => $description,
                'user_id' => $id,
                'date_added' => current_time('mysql')
            ]);
            $error = false;
            $message = wi_admin_message('Payout has been added successfully', $error);
    }
}
?>
<div class="wrap">
    <h1>Payout</h1>
    <?php if(!empty($message)) echo $message?>
    <form method="post" action="">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="firstname">First Name</label></th>
                    <td><input name="firstname" type="text" id="firstname" value="<?=$user->firstname?>" class="regular-text" disabled></td>
                </tr>
                <tr>
                    <th scope="row"><label for="lastname">Last Name</label></th>
                    <td><input name="lastname" type="text" id="lastname" value="<?=$user->lastname?>" class="regular-text" disabled></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">Email Address</label></th>
                    <td><input name="email" type="email" id="email" value="<?=$user->email?>" class="regular-text" disabled></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone">Phone Number</label></th>
                    <td><input name="phone" type="text" id="phone" value="<?=$user->phone?>" class="regular-text" disabled></td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="paid_by">Paid By</label></th>
                    <td>
                        <select name="paid_by" id="wi_paid_by" disabled>
                            <?php 
                                $pay_methods = ['paypal' => 'PAYPAL','bank_acount'=>'Bank Acct/Debit Card '];
                                $paid_by = ($error)? $paid_by: $user->paid_by;
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
                    <td><input name="paypal_email" type="text" id="paypal_email" value="<?=($error)?$paypal_email:$user->paypal_email?>" class="regular-text" disabled></td>
                </tr>

                
                <tr class="wi_dovetail_link">
                    <th scope="row"><label for="dovetale_link">Dovetale Signup Link</label></th>
                    <td><input name="dovetale_link" type="text" id="dovetale_link" value="<?=($error)?$dovetale_link:$user->dovetale_link?>" class="regular-text" disabled></td>
                </tr>


                <tr>
                    <th scope="row"><label for="amount">Current Balance</label></th>
                    <td><input name="amount" type="text" id="amount" value="<?=wi_money_prefix().wi_get_current_ledger_balance($user->id)?>" class="regular-text" disabled>
                    </td>
                </tr>


                <tr>
                    <th scope="row"><label for="amount">Amount Paid</label></th>
                    <td><input name="amount" type="text" id="amount" value="<?=($error)?$amount:''?>" class="regular-text">
                    </td>
                </tr>


                <tr>
                    <th scope="row"><label for="short_description">Statement Description</label></th>
                    <td><input name="short_description" type="text" id="short_description" value="<?=($error)?$short_description:''?>" class="regular-text">
                    </td>
                </tr>


                <tr>
                    <th scope="row"><label for="description">Details</label></th>
                    <td>
                    <textarea name="description" id="description" class="regular-text" rows="3"><?=($error)?$description:''?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>


        <p class="submit">
            <input type="submit" name="wi_payout_submit" id="submit" class="button button-primary" value="Save Payout">
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