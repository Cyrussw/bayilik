<?php
$host = ""; // veritabanı sunucusu
$dbname = "bayilik"; // veritabanı adı
$user = ""; // veritabanı kullanıcı adı
$pass = ""; // veritabanı şifresi

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
  // PDO nesnesi oluşturuldu ve $pdo değişkenine atandı
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8mb4");
} catch (PDOException $e) {
  // Hata durumunda hata mesajını göster
  echo "Bağlantı hatası: " . $e->getMessage();
  exit();
}
