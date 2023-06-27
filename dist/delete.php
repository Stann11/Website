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
    $flag=false;
    $table_name="";
    if(isset($_POST['deletebt'])){
        $row=$_POST['deletebt'];
        $table=$_POST['hide'];
        $query2="delete from `{$table}` where id='$row'";
        $result2 = mysqli_query($conn, $query2);
        $flag=true;
        $table_name=$table;
    }
    if (isset($_POST['logout'])) {
        session_destroy();
        echo '<script>window.location.href = "../login.php"</script>';
    }
    
    if (isset($_POST['SubmitButton'])) {
            global $table_name,$flag;
            $table_name=$_POST['taskOption'];
            
            if($table_name!="0"){
                $flag=true;
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
    <link href="./img/favicon.png" rel="icon">
    <link href="css/styles.css" rel="stylesheet" />
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
                <form method="post" action="delete.php" enctype="multipart/form-data">
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
                    <h1 class="mt-4">Delete</h1>
                    <div class="card mb-4">
                   
                        <div class="card-header"><i class="fas fa-table mr-1"></i>Delete Data</div>
                        <div class="card-body">
                        
                    <form action="./delete.php" method="post" enctype="multipart/form-data">
                    
                   
                     
                        <div class="form-group row">
                         <label for="excel"  class="col-sm-2 col-form-label">Select Table:</label>
                         </br>
                         <div class="col-sm-5">

                         <select class="form-control" name="taskOption">
                             <option value="0" disabled selected>Select</option>
                             <option value="event">Event Information Table</option>
                             <option value="cert">Participant Certificate Table</option>
                             <option value="core_cert">Core Member Certificate Table</option>
                             <option value="cord_cert">Coordinator Certificate Table</option>
                             <option value="mem_cert">Member Certificate Table</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group row">

                         <div class="col-sm-3 align-center">
                             <input  type="submit" name="SubmitButton" value="Get Rows"  class="btn btn-primary mb-2" >
                            </div>
                        </div>
                    </form>
                    <form action="./delete.php" method="post" enctype="multipart/form-data">
                    <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <?php
                                        
                                      
                                    if ($flag) {
                                       
                                        $query1="SHOW columns FROM `{$table_name}` ";
                                       
                                        $result1 = mysqli_query($conn, $query1);
                                        $column=mysqli_num_rows($result1);
                                       
                                        if (mysqli_num_rows($result1) > 0) {
                                            while ($row1 = mysqli_fetch_array($result1)) {
                                                echo "<th>";
                                                echo $row1[0];
                                                echo  "</th>";
                                            }
                                            echo "<th>";
                                            echo "Delete Button";
                                            echo "</th>";
                                        }
                                         ?>
                                       
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                   
<?php
                                        $query="select * from `{$table_name}`";
                                        $result = mysqli_query($conn, $query);
                                        
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>";
                                                
                                                for ($i=0;$i<$column;$i++) {
                                                    echo "<td>";
                                                    
                                                    echo $row[$i];
                                                    echo "</td>";
                                                }
                                                echo "<td>";
                                                $val1=$row[0];
                                                echo "<button type='submit' name='deletebt' class='btn btn-info' value='$val1'";
                                                echo ">";
                                                echo "Delete";
                                                echo "</button>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                        }
                                    }
?>
                                       
                                        
 </tbody>
                                </table>
    </div>
    <input type="hidden" name="hide" value="<?php echo $table_name ?>">
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
    <script src="./js/scripts.js"></script>
  
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
</body>

</html>