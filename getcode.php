<?php
error_reporting(0);
ini_set('display_errors', 0);
try {
    include('config.php');

    $login_button = '';
    $_SESSION['btn']='';
    $d;

    //This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
    if (isset($_GET["code"])) {
        //It will Attempt to exchange a code for an valid authentication token.
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

        //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
        if (!isset($token['error'])) {
  
  //Set the access token used for requests
            $google_client->setAccessToken($token['access_token']);

            //Store "access_token" value in $_SESSION variable for future use.
            $_SESSION['access_token'] = $token['access_token'];

            $_SESSION['id_token']=$token['id_token'];
            //Create Object of Google Service OAuth 2 class
            $google_service = new Google_Service_Oauth2($google_client);

            //Get user profile data from google
            $data = $google_service->userinfo->get();
  
            $d=$data;
            //Below you can find Get profile data and store into $_SESSION variable
            if (!empty($data['given_name'])) {
                $_SESSION['user_first_name'] = $data['given_name'];
            }

            if (!empty($data['family_name'])) {
                $_SESSION['user_last_name'] = $data['family_name'];
            }

            if (!empty($data['email'])) {
                $_SESSION['user_email_address'] = $data['email'];
            }

            if (!empty($data['gender'])) {
                $_SESSION['user_gender'] = $data['gender'];
            }

            if (!empty($data['picture'])) {
                $_SESSION['user_image'] = $data['picture'];
            }

            if (!empty($data['hd'])) {
                $_SESSION['hd']=$data['hd'];
            }
        }
    }

    //This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
    if (!isset($_SESSION['access_token'])) {
        //Create a URL to obtain user authorization
        $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="https://img.icons8.com/color/48/000000/google-logo.png" /></a>';
        $_SESSION['btn']=$login_button;
    }
}
catch(Exception $e){
    session_destroy();
     echo '<script>window.location.href = "404.html"</script>';
}

?>