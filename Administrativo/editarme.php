<?php
	require("../includes/PHPMailer/class.phpmailer.php");
    require("../includes/PHPMailer/class.smtp.php");
	require '../includes/Conectar.php';
	include ('../includes/funciones.php');
	date_default_timezone_set('America/Bogota');
	session_start();
	$mkid=$_SESSION["mkid"];
	$mksesion=$_SESSION["mksesion"];
	validar2($mkid, $mksesion);
	$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $mkid);
	$con=new conectar();
	$con->conectar();
	$con1=new conectar();
	$con1->conectar();
	if(intval($usuario['tpc_rol_usuario']) == 2){//SI NO ES ALIADO O PATROCINADOR
		header('Location: index.php');
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
		//VALIDACION DE CAMBIAR CLAVE -->
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
		//***************************
		
		//VALIDACION AL GUARDAR O EDITAR USUARIO -->
		function validacion(){//VALIDAR IMAGEN
			var fileName = document.getElementById("imagen").files[0].name;
			var fileSize = document.getElementById("imagen").files[0].size;
			if(document.getElementById("imagen").value != ""){
				if(fileSize > 1000000){
					alert("El archivo no debe superar 1MB");
					document.getElementById("imagen").value = "";
					return false;
				}else{
					var ext = fileName.split(".");
					switch (ext[1]) {
						case "jpg":
						case "jpeg":
						case "png": break;
						default:
							alert("El archivo no tiene la extensión adecuada, solo esta permitido JPG, JPEG o PNG");
							document.getElementById("imagen").value = "";
							document.getElementById("imagen").focus();
							return false;
					}
				}
			}
			var rol = document.getElementById("rol").value;
			if(rol == -1 || rol == -2){
				var tpc_aperturahabil_usuariodetalle = document.getElementById("tpc_aperturahabil_usuariodetalle").value;
				var tpc_cierrehabil_usuariodetalle = document.getElementById("tpc_cierrehabil_usuariodetalle").value;
				var tpc_aperturafindesemana_usuariodetalle = document.getElementById("tpc_aperturafindesemana_usuariodetalle").value;
				var tpc_cierrefindesemana_usuariodetalle = document.getElementById("tpc_cierrefindesemana_usuariodetalle").value;
				var tpc_video_usuariodetalle = document.getElementById("tpc_video_usuariodetalle").value;
				var tpc_banner_usuariodetalle = document.getElementById("tpc_banner_usuariodetalle").value;
				var tpc_videofundacion_usuariodetalle = document.getElementById("tpc_videofundacion_usuariodetalle").value;
				var opc = '<? echo $_GET['opc']; ?>';
				if(tpc_aperturahabil_usuariodetalle == "" || tpc_cierrehabil_usuariodetalle == "" || tpc_aperturafindesemana_usuariodetalle == "" || tpc_cierrefindesemana_usuariodetalle == "" || tpc_video_usuariodetalle == "" || tpc_videofundacion_usuariodetalle == ""){
					//if(tpc_banner_usuariodetalle == "" && opc == "nuevo"){
						alert("Debes llenar todos los campos del detalle del aliado o patrocinador");
					//}
					return false;
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
            /*
            SE INCLUYE EL MENU LATERAL DE LA IZQUIERDA CON LAS OPCIONES DE INGRESO A LOS MODULOS
            */
            include('menulateral.php');
        ?>
        <div class="content-inner-all">
            <?php
                /*
                SE INCLUYE MENU SUPERIOR
                */            
                include('menusuperior.php');
            ?>
            <!-- Header top area end-->
            <!-- Breadcome start-->
            <div class="breadcome-area mg-b-30 small-dn">
                <div class="container-fluid">
                    <div class="row">
                        
                    </div>
                </div>
            </div>
            <!-- Breadcome End-->
			<!-- Mobile Menu start -->
			<?php
				include('menumobil.php');
			?>
            <!-- Mobile Menu end -->
			<div class="basic-form-area mg-b-15">
                <div class="container-fluid">
                    <div class="row">
						<div class="col-lg-12">
							<div class="sparkline9-list shadow-reset">
								<div class="sparkline9-graph">
									<div class="basic-login-form-ad">
										<div class="row">
											<div class="col-lg-12">
												<?php
												if($_POST['tipo'] == 'usuario'){
													$con->query("SELECT * FROM tp_usuarios WHERE (tpc_codigo_usuario != '".$_POST['idusuario']."' AND tpc_nickname_usuario='".$_POST['usuario']."') OR (tpc_codigo_usuario != '".$_POST['idusuario']."' AND tpc_identificacion_usuario='".$_POST['identificacion']."');");
													if($con->num_rows() == 0){
														$con->query("UPDATE tp_usuarios SET tpc_telefono_usuario='".$_POST['telefono']."', tpc_direccion_usuario='".$_POST['direccion']."', tpc_ciudad_usuario='".$_POST['ciudad']."', tpc_pais_usuario='".$_POST['pais']."', tpc_identificacion_usuario='".$_POST['identificacion']."', tpc_tipoident_usuario='".$_POST['tipoidentificacion']."', tpc_email_usuario='".$_POST['correo']."', tpc_nickname_usuario='".$_POST['usuario']."', tpc_nombres_usuario='".$_POST['nombre']."', tpc_apellidos_usuario='".$_POST['apellido']."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."';");
														$con->query("INSERT INTO tp_acciones_usuario VALUES (NULL, '".$mkid."', '".date("Y-m-d H:i:s")."', 'Se modifica el usuario con el nombre ".$_POST['usuario']."', '".$_SERVER['REMOTE_ADDR']."');");
														if($_POST['clave'] != ""){
															$con->query("UPDATE tp_usuarios SET tpc_pass_usuario='".md5($_POST['clave'])."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."'");
															$con->query("INSERT INTO tp_acciones_usuario VALUES (NULL, '".$mkid."', '".date("Y-m-d H:i:s")."', 'Se modifica la clave al usuario ".$_POST['usuario']."', '".$_SERVER['REMOTE_ADDR']."');");
														}
														if(intval($usuario["tpc_rol_usuario"]) == -1 || intval($usuario["tpc_rol_usuario"]) == -2){
															alatitudlongitud(trim($_POST['direccion']), trim($_POST['ciudad']), trim($_POST['pais']), $_POST['idusuario'], 1);
															$con->query("SELECT tpc_codigo_usuariodetalle FROM tp_usuarios_detalle WHERE tpc_usuario_usuariodetalle='".$_POST['idusuario']."';");
															$nfilas = $con->num_rows();
															if($nfilas == 0){
																$con->query("INSERT INTO tp_usuarios_detalle VALUES (NULL, '".$_POST['idusuario']."', '".$_POST['tpc_aperturahabil_usuariodetalle']."', '".$_POST['tpc_cierrehabil_usuariodetalle']."', '".$_POST['tpc_aperturafindesemana_usuariodetalle']."', '".$_POST['tpc_cierrefindesemana_usuariodetalle']."', '".$_POST['tpc_video_usuariodetalle']."', '', '', '".$_POST['tpc_url_usuariodetalle']."');");
															}else{
																$con->query("UPDATE tp_usuarios_detalle SET tpc_url_usuariodetalle='".$_POST['tpc_url_usuariodetalle']."', tpc_aperturahabil_usuariodetalle='".$_POST['tpc_aperturahabil_usuariodetalle']."', tpc_cierrehabil_usuariodetalle='".$_POST['tpc_cierrehabil_usuariodetalle']."', tpc_aperturafindesemana_usuariodetalle='".$_POST['tpc_aperturafindesemana_usuariodetalle']."', tpc_cierrefindesemana_usuariodetalle='".$_POST['tpc_cierrefindesemana_usuariodetalle']."', tpc_video_usuariodetalle='".$_POST['tpc_video_usuariodetalle']."' WHERE tpc_usuario_usuariodetalle='".$_POST['idusuario']."';");
															}
															if(isset($_FILES['tpc_banner_usuariodetalle']) && $_FILES['tpc_banner_usuariodetalle']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['tpc_banner_usuariodetalle']['name'];
																if(move_uploaded_file($_FILES['tpc_banner_usuariodetalle']['tmp_name'], $fichero_subido)) {
																	$con->query("UPDATE tp_usuarios_detalle SET tpc_banner_usuariodetalle='".$fichero_subido."' WHERE tpc_usuario_usuariodetalle='".$_POST['idusuario']."';");
																}
															}
														}
														if(isset($_FILES['imagen']) && $_FILES['imagen']['name'] != ''){// SI SE RECIBE IMAGEN
															$usuarioedit = reg('tp_usuarios', 'tpc_codigo_usuario', $_POST['idusuario']);
															$prefijo = substr(md5(uniqid(rand())),0,6);
															//echo  $_FILES['imagen']['name'] . ' <br>';
															$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['imagen']['name'];
															if(move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
																unlink($usuarioedit['tpc_imagen_usuario']);
																chmod($fichero_subido, 0777); 
																$con->query("UPDATE tp_usuarios SET tpc_imagen_usuario='".$fichero_subido."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."';");
															}
														}
														$mensaje="Te actualisaste correctamente";
													}else{
														$mensaje="Error: Al parecer existe un usuario Con El Mismo Nick, Identificaci&oacute;n o Rut";
													}
												}
												//***********************************************************************************************
												$con->query("SELECT * FROM tp_usuarios WHERE tpc_codigo_usuario='".$mkid."' ORDER BY tpc_nickname_usuario;");
												if($con->num_rows() > 0){
													$con->next_record();
													$estados = array("InActivo", "Activo");
													$tp_usuarios_detalle = reg('tp_usuarios_detalle', 'tpc_usuario_usuariodetalle', $mkid);
													echo '<div class="basic-login-inner">
														<h3>Editar Usuario</h3>
														<p>Ingresa todos los datos del usuario</p>
														<form method="post" action="editarme.php" enctype="multipart/form-data">
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Mi Imagen</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="file" name="imagen" id="imagen" class="form-control">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Identificación</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" name="identificacion" id="identificacion" value="'.$con->f("tpc_identificacion_usuario").'" class="form-control" style="width: 100%;" placeholder="Identificacion..." required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Tipo de identificación</label>
																	</div>
																	<div class="col-lg-8">
																		<select name="tipoidentificacion" id="tipoidentificacion" class="form-control">
																			<option value="'.$con->f("tpc_tipoident_usuario").'">'.$con->f("tpc_tipoident_usuario").'</option>
																			<option value="NIT">NIT</option>
																			<option value="CC">Cedula de Ciudadania</option>
																			<option value="CE">Cedula de Extrajeria</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Nombres</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" name="nombre" id="nombre" class="form-control" style="width: 100%;" placeholder="Nombres..." value="'.$con->f("tpc_nombres_usuario").'" required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Apellidos</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" name="apellido" id="apellido" class="form-control" style="width: 100%;" placeholder="Apellidos..." value="'.$con->f("tpc_apellidos_usuario").'" required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Usuario</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" name="usuario" id="usuario" class="form-control" style="width: 100%;" placeholder="Usuario..." value="'.$con->f("tpc_nickname_usuario").'" required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Correo</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="email" name="correo" id="correo" class="form-control" style="width: 100%;" placeholder="Correo..." value="'.$con->f("tpc_email_usuario").'" required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Clave de usuario</label>
																	</div>
																	<div class="col-lg-7">
																		<input type="password" name="clave" id="clave" class="form-control" style="width: 100%;" placeholder="Clave...">
																	</div>
																	<div class="col-lg-1">';
																		?>
																		<a onclick="cambiartipopass('clave')"><img src="../images/ojomostrar.png" height="30px" width="30px"><font color="black"></font></a>
																		<?php
																	echo '</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Telefono</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" name="telefono" id="telefono" class="form-control" value="'.$con->f("tpc_telefono_usuario").'" style="width: 100%;" placeholder="Telefono..." required="required">
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Direcci&oacute;n Principal</label>
																	</div>
																	<div class="col-lg-8">
																		<input type="text" value="'.$con->f("tpc_direccion_usuario").'" name="direccion" id="direccion" class="form-control" style="width: 100%;" placeholder="Dirección..." required="required">
																		NOTA: Poner direccion sin tildes y completa sin ciudad ni minucipio, EJEMPLO: Calle 12b #04-79
																	</div>
																</div>
															</div>
															<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Ciudad</label>
																</div>
																<div class="col-lg-8">
																	<input type="text" value="'.$con->f("tpc_ciudad_usuario").'" name="ciudad" id="ciudad" class="form-control" style="width: 100%;" placeholder="Ciudad..." required="required">
																	NOTA: Poner ciudad sin tildes y ciudad completa, EJEMPLO: Bogota D.C
																</div>
															</div>
														</div>
														<div class="form-group-inner">
															<div class="row">
																<div class="col-lg-4">
																	<label class="login2">Pais</label>
																</div>
																<div class="col-lg-8">
																	<select name="pais" id="pais" required="required" class="form-control">
																		<option value="'.$con->f("tpc_pais_usuario").'">'.$con->f("tpc_pais_usuario").'</option>
																		<option value="Colombia">Colombia</option>
																	</select>
																</div>
															</div>
														</div>';
														if(intval($con->f("tpc_rol_usuario")) == -1 || intval($con->f("tpc_rol_usuario")) == -2){
															echo '<div id="divdetalleusuario">
																<center>
																	<table style="width: 100%;">
																		<tr>
																			<td align="center">
																				<label class="login2">Apertura Dia Hábil</label>
																			</td>
																			<td>
																				<select name="tpc_aperturahabil_usuariodetalle" id="tpc_aperturahabil_usuariodetalle" class="form-control">
																					<option value="'.$tp_usuarios_detalle['tpc_aperturahabil_usuariodetalle'].'">'.$tp_usuarios_detalle['tpc_aperturahabil_usuariodetalle'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($val <= 9){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																					
																				echo '</select>
																			</td>
																			<td align="center">
																				<label class="login2">Cierre Dia Hábil</label>
																			</td>
																			<td>
																				<select name="tpc_cierrehabil_usuariodetalle" id="tpc_cierrehabil_usuariodetalle" class="form-control">
																					<option value="'.$tp_usuarios_detalle['tpc_cierrehabil_usuariodetalle'].'">'.$tp_usuarios_detalle['tpc_cierrehabil_usuariodetalle'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($val <= 9){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																					
																				echo '</select>
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<label class="login2">Apertura Fin de semana</label>
																			</td>
																			<td>
																				<select name="tpc_aperturafindesemana_usuariodetalle" id="tpc_aperturafindesemana_usuariodetalle" class="form-control">
																					<option value="'.$tp_usuarios_detalle['tpc_aperturafindesemana_usuariodetalle'].'">'.$tp_usuarios_detalle['tpc_aperturafindesemana_usuariodetalle'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($val <= 9){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																					
																				echo '</select>
																			</td>
																			<td align="center">
																				<label class="login2">Cierre Fin de semana</label>
																			</td>
																			<td>
																				<select name="tpc_cierrefindesemana_usuariodetalle" id="tpc_cierrefindesemana_usuariodetalle" class="form-control">
																					<option value="'.$tp_usuarios_detalle['tpc_cierrefindesemana_usuariodetalle'].'">'.$tp_usuarios_detalle['tpc_cierrefindesemana_usuariodetalle'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($val <= 9){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																					
																				echo '</select>
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<label class="login2">Tu video embebido</label>
																			</td>
																			<td>
																				<input type="text" value="'.$tp_usuarios_detalle['tpc_video_usuariodetalle'].'" name="tpc_video_usuariodetalle" id="tpc_video_usuariodetalle" class="form-control" placeholder="Ingresa tu link aquí.....">
																			</td>
																			<td align="center">
																				<label class="login2">Banner</label>
																			</td>
																			<td>
																				<input type="file" name="tpc_banner_usuariodetalle" id="tpc_banner_usuariodetalle" class="form-control">
																			</td>
																		</tr>
																		<tr>
																			<td align="center">
																				<label class="login2">URL (Sitio Web)</label>
																			</td>
																			<td>
																				<input type="text" value="'.$tp_usuarios_detalle['tpc_url_usuariodetalle'].'" name="tpc_url_usuariodetalle" id="tpc_url_usuariodetalle" class="form-control" placeholder="Ingresa tu URL aquí.....">
																			</td>
																		</tr>
																	</table>
																</center>
															</div>';
														}
														echo '<div class="login-btn-inner">
															<div class="row">
																<div class="col-lg-4"></div>
																<div class="col-lg-8">
																	<div class="login-horizental">
																		<input type="hidden" name="idusuario" id="idusuario" value="'.$mkid.'">
																		<input type="hidden" name="tipo" id="tipo" value="usuario">
																		<input type="hidden" name="opc" id="opc" value="editarme">
																		<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios</button>
																	</div>
																</div>
															</div>
														</div>
														</form>
													</div>';
												}
												?>
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
	<script>
		function cambiartipopass(id){
			if(document.getElementById(id).type=='password'){
				document.getElementById(id).type='text';
			}else{
				document.getElementById(id).type='password';
			}
		}
	</script>
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>