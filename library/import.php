<?php
$conn = mysqli_connect(
    "tramway.proxy.rlwy.net",
    "root",
    "qrrAyQvwCpvyUsSHZGqufKaWXfSIUINs",
    "railway",
    "57323"
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = file_get_contents("notes.sql");

if (mysqli_multi_query($conn, $sql)) {
    echo "Database Imported Successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>