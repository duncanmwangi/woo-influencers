<?php
	$current_user_id = wi_get_current_user_id();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $payouts_tbl = $WI_Influencer->payouts_tbl;
    $users_tbl = $WI_Influencer->users_tbl;
    $influencer_coupons_tbl = $WI_Influencer->influencer_coupons_tbl;

    $influencer_id = isset($_GET['infid'])? (int)$_GET['infid']:0;

    $influencer = wi_valid_user($influencer_id,'influencer');

    $commissions_sql = "SELECT u.user_id as manager_id, c.user_id as user_id,c.amount as amount,c.date_added as date_added,c.id as tran_id, 'COMMISSION' as type, concat('Commission for Order #',c.order_id,' Coupon Code: ',p.post_name) as description  FROM $commissions_tbl as c
		LEFT JOIN $users_tbl  as u ON u.id = c.user_id 
    LEFT JOIN $influencer_coupons_tbl as ic ON ic.id = c.influencer_coupon_id 
    LEFT JOIN wp_posts as p ON ic.coupon_id = p.ID
    ";

    $payouts_sql = "SELECT u.user_id as manager_id, p.user_id as user_id,p.amount as amount,p.date_added as date_added,p.id as tran_id, 'PAYOUT' as type, short_description as description  FROM $payouts_tbl as p
		LEFT JOIN $users_tbl  as u ON u.id = p.user_id";

    $sql = "SELECT * FROM (($commissions_sql) UNION ALL ($payouts_sql)) as a WHERE manager_id = $current_user_id AND user_id = $influencer_id ORDER BY date_added ASC";

    $records = $WI_Influencer->db->get_results("$sql");
?>

	<?=wi_heading(wi_influencer_display_name().' Ledger For '.$influencer->firstname.' '.$influencer->lastname)?>

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
			      <th scope="col">Tran ID</th>
			      <th scope="col">Tran Date</th>
			      <th scope="col">Description</th>
			      <th scope="col">IN</th>
			      <th scope="col">OUT</th>
			      <th scope="col">BALANCE</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		if($records){
			  			$count = 1;
			  			$balance = 0;
			  			foreach ($records as $record) {
			  				$balance = $record->type=='COMMISSION' ? $balance + $record->amount : $balance - $record->amount;
			  				?>
								<tr>
							      <th scope="row"><?=$count++?></th>
							      <td><?=wi_tran_id($record)?></td>
							      <td><?=wi_date($record->date_added)?></td>
							      <td><?=$record->description?></td>
							      <td><?=wi_ledger_amount($record,'IN')?></td>
							      <td><?=wi_ledger_amount($record,'OUT')?></td>
							      <td><?=wi_money_prefix().number_format($balance,2)?></td>
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
		</div>
	</div>
	

