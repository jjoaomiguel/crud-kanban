<?php
include '../includes/conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: read-gerenciar.php');
exit;
?>
