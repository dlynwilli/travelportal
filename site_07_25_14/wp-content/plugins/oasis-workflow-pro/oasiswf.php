<?php
/*
 Plugin Name: Oasis Workflow Pro
 Plugin URI: http://www.oasisworkflow.com
 Description: Easily create graphical workflows to manage your work.
 Version: 1.0.6
 Author: Nugget Solutions Inc.
 Author URI: http://www.nuggetsolutions.com
 Text Domain: oasis-workflow
----------------------------------------------------------------------
Copyright 2011-2013 Nugget Solutions Inc.
*/


//Install, activate, deactivate and uninstall

define( 'OASISWF_VERSION' , '1.0.6' );
define( 'OASISWF_DB_VERSION','1.0.6');
define( 'OASISWF_PATH', plugin_dir_path(__FILE__) ); //use for include files to other files
define( 'OASISWF_ROOT' , dirname(__FILE__) );
define( 'OASISWF_FILE_PATH' , OASISWF_ROOT . '/' . basename(__FILE__) );
define( 'OASISWF_URL' , plugins_url( '/', __FILE__ ) );
define( 'OASISWF_SETTINGS_PAGE' , add_query_arg( 'page', 'ef-settings', get_admin_url( null, 'admin.php' ) ) );
define( 'OASISWF_STORE_URL', 'http://www.oasisworkflow.com/' );
define( 'OASISWF_PRODUCT_NAME', 'Oasis Workflow Pro' );
load_plugin_textdomain('oasisworkflow', false, basename( dirname( __FILE__ ) ) . '/languages' );

//Initialization
class FCInitialization
{
	function  __construct()
	{
		//run on activation of plugin
		register_activation_hook( __FILE__, array('FCInitialization', 'oasiswf_activate') );

		//run on deactivation of plugin
		register_deactivation_hook( __FILE__, array('FCInitialization', 'oasiswf_deactivate') );

		//run on uninstall
		register_uninstall_hook(__FILE__, array('FCInitialization', 'oasiswf_uninstall') );

	}

	static function oasiswf_activate( $networkwide )
	{
		global $wpdb;
		FCInitialization::run_on_activation();
		if (function_exists('is_multisite') && is_multisite())
		{
	        // check if it is a network activation - if so, run the activation function for each blog id
	        if ($networkwide)
	        {
               // Get all blog ids
	            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
	            foreach ($blogids as $blog_id)
	            {
	            	switch_to_blog($blog_id);
	               FCInitialization::run_for_site();
	               restore_current_blog();
	            }
	            return;
	        }
    	}

    	// for non-network sites only
    	FCInitialization::install_site_database();
	}

	static function oasiswf_deactivate($networkwide)
	{
	    global $wpdb;

	    if (function_exists('is_multisite') && is_multisite())
	    {
	        // check if it is a network activation - if so, run the activation function for each blog id
	        if ($networkwide)
	        {
	            // Get all blog ids
	            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");
	            foreach ($blogids as $blog_id)
	            {
	                switch_to_blog($blog_id);
	                FCInitialization::run_on_deactivation();
	                restore_current_blog();
	            }
	            return;
	        }
	    }
	    FCInitialization::run_on_deactivation();
	}

	static function oasiswf_uninstall()
	{
		global $wpdb;
		FCInitialization::run_on_uninstall();
		if (function_exists('is_multisite') && is_multisite())
		{
			//Get all blog ids; foreach them and call the uninstall procedure on each of them
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->base_prefix}blogs");

			//Get all blog ids; foreach them and call the install procedure on each of them if the plugin table is found
			foreach ( $blog_ids as $blog_id )
			{
				switch_to_blog( $blog_id );
				if( $wpdb->query( "SHOW TABLES FROM ".$wpdb->dbname." LIKE '".$wpdb->prefix."fc_%'" ) )
				{
					FCInitialization::delete_for_site();
				}
				restore_current_blog();
			}
			return;
		}
		FCInitialization::delete_for_site();
	}

	static function run_on_activation()
	{
		$pluginOptions = get_site_option('oasiswf_info');
		if ( false === $pluginOptions )
		{
			$oasiswf_info=array(
				'version'=>OASISWF_VERSION,
				'db_version'=>OASISWF_DB_VERSION
			);

			$oasiswf_process_info = array(
				'assignment' => OASISWF_URL . 'img/assignment.gif',
				'review' => OASISWF_URL . 'img/review.gif',
				'publish' => OASISWF_URL . 'img/publish.gif'
			);

			$oasiswf_path_info = array(
				'success' => array(__('Success','oasisworkflow'), 'blue'),
				'failure' => array(__('Failure','oasisworkflow'), 'red')
			);

			$oasiswf_status = array(
				'assignment' => __('In Progress', "oasisworkflow"),
				'review' => __('In Review', "oasisworkflow"),
				'publish' => __('Ready to Publish', "oasisworkflow")
			);
			$oasiswf_review_decision = array(
				'everyone' => __('Everyone should approve', "oasisworkflow"),
				'atleast' => __('Atleast 50% should approve', "oasisworkflow"),
				'more' => __('More that 50% should approve', "oasisworkflow"),
				'anyone' => __('Anyone should approve', "oasisworkflow"),
			);

			$oasiswf_placeholders = array(
				'%first_name%' => __('first name', "oasisworkflow"),
				'%last_name%' => __('last name', "oasisworkflow"),
				'%post_title%' => __('post title', "oasisworkflow"),
				'%category%' => __('category', "oasisworkflow"),
				'%last_modified_date%' => __('last modified date', "oasisworkflow")
			);

			$auto_submit_stati = array('draft');

         $auto_submit_settings = array(
            'auto_submit_stati' => $auto_submit_stati,
            'auto_submit_due_days' => '1',
            'auto_submit_comment' => '',
            'auto_submit_post_count' => '10',
            'auto_submit_enable' => false
         );

		  // default post published notification setting
		  $post_published_notification = "active";

	      $skip_workflow_roles = array("administrator");

		   $show_wfsettings_on_post_types = array('post', 'page');

         FCInitialization::install_admin_database();
			update_site_option('oasiswf_info', $oasiswf_info) ;
			update_site_option('oasiswf_process', $oasiswf_process_info) ;
			update_site_option('oasiswf_path', $oasiswf_path_info) ;
			update_site_option('oasiswf_status', $oasiswf_status) ;
			update_site_option('oasiswf_review', $oasiswf_review_decision) ;
			update_site_option('oasiswf_placeholders', $oasiswf_placeholders) ;
    		update_site_option("oasiswf_post_published_notification", $post_published_notification) ;
	      update_site_option("oasiswf_skip_workflow_roles", $skip_workflow_roles) ;

		   update_site_option("oasiswf_show_wfsettings_on_post_types", $show_wfsettings_on_post_types) ;
         update_site_option("oasiswf_auto_submit_settings", $auto_submit_settings) ;

         // document revision site options
	      update_site_option("oasiswf_doc_revision_title_prefix", __('Copy of -', "oasisworkflow")) ;
	      update_site_option("oasiswf_doc_revision_title_suffix", '') ;
		}
		else if ( OASISWF_VERSION != $pluginOptions['version'] )
		{
		   FCInitialization::run_on_upgrade();
		}

		if ( !wp_next_scheduled('oasiswf_email_schedule') )
			wp_schedule_event(time(), 'daily', 'oasiswf_email_schedule');

		if ( !wp_next_scheduled('oasiswf_auto_submit_schedule') )
			wp_schedule_event(time(), 'hourly', 'oasiswf_auto_submit_schedule');

	}

	static function run_for_site( )
	{
	   FCInitialization::install_site_database();
	}

	static function run_on_upgrade( )
	{
	   $pluginOptions = get_site_option('oasiswf_info');
		if ($pluginOptions['version'] == "1.0.0")
		{
		   FCInitialization::upgrade_database_105();
		   FCInitialization::upgrade_database_106();
		}
		else if ($pluginOptions['version'] == "1.0.1")
		{
		   FCInitialization::upgrade_database_105();
		   FCInitialization::upgrade_database_106();
		}
		else if ($pluginOptions['version'] == "1.0.2")
		{
		   FCInitialization::upgrade_database_105();
		   FCInitialization::upgrade_database_106();
		}
		else if ($pluginOptions['version'] == "1.0.3")
		{
		   FCInitialization::upgrade_database_105();
		   FCInitialization::upgrade_database_106();
		}
		else if ($pluginOptions['version'] == "1.0.4")
		{
		   FCInitialization::upgrade_database_105();
		   FCInitialization::upgrade_database_106();
		}
		else if ($pluginOptions['version'] == "1.0.5")
		{
		   FCInitialization::upgrade_database_106();
		}
		// update the version value
		$oasiswf_info=array(
			'version'=>OASISWF_VERSION,
			'db_version'=>OASISWF_DB_VERSION
		);
		update_site_option('oasiswf_info', $oasiswf_info) ;
	}

	static function run_on_uninstall()
	{
		if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
			exit();

		// deactivate the license
		$license = trim( get_site_option( 'oasiswf_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( OASISWF_PRODUCT_NAME ) // the name of our product in EDD
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, OASISWF_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		global $wpdb;	//required global declaration of WP variable
		delete_site_option('oasiswf_activate_workflow');
		delete_site_option('oasiswf_info');
		delete_site_option('oasiswf_process');
		delete_site_option('oasiswf_path');
		delete_site_option('oasiswf_status');
		delete_site_option('oasiswf_review');
		delete_site_option('oasiswf_placeholders');
	   delete_site_option('oasiswf_doc_revision_title_prefix' );
	   delete_site_option('oasiswf_doc_revision_title_suffix' );
		if (get_site_option('oasiswf_reminder_days')) {
		   delete_site_option('oasiswf_reminder_days');
		}

		if (get_site_option('oasiswf_post_published_notification')) {
		   delete_site_option('oasiswf_post_published_notification');
		}

		if (get_site_option('oasiswf_skip_workflow_roles')) {
		   delete_site_option('oasiswf_skip_workflow_roles');
		}

		if (get_site_option('oasiswf_show_wfsettings_on_post_types')) {
		   delete_site_option('oasiswf_show_wfsettings_on_post_types');
		}

		if (get_site_option('oasiswf_reminder_days_after')) {
		   delete_site_option('oasiswf_reminder_days_after');
		}

		if (get_option('oasiswf_auto_submit_settings')) {
		   delete_site_option('oasiswf_auto_submit_settings');
		}

		if (get_site_option('oasiswf_license_status')) {
		   delete_site_option( 'oasiswf_license_status' );
		}

		if (get_site_option('oasiswf_license_key')) {
		   delete_site_option( 'oasiswf_license_key' );
		}

		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name like 'workflow_%'") ;
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_workflows_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_workflow_steps_table_name());

	}

	static function delete_for_site( )
	{
	   global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_emails_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_action_history_table_name());
		$wpdb->query("DROP TABLE IF EXISTS " . FCUtility::get_action_table_name());
	}

	static function run_on_add_blog($blog_id, $user_id, $domain, $path, $site_id, $meta )
	{
	    global $wpdb;
	    if (is_plugin_active_for_network(basename( dirname( __FILE__ )) . '/oasiswf.php'))
	    {
	        $old_blog = $wpdb->blogid;
	        switch_to_blog($blog_id);
	        FCInitialization::run_for_site();
	        restore_current_blog();
	    }
	}

	static function run_on_delete_blog($blog_id, $drop )
	{
		global $wpdb;
      switch_to_blog($blog_id);
		FCInitialization::delete_for_site();
	   restore_current_blog();
	}

	static function upgrade_database_105()
	{
	   global $wpdb;

       //fc_workflows table alter
      $table_name = FCUtility::get_workflows_table_name();
      $wpdb->query("ALTER TABLE {$table_name} MODIFY start_date DATE");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY end_date DATE");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY wf_info longtext");

      //fc_workflow_steps table alter
      $table_name = FCUtility::get_workflow_steps_table_name();
      $wpdb->query("ALTER TABLE {$table_name} MODIFY create_datetime datetime");
      $wpdb->query("ALTER TABLE {$table_name} MODIFY update_datetime datetime");

	}

	static function upgrade_database_106()
	{
	   global $wpdb;

       //fc_workflows table alter, add new column "wf_additional_info" at end
      $table_name = FCUtility::get_workflows_table_name();
      $wpdb->query("ALTER TABLE {$table_name} ADD wf_additional_info mediumtext");
		$oasiswf_placeholders = array(
				'%first_name%' => __('first name', "oasisworkflow"),
				'%last_name%' => __('last name', "oasisworkflow"),
				'%post_title%' => __('post title', "oasisworkflow"),
				'%category%' => __('category', "oasisworkflow"),
				'%last_modified_date%' => __('last modified date', "oasisworkflow")
			);
		$show_wfsettings_on_post_types = array('post', 'page');
		 $post_published_notification = "active";

		update_site_option('oasiswf_placeholders', $oasiswf_placeholders) ;
	    update_site_option("oasiswf_show_wfsettings_on_post_types", $show_wfsettings_on_post_types) ;
		update_site_option("oasiswf_post_published_notification", $post_published_notification) ;
		// seed default values to new added field
		$additional_info = stripcslashes( 'a:2:{s:16:"wf_for_new_posts";i:1;s:20:"wf_for_revised_posts";i:1;}' );
		$wpdb->query( "UPDATE {$table_name} SET wf_additional_info = '" . $additional_info . "'" );
	}

	static function install_admin_database()
	{
		global $wpdb;
		if (!empty ($wpdb->charset))
        	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
        	$charset_collate .= " COLLATE {$wpdb->collate}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //fc_workflows table
		$table_name = FCUtility::get_workflows_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			      `ID` int(11) NOT NULL AUTO_INCREMENT,
			      `name` varchar(200) NOT NULL,
			      `description` mediumtext,
			      `wf_info` longtext,
			      `version` int(3) NOT NULL default 1,
			      `parent_id` int(11) NOT NULL default 0,
			      `start_date` date DEFAULT NULL,
			      `end_date` date DEFAULT NULL,
			      `is_auto_submit` int(2) NOT NULL default 0,
			      `auto_submit_keywords` mediumtext,
			      `is_valid` int(2) NOT NULL default 0,
			      `create_datetime` datetime DEFAULT NULL,
			      `update_datetime` datetime DEFAULT NULL,
				   `wf_additional_info` mediumtext DEFAULT NULL,
			      PRIMARY KEY (`ID`)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_workflow_steps table
		$table_name = FCUtility::get_workflow_steps_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			      `ID` int(11) NOT NULL AUTO_INCREMENT,
			      `step_info` text NOT NULL,
			      `process_info` longtext NOT NULL,
			      `workflow_id` int(11) NOT NULL,
			      `create_datetime` datetime DEFAULT NULL,
			      `update_datetime` datetime DEFAULT NULL,
			      PRIMARY KEY (`ID`),
			      KEY `workflow_id` (`workflow_id`)
	    		){$charset_collate};";
			dbDelta($sql);
		}

		FCInitialization::install_admin_data();
	}

	static function install_site_database()
	{
		global $wpdb;
		if (!empty ($wpdb->charset))
        	$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
        	$charset_collate .= " COLLATE {$wpdb->collate}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        //fc_emails table
		$table_name = FCUtility::get_emails_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
		   // action - 1 indicates not send, 0 indicates email sent
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    `ID` int(11) NOT NULL AUTO_INCREMENT,
			    `subject` mediumtext,
			    `message` mediumtext,
			    `from_user` int(11),
			    `to_user` int(11),
			    `action` int(2) DEFAULT 1,
			    `history_id` int(11),
			    `send_date` date DEFAULT NULL,
			    `create_datetime` datetime DEFAULT NULL,
			    PRIMARY KEY (`ID`)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_action_history table
		$table_name = FCUtility::get_action_history_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    `ID` int(11) NOT NULL AUTO_INCREMENT,
			    `action_status` varchar(20) NOT NULL,
			    `comment` longtext NOT NULL,
			    `step_id` int(11) NOT NULL,
			    `assign_actor_id` int(11) NOT NULL,
			    `post_id` int(11) NOT NULL,
			    `from_id` int(11) NOT NULL,
			    `due_date` date DEFAULT NULL,
			    `reminder_date` date DEFAULT NULL,
			    `reminder_date_after` date DEFAULT NULL,
			    `create_datetime` datetime NOT NULL,
			    PRIMARY KEY (`ID`)
	    		){$charset_collate};";
			dbDelta($sql);
		}
        //fc_action table
		$table_name = FCUtility::get_action_table_name();
		if ($wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'") != $table_name)
		{
			$sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
			    `ID` int(11) NOT NULL AUTO_INCREMENT,
			    `review_status` varchar(20) NOT NULL,
			    `actor_id` int(11) NOT NULL,
			    `reassign_actor_id` int(11) NOT NULL,
			    `step_id` int(11) NOT NULL,
			    `comments` mediumtext,
			    `due_date` date DEFAULT NULL,
			    `action_history_id` int(11) NOT NULL,
			    `update_datetime` datetime NOT NULL,
			    PRIMARY KEY (`ID`)
	    		){$charset_collate};";
			dbDelta($sql);

		}

   }

	static function install_admin_data()
	{
	    global $wpdb;

	    // insert into workflow table
	    $table_name = FCUtility::get_workflows_table_name();
	    $workflow_id = '';
       $workflow_info = stripcslashes('{"steps":{"step0":{"fc_addid":"step0","fc_label":"assignment","fc_dbid":"2","fc_process":"assignment","fc_position":["326px","568px"]},"step1":{"fc_addid":"step1","fc_label":"review","fc_dbid":"1","fc_process":"review","fc_position":["250px","358px"]},"step2":{"fc_addid":"step2","fc_label":"publish","fc_dbid":"3","fc_process":"publish","fc_position":["119px","622px"]}},"conns":{"0":{"sourceId":"step1","targetId":"step2","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"blue"}}},"1":{"sourceId":"step2","targetId":"step1","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"2":{"sourceId":"step1","targetId":"step0","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"3":{"sourceId":"step2","targetId":"step0","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"red"}}},"4":{"sourceId":"step0","targetId":"step1","connset":{"connector":"StateMachine","paintStyle":{"lineWidth":3,"strokeStyle":"blue"}}}},"first_step":["step1"]}');
	   $additional_info = stripcslashes( 'a:2:{s:16:"wf_for_new_posts";i:1;s:20:"wf_for_revised_posts";i:1;}' );
		 $data = array(
					'name' => 'Test Workflow',
					'description' => 'sample workflow',
		         'wf_info' => $workflow_info,
		         'start_date' => date('Y-m-d'),
		         'end_date' => date('Y-m-d', strtotime('+1 years')),
		         'is_valid' => 1,
					'create_datetime' => current_time('mysql'),
		 			'update_datetime' => current_time('mysql'),
					 'wf_additional_info' => $additional_info
				);
		 $result = $wpdb->insert($table_name, $data);
		 if( $result ){

			$row = $wpdb->get_row("SELECT max(ID) as maxid FROM $table_name");
			if($row)
				$workflow_id = $row->maxid ;
			else
				return false;
		 }else{
			return false;
		 }

		 // insert steps
		 $workflow_step_table = FCUtility::get_workflow_steps_table_name();

	    // step 1 - review
	    $review_step_info = '{"process":"review","step_name":"review","assignee":{"editor":"Editor"},"status":"pending","failure_status":"draft"}';
	    $review_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $review_step_info ),
						 'process_info' => stripcslashes( $review_process_info ),
						 'create_datetime' => current_time('mysql'),
					    'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );

	    // step 2 - assignment
	    $assignment_step_info = '{"process":"assignment","step_name":"assignment","assignee":{"author":"Author"},"status":"pending","failure_status":"draft"}';
	    $assignment_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $assignment_step_info ),
						 'process_info' => stripcslashes( $assignment_process_info ),
						 'create_datetime' => current_time('mysql'),
					    'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );

	    // step 3 - publish
	    $publish_step_info = '{"process":"publish","step_name":"publish","assignee":{"administrator":"Administrator"},"status":"publish","failure_status":"draft"}';
	    $publish_process_info = '{"assign_subject":"","assign_content":"","reminder_subject":"","reminder_content":""}';
		 $result = $wpdb->insert(
					  $workflow_step_table,
					  array(
						 'step_info' => stripcslashes( $publish_step_info ),
						 'process_info' => stripcslashes( $publish_process_info ),
						 'create_datetime' => current_time('mysql'),
					    'update_datetime' => current_time('mysql'),
						 'workflow_id' => $workflow_id
					 )
			   );
	}


	static function run_on_deactivation()
	{
		/*
		 * Mail schedule remove
		 */
		wp_clear_scheduled_hook( 'oasiswf_email_schedule' );

		/*
		 * Auto Submit schedule remove
		 */
		wp_clear_scheduled_hook( 'oasiswf_auto_submit_schedule' );
	}

}

$initialization=new FCInitialization();
/**
 *
 * workflow create / edit
 *
 */
class FCLoadWorkflow
{
	function __construct()
	{
		//init: Runs after WordPress has finished loading but before any headers are send. It is used before sending data send to browser.
		add_action('init', array('FCLoadWorkflow', 'init_oasiswf'));
		add_action('admin_menu',  array('FCLoadWorkflow', 'register_menu_pages'));
		add_action('network_admin_menu',  array('FCLoadWorkflow', 'register_network_admin_menu_pages'));
	}

	static function init_oasiswf()
	{
	   FCInitialization::run_on_upgrade();
		require_once( OASISWF_PATH . "includes/workflow-base.php" ) ;
   	register_post_status( 'usedrev', array(
   		'label'                     => __( 'Used Revision', 'post' ),
   		'public'                    => false,
   		'exclude_from_search'       => true,
   		'show_in_admin_all_list'    => false,
   		'show_in_admin_status_list' => false
   	) );

   	register_post_status( 'currentrev', array(
   		'label'                     => __( 'Current Revision', 'post' ),
   		'public'                    => false,
   		'exclude_from_search'       => true,
   		'show_in_admin_all_list'    => false,
   		'show_in_admin_status_list' => false
   	) );
	}

	static function load_step_info()
	{
      require_once( OASISWF_PATH . "includes/pages/subpages/step-info-content.php" );
	}

   static function register_network_admin_menu_pages()
   {
      FCLoadWorkflow::register_admin_menu_pages();

	   add_action('admin_print_styles', array('FCLoadWorkflow', 'add_css_files'));
		add_action('admin_print_scripts', array('FCLoadWorkflow', 'add_js_files'));
		add_action('admin_footer', array('FCLoadWorkflow', 'load_js_files_footer'));
   }

	static function register_admin_menu_pages()
	{
		 add_menu_page(__('Workflow Admin', 'oasisworkflow'),
					  	__('Workflow Admin', 'oasisworkflow'),
						'activate_plugins',
						'oasiswf-admin',
						array('FCLoadWorkflow','list_workflows'),
						'');

	    add_submenu_page('oasiswf-admin',
	    				__('Edit Workflows', 'oasisworkflow'),
	    				__('Edit Workflows', 'oasisworkflow'),
	    				'activate_plugins',
	    				'oasiswf-admin',
	    				array('FCLoadWorkflow','list_workflows'));

	    add_submenu_page('oasiswf-admin',
	    				__('New Workflow', 'oasisworkflow'),
	    				__('New Workflow', 'oasisworkflow'),
	    				'activate_plugins',
	    				'oasiswf-add',
	    				array('FCLoadWorkflow','create_workflow'));

	    add_submenu_page('oasiswf-admin',
	    				__('Reports', 'oasisworkflow'),
	    				__('Reports', 'oasisworkflow'),
	    				'activate_plugins',
	    				'oasiswf-reports',
	    				array('FCLoadWorkflow','workflow_reports'));

	    add_submenu_page('oasiswf-admin',
	    				__('Settings', 'oasisworkflow'),
	    				__('Settings', 'oasisworkflow'),
	    				'activate_plugins',
	    				'oasiswf-setting',
	    				array('FCLoadWorkflow','workflow_settings'));
	}

	static function register_menu_pages()
	{
		$current_role = FCWorkflowBase::get_current_user_role() ;
		$position = FCWorkflowBase::get_menu_position() ;

		$inbox_count = FCWorkflowBase::get_count_assigned_post() ;
		$count = ($inbox_count) ? '<span class="update-plugins count"><span class="plugin-count">' . $inbox_count . '</span></span>' : '' ;

		if (!is_multisite())
		{
		   FCLoadWorkflow::register_admin_menu_pages();
		}

		add_menu_page(__('Workflows', 'oasisworkflow'),
						__('Workflows'. $count, 'oasisworkflow'),
						$current_role,
						'oasiswf-inbox',
						array('FCLoadWorkflow','workflow_inbox'),'', $position);

		 add_submenu_page('oasiswf-inbox',
	    				__('Inbox', 'oasisworkflow'),
	    				__('Inbox'. $count, 'oasisworkflow'),
	    				$current_role,
	    				'oasiswf-inbox',
	    				array('FCLoadWorkflow','workflow_inbox'));

		add_submenu_page('oasiswf-inbox',
						__('Workflow History', 'oasisworkflow'),
						__('Workflow History', 'oasisworkflow'),
						$current_role,
						'oasiswf-history',
						array('FCLoadWorkflow','workflow_history'));


	   add_action('admin_print_styles', array('FCLoadWorkflow', 'add_css_files'));
		add_action('admin_print_scripts', array('FCLoadWorkflow', 'add_js_files'));
		add_action('admin_footer', array('FCLoadWorkflow', 'load_js_files_footer'));
	}

	static function create_workflow()
	{
		$create_workflow = new FCWorkflowCRUD() ;
		include( OASISWF_PATH . "includes/pages/workflow-create.php" ) ;
	}

	static function list_workflows()
	{
		if(isset($_GET['wf_id']) && $_GET["wf_id"]){
			FCLoadWorkflow::create_workflow() ;
		}else{
			$list_workflow = new FCWorkflowList() ;
			include( OASISWF_PATH . "includes/pages/workflow-list.php" ) ;
		}
	}

	static function workflow_inbox()
	{
		$inbox_workflow = new FCWorkflowInbox() ;
		include( OASISWF_PATH . "includes/pages/workflow-inbox.php" ) ;
	}

	static function workflow_history()
	{
		$history_workflow = new FCWorkflowHistory() ;
		include( OASISWF_PATH . "includes/pages/workflow-history.php" ) ;
	}

	static function workflow_settings()
	{
		include( OASISWF_PATH . "includes/pages/settings.php" ) ;
	}

	static function workflow_reports()
	{
		include( OASISWF_PATH . "includes/pages/workflow-reports.php" ) ;
	}

	static function add_css_files($page)
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
      	wp_enqueue_style( 'thickbox' );
   	   wp_enqueue_style( 'owf-css',
      	                   OASISWF_URL. 'css/pages/context-menu.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-modal-css',
      	                   OASISWF_URL. 'css/lib/modal/simple-modal.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-calendar-css',
      	                   OASISWF_URL. 'css/lib/calendar/datepicker.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');

   	   wp_enqueue_style( 'owf-oasis-workflow-css',
      	                   OASISWF_URL. 'css/pages/oasis-workflow.css',
      	                   false,
      	                   OASISWF_VERSION,
                            'all');
	   }
	}

	static function add_js_files()
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
   		echo "<script type='text/javascript'>
   					var wf_structure_data = '' ;
   					var wfeditable = '' ;
   					var wfPluginUrl  = '" . OASISWF_URL . "' ;
   				</script>";
	   }

		if( is_admin() && isset($_GET['page']) && ($_GET["page"] == "oasiswf-inbox" ||
		      $_GET["page"] == "oasiswf-history" ))
		{
			wp_enqueue_script( 'owf-workflow-inbox',
			                   OASISWF_URL. 'js/pages/workflow-inbox.js',
			                   array('jquery'),
			                   OASISWF_VERSION);

		}
	}

	static function load_js_files_footer()
	{
	   // ONLY load OWF scripts on OWF plugin pages
	   if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
   		wp_enqueue_script( 'thickbox' );
   		wp_enqueue_script( 'jquery-ui-core' ) ;
   		wp_enqueue_script( 'jquery-ui-widget' ) ;
   		wp_enqueue_script( 'jquery-ui-mouse' ) ;
   		wp_enqueue_script( 'jquery-ui-sortable' ) ;
   		wp_enqueue_script( 'jquery-ui-datepicker' ) ;
   		wp_enqueue_script( 'jquery-json',
   		                   OASISWF_URL. 'js/lib/jquery.json.js',
   		                   '',
   		                   '2.3',
   		                   true);

   		wp_enqueue_script( 'jquery-ui-draggable' ) ;
   		wp_enqueue_script( 'jquery-ui-droppable' ) ;
	   }

		if(is_admin() && ( isset($_GET['page']) && ($_GET["page"] == "oasiswf-admin"  || $_GET["page"] == "oasiswf-add")) ||
		   (isset($_GET['oasiswf']) && $_GET["oasiswf"] ))
		{
   		wp_enqueue_script( 'jsPlumb',
   		                   OASISWF_URL. 'js/lib/jquery.jsPlumb-all-min.js',
   		                   array('thickbox', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable'),
   		                   '1.4.1',
   		                   true);
   		wp_enqueue_script( 'drag-drop-jsplumb',
   		                   OASISWF_URL. 'js/pages/drag-drop-jsplumb.js',
   		                   array('jsPlumb'),
   		                   OASISWF_VERSION,
   		                   true ) ;
         wp_localize_script( 'drag-drop-jsplumb', 'drag_drop_jsplumb_vars', array(
   						'clearAllSteps' => __( 'Do you really want to clear all the steps?', 'oasisworkflow' ),
         				'removeStep' => __( 'This step is already defined. Do you really want to remove this step?', 'oasisworkflow' ),
         				'pathBetween' => __( 'The path between', 'oasisworkflow' ),
         				'stepAnd' => __( 'step and', 'oasisworkflow' ),
         				'incorrect' => __( 'step is incorrect.', 'oasisworkflow' ),
                 ));
		}

		if ( is_admin() && preg_match_all('/page=oasiswf(.*)|post-new\.(.*)|post\.(.*)/', $_SERVER['REQUEST_URI'], $matches ) ) {
         wp_enqueue_script( 'owf-workflow-create',
                   OASISWF_URL. 'js/pages/workflow-create.js',
                   '',
                   OASISWF_VERSION,
                   true);
         wp_localize_script( 'owf-workflow-create', 'owf_workflow_create_vars', array(
				'alreadyExistWorkflow' => __( 'There is an existing workflow with the same name. Please choose another name.', 'oasisworkflow' ),
				'unsavedChanges' => __( 'You have unsaved changes.', 'oasisworkflow' )
           ));

   	   wp_enqueue_script( 'jquery-simplemodal',
   		                   OASISWF_URL. 'js/lib/modal/jquery.simplemodal.js',
   		                   array('thickbox'),
   		                   '1.4.4',
   		                   true);

   		wp_enqueue_script( 'owf-workflow-util',
   		                   OASISWF_URL. 'js/pages/workflow-util.js',
   		                   '',
   		                   OASISWF_VERSION,
   		                   true);
         wp_localize_script( 'owf-workflow-util', 'owf_workflow_util_vars', array(
   						'dueDateInPast' => __( 'Due date cannot be in the past.', 'oasisworkflow' )
                ));

         wp_enqueue_script( 'text-edit-whizzywig',
                         OASISWF_URL. 'js/lib/textedit/whizzywig63.js',
                         '',
                         '63',
                         true);

         wp_enqueue_script( 'owf-workflow-step-info',
                         OASISWF_URL. 'js/pages/subpages/step-info.js',
                         array('text-edit-whizzywig'),
                         OASISWF_VERSION,
                         true);
         wp_enqueue_script( 'owf-workflow-step-info',
                         OASISWF_URL. 'js/pages/subpages/step-info.js',
                         '',
                         OASISWF_VERSION,
                         true);

         wp_localize_script( 'owf-workflow-step-info', 'owf_workflow_step_info_vars', array(
   						'stepNameRequired' => __( 'Step name is required.', 'oasisworkflow' ),
         				'stepNameAlreadyExists' => __( 'Step name already exists. Please use a different name.', 'oasisworkflow' ),
         				'selectAssignees' => __( 'Please select assignee(s).', 'oasisworkflow' ),
                     'statusOnSuccess' => __( 'Please select status on success.', 'oasisworkflow' ),
                     'statusOnFailure' => __( 'Please select status on failure.', 'oasisworkflow' ),
                     'selectPlaceholder' => __('Please select a placeholder.', 'oasisworkflow' )
               ));
		}

	}
}

/* plugin activation whenenver a new blog is created */
add_action( 'wpmu_new_blog', array( 'FCInitialization', 'run_on_add_blog' ), 10, 6);
add_action( 'delete_blog', array( 'FCInitialization', 'run_on_delete_blog' ), 10, 2);
add_action( 'admin_init', array( 'FCInitialization', 'run_on_upgrade' ));

include( OASISWF_PATH . "oasiswf-utilities.php" ) ;
$fcLoadWorkflow = new FCLoadWorkflow();

include( OASISWF_PATH . "oasiswf-actions.php" ) ;
$fcWorkflowActions = new FCWorkflowActions();

/******************************************/
/*     Workflow Automatic Updates         */
/******************************************/
if( !class_exists( 'FCWorkflowPluginUpdater' ) ) {
   include( OASISWF_PATH . "includes/oasis-workflow-updater.php" ) ;
}

/*
 * include duplicate post class
 */
if ( !class_exists( 'FCWorkflowPostDuplicate' ) ) {
   include ( OASISWF_PATH . "includes/workflow-duplicate-post.php" );
}

// check for updates
// retrieve our license key from the DB
$license_key = trim( get_site_option( 'oasiswf_license_key' ) );

// setup the updater
$edd_updater = new FCWorkflowPluginUpdater( OASISWF_STORE_URL, __FILE__, array(
		'version' 	=> OASISWF_VERSION, 		// current version number
		'license' 	=> $license_key, 	// license key (used get_option above to retrieve from DB)
		'item_name' => OASISWF_PRODUCT_NAME, 	// name of this plugin
		'author' 	=> 'Nugget Solutions Inc.'  // author of this plugin
	)
);

/* ajax */
add_action('wp_ajax_create_new_workflow', array( 'FCWorkflowCRUD', 'create_new_workflow' ) );
add_action('wp_ajax_get_workflow_count', array( 'FCWorkflowCRUD', 'get_workflow_count' ) );
add_action('wp_ajax_step_save', array( 'FCWorkflowCRUD', 'workflow_step_save' ) );
add_action('wp_ajax_get_editinline_html', array( 'FCWorkflowInbox', 'get_editinline_html' ) );
add_action('wp_ajax_get_step_signoff_content', array( 'FCWorkflowInbox', 'get_step_signoff_content' ) );
add_action('wp_ajax_get_reassign_content', array( 'FCWorkflowInbox', 'get_reassign_content' ) );
add_action('wp_ajax_claim_process', array( 'FCWorkflowInbox', 'claim_process' ) );
add_action('wp_ajax_reset_assign_actor', array( 'FCWorkflowInbox', 'reset_assign_actor' ) );
add_action('wp_ajax_get_step_comment', array( 'FCWorkflowInbox', 'get_step_comment' ) );
add_action('wp_ajax_save_as_new_post_draft', array( 'FCWorkflowPostDuplicate', 'save_as_new_post_draft_ajax' ) );
// Following action is for handling history report download
add_action('wp_ajax_download_history_report', array('FCWorkflowHistory', 'download_history_report') );
// Following action is for handling user based history report download
/*add_action('wp_ajax_download_user_based_history_report', array('FCWorkflowInbox', 'download_user_based_history_report') );*/
add_action('wp_ajax_copy_step', array( 'FCWorkflowCRUD', 'copy_step' ) );
add_action('wp_ajax_load_step_info', array( 'FCLoadWorkflow', 'load_step_info' ) );
add_action('wp_ajax_change_status_to_owf_status', array( 'FCWorkflowPostDuplicate', 'change_status_to_owf_status_ajax' ) );

add_filter('post_row_actions', array( 'FCWorkflowPostDuplicate', 'new_draft_link_row' ) ,10,2);
add_filter('page_row_actions', array( 'FCWorkflowPostDuplicate', 'new_draft_link_row' ) ,10,2);

add_action('admin_action_save_as_new_post_draft', array( 'FCWorkflowPostDuplicate', 'save_as_new_post_draft' ));
add_action('currentrev_to_draft', array( 'FCWorkflowPostDuplicate', 'update_published_post' ));

// post published notification hook
add_action( 'transition_post_status', array( 'FCWorkflowEmail', 'post_published_notification' ), 10, 3 );

// revise post
// Using our action hooks to copy meta fields
add_action('owf_duplicate_post', array( 'FCWorkflowPostDuplicate', 'copy_post_meta_info' ), 10, 2);
add_action('owf_duplicate_page', array( 'FCWorkflowPostDuplicate', 'copy_post_meta_info' ), 10, 2);

add_action('owf_duplicate_post', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_post_taxonomies' ), 10, 2);
add_action('owf_duplicate_page', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_post_taxonomies' ), 10, 2);

add_action('owf_duplicate_post', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_children' ), 10, 2);
add_action('owf_duplicate_page', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_children' ), 10, 2);

// update published post
// Using our action hooks to copy meta fields
add_action('owf_update_published_post', array( 'FCWorkflowPostDuplicate', 'update_post_meta_info' ), 10, 2);
add_action('owf_update_published_page', array( 'FCWorkflowPostDuplicate', 'update_post_meta_info' ), 10, 2);

add_action('owf_update_published_post', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_post_taxonomies' ), 10, 2);
add_action('owf_update_published_page', array( 'FCWorkflowPostDuplicate', 'copy_post_copy_post_taxonomies' ), 10, 2);

add_action('owf_update_published_post', array( 'FCWorkflowPostDuplicate', 'update_post_update_children' ), 10, 2);
add_action('owf_update_published_page', array( 'FCWorkflowPostDuplicate', 'update_post_update_children' ), 10, 2);

?>