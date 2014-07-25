<?php
class FCWorkflowSubmission extends FCWorkflowBase
{
	static function get_table_header($action)
	{
		echo "<tr>";
		if($action == 'in-workflow') :
		echo "<th style='width: 35px;'><input type='checkbox' name='abort-all'  /></th>";
		endif;
		echo "<th>" . __("Title") . "</th>";
		echo "<th class='column-role'>" . __("Type") . "</th>";
		echo "<th class='column-role'>" . __("Author") . "</th>";
		echo "<th class='column-role'>" . __("Date") . "</th>";
		echo "</tr>";
	}

	static function get_assigned_allposts()
	{
		global $wpdb ;
		$data = "" ;
		$sql = "SELECT DISTINCT(A.post_id) as post_id FROM
							(SELECT * FROM " . FCUtility::get_action_history_table_name() . " WHERE action_status = 'assignment') as A
							LEFT OUTER JOIN
							(SELECT * FROM " . FCUtility::get_action_table_name() . " WHERE review_status = 'assignment') as B
							ON A.ID = B.action_history_id order by A.due_date" ;
		$assign_posts = $wpdb->get_results($sql) ;
		if( $assign_posts ){
			foreach ($assign_posts as $post) {
				$data[] = $post->post_id ;
			}
		}
		return $data ;
	}

	static function get_all_posts($activate = 'all', $type = 'all')
	{
		global $wpdb ;
		$assign_post_ids = FCWorkflowSubmission::get_assigned_allposts() ;
		$assign_post_ids = ( $assign_post_ids ) ? $assign_post_ids : array(-1) ;

		if( $activate == 'all' ){
			$w = "(post_status='publish' OR ID IN (" . implode($assign_post_ids, ",") . "))" ;
		}

		if($activate == 'in-workflow'){
			$w = "ID IN (" . implode($assign_post_ids, ",") . ")" ;
		}

		if($activate == 'not-workflow'){
			$w = "post_status='publish' AND ID NOT IN (" . implode($assign_post_ids, ",") . ")" ;
		}

		if( $type <> "all" ){
			$w .= " AND post_type='" . $type . "'" ;
		}

		$sql = "SELECT * FROM " . $wpdb->posts . " WHERE {$w} ORDER BY ID DESC" ;
		$results = $wpdb->get_results($sql) ;
		return $results ;
	}

	static function get_submit_articles($type = 'all')
	{
		global $wpdb;
		$add_query = ($type == "all") ? "" : "post_type='{$type}' AND " ;

		$assign_post_ids = FCWorkflowSubmission::get_assigned_allposts() ;
		$assign_post_ids = ( $assign_post_ids ) ? $assign_post_ids : array(-1) ;

		$sql = "SELECT posts.ID, posts.post_author, posts.post_title, posts.post_type, posts.post_date FROM " . $wpdb->posts . " as posts WHERE {$add_query}ID IN (" . implode($assign_post_ids, ",") . ") ORDER BY ID DESC" ;
		$submited_posts = $wpdb->get_results($sql) ;
		return $submited_posts ;
	}

	static function get_unsubmit_articles($type = 'all')
	{
		global $wpdb;
		$add_query = ($type == "all") ? "" : "post_type='{$type}' AND " ;

		foreach ( get_post_stati(array('show_in_admin_status_list' => true)) as $key => $status ) {
		   if ($status != 'publish' && $status != 'trash') { //not published
	         $auto_submit_stati[$key] = "'" . mysql_real_escape_string($status) . "'";
		   }
	   }
	   $auto_submit_stati_list = join("," , $auto_submit_stati);

   	// get all posts which are not published and are not in workflow
   	$unsubmitted_posts = $wpdb->get_results( "SELECT distinct posts.ID, posts.post_author, posts.post_title, posts.post_type, posts.post_date FROM {$wpdb->prefix}posts posts
   	WHERE {$add_query}posts.post_status in (" . $auto_submit_stati_list . ")
   	AND
   	(NOT EXISTS (SELECT * from {$wpdb->prefix}postmeta postmeta1 WHERE postmeta1.meta_key = 'oasis_is_in_workflow' and posts.ID = postmeta1.post_id) OR
   	EXISTS (SELECT * from {$wpdb->prefix}postmeta postmeta2 WHERE postmeta2.meta_key = 'oasis_is_in_workflow' AND postmeta2.meta_value = '0' and posts.ID = postmeta2.post_id))
   	order by post_modified_gmt" );
   	return $unsubmitted_posts ;
	}
}
?>