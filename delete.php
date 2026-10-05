<?php

session_start();

require_once 'config/database.php';

$db = Database::getInstance();

$id = $_GET['id'];

$sql = "DELETE FROM produk WHERE id_produk = ?";
$stmt = $db->prepare($sql);

try {
$stmt->execute([$id]);

$_SESSION['success'] = "Produk berhasil dihapus.";
header("Location: index.php");
exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Produk gagal dihapus.";
    header("Location: index.php");
    exit;
}
?>