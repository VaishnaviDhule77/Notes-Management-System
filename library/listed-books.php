<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
    exit();
} else { 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Notes Management System | Downloaded Notes/Books</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
                    <h4 class="header-line">Manage Listed Notes & Books</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Available Notes & Books
                        </div>
                        <div class="panel-body">
                            <div class="row">
<?php 
$sql = "SELECT tblbooks.id as bookid, tblbooks.BookName, tblcategory.CategoryName, tblbooks.bookImage, tblbooks.bookpdf, 
               COUNT(tblissuedbookdetails.id) AS issuedBooks
        FROM tblbooks
        LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
        LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId
        GROUP BY tblbooks.id, tblbooks.BookName, tblcategory.CategoryName, tblbooks.bookImage, tblbooks.bookpdf";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;

if($query->rowCount() > 0) {
    foreach($results as $result) { 
?>  
                                <div class="col-md-4 col-sm-6" style="min-height: 220px; margin-bottom: 20px;">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td rowspan="2" style="width: 130px; text-align: center; vertical-align: middle;">
                                                <?php if(!empty($result->bookImage)) { ?>
                                                    <img src="admin/bookimg/<?php echo htmlentities($result->bookImage);?>" width="110" height="140" style="object-fit: cover; border-radius: 4px;" alt="Book Cover">
                                                <?php } else { ?>
                                                    <span class="label label-default">No Cover</span>
                                                <?php } ?>
                                            </td>
                                            <th style="width: 30%;">Book Name</th>
                                            <td><strong><?php echo htmlentities($result->BookName);?></strong></td>
                                        </tr>
                                        <tr>
                                            <th>Category</th>
                                            <td><?php echo htmlentities($result->CategoryName ? $result->CategoryName : 'Uncategorized');?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <?php if(!empty($result->bookpdf)) { ?>
                                                    <a href="admin/bookpdf/<?php echo htmlentities($result->bookpdf);?>" target="_blank" class="btn btn-success btn-block">
                                                        <i class="fa fa-download"></i> Download PDF
                                                    </a>
                                                <?php } else { ?>
                                                    <button class="btn btn-danger btn-block" disabled>No File Available</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
<?php 
        $cnt++;
    }
} else { 
?>
                                <div class="col-md-12">
                                    <div class="alert alert-info text-center">
                                        No notes or books are currently available in the database.
                                    </div>
                                </div>
<?php } ?> 
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->

    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>