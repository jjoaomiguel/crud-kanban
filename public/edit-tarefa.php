<?php
include '../includes/conexao.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: read-gerenciar.php');
    exit;
}

// Buscar tarefa existente
$stmt = $mysqli->prepare("
    SELECT id, descricao, setor, prioridade, data_cadastro, status_tarefa, usuario_responsavel
    FROM tarefas WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tarefa = $result->fetch_assoc();

if (!$tarefa) {
    die("Tarefa não encontrada.");
}

$usuarios = $mysqli->query("SELECT id, nome FROM usuarios");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];
    $data_cadastro = $_POST['data_cadastro'];
    $status_tarefa = $_POST['status_tarefa'];
    $usuario_responsavel = $_POST['usuario_responsavel'];

    $update = $mysqli->prepare("
        UPDATE tarefas SET descricao=?, setor=?, prioridade=?, data_cadastro=?, status_tarefa=?, usuario_responsavel=?
        WHERE id=?
    ");
    $update->bind_param("sssssii", $descricao, $setor, $prioridade, $data_cadastro, $status_tarefa, $usuario_responsavel, $id);
    $update->execute();

    header('Location: read-gerenciar.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Tarefa #<?= $tarefa['id'] ?></h2>
    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Descrição:</label>
            <input type="text" name="descricao" value="<?= htmlspecialchars($tarefa['descricao']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Setor:</label>
            <input type="text" name="setor" value="<?= htmlspecialchars($tarefa['setor']) ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Prioridade:</label>
            <select name="prioridade" class="form-select" required>
                <option value="Baixa" <?= $tarefa['prioridade']=='Baixa'?'selected':'' ?>>Baixa</option>
                <option value="Média" <?= $tarefa['prioridade']=='Média'?'selected':'' ?>>Média</option>
                <option value="Alta" <?= $tarefa['prioridade']=='Alta'?'selected':'' ?>>Alta</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Data de Cadastro:</label>
            <input type="date" name="data_cadastro" value="<?= $tarefa['data_cadastro'] ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status:</label>
            <select name="status_tarefa" class="form-select" required>
                <option value="Fazer" <?= $tarefa['status_tarefa']=='Fazer'?'selected':'' ?>>Fazer</option>
                <option value="Fazendo" <?= $tarefa['status_tarefa']=='Fazendo'?'selected':'' ?>>Fazendo</option>
                <option value="Pronto" <?= $tarefa['status_tarefa']=='Pronto'?'selected':'' ?>>Pronto</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Usuário Responsável:</label>
            <select name="usuario_responsavel" class="form-select" required>
                <?php while($u = $usuarios->fetch_assoc()): ?>
                    <option value="<?= $u['id'] ?>" <?= $u['id']==$tarefa['usuario_responsavel']?'selected':'' ?>>
                        <?= htmlspecialchars($u['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Salvar Alterações</button>
            <a href="read-gerenciar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
