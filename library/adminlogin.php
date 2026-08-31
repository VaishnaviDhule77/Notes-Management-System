<?php
// Force PHP to display all fatal errors on screen instead of a blank HTTP 500 page
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Safely handle missing configuration file
if (!file_exists(__DIR__ . '/includes/config.php')) {
    die("Error: includes/config.php file not found in " . __DIR__);
}
include(__DIR__ . '/includes/config.php');

if (isset($_POST['login'])) {
    try {
        $username = trim($_POST['username']);
        $password = md5($_POST['password']);
        
        // Query the database
        $sql = "SELECT UserName, Password FROM admin WHERE UserName=:username AND Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();
        
        if ($query->rowCount() > 0) {
            $_SESSION['alogin'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Details');</script>";
        }
    } catch (PDOException $e) {
        die("<h3>Database Query Error:</h3> " . $e->getMessage() . "<br><br><b>Tip:</b> Check if the <code>admin</code> table exists in your TiDB database.");
    } catch (Exception $e) {
        die("<h3>System Error:</h3> " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Notes Management System - Admin Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
</head>
<body>
    <?php 
    if (file_exists(__DIR__ . '/includes/header.php')) {
        include(__DIR__ . '/includes/header.php');
    }
    ?>

    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">ADMIN LOGIN FORM</h4>
                </div>
            </div>
                     
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">LOGIN FORM</div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <div class="form-group">
                                    <label>Enter Username</label>
                                    <input class="form-control" type="text" name="username" autocomplete="off" required />
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>
                                <button type="submit" name="login" class="btn btn-info">LOGIN</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <?php 
    if (file_exists(__DIR__ . '/includes/footer.php')) {
        include(__DIR__ . '/includes/footer.php');
    }
    ?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>