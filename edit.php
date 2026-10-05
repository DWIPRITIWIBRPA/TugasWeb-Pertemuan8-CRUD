<?php

session_start();

require_once 'config/database.php';

$db = Database::getInstance();
$id = $_GET['id'];

$sql = "SELECT * FROM produk WHERE id_produk = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);

$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    die("Data produk tidak ditemukan.");
}

$stmtKategori = $db->prepare("SELECT * FROM kategori ORDER BY nama_kategori");
$stmtKategori->execute();
$kategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

$stmtSupplier = $db->prepare("SELECT * FROM supplier ORDER BY nama_supplier");
$stmtSupplier->execute();
$supplier = $stmtSupplier->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] ==='POST') {
    $nama_produk = $_POST['nama_produk'];
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "UPDATE produk SET
                nama_produk = ?,
                id_kategori = ?,
                id_supplier = ?,
                harga = ?,
                stok = ?
            WHERE id_produk = ?";

    $stmt = $db->prepare($sql);

    try {
    $stmt->execute([
        $nama_produk,
        $id_kategori,
        $id_supplier,
        $harga,
        $stok,
        $id
    ]);

    $_SESSION['success'] = "Produk berhasil diperbarui.";
    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = "Produk gagal diperbarui.";
    header("Location: edit.php?id=" . urlencode($id));
    exit;
    
}
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <h1>Edit Produk</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">
        <label>Nama Produk</label><br>
        <input
            type="text"
            name="nama_produk"
            value="<?= htmlspecialchars($produk['nama_produk']) ?>"
            required
        >

        <br><br>

        <label>Kategori</label><br>
        <select name="id_kategori" required>
            <?php foreach ($kategori as $item): ?>
                <option
                    value="<?= htmlspecialchars($item['id_kategori']) ?>"
                    <?= $item['id_kategori'] == $produk['id_kategori'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($item['nama_kategori']) ?>
                </option>

            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Supplier</label><br>
        <select name="id_supplier" required>
            
            <?php foreach ($supplier as $item): ?>
                
                <option
                    value="<?= htmlspecialchars($item['id_supplier']) ?>"
                    <?= $item['id_supplier'] == $produk['id_supplier'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($item['nama_supplier']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Harga</label><br>
        <input
            type="number"
            name="harga"
            value="<?= htmlspecialchars($produk['harga']) ?>"
            required
        >

        <br><br>

        <label>Stok</label>
        <input
            type="number"
            name="stok"
            value="<?= htmlspecialchars($produk['stok']) ?>"
            required
        >

        <br><br>

        <button type="submit">Update</button>
        
    </form>

</body>

</html>