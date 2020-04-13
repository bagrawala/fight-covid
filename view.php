<?php 

$page = 'view';
include('inc/templates/header.php');
include ('inc/db/dbconnect.php');

if (isset($_GET) AND !empty($_GET)) {
  $user_id = $_GET["id"];
  $month = $_GET["m"];
  $type = $_GET["type"];
  $date = $_GET["date"];
  $cluster_no = $_GET["c"];
}

//var_dump(str_replace('/','-',$_GET));

//exit;



?>


<!DOCTYPE html>
<html>
  <head>
    <style>
       /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 80%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>

    <!--date filter to be added-->

    <div class="row" style="padding: 15px;">
        <div class="col-md-4">

        </div>

        <div class="col-md-4">
          <form action="view.php">
            <input type="hidden" id="id" name="id" value="<?php echo $user_id?>">
            <input type="hidden" id="type" name="type" value="<?php echo $type?>">
            <input type="hidden" id="c" name="c" value="<?php echo $cluster_no?>">
            <input name = "date" id="datepicker" width="276" />
        </div>
        <div class="col-md-4">
          <button class="btn btn-outline-primary" type="submit">Search Date</button>
          <?php

              if (isset($_GET['date'])) {?>

                <a href="view.php?id=<?php echo $user_id?>&m=01&type=<?php echo $type?>&c=10" class="btn btn-danger active">Clear Search</a>
                <?php
                }

              ?>
          </div>
        </div>
      </form>



    <!--The div element for the map -->
    <div id="map"></div>
    <script>

      function downloadUrl(url,callback) {
         var request = window.ActiveXObject ?
             new ActiveXObject('Microsoft.XMLHTTP') :
             new XMLHttpRequest;

         request.onreadystatechange = function() {
           if (request.readyState == 4) {
             request.onreadystatechange = doNothing;
             callback(request, request.status);
           }
         };

         request.open('GET', url, true);
         request.send(null);
        }


        // Initialize and add the map
        function initMap() {
          // The location of Uluru
          //var uluru = {lat: 0, lng: 0};
          // The map, centered at center
          /*var map = new google.maps.Map(
              document.getElementById('map'), {zoom: 1, center: uluru});*/
          

          var mapElement = document.getElementById('map');
          var map = new google.maps.Map(mapElement, { center: new google.maps.LatLng(0, 0), zoom: 1 });
          var iw = new google.maps.InfoWindow();

          var oms = new OverlappingMarkerSpiderfier(map, {
                            markersWontMove: true,
                            markersWontHide: true,
                            basicFormatEvents: true
                          });



          var iconBase =
            'http://maps.google.com/mapfiles/kml/pal4/';

          var icons = {
            safe: {
              icon: iconBase + 'icon16.png'
            },
            suspected: {
              icon: iconBase + 'icon21.png'
            },
            confirmed: {
              icon: iconBase + 'icon55.png'
            }
          };


          $.ajax({
            type: "GET",
            async: true,
            url: "xmlgen.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&date=<?php echo $date?>&c=<?php echo $cluster_no?>",
            dataType: "xml",
            success:
            function (xml) {
                var places = xml.documentElement.getElementsByTagName("marker");
                //console.log(places[0])
                for (var i = 0; i < places.length; i++) {
                  var lat = places[i].getAttribute('lat');
                  var long = places[i].getAttribute('lng');
                  var name = places[i].getAttribute('name');
                  var address = places[i].getAttribute('address');
                  var status = places[i].getAttribute('status');
                  var userid = places[i].getAttribute('user_id');
                  var latLng = new google.maps.LatLng(lat, long);
                  var marker = new google.maps.Marker({
                    position:  latLng,
                    icon: icons[status].icon,
                    map: map,
                    title: 'USER ID: '+ userid +'\nLOCATION: '+name+' ('+address+')'
                  });
                  google.maps.event.addListener(marker, 'spider_click', function(e) {  // 'spider_click', not plain 'click'
                    iw.setContent(markerData.text);
                    iw.open(map, marker);
                  });
                   oms.addMarker(marker);  // adds the marker to the spiderfier _and_ the map
                }
            }
          });

          

        }
    </script>
    <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial)
    * The callback parameter executes the initMap() function
    -->
<?php 

if ($type == '2') {
  //Display the people filter
?>

 <!-- <div class="col-md-12 text-center" style="padding-top: 10px; padding-bottom: 10px">
    <ul class="nav nav-pills center-pills justify-content-center">
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='10') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=10">10 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='25') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=25">25 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='50') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=50">50 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='100') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=100">100 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='200') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=200">200 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='500') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=500">500 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='1000') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>&c=1000">1000 People</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($cluster_no =='') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=<?php echo $month?>&type=<?php echo $type?>">More than 1000 People</a>
      </li>
    </ul>
</div>-->

<?php

}



?>


    

<div class="col-md-12 text-center" style="padding-top: 10px; padding-bottom: 10px">
    <ul class="nav nav-pills center-pills justify-content-center">
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='01') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=01&type=<?php echo $type?>&c=<?php echo $cluster_no?>">January</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='02') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=02&type=<?php echo $type?>&c=<?php echo $cluster_no?>">February</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='03') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=03&type=<?php echo $type?>&c=<?php echo $cluster_no?>">March</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='04') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=04&type=<?php echo $type?>&c=<?php echo $cluster_no?>">April</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='05') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=05&type=<?php echo $type?>&c=<?php echo $cluster_no?>">May</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='06') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=06&type=<?php echo $type?>&c=<?php echo $cluster_no?>">June</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='07') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=07&type=<?php echo $type?>&c=<?php echo $cluster_no?>">July</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='08') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=08&type=<?php echo $type?>&c=<?php echo $cluster_no?>">August</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='09') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=09&type=<?php echo $type?>&c=<?php echo $cluster_no?>">September</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='10') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=10&type=<?php echo $type?>&c=<?php echo $cluster_no?>">October</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='11') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=11&type=<?php echo $type?>&c=<?php echo $cluster_no?>">November</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php if ($month =='12') { echo 'active';}?>" href="view.php?id=<?php echo $user_id?>&m=12&type=<?php echo $type?>&c=<?php echo $cluster_no?>">December</a>
      </li>
    </ul>
<div class="col-md-12 text-center">


<script src="https://cdnjs.cloudflare.com/ajax/libs/OverlappingMarkerSpiderfier/1.0.3/oms.min.js"></script>
    <script async defer
   src="https://maps.googleapis.com/maps/api/js?key=&callback=initMap">
    </script>
    <script>
        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap4'
        });

    </script>
  </body>
</html>












<?php

include('inc/templates/footer.php')

?>
