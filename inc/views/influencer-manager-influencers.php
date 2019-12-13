<?php
	$current_user_id = wi_get_current_user_id();
    $users_tbl = $WI_Influencer->users_tbl;
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $influencer_coupons_tbl = $WI_Influencer->influencer_coupons_tbl;

    $current_page = isset($_GET['cpg']) && !empty($_GET['cpg'])?(int)$_GET['cpg']:1;

    $offset = ($current_page-1)*$WI_Influencer->items_per_page;

    $sql = "SELECT u.*, COALESCE(orders,0) as orders , COALESCE(coupons,0) as coupons FROM $users_tbl as u

		LEFT JOIN (SELECT user_id, count(id) as orders FROM $commissions_tbl GROUP BY user_id) as o ON o.user_id = u.id
		LEFT JOIN (SELECT user_id, count(id) as coupons FROM $influencer_coupons_tbl GROUP BY user_id) as c ON c.user_id = u.id
		WHERE u.user_id = $current_user_id AND u.user_role LIKE 'influencer' ORDER BY u.firstname ASC";

	$limit = " LIMIT $offset, ".$WI_Influencer->items_per_page;

	$influencers = $WI_Influencer->db->get_results($sql.$limit);


?>
<?=wi_heading('My '.wi_influencer_display_name(false).' <a href="'.$WI_Influencer->get_url('add-influencer').'" class="btn btn-primary btn-sm ml-3">Add '.wi_influencer_display_name().'</a>')?>
	 <?php if(!empty($message)): ?>
	<div class="row mx-0 mt-3">
		<div class="col-md-12">
			<?=$message?>
		</div>
	</div>
	 <?php endif; ?>

	<div class="row mx-0">           

		<div class="col-md-12 mx-0 px-0">
			<table class="table table-bordered table-striped mt-3 mb-3">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">ID</th>
			      <th scope="col">Name</th>
			      <th scope="col">Email Address</th>
			      <th scope="col">Date Added</th>
			      <th scope="col">Coupons</th>
			      <th scope="col">Orders</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		if($influencers){
			  			$count = $offset+1;
			  			foreach ($influencers as $influencer) {
			  				?>
								<tr class="cls-edit-tr">
							      <th scope="row"><?=$count++?></th>
							      <td><?=$influencer->id?></td>
							      <td>
							      	<?=$influencer->firstname.' '. $influencer->lastname?><br/>
							      	<a class="cls-edit-hidden" href="<?=$WI_Influencer->get_url('edit-influencer','&infid='.$influencer->id)?>">Edit</a>
							      	<a class="ml-3 cls-edit-hidden" href="<?=$WI_Influencer->get_url('influencer-ledger','&infid='.$influencer->id)?>">View Ledger</a>
							      </td>
							      <td><?=$influencer->email?></td>
							      <td><?=wi_date($influencer->date_added)?></td>
							      <td><a href="<?=$WI_Influencer->get_url('my-influencer-coupons','&infid='.$influencer->id)?>"><?=$influencer->coupons?></a></td>
							      <td><a href="<?=$WI_Influencer->get_url('my-influencer-orders','&infid='.$influencer->id)?>"><?=$influencer->orders?></a></td>
							    </tr>
			  				<?php
			  			}
			  		}else{
			  			?>
							<tr>
						      <th scope="row" colspan="7">No orders were found</th>
						    </tr>
			  			<?php
			  		}
			  	?>
			  </tbody>
			</table>
			<?=wi_pagination($sql,$current_page, $WI_Influencer->get_url('my-influencers')) ?>
		</div>
	</div>
	

