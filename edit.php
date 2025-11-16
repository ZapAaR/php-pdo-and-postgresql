<?php 
require_once __DIR__ . '/config.php';

$pdo = pdo();

$error = [];

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("select * from products where id = :id");
$stmt->execute([':id' => $id]);
$produk = $stmt->fetch();

if (!$produk){
    header("location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!validate_csrf($_POST['csrf'] ?? '')){
        $error[] = 'invalid csrf token';
    }

    $nama = trim($_POST['name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? '0', FILTER_VALIDATE_FLOAT);

    if ($price === false) $error[] = "Harga tidak valid";

    if (!$error){
        $stmt = $pdo->prepare("update products set name = :name, description = :description, price = :price where id = :id");
        $stmt->execute([':name' => $nama, 
                        ':description' => $desc,
                        ':price' => $price,
                        ':id' => $id]);
        header("Location: index.php?msg=" . urlencode("Product Berhasil Diupdate"));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h1>Edit Product</h1>
        <?php if ($error) { ?>
            <div class="alert alert-danger">
                <?php foreach($error as $e) {?>
                <ul><li> <?= e($e) ?></li></ul>
                <?php } ?>
            </div>
        <?php } ?>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="mb-3">
                <label for="form-labe">Nama</label>
                <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? $produk['name']) ?>">
            </div>
            <div class="mb-3">
                <label for="form-labe">Deskripsi</label>
                <textarea name="description" class="form-control" value="<?= e($_POST['description'] ?? $produk['description']) ?>"></textarea>
            </div>
            <div class="mb-3">
                <label for="form-labe">Harga</label>
                <input type="text" name="price" class="form-control" value="<?= e($_POST['price'] ?? $produk['price']) ?>">
            </div>
            <button class="btn btn-success">Update</button>
            <a href="index.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>