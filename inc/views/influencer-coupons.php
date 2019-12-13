<?php
	$current_user_id = wi_get_current_user_id();
    $influencer_coupons_tbl = $WI_Influencer->influencer_coupons_tbl;
    $commissions_tbl = $WI_Influencer->commissions_tbl;

    $current_page = isset($_GET['cpg']) && !empty($_GET['cpg'])?(int)$_GET['cpg']:1;

    $offset = ($current_page-1)*$WI_Influencer->items_per_page;


    if(isset($_POST['wi_create_coupon'])){
    	$error = true;
    	$coupon_code = sanitize_title(sanitize_text_field($_POST['coupon_code']));
    	$description = sanitize_text_field($_POST['description']);
    	if(empty($coupon_code) || empty($description)){
    		$message = wi_user_message('All fields must be filled.');
    	}elseif(strlen($coupon_code)<5){
    		$message = wi_user_message('Coupon code must be more than 5 characters.');
    	}elseif(wi_coupon_exists($coupon_code)){
    		$message = wi_user_message('Coupon code already exists');
    	}else{
    		$coupon = wi_create_influencer_coupon_code($current_user_id,$coupon_code,$description);
    		$error = false;
            $message = wi_user_message('Coupon code: '.$coupon->get_code().' has been created successfully.',$error);
    	}
    }

	$sql = "SELECT c.*, COALESCE(o.orders,0) as orders FROM $influencer_coupons_tbl as c 
		LEFT JOIN (SELECT influencer_coupon_id, count(id) as orders FROM $commissions_tbl WHERE user_id = $current_user_id GROUP BY influencer_coupon_id) as o ON o.influencer_coupon_id = c.id
		WHERE c.user_id = $current_user_id ORDER BY c.date_added ASC";



	$limit = " LIMIT $offset, ".$WI_Influencer->items_per_page;

	$coupons = $WI_Influencer->db->get_results($sql.$limit);
?>
<?=wi_heading('My Coupons')?>
	 <?php if(!empty($message)): ?>
	<div class="row mx-0 mt-3">
		<div class="col-md-12">
			<?=$message?>
		</div>
	</div>
	 <?php endif; ?>

	<div class="row mx-0">           

		<div class="col-md-8 px-0">
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
			  				$description = get_post_meta($coupon->coupon_id, 'wi_coupon_description',true);
			  				?>
								<tr>
							      <th scope="row"><?=$count++?></th>
							      <td><?=$coupon->coupon_id?></td>
							      <td><?=wi_coupon_code_from_id($coupon->coupon_id)?> <button type="button" class="btn btn-primary btn-sm wi-description" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$description?>">Description</button></td>
							      <td><?=wi_date($coupon->date_added)?></td>
							      <td><a href="<?=$WI_Influencer->get_url('coupon-orders','&cpid='.$coupon->coupon_id)?>"><?=$coupon->orders?></a></td>
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
			<?=wi_pagination($sql,$current_page, $WI_Influencer->get_url('coupons')) ?>
		</div>
		

		<div class="col-md-4 mt-3">
			<h3>Create Coupon Code</h3>
			<hr>
			<form action="" method="post">
			  <div class="form-group">
			    <label for="coupon_code">Coupon Code</label>
			    <input type="text" class="form-control" id="coupon_code_c" name="coupon_code" placeholder="Coupon Code" value="<?php if(isset($error) && $error) echo $coupon_code;?>">
			  </div>
			 <div class="form-group">
			    <label for="description">Description</label>
			    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add a description here"><?php if(isset($error) && $error) echo $description;?></textarea>
			  </div>
			  <button type="submit" class="btn btn-primary" name="wi_create_coupon">Create Coupon</button>
			</form>
		</div>
	</div>

	<style type="text/css">
		button.wi-description{
			    padding: 1px 1px;
    			float: right;
		}
	</style>
	

