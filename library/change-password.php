<?php
session_start();
include('includes/config.php');

// Enable error reporting to keep track of any underlying issues on Render
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is actually logged in
if(strlen($_SESSION['login']) == 0) {   
    header('location:index.php');
    exit();
} else { 
    // Initialize notification messages
    $error = "";
    $msg = "";

    if(isset($_POST['change'])) {
        $email = $_SESSION['login'];
        $currentPasswordInput = trim($_POST['password']);
        
        // Securely hash the brand new password to save to the database
        $newPasswordHash = password_hash(trim($_POST['newpassword']), PASSWORD_DEFAULT);

        // 1. Fetch the stored hash from the database based strictly on the logged-in email
        $sql = "SELECT Password FROM tblstudents WHERE EmailId=:email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            // 2. Decode and verify if the typed current password matches the database hash
            if(password_verify($currentPasswordInput, $result->Password)) {
                
                // 3. Update the database table with the newly computed hash
                $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $newPasswordHash, PDO::PARAM_STR);
                $chngpwd1->execute();
                
                $msg = "Your Password successfully changed";
            } else {
                $error = "Your current password is wrong";  
            }
        } else {
            $error = "User account not found";
        }
    }
} // Fixed the missing closing brace that was throwing a syntax error
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online notes Management System</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    }
    .succWrap{
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    }
    </style>
</head>
<script type="text/javascript">
function valid() {
    if(document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
        alert("New Password and Confirm Password Field do not match  !!");
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>

<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
<div class="container">
    <div class="row pad-botm">
        <div class="col-md-12">
            <h4 class="header-line">User Change Password</h4>
        </div>
    </div>
    
    <?php if(!empty($error)){ ?>
        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
    <?php } else if(!empty($msg)){ ?>
        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
    <?php }?>            
           
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
            <div class="panel panel-info">
                <div class="panel-heading">Change Password</div>
                <div class="panel-body">
                    <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input class="form-control" type="password" name="password" autocomplete="off" required  />
                        </div>
                        <div class="form-group">
                            <label>Enter Password</label>
                            <input class="form-control" type="password" name="newpassword" autocomplete="off" required  />
                        </div>
                        <div class="form-group">
                            <label>Confirm Password </label>
                            <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required  />
                        </div>
                        <button type="submit" name="change" class="btn btn-info">Change</button> 
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