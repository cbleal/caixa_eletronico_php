<?php 

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
}

?>