<?php
$selected_tab = 'workflowSettings'; // default tab, if nothing is set
if (isset ( $_GET['tab'] )) { // if something is set, go to that tab
   $selected_tab =  $_GET['tab'];
}
// if the license info is incomplete or license status is invalid, go to the generalSettings tab
$license = get_site_option( 'oasiswf_license_key' );
$status 	= get_site_option( 'oasiswf_license_status' );

if (empty($license) || $status == 'invalid') {
   $selected_tab = 'generalSettings';
}

?>
<div class="wrap">
	<?php
       $tabs = array( 'generalSettings' => __('General Settings', "oasisworkflow"),
       					 'workflowSettings' => __('Workflow Settings', "oasisworkflow"),
       					 'autoSubmitSettings' => __('Auto-Submit Settings', "oasisworkflow"),
                      'wpDocumentRevisionSettings' => __('WP Document Revision Settings', "oasisworkflow") );
       echo '<div id="icon-themes" class="icon32"><br></div>';
       echo '<h2 class="nav-tab-wrapper">';
       foreach( $tabs as $tab => $name ){
           $class = ( $tab == $selected_tab ) ? ' nav-tab-active' : '';
           echo "<a class='nav-tab$class' href='?page=oasiswf-setting&tab=$tab'>$name</a>";

       }
       echo '</h2>';
   	echo '<table class="form-table">';
   	switch ( $selected_tab ){
   		case 'generalSettings' :
   		   $workflow_license = new FCWorkflowLicense() ;
   		   include( OASISWF_PATH . "includes/pages/general-settings.php" ) ;
   		   break;
   		case 'workflowSettings' :
   		   include( OASISWF_PATH . "includes/pages/workflow-settings.php" ) ;
   		   break;
   		case 'autoSubmitSettings' :
   		   include( OASISWF_PATH . "includes/pages/auto-submit-settings.php" ) ;
   		   break;
   		case 'wpDocumentRevisionSettings' :
   		   include( OASISWF_PATH . "includes/pages/wp-document-revision-settings.php" ) ;
   		   break;
   	}
   	echo '</table>';
	?>
</div>