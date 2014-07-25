<?php

/**
*  Travel Request Formidable custom code.
*
*
*
*/


/***************************************
* my custom functions
**/
// [date]
function displaydate(){
return date('l, F jS, Y');
}
add_shortcode('date', 'displaydate');
// end date 


//populate the reviewers dropdown field
add_filter('frm_setup_new_fields_vars', 'frm_populate_reviewers', 20, 2);
add_filter('frm_setup_edit_fields_vars', 'frm_populate_reviewers', 20, 2); //use this function on edit too
function frm_populate_reviewers($values, $field){
if($field->id == 369 || $field->id == 370){ //replace 125 with the ID of the field to populate
   
   $args = array(
			'role'=>'reviewer'
			);
   //the Query
   $wp_user_search = new WP_User_Query($args);
   
   $the_Result = $wp_user_search->results;
   
   unset($values['options']);
   
   $values['options'] = array(''); //remove this line if you are using a checkbox or radio button field
   
   //the Loop
   if(! empty($the_Result))
   {
      foreach($the_Result as $u){
      $values['options'][$u->user_email] = $u->display_name;
   }
   } else 
   {
		echo 'No users found.';
   }     
   $values['use_key'] = true; //this will set the field to save the post ID instead of post title		
}
return $values;
}


/**validate and add data to form B based on data from form A
*
*/
add_filter(‘frm_validate_field_entry’, ‘mysetfields’, 8, 3);
function mysetfields($errors, $posted_field, $posted_value){
	$_POST['item_meta'][358] = $_POST['item_meta'][338];
	/**$_POST['item_meta'][358] = $_POST['item_meta'][357];
	$_POST['item_meta'][372] = $_POST['item_meta'][116 show=356];**/
	return $errors;
}

//Clear javascript
add_filter('frm_validate_field_entry', 'my_custom_validation', 10, 2);
function my_custom_validation($errors, $posted_field){
  if(!current_user_can('administrator')){ //don't strip javascript submitted by administrators
    if(!is_array($_POST['item_meta'][$posted_field->id])){
      $_POST['item_meta'][$posted_field->id] = wp_kses_post($_POST['item_meta'][$posted_field->id]);
    }else{
      foreach($_POST['item_meta'][$posted_field->id] as $k => $v){
        if(!is_array($v))
          $_POST['item_meta'][$posted_field->id][$k] = wp_kses_post($v);
      }
    }
  }
  return $errors;
}

?>