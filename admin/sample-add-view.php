<div class="wrap">
<h1>General Settings</h1><div class="notice is-dismissible notice-info">
	  <p><strong>Facebook for WooCommerce
        is almost ready.</strong> To complete your configuration, <a href="https://trifectacbd.com/wp-admin//admin.php?page=wc-settings&amp;tab=integration&amp;section=facebookcommerce">complete the
        setup steps</a>.</p>
	<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

<form method="post" action="options.php" novalidate="novalidate">
<input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="e38a676f1b"><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
<table class="form-table" role="presentation">

<tbody><tr>
<th scope="row"><label for="blogname">Site Title</label></th>
<td><input name="blogname" type="text" id="blogname" value="Trifecta CBD" class="regular-text"></td>
</tr>

<tr>
<th scope="row"><label for="blogdescription">Tagline</label></th>
<td><input name="blogdescription" type="text" id="blogdescription" aria-describedby="tagline-description" value="Relief. Balance. Wellbeing." class="regular-text">
<p class="description" id="tagline-description">In a few words, explain what this site is about.</p></td>
</tr>


<tr>
<th scope="row"><label for="siteurl">WordPress Address (URL)</label></th>
<td><input name="siteurl" type="url" id="siteurl" value="https://trifectacbd.com" class="regular-text code"></td>
</tr>

<tr>
<th scope="row"><label for="home">Site Address (URL)</label></th>
<td><input name="home" type="url" id="home" aria-describedby="home-description" value="https://trifectacbd.com" class="regular-text code">
	<p class="description" id="home-description">
		Enter the address here if you <a href="https://codex.wordpress.org/Giving_WordPress_Its_Own_Directory">want your site home page to be different from your WordPress installation directory</a>.</p>
</td>
</tr>


<tr>
<th scope="row"><label for="new_admin_email">Email Address</label></th>
<td><input name="new_admin_email" type="email" id="new_admin_email" aria-describedby="new-admin-email-description" value="info@elitefunds.com" class="regular-text ltr">
<p class="description" id="new-admin-email-description">This address is used for admin purposes. If you change this we will send you an email at your new address to confirm it. <strong>The new address will not become active until confirmed.</strong></p>
	<div class="updated inline">
	<p>
	There is a pending change of the admin email to <code>info@trifectacbd.com</code>. <a href="https://trifectacbd.com/wp-admin/options.php?dismiss=new_admin_email&amp;_wpnonce=dcb0c932a4">Cancel</a>	</p>
	</div>
</td>
</tr>


<tr>
<th scope="row">Membership</th>
<td> <fieldset><legend class="screen-reader-text"><span>Membership</span></legend><label for="users_can_register">
<input name="users_can_register" type="checkbox" id="users_can_register" value="1">
	Anyone can register</label>
</fieldset></td>
</tr>

<tr>
<th scope="row"><label for="default_role">New User Default Role</label></th>
<td>
<select name="default_role" id="default_role">
	<option value="shop_manager">Shop manager</option>
	<option value="customer">Customer</option>
	<option value="wholesale">Wholesale</option>
	<option selected="selected" value="subscriber">Subscriber</option>
	<option value="contributor">Contributor</option>
	<option value="author">Author</option>
	<option value="editor">Editor</option>
	<option value="administrator">Administrator</option></select>
</td>
</tr>

		
</tr>
<tr>
<th scope="row">Date Format</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Date Format</span></legend>
	<label><input type="radio" name="date_format" value="F j, Y" checked="checked"> <span class="date-time-text format-i18n">November 15, 2019</span><code>F j, Y</code></label><br>
	<label><input type="radio" name="date_format" value="Y-m-d"> <span class="date-time-text format-i18n">2019-11-15</span><code>Y-m-d</code></label><br>
	<label><input type="radio" name="date_format" value="m/d/Y"> <span class="date-time-text format-i18n">11/15/2019</span><code>m/d/Y</code></label><br>
	<label><input type="radio" name="date_format" value="d/m/Y"> <span class="date-time-text format-i18n">15/11/2019</span><code>d/m/Y</code></label><br>
<label><input type="radio" name="date_format" id="date_format_custom_radio" value="\c\u\s\t\o\m"> <span class="date-time-text date-time-custom-text">Custom:<span class="screen-reader-text"> enter a custom date format in the following field</span></span></label><label for="date_format_custom" class="screen-reader-text">Custom date format:</label><input type="text" name="date_format_custom" id="date_format_custom" value="F j, Y" class="small-text"><br><p><strong>Preview:</strong> <span class="example">November 15, 2019</span><span class="spinner"></span>
</p>	</fieldset>
</td>
</tr>

<tr>
<th scope="row"><label for="start_of_week">Week Starts On</label></th>
<td><select name="start_of_week" id="start_of_week">

	<option value="0">Sunday</option>
	<option value="1" selected="selected">Monday</option>
	<option value="2">Tuesday</option>
	<option value="3">Wednesday</option>
	<option value="4">Thursday</option>
	<option value="5">Friday</option>
	<option value="6">Saturday</option></select></td>
</tr>
</tbody></table>


<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>

</div>