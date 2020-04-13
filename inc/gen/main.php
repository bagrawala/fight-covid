<?php

include ('../db/dbconnect.php');

if (isset($_GET) AND !empty($_GET)) {
  $user_id = $_GET["id"];
}


//After getting the user ID proceed with XML generation

function parseToXML($htmlStr)
{
$xmlStr=str_replace('<','&lt;',$htmlStr);
$xmlStr=str_replace('>','&gt;',$xmlStr);
$xmlStr=str_replace('"','&quot;',$xmlStr);
$xmlStr=str_replace("'",'&#39;',$xmlStr);
$xmlStr=str_replace("&",'&amp;',$xmlStr);
return $xmlStr;
}

// Opens a connection to a MySQL server



// Select all the rows in the markers table
$query = "SELECT id, name, address, lat, lng FROM individual_markers WHERE user_id = '".$user_id."'";
$result = $conn->query($query);
if (!$result) {
  die('Invalid query: ' . mysqli_error());
}


header("Content-type: text/xml");

// Start XML file, echo parent node
echo "<?xml version='1.0' ?>";
echo '<markers>';
$ind=0;
// Iterate through the rows, printing XML nodes for each
while ($row = $result -> fetch_assoc()){
  // Add to XML document node
  echo '<marker ';
  echo 'id="' . $row['id'] . '" ';
  echo 'name="' . parseToXML(utf8_encode($row['name'])) . '" ';
  echo 'address="' . parseToXML(utf8_encode($row['address'])) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  //echo 'type="' . $row['type'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';



?>