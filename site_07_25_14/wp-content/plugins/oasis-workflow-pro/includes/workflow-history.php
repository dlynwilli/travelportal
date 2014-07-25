<?php
class FCWorkflowHistory extends FCWorkflowBase
{
	static function get_table_header()
	{
		$create_date_order_class = "";
		$order = ( isset($_GET['order']) && $_GET["order"] == "desc" ) ? "asc" : "desc" ;

		if( isset($_GET['orderby']) && $_GET["orderby"] == "post_title" )
			$post_order_class = $_GET["order"] ;
		else
			$post_order_class = "" ;

		if( isset($_GET['orderby']) && $_GET["orderby"] == "wf_name" )
			$wf_order_class = $_GET["order"] ;
		else
			$wf_order_class = "" ;

		if( isset($_GET['orderby']) && $_GET["orderby"] == "assign_actor" )
			$assign_order_class = $_GET["order"] ;
		else
			$assign_order_class = "" ;

		if( isset($_GET['orderby']) && $_GET["orderby"] == "due_date" )
			$due_date_order_class = $_GET["order"] ;
		else
			$due_date_order_class = "" ;

		if( isset($_GET['orderby']) && $_GET["orderby"] == "reminder_date" )
			$reminder_date_order_class = $_GET["order"] ;
		else
			$reminder_date_order_class = "" ;

		$wherepost = ( isset($_GET['post']) && $_GET["post"] ) ? "&post=" . $_GET["post"] : "" ;

		echo "<tr>";
		echo "<th scope='col' class='manage-column check-column' ><input type='checkbox'></th>";
		echo "<th scope='col' class='sorted $post_order_class'>
				<a href='admin.php?page=oasiswf-history&orderby=post_title&order=$order" . $wherepost . "'>
					<span>". __("Title", "oasisworkflow") . "</span>
					<span class='sorting-indicator'></span>
				</a>
			</th>" ;
		echo "<th>" . __("Actor", "oasisworkflow") . "</th>" ;
		echo "<th scope='col' class='sorted $wf_order_class'>
				<a href='admin.php?page=oasiswf-history&orderby=wf_name&order=$order" . $wherepost . "'>
					<span>". __("Workflow(version)", "oasisworkflow") . "</span>
					<span class='sorting-indicator'></span>
				</a>
			</th>" ;
		echo "<th>" . __("Step", "oasisworkflow") . "</th>";
		echo "<th scope='col' class='sorted $create_date_order_class'>
				<a href='admin.php?page=oasiswf-history&orderby=create_datetime&order=$order" . $wherepost . "'>
					<span>". __("Assigned date", "oasisworkflow") . "</span>
					<span class='sorting-indicator'></span>
				</a>
			</th>" ;
		echo "<th scope='col'>". __("Sign Off Date", "oasisworkflow") . "</th>" ;
		echo "<th scope='col'>". __("Result", "oasisworkflow") . "</th>" ;
		echo "<th scope='col' class='history-comment'>". __("Comments", "oasisworkflow") . "</th>" ;
		echo "</tr>";
	}

	static function get_workflow_history_all($postid=null)
	{
		global $wpdb;
		$orderby = ( isset($_GET['orderby']) && $_GET['orderby'] ) ? " ORDER BY {$_GET['orderby']} {$_GET['order']}" : "  ORDER BY A.ID DESC" ;
		$w = "action_status!='abort_no_action' AND action_status!='complete' AND action_status!='cancelled'" ;
		if( $postid )$w .= " AND post_id=" . $postid ;
		$sql = "SELECT A.* , B.post_title, C.ID as userid, C.display_name as assign_actor, D.step_info, D.workflow_id, D.wf_name, D.version
					FROM
						((SELECT * FROM " . FCUtility::get_action_history_table_name() . " WHERE $w) AS A
						LEFT JOIN
						{$wpdb->posts} AS B
						ON  A.post_id = B.ID
						LEFT JOIN
						{$wpdb->users} AS C
						ON A.assign_actor_id = C.ID
						LEFT JOIN
						(SELECT AA.*, BB.name as wf_name, BB.version FROM " . FCUtility::get_workflow_steps_table_name() . " AS AA LEFT JOIN " . FCUtility::get_workflows_table_name() . " AS BB ON AA.workflow_id = BB.ID) AS D
						ON A.step_id = D.ID)
					{$orderby}
					" ;
		$result = $wpdb->get_results( $sql ) ;
		return $result;
	}

	static function get_history_count($postid=null)
	{
		global $wpdb;
		$w = "action_status!='abort_no_action' AND action_status!='complete'" ;
		if( $postid )$w .= " AND post_id=" . $postid ;
		$sql = "SELECT A.*
					FROM
						((SELECT * FROM " . FCUtility::get_action_history_table_name() . " WHERE $w) AS A
						LEFT JOIN " . FCUtility::get_action_table_name() . " AS C
						ON A.ID = C.action_history_id)
					" ;
		$result = $wpdb->get_results( $sql ) ;
		return count($result);
	}

	static function get_step_name($row)
	{
		if( $row->action_status == "submitted" )return "submit" ;
		$info = $row->step_info;
		if( $info ){
			$stepinfo = json_decode( $info )	;
			if( $stepinfo )
				return $stepinfo->step_name ;
		}
		return "";
	}

	static function get_workflow_posts()
	{
		global $wpdb ;
		$sql = "SELECT DISTINCT(A.post_id) as wfpostid , B.post_title as title
					FROM " . FCUtility::get_action_history_table_name() . " AS A
						LEFT JOIN
						{$wpdb->posts} AS B
						ON  A.post_id = B.ID
					GROUP BY A.post_id" ;
		$result = $wpdb->get_results( $sql ) ;
		return $result;
	}

	static function get_signoff_date($row)
	{
		if( $row->action_status == "complete" || $row->action_status == "submitted") return $row->create_datetime ;
		if( $row->action_status == "claim_cancel" ){
			$claimed_row = FCWorkflowHistory::get_action_history("claimed", $row->step_id, $row->post_id, $row->from_id ) ;
			return $claimed_row->create_datetime ;
		}
		$action = FCWorkflowHistory::get_action_history_by_from_id( $row->ID );
		if( $action )
			return $action->create_datetime ;
	}

	static function get_review_actors($reaction)
	{
		$user = get_userdata( $reaction->actor_id ) ;
		return $user->data->display_name;
	}

	static function get_process_result($fromStep, $toStep)
	{
		$fromsteps = FCWorkflowHistory::get_process_steps($fromStep) ;
		if( $fromsteps && isset($fromsteps["success"]) && $fromsteps["success"] )
		{
			foreach ($fromsteps["success"] as $k => $v) {

				if( $k == $toStep )return "success" ;
			}
		}

		if( $fromsteps && $fromsteps["failure"] )
		{
			foreach ($fromsteps["failure"] as $k => $v) {
				if( $k == $toStep )return "failure" ;
			}
		}
	}

	static function get_signoff_status($row)
	{

		if( $row->action_status == "submitted" ) return __("Submitted","oasisworkflow") ;
		if( $row->action_status == "aborted" ) return __("Aborted","oasisworkflow") ;
		if( $row->action_status == "claim_cancel" )return __("Unclaimed","oasisworkflow") ;
		if( $row->action_status == "claimed" )return __("Claimed","oasisworkflow") ;
		if( $row->action_status == "reassigned" )return __("Reassigned","oasisworkflow") ;
		$nextHistory = FCWorkflowHistory::get_action_history_by_from_id( $row->ID ) ;
		if( !$nextHistory )return "";
		if( $nextHistory->action_status == "complete" ) return __("Workflow completed","oasisworkflow") ;
		if( $nextHistory->action_status == "cancelled" ) return __("Cancelled","oasisworkflow") ;
		$step_info = json_decode($row->step_info) ;
		$process = $step_info->process ;

		$fromStep = $row->step_id ;
		$toStep = $nextHistory->step_id ;
		$pro_result = FCWorkflowHistory::get_process_result($fromStep, $toStep) ;

		if( $process == "review" ){
			if($pro_result == "success")return __("Approved","oasisworkflow") ;
			if($pro_result == "failure")return __("Rejected","oasisworkflow") ;
		}
		if($pro_result == "success")return __("Completed","oasisworkflow") ;
		if($pro_result == "failure")return __("Unable to Complete","oasisworkflow") ;
	}

	static function get_review_signoff_status($row, $review_row)
	{
		if( $review_row->review_status == "reassigned" )return __("Reassigned","oasisworkflow") ;

		$fromStep = $row->step_id ;
		$toStep = $review_row->step_id ;
		if( !($fromStep &&  $toStep ))return "" ;
		$step_info = json_decode($row->step_info) ;
		$process = $step_info->process ;

		$pro_result = FCWorkflowHistory::get_process_result($fromStep, $toStep) ;

		if( $process == "review" ){
			if($pro_result == "success")return __("Approved","oasisworkflow") ;
			if($pro_result == "failure")return __("Rejected","oasisworkflow") ;
		}
		if($pro_result == "success")return __("Complete","oasisworkflow") ;
		if($pro_result == "failure")return __("Unable to Complete","oasisworkflow") ;

	}

	static function get_signoff_comment_count($row)
	{
		if( $row->action_status == "claimed" ||
		   $row->action_status == "claim_cancel" ||
		   $row->action_status == "reassigned" ||
		   $row->action_status == "complete" ){
		      return "0";
		   }
		$nextHistory = FCWorkflowHistory::get_action_history_by_from_id( $row->ID ) ;
		if (is_object($nextHistory))
		{
			return FCWorkflowHistory::get_comment_count($nextHistory->ID) ;
		}
		else return 0; // no comments found
	}

	static function get_review_signoff_comment_count($review_row)
	{
		$i = 0 ;

	   if($review_row->review_status == "reassigned") {
	      return $i ;
	   }

		if( $review_row ){
			$comments = json_decode($review_row->comments) ;
			if($comments){
				foreach ($comments as $comment) {
					if($comment->comment)$i++ ;
				}
			}
		}
		return $i ;
	}

	/*
	* This action handles download history report AJAX request
	* And returns generated CSV file path for download
	*/
	static function download_history_report()
	{

		$selected_post = isset( $_POST['post'] ) ? $_POST["post"] : "";
		$histories = self::get_workflow_history_all( $selected_post );
		$count_posts = self::get_history_count($selected_post);
		$data[] = array(__("Title","oasisworkflow"),
							 __("Actor","oasisworkflow"),
							 __("Workflow (version)","oasisworkflow"),
							 __("Step","oasisworkflow"),
							 __("Assigned Date","oasisworkflow"),
							 __("Signoff Date","oasisworkflow"),
							 __("Result","oasisworkflow"),
							 __("Comments","oasisworkflow"));

		if($histories):
   		$act = array("", "active");
   		foreach ($histories as $key=>$row) {
   		   $post_title = $row->post_title;
				$actor = parent::get_user_name($row->userid);
				$workflow = $row->wf_name;
   		   if (!empty( $row->version )) {
				   $workflow .= "(" . $row->version . ")";
				}
				$step = self::get_step_name($row);
				$assigned_date = parent::format_date_for_display( $row->create_datetime, '-', 'datetime' );
				$signoff_date = parent::format_date_for_display(self::get_signoff_date( $row ), '-', 'datetime' );
				$results = self::get_signoff_status( $row );
				$comments = self::get_comments($row, 'history');

				$data[] = array($post_title, $actor, $workflow, $step, $assigned_date, $signoff_date, $results, $comments);

				if(self::get_signoff_comment_count($row)==0 || $data[$key][7]=="") {
					if($key>0) {
					   $data[$key][7]=__("No comments found.","oasisworkflow");
					}
				}

   		   if( $row->assign_actor_id == -1 ) {
   				$review_rows = self::get_review_action_by_history_id($row->ID ) ;
   				if( $review_rows ) {
   					foreach ($review_rows as $review_row) {
         				$post_title = $row->post_title;
         				$actor = parent::get_user_name($review_row->actor_id);
         				$workflow = $row->wf_name."(".$row->version.")";
         				$step = self::get_step_name($row);
         				$assigned_date =parent::format_date_for_display( $row->create_datetime, '-', 'datetime' );
         				if( $review_row->review_status == "reassigned" ) {
   							$info = get_option("reassign_{$review_row->ID}") ;
   							$signoff_date = $info["sign_off_date"] ;
   						}
   						else
   						{
   							$signoff_date = $review_row->update_datetime ;
   						}
         				$signoff_date = self::format_date_for_display( $signoff_date, '-', 'datetime' );
         				$results = self::get_review_signoff_status( $row, $review_row );
         				$comments = self::get_comments($review_row, 'review');

         				$data[] = array($post_title, $actor, $workflow, $step, $assigned_date, $signoff_date, $results, $comments);

         				if(self::get_signoff_comment_count($row)==0 || $data[$key][7]=="") {
   					      if($key>0) {
   					         $data[$key][7]=__("No comments found.","oasisworkflow");
   					      }
   				      }
     					}
   				}
   		   }
		   }
		  endif;
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/

		$today = date("Ymd-His");
		$file_name = __("workflow-history-","oasisworkflow") . $today . ".csv";
		$handle = fopen(OASISWF_PATH.$file_name,"w");

		if(file_exists(OASISWF_PATH.$file_name))
		{
			foreach($data as $d)
			{
					fputcsv($handle,$d);
			}
			fclose($handle);

		}
		else
		{
			echo __("File not exist","oasisworkflow");
		}

		echo OASISWF_PATH.$file_name;
		exit();
	}

	// Get all comments related to specific  posts or users
	static function get_comments($row,$page_action="")
	{
		$action = parent::get_action_history_by_id( $row->ID) ;

		if($action && $action->comment != "")
		{
			$comments = json_decode($action->comment) ;
		}
		else
		{
			$comments = "";
		}

		if($action && $action->create_datetime != "")
		{
			$signoffdate = $action->create_datetime;
		}
		else
		{
			$signoffdate = "";
		}

		$content = "";
		if( $page_action != "" && $page_action == "history" )
		{
			$action = parent::get_action_history_by_from_id( $row->ID ) ;
			if( $action )
			{
				if($action->comment != "")
				{
					$comments = json_decode($action->comment);
				}
				else
				{
					$comments = "";
				}

			}
				if(!$comments)$comments = array();
		}

		if($page_action != "" && $page_action =="review" )
		{
			$signoffdate = "" ;
			$action = FCWorkflowInbox::get_review_action_by_id( $row->ID ) ;
			if( $action )
			{
				$comments = json_decode($action->comments) ;
				$signoffdate = $action->update_datetime ;
			}
			if(!$comments)$comments = array();
		}
		foreach($comments as $key=>$comment)
		{

			if($comment->send_id == "System")
			{
				$lbl = "System" ;
			}
			else
			{
				//$user = get_userdata( $comment->send_id ) ;
				//$lbl = $user->data->user_nicename ;
				$lbl = parent::get_user_name($comment->send_id) ;
			}

			 //$content[] = array($lbl, self::format_date_for_display($signoffdate, "-", "datetime"), nl2br($comment->comment));

			 //return only comments exclude user and date
			 if($key >= 0)
			 {
			 	$content .= nl2br($comment->comment);
			}
			 else
			 {
			 	$content .= nl2br($comment->comment)."\t";
			 }
		}
		return $content;
	}
}
?>