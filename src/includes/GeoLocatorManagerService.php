<?php

class GeoLocatorManagerService {

  public function GetGeoLocatorData() {
    global $wpdb;
    $table_name = $wpdb->prefix . "geolocator";
    
    // Fetch all data from the geolocator table
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    
    // Return the results
    return rest_ensure_response($results);
  }

  public function SelectDataById($request) {
    global $wpdb;
    $table_name = $wpdb->prefix . "geolocator";
    
    // Fetch data by ID from the geolocator table
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $request->get_param('id')), ARRAY_A);
    
    // Check if result is found
    if ($result) {
      return rest_ensure_response($result);
    } else {
      return new WP_Error('no_data', 'No data found for the given ID', array('status' => 404));
    }
  }
  
}