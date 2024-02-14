<?php
    require("./includes/PHPMailer/class.phpmailer.php");
    require("./includes/PHPMailer/class.smtp.php");
	require 'includes/Conectar.php';
	include('includes/funciones.php');
	$con=new conectar();
	$con->conectar();
	if(isset($_POST['correo'])){
		$mensaje = '';
		$con->query("SELECT * FROM tp_usuarios WHERE tpc_email_usuario='".$_POST['correo']."' AND tpc_estado_usuario='1';");
		if($con->num_rows() > 0){
			$con->next_record();
			$id_usuario=$con->f('tpc_codigo_usuario');
			$usuario=reg('tp_usuarios', 'tpc_codigo_usuario', $id_usuario);
			$clave = "Ram" . rand();
			$con->query("UPDATE tp_usuarios SET tpc_pass_usuario='".md5($clave)."' WHERE tpc_codigo_usuario='".$id_usuario."';");
			$correo = $_POST['correo'];
			//ENVIAR CORREO
			$to = $correo;
			$subject = "Recuperacion de usuario Landing";
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";  
			$headers .= "From: direccion@guiakmymedio.com.co";
			$message = "Cordial Saludo <br><br><label>De acuerdo con su solicitud, hemos recuperado su usuario y clave, te recomendamos cambiarla al iniciar sesion la proxima vez, los datos son <br><br>USUARIO: ".$usuario['tpc_nickname_usuario']."<br>CLAVE: ".$clave."</label><br><br><label>Cualquier inquietud adicional no dude en contactarnos para asesorarle oportunamente. </label><br>";
			mail($to, $subject, $message, $headers);
			//*************
		}
		echo '
		<body>
			<form class="login" method="post" action="index.php">
				<h1 class="login-title">Mensaje Enviado</h1>
				<p align="justify">Si el correo esta registrado, en tu bandeja de entrada estar&aacute; la clave asignada. Revisa en el SPAM si no vez el correo en la bandeja principal</p>
				<input type="submit" value="Ir al inicio" class="login-button">
			</form>
		</body>';exit();
	}
?>
<!DOCTYPE html>
<head>
	<!-- templatemo 418 form pack -->
    <!-- 
    Form Pack
    http://www.templatemo.com/preview/templatemo_418_form_pack 
    -->
	<title>Recuperar Contraseña</title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet" type="text/css">
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
	<link href="css/templatemo_style.css" rel="stylesheet" type="text/css">
	<script>
		function enviar(){
			var email = document.getElementById("email").value;
			if(email == ''){
				alert("Debes ingresar tu correo");
				document.getElementById("email").focus();
			}else{
				document.getElementById("correo").value = email;
				frm1.submit();
			}
		}
	</script>
</head>
<body class="templatemo-bg-gray">
	<div class="container">
		<div class="col-md-12">
			<h1 class="margin-bottom-15">Resetear Contraseña</h1>
			<div class="form-horizontal templatemo-forgot-password-form templatemo-container">	
				<div class="form-group">
		          <div class="col-md-12">
		          	Por favor ingrese el correo registrado de tu cuenta
		          </div>
		        </div>		
		        <div class="form-group">
		          <div class="col-md-12">
		            <input type="email" class="form-control" id="email" name="email" placeholder="Tu correo">	            
		          </div>              
		        </div>
		        <div class="form-group">
		          <div class="col-md-12">
		            <input type="button" value="Enviar" class="btn btn-danger" onclick="enviar()">
					<button onclick="location.href='index.php';" class="btn btn-info">Volver</button>
                    <br><br>
                    <!--<a href="login-1.html">Login One</a> |
                    <a href="login-2.html">Login Two</a>-->
		          </div>
		        </div>
			</div>
			<form name="frm1" method="post" action="forgot-password.php"><input type="hidden" name="correo" id="correo"></form>
		</div>
	</div>
</body>
</html>
</html>