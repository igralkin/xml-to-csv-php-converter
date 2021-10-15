# XML to CSV PHP converter
XML to CSV PHP converter

// Entry parameteres
$xmldata='SOURCE_URL.xml';
$csvFile = 'results.csv';
$xPath = '//product';
// One of this array must be empty, or INCLUDE columns, or EXCLUDE
$included_columns = array();  $excluded_columns = array('description');

// Use function - generates CSV
xml2csv($xmldata, $csvFile, $xPath, $included_columns, $excluded_columns);

