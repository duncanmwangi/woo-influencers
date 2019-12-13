<?php
if(isset($_POST['wi_submit_settings'])){
    $influencer_manager_commission_percent = sanitize_text_field($_POST['influencer_manager_commission_percent']);
    $influencer_commission_percent = sanitize_text_field($_POST['influencer_commission_percent']);
    $wi_coupon_discount_percent = sanitize_text_field($_POST['wi_coupon_discount_percent']);
    $wi_influencer_page_id = sanitize_text_field($_POST['wi_influencer_page_id']);
    $wi_influencer_manager_menu_id = sanitize_text_field($_POST['wi_influencer_manager_menu_id']);
    $wi_influencer_menu_id = sanitize_text_field($_POST['wi_influencer_menu_id']);

    $wi_influencer_personal_coupon_discount_percent = sanitize_text_field($_POST['wi_influencer_personal_coupon_discount_percent']);
    
    if(empty($influencer_manager_commission_percent) || empty($influencer_commission_percent) || 
        empty($wi_coupon_discount_percent) || empty($wi_influencer_page_id) || 
        empty($wi_influencer_personal_coupon_discount_percent)){
        $message = wi_admin_message('All fields must be filled');
    }
    elseif($wi_coupon_discount_percent<0.00001 || $wi_coupon_discount_percent>=100 || $influencer_manager_commission_percent<0.00001 || $influencer_manager_commission_percent>=100 || $influencer_commission_percent<0.00001 || $influencer_commission_percent>=100 || ($influencer_manager_commission_percent+$influencer_commission_percent)>=100){
        $message = wi_admin_message('Kindly check through the percentages. They seem to be off!');
    }
    else{
        update_option('wi_influencer_manager_commission_percent', $influencer_manager_commission_percent);
        update_option('wi_influencer_commission_percent', $influencer_commission_percent);
        update_option('wi_coupon_discount_percent', $wi_coupon_discount_percent);
        update_option('wi_influencer_page_id', $wi_influencer_page_id);
        update_option('wi_influencer_manager_menu_id', $wi_influencer_manager_menu_id);
        update_option('wi_influencer_menu_id', $wi_influencer_menu_id);
        update_option('wi_influencer_personal_coupon_discount_percent', $wi_influencer_personal_coupon_discount_percent);
        $message = wi_admin_message('Settings saved successfully',false);
    }
}




if(isset($_POST['wi_submit_protect_page'])){
    $wi_role = sanitize_text_field($_POST['wi_role']);
    $wi_protected_page_id = sanitize_text_field($_POST['wi_protected_page_id']);
    
    if(empty($wi_role) || empty($wi_protected_page_id)){
        $message = wi_admin_message('All fields must be filled');
    }
    else{
        if($wi_role=='influencer'){
            $existing_pages = explode(',', get_option('wi_influencer_protected_page_ids',false));
            if(!in_array($wi_protected_page_id, $existing_pages)){
                $existing_pages[] = $wi_protected_page_id;
                $wi_influencer_protected_page_ids = implode(',', $existing_pages);
                update_option('wi_influencer_protected_page_ids', $wi_influencer_protected_page_ids);
            }
            

        }elseif($wi_role=='influencer_manager'){

            $existing_pages = explode(',', get_option('wi_influencer_manager_protected_page_ids',false));
            if(!in_array($wi_protected_page_id, $existing_pages)){
                $existing_pages[] = $wi_protected_page_id;
                $wi_influencer_manager_protected_page_ids = implode(',', $existing_pages);
                update_option('wi_influencer_manager_protected_page_ids', $wi_influencer_manager_protected_page_ids);
            }

        }

        $message = wi_admin_message('The page has been protected successfully',false);
    }
}

if(isset($_POST['wi_del_protect_page'])){
    $wi_protected_page_id = sanitize_text_field($_POST['wi_protected_page_id']);
    $wi_role = sanitize_text_field($_POST['wi_role']);
    if(empty($wi_role) || empty($wi_protected_page_id)){
        $message = wi_admin_message('All fields must be filled');
    }
    else{
        if($wi_role=='influencer'){
            $existing_pages = explode(',', get_option('wi_influencer_protected_page_ids',false));
            if(in_array($wi_protected_page_id, $existing_pages)){
                
                $existing_pages = array_filter($existing_pages, function($e) use ($wi_protected_page_id) {
                    return ($e !== $wi_protected_page_id);
                });

                $wi_influencer_protected_page_ids = implode(',', $existing_pages);
                update_option('wi_influencer_protected_page_ids', $wi_influencer_protected_page_ids);
            }
            

        }elseif($wi_role=='influencer_manager'){

            $existing_pages = explode(',', get_option('wi_influencer_manager_protected_page_ids',false));
            if(in_array($wi_protected_page_id, $existing_pages)){
                $existing_pages = array_filter($existing_pages, function($e) use ($wi_protected_page_id) {
                    return ($e !== $wi_protected_page_id);
                });
                $wi_influencer_manager_protected_page_ids = implode(',', $existing_pages);
                update_option('wi_influencer_manager_protected_page_ids', $wi_influencer_manager_protected_page_ids);
            }

        }

        $message = wi_admin_message('The page is no longer protected successfully',false);
    }
}


?>

<div class="wrap">
    <h1>Woo Influencer Settings</h1>
    <?php if(!empty($message)) echo $message ?>
    <form method="post" action="<?=admin_url('admin.php?page=wi-settings')?>" novalidate="novalidate">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="influencer_manager_commission_percent"><?=wi_influencer_manager_display_name()?> Commission Percentage</label></th>
                    <td><input name="influencer_manager_commission_percent" type="decimal" id="influencer_manager_commission_percent" value="<?=get_option('wi_influencer_manager_commission_percent',10)?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="influencer_commission_percent"><?=wi_influencer_display_name()?> Commission Percentage</label></th>
                    <td><input name="influencer_commission_percent" type="text" id="influencer_commission_percent" value="<?=get_option('wi_influencer_commission_percent',10)?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wi_coupon_discount_percent">Coupon Discount Percentage</label></th>
                    <td><input name="wi_coupon_discount_percent" type="text" id="wi_coupon_discount_percent" value="<?=get_option('wi_coupon_discount_percent',10)?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wi_influencer_personal_coupon_discount_percent">Personal Influencer Coupon Discount Percentage</label></th>
                    <td><input name="wi_influencer_personal_coupon_discount_percent" type="text" id="wi_influencer_personal_coupon_discount_percent" value="<?=get_option('wi_influencer_personal_coupon_discount_percent',30)?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="wi_influencer_page_id">Influencers Login Page</label></th>
                    <td>
<?php wp_dropdown_pages(['name'=>'wi_influencer_page_id','selected'=>get_option('wi_influencer_page_id',get_option('page_on_front'))])?>
                    </td>
                </tr>


                    <tr>
                        <th scope="row"><label for="wi_influencer_menu_id"><?=wi_influencer_display_name().' Menu'?></label></th>
                        <td>
                            <select name="wi_influencer_menu_id">
                                <option value="">Select Menu</option>
                                <?php 
                                $menus = wp_get_nav_menus();
                                foreach ($menus as $menu) {

                                    $wi_influencer_menu_id = get_option('wi_influencer_menu_id','');
                                    $selected = $wi_influencer_menu_id==$menu->term_id?'selected="selected"':'';
                                    ?>
                                        <option value="<?=$menu->term_id?>" <?=$selected?> ><?=$menu->name?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            
                        </td>
                    </tr>


                    <tr>
                        <th scope="row"><label for="wi_influencer_manager_menu_id"><?=wi_influencer_display_name().' Menu'?></label></th>
                        <td>
                            <select name="wi_influencer_manager_menu_id">
                                <option value="">Select Menu</option>
                                <?php 
                                $menus = wp_get_nav_menus();
                                foreach ($menus as $menu) {

                                    $wi_influencer_menu_id = get_option('wi_influencer_manager_menu_id','');
                                    $selected = $wi_influencer_menu_id==$menu->term_id?'selected="selected"':'';
                                    ?>
                                        <option value="<?=$menu->term_id?>" <?=$selected?> ><?=$menu->name?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            
                        </td>
                    </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="wi_submit_settings" id="submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
    <hr>
        <h2>Add a protected pages</h2>
        <form method="post" action="<?=admin_url('admin.php?page=wi-settings')?>" novalidate="novalidate">

            <table class="form-table" role="presentation" style="max-width: 800px">
                <tbody>
                    <tr>
                        <th scope="row"><label for="influencer_manager_commission_percent">Role</label></th>
                        <td>
                            <select name="wi_role">
                                <option value="">Select Role</option>
                                <?php 
                                $roles = ['influencer' => wi_influencer_display_name(), 'influencer_manager'=>wi_influencer_manager_display_name()];
                                foreach ($roles as $key => $value) {
                                    echo '<option value="'.$key.'" >'.$value.'</option>';
                                }
                                ?>
                            </select>
                            
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wi_protected_page_id">Page</label></th>
                        <td>
                            <?php wp_dropdown_pages(['name'=>'wi_protected_page_id'])?>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            <p class="submit">
                <input type="submit" name="wi_submit_protect_page" id="submit" class="button button-primary" value="Protect Page">
            </p>
        </form>
        
    <hr>

        <h2><?=wi_influencer_manager_display_name()?> protected pages</h2>

        <table class="wp-list-table widefat fixed striped pages" style="max-width: 800px">
            <tr>
                <th width="10%">ID</th>
                <th width="60%">Page Title</th>
                <th width="30%">Action</th>
            </tr>
            <?php 
                $existing_pages = explode(',', get_option('wi_influencer_manager_protected_page_ids',false));
                if($existing_pages && count($existing_pages)>1){
                    foreach ($existing_pages as $page_id) {
                        if(empty($page_id)) continue;
                        ?>
                            <tr>
                                <td><?=$page_id?></td>
                                <td><a target="_blank" href="<?=get_permalink($page_id)?>"><?=get_the_title( $page_id )?></a></td>
                                <td><form method="post" action="<?=admin_url('admin.php?page=wi-settings')?>" novalidate="novalidate"><input type="hidden" name="wi_role" value="influencer_manager"><input type="hidden" name="wi_protected_page_id" value="<?=$page_id?>"><input type="submit" name="wi_del_protect_page" id="submit" class="button button-danger" value="Remove"></form></td>
                            </tr>
                        <?php
                    }
                }
                else{
                    ?>
                        <tr>
                            <td colspan="3">No protected pages found</td>
                        </tr>

                    <?php
                }
            ?>
        </table>

    <hr>
        <h2><?=wi_influencer_display_name()?> protected pages</h2>
        <table class="wp-list-table widefat fixed striped pages" style="max-width: 800px">
            <tr>
                <th>ID</th>
                <th>Page Title</th>
                <th>Action</th>
            </tr>
            <?php 
                $existing_pages = explode(',', get_option('wi_influencer_protected_page_ids',false));
                if($existing_pages && count($existing_pages)>1){
                    foreach ($existing_pages as $page_id) {
                        if(empty($page_id)) continue;
                        ?>
                            <tr>
                                <td><?=$page_id?></td>
                                <td><a target="_blank" href="<?=get_permalink($page_id)?>"><?=get_the_title( $page_id )?></a></td>
                                <td><form method="post" action="<?=admin_url('admin.php?page=wi-settings')?>" novalidate="novalidate"><input type="hidden" name="wi_role" value="influencer"><input type="hidden" name="wi_protected_page_id" value="<?=$page_id?>"><input type="submit" name="wi_del_protect_page" id="submit" class="button button-danger" value="Remove"></form></td>
                            </tr>
                        <?php
                    }
                }else{
                    ?>
                        <tr>
                            <td colspan="3">No protected pages found</td>
                        </tr>

                    <?php
                }
            ?>
        </table>

        
        

</div>
<style>
.form-table th {
    width: 30%;
}
</style>