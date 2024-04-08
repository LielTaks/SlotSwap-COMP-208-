<?php
$dsn = 'mysql:host=localhost;dbname=sgbakinp';
$username = 'sgbakinp';
$password = 'Kaynlape10';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>
