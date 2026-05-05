<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $dbh = new PDO(
        "mysql:host=tramway.proxy.rlwy.net;port=57323;dbname=railway",
        "root",
        "qrrAyQvwCpvyUsSHZGqufKaWXfSIUINs"
    );
    //echo "DB CONNECTED";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>