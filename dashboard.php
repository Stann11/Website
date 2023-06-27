<?php 
error_reporting(0);
ini_set('display_errors', 0);
try {
    include("./db.php");
    session_start();
    if (isset($_SESSION['access_token'])  &&  isset($_SESSION['user_email_address'])) {
        $email=$_SESSION['user_email_address'];
        $query="select type from login_info where email='$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['type']=="user") {
                    echo '<script>window.location.href = "dist/index.php"</script>';
                } elseif ($row['type']=="admin") {
                    echo '<script>window.location.href = "dist/admin.php"</script>';
                } else {
                    session_destroy();
                    echo '<script>window.location.href = "404.html"</script>';
                }
            }
        } else {
            session_destroy();
            echo '<script>window.location.href = "404.html"</script>';
        }
    } else {
        session_destroy();
        echo '<script>window.location.href = "404.html"</script>';
    }
}
catch(Exception $e){
    session_destroy();
  echo '<script>window.location.href = "404.html"</script>';
}

?>