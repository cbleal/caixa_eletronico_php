<?php

//session_start();

/**
 * Classe para fazer o login no sistema
 */
class Contas extends Conexao
{
	// Método para fazer Login
	public function setLogged($agencia, $conta, $senha)
	{
		// PEGA A CONEXÃO
		$pdo = parent::get_instance();

		// COMANDO SQL NA TABELA
		$sql = "SELECT * FROM contas WHERE agencia = :agencia AND conta = :conta AND senha = :senha";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":agencia", $agencia);
		$sql->bindValue(":conta", $conta);
		$sql->bindValue(":senha", $senha);
		$sql->execute();

		if ($sql->rowCount() > 0) { // SE ENCONTRAR
			// PEGA O RESULTADO
			$sql = $sql->fetch();
			// SETA NA SESSÃO O ID
			$_SESSION['login'] = $sql['id'];
			// REDIRECIONA PARA PÁGINA INDEX
			header("Location: ../index.php?login_success");

		} else { // SE NÃO ENCONTROU
			// REDIRECIONA PARA PÁGINA LOGIN
			header("Location: ../login.php?not_login");
		}
	}

	// Método para fazer Logout
	public function logout()
	{
		unset($_SESSION['login']);
		//header("Location: ../login.php?session_ending_session");
	}

	// Método para listas as contas
	public function listAccounts()
	{
		$pdo = parent::get_instance();

		$sql = "SELECT * FROM contas ORDER BY id ASC";
		$sql = $pdo->prepare($sql);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return $sql->fetchAll();
		}
	}

	// Método para pegar informções de cada conta
	public function getInfo($id)
	{
		$pdo = parent::get_instance();

		$sql = "SELECT * FROM contas WHERE id = :id";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":id", $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return $sql->fetchAll();
		}
	}

	// Método para listar o histórico
	public function listHistoric($id)
	{
		$pdo = parent::get_instance();

		$sql = "SELECT * FROM historico WHERE id_conta = :id_conta";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":id_conta", $id);
		$sql->execute();

		if ($sql->rowCount() > 0) {
			return $sql->fetchAll();
		} else {
			return NULL;
		}
	}

	// Método para efetuar uma transação
	public function setTransaction($tipo, $valor)
	{
		$pdo = parent::get_instance();

		// INSERIR NA TABELA HISTORICO
		$sql = "INSERT INTO historico (id_conta, tipo, valor, data_operacao) VALUES (:id_conta, :tipo, :valor, NOW())";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":id_conta", $_SESSION['login']);
		$sql->bindValue(":tipo", $tipo);
		$sql->bindValue(":valor", $valor);
		$sql->execute();

		// ALTERAR O SALDO DA TABELA CONTAS DEPENDENDO DO TIPO DO LANÇAMENTO
		if ($tipo == 'Deposito') {
			$sql = "UPDATE contas SET saldo = saldo + :valor WHERE id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue(":valor", $valor);
			$sql->bindValue(":id", $_SESSION['login']);
			$sql->execute();
		} else {
			$sql = "UPDATE contas SET saldo = saldo - :valor WHERE id = :id";
			$sql = $pdo->prepare($sql);
			$sql->bindValue(":valor", $valor);
			$sql->bindValue(":id", $_SESSION['login']);
			$sql->execute();
		}
	}

}

?>