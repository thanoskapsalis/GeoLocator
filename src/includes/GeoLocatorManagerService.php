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
  
}