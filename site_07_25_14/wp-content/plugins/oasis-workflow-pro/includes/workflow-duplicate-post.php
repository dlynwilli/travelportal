<?php
/**
 * Allows post to be duplicated
 * @author Nugget Solutions, Inc
 *
 */

class FCWorkflowPostDuplicate {

   static function get_clone_post_link( $id = 0 ) {
   	if ( !$post = get_post( $id ) )
   	   return;

   	$action_name = "save_as_new_post_draft";
      $action = '?action='.$action_name.'&post='.$post->ID;

      return admin_url( "admin.php". $action );
   }

   static function new_draft_link_row($actions, $post) {
      $post_status = get_post_status($post->ID);
	  // show 'Make Revision' to selected post/page types only
	  $allowed_post_types = get_site_option('oasiswf_show_wfsettings_on_post_types');
      if ($post_status == 'publish' && in_array($post->post_type,  $allowed_post_types) ) {
   		$actions['edit_as_new_draft'] = '<a href="'. FCWorkflowPostDuplicate::get_clone_post_link( $post->ID ) .'" title="'
   		. esc_attr(__('Make Revision', "oasisworkflow"))
   		. '">' .  __('Make Revision', "oasisworkflow") . '</a>';
      }
		return $actions;
   }

   static function save_as_new_post_draft(){
   	$new_post_id = FCWorkflowPostDuplicate::save_as_new_post('draft');
   	wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
   }

   static function save_as_new_post_draft_ajax(){
   	$new_post_id = FCWorkflowPostDuplicate::save_as_new_post('draft');
		echo $new_post_id ;
		exit();
   }

   static function save_as_new_post($status = ''){
   	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'save_as_new_post_draft' == $_REQUEST['action'] ) ) ) {
   		wp_die(__('No post to copy has been supplied!', "oasisworkflow"));
   	}

   	// Get the original post
   	$id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
   	$post = get_post($id);

   	// Copy the post and insert it
   	if (isset($post) && $post!=null) {
   		$new_id = FCWorkflowPostDuplicate::create_copy($post, $status);

  			return $new_id;
   		exit;

   	} else {
   		$post_type_obj = get_post_type_object( $post->post_type );
   		wp_die(esc_attr(__('Copy failed, could not find original:', "oasisworkflow")) . ' ' . $id);
   	}
   }

   /**
    * Create a copy from a post
    */
   static function create_copy($post, $status = '', $parent_id = '') {

   	// We don't want to clone revisions
   	if ($post->post_type == 'revision') return;

   	$prefix = '';
   	$suffix = '';
      $new_post_author = '';
   	if ($post->post_type != 'attachment'){
   		$prefix = get_site_option('oasiswf_doc_revision_title_prefix');
   		$suffix = get_site_option('oasiswf_doc_revision_title_suffix');
   		if (!empty($prefix)) $prefix.= " ";
   		if (!empty($suffix)) $suffix = " ".$suffix;

   		// reset the status of the revision to draft
   		$status = 'draft';
   		$new_post_author = FCUtility::get_current_user()->ID;
   	}
   	else if ($post->post_type == 'attachment'){
   	   $new_post_author = $post->post_author;
   	}

   	$new_post = array(
   	'menu_order' => $post->menu_order,
   	'comment_status' => $post->comment_status,
   	'ping_status' => $post->ping_status,
   	'post_author' => $new_post_author,
   	'post_content' => $post->post_content,
   	'post_excerpt' => $post->post_excerpt,
   	'post_mime_type' => $post->post_mime_type,
   	'post_parent' => $new_post_parent = empty($parent_id)? $post->post_parent : $parent_id,
   	'post_password' => $post->post_password,
   	'post_status' => $new_post_status = (empty($status))? $post->post_status: $status,
   	'post_title' => $prefix.$post->post_title.$suffix,
   	'post_type' => $post->post_type,
   	);

   	$new_post_id = wp_insert_post($new_post);

   	// If you have written a plugin which uses non-WP database tables to save
   	// information about a post you can hook this action to dupe that data.
   	if ($post->post_type == 'page' || (function_exists('is_post_type_hierarchical') && is_post_type_hierarchical( $post->post_type )))
   	   do_action( 'owf_duplicate_page', $new_post_id, $post );
   	else
   	   do_action( 'owf_duplicate_post', $new_post_id, $post );

   	delete_post_meta($new_post_id, 'oasis_original');
   	add_post_meta($new_post_id, 'oasis_original', $post->ID);

   	return $new_post_id;
   }


   /*
    * Update published post
    */
   static function update_published_post( $revised_post, $parent_id = '' ) {
	   $original_post_id = get_post_meta($revised_post->ID, 'oasis_original', true);
      if (empty( $original_post_id )) {
         return; // we are probably dealing with an incorrect article
      }

   	if ($revised_post->post_type != 'attachment'){
   		$prefix = get_site_option('oasiswf_doc_revision_title_prefix');
   		$suffix = get_site_option('oasiswf_doc_revision_title_suffix');
   		if (!empty($prefix)) $prefix.= " ";
   		if (!empty($suffix)) $suffix = " ".$suffix;
	   }
	   $post_title = $revised_post->post_title;
      if (!empty ( $prefix )) {
	      $post_title = substr($post_title, strlen($prefix ));
      }
      if (!empty ( $suffix )) {
	      $post_title = substr($post_title, 0, (-1 * abs(strlen($suffix ))));
      }

   	$published_post = array(
   	   'ID' => $original_post_id,
      	'menu_order' => $revised_post->menu_order,
      	'comment_status' => $revised_post->comment_status,
      	'ping_status' => $revised_post->ping_status,
      	'post_content' => $revised_post->post_content,
      	'post_excerpt' => $revised_post->post_excerpt,
      	'post_mime_type' => $revised_post->post_mime_type,
      	'post_parent' => $new_post_parent = empty($parent_id)? $revised_post->post_parent : $parent_id,
      	'post_password' => $revised_post->post_password,
      	'post_title' => $post_title,
      	'post_type' => $revised_post->post_type,
   	);

   	wp_update_post($published_post);

   	if ($revised_post->post_type == 'page' || (function_exists('is_post_type_hierarchical') && is_post_type_hierarchical( $revised_post->post_type )))
   	   do_action( 'owf_update_published_page', $original_post_id, $revised_post );
   	else
   	   do_action( 'owf_update_published_post', $original_post_id, $revised_post );

   	// finally change the revised post status to usedrevision again.
   	FCWorkflowPostDuplicate::change_status_to_owf_status( $revised_post->ID, "usedrev" );
   }

   static function change_status_to_owf_status( $revised_post_id, $status ) {
   	// change the post status of the revised post to one of the owf defined status
   	$change_post_status = array (
   		'ID' => $revised_post_id,
   	   'post_status' => $status
   	);

      wp_update_post($change_post_status);

   }

   static function change_status_to_owf_status_ajax() {
      $post_id = $_POST['post_id'];
      $post_status = $_POST['post_status'];
      FCWorkflowPostDuplicate::change_status_to_owf_status($post_id, $post_status);
   }

   /**
    * Copy the meta information of a post to another post
    */
   static function copy_post_meta_info($new_id, $post) {
   	$post_meta_keys = get_post_custom_keys($post->ID);
   	if (empty($post_meta_keys)) return;

   	foreach ($post_meta_keys as $meta_key) {
   	   $meta_key_trim = trim($meta_key);
         if ( '_edit_lock' == $meta_key_trim
            || '_edit_last' == $meta_key_trim
            || strpos($meta_key_trim,'oasis') !== false ) //ignore keys like _edit_last, _edit_lock
            continue;
   		$meta_values = get_post_custom_values($meta_key, $post->ID);
   		foreach ($meta_values as $meta_value) {
   			$meta_value = maybe_unserialize($meta_value);
   			add_post_meta($new_id, $meta_key, $meta_value, false);
   		}
   	}
   }

   /**
    * Copy the meta information of the revised post back to the published post
    */
   static function update_post_meta_info($original_post_id, $revised_post) {
   	$post_meta_keys = get_post_custom_keys($revised_post->ID);
   	if (empty($post_meta_keys)) return;

   	foreach ($post_meta_keys as $meta_key) {
   	   $meta_key_trim = trim($meta_key);
         if ( '_edit_lock' == $meta_key_trim
            || '_edit_last' == $meta_key_trim
            || strpos($meta_key_trim,'oasis') !== false ) //ignore keys like _edit_last, _edit_lock
            continue;
   		$revised_meta_values = get_post_custom_values($meta_key, $revised_post->ID);
   		$original_meta_values = get_post_custom_values($meta_key, $original_post_id);

   		// find the bigger array of the two
   		$meta_values_count = count($revised_meta_values) > count($original_meta_values) ? count($revised_meta_values) : count($original_meta_values);

   		// loop through the meta values to find what's added, modified and deleted.
   		for($i = 0; $i < $meta_values_count; $i++) {
   		   $new_meta_value = "";
   		   // delete if the revised post doesn't have that key
   		   if (count($revised_meta_values) >= $i+1) {
   			   $new_meta_value = maybe_unserialize($revised_meta_values[$i]);
   		   }
   		   else {
   		      $old_meta_value = maybe_unserialize($original_meta_values[$i]);
   		      delete_post_meta($original_post_id, $meta_key, $old_meta_value);
   		      continue;
   		   }

   			// old meta values got updated, so simply update it
   			if (count($original_meta_values) >= $i+1) {
   			   $old_meta_value = maybe_unserialize($original_meta_values[$i]);
   			   update_post_meta($original_post_id, $meta_key, $new_meta_value, $old_meta_value);
   			}

   			// new meta values got added, so add it
   			if (count($original_meta_values) < $i+1) {
   			   add_post_meta($original_post_id, $meta_key, $new_meta_value);
   			}

   		}
   	}
   }

   /**
    * Copy the taxonomies of a post to another post
    */
   static function copy_post_copy_post_taxonomies($new_id, $post) {
   	global $wpdb;
   	if (isset($wpdb->terms)) {
   		// Clear default category (added by wp_insert_post)
   		wp_set_object_terms( $new_id, NULL, 'category' );

   		$post_taxonomies = get_object_taxonomies($post->post_type);
   		foreach ($post_taxonomies as $taxonomy) {
   			$post_terms = wp_get_object_terms($post->ID, $taxonomy, array( 'orderby' => 'term_order' ));
   			$terms = array();
   			for ($i=0; $i<count($post_terms); $i++) {
   				$terms[] = $post_terms[$i]->slug;
   			}
   			wp_set_object_terms($new_id, $terms, $taxonomy);
   		}
   	}
   }

   /**
    * Copy the attachments
    * It simply copies the table entries, actual file won't be duplicated
    */
   static function copy_post_copy_children($new_id, $post){
   	// get children
   	$children = get_posts(array( 'post_type' => 'any', 'numberposts' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ));
   	// clone old attachments
   	foreach($children as $child){
   		FCWorkflowPostDuplicate::create_copy($child, '', $new_id);
   	}
   }

   /**
    * Copy the attachments
    * It simply copies the table entries, actual file won't be duplicated
    */
   static function update_post_update_children($new_id, $post){
   	// get children
   	$children = get_posts(array( 'post_type' => 'any', 'numberposts' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ));
   	// clone old attachments
   	foreach($children as $child){
   		FCWorkflowPostDuplicate::update_published_post($child, $new_id);
   	}
   }
}

?>