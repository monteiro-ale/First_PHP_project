<?php require_once 'functions.php'; session_start();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Página do Admin - Inicial</title>
</head>
<body class="body-admin">
<?php if ( isset($_SESSION['ativa']) ) { ?>

	<nav class="menu">
		<ul>
			<li><a href="admin.php">Início</a></li>
			<li><a href="usuarios.php">Gerenciar Usuários</a></li>
			<li><a href="deslogar.php">Sair</a></li>
		</ul>
	</nav>
	<div class="bemvindo">
	<h1>Bem Vindo(a) <?php echo $_SESSION['nome'] ?> ao painel administrativo!</h1>
	<?php if (!empty($_SESSION['img'])) {
		echo "<img class='user' width='100px' src='uploads/" . $_SESSION['img'] . "' />";
		}else{echo "<img class='user' width='100px' src=uploads/padrao2.png></img>";} ?>
	</div>
	<div class="conteudo" id="form_editar">		
		<?php if (isset( $_GET['id'] )) { ?>			
			<h2>Editar informações do usuário: <?php echo $_GET['nome']; ?></h2>
			<?php 
			$where = "id = ". $_GET['id'];
			$usuario = selecionar($conectar, 'usuarios', $where);
			// print_r($usuario);	
			// print_r($_FILES);
			?>
			<?php if (isset($_POST['editar'])) {
					$where = $_POST['id'];
					editar($conectar, $_POST, $where);
				} ?>
			<form method="post" action=""enctype="multipart/form-data"class="inserir">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
				<div>
					<input value="<?php echo $usuario[0]['nome']?>" required type="text" name="nome">
				</div>
				<div>
					<input value="<?php echo $usuario[0]['email']?>" required type="email" name="email">
				</div>
				<div>
					<input type="password" name="senha" placeholder="Alterar senha">
				</div>
				<div>
					<input type="password" name="repetesenha" placeholder="Confirme sua senha">
				</div>
				<div>
					<input class="data" value="<?php echo $usuario[0]['data_cadastro']?>" required type="date" name="data_cadastro">
				</div>
				<div>
					<input type="file" name="imagem[]" class="btn-2">
				</div>

				<input class="button" type="submit" name="editar" value="Atualizar">
			</form>

		<?php }else{
			echo "Usuário não encontrado!";
		} ?>
	</div>

<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>