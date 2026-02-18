<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['add']))
{
$bookname=$_POST['bookname'];
$category=$_POST['category'];
$price=$_POST['price'];
$bookimg=$_FILES["bookpic"]["name"];
$bookpdf=$_FILES["bookpdf"]["name"];

// get the image extension
$extension = substr($bookimg,strlen($bookimg)-4,strlen($bookimg));
$extension2 =substr($bookpdf,strlen($bookpdf)-4,strlen($bookpdf));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
$allowed_extensions2 = array(".pdf");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
//rename the image file
$imgnewname=md5($bookimg.time()).$extension;
$pdfnewname=md5($bookpdf.time()).$extension2;
// Code for move image into directory

if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{
if(!in_array($extension2,$allowed_extensions2)){
    echo "<script>alert('Invalid format. Only pdf format allowed');</script>";
}else{
  if(move_uploaded_file($_FILES["bookpic"]["tmp_name"],"bookimg/".$imgnewname)){
    if(move_uploaded_file($_FILES["bookpdf"]["tmp_name"],"bookpdf/".$pdfnewname)){
$sql="INSERT INTO  tblbooks(BookName,CatId,bookImage,bookpdf) VALUES(:bookname,:category,:imgnewname,:pdfnewname)";
$query = $dbh->prepare($sql);
$query->bindParam(':bookname',$bookname,PDO::PARAM_STR);
$query->bindParam(':category',$category,PDO::PARAM_STR);
$query->bindParam(':imgnewname',$imgnewname,PDO::PARAM_STR);
$query->bindParam(':pdfnewname',$pdfnewname,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo "<script>alert('Book Listed successfully');</script>";
echo "<script>window.location.href='manage-books.php'</script>";
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";    
echo "<script>window.location.href='manage-books.php'</script>";
}
}}}}}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student notes Management System | Add Book/Notes</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<script type="text/javascript">
    function checkisbnAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'isbn='+$("#isbn").val(),
type: "POST",
success:function(data){
$("#isbn-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}

</script>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add Book</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="panel panel-info">
<div class="panel-heading">
Book Info
</div>
<div class="panel-body">
<form role="form" method="post" enctype="multipart/form-data">

<div class="col-md-6">   
<div class="form-group">
<label>Book Name<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="bookname" autocomplete="off"  required />
</div>
</div>

<div class="col-md-6">  
<div class="form-group">
<label> Category<span style="color:red;">*</span></label>
<select class="form-control" name="category" required="required">
<option value=""> Select Category</option>
<?php 
$status=1;
$sql = "SELECT * from  tblcategory where Status=:status";
$query = $dbh -> prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->CategoryName);?></option>
 <?php }} ?> 
</select>
</div></div>


<?php 

$sql = "SELECT * from  tblbooks ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->AuthorName);?></option>
 <?php }} ?> 
</select>
</div></div >



<div class="col-md-6">  
 <div class="form-group">
 <label>Book Picture<span style="color:red;">*</span></label>
 <input class="form-control" type="file" name="bookpic" autocomplete="off"   required="required" />
 </div>
    </div>
    <div class="col-md-6">  
 <div class="form-group">
 <label>Book PDF<span style="color:red;">*</span></label>
 <input class="form-control" type="file" name="bookpdf" autocomplete="off"   required="required" />
 </div>
    </div>


<div class="col-md-12"> 
<button type="submit" name="add" id="add" class="btn btn-info">Submit </button>
</div>
 </div>
</div>
                            </div>

        </div>
   
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
