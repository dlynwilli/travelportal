<?php
//----------------
$action = ( isset($_REQUEST["action"]) ) ? $_REQUEST["action"] : "not-workflow" ;
$type = ( isset($_REQUEST["type"]) ) ? $_REQUEST["type"] : "all" ;
$site_id = isset( $_REQUEST["site"] ) ?  $_REQUEST["site"] : "";

if ( !empty( $site_id ) ) {
    switch_to_blog($site_id);
}

$submitPosts = $submission_workflow->get_submit_articles($type);
$unsubmitPosts = $submission_workflow->get_unsubmit_articles($type) ;

if( $action == "in-workflow" ){
   $posts = $submitPosts ;
}
else {
   $posts = $unsubmitPosts ;
}

//----------------
$count_posts = count($posts);
$pagenum=(isset($_GET['paged']) && $_GET["paged"]) ? $_GET["paged"] : 1;
$per_page=15;
?>
<div class="wrap">
	<?php
	if (function_exists('is_multisite') && is_multisite())
	{
	?>
	<div class="alignleft actions">
		<?php echo __("Select a site: ", "oasisworkflow"); ?><select id="site_filter_submission_report">
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
	<div id="view-workflow">
		<form id="submission_report_form" method="post" action="<?php echo network_admin_url('admin.php?page=oasiswf-reports&tab=workflowSubmissions');?>">
			<div class="tablenav">
				<input type="hidden" name="page" value="oasiswf-submission" />
				<?php if( $action ):?><input type="hidden" id="action" name="action" value="<?php echo $action;?>" /><?php endif ;?>
				<div class="alignleft actions">
					<select name="type">
						<option value="all" <?php echo ($type=="all") ? "selected" : "" ;?> >All Types</option>
						<option value="post" <?php echo ($type=="post") ? "selected" : "" ;?> >Post</option>
						<option value="page" <?php echo ($type=="page")? "selected" : "" ;?> >Page</option>
					</select>
					<input type="submit" class="button action" value="Filter">
				</div>
   			<div>
            	<ul class="subsubsub">
            		<?php
            			$all = ( $action == "all" ) ? "class='current'" : "" ;
            			$not_wf = ( $action == "not-workflow" ) ? "class='current'" : "" ;
            			$wf = ( $action == "in-workflow" ) ? "class='current'" : "" ;
            			echo '<li class="all"><a id="notInWorkflow" href="#" ' . $not_wf . '>' . __('Not in Workflow') .
            					'<span class="count"> (' . count($unsubmitPosts) . ')</span></a> </li>';
            			echo ' | <li class="all"><a id="inWorkflow" href="#" ' . $wf .  '>' . __('In Workflow') .
            				'<span class="count"> (' . count($submitPosts) . ')</span></a> </li>';
            		?>
            	</ul>
         	</div>
   			<div class="tablenav-pages">
   				<?php $submission_workflow->get_page_link($count_posts, $pagenum, $per_page);?>
   			</div>
			</div>
			<input type="hidden" name="site" id="site" value="<?php echo $site_id;?>" />
		</form>
		<table class="wp-list-table widefat fixed posts" cellspacing="0" border=0>
			<thead>
				<?php $submission_workflow->get_table_header($action);?>
			</thead>
			<tfoot>
				<?php $submission_workflow->get_table_header($action);?>
			</tfoot>
			<tbody id="coupon-list">
				<?php
				if($posts):
					$count = 0;
					$start = ($pagenum - 1) * $per_page;
					$end = $start + $per_page;
					foreach ($posts as $post){
						if ( $count >= $end )
							break;
						if ( $count >= $start )
						{
							$user = get_userdata($post->post_author) ;
							echo "<tr>" ;
							if($action == 'in-workflow') :
							echo "<td>&nbsp;&nbsp;&nbsp;<input type='checkbox' id='abort-".$post->ID."' value='".$post->ID."' name='abort' /></td>" ;
							endif;
							echo "<td><a href='post.php?post=" . $post->ID . "&action=edit'>{$post->post_title}</a></td>" ;
							echo "<td>{$post->post_type}</td>" ;
							echo "<td>{$user->data->display_name}</td>" ;
							echo "<td>" . FCWorkflowBase::format_date_for_display( $post->post_date, "-", "datetime") . "</td>" ;
							echo "</tr>" ;
						}
						$count++;
					}
				else:
					echo "<tr>" ;
					echo "<td class='hurry-td' colspan='4'>
							<label class='hurray-lbl'>";
					echo __("No Post/Pages found.");
					echo "</label></td>" ;
					echo "</tr>" ;
				endif ;
				?>
			</tbody>
		</table>
		<?php if($action == 'in-workflow') : ?>
			<div id="view-workflow">
			<div class="tablenav">
				<div class="alignleft actions">
					<select name="action_type" id="action_type">
						<option value="none"><?php echo __("-- Select Action --");?></option>
						<option value="abort"><?php echo __("Abort");?></option>
					</select>
					<input type="button" class="button action" id="apply_action" value="Apply"><span class='loading hidden' style="height: 23px;position: absolute;width: 24px;"></span>
				</div>
   			<div>
			<?php endif; ?>
		<div class="tablenav">
			<div class="tablenav-pages">
				<?php $submission_workflow->get_page_link($count_posts, $pagenum, $per_page);?>
			</div>
		</div>
	</div>
	<div id="out"></div>
</div>
<?php
if ( !empty( $site_id ) ) {
    restore_current_blog();
}
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#notInWorkflow').click( function(event) {
			jQuery("#action").val("not-workflow");
			jQuery("#submission_report_form").submit();
		});

		jQuery('#inWorkflow').click( function(event) {
			jQuery("#action").val("in-workflow");
			jQuery("#submission_report_form").submit();
		});

		jQuery('#forSite').click( function(event) {
			jQuery("#action").val("not-workflow");
			jQuery("#site").val(jQuery('#site_filter_submission_report').val());
			jQuery("#submission_report_form").submit();
		});

		jQuery('input[name=abort-all]').click( function(event) {
			jQuery('input[type=checkbox]').prop('checked',jQuery(this).prop("checked"));
		});

		jQuery('#apply_action').click(function()
		{
			if(jQuery('#action_type').val() == 'none') return;

			var arr = jQuery('input[name=abort]:checked');
			var post_ids = new Array();
			jQuery.each(arr, function(k,v)
			{
				post_ids.push(jQuery(this).val());
			});
			if(post_ids.length === 0) return;

			data = {
			action: 'multi_abort_from_workflow' ,
			postids: post_ids,
		   	};

			jQuery(".loading").show();
			jQuery.post(ajaxurl, data, function( response ) {
				if(response){
				jQuery(".loading").hide();
				jQuery('#inWorkflow').click();
				}
			});
		});

	});
</script>
