<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0)
{ 
    header('location:index.php');
    exit(); // Stop script execution if not logged in
}
else {
    // FIX: Retrieve StudentID from active session so issued book queries execute correctly
    $sid = $_SESSION['stdid']; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Notes Management System | User Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User DASHBOARD</h4>
                </div>
            </div>
             
            <div class="row">
                <a href="listed-books.php">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <?php 
                            $sql = "SELECT id FROM tblbooks";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $listdbooks = $query->rowCount();
                            ?>
                            <h3><?php echo htmlentities($listdbooks);?></h3>
                            Books/Notes Listed
                        </div>
                    </div>
                </a>

                <?php 
                $ret = $dbh->prepare("SELECT id FROM tblissuedbookdetails WHERE StudentID=:sid");
                $ret->bindParam(':sid', $sid, PDO::PARAM_STR);
                $ret->execute();
                $results22 = $ret->fetchAll(PDO::FETCH_OBJ);
                $totalissuedbook = $ret->rowCount();
                ?>
            </div>    
        </div>
    </div>
    
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    
    <!-- JAVASCRIPT FILES -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>