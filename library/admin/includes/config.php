<?php 
define('DB_HOST', getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com');
define('DB_USER', getenv('DB_USER') ?: '4FCqzzlLGEh3n2f.root');
define('DB_PASS', getenv('DB_PASS') ?: 'LQG88dIPQ3hZtPaK'); 
define('DB_NAME', getenv('DB_NAME') ?: 'sys');
define('DB_PORT', getenv('DB_PORT') ?: '4000');

try {
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    );

    // Dynamically locate CA bundle without throwing fatal errors
    $local_ca = __DIR__ . '/cacert.pem';
    if (file_exists($local_ca)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $local_ca;
    }

    $dbh = new PDO(
        "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME, 
        DB_USER, 
        DB_PASS, 
        $options
    );
} catch (PDOException $e) {
    exit("DB ERROR: " . $e->getMessage());
}
?>