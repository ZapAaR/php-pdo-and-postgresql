<?php
require_once __DIR__ . '/config.php';

$pdo = pdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!validate_csrf($_POST['csrf'] ?? '')){
        exit('invalid csrf token');
    }
    $id = (int)($_POST['id'] ?? 0);
    if ($id){
        $stmt = $pdo->prepare("delete from products where id = :id");
        $hapus = $stmt->execute([':id' => $id]);
    }
}
header("location: index.php?msg=" . urlencode("product Berhasil Dihapus"));
exit;
?>