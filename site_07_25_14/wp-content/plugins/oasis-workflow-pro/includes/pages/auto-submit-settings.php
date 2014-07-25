<?php
if( isset($_POST['page_action'])){
	$auto_submit_stati = array();
	if (isset($_POST["auto_submit_stati"]) && count($_POST["auto_submit_stati"]) > 0 )
	{
	   $selectedOptions = $_POST["auto_submit_stati"];
	   foreach ($selectedOptions as $selectedOption)
	   {
         array_push($auto_submit_stati, $selectedOption);
	   }
	}

   $auto_submit_due_days = (isset($_POST["auto_submit_due_days"]) && $_POST["auto_submit_due_days"]) ? $_POST["auto_submit_due_days"] : "";
   $auto_submit_comment = (isset($_POST["auto_submit_comment"]) && trim($_POST["auto_submit_comment"])) ? $_POST["auto_submit_comment"] : "";
   $auto_submit_post_count = (isset($_POST["auto_submit_post_count"]) && $_POST["auto_submit_post_count"]) ? $_POST["auto_submit_post_count"] : "";
   $auto_submit_enable = (isset($_POST["auto_submit_enable"]) && $_POST["auto_submit_enable"]) ? $_POST["auto_submit_enable"] : "";

   $auto_submit_settings = array(
      'auto_submit_stati' => $auto_submit_stati,
      'auto_submit_due_days' => $auto_submit_due_days,
      'auto_submit_comment' => stripcslashes( $auto_submit_comment ),
      'auto_submit_post_count' => $auto_submit_post_count,
      'auto_submit_enable' => $auto_submit_enable
   );
	update_site_option("oasiswf_auto_submit_settings", $auto_submit_settings) ;

	// if trigger auto submit is clicked
   if( $_POST["page_action"] == "auto_submit"){
      $submitted_posts_count = FCWorkflowActions::auto_submit_articles(true);
   }
}


$auto_submit_settings = get_site_option('oasiswf_auto_submit_settings');
$auto_submit_stati = $auto_submit_settings['auto_submit_stati'];
$auto_submit_due_days = $auto_submit_settings['auto_submit_due_days'];
$auto_submit_comment = $auto_submit_settings['auto_submit_comment'];
$auto_submit_post_count = $auto_submit_settings['auto_submit_post_count'];
$auto_submit_enable = $auto_submit_settings['auto_submit_enable'];

?>

<div class="wrap">
	<?php if( isset($_POST['page_action'])):?>
		<div class="message"><?php echo __("Auto submit settings saved successfully.", "oasisworkflow");?></div>
	<?php endif;?>
	<?php if( isset($_POST['page_action']) && $_POST["page_action"] == "auto_submit"):?>
		<div class="message"><?php echo __("Auto submit triggered successfully. " . $submitted_posts_count . " posts/page submitted.", "oasisworkflow");?></div>
	<?php endif;?>
	<form id="wf_settings_form" method="post">
		<div id="auto-submit-setting">
			<div id="settingstuff">
				<ol>
					<li>
      				<div class="select-info">
         				<?php
         				$str="" ;
         				if( $auto_submit_enable == "active" )$str = "checked=true" ;
         				?>
      					<label class="settings-title"><input type="checkbox" name="auto_submit_enable" id="auto_submit_enable"
      						value="active" <?php echo $str;?> />&nbsp;&nbsp;<?php echo __("Enable Auto Submit?", "oasisworkflow") ;?>
      					</label>
      				</div>
      			</li>
      			<li>
      				<div class="select-info">
      					<div class="list-section-heading">
      						<label><?php echo __("Post/Page status(es) to be selected for auto submit:", "oasisworkflow")?></label>
      					</div>
          				<select name="auto_submit_stati[]" id="auto_submit_stati" size="6" multiple="multiple">
          				   <?php FCUtility::owf_dropdown_post_status_multi($auto_submit_stati); ?>
          				</select>
      				</div>
   				</li>
   				<li>
      				<div class="select-info">
      					<label class="settings-title">
     						   <?php echo __("Set Due date as CURRENT DATE + ", "oasisworkflow") ;?>
      					</label>
      					<input type="text" id="auto_submit_due_days" name="auto_submit_due_days" size="4" class="auto_submit_due_days" value="<?php echo $auto_submit_due_days;?>" maxlength=2 />
      					<label class="settings-title"><?php echo __("day(s).", "oasisworkflow");?></label>
      				</div>
   				</li>
   				<li>
      				<div class="select-info">
      					<div class="list-section-heading">
         					<label>
        						   <?php echo __("Auto submit comments:", "oasisworkflow") ;?>
         					</label>
         				</div>
      					<textarea id="auto_submit_comment" name="auto_submit_comment" size="4" class="auto_submit_comment"
      					cols="80" rows="5"><?php echo $auto_submit_comment;?></textarea>
      				</div>
   				</li>
   				<li>
      				<div class="select-info">
      					<label class="settings-title">
     						   <?php echo __("Process ", "oasisworkflow") ;?>
      					</label>
      					<input type="text" id="auto_submit_post_count" name="auto_submit_post_count" size="8" class="auto_submit_post_count" value="<?php echo $auto_submit_post_count;?>" maxlength=4 />
      					<label class="settings-title"><?php echo __("posts/pages at one time.", "oasisworkflow");?></label>
      					</br/>
      					<span class="description"><?php echo __("(Limit the number of posts/pages to be processed at one time for optimum server performance.)", "oasisworkflow");?></span>
      				</div>
   				</li>
				</ol>
				<div id="owf_settings_button_bar">
					<input type="button" id="settingSave"
						class="button button-primary button-large"
						value="<?php echo __("Save", "oasisworkflow") ;?>" />

					<input type="button" id="autoSubmitBtn"
						class="button button-secondary button-large" name ="autoSubmitBtn"
						value="<?php echo __("Trigger Auto Submit - Just One Time", "oasisworkflow") ;?>" />

					<input type="hidden"
						name="page_action" id="page_action" value="submit" />
				</div>
			</div>
	</form>
</div>
<script type='text/javascript'>
jQuery(document).ready(function($) {
	jQuery("#autoSubmitBtn").click(function(){
		jQuery("#page_action").val("auto_submit");
		var result = validateForm();
		if (result) {
			jQuery("#wf_settings_form").submit();
		}
	});

	jQuery("#settingSave").click(function(){
		if ( jQuery('#auto_submit_enable').is(':checked') ) { // we need to validate the fields for auto submit
			var result = validateForm();
			if (result) {
				jQuery("#wf_settings_form").submit();
			}
		}
		else {
			jQuery("#wf_settings_form").submit();
		}
	});

   function validateForm() {
	   var obj = jQuery('#auto_submit_stati');
 	   var options = jQuery('#auto_submit_stati > option:selected');
	   if(options.length == 0){
		   alert("Please select atleast one Post/Page status.") ;
		   return false;
	   }

   	if( !jQuery("#auto_submit_due_days").val() ){
   		alert("Please enter a value for due date.") ;
   		return false;
   	}
   	if(isNaN(jQuery("#auto_submit_due_days").val())){
   		alert("Please enter a numeric value for due date.") ;
   		return false;
   	}

   	if( !jQuery("#auto_submit_post_count").val() ){
   		alert("Please enter the number of posts/pages to be processed at one time.") ;
   		return false;
   	}
   	if(isNaN(jQuery("#auto_submit_post_count").val())){
   		alert("Please enter a numeric value for post count.") ;
   		return false;
   	}
   	return true;
   }
});
</script>