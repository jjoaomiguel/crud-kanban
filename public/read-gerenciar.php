<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$resultado = $mysqli->query("SELECT * FROM tarefas WHERE usuario_responsavel = $usuario_id");
$tarefas = [];
while ($row = $resultado->fetch_assoc()) {
    $tarefas[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Tarefas (Kanban)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
        }
        .kanban-column {
            background: #ffffff;
            border-radius: 10px;
            padding: 15px;
            min-height: 400px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        .kanban-column h4 {
            text-align: center;
            color: #004aad;
            margin-bottom: 15px;
        }
        .task-card {
            background: #e9f0fb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: grab;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s;
        }
        .task-card.dragging {
            opacity: 0.5;
        }
        .task-card:hover {
            transform: translateY(-2px);
        }
        .task-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 5px;
            margin-top: 10px;
        }
        .task-buttons a {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.2s;
            color: white;
        }
        .task-buttons a.btn-primary {
            background-color: #007bff;
            border: none;
        }
        .task-buttons a.btn-primary:hover {
            background-color: #0056b3;
        }
        .task-buttons a.btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .task-buttons a.btn-danger:hover {
            background-color: #a71d2a;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg" style="background-color:rgb(0, 86, 179);">
    <div class="container-fluid">
        <h3 class="text-white">Gerenciamento de Tarefas - Kanban</h3>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link text-white" href="create-usuarios.php">Usuários</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="create-tarefas.php">Tarefas</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="read-gerenciar.php">Gerenciar</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="../index.php">Sair</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row text-center">
        <?php 
        $columns = ['Fazer' => 'Fazer', 'Fazendo' => 'Fazendo', 'Pronto' => 'Pronto'];
        foreach ($columns as $col_id => $col_name): ?>
        <div class="col-md-4">
            <div class="kanban-column" id="<?= $col_id ?>" ondrop="drop(event)" ondragover="allowDrop(event)">
                <h4><?= $col_name ?></h4>
                <?php foreach ($tarefas as $t): if ($t['status_tarefa'] === $col_id): ?>
                    <div class="task-card" draggable="true" ondragstart="drag(event)" data-id="<?= $t['id'] ?>">
                        <div>
                            <strong><?= htmlspecialchars($t['descricao']) ?></strong><br>
                            <small><?= htmlspecialchars($t['setor']) ?> | <?= htmlspecialchars($t['prioridade']) ?></small><br>
                            <small><i><?= htmlspecialchars($t['data_cadastro']) ?></i></small>
                        </div>
                        <div class="task-buttons">
                            <a href="edit-tarefa.php?id=<?= $t['id'] ?>" class="btn btn-primary">
                                Editar
                            </a>
                            <a href="delete-tarefa.php?id=<?= $t['id'] ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                Excluir
                            </a>
                        </div>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.dataset.id);
    ev.target.classList.add("dragging");
}

function drop(ev) {
    ev.preventDefault();
    const id = ev.dataTransfer.getData("text");
    const column = ev.currentTarget.id;

    const task = document.querySelector(`[data-id='${id}']`);
    ev.currentTarget.appendChild(task);
    task.classList.remove("dragging");

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update-status.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(`id=${id}&status=${column}`);
}
</script>

</body>
</html>
