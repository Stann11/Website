<?php
$path = ltrim($_SERVER['REQUEST_URI'], '/');    // Trim leading slash(es)
$elements = explode('/', $path);                // Split path on slashes
function response($f, $response_code, $response_desc)
 {
     if ($response_code==200) {
         $file = $f;
         $filename = $f;
         header('Content-type: application/pdf');
         header('Content-Disposition: inline; filename=Certificate');
         header('Content-Transfer-Encoding: binary');
         header('Content-Length: ' . filesize($file));
         header('Accept-Ranges: bytes');
         @readfile($file);
     } else {
         $path="../404.html";
         echo "<script>window.location.href = '$path'</script>";
     }
 }


try {
    if(sizeof($elements)==3 and preg_match('/[\[\@0-9a-zA-Z]/i',$elements[2]) and preg_match('/[0-9]/i',$elements[1]) ){
  $var1=$elements[0];
  switch($var1){
      case "certificate":
      $file=$path;
        if (file_exists($file.'.pdf')) {
            $f=$file.'.pdf';
            $response_code=200;
            $response_desc="Ok";
            response($f, $response_code, $response_desc);
        } else {
            response(null, null, 404, "No Record Found");
        }
      break;
         case "core":
      $file=$path;
        if (file_exists($file.'.pdf')) {
            $f=$file.'.pdf';
            $response_code=200;
            $response_desc="Ok";
            response($f, $response_code, $response_desc);
        } else {
            response(null, null, 404, "No Record Found");
        }
      break;
       case "coordinator":
      $file=$path;
        if (file_exists($file.'.pdf')) {
            $f=$file.'.pdf';
            $response_code=200;
            $response_desc="Ok";
            response($f, $response_code, $response_desc);
        } else {
            response(null, null, 404, "No Record Found");
        }
      break;
       case "member":
      $file=$path;
        if (file_exists($file.'.pdf')) {
            $f=$file.'.pdf';
            $response_code=200;
            $response_desc="Ok";
            response($f, $response_code, $response_desc);
        } else {
            response(null, null, 404, "No Record Found");
        }
      break;
       default:
            response(null, null, 404, "No Record Found");
       break;
      
  }
}
else{
    header("location:../404.html");
}
   
    
   
}
catch(Exception $e){
    $path="../404.html";
    echo "<script>window.location.href = '$path'</script>";
}


?>