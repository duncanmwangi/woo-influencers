<?php
	$current_user_id = wi_get_current_user_id();
    $commissions_tbl = $WI_Influencer->commissions_tbl;

    $current_page = isset($_GET['cpg']) && !empty($_GET['cpg'])?(int)$_GET['cpg']:1;

    $offset = ($current_page-1)*$WI_Influencer->items_per_page;


	$sql = "SELECT c.* FROM $commissions_tbl as c 
		WHERE c.user_id = $current_user_id ORDER BY c.date_added ASC";

	$limit = " LIMIT $offset, ".$WI_Influencer->items_per_page;

	$orders = $WI_Influencer->db->get_results($sql.$limit);
?>
<?=wi_heading('My Orders')?>
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
			  			$count = $offset+1;
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
			<?=wi_pagination($sql,$current_page, $WI_Influencer->get_url('my-orders')) ?>
		</div>
	</div>
	

