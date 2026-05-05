<?php
try {
    $dbh = new PDO(
        "mysql:host=tramway.proxy.rlwy.net;port=57323;dbname=railway",
        "root",
        "qrrAyQvwCpvyUsSHZGqufKaWXfSIUINs"
    );

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}
?>