<?php
$conn = mysqli_connect("sqlXXX.infinityfree.com", "username", "password", "dbname");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>