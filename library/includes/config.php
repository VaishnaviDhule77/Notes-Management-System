<?php 
define('DB_HOST', getenv('DB_HOST') ?: 'notes-db-vaishnavidhule77-77ed.h.aivencloud.com');
define('DB_USER', getenv('DB_USER') ?: 'avnadmin');
define('DB_PASS', getenv('DB_PASS') ?: ''); // Leave blank in repository code
define('DB_NAME', getenv('DB_NAME') ?: 'defaultdb');
define('DB_PORT', getenv('DB_PORT') ?: '26413');

try {
    $dbh = new PDO(
        "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, 
        DB_USER, 
        DB_PASS, 
        array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
} catch (PDOException $e) {
    exit("DB ERROR: " . $e->getMessage());
}
?>