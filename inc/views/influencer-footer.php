<?php 
$current_user = wi_valid_user(wi_get_current_user_id(),'influencer');
$manager = wi_valid_user($current_user->user_id,'influencer-manager');
$manager->name = ucwords($manager->firstname.' '.$manager->lastname);
?>

<div class="row  justify-content-md-center wi-btm-card">
  <div class="col-md-7">
    <div class="card mb-0 mt-3">
      <div class="row no-gutters">
        <div class="col-md-2">
          <img src="<?=get_site_url().$manager->headshot_path?>" class="card-img card-img-km" alt="<?=$manager->name?>">
        </div>
        <div class="col-md-10">
          <div class="card-body pb-0 pt-2"> 
            <h4 class="card-title h41km">Your <?=wi_influencer_manager_display_name()?>:</h4>           
            <h4 class="card-title h42km"><?=$manager->name?> : <?=$manager->email?></h4>
            <!-- <h5 class="card-title text-muted h51km"><?=$manager->email?></h5> -->
            <h5 class="card-title text-muted h51km">Your personal 30% off coupon code: <span class="font-weight-bold"><?=$current_user->personal_coupon_code?></span> to be used with your email: <span class="font-weight-bold"><?=$current_user->email?></span></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
