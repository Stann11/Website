<?php
error_reporting(0);
ini_set('display_errors', 0);
try {
    include("../db.php");

   session_start();
    if (!isset($_SESSION['access_token'])  &&  !isset($_SESSION['user_email_address'])) {
        session_destroy();
        echo '<script>window.location.href = "../404.html"</script>';
    } else {
        $email=$_SESSION['user_email_address'];
        $query="select type from login_info where email='$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['type']=="user") {
                    echo '<script>window.location.href = "../404.html"</script>';
                }
            }
        } else {
            session_destroy();
            echo '<script>window.location.href = "../404.html"</script>';
        }
    }
    $date="";
    if (isset($_POST['uploadbt'])) {
        if (isset($_FILES['file'],$_POST['s_de'],$_POST['f_de'],$_POST['coordinators'],$_POST['faicon'],$_POST['Name'],$_POST['folder'],$_POST['date'])) {
            
            $date=$_POST['date'];
            if ($date!="") {
                $orgDate=$_POST['date'];
               
                $folder=$_POST['folder'];
                $path1= "../img/".$folder;
                $s_de=$_POST['s_de'];
                $f_de=$_POST['f_de'];
                $coor=json_encode(explode(",",$_POST['coordinators']),JSON_FORCE_OBJECT);
                $faicon=$_POST['faicon'];
                $name=$_POST['Name'];
                if (!is_dir($path1)) {
                    if (mkdir($path1, 0755, true)) {  
                        $countfiles = count($_FILES['file']['name']);
                        $path = $_FILES['file']['name'][0];
                        $ext = pathinfo($path, PATHINFO_EXTENSION);
                        // Looping all files
                        for ($i=0;$i<$countfiles;$i++) {
                            $filename =strval($i+1).".".$ext;
               
                            // Upload file
                            move_uploaded_file($_FILES['file']['tmp_name'][$i], $path1."/".$filename);
                        }
                        $sql1="INSERT INTO `event`(`Name`, `Date`, `s_details`, `f_details`, `img_folder`, `coordinators`, `img_type`, `total_image`, `faicon`) VALUES ('".$name."','".$orgDate."','".$s_de."','".$f_de."','".$folder."','".$coor."','".$ext."','".$countfiles."','".$faicon."')";
                        if (mysqli_query($conn, $sql1)) {
                            echo '<script>alert("Data Uploaded")</script>';
                            header("Refresh:0");
                        } else {
                            echo "<script>alert('error')</script>";
                        }
                    }
                }
                else{
                    echo '<script>alert("Change The Folder Name!!!!")</script>';
                }
                
            } else {
                echo '<script>alert("first upload data")</script>';
            }
        }
        else{
            echo '<script>alert("Fill all details")</script>';
        }
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href = "../login.php"</script>';
    }
    
  if (time() - $_SESSION['timestamp'] > 300) { //subtract new timestamp from the old one
        session_destroy();
        echo '<script>window.location.href = "../login.php"</script>'; //redirect to index.php
        exit;
    } else {
        #$_SESSION['timestamp'] = time(); //set new timestamp
    }
   // 
}
catch(Exception $e){
    session_destroy();
    echo '<script>alert("$e")</script>';
    // echo '<script>window.location.href = "../404.html"</script>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>My Certificates</title>
    <link href="../img/favicon.png" rel="icon">
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"
        crossorigin="anonymous"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EEK2QMEL41"></script>
    <style>
        .center {
            text-align: center;
            width: 100%;
        }
    </style>
    <script>
        $(document).ready(function () {
            $("#selectbox").change(function () {
                var stylefa = $("#selectbox").val();
                var new1 = document.getElementById("fapreview");
                new1.innerHTML = '';
                var icon = document.createElement("I");
                var x1 = document.createElement("BR");
                icon.setAttribute("class", stylefa + " float-center");
                icon.setAttribute("style", "font-size:50px");
                new1.appendChild(x1);
                new1.appendChild(icon);
            });
        });
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-EEK2QMEL41');
    </script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="admin.php">My Certificates</a><button
            class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button><!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group bg-dark text-white">
                Admin
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <form method="post" action="event.php" enctype="multipart/form-data">
                        <input type="submit" class="dropdown-item" value="Logout" name="logout" id="logout" />
                        </from>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add Participant Certificates
                        </a>
                        <a class="nav-link" href="coordinator.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add Coordinator Certificates
                        </a>
                        <a class="nav-link" href="core.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add Core Member Certificates
                        </a>
                        <a class="nav-link" href="member.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add Member Certificates
                        </a>
                        <a class="nav-link" href="event.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Add Event
                        </a>
                        <a class="nav-link" href="delete.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Delete
                        </a>
                        <a class="nav-link" href="log.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Log
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Add Event</h1>
                    <div class="card mb-4">

                        <div class="card-header"><i class="fas fa-table mr-1"></i>Upload Data</div>
                        <div class="card-body">

                            <form action="event.php" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Event Name</label>
                                    <input type="Name" name="Name" class="form-control" id="exampleFormControlInput1">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Date</label>
                                    <input type="Date" name="date" class="form-control" id="exampleFormControlInput1">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Short Details</label>
                                    <textarea class="form-control" name="s_de" id="exampleFormControlTextarea1"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Full Details</label>
                                    <textarea class="form-control" name="f_de" id="exampleFormControlTextarea1"
                                        rows="6"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="exampleFormControlSelect1">Select Fa Fa Icon For Event</label>
                                            <select name="faicon" class="form-control" id="selectbox">
                                                <option value="" disabled selected>Choose option</option>
                                                <option value='fa fa-address-book-o'>fa fa-address-book-o</option>
                                                <option value='fa fa-address-card'>fa fa-address-card</option>
                                                <option value='fa fa-address-card-o'>fa fa-address-card-o</option>
                                                <option value='fa fa-adjust'>fa fa-adjust</option>
                                                <option value='fa fa-american-sign-language-interpreting'>fa
                                                    fa-american-sign-language-interpreting</option>
                                                <option value='fa fa-anchor'>fa fa-anchor</option>
                                                <option value='fa fa-archive'>fa fa-archive</option>
                                                <option value='fa fa-area-chart'>fa fa-area-chart</option>
                                                <option value='fa fa-arrows'>fa fa-arrows</option>
                                                <option value='fa fa-arrows-h'>fa fa-arrows-h</option>
                                                <option value='fa fa-arrows-v'>fa fa-arrows-v</option>
                                                <option value='fa fa-asl-interpreting'>fa fa-asl-interpreting</option>
                                                <option value='fa fa-assistive-listening-systems'>fa
                                                    fa-assistive-listening-systems</option>
                                                <option value='fa fa-asterisk'>fa fa-asterisk</option>
                                                <option value='fa fa-at'>fa fa-at</option>
                                                <option value='fa fa-automobile'>fa fa-automobile</option>
                                                <option value='fa fa-audio-description'>fa fa-audio-description</option>
                                                <option value='fa fa-balance-scale'>fa fa-balance-scale</option>
                                                <option value='fa fa-ban'>fa fa-ban</option>
                                                <option value='fa fa-bank'>fa fa-bank</option>
                                                <option value='fa fa-bar-chart'>fa fa-bar-chart</option>
                                                <option value='fa fa-bar-chart-o'>fa fa-bar-chart-o</option>
                                                <option value='fa fa-barcode'>fa fa-barcode</option>
                                                <option value='fa fa-bars'>fa fa-bars</option>
                                                <option value='fa fa-bath'>fa fa-bath</option>
                                                <option value='fa fa-bathtub'>fa fa-bathtub</option>
                                                <option value='fa fa-battery-0'>fa fa-battery-0</option>
                                                <option value='fa fa-battery-1'>fa fa-battery-1</option>
                                                <option value='fa fa-battery-2'>fa fa-battery-2</option>
                                                <option value='fa fa-battery-3'>fa fa-battery-3</option>
                                                <option value='fa fa-battery-4'>fa fa-battery-4</option>
                                                <option value='fa fa-battery-empty'>fa fa-battery-empty</option>
                                                <option value='fa fa-battery-full'>fa fa-battery-full</option>
                                                <option value='fa fa-battery-half'>fa fa-battery-half</option>
                                                <option value='fa fa-battery-quarter'>fa fa-battery-quarter</option>
                                                <option value='fa fa-battery-three-quarters'>fa
                                                    fa-battery-three-quarters</option>
                                                <option value='fa fa-bed'>fa fa-bed</option>
                                                <option value='fa fa-beer'>fa fa-beer</option>
                                                <option value='fa fa-bell'>fa fa-bell</option>
                                                <option value='fa fa-bell-o'>fa fa-bell-o</option>
                                                <option value='fa fa-bell-slash'>fa fa-bell-slash</option>
                                                <option value='fa fa-bell-slash-o'>fa fa-bell-slash-o</option>
                                                <option value='fa fa-bicycle'>fa fa-bicycle</option>
                                                <option value='fa fa-binoculars'>fa fa-binoculars</option>
                                                <option value='fa fa-birthday-cake'>fa fa-birthday-cake</option>
                                                <option value='fa fa-blind'>fa fa-blind</option>
                                                <option value='fa fa-bolt'>fa fa-bolt</option>
                                                <option value='fa fa-bomb'>fa fa-bomb</option>
                                                <option value='fa fa-book'>fa fa-book</option>
                                                <option value='fa fa-bookmark'>fa fa-bookmark</option>
                                                <option value='fa fa-bookmark-o'>fa fa-bookmark-o</option>
                                                <option value='fa fa-braille'>fa fa-braille</option>
                                                <option value='fa fa-briefcase'>fa fa-briefcase</option>
                                                <option value='fa fa-bug'>fa fa-bug</option>
                                                <option value='fa fa-building'>fa fa-building</option>
                                                <option value='fa fa-building-o'>fa fa-building-o</option>
                                                <option value='fa fa-bullhorn'>fa fa-bullhorn</option>
                                                <option value='fa fa-bullseye'>fa fa-bullseye</option>
                                                <option value='fa fa-bus'>fa fa-bus</option>
                                                <option value='fa fa-cab'>fa fa-cab</option>
                                                <option value='fa fa-calculator'>fa fa-calculator</option>
                                                <option value='fa fa-calendar'>fa fa-calendar</option>
                                                <option value='fa fa-calendar-o'>fa fa-calendar-o</option>
                                                <option value='fa fa-calendar-check-o'>fa fa-calendar-check-o</option>
                                                <option value='fa fa-calendar-minus-o'>fa fa-calendar-minus-o</option>
                                                <option value='fa fa-calendar-plus-o'>fa fa-calendar-plus-o</option>
                                                <option value='fa fa-calendar-times-o'>fa fa-calendar-times-o</option>
                                                <option value='fa fa-camera'>fa fa-camera</option>
                                                <option value='fa fa-camera-retro'>fa fa-camera-retro</option>
                                                <option value='fa fa-car'>fa fa-car</option>
                                                <option value='fa fa-caret-square-o-down'>fa fa-caret-square-o-down
                                                </option>
                                                <option value='fa fa-caret-square-o-left'>fa fa-caret-square-o-left
                                                </option>
                                                <option value='fa fa-caret-square-o-right'>fa fa-caret-square-o-right
                                                </option>
                                                <option value='fa fa-caret-square-o-up'>fa fa-caret-square-o-up</option>
                                                <option value='fa fa-cart-arrow-down'>fa fa-cart-arrow-down</option>
                                                <option value='fa fa-cart-plus'>fa fa-cart-plus</option>
                                                <option value='fa fa-cc'>fa fa-cc</option>
                                                <option value='fa fa-certificate'>fa fa-certificate</option>
                                                <option value='fa fa-check'>fa fa-check</option>
                                                <option value='fa fa-check-circle'>fa fa-check-circle</option>
                                                <option value='fa fa-check-circle-o'>fa fa-check-circle-o</option>
                                                <option value='fa fa-check-square'>fa fa-check-square</option>
                                                <option value='fa fa-check-square-o'>fa fa-check-square-o</option>
                                                <option value='fa fa-child'>fa fa-child</option>
                                                <option value='fa fa-circle'>fa fa-circle</option>
                                                <option value='fa fa-circle-o'>fa fa-circle-o</option>
                                                <option value='fa fa-circle-o-notch'>fa fa-circle-o-notch</option>
                                                <option value='fa fa-circle-thin'>fa fa-circle-thin</option>
                                                <option value='fa fa-clock-o'>fa fa-clock-o</option>
                                                <option value='fa fa-clone'>fa fa-clone</option>
                                                <option value='fa fa-close'>fa fa-close</option>
                                                <option value='fa fa-cloud'>fa fa-cloud</option>
                                                <option value='fa fa-cloud-download'>fa fa-cloud-download</option>
                                                <option value='fa fa-cloud-upload'>fa fa-cloud-upload</option>
                                                <option value='fa fa-code'>fa fa-code</option>
                                                <option value='fa fa-code-fork'>fa fa-code-fork</option>
                                                <option value='fa fa-coffee'>fa fa-coffee</option>
                                                <option value='fa fa-cog'>fa fa-cog</option>
                                                <option value='fa fa-cogs'>fa fa-cogs</option>
                                                <option value='fa fa-comment'>fa fa-comment</option>
                                                <option value='fa fa-comment-o'>fa fa-comment-o</option>
                                                <option value='fa fa-comments'>fa fa-comments</option>
                                                <option value='fa fa-comments-o'>fa fa-comments-o</option>
                                                <option value='fa fa-commenting'>fa fa-commenting</option>
                                                <option value='fa fa-commenting-o'>fa fa-commenting-o</option>
                                                <option value='fa fa-compass'>fa fa-compass</option>
                                                <option value='fa fa-copyright'>fa fa-copyright</option>
                                                <option value='fa fa-credit-card'>fa fa-credit-card</option>
                                                <option value='fa fa-credit-card-alt'>fa fa-credit-card-alt</option>
                                                <option value='fa fa-creative-commons'>fa fa-creative-commons</option>
                                                <option value='fa fa-crop'>fa fa-crop</option>
                                                <option value='fa fa-crosshairs'>fa fa-crosshairs</option>
                                                <option value='fa fa-cube'>fa fa-cube</option>
                                                <option value='fa fa-cubes'>fa fa-cubes</option>
                                                <option value='fa fa-cutlery'>fa fa-cutlery</option>
                                                <option value='fa fa-dashboard'>fa fa-dashboard</option>
                                                <option value='fa fa-database'>fa fa-database</option>
                                                <option value='fa fa-deaf'>fa fa-deaf</option>
                                                <option value='fa fa-deafness'>fa fa-deafness</option>
                                                <option value='fa fa-desktop'>fa fa-desktop</option>
                                                <option value='fa fa-diamond'>fa fa-diamond</option>
                                                <option value='fa fa-dot-circle-o'>fa fa-dot-circle-o</option>
                                                <option value='fa fa-download'>fa fa-download</option>
                                                <option value='fa fa-drivers-license'>fa fa-drivers-license</option>
                                                <option value='fa fa-drivers-license-o'>fa fa-drivers-license-o</option>
                                                <option value='fa fa-edit'>fa fa-edit</option>
                                                <option value='fa fa-ellipsis-h'>fa fa-ellipsis-h</option>
                                                <option value='fa fa-ellipsis-v'>fa fa-ellipsis-v</option>
                                                <option value='fa fa-envelope'>fa fa-envelope</option>
                                                <option value='fa fa-envelope-o'>fa fa-envelope-o</option>
                                                <option value='fa fa-envelope-open'>fa fa-envelope-open</option>
                                                <option value='fa fa-envelope-open-o'>fa fa-envelope-open-o</option>
                                                <option value='fa fa-envelope-square'>fa fa-envelope-square</option>
                                                <option value='fa fa-eraser'>fa fa-eraser</option>
                                                <option value='fa fa-exchange'>fa fa-exchange</option>
                                                <option value='fa fa-exclamation'>fa fa-exclamation</option>
                                                <option value='fa fa-exclamation-circle'>fa fa-exclamation-circle
                                                </option>
                                                <option value='fa fa-exclamation-triangle'>fa fa-exclamation-triangle
                                                </option>
                                                <option value='fa fa-external-link'>fa fa-external-link</option>
                                                <option value='fa fa-external-link-square'>fa fa-external-link-square
                                                </option>
                                                <option value='fa fa-eye'>fa fa-eye</option>
                                                <option value='fa fa-eye-slash'>fa fa-eye-slash</option>
                                                <option value='fa fa-eyedropper'>fa fa-eyedropper</option>
                                                <option value='fa fa-fax'>fa fa-fax</option>
                                                <option value='fa fa-female'>fa fa-female</option>
                                                <option value='fa fa-fighter-jet'>fa fa-fighter-jet</option>
                                                <option value='fa fa-file-archive-o'>fa fa-file-archive-o</option>
                                                <option value='fa fa-file-audio-o'>fa fa-file-audio-o</option>
                                                <option value='fa fa-file-code-o'>fa fa-file-code-o</option>
                                                <option value='fa fa-file-excel-o'>fa fa-file-excel-o</option>
                                                <option value='fa fa-file-image-o'>fa fa-file-image-o</option>
                                                <option value='fa fa-file-movie-o'>fa fa-file-movie-o</option>
                                                <option value='fa fa-file-pdf-o'>fa fa-file-pdf-o</option>
                                                <option value='fa fa-file-photo-o'>fa fa-file-photo-o</option>
                                                <option value='fa fa-file-picture-o'>fa fa-file-picture-o</option>
                                                <option value='fa fa-file-powerpoint-o'>fa fa-file-powerpoint-o</option>
                                                <option value='fa fa-file-sound-o'>fa fa-file-sound-o</option>
                                                <option value='fa fa-file-video-o'>fa fa-file-video-o</option>
                                                <option value='fa fa-file-word-o'>fa fa-file-word-o</option>
                                                <option value='fa fa-file-zip-o'>fa fa-file-zip-o</option>
                                                <option value='fa fa-film'>fa fa-film</option>
                                                <option value='fa fa-filter'>fa fa-filter</option>
                                                <option value='fa fa-fire'>fa fa-fire</option>
                                                <option value='fa fa-fire-extinguisher'>fa fa-fire-extinguisher</option>
                                                <option value='fa fa-flag'>fa fa-flag</option>
                                                <option value='fa fa-flag-checkered'>fa fa-flag-checkered</option>
                                                <option value='fa fa-flag-o'>fa fa-flag-o</option>
                                                <option value='fa fa-flash'>fa fa-flash</option>
                                                <option value='fa fa-flask'>fa fa-flask</option>
                                                <option value='fa fa-folder'>fa fa-folder</option>
                                                <option value='fa fa-folder-o'>fa fa-folder-o</option>
                                                <option value='fa fa-folder-open'>fa fa-folder-open</option>
                                                <option value='fa fa-folder-open-o'>fa fa-folder-open-o</option>
                                                <option value='fa fa-frown-o'>fa fa-frown-o</option>
                                                <option value='fa fa-futbol-o'>fa fa-futbol-o</option>
                                                <option value='fa fa-gamepad'>fa fa-gamepad</option>
                                                <option value='fa fa-gavel'>fa fa-gavel</option>
                                                <option value='fa fa-gear'>fa fa-gear</option>
                                                <option value='fa fa-gears'>fa fa-gears</option>
                                                <option value='fa fa-genderless'>fa fa-genderless</option>
                                                <option value='fa fa-gift'>fa fa-gift</option>
                                                <option value='fa fa-glass'>fa fa-glass</option>
                                                <option value='fa fa-globe'>fa fa-globe</option>
                                                <option value='fa fa-graduation-cap'>fa fa-graduation-cap</option>
                                                <option value='fa fa-group'>fa fa-group</option>
                                                <option value='fa fa-hard-of-hearing'>fa fa-hard-of-hearing</option>
                                                <option value='fa fa-hdd-o'>fa fa-hdd-o</option>
                                                <option value='fa fa-handshake-o'>fa fa-handshake-o</option>
                                                <option value='fa fa-hashtag'>fa fa-hashtag</option>
                                                <option value='fa fa-headphones'>fa fa-headphones</option>
                                                <option value='fa fa-heart'>fa fa-heart</option>
                                                <option value='fa fa-heart-o'>fa fa-heart-o</option>
                                                <option value='fa fa-heartbeat'>fa fa-heartbeat</option>
                                                <option value='fa fa-history'>fa fa-history</option>
                                                <option value='fa fa-home'>fa fa-home</option>
                                                <option value='fa fa-hotel'>fa fa-hotel</option>
                                                <option value='fa fa-hourglass'>fa fa-hourglass</option>
                                                <option value='fa fa-hourglass-1'>fa fa-hourglass-1</option>
                                                <option value='fa fa-hourglass-2'>fa fa-hourglass-2</option>
                                                <option value='fa fa-hourglass-3'>fa fa-hourglass-3</option>
                                                <option value='fa fa-hourglass-end'>fa fa-hourglass-end</option>
                                                <option value='fa fa-hourglass-half'>fa fa-hourglass-half</option>
                                                <option value='fa fa-hourglass-o'>fa fa-hourglass-o</option>
                                                <option value='fa fa-hourglass-start'>fa fa-hourglass-start</option>
                                                <option value='fa fa-i-cursor'>fa fa-i-cursor</option>
                                                <option value='fa fa-id-badge'>fa fa-id-badge</option>
                                                <option value='fa fa-id-card'>fa fa-id-card</option>
                                                <option value='fa fa-id-card-o'>fa fa-id-card-o</option>
                                                <option value='fa fa-image'>fa fa-image</option>
                                                <option value='fa fa-inbox'>fa fa-inbox</option>
                                                <option value='fa fa-industry'>fa fa-industry</option>
                                                <option value='fa fa-info'>fa fa-info</option>
                                                <option value='fa fa-info-circle'>fa fa-info-circle</option>
                                                <option value='fa fa-institution'>fa fa-institution</option>
                                                <option value='fa fa-key'>fa fa-key</option>
                                                <option value='fa fa-keyboard-o'>fa fa-keyboard-o</option>
                                                <option value='fa fa-language'>fa fa-language</option>
                                                <option value='fa fa-laptop'>fa fa-laptop</option>
                                                <option value='fa fa-leaf'>fa fa-leaf</option>
                                                <option value='fa fa-legal'>fa fa-legal</option>
                                                <option value='fa fa-lemon-o'>fa fa-lemon-o</option>
                                                <option value='fa fa-level-down'>fa fa-level-down</option>
                                                <option value='fa fa-level-up'>fa fa-level-up</option>
                                                <option value='fa fa-life-bouy'>fa fa-life-bouy</option>
                                                <option value='fa fa-life-buoy'>fa fa-life-buoy</option>
                                                <option value='fa fa-life-ring'>fa fa-life-ring</option>
                                                <option value='fa fa-life-saver'>fa fa-life-saver</option>
                                                <option value='fa fa-lightbulb-o'>fa fa-lightbulb-o</option>
                                                <option value='fa fa-line-chart'>fa fa-line-chart</option>
                                                <option value='fa fa-location-arrow'>fa fa-location-arrow</option>
                                                <option value='fa fa-lock'>fa fa-lock</option>
                                                <option value='fa fa-low-vision'>fa fa-low-vision</option>
                                                <option value='fa fa-magic'>fa fa-magic</option>
                                                <option value='fa fa-magnet'>fa fa-magnet</option>
                                                <option value='fa fa-mail-forward'>fa fa-mail-forward</option>
                                                <option value='fa fa-mail-reply'>fa fa-mail-reply</option>
                                                <option value='fa fa-mail-reply-all'>fa fa-mail-reply-all</option>
                                                <option value='fa fa-male'>fa fa-male</option>
                                                <option value='fa fa-map'>fa fa-map</option>
                                                <option value='fa fa-map-o'>fa fa-map-o</option>
                                                <option value='fa fa-map-pin'>fa fa-map-pin</option>
                                                <option value='fa fa-map-signs'>fa fa-map-signs</option>
                                                <option value='fa fa-map-marker'>fa fa-map-marker</option>
                                                <option value='fa fa-meh-o'>fa fa-meh-o</option>
                                                <option value='fa fa-microchip'>fa fa-microchip</option>
                                                <option value='fa fa-microphone'>fa fa-microphone</option>
                                                <option value='fa fa-microphone-slash'>fa fa-microphone-slash</option>
                                                <option value='fa fa-minus'>fa fa-minus</option>
                                                <option value='fa fa-minus-circle'>fa fa-minus-circle</option>
                                                <option value='fa fa-minus-square'>fa fa-minus-square</option>
                                                <option value='fa fa-minus-square-o'>fa fa-minus-square-o</option>
                                                <option value='fa fa-mobile'>fa fa-mobile</option>
                                                <option value='fa fa-mobile-phone'>fa fa-mobile-phone</option>
                                                <option value='fa fa-money'>fa fa-money</option>
                                                <option value='fa fa-moon-o'>fa fa-moon-o</option>
                                                <option value='fa fa-mortar-board'>fa fa-mortar-board</option>
                                                <option value='fa fa-motorcycle'>fa fa-motorcycle</option>
                                                <option value='fa fa-mouse-pointer'>fa fa-mouse-pointer</option>
                                                <option value='fa fa-music'>fa fa-music</option>
                                                <option value='fa fa-navicon'>fa fa-navicon</option>
                                                <option value='fa fa-newspaper-o'>fa fa-newspaper-o</option>
                                                <option value='fa fa-object-group'>fa fa-object-group</option>
                                                <option value='fa fa-object-ungroup'>fa fa-object-ungroup</option>
                                                <option value='fa fa-paint-brush'>fa fa-paint-brush</option>
                                                <option value='fa fa-paper-plane'>fa fa-paper-plane</option>
                                                <option value='fa fa-paper-plane-o'>fa fa-paper-plane-o</option>
                                                <option value='fa fa-paw'>fa fa-paw</option>
                                                <option value='fa fa-pencil'>fa fa-pencil</option>
                                                <option value='fa fa-pencil-square'>fa fa-pencil-square</option>
                                                <option value='fa fa-pencil-square-o'>fa fa-pencil-square-o</option>
                                                <option value='fa fa-percent'>fa fa-percent</option>
                                                <option value='fa fa-phone'>fa fa-phone</option>
                                                <option value='fa fa-phone-square'>fa fa-phone-square</option>
                                                <option value='fa fa-photo'>fa fa-photo</option>
                                                <option value='fa fa-picture-o'>fa fa-picture-o</option>
                                                <option value='fa fa-pie-chart'>fa fa-pie-chart</option>
                                                <option value='fa fa-plane'>fa fa-plane</option>
                                                <option value='fa fa-plug'>fa fa-plug</option>
                                                <option value='fa fa-plus'>fa fa-plus</option>
                                                <option value='fa fa-plus-circle'>fa fa-plus-circle</option>
                                                <option value='fa fa-plus-square'>fa fa-plus-square</option>
                                                <option value='fa fa-plus-square-o'>fa fa-plus-square-o</option>
                                                <option value='fa fa-podcast'>fa fa-podcast</option>
                                                <option value='fa fa-power-off'>fa fa-power-off</option>
                                                <option value='fa fa-print'>fa fa-print</option>
                                                <option value='fa fa-puzzle-piece'>fa fa-puzzle-piece</option>
                                                <option value='fa fa-qrcode'>fa fa-qrcode</option>
                                                <option value='fa fa-question'>fa fa-question</option>
                                                <option value='fa fa-question-circle'>fa fa-question-circle</option>
                                                <option value='fa fa-question-circle-o'>fa fa-question-circle-o</option>
                                                <option value='fa fa-quote-left'>fa fa-quote-left</option>
                                                <option value='fa fa-quote-right'>fa fa-quote-right</option>
                                                <option value='fa fa-random'>fa fa-random</option>
                                                <option value='fa fa-recycle'>fa fa-recycle</option>
                                                <option value='fa fa-refresh'>fa fa-refresh</option>
                                                <option value='fa fa-registered'>fa fa-registered</option>
                                                <option value='fa fa-remove'>fa fa-remove</option>
                                                <option value='fa fa-reorder'>fa fa-reorder</option>
                                                <option value='fa fa-reply'>fa fa-reply</option>
                                                <option value='fa fa-reply-all'>fa fa-reply-all</option>
                                                <option value='fa fa-retweet'>fa fa-retweet</option>
                                                <option value='fa fa-road'>fa fa-road</option>
                                                <option value='fa fa-rocket'>fa fa-rocket</option>
                                                <option value='fa fa-rss'>fa fa-rss</option>
                                                <option value='fa fa-rss-square'>fa fa-rss-square</option>
                                                <option value='fa fa-s15'>fa fa-s15</option>
                                                <option value='fa fa-search'>fa fa-search</option>
                                                <option value='fa fa-search-minus'>fa fa-search-minus</option>
                                                <option value='fa fa-search-plus'>fa fa-search-plus</option>
                                                <option value='fa fa-send'>fa fa-send</option>
                                                <option value='fa fa-send-o'>fa fa-send-o</option>
                                                <option value='fa fa-server'>fa fa-server</option>
                                                <option value='fa fa-share'>fa fa-share</option>
                                                <option value='fa fa-share-alt'>fa fa-share-alt</option>
                                                <option value='fa fa-share-alt-square'>fa fa-share-alt-square</option>
                                                <option value='fa fa-share-square'>fa fa-share-square</option>
                                                <option value='fa fa-share-square-o'>fa fa-share-square-o</option>
                                                <option value='fa fa-shield'>fa fa-shield</option>
                                                <option value='fa fa-ship'>fa fa-ship</option>
                                                <option value='fa fa-shopping-bag'>fa fa-shopping-bag</option>
                                                <option value='fa fa-shopping-basket'>fa fa-shopping-basket</option>
                                                <option value='fa fa-shopping-cart'>fa fa-shopping-cart</option>
                                                <option value='fa fa-shower'>fa fa-shower</option>
                                                <option value='fa fa-sign-in'>fa fa-sign-in</option>
                                                <option value='fa fa-sign-out'>fa fa-sign-out</option>
                                                <option value='fa fa-sign-language'>fa fa-sign-language</option>
                                                <option value='fa fa-signal'>fa fa-signal</option>
                                                <option value='fa fa-signing'>fa fa-signing</option>
                                                <option value='fa fa-sitemap'>fa fa-sitemap</option>
                                                <option value='fa fa-sliders'>fa fa-sliders</option>
                                                <option value='fa fa-smile-o'>fa fa-smile-o</option>
                                                <option value='fa fa-snowflake-o'>fa fa-snowflake-o</option>
                                                <option value='fa fa-soccer-ball-o'>fa fa-soccer-ball-o</option>
                                                <option value='fa fa-sort'>fa fa-sort</option>
                                                <option value='fa fa-sort-alpha-asc'>fa fa-sort-alpha-asc</option>
                                                <option value='fa fa-sort-alpha-desc'>fa fa-sort-alpha-desc</option>
                                                <option value='fa fa-sort-amount-asc'>fa fa-sort-amount-asc</option>
                                                <option value='fa fa-sort-amount-desc'>fa fa-sort-amount-desc</option>
                                                <option value='fa fa-sort-asc'>fa fa-sort-asc</option>
                                                <option value='fa fa-sort-desc'>fa fa-sort-desc</option>
                                                <option value='fa fa-sort-down'>fa fa-sort-down</option>
                                                <option value='fa fa-sort-numeric-asc'>fa fa-sort-numeric-asc</option>
                                                <option value='fa fa-sort-numeric-desc'>fa fa-sort-numeric-desc</option>
                                                <option value='fa fa-sort-up'>fa fa-sort-up</option>
                                                <option value='fa fa-space-shuttle'>fa fa-space-shuttle</option>
                                                <option value='fa fa-spinner'>fa fa-spinner</option>
                                                <option value='fa fa-spoon'>fa fa-spoon</option>
                                                <option value='fa fa-square'>fa fa-square</option>
                                                <option value='fa fa-square-o'>fa fa-square-o</option>
                                                <option value='fa fa-star'>fa fa-star</option>
                                                <option value='fa fa-star-half'>fa fa-star-half</option>
                                                <option value='fa fa-star-half-empty'>fa fa-star-half-empty</option>
                                                <option value='fa fa-star-half-full'>fa fa-star-half-full</option>
                                                <option value='fa fa-star-half-o'>fa fa-star-half-o</option>
                                                <option value='fa fa-star-o'>fa fa-star-o</option>
                                                <option value='fa fa-sticky-note'>fa fa-sticky-note</option>
                                                <option value='fa fa-sticky-note-o'>fa fa-sticky-note-o</option>
                                                <option value='fa fa-street-view'>fa fa-street-view</option>
                                                <option value='fa fa-suitcase'>fa fa-suitcase</option>
                                                <option value='fa fa-sun-o'>fa fa-sun-o</option>
                                                <option value='fa fa-support'>fa fa-support</option>
                                                <option value='fa fa-tablet'>fa fa-tablet</option>
                                                <option value='fa fa-tachometer'>fa fa-tachometer</option>
                                                <option value='fa fa-tag'>fa fa-tag</option>
                                                <option value='fa fa-tags'>fa fa-tags</option>
                                                <option value='fa fa-tasks'>fa fa-tasks</option>
                                                <option value='fa fa-taxi'>fa fa-taxi</option>
                                                <option value='fa fa-television'>fa fa-television</option>
                                                <option value='fa fa-terminal'>fa fa-terminal</option>
                                                <option value='fa fa-thermometer'>fa fa-thermometer</option>
                                                <option value='fa fa-thermometer-0'>fa fa-thermometer-0</option>
                                                <option value='fa fa-thermometer-1'>fa fa-thermometer-1</option>
                                                <option value='fa fa-thermometer-2'>fa fa-thermometer-2</option>
                                                <option value='fa fa-thermometer-3'>fa fa-thermometer-3</option>
                                                <option value='fa fa-thermometer-4'>fa fa-thermometer-4</option>
                                                <option value='fa fa-thermometer-empty'>fa fa-thermometer-empty</option>
                                                <option value='fa fa-thermometer-full'>fa fa-thermometer-full</option>
                                                <option value='fa fa-thermometer-half'>fa fa-thermometer-half</option>
                                                <option value='fa fa-thermometer-quarter'>fa fa-thermometer-quarter
                                                </option>
                                                <option value='fa fa-thermometer-three-quarters'>fa
                                                    fa-thermometer-three-quarters</option>
                                                <option value='fa fa-thumb-tack'>fa fa-thumb-tack</option>
                                                <option value='fa fa-thumbs-down'>fa fa-thumbs-down</option>
                                                <option value='fa fa-thumbs-o-up'>fa fa-thumbs-o-up</option>
                                                <option value='fa fa-thumbs-up'>fa fa-thumbs-up</option>
                                                <option value='fa fa-ticket'>fa fa-ticket</option>
                                                <option value='fa fa-times'>fa fa-times</option>
                                                <option value='fa fa-times-circle'>fa fa-times-circle</option>
                                                <option value='fa fa-times-circle-o'>fa fa-times-circle-o</option>
                                                <option value='fa fa-times-rectangle'>fa fa-times-rectangle</option>
                                                <option value='fa fa-times-rectangle-o'>fa fa-times-rectangle-o</option>
                                                <option value='fa fa-tint'>fa fa-tint</option>
                                                <option value='fa fa-toggle-down'>fa fa-toggle-down</option>
                                                <option value='fa fa-toggle-left'>fa fa-toggle-left</option>
                                                <option value='fa fa-toggle-right'>fa fa-toggle-right</option>
                                                <option value='fa fa-toggle-up'>fa fa-toggle-up</option>
                                                <option value='fa fa-toggle-off'>fa fa-toggle-off</option>
                                                <option value='fa fa-toggle-on'>fa fa-toggle-on</option>
                                                <option value='fa fa-trademark'>fa fa-trademark</option>
                                                <option value='fa fa-trash'>fa fa-trash</option>
                                                <option value='fa fa-trash-o'>fa fa-trash-o</option>
                                                <option value='fa fa-tree'>fa fa-tree</option>
                                                <option value='fa fa-trophy'>fa fa-trophy</option>
                                                <option value='fa fa-truck'>fa fa-truck</option>
                                                <option value='fa fa-tty'>fa fa-tty</option>
                                                <option value='fa fa-tv'>fa fa-tv</option>
                                                <option value='fa fa-umbrella'>fa fa-umbrella</option>
                                                <option value='fa fa-universal-access'>fa fa-universal-access</option>
                                                <option value='fa fa-university'>fa fa-university</option>
                                                <option value='fa fa-unlock'>fa fa-unlock</option>
                                                <option value='fa fa-unlock-alt'>fa fa-unlock-alt</option>
                                                <option value='fa fa-unsorted'>fa fa-unsorted</option>
                                                <option value='fa fa-upload'>fa fa-upload</option>
                                                <option value='fa fa-user'>fa fa-user</option>
                                                <option value='fa fa-user-circle'>fa fa-user-circle</option>
                                                <option value='fa fa-user-circle-o'>fa fa-user-circle-o</option>
                                                <option value='fa fa-user-o'>fa fa-user-o</option>
                                                <option value='fa fa-user-plus'>fa fa-user-plus</option>
                                                <option value='fa fa-user-secret'>fa fa-user-secret</option>
                                                <option value='fa fa-user-times'>fa fa-user-times</option>
                                                <option value='fa fa-users'>fa fa-users</option>
                                                <option value='fa fa-vcard'>fa fa-vcard</option>
                                                <option value='fa fa-vcard-o'>fa fa-vcard-o</option>
                                                <option value='fa fa-video-camera'>fa fa-video-camera</option>
                                                <option value='fa fa-volume-control-phone'>fa fa-volume-control-phone
                                                </option>
                                                <option value='fa fa-volume-down'>fa fa-volume-down</option>
                                                <option value='fa fa-volume-off'>fa fa-volume-off</option>
                                                <option value='fa fa-volume-up'>fa fa-volume-up</option>
                                                <option value='fa fa-warning'>fa fa-warning</option>
                                                <option value='fa fa-wheelchair'>fa fa-wheelchair</option>
                                                <option value='fa fa-wheelchair-alt'>fa fa-wheelchair-alt</option>
                                                <option value='fa fa-window-close'>fa fa-window-close</option>
                                                <option value='fa fa-window-close-o'>fa fa-window-close-o</option>
                                                <option value='fa fa-window-maximize'>fa fa-window-maximize</option>
                                                <option value='fa fa-window-minimize'>fa fa-window-minimize</option>
                                                <option value='fa fa-window-restore'>fa fa-window-restore</option>
                                                <option value='fa fa-wifi'>fa fa-wifi</option>
                                                <option value='fa fa-wrench'>fa fa-wrench</option>
                                            </select>
                                        </div>

                                        <div class="col-6" style="display: table;" id="fapreview">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Coordinators Names(Coma Separated)</label>
                                    <textarea name="coordinators" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Folder Name</label>
                                    <input type="Name" name="folder" class="form-control" id="exampleFormControlInput1">
                                </div>
                                <div class="form-group row">
                                    <label for="image" class="col-sm-2 col-form-label">Upload Image Files(All File must have Same Extension):</label>
                                    <div class="col-sm-5">

                                        <input type="file" name="file[]" id="file" class="form-control" multiple="multiple">
                                    </div>
                                </div>
                                <div class="form-group row">

                                    <div class="col-sm-3 align-center">
                                        <input type="submit" name="uploadbt" class="btn btn-primary mb-2">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>


            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Eager Beavers Club</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>

    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
</body>

</html>