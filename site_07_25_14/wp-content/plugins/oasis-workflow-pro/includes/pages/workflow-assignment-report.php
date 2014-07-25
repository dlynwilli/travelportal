<?php
$site_id = isset( $_REQUEST["site"] ) ?  $_REQUEST["site"] : "";
if ( !empty( $site_id ) ) {
    switch_to_blog($site_id);
}
$selected_user = isset( $_REQUEST['user'] ) ? $_REQUEST["user"] : null;
$wfactions = FCWorkflowInbox::get_assigned_post( null, $selected_user ) ;
$count_posts = count($wfactions);
$pagenum = (isset( $_GET['paged'] ) && $_GET["paged"]) ? $_GET["paged"] : 1;
$per_page = 25;
?>
<div class="wrap">
	<?php
	if (function_exists('is_multisite') && is_multisite())
	{
	?>
	<div class="alignleft actions">
		<?php echo __("Select a site: ", "oasisworkflow"); ?><select id="site_filter_assignment_report">
		<?php
		   $all_blogs = FCUtility::get_all_blogs();
         foreach ($all_blogs as $blog){
				if( (isset( $_REQUEST['site'] ) && $_REQUEST["site"] == $blog["blog_id"]) )
					echo "<option value={$blog["blog_id"]} selected>{$blog["blog_name"]}</option>" ;
				else
					echo "<option value={$blog["blog_id"]}>{$blog["blog_name"]}</option>" ;
         }
		?>
		</select>
		<a id="forSite" href="#">
			<input type="button" class="button-secondary action" value="<?php echo __("Show", "oasisworkflow"); ?>" />
		</a>
	</div>
	<?php
	}
	?>
	<form id="assignment_report_form" method="post" action="<?php echo network_admin_url('admin.php?page=oasiswf-reports&tab=userAssignments');?>">
      <div class="tablenav">
      	<ul class="subsubsub"></ul>
      	<div class="tablenav-pages">
      		<?php FCWorkflowInbox::get_page_link($count_posts,$pagenum, $per_page);?>
      	</div>
      </div>
      <input type="hidden" name="site" id="site" value="<?php echo $site_id;?>" />
   </form>
   <table class="wp-list-table widefat fixed posts" cellspacing="0" border=0>
   	<thead>
   		<?php
   				echo "<tr>";
   				echo "<th class='column-role'>" . __("User", "oasisworkflow") . "</th>";
   				echo "<th>" . __("Post/Page", "oasisworkflow") . "</th>";
   				echo "<th class='column-role'>" . __("Workflow", "oasisworkflow") . "</th>";
   				echo "<th class='column-author'>" . __("Status", "oasisworkflow") . "</th>";
   				echo "<th class='column-author'>" . __("Due Date", "oasisworkflow") . "</th>";
   				echo "</tr>";
   		?>
   	</thead>
   	<tfoot>
   		<?php
   				echo "<tr>";
   				echo "<th class='column-role'>" . __("User", "oasisworkflow") . "</th>";
   				echo "<th>" . __("Post/Page", "oasisworkflow") . "</th>";
   				echo "<th class='column-role'>" . __("Workflow", "oasisworkflow") . "</th>";
   				echo "<th class='column-author'>" . __("Status", "oasisworkflow") . "</th>";
   				echo "<th class='column-author'>" . __("Due Date", "oasisworkflow") . "</th>";
   				echo "</tr>";
   		?>
   	</tfoot>
   	<tbody id="coupon-list">
   		<?php
   			$wfstatus = get_site_option( "oasiswf_status" ) ;
   			$sspace = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ;
   			if($wfactions):
   				$count = 0;
   				$start = ($pagenum - 1) * $per_page;
   				$end = $start + $per_page;
   				foreach ($wfactions as $wfaction){
   					if ( $count >= $end )
   						break;
   					if ( $count >= $start )
   					{
   						$post = get_post($wfaction->post_id);
   						$user = get_userdata( $post->post_author ) ;
   						$stepId = $wfaction->step_id;
   						if ($stepId <= 0 || $stepId == "" ) {
   							$stepId = $wfaction->review_step_id;
   						}
   						$step = FCWorkflowInbox::get_step_by_id( $stepId ) ;
   						$workflow = FCWorkflowInbox::get_workflow_by_id( $step->workflow_id );

   						$chk_claim = FCWorkflowInbox::check_claim($wfaction->ID) ;

   						echo "<tr id='post-{$wfaction->post_id}' class='post-{$wfaction->post_id} post type-post status-pending format-standard hentry category-uncategorized alternate iedit author-other'> " ;
   						$assigned_actor_id = null;
   						if ($wfaction->assign_actor_id != -1) { // not in review process
   						   $assigned_actor = FCWorkflowBase::get_user_name($wfaction->assign_actor_id);
   						}
   						else { //in review process
      				      $assigned_actor = FCWorkflowBase::get_user_name($wfaction->actor_id);
   						}

   						echo "<td>".$assigned_actor."</td>" ;
   						echo "<td><a href='post.php?post=" . $post->ID . "&action=edit'>{$post->post_title}</a></td>" ;
   						echo "<td>{$workflow->name}</td>" ;
   						echo "<td>". $wfstatus[FCProcessFlow::get_gpid_dbid( $workflow->ID, $stepId, 'process' )] ."</td>" ;
   						echo "<td>" . FCWorkflowInbox::format_date_for_display($wfaction->due_date) . "</td>" ;
   						echo "</tr>" ;
   					}
   					$count++;
   				}
   			else:
   				echo "<tr>" ;
   				echo "<td class='hurry-td' colspan='5'>
   						<label class='hurray-lbl'>";
   				echo __("No current assignments.", "oasisworkflow");
   				echo "</label></td>" ;
   				echo "</tr>" ;
   			endif;
   		?>
   	</tbody>
   </table>
   <div class="tablenav">
   	<div class="tablenav-pages">
   		<?php FCWorkflowInbox::get_page_link($count_posts,$pagenum, $per_page);?>
   	</div>
   </div>
</div>
<?php
if ( !empty( $site_id ) ) {
    restore_current_blog();
}
?>
<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#forSite').click( function(event) {
			jQuery("#site").val(jQuery('#site_filter_assignment_report').val());
			jQuery("#assignment_report_form").submit();
		});
	});
</script>

