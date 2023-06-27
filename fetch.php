<?php
error_reporting(0);
ini_set('display_errors', 0);
try {

if (isset($_POST['q'])) {
  include "./db.php";
    if ($_POST["q"]==3) {
        $query="select * from event order by Date DESC limit 3;";
        $result = mysqli_query($conn, $query);
        $main=array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $intermidiate=array();
                $intermidiate['Name']=$row['Name'];
                $intermidiate['Date']=$row['Date'];
                $intermidiate['s_details']=$row['s_details'];
                $intermidiate['f_details']=$row['f_details'];
                $intermidiate['img_folder']=$row['img_folder'];
                $intermidiate['coordinators']=$row['coordinators'];
                $intermidiate['img_type']=$row['img_type'];
                $intermidiate['total_image']=$row['total_image'];
                $intermidiate['faicon']=$row['faicon'];
                array_push($main, $intermidiate);
            }
            echo json_encode($main);
        }
    
    } else if($_POST['q']==0) {
        $query="select * from event order by Date DESC;";
        $result = mysqli_query($conn, $query);
        $main=array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $intermidiate=array();
                $intermidiate['Name']=$row['Name'];
                $intermidiate['Date']=$row['Date'];
                $intermidiate['s_details']=$row['s_details'];
                $intermidiate['f_details']=$row['f_details'];
                $intermidiate['img_folder']=$row['img_folder'];
                $intermidiate['coordinators']=$row['coordinators'];
                $intermidiate['img_type']=$row['img_type'];
                $intermidiate['total_image']=$row['total_image'];
                $intermidiate['faicon']=$row['faicon'];
                array_push($main, $intermidiate);
            }
            echo json_encode($main);
        }
    }
}
else{
  header("location:index.html");
}
}
catch(Exception $e){
    //session_destroy();
     echo '<script>window.location.href = "404.html"</script>';
}


?>