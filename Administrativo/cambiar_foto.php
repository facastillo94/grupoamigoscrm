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
			var fileName = document.getElementById("foto_perfil").files[0].name;
			var fileSize = document.getElementById("foto_perfil").files[0].size;
			if(document.getElementById("foto_perfil").value != ""){
				if(fileSize > 1000000){
					alert("El archivo no debe superar 1MB");
					document.getElementById("foto_perfil").value = "";
					return false;
				}else{
					var ext = fileName.split(".");
					switch (ext[1]) {
						case "jpg":
						case "jpeg":
						case "png": break;
						default:
							alert("El archivo no tiene la extensión adecuada, solo esta permitido JPG, JPEG o PNG");
							document.getElementById("foto_perfil").value = "";
							document.getElementById("foto_perfil").focus();
							return false;

					}
				}
				
			}
			return true;
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
				if(isset($_POST['confirma_foto'])){//SI SE RECIBEN PARAMETROS PARA CAMBIAR FOTO
					echo 'DATO:'.$_FILES['foto_perfil']['name'];
					if(isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['name'] != ''){// SI SE RECIBE IMAGEN                               
						$prefijo = substr(md5(uniqid(rand())),0,7);
						$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['foto_perfil']['name'];
						if(!move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $fichero_subido)) {
							$fichero_subido = '';
							echo '<script type="text/javascript">alert("Error en subida de archivo, intentalo mas tarde");location.href="cambiar_foto.php";</script>';
						}else{
							$con->query("UPDATE tp_usuarios SET tpc_imagen_usuario='".$fichero_subido."' WHERE tpc_codigo_usuario='".$mkid."';");
							echo '<script type="text/javascript">alert("Imagen Actualizada Correctamente");location.href="index.php";</script>';
						}
					}
				}else{echo 'NO RECIBIDO'.$_POST['confirma_foto'];}
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
                                            <li><span class="bread-blod">Cambiar Foto</span>
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
													<h3>Cambiar Mi Foto</h3>
													<p>Ingresa la imagen que desees</p>
													<form action="cambiar_foto.php" method="post" onSubmit="return valide_formu()" enctype="multipart/form-data">
														<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Imagen</label>
																</div>
																<div class="col-lg-6">
																	<input type="file" class="form-control" name="foto_perfil" id="foto_perfil" required="required"/>
																</div>
															</div>
														</div>
														<div class="login-btn-inner">
															<div class="row">
																<div class="col-lg-4"></div>
																<div class="col-lg-8">
																	<div class="login-horizental">
																		<input type="hidden" name="confirma_foto" id="confirma_foto" value="1">
																		<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Cambiar Foto</button>
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