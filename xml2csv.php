<?php
$xmldata='SOURCE_URL.xml';
$csvFile = 'results.csv';
$xPath = '//product';

// One of this array must be empty, or INCLUDE columns, or EXCLUDE
$included_columns = array();  $excluded_columns = array('description');

// Use function - generates CSV
xml2csv($xmldata, $csvFile, $xPath, $included_columns, $excluded_columns);




function xml2csv($xmlFile, $csvFile, $xPath, $included_columns = array(), $excluded_columns = array()) {
    $columns = array(); // All possible columns in XML
    $all_data = array();
	// Load the XML file
	$xml = simplexml_load_file($xmlFile);

	// Jump to the specified xpath
	$path = $xml->xpath($xPath);

	// Loop through the specified xpath
	foreach($path as $item) {
        
        $item_data = array();
		// Loop through the elements in this xpath
		foreach($item as $key => $value) {
            if (!in_array($key, $columns)) {
                array_push($columns, $key);
            }
            $item_data[$key] = $value;	
		}
        
        // Add item data for all data
        array_push($all_data, $item_data);
	}
    
    
    // Fill all columns for empty values
    foreach($all_data as $item_data) {
        foreach ($columns as $column) {
            if (!in_array($column, $item_data)) {
                $item_data[$column] = NULL;
            }
        }
    }
    
    // Delete excluded columns from columns
    if (!empty($included_columns)) {
        $columns = $included_columns;
    }    
    else if (!empty($excluded_columns)) {
        $columns = array_diff($columns, $excluded_columns);
    }

    // Cleaned and ordered data
    $ordered_all_data = array();
    foreach($all_data as $item_data) {
        $ordered_item = array();
        foreach($columns as $column) {
            $ordered_item[$column] = $item_data[$column];
        }
        array_push($ordered_all_data, $ordered_item);
    }
    
    
    // Open CSV file to write
    
    $fp = fopen($csvFile, 'w');
    // Write header to CSV
    $fields = implode(',', $columns);

    fwrite($fp,$fields.PHP_EOL);

    // Write data to CSV
    foreach ($ordered_all_data as $item_data) {
        $item_values = implode(',', array_values($item_data));
        //print_r($item_data);
        //fputcsv($fp, $item_values);
        fwrite($fp,$item_values.PHP_EOL);
    }

    fclose($fp);
    
	return True;
}
?>
