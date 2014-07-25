<?php
	if( isset( $_POST['oasiswf_license_activate'] ) ) {
	   $oasis_workflow_license_key = (isset($_POST["oasis_workflow_license_key"]) && $_POST["oasis_workflow_license_key"]) ? $_POST["oasis_workflow_license_key"] : "";
      update_site_option("oasiswf_license_key", $oasis_workflow_license_key) ;
      $workflow_license->activate_license( );
	}

	if( isset( $_POST['oasiswf_license_deactivate'] ) ) {
      $workflow_license->deactivate_license( );
	}

	$license = get_site_option( 'oasiswf_license_key' );
	$status 	= get_site_option( 'oasiswf_license_status' );

?>
<div class="wrap">
	<?php if( isset($_POST['page_action']) && $_POST["page_action"] == "submit" ){?>
		<?php if( $status == 'valid' ) {?>
			<div class="message"><?php echo __("General settings saved successfully.", "oasisworkflow");?></div>
		<?php } else if ($status == 'invalid') {?>
			<div class="error-message"><?php echo __("Either the license key is invalid or your activation limit is reached. Oasis Workflow License cannot be activated.", "oasisworkflow");?></div>
	<?php
		}
	}
   ?>
	<form id="wf_general_settings_form" method="post">
		<div id="workflow-general-setting">
			<div id="license-setting">
				<div class="select-info">
					<label class="settings-title" for="oasis_workflow_license_key"><?php _e('Enter your license key'); ?>:</label>
					<input id="oasis_workflow_license_key" name="oasis_workflow_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
				</div>
				<div class="select-info">
					<?php if( $status !== false && $status == 'valid' ) { ?>
						<?php wp_nonce_field( 'oasiswf_nonce', 'oasiswf_nonce' ); ?>
						<input type="submit" class="button-secondary" name="oasiswf_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
					<?php } else {
						wp_nonce_field( 'oasiswf_nonce', 'oasiswf_nonce' ); ?>
						<input type="submit" class="button button-primary button-large" name="oasiswf_license_activate" value="<?php _e('Activate License'); ?>"/>
					<?php } ?>
				</div>
			</div>
		</div>
		<input type="hidden"
			name="page_action" id="page_action" value="submit" />
	</form>
	<div id="poststuff">
		<div class="owf-sidebar">
			<div class="postbox" style="float: left;">
				<h3 style="cursor: default;">
					<span><?php _e("About this Plugin:", "oasisworkflow"); ?> </span>
				</h3>
				<div class="inside">
					<a class="owf_about_link" style="background-image:url(<?php echo OASISWF_URL . '/img/nugget-solutions.png'; ?>);" target="_blank" href="http://www.nuggetsolutions.com/"><?php _e("Author's website", "oasisworkflow"); ?></a>
					<a class="owf_about_link" style="background-image:url(<?php echo OASISWF_URL . 'img/publish.gif'; ?>);" target="_blank" href="http://oasisworkflow.com/"><?php _e('Plugin webpage', "oasisworkflow"); ?></a>
					<a class="owf_about_link" style="background-image:url(<?php echo OASISWF_URL . '/img/faq-icon.png'; ?>);" target="_blank" href="http://oasisworkflow.com/faq/"><?php _e('FAQ', "oasisworkflow"); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>