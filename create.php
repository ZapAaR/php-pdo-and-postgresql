<?php 
require_once __DIR__ . '/config.php';

$pdo = pdo();

$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!validate_csrf($_POST['csrf'] ?? '')){
        $error[] = 'invalid csrf token';
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST"){
        $nama = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);

        if ($nama === '') $error[] = "Nama Tidak Boleh Kosong";
        if ($price === false) $error[] = "Harga Tidak valid";

        if (!$error){
            $stmt = $pdo->prepare("insert into products (name, description, price) values (:name, :description, :price)");
            $stmt->execute([':name' => $nama,
                            ':description' => $desc,
                            ':price' => $price]);
            header("Location: index.php?msg=" . urlencode("Product Berhasil Ditambahkan"));
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body class="p-4">
    <div class="container">
        <h1>Tambah Product</h1>
            <?php foreach($error as $e){ ?>
                <div class="alert alert-danger">
                    <ul><li><?= e($e) ?></li></ul>
                </div>
            <?php } ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"><?= e($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-control" value="<?= e($_POST['price'] ?? '0.00') ?>">
                </div>
                    <button class="btn btn-success">Save</button>
                    <a href="index.php" class="btn btn-secondary">Back</a>       
        </form>
    </div>
</body>
</html>