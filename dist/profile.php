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
  $year=date("Y");
  $query0="select Chash from core_cert where Email='$email' and Year='$year'";
  $result0 = mysqli_query($conn, $query0);
  $query5="select Chash from mem_cert where Email='$email' and Year='$year'";
  $result5 = mysqli_query($conn, $query5);
  if (mysqli_num_rows($result0) < 0 && mysqli_num_rows($result5) < 0) {
    echo '<script>window.location.href = "../404.html"</script>';
  }
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
    #$_SESSION['timestamp'] = time(); //set new timestamp
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
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.html">My Certificates</a><button
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
                    <form method="post" action="profile.php">
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
                <div class="col-md-9">
                    <br>
                    <br>

		    <div class="card">
		        <div class="card-body">
		            <div class="row">
                        
		                <div class="col-md-12">
		                    <h4>Your Profile</h4>
		                    <hr>
		                </div>
		            </div>
		            <div class="row">
		                <div class="col-md-12">
		                    <form>
                                <?php
                            $sql="select given_name,family_name from login_info where email='$email'";
                            $first_name="";
                            $last_name="";
                            $result = mysqli_query($conn, $sql);
                            if(mysqli_num_rows($result) > 0){
                                while ($row1 = mysqli_fetch_assoc($result)) {
                                    $first_name=$row1['given_name'];
                                    $last_name=$row1['family_name'];
                                }
                            }
                                ?>
                              <div class="form-group row">
                                <label for="name" class="col-4 col-form-label">First Name</label> 
                                <div class="col-8">
                                  <input id="name" name="name" value="<?php echo $first_name;  ?>" class="form-control here" type="text" disabled>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="lastname" class="col-4 col-form-label">Last Name</label> 
                                <div class="col-8">
                                  <input id="lastname" name="lastname" value="<?php echo $last_name ?>"  class="form-control here" type="text" disabled>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="email" class="col-4 col-form-label">Email</label> 
                                <div class="col-8">
                                  <input id="email" name="email" value="<?php echo $email ?>" class="form-control here" required="required" type="text" disabled>
                                </div>
                              </div>
                              <?php
                                     if (mysqli_num_rows($result0) > 0){
                                        while ($row2 = mysqli_fetch_assoc($result0)) {
                                         ?>
                              <div class="form-group row">
                                <label for="website" class="col-4 col-form-label">Position</label> 
                                <div class="col-8">
                                   
                                  <input id="website" name="website" value="Core Member"  class="form-control here" type="text" disabled>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="website" class="col-4 col-form-label">Certificate</label> 
                                <div class="col-8">
                                    
                                 <a class="btn btn-info" href="<?php echo '../core/'.$year.'/'.$row2['Chash']; ?>">Core Member Certificate</a>
                                </div>
                                
                              </div>
                              <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                     if (mysqli_num_rows($result5) > 0){
                                        while ($row3 = mysqli_fetch_assoc($result5)) {
                                         ?>
                              <div class="form-group row">
                                <label for="website" class="col-4 col-form-label">Position</label> 
                                <div class="col-8">
                                   
                                  <input id="website" name="website" value="Member"  class="form-control here" type="text" disabled>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label for="website" class="col-4 col-form-label">Certificate</label> 
                                <div class="col-8">
                                    
                                 <a class="btn btn-info" href="<?php echo '../member/'.$year.'/'.$row3['Chash']; ?>">Member Certificate</a>
                                </div>
                                
                              </div>
                              <?php
                                        }
                                    }
                                    ?>
                              
                            </form>
		                </div>
		            </div>
		            
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
}
?>