<?php
error_reporting(0);
ini_set('display_errors', 0);
try {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eagerbeavers";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
catch(Exception $e){
  echo '<script>alert("error ")</script>';
  //echo '<script>window.location.href = "404.html"</script>';
}
  ?>