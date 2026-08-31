<?php
ob_start(); // Prevent header modification issues
session_start();
include('includes/config.php');

// Clear errors for live site
error_reporting(0); 
ini_set('display_errors', 0);

// Guard: Redirect if already logged in
if(isset($_SESSION['login']) && $_SESSION['login'] != '') {
    header("Location: dashboard.php");
    exit();
}

// Login Processing
if(isset($_POST['login'])) {
    $email = trim($_POST['emailid']);
    $passwordInput = trim($_POST['password']); 

    $sql = "SELECT id, EmailId, Password, StudentId, Status FROM tblstudents WHERE EmailId=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            
            $isValidPassword = false;
            
            // Validate password using password_verify OR MD5
            if (password_verify($passwordInput, $result->Password)) {
                $isValidPassword = true;
            } elseif (md5($passwordInput) === $result->Password) {
                $isValidPassword = true;
                
                // Auto-upgrade legacy MD5 hash to modern secure hash
                $newHash = password_hash($passwordInput, PASSWORD_DEFAULT);
                $updateSql = "UPDATE tblstudents SET Password=:newhash WHERE id=:id";
                $updateQuery = $dbh->prepare($updateSql);
                $updateQuery->bindParam(':newhash', $newHash, PDO::PARAM_STR);
                $updateQuery->bindParam(':id', $result->id, PDO::PARAM_INT);
                $updateQuery->execute();
            }

            if ($isValidPassword) {
                $_SESSION['stdid'] = $result->StudentId;
                
                if($result->Status == 1 || $result->Status == 0) {
                    $_SESSION['login'] = $email;
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "<script>alert('Your Account Has been blocked. Please contact admin');</script>";
                }
            } else {
                echo "<script>alert('Invalid Details');</script>";
            }
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Notes Management System | User Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
<div class="container">
    <div class="row">
        <div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-1">
            <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" >
                <div class="carousel-inner">
                    <div class="item active"><img src="assets/img/img1.jpeg" alt="" /></div>
                    <div class="item"><img src="assets/img/img2.jpeg" alt="" /></div>
                    <div class="item"><img src="assets/img/img3.jpeg" alt="" /></div>
                </div>
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example" data-slide-to="1"></li>
                    <li data-target="#carousel-example" data-slide-to="2"></li>
                </ol>
                <a class="left carousel-control" href="#carousel-example" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                <a class="right carousel-control" href="#carousel-example" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        </div>
    </div>
    <hr />

    <div class="row pad-botm" id="login-section">
        <div class="col-md-12">
            <h4 class="header-line">USER LOGIN FORM</h4>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
            <div class="panel panel-info">
                <div class="panel-heading">LOGIN FORM</div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Enter Email id</label>
                            <input class="form-control" type="text" name="emailid" required autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="password" required autocomplete="off" />
                            <p class="help-block"><a href="user-forgot-password.php">Forgot Password</a></p>
                        </div>
                        <button type="submit" name="login" class="btn btn-info">LOGIN </button> | <a href="signup.php">Not Registered Yet?</a>
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