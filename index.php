<?php 
$page = 'index';
include('inc/templates/header.php');
include ('inc/db/dbconnect.php');


/*Pagination and Search Query code goes here*/
//Get current page
if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

//Pagination formula
$no_of_records_per_page = 100;
$offset = ($pageno-1) * $no_of_records_per_page; 

//Get the total number of fpages
$total_pages_sql = "SELECT COUNT(*) FROM individual";
$result = mysqli_query($conn,$total_pages_sql);
$total_rows = mysqli_fetch_array($result)[0];
$total_pages = ceil($total_rows / $no_of_records_per_page);

if (isset($_GET['search'])) {

  //clean the search value first
  $search_value = strip_tags(trim($_GET['search']));

  //Fetch the user ID
  $sql = "SELECT individual.user_id, individual.firstname, individual.lastname, individual.city_id, individual.status, cities.name FROM individual JOIN cities ON individual.city_id=cities.city_id WHERE individual.user_id = '".$search_value."'";
  //exit;
}else{

  $sql = "SELECT individual.user_id, individual.firstname, individual.lastname, individual.city_id, individual.status, cities.name FROM individual JOIN cities ON individual.city_id=cities.city_id LIMIT $offset, $no_of_records_per_page";

}



$result = $conn->query($sql);


?>


<div class="row" style="padding-left: 15px;padding-right: 15px;">
        <div class="col-md-4">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>

              <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                <a class="page-link" tabindex="-1" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Previous</a>
              </li>
              <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>"><a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a></li>
              <li class="page-item">
                <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="col-md-4" style="text-align: center;line-height: 7px;">
           <p>Page <? echo $pageno?> of <?php echo $total_pages?></p>
            <p>Total Records : <?php echo $total_rows ?></p>
          
        </div>
        <div class="col-md-4">

          <form action="#">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Enter User ID" aria-label="Search" name="search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
              <?php

              if (isset($_GET['search'])) {?>

                <a href="index.php" class="btn btn-danger active">Clear Search</a>
                <?php
                }

              ?>
            </form>


              </div>
            </div>
          </div>
        </div>



<div class="col-md-12 text-center">   
  <table class="table">
  <thead>
    <tr>
      <th scope="col">ID Number</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">City</th>
      <th scope="col">Status</th>
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
          <th scope="row"><?php echo $row["user_id"] ?></th>
          <td><?php echo $row["firstname"] ?></td>
          <td><?php echo $row["lastname"] ?></td>
          <td><?php echo $row["name"] ?></td>
          <td><?php 

            if ($row["status"] == '1') {
              echo '<span style="color:red">Comfirmed</span>';
            }elseif($row["status"] == '0'){
              echo '<span style="color:green">Safe</span>';
            }elseif ($row["status"] == '2') {
              echo '<span style="color:orange">Suspected</span>';
            }

           ?></td>
          <td><a href="view.php?id=<?php echo $row["user_id"] ?>&m=01&type=1" target="_blank" class="btn btn-primary active" role="button" aria-pressed="true">View Map</a> || <a href="edit.php?id=<?php echo $row["user_id"] ?>" target="_blank" class="btn btn-success active" role="button" aria-pressed="true">Edit User</a></td>
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

<div class="clearfix"></div>

<div class="row" style="padding-left: 15px;padding-right: 15px;">
        <div class="col-md-4">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>

              <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                <a class="page-link" tabindex="-1" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Previous</a>
              </li>
              <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>"><a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a></li>
              <li class="page-item">
                <a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="col-md-4" style="text-align: center;line-height: 7px;">
           <p>Page <? echo $pageno?> of <?php echo $total_pages?></p>
            <p>Total Records : <?php echo $total_rows ?></p>
          
        </div>
        <div class="col-md-4">

          <form action="#">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Enter User ID" aria-label="Search" name="search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="submit">Search</button>
              <?php

              if (isset($_GET['search'])) {?>

                <a href="index.php" class="btn btn-danger active">Clear Search</a>
                <?php
                }

              ?>
            </form>


              </div>
            </div>
          </div>
        </div>



</div>





</div>





<?php

include('footer.php')

?>