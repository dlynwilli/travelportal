<?php
/*************************************/
/*     Workflow create               */
/*************************************/
class FCWorkflowCRUD extends FCWorkflowBase
{
	static function save()
	{
		global $wpdb, $workflow_message;
		$title = trim($_POST["define-workflow-title"]) ;
		$dec = $_POST["define-workflow-description"] ;
		$graphic = stripcslashes($_POST["wf_graphic_data_hi"]) ;
		$startdate = FCWorkflowCRUD::format_date_for_db( $_POST["start-date"] ) ;
		$enddate = FCWorkflowCRUD::format_date_for_db( $_POST["end-date"] ) ;
		$auto_submit = (isset($_POST["auto-submit"]) && $_POST["auto-submit"]) ? 1 : 0;
		$auto_submit_keywords = explode(',', stripcslashes($_POST["auto-submit-keywords"])) ;
		$keyword_array = array('keywords' => $auto_submit_keywords);
		$wf_for_new_posts = (isset($_POST["new_post_workflow"]) && $_POST["new_post_workflow"]) ? 1 : 0;
		$wf_for_revised_posts = (isset($_POST["revised_post_workflow"]) && $_POST["revised_post_workflow"]) ? 1 : 0;
		$wf_additional_info = array('wf_for_new_posts' => $wf_for_new_posts, 'wf_for_revised_posts' => $wf_for_revised_posts);

		$workflow_message = FCWorkflowValidate::check_workflow_validate() ;
		$workflow_table = FCUtility::get_workflows_table_name();
		$valid = ( $workflow_message ) ? 0 : 1 ;
		$result = $wpdb->update($workflow_table,
								array(
									'name' => stripcslashes( trim( $title )),
									'description' => stripcslashes( $dec ),
									'wf_info' => $graphic,
									'start_date' => $startdate,
									'end_date' => $enddate,
								   'is_auto_submit' => $auto_submit,
								   'auto_submit_keywords' => serialize($keyword_array),
									'is_valid' => $valid,
									'update_datetime' => current_time('mysql'),
									'wf_additional_info' =>  serialize($wf_additional_info)
								),
								array('ID' => $_POST["wf_id"])
					);


		if($_POST["deleted_step_ids"]){
			$deleted_steps = explode( "@", $_POST["deleted_step_ids"] ) ;
			for( $i = 0; $i < count( $deleted_steps ) -1 ; $i++ )
			{
				$wpdb->get_results( "DELETE FROM " . FCUtility::get_workflow_steps_table_name() . " WHERE ID = " . $deleted_steps[$i] ) ;
			}
		}				

		if( !$workflow_message )
			wp_redirect( network_admin_url( 'admin.php?page=oasiswf-admin' ) );
	}

	static function as_save()
	{
		global $wpdb;
		$wf = FCWorkflowCRUD::get_workflow_by_id( $_POST["wf_id"] ) ;
		if($wf){
			$parentId = ( $wf->parent_id == 0 ) ? $wf->ID : $wf->parent_id ;
			$newVersion = FCWorkflowCRUD::get_new_version($parentId);
			$data = array(
						'name' => stripcslashes( trim( $wf->name )),
						'description' => stripcslashes( $wf->description ),
					   'is_auto_submit' => $wf->is_auto_submit,
			 	      'auto_submit_keywords' => $wf->auto_submit_keywords,
						'version' => $newVersion,
						'parent_id' => $parentId,
						'create_datetime' => current_time('mysql'),
						'update_datetime' => current_time('mysql'),
						'wf_additional_info' =>  $wf->wf_additional_info
					);
			$workflow_table = FCUtility::get_workflows_table_name();
			$newWfId = FCWorkflowCRUD::insert_to_table($workflow_table, $data );

			$wfInfo = json_decode($wf->wf_info);

			foreach ($wfInfo->steps as $k => $v)
			{
				if( $v->fc_dbid == "nodefine" ) continue ;

				$new_fc_dbid = FCWorkflowCRUD::step_as_save( $newWfId, $v->fc_dbid ) ;

				if($new_fc_dbid)
					$wfInfo->steps->$k->fc_dbid = $new_fc_dbid ;

			}
			$wfInfo = json_encode( $wfInfo ) ;

			$wpdb->update( $workflow_table,
							array(
								"wf_info" => $wfInfo
							),
							array("ID" => $newWfId ) ) ;								

			wp_redirect( network_admin_url( 'admin.php?page=oasiswf-admin&wf_id=' . $newWfId ) );
		}
	}

	static function step_as_save($newWfId, $stepid)
	{
		global $wpdb ;
		$workflow_step_table = FCUtility::get_workflow_steps_table_name();
		$newStepId = FCWorkflowCRUD::same_as_save( $workflow_step_table, $stepid ) ;
		if( $newStepId ){
			$wpdb->update($workflow_step_table, array( "workflow_id" => $newWfId ), array( "ID" => $newStepId ) ) ;
			return $newStepId ;
		}else{
			return false;
		}
	}

	static function copy()
	{
		global $wpdb;
		$wf = FCWorkflowCRUD::get_workflow_by_id( $_POST["wf_id"] ) ;
		if($wf){
			$parentId = 0 ;
			$newVersion = 1;

			$arr_date = explode(' ', current_time('mysql')) ;
			$data = array(
						'name' => stripcslashes( trim( $_POST['define-workflow-title'] )),
						'description' => stripcslashes( $_POST['define-workflow-description'] ),
					   'is_auto_submit' => $wf->is_auto_submit,
			 	      'auto_submit_keywords' => $wf->auto_submit_keywords,
						'version' => $newVersion,
						'parent_id' => $parentId,
						'start_date' => $wf->start_date,
						'end_date' => $wf->end_date,
						'create_datetime' => current_time('mysql'),
						'update_datetime' => current_time('mysql'),
						'wf_additional_info' =>  $wf->wf_additional_info
					);
			$workflow_table = FCUtility::get_workflows_table_name();
			$newWfId = FCWorkflowCRUD::insert_to_table($workflow_table, $data );

			$wfInfo = json_decode($wf->wf_info);

			foreach ($wfInfo->steps as $k => $v)
			{
				if( $v->fc_dbid == "nodefine" ) continue ;

				$new_fc_dbid = FCWorkflowCRUD::step_as_save( $newWfId, $v->fc_dbid ) ;

				if($new_fc_dbid)
					$wfInfo->steps->$k->fc_dbid = $new_fc_dbid ;

			}
			$wfInfo = json_encode( $wfInfo ) ;

			$wpdb->update( $workflow_table,
							array(
								"wf_info" => $wfInfo
							),
							array("ID" => $newWfId ) ) ;
															
			wp_redirect( network_admin_url( 'admin.php?page=oasiswf-admin&wf_id=' . $newWfId ) );
		}
	}

	static function copy_step()
	{
		$stepid = $_POST["copy_step_id"] ;
		$step = FCWorkflowCRUD::get_step_by_id($stepid) ;
		if( $step ){
			$data = array(
						'step_info' => $step->step_info,
						'process_info' => $step->process_info,
					   'workflow_id' => $step->workflow_id,
			 	      'create_datetime' => current_time('mysql'),
						'update_datetime' => current_time('mysql')
					);

			$step_table = FCUtility::get_workflow_steps_table_name();
			$newWfId = FCWorkflowCRUD::insert_to_table( $step_table, $data );
			if( is_numeric( $newWfId ) )echo $newWfId ;
		}
		exit ;
	}

	static function connection_setting_html()
	{
		$str = '<div class="dialog-title"><strong>' . __("Connection Settings", "oasisworkflow") . '</strong></div>
				<div class="connection-status">
					<table width="100%" style="text-align:left;">
						<tr>
							<td width="150px;" height="30px;">' . __("Current Connection", "oasisworkflow") . ' :</td>
							<td width="55px;">' . __("Source", "oasisworkflow") . ' - </td>
							<td><label id="source_name_lbl"></label></td>
						</tr>
						<tr>
							<td></td>
							<td>' . __("Target", "oasisworkflow") . ' - </td>
							<td><label id="target_name_lbl"></label></td>
						</tr>
					</table>
				</div>
				<div style="margin-bottom:15px;">
					<table width="100%" style="text-align:left;">
						<tr height="50px">
							<td width="85px;">' . __("Path", "oasisworkflow") . '</td>
							<th width="7px">:</th>';
							$oasiswf_path = get_site_option( "oasiswf_path" ) ;
							if($oasiswf_path){
								foreach ($oasiswf_path as $k => $v) {
									$str .= '<td width="110px;">
												<input type="radio" id="path-opt-' . $v[1] . '" name="path-opt" value="' . $v[1] . '" ' . '/> ' . $v[0] . '
											</td>';
								}
							}
				$str .=	'</tr>
						</table>
					</div>
				<p class="changed-data-set">
					<div class="right button-spacing">
						<input type="button" id="connection-setting-save" class="button-primary" value="' . __("Save", "oasisworkflow") . '" />
					</div>
					<div class="right button-link-spacing">
						<a href="#" id="connection-setting-cancel">' . __("Cancel", "oasisworkflow") . '</a>
					</div>
				</p>
				<br class="clear" />
			    ';
		return $str;
	}

	static function new_workflow_create_check_html()
	{
		$str = '<div class="dialog-title"><strong>' . __("Create New Workflow", "oasisworkflow") . '</strong></div>
				<table style="margin-top:15px;">
					<tr>
						<td width="90px">
							<label>' . __("Title : ", "oasisworkflow") . '</label>
						</td>
						<td>
							<input type="text"  id="new-workflow-title" style="width:350px;" />
						</td>
					</tr>

					<tr height="20px;"><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr>
						<td style="vertical-align: top;">
							<label>' . __("Description : ", "oasisworkflow") . '</label>
						</td>
						<td>
							<textarea id="new-workflow-description" cols="20" rows="10" style="height:100px;width:350px;"></textarea>
						</td>
					</tr>

				</table>
				<p class="changed-data-set">
					<input type="button" id="new-create-wf-save" class="button-primary" value="' . __("Save", "oasisworkflow"). '" />
					<span>&nbsp;</span>
					<a href="javascript:window.history.back()" id="new-create-wf-cancel">' . __("Cancel", "oasisworkflow"). '</a>
				</p>
				<br class="clear" />
			    ';
		return $str;
	}

	static function copy_workflow_create_check_html($workflow)
	{
		$str = '<div class="dialog-title"><strong>' . __("Copy Workflow") . '</strong></div>
				<table style="margin-top:15px;">
					<tr>
						<td width="90px">
							<label>' . __("Title : ") . '</label>
						</td>
						<td>
							<input type="text"  id="copy-workflow-title" value="Copy-' . $workflow->name . '" style="width:350px;" />
						</td>
					</tr>

					<tr height="20px;"><td>&nbsp;</td><td>&nbsp;</td></tr>
					<tr>
						<td style="vertical-align: top;">
							<label>' . __("Description : ") . '</label>
						</td>
						<td>
							<textarea id="copy-workflow-description" cols="20" rows="10" style="height:100px;width:350px;">' . $workflow->description . '</textarea>
						</td>
					</tr>

				</table>
				<p class="changed-data-set">
					<input type="button" id="copy-wf-submit" class="button-primary" value="Copy" />
					<span>&nbsp;</span>
					<a href="javascript:jQuery.modal.close();" id="copy-wf-cancel">Cancel</a>
				</p>
				<br class="clear" />
			    ';
		return $str;
	}

	static function create_new_workflow()
	{
		$data = array(
					'name' => stripcslashes( $_POST["name"] ),
					'description' => stripcslashes( $_POST["description"] ),
					'create_datetime' => current_time('mysql'),
		         'update_datetime' => current_time('mysql')
				);
		$workflow_table = FCUtility::get_workflows_table_name();
		$newid = FCWorkflowCRUD::insert_to_table( $workflow_table, $data ) ;
		echo $newid ;
		exit();
	}

	static function get_workflow_count()
	{
		global $wpdb;
		$name = strtolower($_POST["name"]);
		$result = $wpdb->get_row( $wpdb->prepare( "SELECT count(*) count FROM " . FCUtility::get_workflows_table_name() . " WHERE LOWER(name) = %s", $name ) );
		echo $result->count;
		exit();
	}

	static function wokflow_data_update($param)
	{
		global $wpdb;
		$result = FCWorkflowCRUD::get_workflow_by_id( $param["wpid"] ) ;
		$workflow_table = FCUtility::get_workflows_table_name();
		if( $result ){
			$wf_info = json_decode( $result->wf_info ) ;
			if( $wf_info->steps->$param["stepgpid"] ){
				$wf_info->steps->$param["stepgpid"]->fc_label =  $param["name"] ;
				$wf_info->steps->$param["stepgpid"]->fc_dbid =  $param["stepdbid"] ;
				$saveData = json_encode( $wf_info ) ;
				$result = $wpdb->update( $workflow_table, array('wf_info' => $saveData), array('ID' =>  $param["wpid"]) );
			}
		}
	}

	static function workflow_step_save()
	{
		global $wpdb ;
		$workflow_step_table = FCUtility::get_workflow_steps_table_name();

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . FCUtility::get_workflow_steps_table_name() . " WHERE ID = %d" , $_POST["stepid"] ));
		if(	$result ) {
			$result = $wpdb->update(
						$workflow_step_table,
						array(
							'step_info' => stripcslashes( $_POST["stepinfo"] ),
							'process_info' => stripcslashes( $_POST["processinfo"] ),
							'update_datetime' => current_time('mysql')
						),
						array('ID' =>  $_POST["stepid"])
					);

			$stepId = $_POST["stepid"] ;
		}else{
			$result = $wpdb->insert(
						$workflow_step_table,
						array(
							'step_info' => stripcslashes( $_POST["stepinfo"] ),
							'process_info' => stripcslashes( $_POST["processinfo"] ),
							'create_datetime' => current_time('mysql'),
						   'update_datetime' => current_time('mysql'),
							'workflow_id' => $_POST["wfid"],
						)
					);
			$insert_row = $wpdb->get_row("SELECT max(ID) as maxid FROM " . FCUtility::get_workflow_steps_table_name());
			$stepId = $insert_row->maxid ;
		}

		if( $_POST["act"] == "old" ){
			$param = array(
						"wpid" => $_POST["wfid"],
						"stepgpid" => $_POST["stepgpid"],
						"stepdbid" => $stepId,
						"name" => stripcslashes( $_POST["stepname"])
						);
			FCWorkflowCRUD::wokflow_data_update($param) ;
		}
		echo $stepId ;
		exit();
	}

	static function is_wf_editable($wfid)
	{
		global $wpdb;

		$postcount = FCWorkflowCRUD::get_postcount_in_wf( $wfid ) ;
		if( $postcount )return false;  //filter-3

		return true ;
	}

	static function get_previous_workflow_version($wfid, $field_name=null)
	{
		global $wpdb;
		$workflow = FCWorkflowCRUD::get_workflow_by_id( $wfid ) ;
		if( $workflow->version == 1 )return false;
		$parent_id = ( $workflow->parent_id ) ? $workflow->parent_id : $wfid ;
		$sql = "SELECT * FROM " . FCUtility::get_workflows_table_name() . " WHERE version=" .  ( $workflow->version - 1 ) . " && (parent_id = " . $parent_id . " || ID = " . $parent_id .  " )" ;
		$previous_workflow = $wpdb->get_row( $sql ) ;
		if( $previous_workflow ){
			if( $field_name )
				return 	$previous_workflow->$field_name ;
			else
				return 	$previous_workflow ;
		}
		return false;
	}

	//============The Functions is to copy and paste FCProcessFlow Class functions to get first step and last step===========
	static function copy_get_first_last_step($wfinfo)
	{
		if( $wfinfo->steps ){
			foreach ($wfinfo->steps as $k => $v) {
				if( $v->fc_dbid == "nodefine" )return "nodefine" ;
				$step_stru = FCWorkflowCRUD::copy_get_process_steps($wfinfo, $v->fc_dbid, "target");
				if( isset($step_stru["success"]) && $step_stru["success"] )continue ;
				$first_step[] = array($v->fc_dbid, $v->fc_label, $v->fc_process);
			}

			foreach ($wfinfo->steps as $k => $v) {
				if( $v->fc_dbid == "nodefine" )return "nodefine" ;
				$step_stru = FCWorkflowCRUD::copy_get_process_steps($wfinfo, $v->fc_dbid, "source");
				if( isset($step_stru["success"]) && $step_stru["success"] )continue ;
				$last_step[] = array($v->fc_dbid, $v->fc_label, $v->fc_process);
			}

			$getStep["first"] = $first_step ;
			$getStep["last"] = $last_step ;
		}

		return $getStep ;

	}

	static function copy_get_process_steps($wfinfo, $stepid, $direct="source")
	{

		$info = $wfinfo ;
		$conns = $info->conns ;
		$stepgpid = FCWorkflowCRUD::get_gpid_dbid( $info, $stepid );
		$all_path = get_site_option("oasiswf_path") ;
		foreach ($all_path as $k => $v) {
			$path[$v[1]] = $k ;
		}
      $steps = array();
		if( $conns ){
			if( $direct == "source" )
				foreach ($conns as $k => $v){
					if( $stepgpid == $v->sourceId ){
						$color = $v->connset->paintStyle->strokeStyle ;
						$steps[$path[$color]][FCWorkflowCRUD::get_gpid_dbid($info, $v->targetId )] = FCWorkflowCRUD::get_gpid_dbid($info, $v->targetId, "lbl" ) ;
					}
				}
			else{
				foreach ($conns as $k => $v){
					if( $stepgpid == $v->targetId ){
						$color = $v->connset->paintStyle->strokeStyle ;
						$steps[$path[$color]][FCWorkflowCRUD::get_gpid_dbid($info, $v->sourceId)] =  FCWorkflowCRUD::get_gpid_dbid($info, $v->sourceId, "lbl") ;
					}
				}
			}
			if( count($steps) > 0 )	return $steps ;
		}

		return false;
	}
	//=========================================================================================================================
}
?>