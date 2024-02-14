<!DOCTYPE html>
<html>
<head>
	<?php
	require 'includes/Conectar.php';
	include('includes/funciones.php');
	$con=new conectar();
	$con->conectar();
	if(isset($_POST['username']) && isset($_POST['password'])){
		$mensaje = '';
		$con->query("SELECT * FROM tp_usuarios WHERE tpc_nickname_usuario='".$_POST['username']."' AND tpc_pass_usuario='".md5($_POST['password'])."';");
		if($con->num_rows() > 0){
			$con->next_record();
			$id_usuario=$con->f('tpc_codigo_usuario');
			if(intval($con->f("tpc_estado_usuario")) == 1){
				$usuario=reg('tp_usuarios', 'tpc_codigo_usuario', $id_usuario);
				$sesion=md5(time());
				$vence=time()+3600;
				/*$con->query("UPDATE sesiones SET estado=0 WHERE id_usuario='$id_usuario';");
				$con->query("INSERT INTO sesiones (id_usuario,fecha,sesion,estado) VALUES ('$id_usuario','$vence','$sesion','1')");*/
				ini_set("session.cookie_lifetime","36000");
				session_start();
				$_SESSION["mksesion"] = $sesion;
				$_SESSION["mkid"] = $id_usuario;
				/*setcookie("mksesion",$sesion,time()+3600, '/');
				setcookie("mkid",$id_usuario,time()+3600, '/');*/
				echo '<script type="text/javascript">location.href="./Administrativo/";</script>';
				exit();
			}else{
				echo '<script type="text/javascript">alert("Tu cuenta ha sido desactivada, por favor comunicate con el departamento técnico");location.href="https://guiakmymedio.com.co/index.php/contactanos";</script>';
				exit();
			}
		}else{
			$mensaje = 'Usuario Y/O Contraseña Incorrectos';
		}
	}
	?>
	<!-- templatemo 418 form pack -->
    <!-- 
    Form Pack
    http://www.templatemo.com/preview/templatemo_418_form_pack 
    -->
	<title>Guia Kilometro Y Medio</title>
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
		function cambiartipopass(){
			if(document.getElementById("password").type=='password'){
				document.getElementById("password").type='text';
			}else{
				document.getElementById("password").type='password';
			}
		}
	</script>
</head>
<body class="templatemo-bg-gray">
	<br><br>
	<div class="container">
		<div class="col-md-12">
			<form class="form-horizontal templatemo-container templatemo-login-form-1 margin-bottom-30" role="form" action="index.php" method="post">				
		        <!--<div class="form-group">
		          <div class="col-xs-6">
					<center><img src="images/vtiger.png" height="80px" width="280px"></center><br>
		          </div>
				  <div class="col-xs-6">
					<img src="images/logofundacion.png" height="80px" width="80px" align="right">
		          </div>    				  
		        </div>-->
				<div class="form-group">
		          <div class="col-xs-12">
					<center><img src="images/km2.png" height="120px" width="120px"></center>
				  </div>    				  
		        </div>
		        <div class="form-group">
		          <div class="col-md-12">
					<?php
						if($mensaje != ''){
							echo '<center><font color="red">'.$mensaje.'</font></center><br>';
						}
					?>	  
					<div class="control-wrapper">
		            	<label for="username" class="control-label fa-label"><i class="fa fa-user fa-medium"></i></label>
		            	<input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required="required">
		            </div>	<br>
		          	<div class="control-wrapper">
		            	<label for="password" class="control-label fa-label"><i class="fa fa-lock fa-medium"></i></label>
		            	<input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required="required">
						<a onclick="cambiartipopass()"><img src="images/ojomostrar.png" height="30px" width="30px"><font color="black">&nbsp;Mostrar Contraseña</font></a>
		            </div>
		          </div>
		        </div>
		        <div class="form-group">
		          <div class="col-md-12">
		          	<div class="control-wrapper">
		          		<input type="submit" value="Iniciar Sesión" class="btn btn-info">
		          		<a href="forgot-password.php" class="text-right pull-right">Olvide mi contraseña?</a>
		          	</div>
		          </div>
		        </div><hr>
				<div class="form-group">
		          <div class="col-md-12">
		          	<div class="control-wrapper">
		          		<a href="https://guiakmymedio.com.co/index.php/registra-tu-establecimiento" class="text-right pull-right">No estás registrado? Registrate aquí</a>
		          	</div>
		          </div>
		        </div>
		        <hr>
		      </form>
		      <!--<div class="text-center">
		      	<a href="create-account.html" class="templatemo-create-new">Create new account <i class="fa fa-arrow-circle-o-right"></i></a>	
		      </div>-->
		</div>
	</div>
</body>
</html>