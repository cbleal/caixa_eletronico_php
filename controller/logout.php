<?php 

session_start();

require_once '../model/Conexao.class.php';
require_once '../model/Contas.class.php';

$contas = new Contas();

$contas->logout();

header("Location: ../login.php?session_ending_success");

?>