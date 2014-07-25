<?php
class FCWorkflowLicense
{
   static function sanitize_license( $new ) {
   	$old = get_site_option( 'oasiswf_license_key' );
   	if( $old && $old != $new ) {
   		delete_site_option( 'oasiswf_license_status' ); // new license has been entered, so must reactivate
   	}
   	return $new;
   }

   static function activate_license( ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'oasiswf_nonce', 'oasiswf_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_site_option( 'oasiswf_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( OASISWF_PRODUCT_NAME ) // the name of our product in EDD
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, OASISWF_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "valid" or "invalid"

		update_site_option( 'oasiswf_license_status', $license_data->license );
	}

   static function deactivate_license( ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'oasiswf_nonce', 'oasiswf_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_site_option( 'oasiswf_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( OASISWF_PRODUCT_NAME ) // the name of our product in EDD
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, OASISWF_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			update_site_option( 'oasiswf_license_status', $license_data->license );
   }

}