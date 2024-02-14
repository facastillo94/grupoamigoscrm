<?php
	require '../includes/Conectar.php';
	include ('../includes/funciones.php');
	session_start();
	$mkid=$_SESSION["mkid"];
	$mksesion=$_SESSION["mksesion"];
	validar2($mkid, $mksesion);
	$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $mkid);
	$con=new conectar();
	$con->conectar();
	if(isset($_POST['clave_actual']) && isset($_POST['clave_nueva'])){//SI SE RECIBEN PARAMETROS PARA CAMBIAR CLAVE
		$con->query("SELECT * FROM tp_usuarios WHERE tpc_codigo_usuario='".$mkid."' AND tpc_pass_usuario='".md5($_POST['clave_actual'])."';");
		if($con->num_rows() > 0){
			if($_POST['clave_nueva'] == $_POST['reclave_nueva']){
				$con->query("UPDATE tp_usuarios SET tpc_pass_usuario='".md5($_POST['clave_nueva'])."' WHERE tpc_codigo_usuario='".$mkid."';");
				echo '<script type="text/javascript">alert("Clave Actualizada Correctamente");location.href="index.php";</script>';
			}else{
				echo '<script type="text/javascript">alert("Las Dos Claves Nuevas Deben Ser Iguales");location.href="index.php";</script>';
			}
		}else{
			echo '<script type="text/javascript">alert("La Clave Actual Ingresada No Corresponde");location.href="index.php";</script>';
		}
		exit();
	}
	
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Administrativo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
		include('head.php');
	?>
	<script>
		function valide_formu(){
			var upperCase= new RegExp('[A-Z]');
			var lowerCase= new RegExp('[a-z]');
			var numbers = new RegExp('[0-9]');
			var characters = 0;
			var capitalletters = 0;
			var loweletters = 0;
			var number = 0;
			var clave_nueva = document.getElementById("clave_nueva").value;
			var reclave_nueva = document.getElementById("reclave_nueva").value;
			if(clave_nueva != '' && reclave_nueva != ''){
				if(clave_nueva != reclave_nueva){
					alert("La clave nueva debe coindidir en los 2 campos de clave nueva");
					return false;
				}else{
					if (clave_nueva.length >= 8) { characters = 1; } else { characters = 0; };
					if (clave_nueva.match(upperCase)) { capitalletters = 1} else { capitalletters = 0; };
					if (clave_nueva.match(lowerCase)) { loweletters = 1}  else { loweletters = 0; };
					if (clave_nueva.match(numbers)) { number = 1}  else { number = 0; };
					var total = characters + capitalletters + loweletters + number;
					if(total > 3){
						return true;
					}else{
						alert("ERROR: Revisa tu clave que al menos tenga 8 caracteres, una mayuscula, una minuscula y un numero.");
						return false;
					}
				}
			}else{
				return true;
			}
		}
		function cambiartipopass(id){
			if(document.getElementById(id).type=='password'){
				document.getElementById(id).type='text';
			}else{
				document.getElementById(id).type='password';
			}
		}
	</script>
</head>

<body class="materialdesign">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Header top area start-->
    <div class="wrapper-pro">
        <?php
			include('menulateral.php');
		?>
        <div class="content-inner-all">
            <?php
				include('menusuperior.php');
			?>
            <!-- Header top area end-->
            <!-- Breadcome start-->
            <div class="breadcome-area mg-b-30 small-dn">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="breadcome-list map-mg-t-40-gl shadow-reset">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Inicio</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Cambiar Clave</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Breadcome End-->
			<div class="basic-form-area mg-b-15">
                <div class="container-fluid">
                    <div class="row">
						<div class="col-lg-5">
							<div class="sparkline9-list shadow-reset">
								<div class="sparkline9-graph">
									<div class="basic-login-form-ad">
										<div class="row">
											<div class="col-lg-12">
												<div class="basic-login-inner">
													<h3>Cambiar Clave</h3>
													<p>Ingresa tu clave actual y tu clave nueva</p>
													<form action="cambiar_clave.php" method="post" onSubmit="return valide_formu()">
														<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Clave Actual</label>
																</div>
																<div class="col-lg-6">
																	<input type="password" class="form-control" name="clave_actual" id="clave_actual" placeholder="Clave Actual" required="required"/>
																</div>
																<div class="col-lg-2">
																	<a onclick="cambiartipopass('clave_actual')"><img src="../images/ojomostrar.png" height="30px" width="30px"></a>
																</div>
															</div>
														</div>
														<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Clave Nueva</label>
																</div>
																<div class="col-lg-6">
																	<input type="password" class="form-control" name="clave_nueva" id="clave_nueva" placeholder="Clave Nueva" required="required"/>
																</div>
																<div class="col-lg-2">
																	<a onclick="cambiartipopass('clave_nueva')"><img src="../images/ojomostrar.png" height="30px" width="30px"></a>
																</div>
															</div>
														</div>
														<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Clave Nueva (Re)</label>
																</div>
																<div class="col-lg-6">
																	<input type="password" class="form-control" name="reclave_nueva" id="reclave_nueva" placeholder="Clave Nueva" required="required"/>
																</div>
																<div class="col-lg-2">
																	<a onclick="cambiartipopass('reclave_nueva')"><img src="../images/ojomostrar.png" height="30px" width="30px"></a>
																</div>
															</div>
														</div>
														<div class="login-btn-inner">
															<div class="row">
																<div class="col-lg-4"></div>
																<div class="col-lg-8">
																	<div class="login-horizental">
																		<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Cambiar Clave</button>
																	</div>
																</div>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>