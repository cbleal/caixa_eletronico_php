<?php 

session_start();

require_once '../model/Conexao.class.php';
require_once '../model/Contas.class.php';

$contas = new Contas();

$agencia = addslashes($_POST['agencia']);
$conta   = addslashes($_POST['conta']);
$senha	 = addslashes(md5($_POST['senha']));

if (isset($conta) && !empty($conta)) {
	$contas->setLogged($agencia, $conta, $senha);
}

?>