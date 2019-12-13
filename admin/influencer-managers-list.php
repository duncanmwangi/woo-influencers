<?php
$WI_Influencer = new WI_Influencer();

$orderby_array = ['id','firstname','email','phone','orders','date_added','balance'];
$order_by = isset($_GET['orderby']) && !empty($_GET['orderby']) && in_array($_GET['orderby'],$orderby_array)?$_GET['orderby']:'id';

$order_array = ['asc','desc'];
$order = isset($_GET['order']) && !empty($_GET['order']) && in_array($_GET['order'],$order_array)?$_GET['order']:'asc';

$items_per_page = $WI_Influencer->items_per_page;

$users_tbl = $WI_Influencer->users_tbl;
$commissions_tbl = $WI_Influencer->commissions_tbl;
$payouts_tbl = $WI_Influencer->payouts_tbl;


$payouts_sql = "SELECT user_id, (0-amount)as amount FROM $payouts_tbl";
$commissions_sql = "SELECT user_id, amount FROM $commissions_tbl";
$balance_sql = "SELECT SUM(amount) as balance, user_id FROM (($payouts_sql) UNION ALL ($commissions_sql)) as ledger GROUP BY user_id";
$influencers = $WI_Influencer->db->get_results(
    "SELECT u.*, COALESCE(c.orders,0) as orders , COALESCE(b.balance,0) as balance FROM $users_tbl as u
    LEFT JOIN (SELECT count(id) as orders, user_id FROM $commissions_tbl WHERE 1 GROUP BY user_id) as c ON u.id = c.user_id
    LEFT JOIN ($balance_sql) as b ON b.user_id = u.id
    WHERE u.user_role LIKE 'influencer-manager' ORDER BY $order_by $order"
);

?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?=wi_influencer_manager_display_name(false)?></h1>
    <a href="<?=admin_url('admin.php?page=wi-add-influencer-manager')?>" class="page-title-action">Add New</a>

    <hr class="wp-header-end">

    <?php if(!empty($message)) echo $message ?>
    
    <form id="posts-filter" method="get">

        <input type="hidden" name="page" class="post_type_page" value="wi-managers">
        
        
        <table class="wp-list-table widefat fixed striped pages">
            <thead>
                <tr> 
                    <?php $order_type = ($orderby=='id' && $order=='desc')?'asc':'desc'; ?>
                    <th scope="col" id="title" class="manage-column column-id column-primary sortable <?=wi_get_inverse_order_type($order_by,'id',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-managers'.wi_get_order_by_get_vars($order_by,'id',$order))?>">
                            <span>ID</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>

                    <?php $order_type = ($orderby=='id' && $order=='desc')?'asc':'desc'; ?>
                    <th scope="col" id="title" class="manage-column column-title column-primary sortable <?=wi_get_inverse_order_type($order_by,'firstname',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-managers'.wi_get_order_by_get_vars($order_by,'firstname',$order))?>">
                            <span>Name</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    
                    <th scope="col" id="email" class="manage-column column-email sortable <?=wi_get_inverse_order_type($order_by,'email',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-managers'.wi_get_order_by_get_vars($order_by,'email',$order))?>">
                            <span>Email Address</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="role" class="manage-column column-role sortable  <?=wi_get_inverse_order_type($order_by,'phone',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-manager'.wi_get_order_by_get_vars($order_by,'phone',$order))?>">
                            <span>Phone</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>
                    <th scope="col" id="role" class="manage-column column-role">
                        Head Shot
                    </th>
                    <th scope="col" id="orders" class="manage-column column-orders sortable  <?=wi_get_inverse_order_type($order_by,'orders',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-managers'.wi_get_order_by_get_vars($order_by,'orders',$order))?>">
                            <span>Orders</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>

                    
                    <th scope="col" id="balance" class="manage-column column-orders sortable  <?=wi_get_inverse_order_type($order_by,'balance',$order)?>">
                            <a href="<?=admin_url('admin.php?page=woocommerce-influencers'.wi_get_order_by_get_vars($order_by,'balance',$order))?>">
                                <span>Balance</span>
                                <span class="sorting-indicator"></span>
                            </a>
                    </th>
                    <th scope="col" id="date" class="manage-column column-date sortable  <?=wi_get_inverse_order_type($order_by,'date_added',$order)?>">
                        <a href="<?=admin_url('admin.php?page=wi-managers'.wi_get_order_by_get_vars($order_by,'date_added',$order))?>">
                            <span>Date Created</span>
                            <span class="sorting-indicator"></span>
                        </a>
                    </th>

                </tr>
            </thead>

            <tbody id="the-list">

                <?php 
                if($influencers):
                foreach($influencers as $influencer):
                ?>
                <tr id="post-1149" class="iedit author-self level-0 post-1149 type-page status-publish hentry simple-restrict-permission-wholesale">

                    <td class="column-cols">
                        <?=$influencer->id?>.
                    </td>
                    
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">
                        
                        <strong>
                            <a class="row-title" href="<?=admin_url('admin.php?page=wi-managers&action=edit')?>"><?=$influencer->firstname.' '.$influencer->lastname?></a>
                        </strong>

                        
                        <div class="row-actions">
                            <span class="edit">
                                <a href="<?=admin_url('admin.php?page=wi-managers&id='.$influencer->id.'&action=edit')?>">Edit</a> | 
                            </span>
                            <?php if($influencer->balance>0): ?>
                            <span class="edit">
                                <a target="_blank" href="<?=admin_url('admin.php?page=wi-managers&id='.$influencer->id.'&action=payout&role='.$influencer->user_role)?>"  class="warning">Payout</a> | 
                            </span> 
                            <?php endif; ?>
                            <span class="edit">
                                <a target="_blank" href="<?=$WI_Influencer->get_url('my-influencers','&as=admin&role=influencer-manager&uid='.$influencer->id)?>" class="submitdelete danger" >Login as me</a>
                            </span> 
                        </div>
                    </td>
                    <td class="column-cols">
                        <?=$influencer->email?>
                    </td>
                    <td class="column-cols">
                        <?=$influencer->phone?>
                    </td>
                    <td class="column-cols">
                        <img class="headshot" src="<?=get_home_url().$influencer->headshot_path?>"/>
                    </td>
                    <td class="column-cols">
                        <?=$influencer->orders?>
                    </td>

                    
                    <td class="column-cols">
                        <?=wi_money_prefix().$influencer->balance?>
                    </td>
                    <td class="column-cols"><abbr title="<?=date('m/d/Y H:i:s',strtotime($influencer->date_added))?>"><?=date('m/d/Y',strtotime($influencer->date_added))?></abbr></td>
                </tr>

                <?php  endforeach;
                
                else:?>
                    <tr id="" class="">
                        <td colspan="6"><strong>No records found.</strong></td>
                    </tr>

                <?php  endif; ?>
                
            </tbody>
            
        </table>
        
    </form>

    <div id="ajax-response"></div>
    <br class="clear">
</div>
<style>
.fixed .column-title{
    width: 30%;
}
.fixed .column-email{
    width: 20%;
}
.fixed .column-role{
    width: 15%;
}
.fixed .column-orders{
    width: 10%;
}
.fixed .column-date{
    width: 10%;
}
.fixed .column-id{
    width: 5%;
}

.fixed .column-cols{
    text-align: left;
}
.headshot{
    max-width: 50px;
    max-height: 50px;
}
a.warning{
    color: green;
}
a.danger{
    color: red;
}
</style>