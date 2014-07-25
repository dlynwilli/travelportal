<?php
if( isset($_POST['page_action'])){
   /*
	$doc_revision_roles = array();
	if (isset($_POST["doc_revision_roles"]) && count($_POST["doc_revision_roles"]) > 0 )
	{
	   $selectedOptions = $_POST["doc_revision_roles"];
	   foreach ($selectedOptions as $selectedOption)
	   {
         array_push($doc_revision_roles, $selectedOption);
	   }
	}
	*/

   $doc_revision_title_prefix = (isset($_POST["title_prefix"]) && trim($_POST["title_prefix"])) ? $_POST["title_prefix"] : "";
   $doc_revision_title_suffix = (isset($_POST["title_suffix"]) && $_POST["title_suffix"]) ? $_POST["title_suffix"] : "";

	/*update_site_option("oasiswf_doc_revision_roles", $doc_revision_roles) ;*/
	update_site_option("oasiswf_doc_revision_title_prefix", $doc_revision_title_prefix) ;
	update_site_option("oasiswf_doc_revision_title_suffix", $doc_revision_title_suffix) ;


}
$doc_revision_title_prefix = get_site_option( 'oasiswf_doc_revision_title_prefix' );
$doc_revision_title_suffix = get_site_option( 'oasiswf_doc_revision_title_suffix' );
/*$doc_revision_roles = get_site_option('oasiswf_doc_revision_roles') ;*/
?>

<div class="wrap">
	<?php if( isset($_POST['page_action'])):?>
		<div class="message"><?php echo __("Document revision settings saved successfully.", "oasisworkflow");?></div>
	<?php endif;?>

	<form id="wf_settings_form" method="post">
		<div id="auto-submit-setting">
			<div id="settingstuff">
				<div class="select-info">
					<label class="settings-title">
						<?php echo __("Title prefix:", "oasisworkflow") ;?>
					</label>
					<input type="text" id="title_prefix" name="title_prefix" value="<?php echo $doc_revision_title_prefix;?>" />
					<span class="description"><?php echo __("Prefix to be added before the original title, e.g. \"Copy of\" (blank for no prefix)", "oasisworkflow") ; ?> </span>
				</div>
				<div class="select-info">
					<label class="settings-title">
						<?php echo __("Title suffix:", "oasisworkflow") ;?>
					</label>
					<input type="text" id="title_suffix" name="title_suffix" value="<?php echo $doc_revision_title_suffix;?>" />
					<span class="description"><?php echo __("Suffix to be added after the original title, e.g. \"(dup)\" (blank for no suffix)", "oasisworkflow") ; ?> </span>
				</div>
				<div id="owf_settings_button_bar">
					<input type="submit" id="revisionSettingSave"
						class="button button-primary button-large"
						value="<?php echo __("Save", "oasisworkflow") ;?>" />

					<input type="hidden"
						name="page_action" id="page_action" value="submit" />
				</div>
			</div>
	</form>
</div>