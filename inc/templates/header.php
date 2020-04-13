<?php ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <title>COVID-19 Tracker</title>

    <style type="text/css">
    	/*.center-pills { display: inline-block; }*/
      .container-fluid {
        margin-bottom: 20px;
      }
    </style>

  </head>
  <body>


      <!-- As a heading -->
    <nav class="navbar navbar-light bg-light" style="color: white">
      <span class="navbar-brand mb-0 h1">COVID-19 Tracker</span>
    </nav>


    <?php

    if ($page == 'view' OR $page == 'edit' ) {
      //Show noe menu 
    }else{
      ?>

      <div class="container-fluid">
    
      <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link <?php if ($page =='index') { echo 'active';}?>" href="index.php">Individuals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($page =='cities') { echo 'active';}?>" href="cities.php">Cities</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($page =='uploader') { echo 'active';}?>" href="uploader.php">Uploader</a>
      </li>
    </ul>
  </div>

    <?php 

      }

    ?>

  	

<?php ?>

