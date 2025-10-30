<?php
include '../includes/conexao.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $mysqli->query("
        UPDATE tarefas 
        SET status_tarefa = CASE 
            WHEN status_tarefa = 'Fazer' THEN 'Fazendo'
            WHEN status_tarefa = 'Fazendo' THEN 'Pronto'
            ELSE 'Fazer'
        END
        WHERE id = $id
    ");
}
header('Location: read-gerenciar.php');
exit;
?>
