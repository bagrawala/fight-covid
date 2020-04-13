<?php 


$host = 'localhost';
$db_user = '';
$db_pass = '';
$db_name = '';

$conn = new mysqli($host,$db_user,$db_pass,$db_name);

// Check connection
if ($conn -> connect_errno) {
  echo "Failed to connect to MySQL: " . $conn -> connect_error;
  exit();
}




?>