<?php
error_reporting(0);
ini_set('display_errors', 0);
try {
    include("../db.php");
    require_once('../Classes/PHPExcel/IOFactory.php');
 
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
        if (isset($_FILES['file'])) {
            $date=$_POST['date'];
            if ($date!="") {
                $orgDate=$_POST['date'];
                $newDate = date("dmY", strtotime($orgDate));
                $path="../certificate/".$newDate;
                if (!is_dir($path)) {
                    if (mkdir($path, 0755, true)) {
                       
                        echo '<script>alert("Directory Made")</script>';
                        
                    }
                else {  
                    echo '<script>alert("Error in Making Directory")</script>';  
                }
                }
                $countfiles = count($_FILES['file']['name']);
                // Looping all files
                for ($i=0;$i<$countfiles;$i++) {
                    $filename = $_FILES['file']['name'][$i];
       
                    // Upload file
                    move_uploaded_file($_FILES['file']['tmp_name'][$i], "../certificate/".$newDate."/".$filename);
                }
                header("Refresh:0");
            } else {
                echo '<script>alert("first upload data")</script>';
            }
        }
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href = "../login.php"</script>';
    }
    if (isset($_POST['bt'])) {
        $file="../sample.xlsx";
        ;
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
    if (isset($_POST['SubmitButton'])) {
        if (isset($_FILES['filepath'])) {
            $ename=$_POST['ename'];
            $date=$_POST['edate'];
            $excelobject =PHPExcel_IOFactory::load($_FILES["filepath"]["tmp_name"]);
            $getsheet=$excelobject->getActiveSheet()->toArray(null);
            echo "<pre>";
            $new = json_encode($getsheet);
            $c=0;
            for ($x=1;$x<sizeof($getsheet);$x++) {
                $email=strtolower($getsheet[$x][1]."@charusat.edu.in");
                $chash=$getsheet[$x][3];
                $rank=$getsheet[$x][2];
                $sql1="INSERT into `cert` (EName,Date,Rank,Chash,email) values('".$ename."','".$date."','".$rank."','".$chash."','".$email."')";
                if (mysqli_query($conn, $sql1)) {
                    $c+=1;
                } else {
                    echo "<script>alert('error')</script>";
                }
               
            }
            header("Refresh:0");
        }
    }
    if (time() - $_SESSION['timestamp'] > 300) { //subtract new timestamp from the old one
        session_destroy();
        echo '<script>window.location.href = "../login.php"</script>'; //redirect to index.php
        exit;
    } else {
        #$_SESSION['timestamp'] = time(); //set new timestamp
    }
    
}
catch(Exception $e){
    #session_destroy();
    #echo '<script>window.location.href = "../404.html"</script>';
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
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet"
        crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"
        crossorigin="anonymous"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EEK2QMEL41"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
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
                <form method="post" action="admin.php" enctype="multipart/form-data">
                    <input type="submit" class="dropdown-item" value="Logout" name="logout" id="logout"/>
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
                    <h1 class="mt-4">Add Certificates</h1>
                    <div class="card mb-4">
                   
                        <div class="card-header"><i class="fas fa-table mr-1"></i>Upload Data</div>
                        <div class="card-body">
                        <form action="./admin.php" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                         <label for="News"  class="col-sm-2 col-form-label">Here sample file of Excel</label>
                         <div class="col-sm-2">
                             <input type="submit" class="form-control btn btn-info" id="bt" name="bt" value="Download">
                            </div>
                        </div>
                    </form>
                    <form action="./admin.php" method="post" enctype="multipart/form-data">
                    
                    <div class="form-group row">
                        <label for="ename" class="col-sm-2 col-form-label">Event Name:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="ename" name="ename" >
                        </div>
                     </div>
                     <div class="form-group row">
                         <label for="date"  class="col-sm-2 col-form-label">Date of Event:</label>
                         <div class="col-sm-5">
                             <input type="date" class="form-control" id="edate" name="edate">
                            </div>
                        </div>
                        <div class="form-group row">
                         <label for="excel"  class="col-sm-2 col-form-label">Upload Excel File:</label>
                         <div class="col-sm-5">
                             <input  type="file"  name="filepath" id="filepath" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row">

                         <div class="col-sm-3 align-center">
                             <input  type="submit" name="SubmitButton"  class="btn btn-primary mb-2" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-table mr-1"></i>Upload Ceritficate</div>
                        <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                        <label for="pdf"  class="col-sm-2 col-form-label">Date of Event:</label>
                        <div class="col-sm-5">
                        
                             <input type="date" class="form-control" id="date" name="date">
                            </div>
                        </div>
                        <div class="form-group row">
                         <label for="pdf"  class="col-sm-2 col-form-label">Upload PDF File:</label>
                         <div class="col-sm-5">
                      
                      <input  type="file"  name="file[]" id="file" class="form-control" multiple="multiple">  
                     </div>
                        </div>
                        <div class="form-group row">

                         <div class="col-sm-3 align-center">
                             <input  type="submit" name="uploadbt"  class="btn btn-primary mb-2" >
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