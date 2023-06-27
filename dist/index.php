<?php
error_reporting(0);
ini_set('display_errors', 0);
try{
include("../db.php");
session_start();
if(isset($_POST['logout'])){
    session_destroy();
    echo '<script>window.location.href = "../login.php"</script>';
}


if(isset($_SESSION['access_token'])  &&  isset($_SESSION['user_email_address'])){
  $email=$_SESSION['user_email_address'];
  $query="select type from login_info where email='$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            if($row['type']=="admin"){
                echo '<script>window.location.href = "../404.html"</script>';
            }
        }
    }
    else{
        session_destroy();
        echo '<script>window.location.href = "../404.html"</script>';
    }
}
else{
    session_destroy();
    echo '<script>window.location.href = "../404.html"</script>';
}

if (time() - $_SESSION['timestamp'] > 300) { //subtract new timestamp from the old one
    session_destroy();
    echo '<script>window.location.href = "../login.php"</script>'; //redirect to index.php
    exit;
} else {
   # $_SESSION['timestamp'] = time(); //set new timestamp
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
    <link href="../css/styles.css" rel="stylesheet" />
    <link href="../img/favicon.png" rel="icon">
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
        <a class="navbar-brand" href="index.php">My Certificates</a><button
            class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button><!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group bg-dark text-white">
                <?php echo $email ?>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <form method="post" action="index.php">
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
                    <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <?php
                        $year=date("Y");
                        $query0="select Chash from core_cert where Email='$email' and Year='$year'";
                        $result0 = mysqli_query($conn, $query0);
                        $query5="select Chash from mem_cert where Email='$email' and Year='$year'";
                        $result5 = mysqli_query($conn, $query5);
                        if (mysqli_num_rows($result0) > 0 || mysqli_num_rows($result5) > 0) {
                            ?>          
                        <a class="nav-link" href="profile.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Profile
                        </a>
                        <?php
                        }
                    ?>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Certificates</h1>


                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-table mr-1"></i>Events</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Rank/Position</th>
                                            <th>Certificate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$query="select EName,Date,Rank,Chash from cert where email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
   ?>
                                    
                                        <tr>
                                            <td><?php echo $row['EName'];   ?></td>
                                            <td><?php echo $row['Date'];   ?></td>
                                            <td><?php echo $row['Rank'];   ?></td>
 <?php
$orgDate=$row['Date'];
$newDate = date("dmY", strtotime($orgDate));

?>
                                            <td><a class="btn btn-info" href="<?php echo '../certificate/'.$newDate.'/'.$row['Chash'];   ?>"><?php echo $row['EName'];   ?> Certificate</a></td>
                                        </tr>
                                        <?php


}
}
?>
                                        <?php
$query1="select EName,Date,Chash from cord_cert where email='$email'";
$result1 = mysqli_query($conn, $query1);
if (mysqli_num_rows($result1) > 0) {
    while($row1 = mysqli_fetch_assoc($result1)) {
   ?>
                                    
                                        <tr>
                                            <td><?php echo $row1['EName'];   ?></td>
                                            <td><?php echo $row1['Date'];   ?></td>
                                            <td>Coordinator</td>
 <?php
$orgDate1=$row1['Date'];
$newDate1 = date("dmY", strtotime($orgDate1));

?>
                                            <td><a class="btn btn-info" href="<?php echo '../coordinator/'.$newDate1.'/'.$row1['Chash'];   ?>"><?php echo $row1['EName'];   ?>Coordinator Certificate</a></td>
                                        </tr>

                                   

                                    <?php


}
}
?>
                                        
 </tbody>
                                </table>
                            </div>
                        </div>
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
    <script src="../assets/demo/datatables-demo.js"></script>
</body>

</html>
<?php

}
catch(Exception $e){
    session_destroy();
     echo '<script>window.location.href = "../404.html"</script>';
}?>
