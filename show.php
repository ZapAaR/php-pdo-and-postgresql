<?php 
require_once __DIR__ . '/config.php';
$pdo = pdo();

$id = (int)($_GET['id'] ?? 0);
if (!$id){
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("select * from products where id = :id");
$stmt->execute([':id' => $id]);
$produk = $stmt->fetch();
if (!$produk) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Show Produk</title>
</head>
<body class="p-4">
    <div class="container">
        <h1><?= e($produk['name']) ?></h1>
        <p><strong>Price : </strong> <?= number_format((float)$produk['price'], 2) ?></p>
        <p><?= nl2br(e($produk['description'])) ?></p>
        <p><small>Created : <?= e($produk['created_at']) ?></small></p>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
        <a href="edit.php?id=<?= e($produk['id']) ?>" class="btn btn-primary">Edit</a>
    </div>
</body>
</html>