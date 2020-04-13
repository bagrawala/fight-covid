<?php 
$page = 'edit';
include('inc/templates/header.php');
include ('inc/db/dbconnect.php');



if (isset($_POST) AND !empty($_POST)) {

	//Perform Update
	$sql = "UPDATE individual
	SET firstname = '".$_POST["firstname"]."', lastname= '".$_POST["lastname"]."', city_id= '".$_POST["cityname"]."', status= '".$_POST["status"]."'
	WHERE user_id = '".$_POST["idnumber"]."'";


	$result = $conn->query($sql);


	if ($result) {
		?>

		<div class="col-md-12">  
	      <div class="alert alert-success" role="alert">
	        Update Successful click <a href="index.php">HERE</a> to go back to the list
	      </div>
	    </div>

		<?php
	}else{
		?>

		<div class="col-md-12">  
	      <div class="alert alert-danger" role="alert">
	        Update unsuccessful, please try again
	      </div>
	    </div>

		<?php
	}

}



$sql = "SELECT city_id, name FROM cities";
$result = $conn->query($sql);

if (isset($_GET) AND !empty($_GET)) {
  $user_id = $_GET["id"];
}else{

	?>

	<div class="col-md-12">  
      <div class="alert alert-danger" role="alert">
        Invalid User ID
      </div>
    </div>

	<?php
	exit;
}


$sql2 = "SELECT user_id, firstname, lastname, city_id, status FROM individual WHERE user_id = '".$user_id."' ";
$result2 = $conn->query($sql2);

$user_data = $result2->fetch_assoc();

//var_dump($user_data);
//exit;

?>

<div class="col-md-12 text-center">   


<form action="#" method="post">
		  <div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="firstname">First Name</label>
		      <input type="text" class="form-control" id="firstname" name="firstname" required="true" value="<?php echo $user_data['firstname'] ?>">
		    </div>
		    <div class="form-group col-md-6">
		      <label for="lastname">Last Name</label>
		      <input type="text" class="form-control" id="lastname" name="lastname" required="true" value="<?php echo $user_data['lastname'] ?>">
		    </div>
		  </div>
		   <input type="hidden" id="idnumber" name="idnumber" value="<?php echo $user_data['user_id'] ?>">
		  <div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="cityname">City</label>
		      <select id="cityname" name="cityname" class="form-control">
		        <option value="c">Choose...</option>
		   	<?php 


				if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) { 
				    	?>
				    		<option value="<?php echo $row['city_id']?>"  <?php if ($row['city_id'] == $user_data['city_id']) {echo "selected";} ?>><?php echo $row['name']?></option>

				    	<?php

				    	

					    }
					}


			   	?>
		       
		      </select>
		    </div>
		    <div class="form-group col-md-6">
		    	<label for="status">Status</label>
			      <select id="status" name="status" class="form-control">
			        <option value="c">Choose...</option>
			        <option value="1" <?php if ($user_data['status'] == '1') {echo "selected";} ?>>Confirmed</option>
			        <option value="0" <?php if ($user_data['status'] == '0') {echo "selected";} ?>>Safe</option>
			        <option value="2" <?php if ($user_data['status'] == '2') {echo "selected";} ?>>Suspected</option>
			      </select>
		    </div>

		  </div>
		  
		  <button value= "individualform"  type="submit" class="btn btn-primary">Update</button>
		</form>

</div>




<?php


?>