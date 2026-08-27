<?php 
define('DB_HOST', getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com');
define('DB_USER', getenv('DB_USER') ?: '4FCqzzlLGEh3n2f.root');
define('DB_PASS', getenv('DB_PASS') ?: 'LQG88dIPQ3hZtPaK'); 
define('DB_NAME', getenv('DB_NAME') ?: 'sys');
define('DB_PORT', getenv('DB_PORT') ?: '4000');

try {
    $ca_path = __DIR__ . '/cacert.pem';

    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    );

    // Pass bundled CA file path to enforce TLS transport
    if (file_exists($ca_path)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $ca_path;
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