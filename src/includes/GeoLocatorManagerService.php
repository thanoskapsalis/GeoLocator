<?php

class GeoLocatorManagerService
{

  public function GetGeoLocatorData()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "geolocator";

    // Fetch all data from the geolocator table
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    // Return the results
    return rest_ensure_response($results);
  }

  public function SelectDataById($args)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "geolocator";

    // Fetch data by ID from the geolocator table
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $args->get_param('id')), ARRAY_A);

    // Check if result is found
    if ($result) {
      return rest_ensure_response($result);
    } else {
      return new WP_Error('no_data', 'No data found for the given ID', array('status' => 404));
    }
  }

  public function AddGeoLocatorData($request)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "geolocator";

    // Prepare data for insertion
    $data = array(
      'created_at' => current_time('mysql'),
      'name' => sanitize_text_field($request->get_param('name')),
      'description' => sanitize_textarea_field($request->get_param('description')),
    );

    // Insert data into the geolocator table
    $inserted = $wpdb->insert($table_name, $data);


    // Check if insertion was successful
    if ($inserted) {
      $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
      return rest_ensure_response(array('message' => 'Data added successfully', 'data' => $results));
    } else {
      return new WP_Error('db_error', 'Failed to add data', array('status' => 500));
    }
  }

}