<?php
	$current_user_id = wi_get_current_user_id();
    $commissions_tbl = $WI_Influencer->commissions_tbl;
    $payouts_tbl = $WI_Influencer->payouts_tbl;

    $commissions_sql = "SELECT user_id,amount,date_added,id as tran_id, 'COMMISSION' as type, concat('Commission for Order #',order_id) as description  FROM $commissions_tbl";

    $payouts_sql = "SELECT user_id,amount,date_added,id as tran_id, 'PAYOUT' as type, short_description as description  FROM $payouts_tbl";

    $sql = "SELECT * FROM (($commissions_sql) UNION ALL ($payouts_sql)) as a WHERE user_id = $current_user_id ORDER BY date_added ASC";

    $records = $WI_Influencer->db->get_results("$sql");
?>

	<?=wi_heading('My Ledger')?>

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
	

