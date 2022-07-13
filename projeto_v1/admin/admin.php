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


<?php } else { 
	//header('location:index.php');
	echo "<script>window.location.href = 'index.php';</script>";
} ?>
<div class="bemvindo">
<h1>Bem Vindo(a) <?php echo $_SESSION['nome'] ?> ao painel administrativo!</h1>
	<?php if (!empty($_SESSION['img'])) {
		echo "<img class='user' width='100px' src='uploads/" . $_SESSION['img'] . "' />";
		}else{echo "<img class='user' width='100px' src=uploads/padrao2.png></img>";} ?>
</div>
</body>
</html>