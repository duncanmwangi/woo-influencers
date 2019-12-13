<?php if(wi_get_current_user_obj()->is_admin): 
	$logged_in_user = wi_valid_user(wi_get_current_user_id(),wi_get_current_user_obj()->role);
	?>
<div class="alert alert-warning" role="alert">
  <strong>NOTE:</strong> ADMIN logged in as  <?=wi_influencer_display_name()?>: <strong><?=$logged_in_user->firstname.' '.$logged_in_user->lastname?></strong> Email: <strong><?=$logged_in_user->email?></strong>

</div>
<?php endif; ?>

<div class="topnav" id="myTopnav">
  <!-- <a href="<?=$WI_Influencer->get_url('dashboard')?>" class="<?=($WI_Influencer->current_page=='dashboard')?'active':''?>">Dashboard</a> -->
  <a href="<?=$WI_Influencer->get_url('coupons')?>" class="<?=($WI_Influencer->current_page=='coupons' || $WI_Influencer->current_page=='coupon-orders')?'active':''?>">Coupons</a>
  <a href="<?=$WI_Influencer->get_url('orders')?>" class="<?=($WI_Influencer->current_page=='orders')?'active':''?>">Orders</a>
  <a href="<?=$WI_Influencer->get_url('ledger')?>" class="<?=($WI_Influencer->current_page=='ledger')?'active':''?>">Ledger</a>
  <a href="<?=$WI_Influencer->get_url('profile')?>" class="<?=($WI_Influencer->current_page=='profile')?'active':''?>">My Profile</a>
  <a href="<?=$WI_Influencer->get_url('logout')?>" class="<?=($WI_Influencer->current_page=='logout')?'active':''?> logout">Logout</a>
  <a href="javascript:void(0);" class="icon" onclick="wi_responsive_menu()">&#9776;</a>
</div>

