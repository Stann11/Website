<?php
error_reporting(0);
ini_set('display_errors', 0);
//config.php
try {
    //Include Google Client Library for PHP autoload file
    require_once './vendor/autoload.php';

    //Make object of Google API Client for call Google API
    $google_client = new Google_Client();

    //Set the OAuth 2.0 Client ID
    $google_client->setClientId('159441097499-8om89juga89r60kponmvco5sdcmg51ft.apps.googleusercontent.com');

    //Set the OAuth 2.0 Client Secret key
    $google_client->setClientSecret('W2G4DPu0VzS1VuE_sYYtjroq');

    //Set the OAuth 2.0 Redirect URI
    $google_client->setRedirectUri('http://localhost/eagerbeavers/info.php');

//
    $google_client->addScope('email');

    $google_client->addScope('profile');

    //start session on web page
    session_start();
}
catch(Exception $e){
    session_destroy();
     echo '<script>window.location.href = "404.html"</script>';
}

?>