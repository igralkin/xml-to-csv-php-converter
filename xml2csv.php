<?php
$xmldata='https://www.connectccs.gr/xml/all-products.xml';
$csvFile = 'connectccs.csv';


echo '<pre>';
// ----------
// xml2csv
// -----
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
            //print "$key<br>";
			$csvData .= '"' . trim($value) . '"' . ',';
		
		}
        
		// Trim off the extra comma
		$csvData = trim($csvData, ',');
		
		// Add an LF
		$csvData .= "\n";
        
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
    if (!empty($excluded_columns)) {
        $columns = array_diff($columns, $excluded_columns);
    }
    print_r($columns);
    // Cleaned and ordered data
    $ordered_all_data = array();
    foreach($all_data as $item_data) {
        $ordered_item = array();
        foreach($columns as $column) {
            $ordered_item[$column] = $item_data[$column];
        }
        array_push($ordered_all_data, $ordered_item);
    }
    
    //print_r($ordered_all_data);
    
    // Open CSV file to write
    
    $fp = fopen($csvFile, 'w');
    // Write header to CSV
    $fields = implode(',', $columns);
    //fputcsv($fp, $fields);
    fwrite($fp,$fields.PHP_EOL);

    // Write data to CSV
    foreach ($ordered_all_data as $item_data) {
        $item_values = implode(',', array_values($item_data));
        //print_r($item_data);
        //fputcsv($fp, $item_values);
        fwrite($fp,$item_values.PHP_EOL);
    }

    fclose($fp);
    
    
	// Return the CSV data
	return $csvData;
	
}
$data = xml2csv($xmldata, $csvFile, '//product', '', array('description'));
//echo '<pre>';
//print($data);