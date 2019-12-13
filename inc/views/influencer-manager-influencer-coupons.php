<?php
	$current_user_id = wi_get_current_user_id();
    $influencer_coupons_tbl = $WI_Influencer->influencer_coupons_tbl;
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $users_tbl = $WI_Influencer->users_tbl;

    $influencer_id = isset($_GET['infid'])? (int)$_GET['infid']:0;

    $influencer = wi_valid_user($influencer_id,'influencer');



    $current_page = isset($_GET['cpg']) && !empty($_GET['cpg'])?(int)$_GET['cpg']:1;

    $offset = ($current_page-1)*$WI_Influencer->items_per_page;

	$sql = "SELECT c.*, COALESCE(o.orders,0) as orders FROM $influencer_coupons_tbl as c 
		LEFT JOIN (SELECT influencer_coupon_id, count(id) as orders FROM $commissions_tbl GROUP BY influencer_coupon_id) as o ON o.influencer_coupon_id = c.id
		LEFT JOIN $users_tbl as u ON u.id=c.user_id
		
		WHERE u.user_id = $current_user_id AND c.user_id = $influencer_id ORDER BY c.date_added ASC";

	$limit = " LIMIT $offset, ".$WI_Influencer->items_per_page;

	$coupons = $WI_Influencer->db->get_results($sql.$limit);
?>
<?=wi_heading('Coupons For: '.$influencer->firstname.' '.$influencer->lastname)?>
	 <?php if(!empty($message)): ?>
	<div class="row mx-0 mt-3">
		<div class="col-md-12">
			<?=$message?>
		</div>
	</div>
	 <?php endif; ?>

	<div class="row mx-0">           

		<div class="col-md-12 px-0">
			<table class="table table-bordered table-striped mt-3 mb-3">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">ID</th>
			      <th scope="col">Coupon Code</th>
			      <th scope="col">Date Created</th>
			      <th scope="col">Orders</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		if($coupons){
			  			$count = $offset+1;
			  			foreach ($coupons as $coupon) {
			  				?>
								<tr>
							      <th scope="row"><?=$count++?></th>
							      <td><?=$coupon->coupon_id?></td>
							      <td><?=wi_coupon_code_from_id($coupon->coupon_id)?></td>
							      <td><?=wi_date($coupon->date_added)?></td>
							      <td><a href="<?=$WI_Influencer->get_url('my-influencer-coupon-orders','&cpid='.$coupon->coupon_id)?>"><?=$coupon->orders?></a></td>
							    </tr>
			  				<?php
			  			}
			  		}else{
			  			?>
							<tr>
						      <th scope="row" colspan="5">No coupons were found</th>
						    </tr>
			  			<?php
			  		}
			  	?>
			  </tbody>
			</table>
			<?=wi_pagination($sql,$current_page, $WI_Influencer->get_url('my-influencer-coupons','&infid='.$influencer_id)) ?>
		</div>
		

		
	</div>
	

