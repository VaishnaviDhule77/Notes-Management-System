<?php 
define('DB_HOST', getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com');
define('DB_USER', getenv('DB_USER') ?: '4FCqzzlLGEh3n2f.root');
define('DB_PASS', getenv('DB_PASS') ?: 'LQG88dIPQ3hZtPaK'); 
define('DB_NAME', getenv('DB_NAME') ?: 'test');
define('DB_PORT', getenv('DB_PORT') ?: '4000');

try {
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    );

    $dbh = new PDO(
        "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, 
        DB_USER, 
        DB_PASS, 
        $options
    );
} catch (PDOException $e) {
    // Print the raw connection message directly to prevent a silent HTTP 500 error
    die("DATABASE CONNECTION FAILED: " . $e->getMessage());
}
?>