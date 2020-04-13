<?php 

$page = 'cities';
include('inc/templates/header.php');
include ('inc/db/dbconnect.php');


$sql = "SELECT DISTINCT individual.city_id, cities.name FROM individual JOIN cities ON individual.city_id=cities.city_id";
$result = $conn->query($sql);

?>


<div class="col-md-12 text-center">   
  <table class="table">
  <thead>
    <tr>
      <th scope="col">ID Number</th>
      <th scope="col">City Name</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>

<?php

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        ?>

        <tr>
          <th scope="row"><?php echo $row["city_id"] ?></th>
          <td><?php echo $row["name"] ?></td>
          <td><a href="view.php?id=<?php echo $row["city_id"] ?>&m=01&type=2&c=" target="_blank" class="btn btn-primary active" role="button" aria-pressed="true">View Map</a></td>
        </tr>

        <?php

    }
} else {
    ?>

    <div class="col-md-12">  
      <div class="alert alert-primary" role="alert">
        No Individual records upload, go to the uploader tab and add individual record
      </div>
    </div>

    <?php
}

?>

  </tbody>
</table>
</div>

<?php

include('inc/templates/footer.php')

?>