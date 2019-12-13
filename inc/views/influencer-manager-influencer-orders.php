<?php
	$current_user_id = wi_get_current_user_id();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $users_tbl = $WI_Influencer->users_tbl;

    $influencer_id = isset($_GET['infid'])? (int)$_GET['infid']:0;

    $influencer = wi_valid_user($influencer_id,'influencer');

	$orders = $WI_Influencer->db->get_results("SELECT c.* FROM $commissions_tbl as c 
		LEFT JOIN $users_tbl as u ON u.id = c.user_id
		WHERE c.user_id = $influencer_id AND u.user_id = $current_user_id ORDER BY c.date_added ASC");
?>
<?=wi_heading('Orders For: '.$influencer->firstname.' '.$influencer->lastname)?>
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
			      <th scope="col">Order #</th>
			      <th scope="col">Order Date</th>
			      <th scope="col">Order Amount</th>
			      <th scope="col">Commission</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php 
			  		if($orders){
			  			$count = 1;
			  			foreach ($orders as $order_row) {
			  				$order = new WC_Order($order_row->order_id);
			  				?>
								<tr>
							      <th scope="row"><?=$count++?></th>
							      <td><?=$order_row->id?></td>
							      <td><?='#'.$order_row->order_id?></td>
							      <td><?=wi_date($order_row->date_added)?></td>
							      <td><?=wi_money_prefix().number_format($order->get_total(),2)?></td>
							      <td><?=wi_money_prefix().number_format($order_row->amount,2)?></td>
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
	

