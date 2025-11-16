<?php

require_once __DIR__ . '/config.php';
$pdo = pdo();

$stmt = $pdo->query("select * from products order by id desc");
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Product - WEB BASIC</title>
</head>

<body class="p-4">
    <div class="container">
        <h1 class="mb-4">Products</h1>
        <a href="create.php" class="btn btn-primary mb-3">+ Add Product</a>
        <?php if (!empty($_GET['msg'])) { ?>
            <div class="alert alert-success"><?= e($_GET['msg']) ?></div>
        <?php } ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($products as $produk) {
                ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= e($produk['name']) ?></td>
                        <td><?= number_format((float)$produk['price']) ?></td>
                        <td><?= e($produk['created_at']) ?></td>
                        <td>
                            <a href="show.php?id=<?= e($produk['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="edit.php?id=<?= e($produk['id']) ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="delete.php" method="POST" style="display:inline;">
                                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="id" value="<?= e($produk['id']) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                <?php if (empty($products)) { ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada produk.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>