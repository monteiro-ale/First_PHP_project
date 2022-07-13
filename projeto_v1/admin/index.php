<?php require_once "functions.php"; ?>
<?php if ( isset($_POST['enviar']) ) {
			logar($conectar);
} ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Login - Acesso a área administrativa</title>
</head>
<body class="body-index">
	
		<div class="login">
			
			<form method="post" action="" id="form_login">
				<div>
					<input required type="email" name="email" placeholder="Seu E-mail">
				</div>
				<div>
					<input required type="password" name="senha" placeholder="Sua Senha">
				</div>
				<input class="button"type="submit" name="enviar" value="Acessar">
			</form>

		</div>



</body>
</html>