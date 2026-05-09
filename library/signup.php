<?php 
// 1. Start output buffering to prevent "Headers already sent" errors
ob_start();
session_start();
include('includes/config.php');

// 2. Enable error reporting during development to catch empty fields
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['signup']))
{
    // 3. Robust Student ID Generation
    // Note: On Render, files like 'studentid.txt' can be reset on redeploy.
    // It is better to use a database AUTO_INCREMENT, but here is a safe fix for your method:
    $count_my_page = ("studentid.txt");
    
    // Create the file if it doesn't exist
    if(!file_exists($count_my_page)){
        file_put_contents($count_my_page, "1000");
    }

    $hits = file($count_my_page);
    $current_id = trim($hits[0]);
    $current_id++;
    
    file_put_contents($count_my_page, $current_id);
    $StudentId = "SID" . $current_id; // Added a prefix for better formatting
    
    $fname=$_POST['fullanme'];
    $email=$_POST['email']; 
    
    // 4. SECURITY: Use password_hash instead of md5 (md5 is easily cracked)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    
    $course=$_POST['course']; 
    $year=$_POST['year']; 
    $semester=$_POST['semester']; 
    $status=1;

    $sql="INSERT INTO tblstudents(StudentId,FullName,EmailId,Password,Status,course,year,semester) VALUES(:StudentId,:fname,:email,:password,:status,:course,:year,:semester)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':course',$course,PDO::PARAM_STR);
    $query->bindParam(':year',$year,PDO::PARAM_STR);
    $query->bindParam(':semester',$semester,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    
    if($query->execute())
    {
        echo '<script>alert("Your Registration successful! Your Student ID is: '.$StudentId.'"); window.location.href="index.php";</script>';
    }
    else 
    {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Student Notes Management System | Student Signup</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <script type="text/javascript">
    function valid() {
        if(document.signup.password.value != document.signup.confirmpassword.value) {
            alert("Password and Confirm Password Field do not match!!");
            document.signup.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
    <script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'emailid='+$("#emailid").val(),
            type: "POST",
            success:function(data){
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function (){}
        });
    }
    </script>    
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User Signup</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 col-md-offset-1">
                    <div class="panel panel-danger">
                        <div class="panel-heading">SIGNUP FORM</div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
                                <div class="form-group">
                                    <label>Enter Full Name</label>
                                    <input class="form-control" type="text" name="fullanme" autocomplete="off" required />
                                </div>
                                            
                                <div class="form-group">
                                    <label>Enter Email</label>
                                    <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" required />
                                    <span id="user-availability-status" style="font-size:12px;"></span> 
                                </div>

                                <div class="form-group">
                                    <label>Enter Password</label>
                                    <input class="form-control" type="password" name="password" autocomplete="off" required />
                                </div>

                                <div class="form-group">
                                    <label>Confirm Password </label>
                                    <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Course Name</label>
                                            <select class="form-control" name="course" id="course" required>
                                                <option value="">Select Course</option>
                                                <option value="B.sc(cs)">B.sc(cs)</option>
                                                <option value="M.sc(chemistry)">M.sc(chemistry)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Year</label>
                                            <select class="form-control" name="year" id="year" required>
                                                <option value="">Select Year</option>
                                                <option value="First year">First year</option>
                                                <option value="Second year">Second year</option>
                                                <option value="Third year">Third year</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Semester</label>
                                            <select class="form-control" name="semester" id="semester" required>
                                                <option value="">Select Semester</option>
                                                <option value="I">I</option>
                                                <option value="II">II</option>
                                                <option value="III">III</option>
                                                <option value="IV">IV</option>
                                                <option value="V">V</option>
                                                <option value="VI">VI</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                <button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now </button>
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
<?php ob_end_flush(); ?>