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
    <title>Online Notes Management System | Listed Notes</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <?php include('includes/header.php');?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Manage Listed Books & Notes</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Available Notes & Books
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book Cover</th>
                                            <th>Book Name</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$sql = "SELECT tblbooks.id as bookid, tblbooks.BookName, tblcategory.CategoryName, tblbooks.bookImage, tblbooks.bookpdf 
        FROM tblbooks 
        LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId";

$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;

if($query->rowCount() > 0) {
    foreach($results as $result) { 
        $imgName = trim($result->bookImage);
        $pdfName = trim($result->bookpdf);

        // Path relative to where listed-books.php is located
        $imgUrl = !empty($imgName) ? "admin/bookimg/" . $imgName : "";
        $pdfUrl = !empty($pdfName) ? "admin/bookpdf/" . $pdfName : "";
?>  
                                        <tr class="odd gradeX">
                                            <td class="center" style="vertical-align: middle;"><?php echo htmlentities($cnt);?></td>
                                            <td class="center" style="width: 110px; text-align: center; vertical-align: middle;">
                                                <?php if(!empty($imgUrl)) { ?>
                                                    <img src="<?php echo htmlentities($imgUrl);?>" width="70" height="95" style="object-fit: cover; border: 1px solid #ccc; padding: 2px; border-radius: 3px;" alt="Cover">
                                                <?php } else { ?>
                                                    <span class="label label-default">No Image</span>
                                                <?php } ?>
                                            </td>
                                            <td style="vertical-align: middle;"><strong><?php echo htmlentities($result->BookName);?></strong></td>
                                            <td style="vertical-align: middle;"><?php echo htmlentities($result->CategoryName ? $result->CategoryName : 'Uncategorized');?></td>
                                            <td class="center" style="vertical-align: middle;">
                                                <?php if(!empty($pdfUrl)) { ?>
                                                    <a href="<?php echo htmlentities($pdfUrl);?>" target="_blank" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-download"></i> Download / View PDF
                                                    </a>
                                                <?php } else { ?>
                                                    <span class="label label-danger">No File</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
<?php 
        $cnt++;
    }
} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>