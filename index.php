<?php

session_start();

require_once 'config/database.php';

$db = Database::getInstance();

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['success'], $_SESSION['error']);

$sql = "SELECT
            produk.id_produk,
            produk.nama_produk,
            kategori.nama_kategori,
            supplier.nama_supplier,
            produk.harga,
            produk.stok
        FROM produk
        INNER JOIN kategori
            ON produk.id_kategori = kategori.id_kategori
        INNER JOIN supplier
            ON produk.id_supplier = supplier.id_supplier";

$stmt = $db->prepare($sql);
$stmt->execute();

$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris</title>

    <link rel="stylesheet" href="style.css">
    
</head>
<body>

    <h1>Data Inventaris Produk</h1>

    <?php if ($success): ?>
        <p><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Supplier</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            <?php $no = 1; ?>

            <?php foreach ($produk as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($item['nama_supplier']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($item['stok']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $item['id_produk'] ?>">Edit</a>
                        |
                        <a href="delete.php?id=<?= $item['id_produk'] ?>"
                           onclick="return confirm('Yakin ingin menghapus produk ini?')">
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
                    
</body>
</html>
