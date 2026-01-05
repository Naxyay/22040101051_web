<?php
// db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kalp_projesi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}
// Türkçe karakter sorunu olmaması için
$conn->set_charset("utf8");
?>