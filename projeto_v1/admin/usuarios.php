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
	<div class="conteudo">

		<?php 
			if ( isset($_POST['cadastrar']) ) {
				inserirUsuario($conectar);
			}
		?>
		<form class="inserir" method="post" action="" enctype="multipart/form-data">
			<fieldset>
				<legend>Inserir usuário</legend>
			<div>			
				<input required type="text" name="nome" placeholder="Nome">
			</div>
			<div>
				<input required type="email" name="email" placeholder="E-mail">
			</div>
			<div>
				<input required type="password" name="senha" placeholder="Senha">
			</div>
			<div>
				<input required type="password" name="repetesenha" placeholder="Repita sua senha">
			</div>
			<div>
				<input class="btn-2" type="file" name="imagem[]">
			</div>
			<div>
				<input class="button" type="submit" name="cadastrar" value="Cadastrar">
			</div>
			</fieldset>
		</form>
		
		<table class="tb_usuarios">
			<thead>
				<tr>
					<th>3x4</th>
					<th>Nome</th>
					<th>E-mail</th>
					<th>Data</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				//$usuarios = selecionar($conectar, "usuarios");
				$where = "1";
				$order = "nome DESC";
				$usuarios = selecionar($conectar, "usuarios", $where, $order);
				foreach ($usuarios as $usuario) : ?>
					<tr class="active-row">
						<td>
							<?php if (!empty($usuario['img'])) {
							echo "<img width='50px' src='uploads/" . $usuario['img'] . "' />";
							}else{echo "<img width='50px' src=uploads/padrao2.png></img>";} ?>
						</td>
						<td><?php echo $usuario['nome'] ?></td>
						<td><?php echo $usuario['email'] ?></td>
						<td><?php echo $usuario['data_cadastro'] ?></td>
						<td>
							<a href="edit_user.php?id=<?php echo $usuario['id']; ?>&nome=<?php echo $usuario['nome']; ?> " >
								Editar
							</a> - 
							<a href="delete_user.php?id=<?php echo $usuario['id']; ?>&nome=<?php echo $usuario['nome']; ?> " >
								Deletar
							</a> -
							<a href="delete_img.php?id=<?php echo $usuario['id']; ?>&nome=<?php echo $usuario['nome']; ?> " >
								Deletar Foto
							</a>
						</td>
					</tr>					
				<?php endforeach ?>
				
			</tbody>
		</table>
	</div>


<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
</body>
</html>