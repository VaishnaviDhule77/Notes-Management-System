<?php
// Start output buffering and session
ob_start();
session_start();

// Disable warnings for a cleaner UI; use E_ALL for debugging local issues
error_reporting(E_ALL & ~E_NOTICE); 

include('includes/config.php');

// Robust session check
if(!isset($_SESSION['alogin']) || strlen((string)$_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
} else { 

    if(isset($_POST['update'])) {
        $bookname = $_POST['bookname'];
        $category = $_POST['category'];
        $bookid = intval($_GET['bookid']);
        
        $sql = "UPDATE tblbooks SET BookName=:bookname, CatId=:category WHERE id=:bookid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->execute();
        
        echo "<script>alert('Book info updated successfully');</script>";
        echo "<script>window.location.href='manage-books.php'</script>";
        exit();
    }
} // Fixed: Properly closed the else block before the HTML starts
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online notes Management System | Edit Book</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <?php include('includes/header.php');?>
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Edit Book/Notes</h4>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">Book/Notes Info</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <?php 
                                $bookid = intval($_GET['bookid']);
                                $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblcategory.id as cid, tblbooks.id as bookid, tblbooks.bookImage, tblbooks.bookpdf 
                                        FROM tblbooks 
                                        JOIN tblcategory ON tblcategory.id=tblbooks.CatId 
                                        WHERE tblbooks.id=:bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { ?>  

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Image</label><br />
                                                <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" width="100" style="border:1px solid #ddd; padding:5px;">
                                                <br />
                                                <a href="change-bookimg.php?bookid=<?php echo htmlentities($result->bookid);?>">Change Book Image</a>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book PDF</label><br />
                                                <p class="help-block">Current PDF: <?php echo htmlentities($result->bookpdf); ?></p>
                                                <a href="change-bookpdf.php?bookid=<?php echo htmlentities($result->bookid);?>" class="btn btn-xs btn-primary">Change Book PDF</a>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Book Name<span style="color:red;">*</span></label>
                                                <input class="form-control" type="text" name="bookname" value="<?php echo htmlentities($result->BookName);?>" required />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category<span style="color:red;">*</span></label>
                                                <select class="form-control" name="category" required="required">
                                                    <option value="<?php echo htmlentities($result->cid);?>"><?php echo htmlentities($catname=$result->CategoryName);?></option>
                                                    <?php 
                                                    $status = 1;
                                                    $sql1 = "SELECT * FROM tblcategory WHERE Status=:status";
                                                    $query1 = $dbh->prepare($sql1);
                                                    $query1->bindParam(':status', $status, PDO::PARAM_STR);
                                                    $query1->execute();
                                                    $resultss = $query1->fetchAll(PDO::FETCH_OBJ);
                                                    if($query1->rowCount() > 0) {
                                                        foreach($resultss as $row) {           
                                                            if($catname == $row->CategoryName) continue;
                                                            ?>  
                                                            <option value="<?php echo htmlentities($row->id);?>"><?php echo htmlentities($row->CategoryName);?></option>
                                                    <?php } } ?> 
                                                </select>
                                            </div>
                                        </div>
                                <?php } } ?>

                                <div class="col-md-12">
                                    <hr />
                                    <button type="submit" name="update" class="btn btn-info">Update Info</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php 
ob_end_flush(); 
?>