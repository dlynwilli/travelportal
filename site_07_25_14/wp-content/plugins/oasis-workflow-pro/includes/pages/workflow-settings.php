<?php
if( isset($_POST['page_action']) && $_POST["page_action"] == "submit" ){

	$reminder_days = (isset($_POST["reminder_days"]) && $_POST["reminder_days"]) ? $_POST["reminder_days"] : "";
   update_site_option("oasiswf_reminder_days", $reminder_days) ;

	$reminder_days_after = (isset($_POST["reminder_days_after"]) && $_POST["reminder_days_after"]) ? $_POST["reminder_days_after"] : "";
   update_site_option("oasiswf_reminder_days_after", $reminder_days_after) ;

	$enable_workflow_process = (isset($_POST["activate_workflow_process"]) && $_POST["activate_workflow_process"]) ? $_POST["activate_workflow_process"] : "";
	update_site_option("oasiswf_activate_workflow", $enable_workflow_process) ;
	
	$post_published_notification = (isset($_POST["post_published_notification"]) && $_POST["post_published_notification"]) ? $_POST["post_published_notification"] : "";
   update_site_option("oasiswf_post_published_notification", $post_published_notification) ;

	$skip_workflow_roles = array();
	if (isset($_POST["skip_workflow_roles"]) && count($_POST["skip_workflow_roles"]) > 0 )
	{
	   $selectedOptions = $_POST["skip_workflow_roles"];
	   foreach ($selectedOptions as $selectedOption)
	   {
         array_push($skip_workflow_roles, $selectedOption);
	   }
	}
	update_site_option("oasiswf_skip_workflow_roles", $skip_workflow_roles) ;
	
	$wfsettings_on_post_type = array();
	if (isset($_POST["show_workflow_setting_on_post_types"]) && count($_POST["show_workflow_setting_on_post_types"]) > 0 )
	{
	   $selectedTypes = $_POST["show_workflow_setting_on_post_types"];
	   foreach ($selectedTypes as $selectedType)
	   {
         array_push($wfsettings_on_post_type, $selectedType);
	   }
	}
	update_site_option("oasiswf_show_wfsettings_on_post_types", $wfsettings_on_post_type) ;	
}

$reminder_day = get_site_option('oasiswf_reminder_days') ;
$reminder_day_after = get_site_option('oasiswf_reminder_days_after') ;
$skip_workflow_roles = get_site_option('oasiswf_skip_workflow_roles') ;
$show_wfsettings_on_post_types = get_site_option('oasiswf_show_wfsettings_on_post_types') ;
?>
<div class="wrap">
	<?php if( isset($_POST['page_action']) && $_POST["page_action"] == "submit" ):?>
		<div class="message"><?php echo __("Workflow settings saved successfully.", "oasisworkflow");?></div>
	<?php endif;?>
	<?php if( isset($_POST['page_action']) && $_POST["page_action"] == "auto_submit" ):?>
		<div class="message"><?php echo __("Auto submit triggered successfully. " . $submitted_posts_count . " posts/page submitted.", "oasisworkflow");?></div>
	<?php endif;?>
	<form id="wf_settings_form" method="post">
		<div id="workflow-setting">
			<div id="settingstuff">
				<div class="select-info">
   				<?php
   				$str="" ;
   				if( get_site_option("oasiswf_activate_workflow") == "active" )$str = "checked=true" ;
   				?>
					<label class="settings-title"><input type="checkbox" name="activate_workflow_process"
						value="active" <?php echo $str;?> />&nbsp;&nbsp;<?php echo __("Activate Workflow process ?", "oasisworkflow") ;?>
					</label>
				</div>
				<div class="select-info">
					<label class="settings-title">
						<input type="checkbox" id="chk_reminder_day"	<?php echo ($reminder_day) ? "checked" : "" ;?> />
						&nbsp;&nbsp;<?php echo __(" Send Reminder Email", "oasisworkflow") ;?>
					</label>
					<input type="text" id="reminder_days" name="reminder_days" size="4" class="reminder_days" value="<?php echo $reminder_day;?>" maxlength=2 />
					<label class="settings-title"><?php echo __("day(s) before due date.", "oasisworkflow");?></label>
				</div>
				<div class="select-info">
					<label class="settings-title">
						<input type="checkbox" id="chk_reminder_day_after"	<?php echo ($reminder_day_after) ? "checked" : "" ;?> />
						&nbsp;&nbsp;<?php echo __(" Send Reminder Email", "oasisworkflow") ;?>
					</label>
					<input type="text" id="reminder_days_after" name="reminder_days_after" size="4" class="reminder_days" value="<?php echo $reminder_day_after;?>" maxlength=2 />
					<label class="settings-title"><?php echo __("day(s) after due date.", "oasisworkflow");?></label>
				</div>
				<!-- setting for sending email to author when his/her post published. checked by default. -->
				<div class="select-info">  
				<?php
   				$value = (get_site_option("oasiswf_post_published_notification") == "active") ? ' checked="checked" ' : '';   				
   				?> 			
					<label class="settings-title"><input type="checkbox" name="post_published_notification"
						value="active" <?php echo $value; ?> />&nbsp;&nbsp;<?php echo __("Send an email notification to the author when post is published.", "oasisworkflow") ; ?>
					</label>
				</div>
				<!-- email notification setting ends -->
				<div class="select-info">
					<div class="list-section-heading">
						<label><?php echo __("Role(s) that can skip the workflow and use the out of the box options:", "oasisworkflow")?></label>
					</div>
    				<select name="skip_workflow_roles[]" id="skip_workflow_roles[]" size="6" multiple="multiple">
    				   <?php FCUtility::owf_dropdown_roles_multi($skip_workflow_roles); ?>
    				</select>
				</div>
				
				<div class="select-info">
					<div class="list-section-heading">
						<label><?php echo __("Show Workflow options for the following post/page types:", "oasisworkflow")?></label>
					</div>    			
    				   <?php FCUtility::owf_dropdown_post_types_multi('show_workflow_setting_on_post_types[]', $show_wfsettings_on_post_types); ?>
				</div>	

				<div id="owf_settings_button_bar">
					<input type="submit" id="settingSave"
						class="button button-primary button-large"
						value="<?php echo __("Save", "oasisworkflow") ;?>" />

					<input type="hidden"
						name="page_action" id="page_action" value="submit" />
				</div>
			</div>

	</form>
</div>
<script type='text/javascript'>
jQuery(document).ready(function($) {
	jQuery("#chk_reminder_day").click(function(){
		if(jQuery(this).attr("checked") == "checked"){
			jQuery("#reminder_days").attr("disabled", false) ;
		}else{
			jQuery("#reminder_days").val('');
			jQuery("#reminder_days").attr("disabled", true) ;
		}
	}) ;

	jQuery("#chk_reminder_day_after").click(function(){
		if(jQuery(this).attr("checked") == "checked"){
			jQuery("#reminder_days_after").attr("disabled", false) ;
		}else{
			jQuery("#reminder_days_after").val('');
			jQuery("#reminder_days_after").attr("disabled", true) ;
		}
	}) ;

	jQuery("#settingSave").click(function(){
		if( jQuery("#chk_reminder_day").attr("checked") == "checked" ){
			if( !jQuery("#reminder_days").val() ){
				alert("Please enter the number of days.") ;
				return false;
			}
			if(isNaN(jQuery("#reminder_days").val())){
				alert("Please enter a numeric value.") ;
				return false;
			}
		}

		if( jQuery("#chk_reminder_day_after").attr("checked") == "checked" ){
			if( !jQuery("#reminder_days_after").val() ){
				alert("Please enter the number of days.") ;
				return false;
			}
			if(isNaN(jQuery("#reminder_days_after").val())){
				alert("Please enter a numeric value.") ;
				return false;
			}
		}
	});
});
</script>
