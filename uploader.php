<?php 

$page = 'uploader';
include('inc/templates/header.php');
include ('inc/db/dbconnect.php');

//Fetch All cities for drop down
$citysql = "SELECT city_id, name FROM cities";
$cityresult = $conn->query($citysql);

//Set the options string
if ($cityresult->num_rows > 0) {
	    // output data of each row
	    $city_options = '';
	    while($row = $cityresult->fetch_assoc()) { 
	    	
	    		$city_options .= "<option value=".$row['city_id'].">".$row['name']."</option>";

	    	

		    }
		}


//echo $city_options;
//exit;

//Do a check if there is any upload

if (isset($_POST) AND !empty($_POST)) {
	
	
	if (isset($_FILES['uploaddata2'])) {
		
		// Count # of uploaded files in array
		$total = count($_FILES['uploaddata2']['name']);

		//$strJsonFileContents = file_get_contents( $_FILES['uploaddata2']['tmp_name'][0] );
		//$historyArray = json_decode($strJsonFileContents, true);

		// Loop through each file and save data to db
		for( $i=0 ; $i < $total ; $i++ ) {

		  $strJsonFileContents = file_get_contents( $_FILES['uploaddata2']['tmp_name'][$i] );
		  $historyArray = json_decode($strJsonFileContents, true);




		  $cityArray = explode('-' ,$_FILES['uploaddata2']['name'][$i]);

		  if (count($cityArray ) > 2) {
				?>
					<div class="col-md-12">  
						<div class="alert alert-danger" role="alert">
						  Invalid file name format.
						</div>
					</div>

				<?php
				exit;
			}

			$idNumber = $cityArray[0];
			$monthNameExt = $cityArray[1];
			$monthNameExtArr = explode('.', $monthNameExt);
			$monthName = $monthNameExtArr[0];

			$stamp = time();

			$lastname = $stamp.'-'.$i;

			$month = getMonthNumber($monthName);


			if ($month == false) {
				?>
					<div class="col-md-12">  
						<div class="alert alert-danger" role="alert">
						  Invalid file name format.
						</div>
					</div>

				<?php
				exit;
			}

			//Before insert need to confrirm ID doesnt exist
		 	$sql = "SELECT id FROM individual_markers WHERE user_id='".$idNumber."' AND month='".$month."'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output return erro rencountered
			    // TODO if user id already exits load for the new month
			    ?>
			    	<div class="col-md-12">  
						<div class="alert alert-danger" role="alert">
						  A user with that ID aleady  and the same month exist, cannot add again.
						</div>
					</div>

			    <?php
			    exit;	    
			} else {
			    

			    //Check if user is existing already and the add the new month data
			    $sql = "SELECT id FROM individual WHERE user_id='".$idNumber."'";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					//Dont add to indvidual table again
				}else{
					//Inserting data to DB first set hte user info on individuals table
					$sql = "INSERT INTO individual (user_id, firstname, lastname, city_id)
					VALUES ('".$idNumber."', 'Person', '".$lastname."', '".$_POST["cityname"]."')";


					if ($conn->query($sql) === TRUE) {
						//Do nothing
					} else {
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}

				

				//Loop through and inser the data into the DB
					foreach ($historyArray["timelineObjects"] as $history) {
						if (!empty($history["placeVisit"])) {
							
							$longitude = $history["placeVisit"]["location"]["longitudeE7"]/10000000 ;
							$latitude = $history["placeVisit"]["location"]["latitudeE7"]/10000000 ;
							$name = $conn->real_escape_string($history["placeVisit"]["location"]["name"]);
							$address = $conn->real_escape_string($history["placeVisit"]["location"]["address"]);
							$date = date('m-d-Y', $history["placeVisit"]["duration"]["startTimestampMs"]/1000);



							$sql = "INSERT INTO individual_markers (user_id, month, city_id, name, address, lat, lng, event_date)
								VALUES ('".$idNumber."', '".$month."', '".$_POST["cityname"]."', '".$name."', '".$address."', ".$latitude.", ".$longitude.", '".$date."')";


								if ($conn->query($sql) === TRUE) {
									//Do nothing
								} else {
								    //echo "Error: " . $sql . "<br>" . $conn->error;
								    ?>
								    <div class="col-md-12">  
								      <div class="alert alert-danger" role="alert">
								        Upload unsuccessful, please try again
								      </div>
								    </div>

								    <?php
								}

						}
						
						
					}


				

			}



		}

				?>

				<!-- Show success message --->
				<div class="col-md-12">  
					      <div class="alert alert-success" role="alert">
					        Upload sucessful, click <a href="uploader.php">HERE</a> to go back to the uploader
					      </div>
					    </div>


				<?php
				exit;

		


	}else{

		$strJsonFileContents = file_get_contents( $_FILES['uploaddata']['tmp_name'] );
		$historyArray = json_decode($strJsonFileContents, true);

		//Individual Data Upload
		
		
			//Before insert need to confrirm ID doesnt exist
		 	$sql = "SELECT id FROM individual_markers WHERE user_id='".$_POST["idnumber"]."' AND month='".$_POST["month"]."'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output return erro rencountered
			    ?>
			    	<div class="col-md-12">  
						<div class="alert alert-danger" role="alert">
						  A user with that ID aleady  and the same month exist, cannot add again.
						</div>
					</div>

			    <?php
			    exit;	    
			} else {
			    

			    //Check if user is existing already and the add the new month data
			    $sql = "SELECT id FROM individual WHERE user_id='".$_POST["idnumber"]."'";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					//Dont add to indvidual table again
				}else{
					//Inserting data to DB first set hte user info on individuals table
					$sql = "INSERT INTO individual (user_id, firstname, lastname, city_id)
					VALUES ('".$conn->real_escape_string($_POST["idnumber"])."', '".$conn->real_escape_string($_POST["firstname"])."', '".$conn->real_escape_string($_POST["lastname"])."', '".$_POST["cityname"]."')";



					if ($conn->query($sql) === TRUE) {
						//Do nothing
					} else {
					    echo "Error: " . $sql . "<br>" . $conn->error;
					}
				}

				

				//Loop through and inser the data into the DB
					foreach ($historyArray["timelineObjects"] as $history) {
						if (!empty($history["placeVisit"])) {
							
							$longitude = $history["placeVisit"]["location"]["longitudeE7"]/10000000 ;
							$latitude = $history["placeVisit"]["location"]["latitudeE7"]/10000000 ;
							$name = $conn->real_escape_string($history["placeVisit"]["location"]["name"]);
							$address = $conn->real_escape_string($history["placeVisit"]["location"]["address"]);
							$date = date('m-d-Y', $history["placeVisit"]["duration"]["startTimestampMs"]/1000);



							$sql = "INSERT INTO individual_markers (user_id, month, city_id, name, address, lat, lng, event_date)
								VALUES ('".$conn->real_escape_string($_POST["idnumber"])."', '".$_POST["monthdata"]."', '".$_POST["cityname"]."', '".$name."', '".$address."', ".$latitude.", ".$longitude.", '".$date."')";


								if ($conn->query($sql) === TRUE) {
									//Do nothing
								} else {
								    //echo "Error: " . $sql . "<br>" . $conn->error;
								    ?>
								    <div class="col-md-12">  
								      <div class="alert alert-danger" role="alert">
								        Upload unsuccessful, please try again
								      </div>
								    </div>

								    <?php
								}

						}
						
						
					}


				?>

				<!-- Show success message --->
				<div class="col-md-12">  
					      <div class="alert alert-success" role="alert">
					        Upload sucessful, click <a href="uploader.php">HERE</a> to go back to the uploader
					      </div>
					    </div>


				<?php
				exit;
			}


	}


	
}


function getMonthNumber($monthName){
	$arr = array ('01' => 'january', '02' => 'february', '03' => 'march', '04' => 'april', '05' => 'may', '06' => 'june', '07' => 'july', '08' => 'august', '09' => 'september', '10' => 'october', '11' => 'november', '12' => 'december' );
	$key = array_search(strtolower($monthName), array_map('strtolower', $arr));
	return $key;
}



?>

<div class="col-md-12">  
	<div class="alert alert-primary" role="alert">
	  Upload location history in .json format only
	</div>
</div>

<div class="col-md-12"> 
<div class="accordion" id="accordionExample">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Individual Data Upload
        </button>
      </h2>
    </div>

    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body">
      
      	<form action="#" method="post" enctype="multipart/form-data">
		  <div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="firstname">First Name</label>
		      <input type="text" class="form-control" id="firstname" name="firstname" required="true">
		    </div>
		    <div class="form-group col-md-6">
		      <label for="lastname">Last Name</label>
		      <input type="text" class="form-control" id="lastname" name="lastname" required="true">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="idnumber">ID Number</label>
		    <input type="number" class="form-control" id="idnumber" name="idnumber" placeholder="" required="true">
		  </div>
		  <div class="form-row">
		    <div class="form-group col-md-6">
		      <label for="monthdata">Month of Location History</label>
		      <select id="monthdata" name="monthdata" class="form-control">
		        <option selected value="0">Choose...</option>
		        <option value="01">January</option>
		        <option value="02">February</option>
		        <option value="03">March</option>
		        <option value="04">April</option>
		        <option value="05">May</option>
		        <option value="06">June</option>
		        <option value="07">July</option>
		        <option value="08">August</option>
		        <option value="09">September</option>
		        <option value="10">October</option>
		        <option value="11">Novemeber</option>
		        <option value="12">December</option>
		      </select>
		    </div>
		    <div class="form-group col-md-6">
		    	<label for="customFile">Select Location History File</label>
		    	<div class="custom-file">
				  <input type="file" name="uploaddata" class="custom-file-input" id="customFile">
				  <label class="custom-file-label" id="custom-file-1-label" for="customFile">Choose file</label>
				</div>
		    </div>

		  </div>

		  <div class="form-row">
		  	<div class="form-group col-md-6">
		      <label for="cityname">City</label>
		      <select id="cityname" name="cityname" class="form-control">
		        <option value="c">Choose...</option>
		   	<?php 


				echo $city_options;

			   	?>
		       
		      </select>
		    </div>
		  </div>
		  
		  <button value= "individualform"  type="submit" class="btn btn-primary">Upload History</button>
		</form>

      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          City Data Upload
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
        	<form action="#" method="post" enctype="multipart/form-data">
			  <div class="form-row">
			    <div class="form-group col-md-6">
		      <label for="cityname">City</label>
		      <select id="cityname" name="cityname" class="form-control">
		        <option value="c">Choose...</option>
				   	<?php 


						echo $city_options;

					   	?>
				       
				      </select>
				    </div>
			    <div class="form-group col-md-6">
			    	<label for="customFile2">Select Location History File</label>
			    	<div class="custom-file">
					  <input type="file" name="uploaddata2[]"  multiple="multiple" class="custom-file-input" id="customFile2">
					  <label class="custom-file-label" id="custom-file-2-label" for="customFile2">Choose file</label>
					</div>
			    </div>
			  </div>
			  
			  <button value= "individualform"  type="submit" class="btn btn-primary">Upload History</button>
			</form>
      </div>
    </div>
  </div>
</div>
</div>

<script>
            $('#customFile').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                $(this).next('#custom-file-1-label').html(fileName);
            })


            $('#customFile2').on('change',function(){
                //get the file name
                var fileName = $(this).val();
                //replace the "Choose a file" label
                console.log(fileName);
                $(this).next('#custom-file-2-label').html('Multiple Files Selected');
            })
        </script>
<?php

include('inc/templates/footer.php')

?>


