<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "tarefas_db";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Conexao falhou: " . $mysqli->connect_error);
}

?>
