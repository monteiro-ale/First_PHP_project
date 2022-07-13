<?php
$servidor = "localhost:3307";
$usuarioBd = "root";
$senhaBd = "";
$nomeBd = "first_db_xampp";
$conectar = mysqli_connect($servidor, $usuarioBd, $senhaBd, $nomeBd);

function logar($conectar){
	if ( isset($_POST['enviar']) AND !empty($_POST['senha']) AND !empty($_POST['email'])) {		
		
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$senha = sha1($_POST['senha']);
		$query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha' ";
		//echo $query;
		$executar = mysqli_query( $conectar, $query );
		$resultado = mysqli_fetch_assoc($executar);
		//print_r($resultado);
		if (!empty($resultado)) {
			session_start();
			$_SESSION['nome'] = $resultado['nome'];
			$_SESSION['img'] = $resultado['img'];
			$_SESSION['ativa'] = true;
			header('location:admin.php');
		}else{
			echo "E-mail ou senha inválidos";
		}
	}else{
		echo "E-mail ou senha inválidos";
	}
} //end logar

function deslogar(){
	session_start();
	session_unset();
	session_destroy();
	header("location: index.php");
}

function selecionar($conectar, $tabela, $where=1, $order="id"){
	$query = "SELECT * FROM $tabela WHERE $where ORDER BY $order" ;
	$executar = mysqli_query( $conectar, $query);
	$resultados = mysqli_fetch_all($executar, MYSQLI_ASSOC);	
	return $resultados;
}

function inserirUsuario($conectar){
	if ( isset($_POST['cadastrar']) AND !empty($_POST['email']) AND !empty($_POST['senha']) ){
		$nome = mysqli_real_escape_string($conectar, $_POST['nome']);
		$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
		$senha = sha1($_POST['senha']);

		$nomeArquivo = $_FILES['imagem']['name'][0];
		// print_r($_FILES['imagem']);
		//gera um nome unico pra imagem para não sobrescrever em caso de nomes iguais
		// $novoNome = uniqid(); - Por hora vamos manter a data no nome do arquivo
		$tipo = $_FILES['imagem']['type'][0];
		$nomeTemporario = $_FILES['imagem']['tmp_name'][0];	
		$pasta = "uploads/";
		$tamanho = $_FILES['imagem']['size'][0];
		$tamanhoMaximo = 1024 * 1024 * 5;
		$erros = array();
	

		if (strlen($nome) < 3) {
			$erros[] = "Preecha seu nome completo!";
		}
		if (empty($email)) {
			$erros[] = "Preencha um e-mail válido";
		}
		if ($_POST['senha'] != $_POST['repetesenha']) {
			$erros[] = "Senhas não são iguais!";
		}

		$queryEmail = "SELECT email FROM usuarios WHERE email = '$email'";
		$buscaEmail = mysqli_query( $conectar, $queryEmail);
		$resultEmail = mysqli_fetch_assoc( $buscaEmail );

		if ( !empty($resultEmail['email']) ) {
		 	$erros[] = "E-mail já cadastrado no sistema!";
		} 
		

		if (empty($erros)) {
			
			if (!empty($nomeArquivo)AND !empty($tipo)AND !empty($nomeTemporario)AND !empty($tamanho)) {
				$a = atualizaImagem($conectar, $nomeArquivo,$tamanho,$tamanhoMaximo,$tipo,$nomeTemporario);
				$query = "INSERT INTO usuarios (nome, email, senha, data_cadastro, img) VALUES ( '$nome', '$email', '$senha', NOW(), '$a' )";
				}else{
				$query = "INSERT INTO usuarios (nome, email, senha, data_cadastro) VALUES ( '$nome', '$email', '$senha', NOW())";
				}
			$executar = mysqli_query($conectar, $query);
			// executa o insert
			// $query = "INSERT INTO usuarios (nome, email, senha, data_cadastro, img) VALUES ( '$nome', '$email', '$senha', NOW(), '$novoNome' )";
			// $executar = mysqli_query( $conectar, $query);
			if ($executar) {
				echo "Usuário inserido com sucesso!";
			}else{
				echo "Erro ao inserir Usuário! no banco";
			}

		}else{
			foreach ($erros as $erro) {
				echo $erro . "<br>";
			}
		}
	}else{
		echo "Erro ao inserir Usuário!";
	}

} //end function inserirUsuarios

function deletar($conectar, $tabela, $where, $redirecionar = ""){
	if (!empty($where)) {
		//$id = is_int($where);
		$id = filter_var($where, FILTER_VALIDATE_INT);

		if ($id) {
			$query = "DELETE FROM $tabela WHERE id = $where";
			$executar = mysqli_query($conectar, $query);
			if ($executar) {
				echo "Usuário Deletado com Sucesso!";
				if (!empty($redirecionar)) {
					//header("location: $redirecionar");
					echo "<script>window.location.href = '$redirecionar'</script>";
				}
				
			}else{
				echo "Erro ao deletar!";
			}
		}else{
			echo "ID Inválido!";
		}
	}
}

function editar($conectar, $lstDados = array(), $where){
	if (!empty($where)) {
		$erros = array();
		$id = $lstDados['id'];
		$nome = mysqli_real_escape_string($conectar, $lstDados['nome']);
		$email = mysqli_real_escape_string($conectar, $lstDados['email']);
		$data = $lstDados['data_cadastro'];

		$imagem = $_FILES['imagem'];
		$nomeArquivo = $_FILES['imagem']['name'][0];
		$tipo = $_FILES['imagem']['type'][0];
		$nomeTemporario = $_FILES['imagem']['tmp_name'][0];	
		$pasta = "uploads/";
		$tamanho = $_FILES['imagem']['size'][0];
		$tamanhoMaximo = 1024 * 1024 * 5;

		$senha = $lstDados['senha'];
		$repeteSenha = $lstDados['repetesenha'];

		$consultaEmail = "SELECT email FROM usuarios WHERE email = '" . $email . "' AND id <> ".$id;
		$executarBusca = mysqli_query($conectar, $consultaEmail);
		$resultado = mysqli_num_rows($executarBusca);
		//echo $resultado;

		// $consultaImagem = "SELECT img FROM usuarios WHERE id =".$id;
		// $executarBuscaImagem = mysqli_query($conectar, $consultaImagem);
		// $resultadoImagem = mysqli_fetch_assoc( $executarBuscaImagem);

		if(!empty($resultado)){
			$erros[] = "E-mail já cadastrado!";
		}
			// var_dump($lstDados);
		if (empty($erros)) {
			if (!empty($senha)) {
				// echo"<br>Entrei na condiçao de senha não-vazia<br>";
				$senha = sha1($lstDados['senha']);
				// echo "<br>transformando a senha em sha1<br>";
				$query = "UPDATE usuarios SET nome = '".$nome."', email = '".$email."', senha = '".$senha."', data_cadastro = '".$data."' WHERE id = ".$where;
				$executar = mysqli_query($conectar, $query);
				if ($executar) {
				echo "Senha Atualizada com sucesso!<br>";
				}else{
					echo "Erro ao atualizar a senha";
				}
			}
			
			if (!empty($imagem['name'][0])) {
				$a = atualizaImagem($conectar, $nomeArquivo,$tamanho,$tamanhoMaximo,$tipo,$nomeTemporario);
					if(is_array($a)){
						foreach ($a as $b){
							echo $b."<br>";
						}
					}else{
						$query = "UPDATE usuarios SET nome = '".$nome."', email = '".$email."', data_cadastro = '".$data."', img = '".$a."' WHERE id = ".$where;
						$executar = mysqli_query($conectar, $query);
						if ($executar) {
							echo "Imagem Atualizada com sucesso!<br>";
							}else{
								echo "Erro ao atualizar a imagem";
							}
					}
				}else{
				$query = "UPDATE usuarios SET nome = '".$nome."', email = '".$email."', data_cadastro = '".$data."' WHERE id = ".$where;
				$executar = mysqli_query($conectar, $query);
					if ($executar) {
						echo "Atualizado com sucesso!<br>";
						// var_dump($resultadoImagem);
						// var_dump($imagem);
					}else{
						echo "Erro ao atualizar!<br>";
						// var_dump($imagem);
					}
				}		
			}
		}	else{
				foreach ($erros as $erro) {
					echo $erro . "<br>";
				}
	}	

}
 


function deletarImagem($conectar, $tabela, $where, $redirecionar = ""){
	if (!empty($where)) {
		//$id = is_int($where);
		$id = filter_var($where, FILTER_VALIDATE_INT);

		if ($id) {
			$query = "UPDATE usuarios SET img = NULL WHERE id = $where";
			$executar = mysqli_query($conectar, $query);
			if ($executar) {
				echo "Usuário Deletado com Sucesso!";
				if (!empty($redirecionar)) {
					//header("location: $redirecionar");
					echo "<script>window.location.href = '$redirecionar'</script>";
				}
				
			}else{
				echo "Erro ao deletar!";
			}
		}else{
			echo "ID Inválido!";
		}
	}
}

function atualizaImagem( $conectar,$nomeArquivo,$tamanho,$tamanhoMaximo,$tipo,$nomeTemporario){
	$caminho = "uploads/";
	$hoje = date('d-m-Y_h-i');
	$novoNome = $hoje."-".$nomeArquivo;
	if ($tamanho > $tamanhoMaximo) {
		$erros[] = "Seu arquivo excede o tamanho máximo<br>";
	}
	$extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
		
	if($extensao != 'jpg' && $extensao != 'png' && $extensao != 'jpeg'){
	$erros[] = "Extensão de arquivo não permitido";
		
	}
	$typesPermitidos = ["application/pdf", "image/png", "image/jpg","image/jpeg"];
		
	if ( !in_array( $tipo, $typesPermitidos )) {
		$erros[] = "Tipo de arquivo não permitido!<br>";
	}
	if (move_uploaded_file($nomeTemporario, $caminho.$novoNome)) {
		echo "Upload feito com Sucesso!<br>";
	}else{
		echo "Erro ao Enviar o arquivo imagem";
	}
	// $query = "UPDATE usuarios SET nome = '".$nome."', email = '".$email."', senha = '".$senha."', data_cadastro = '".$data."', img = '".$novoNome."' WHERE id = ".$where;
	if(!empty($erros)){
		return $erros;
	}else{
		return $novoNome;
	}
}