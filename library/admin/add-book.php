<?php
// This stops the Warning from printing and breaking the session
ini_set('display_errors', 0); 
ob_start();
session_start();

// 2. Suppress warnings in production to prevent UI breakage
// (If you still have issues, change this to E_ALL to debug)
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING); 

include('includes/config.php');

// 3. Robust Session Check
if(!isset($_SESSION['alogin']) || strlen((string)$_SESSION['alogin']) == 0) {   
    header('location:index.php');
    exit();
} else { 
    if(isset($_POST['add'])) {
        // 4. Verification: If the upload was too large, $_POST will be empty.
        if(empty($_POST['bookname']) && empty($_FILES)) {
            echo "<script>alert('Error: The file is too large for the server to process.');</script>";
        } else {
            $bookname = $_POST['bookname'];
            $category = $_POST['category'];
            
            // Check if files were actually uploaded
            if(!empty($_FILES["bookpic"]["name"]) && !empty($_FILES["bookpdf"]["name"])) {
                
                $bookimg = $_FILES["bookpic"]["name"];
                $bookpdf = $_FILES["bookpdf"]["name"];

                // 5. Use pathinfo for safer extension detection
                $extension = strtolower(pathinfo($bookimg, PATHINFO_EXTENSION));
                $extension2 = strtolower(pathinfo($bookpdf, PATHINFO_EXTENSION));

                $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                $allowed_extensions2 = array("pdf");

                // Generate unique names
                $imgnewname = md5($bookimg . time()) . "." . $extension;
                $pdfnewname = md5($bookpdf . time()) . "." . $extension2;

                if(!in_array($extension, $allowed_extensions)) {
                    echo "<script>alert('Invalid image format. Only jpg/jpeg/png/gif allowed');</script>";
                } else if(!in_array($extension2, $allowed_extensions2)) {
                    echo "<script>alert('Invalid PDF format. Only PDF allowed');</script>";
                } else {
                    // 6. Attempt to move files
                    if(move_uploaded_file($_FILES["bookpic"]["tmp_name"], "bookimg/" . $imgnewname)) {
                        if(move_uploaded_file($_FILES["bookpdf"]["tmp_name"], "bookpdf/" . $pdfnewname)) {
                            
                            $sql = "INSERT INTO tblbooks(BookName, CatId, bookImage, bookpdf) VALUES(:bookname, :category, :imgnewname, :pdfnewname)";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
                            $query->bindParam(':category', $category, PDO::PARAM_STR);
                            $query->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
                            $query->bindParam(':pdfnewname', $pdfnewname, PDO::PARAM_STR);
                            $query->execute();
                            
                            $lastInsertId = $dbh->lastInsertId();
                            if($lastInsertId) {
                                echo "<script>alert('Book Listed successfully');</script>";
                                echo "<script>window.location.href='manage-books.php'</script>";
                            } else {
                                echo "<script>alert('Something went wrong with the database. Please try again');</script>";
                            }
                        } else {
                            echo "<script>alert('Failed to upload PDF. Check folder permissions.');</script>";
                        }
                    } else {
                        echo "<script>alert('Failed to upload Image. Check folder permissions.');</script>";
                    }
                }
            } else {
                echo "<script>alert('Please select both an image and a PDF file.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online notes Management System | Add Book</title>
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
                    <h4 class="header-line">Add Book</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">Book Info</div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data">
                                <div class="col-md-6">   
                                    <div class="form-group">
                                        <label>Book Name<span style="color:red;">*</span></label>
                                        <input class="form-control" type="text" name="bookname" autocomplete="off" required />
                                    </div>
                                </div>
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label>Category<span style="color:red;">*</span></label>
                                        <select class="form-control" name="category" required>
                                            <option value="">Select Category</option>
                                            <?php 
                                            $status = 1;
                                            $sql = "SELECT * from tblcategory where Status=:status";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':status', $status, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) { ?>  
                                                    <option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->CategoryName);?></option>
                                            <?php }} ?> 
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label>Book Picture<span style="color:red;">*</span></label>
                                        <input class="form-control" type="file" name="bookpic" required />
                                    </div>
                                </div>
                                <div class="col-md-6">  
                                    <div class="form-group">
                                        <label>Book PDF<span style="color:red;">*</span></label>
                                        <input class="form-control" type="file" name="bookpdf" required />
                                    </div>
                                </div>
                                <div class="col-md-12"> 
                                    <button type="submit" name="add" class="btn btn-info">Submit</button>
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
// End buffering and flush
ob_end_flush(); 
?>