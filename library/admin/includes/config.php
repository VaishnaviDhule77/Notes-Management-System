<?php 
define('DB_HOST', getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com');
define('DB_USER', getenv('DB_USER') ?: '4FCqzzlLGEh3n2f.root');
define('DB_PASS', getenv('DB_PASS') ?: 'LQG88dIPQ3hZtPaK'); 
define('DB_NAME', getenv('DB_NAME') ?: 'sys');
define('DB_PORT', getenv('DB_PORT') ?: '4000');

try {
    // Array of standard system CA locations across Linux distributions (including Render's container environment)
    $ca_paths = [
        '/etc/ssl/certs/ca-certificates.crt',
        '/etc/pki/tls/certs/ca-bundle.crt',
        '/etc/ssl/ca-bundle.pem'
    ];

    $ssl_ca = null;
    foreach ($ca_paths as $path) {
        if (file_exists($path)) {
            $ssl_ca = $path;
            break;
        }
    }

    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    );

    // Only assign MYSQL_ATTR_SSL_CA if a valid string path exists on the host filesystem
    if ($ssl_ca !== null) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $ssl_ca;
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