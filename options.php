<script type="text/javascript">
function gtwregister_reg() {
	jQuery.post(ajaxurl, jQuery('#regform').serialize(), function(response) {
		if(response == 'Success')
		{
			jQuery('#regbox').html('Thanks! Registration was successful!');
		} else {
			alert("Oh, no! We weren't able to complete your registration. :(");
		}
	});
};
</script>

<div class="wrap">
<h2>GoToWebinar Background Registration</h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<?php settings_fields('gtwregister'); ?>

<p>
Please enter the server number for your account. When you log into GoToWebinar or look at any of the registration URLs, you'll see a number after the www. Enter it below.

<table class="form-table">

<tr valign="top">
<th scope="row">Server #:</th>
<td><code>https://www<input type="text" name="gtw_servernum" value="<?php echo get_option('gtw_servernum'); ?>" size="1" style="width: 20px;" />.gotomeeting.com</code></td>
</tr>

</table>

<p>
Please enter the default url paramater names for each of the following required GoToWebinar fields. These should reflect the various
names that your CRM system will use when redirecting opt-in submissions to this page. Separate each variation with a comma.

<table class="form-table">

<tr valign="top">
<th scope="row">First Name Fields:</th>
<td><input type="text" name="gtw_field_firstname" value="<?php echo get_option('gtw_field_firstname'); ?>" size="60" /></td>
</tr>

<tr valign="top">
<th scope="row">Last Name Fields:</th>
<td><input type="text" name="gtw_field_lastname" value="<?php echo get_option('gtw_field_lastname'); ?>" size="60" /></td>
</tr>

<tr valign="top">
<th scope="row">Email Fields:</th>
<td><input type="text" name="gtw_field_email" value="<?php echo get_option('gtw_field_email'); ?>" size="60" /></td>
</tr>

<tr valign="top">
<th scope="row">Phone Fields:</th>
<td><input type="text" name="gtw_field_phone" value="<?php echo get_option('gtw_field_phone'); ?>" size="60" /></td>
</tr>

<tr valign="top">
<th scope="row">Server Num Fields:</th>
<td><input type="text" name="gtw_field_servernum" value="<?php echo get_option('gtw_field_servernum'); ?>" size="60" /></td>
</tr>

<tr valign="top">
<th scope="row">GoToWebinarID Fields:</th>
<td><input type="text" name="gtw_field_gtwid" value="<?php echo get_option('gtw_field_gtwid'); ?>" size="60" /></td>
</tr>

</table>

<input type="hidden" name="action" value="update" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</form>



<a name="register"></a>
<?php if(gtwregister_isregistered()) { ?>
<h3>"Thank you for registering your plugin! You're a rock star!" - Jeremy Shapiro</h3>
<?php
  } else {
	global $current_user;
	get_currentuserinfo();
?>
<div id="regbox" class="updated">
<p>
<strong>Please register your copy of the GTWRegister Plugin!</strong> Your registration helps keep Jeremy motivated and let's him know how widespread the use of this plugin is.
He'll keep you up to date on plugin changes and might even email you some cool non-spammy goodies from time to time. Your email will be kept super safe and won't be shared,
swapped or given away.
<p>
<form accept-charset="UTF-8" id="regform" 
	action="javascript:gtwregister_reg();"
	name="Wordpress GTWRegister Registration" style="height:100%; margin:0" target=""
	onsubmit="gtwregister_reg(); return false;">
<input name="action" type="hidden" value="gtwregister_reg">
<input name="inf_form_xid" type="hidden" value="bd263214d17cb0c6ee09e53ac57fcc65" />
<input name="inf_form_name" type="hidden" value="Wordpress GTWRegister Registration" />
<input name="infusionsoft_version" type="hidden" value="1.24.1.81" />
<label for="inf_field_FirstName">First Name: </label>
<input class="infusion-field-input" id="inf_field_FirstName" name="inf_field_FirstName" type="text" value="<?php
	global $current_user; echo((isset($current_user->user_firstname) && $current_user->user_firstname) ? 
	$current_user->user_firstname : $current_user->display_name);
	?>" />
<label for="inf_field_Email">Email: </label>
<input class="infusion-field-input" id="inf_field_Email" name="inf_field_Email" type="text" value="<?php echo(get_bloginfo('admin_email')); ?>" />
<input id="inf_field_Website" name="inf_field_Website" type="hidden" value="<?php echo(get_bloginfo('url')); ?>" />
<input type="submit" value="Register!" class="button-primary" />
</form>
</div>
<?php } ?>



<h2>Usage</h2>
<p>
To background process a registration, embed the <code>[gtwregister /]</code> shortcode in your page or post. To hardcode
any field, specify the value using <code>firstname</code>, <code>lastname</code>, <code>email</code>, <code>phone</code>,
<code>servernum</code> (the server number for the webinar), and <code>gtwid</code> (the ID of your webinar), for example
<code>[gtwregister gtwid="23940324" /]</code>.

<p>
By default, if there is an error, it will be displayed, and if the registration is successful, the success will appear in
an HTML comment. You can change these options by passing along <code>commentsuccess</code> and <code>commenterror</code>
as in <code>commenterror=1</code> to force errors to be commented or <code>commentsuccess=0</code> to display that the
registration was successful.

<p>
Anything that you don't hardcode into the shortcode, should be passed along in the query string to your opt-in thank you page
on your site.

<p>
You only need to set the server number once (at the top of this page), but if you run webinars on multiple GoToWebinar accounts
and want to override the server number for an individual webinar, you can do so by either hardcoding the server number into
the short code (i.e. <code>[gtwregister gtwid="3239904949" servernum="2" /]</code>) or passing it along in the query string
using one of the Server Num Fields specified above.

<p>
Next set your registration form or opt-in thank you page redirection form to go to the page you created. If using an opt-in form
with a thank you page redirect, you must enable the option to pass the opt-in information to the thank you page.

<h2>What You Need to Create</h2>
<ol>
<li>An opt-in form from your CRM. This opt-in form must:
 <ol>
  <li>Ask for First Name, Last Name, and Email at a minimum
  <li>Have hidden fields for the Webinar ID (this can also be a drop down so folks can choose the time slot they want)
  <li>Allow you to configure an external "thank you" page
  <li>Allow you to pass along the form fields to the thank you page.
 </ol>
<li>A webinar registartion page on this site. You'll put the form code from #1 above on this page. Ideally, you'll make it pretty, too!
<li>A registration confirmation page on this site. This is where you'll put the <code>[gtwregister /]</code> shortcode as well as any copy to say "Thanks!"
</ol>

<h2>Your GoToWebinar Configuration</h2>
Please note that the only fields you can make required on your GoToWebinar configuration are First Name, Last Name and Email.
You can include Phone, but it's best to make it optional unless your opt-in form requires Phone, too. Any other fields marked
as "required" in GoToWebinar will cause the registration to fail.


</div>
