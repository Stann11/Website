<?php
error_reporting(0);
ini_set('display_errors', 0);
try {
    //include('config.php');
    include('db.php');
    include('getcode.php');
    require_once './vendor/autoload.php';
    if ($_SESSION['btn'] == '') {
        $domain=$_SESSION['hd'];
        if ($domain=="charusat.edu.in") {
            $CLIENT_ID='159441097499-8om89juga89r60kponmvco5sdcmg51ft.apps.googleusercontent.com';
            $id_token=$_SESSION['id_token'];
            $client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
            $payload = $client->verifyIdToken($id_token);
            if ($payload) {
                if (isset($payload['hd']) && $payload['email_verified']) {
                    if ($payload['hd']=="charusat.edu.in") {
                        $regex="/(@charusat\.edu\.in)$/";
                        if (!preg_match($regex, $payload['email'])) {
                            session_destroy();
                            echo '<script>window.location.href="../404.html"</script>';
                        }
                    } else {
                        session_destroy();
                        echo '<script>window.location.href="../404.html"</script>';
                    }
                } else {
                    session_destroy();
                    echo '<script>window.location.href="../404.html"</script>';
                }
            } else {
                session_destroy();
                echo '<script>window.location.href="../404.html"</script>';
            }
            $access_token=$_SESSION['access_token'];
            $gname=$_SESSION['user_first_name'];
            $email=strtolower($_SESSION['user_email_address']);
            $fname=$_SESSION['user_last_name'];
            $_SESSION['timestamp']=$payload['exp'];
            $flag=true;
            $query="select access_token,email from login_info where email='$email'";
            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (strtolower($row['email']) ==$email) {
                        $flag=false;
                        $sql = "UPDATE login_info SET access_token='$access_token' WHERE email='$email'";
                        if (mysqli_query($conn, $sql)) {
                            date_default_timezone_set('Asia/Kolkata');
                            $date = date('y-m-d');
                            $time=date('h:i:s');
                            $type="user";
                            $sql1="INSERT into log (access_token,given_name,email,date,time) values('".$access_token."','".$gname."','".$email."','".$date."','".$time."')";
                            if (mysqli_query($conn, $sql1)) {
                                header("location:dashboard.php");
                            } else {
                                session_destroy();
                                header("location:404.html") ;
                            }
                        } else {
                            session_destroy();
                            header("location:404.html") ;
                        }
                    }
                }
            }
            if ($flag) {
                $type="user";
                $sql ="INSERT INTO login_info(access_token,given_name,family_name,email,type) values('".$access_token."','".$gname."','".$fname."','".$email."','".$type."')";
                if (mysqli_query($conn, $sql)) {
                    date_default_timezone_set('Asia/Kolkata');
                    $date = date('y-m-d');
                    $time=date('h:i:s');
                
                    $sql1="INSERT into log (access_token,given_name,email,date,time) values('".$access_token."','".$gname."','".$email."','".$date."','".$time."')";
                    if (mysqli_query($conn, $sql1)) {
                        header("location:dashboard.php");
                    } else {
                        session_destroy();
                        header("location:404.html") ;
                    }
                } else {
                    session_destroy();
                    header("location:404.html") ;
                }
            }
        } else {
            session_destroy();
            header("location:404.html") ;
        }
    } else {
        session_destroy();
        header("location:404.html") ;
    }
}
catch(Exception $e){
    session_destroy();
     echo '<script>window.location.href = "404.html"</script>';
}
?>
  