<?php
// Conexão com o banco
$mysqli = new mysqli("localhost", "root", "", "tarefas_db");
if ($mysqli->connect_errno) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

session_start();
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";
    $cep = $_POST["cep"] ?? "";
    $endereco = $_POST["endereco"] ?? "";
    $bairro = $_POST["bairro"] ?? "";
    $cidade = $_POST["cidade"] ?? "";
    $estado = $_POST["estado"] ?? "";

    // Verifica se o e-mail já existe
    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $msg = "Este e-mail já está cadastrado!";
    } else {
        // Insere o novo usuário
        $stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha);
        if ($stmt->execute()) {
            $msg = "Cadastro realizado com sucesso! <a href='login.php'>Clique aqui para fazer login</a>";
        } else {
            $msg = "Erro ao cadastrar: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastro de Usuário</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow p-4">
        <h3 class="text-center mb-4">Cadastrar Novo Usuário</h3>

        <?php if ($msg): ?>
          <div class="alert alert-info text-center"><?= $msg ?></div>
        <?php endif; ?>

        <form method="post">
          <div class="row mb-3">
            <div class="col">
              <label for="nome" class="form-label">Nome Completo</label>
              <input type="text" name="nome" id="nome" class="form-control" placeholder="Digite seu nome" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Digite seu e-mail" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col">
              <label for="senha" class="form-label">Senha</label>
              <input type="password" name="senha" id="senha" class="form-control" placeholder="Crie uma senha" required>
            </div>
          </div>

          <hr>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="cep" class="form-label">CEP</label>
              <input type="text" name="cep" id="cep" class="form-control" placeholder="00000-000" maxlength="9" required>
            </div>
            <div class="col-md-8">
              <label for="endereco" class="form-label">Endereço</label>
              <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Rua / Avenida" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-5">
              <label for="bairro" class="form-label">Bairro</label>
              <input type="text" name="bairro" id="bairro" class="form-control" required>
            </div>
            <div class="col-md-5">
              <label for="cidade" class="form-label">Cidade</label>
              <input type="text" name="cidade" id="cidade" class="form-control" required>
            </div>
            <div class="col-md-2">
              <label for="estado" class="form-label">UF</label>
              <input type="text" name="estado" id="estado" class="form-control" maxlength="2" required>
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-success">Cadastrar</button>
          </div>
        </form>

        <p class="mt-3 text-center text-muted">
          <small>Já tem uma conta? <a href="../index.php">Faça login</a></small>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script da API ViaCEP -->
<script>
document.getElementById('cep').addEventListener('blur', function() {
  const cep = this.value.replace(/\D/g, '');
  if (cep.length === 8) {
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(response => response.json())
      .then(data => {
        if (!data.erro) {
          document.getElementById('endereco').value = data.logradouro || '';
          document.getElementById('bairro').value = data.bairro || '';
          document.getElementById('cidade').value = data.localidade || '';
          document.getElementById('estado').value = data.uf || '';
        } else {
          alert('CEP não encontrado!');
        }
      })
      .catch(() => alert('Erro ao consultar o CEP!'));
  }
});
</script>

</body>
</html>