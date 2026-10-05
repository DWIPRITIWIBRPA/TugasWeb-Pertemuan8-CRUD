<?php

session_start();

require_once 'config/database.php';

$db = Database::getInstance();

$stmtKategori = $db->prepare("SELECT * FROM kategori ORDER BY nama_kategori");
$stmtKategori->execute();
$kategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

$stmtSupplier = $db->prepare("SELECT * FROM supplier ORDER BY nama_supplier");
$stmtSupplier->execute();
$supplier = $stmtSupplier->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $id_kategori = $_POST['id_kategori'];
    $id_supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $sql = "INSERT INTO produk
            (nama_produk, id_kategori, id_supplier, harga, stok)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    
    try {
    $stmt->execute([
        $nama_produk,
        $id_kategori,
        $id_supplier,
        $harga,
        $stok
    ]);
    
    $_SESSION['success'] = "Produk berhasil ditambahkan.";
    header("Location: index.php");
    exit;

} catch (PDOException $e) {

    $_SESSION['error'] = "Produk gagal ditambahkan.";
    header("Location: create.php");
    exit;
}
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>
    <h1>Tambah produk</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <p><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">

    <label>Nama Produk</label><br>
    <input type="text" name="nama_produk" required>

    <br><br>

    <label>Kategori</label><br>
    <select name="id_kategori" required>
        <option value="">-- Pilih Kategori --</option>

        <?php foreach ($kategori as $item): ?>
            <option value="<?= htmlspecialchars($item['id_kategori']) ?>">
                <?= htmlspecialchars($item['nama_kategori']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Supplier</label><br>
    <select name="id_supplier" required>
        <option value="">-- Pilih Supplier --</option>

        <?php foreach ($supplier as $item): ?>
            <option value="<?= htmlspecialchars($item['id_supplier']) ?>">
                <?= htmlspecialchars($item['nama_supplier']) ?>
            </option>
        <?php endforeach; ?>
    
    </select>

    <br><br> 
    
    <label>Harga</label><br>
    <input type="number" name="harga" required>

    <br><br>
    
    <label>Stok</label><br>
    <input type="number" name="stok" required>

    <br><br>

    <button type="submit">Simpan</button>

    </form>

</body>

</html>