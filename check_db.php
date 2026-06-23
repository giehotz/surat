<?php
$hostname = "localhost";
$database = "u1448571_surat";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8mb4", $username, $password);
    
    echo "Surat Keluar Schema:\n";
    $stmt = $pdo->query("DESCRIBE surat_keluar");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\nData Surat Keluar:\n";
    $stmt = $pdo->query("SELECT id, nomor_surat, tanggal_surat, tujuan, perihal FROM surat_keluar ORDER BY id DESC LIMIT 5");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
