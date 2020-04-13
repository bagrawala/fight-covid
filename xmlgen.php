<?php

include ('inc/db/dbconnect.php');

if (isset($_GET) AND !empty($_GET)) {
  $user_id = $_GET["id"];
  $type = $_GET["type"];
  if (isset( $_GET["m"])) {
    $month = $_GET["m"];
  }

  if (isset( $_GET["date"])) {
    //Format the date as stored in database
    $date = str_replace("/","-",$_GET["date"]);
  }

  if (isset( $_GET["c"])) {
    //Get the cluster
    $cluster_no = $_GET["c"];
  }


}


if ($type == 1) {
  $table = 'individual_markers';
  $where_clause = 'user_id';

  if (isset($date) AND !empty($date)) {
    $query = "SELECT id, name, address, lat, lng FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND event_date = '".$date."'";

  }else{
    $query = "SELECT id, name, address, lat, lng FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND month = '".$month."'";
  }
  
}elseif ($type == 2) {
  
  $table = 'individual_markers';
  $where_clause = 'city_id';


if (isset($date) AND !empty($date)) {
      if (isset($cluster_no) AND !empty($cluster_no)) {
    $users_query = "SELECT DISTINCT user_id FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND event_date = '".$date."' LIMIT ".$cluster_no."";
    }else{
      $users_query = "SELECT DISTINCT user_id FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND event_date = '".$date."'";
    }
}else{
    if (isset($cluster_no) AND !empty($cluster_no)) {
    $users_query = "SELECT DISTINCT user_id FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND month = '".$month."' LIMIT ".$cluster_no."";
    }else{
      $users_query = "SELECT DISTINCT user_id FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND month = '".$month."'";
    }
}

  
    

  

  $users_result = $conn->query($users_query);

  $all_users = "(";
  $user_array = [];
  while ($row = $users_result->fetch_assoc()){
        $all_users .= "'".$row['user_id']."', ";
        array_push($user_array, $row['user_id']);
  }

  $user_ids = (substr_replace($all_users ,"", -2)).')';


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


if ($type == 1) {

  // Select all the rows in the markers table

  if (isset($date) AND !empty($date)) {
    $query = "SELECT id, user_id, name, address, lat, lng FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND event_date = '".$date."'";

  }else{
    $query = "SELECT id, user_id, name, address, lat, lng FROM ".$table." WHERE ".$where_clause." = '".$user_id."' AND month = '".$month."'";
  }



  $result = $conn->query($query);
  if (!$result) {
    die('Invalid query: ' . mysqli_error());
  }


  $query2 = "SELECT status FROM individual WHERE user_id = '".$user_id."'";
  $result2 = $conn->query($query2);
  if (!$result2) {
    die('Invalid query: ' . mysqli_error());
  }

  //Get the user status
  $row2 = $result2 -> fetch_assoc();
  if ($row2['status'] == '0') {
    $status = 'safe';
  }elseif ($row2['status'] == '1') {
    $status = 'confirmed';
  }elseif ($row2['status'] == '2') {
    $status = 'suspected';
  }

}elseif ($type == 2) {



  if (isset($date) AND !empty($date)) {

        $query = "SELECT id, user_id, name, address, lat, lng FROM ".$table." WHERE user_id IN ".$user_ids." AND city_id = '".$user_id."' AND event_date = '".$date."'";

        //echo $query;
        //exit;

      $result = $conn->query($query);
      if (!$result) {
        die('Invalid query: ' . mysqli_error());
      }
 

  }else{
     $query = "SELECT id, user_id, name, address, lat, lng FROM ".$table." WHERE user_id IN ".$user_ids." AND city_id = '".$user_id."' AND month = '".$month."'";

    $result = $conn->query($query);
      if (!$result) {
        die('Invalid query: ' . mysqli_error());
      }
  }
  
 

  $users_status = [];

  foreach ($user_array as $user_id) {

    $query2 = "SELECT status FROM individual WHERE user_id = '".$user_id."'";
    $result2 = $conn->query($query2);
    if (!$result2) {
      die('Invalid query: ' . mysqli_error());
    }

    //Get the user status
    $row2 = $result2 -> fetch_assoc();
    if ($row2['status'] == '0') {
      $status = 'safe';
    }elseif ($row2['status'] == '1') {
      $status = 'confirmed';
    }elseif ($row2['status'] == '2') {
      $status = 'suspected';
    }

    //array_push($users_status, $status);
    $users_status[$user_id] = $status;

  }

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
  echo 'user_id="' . $row['user_id'] . '" ';
  echo 'name="' . parseToXML(utf8_encode($row['name'])) . '" ';
  echo 'address="' . parseToXML(utf8_encode($row['address'])) . '" ';
  echo 'lat="' . $row['lat'] . '" ';
  echo 'lng="' . $row['lng'] . '" ';
  if ($type == 1) {
    echo 'status="' . $status . '" ';
  }elseif ($type == 2) {
    echo 'status="' . $users_status[$row['user_id']] . '" ';
  }
  
  //echo 'type="' . $row['type'] . '" ';
  echo '/>';
  $ind = $ind + 1;
}

// End XML file
echo '</markers>';



?>