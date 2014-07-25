<?php
$selected_tab = isset ( $_GET['tab'] ) ?  $_GET['tab'] : 'userAssignments';
?>
<div class="wrap">
	<?php
       $tabs = array( 'userAssignments' => __('Current Assignments', "oasisworkflow"),
       					 'workflowSubmissions' => __('Workflow Submissions', "oasisworkflow") );
       echo '<div id="icon-themes" class="icon32"><br></div>';
       echo '<h2 class="nav-tab-wrapper">';
       foreach( $tabs as $tab => $name ){
           $class = ( $tab == $selected_tab ) ? ' nav-tab-active' : '';
           echo "<a class='nav-tab$class' href='?page=oasiswf-reports&tab=$tab'>$name</a>";

       }
       echo '</h2>';
   	echo '<table class="form-table">';
   	switch ( $selected_tab ){
   		case 'userAssignments' :
   		   include( OASISWF_PATH . "includes/pages/workflow-assignment-report.php" ) ;
   		   break;
   		case 'workflowSubmissions' :
   		   $submission_workflow = new FCWorkflowSubmission() ;
   		   include( OASISWF_PATH . "includes/pages/workflow-submission-report.php" ) ;
   		   break;
   	}
   	echo '</table>';
	?>
</div>