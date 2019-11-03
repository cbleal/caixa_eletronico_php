<?php 

session_start();

require_once '../model/Conexao.class.php';
require_once '../model/Contas.class.php';

// OBJETO DA CLASSE QUE CONTÉM OS MÉTODOS
$contas = new Contas();

// CAMPOS ENVIADOS PELO FORMULÁRIO
$tipo  = addslashes($_POST['tipo']);
$valor = $_POST['valor'];

if (isset($valor)) {
	
	// SUBSTITUIR A VÍRGULA PELO PONTO PARA FICAR NO PADRÃO DO BANCO DE DADOS
	$valor = str_replace(",", ".", $valor);
	// COLOCAR NO PADRÃO DO BANCO - FLOAT
	$valor = floatval($valor);

	// CHAMAR O MÉTODO DA CLASSE CONTAS ATRAVÉS DO OBJETO
	$contas->setTransaction($tipo, $valor);

	// REDIRECIONA PARA A PÁGINA INDEX
	header("Location: ../index.php?transaction_success");
}

?>