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
		//VALIDAR QUE SEA IMAGEN EN LA PROMOCION
		function vimagen_promocion(posnombre){
			var nom_id="";
			if(posnombre == 1){nom_id="tpc_archivo_documento";}
			if(posnombre == 2){nom_id="tpc_archivo_evento";}
			if(posnombre == 3){nom_id="tpc_archivo_promociones_aliados";}
			var fileName = document.getElementById(nom_id).files[0].name;
			var fileSize = document.getElementById(nom_id).files[0].size;
			if(document.getElementById(nom_id).value != ""){
				if(fileSize > 1000000){
					alert("El archivo no debe superar 1MB");
					document.getElementById(nom_id).value = "";
					return false;
				}else{
					var ext = fileName.split(".");
					switch (ext[1]) {
						case "jpg":
						case "jpeg":
						case "png": break;
						default:
							alert("El archivo no tiene la extensión adecuada, solo esta permitido JPG, JPEG o PNG");
							document.getElementById(nom_id).value = "";
							document.getElementById(nom_id).focus();
							return false;

					}
				}
				
			}
			return true;
		}
		//**********************
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
				var tpc_url_usuariodetalle = document.getElementById("tpc_url_usuariodetalle").value;
				//var opc = '<? echo $_GET['opc']; ?>';
				if(tpc_aperturahabil_usuariodetalle == "" || tpc_cierrehabil_usuariodetalle == "" || tpc_aperturafindesemana_usuariodetalle == "" || tpc_cierrefindesemana_usuariodetalle == "" || tpc_video_usuariodetalle == "" || tpc_videofundacion_usuariodetalle == "" || tpc_url_usuariodetalle == ""){
					//if(tpc_banner_usuariodetalle == "" && opc == "nuevo"){
						alert("Debes llenar todos los campos del detalle del aliado o patrocinador");
					//}
					return false;
				}
			}
			return true;
		}
		function armasdir_conjunto(){//ARMAR DIRECCION SEGUN PARAMETROS AL GUARDAR O MODIFICAR COPROPIEDAD
			var param1direccion = document.getElementById("param1direccion").value;
			var param2direccion = document.getElementById("param2direccion").value;
			var param3direccion = document.getElementById("param3direccion").value;
			var param5direccion = document.getElementById("param5direccion").value;
			var param6direccion = document.getElementById("param6direccion").value;
			var param7direccion = document.getElementById("param7direccion").value;
			var param9direccion = document.getElementById("param9direccion").value;
			var param10direccion = document.getElementById("param10direccion").value;//dir_conj
			var cadfinal = '';
			cadfinal = cadfinal + '' + param1direccion + ' ' + param2direccion;
			if(param3direccion != ''){
				cadfinal = cadfinal + '' + param3direccion;
			}
			if(document.getElementById("param4direccion").checked == 1){
				cadfinal = cadfinal + ' Bis';
			}
			if(param5direccion != ''){
				cadfinal = cadfinal + ' ' + param5direccion;
			}
			cadfinal = cadfinal + ' #';
			if(param6direccion != ''){
				cadfinal = cadfinal + '' + param6direccion;
			}
			if(param7direccion != ''){
				cadfinal = cadfinal + '' + param7direccion;
			}
			if(document.getElementById("param8direccion").checked == 1){
				cadfinal = cadfinal + ' Bis';
			}
			cadfinal = cadfinal + '-';
			if(param9direccion != ''){
				cadfinal = cadfinal + '' + param9direccion;
			}
			if(param10direccion != ''){
				cadfinal = cadfinal + ' ' + param10direccion;
			}
			document.getElementById("tpc_direccion_establecimiento").value = cadfinal;
		}
		function check_strength(){//VALIDAR FORTALEZA DE CLAVE
			var upperCase= new RegExp('[A-Z]');
			var lowerCase= new RegExp('[a-z]');
			var numbers = new RegExp('[0-9]');
			var characters = 0;
			var capitalletters = 0;
			var loweletters = 0;
			var number = 0;
			var thisval = document.getElementById("clave").value;
			if(thisval != ''){
				if (thisval.length >= 8) { characters = 1; } else { characters = 0; };
				if (thisval.match(upperCase)) { capitalletters = 1} else { capitalletters = 0; };
				if (thisval.match(lowerCase)) { loweletters = 1}  else { loweletters = 0; };
				if (thisval.match(numbers)) { number = 1}  else { number = 0; };
				var total = characters + capitalletters + loweletters + number;
				if(total < 4){
					alert("ERROR: Revisa tu clave que al menos tenga 8 caracteres, una mayuscula, una minuscula y un numero.");
					document.getElementById("clave").focus();
					return false;
				}
			}
			return validacion();
		}
		function mostrardiv_aliado(){
			var rol = document.getElementById("rol").value;
			if(rol == -1 || rol == -2 || rol == -3){
				document.getElementById("divdetalleusuario").style.display="block";
			}else{
				document.getElementById("divdetalleusuario").style.display="none";
			}
		}
		function cambiartipopass(id){
			if(document.getElementById(id).type=='password'){
				document.getElementById(id).type='text';
			}else{
				document.getElementById(id).type='password';
			}
		}
		function calcularinversion_usuario(){//CALCULAR INVERSIONES EN EDICION DE USUARIO
			var cantidad_establecimientos = document.getElementById("cantidad_establecimientos").value;
			var tpc_inversionestabs_usuario = document.getElementById("tpc_inversionestabs_usuario").value;
			if(document.getElementById("tpc_porcinversion_usuario").value > 100){document.getElementById("tpc_porcinversion_usuario").value = 100;}
			var tpc_porcinversion_usuario = document.getElementById("tpc_porcinversion_usuario").value;
			var total_factura = new Intl.NumberFormat("en-US").format(tpc_inversionestabs_usuario * cantidad_establecimientos); 
			document.getElementById("valorfactura_usuario").innerHTML = '$ '+total_factura;
			var total_inversion = new Intl.NumberFormat("en-US").format(((tpc_inversionestabs_usuario * cantidad_establecimientos)/100) * tpc_porcinversion_usuario); 
			document.getElementById("total_inversion").innerHTML = total_inversion;
			var tpc_arbolesmes_usuario = document.getElementById("tpc_arbolesmes_usuario").value;
			var tpc_inversionarbolesmes_usuario = document.getElementById("tpc_inversionarbolesmes_usuario").value;
			var total_marboles = new Intl.NumberFormat("en-US").format(tpc_arbolesmes_usuario * tpc_inversionarbolesmes_usuario);
			document.getElementById("total_inversion_menarboles").innerHTML = '$ '+total_marboles;
		}
		//******************************************
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
												
																	/*
												//SI SE VA A GUARDAR ALGUNA ACCION EL OPC ES CON POST, SI ES PARA INICIAR ACCION ES LA ACCION GET
																									*/
												if($_POST['tipo'] == 'usuario'){
													if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
														header('Location: index.php');
														exit();
													}
													switch($_POST['opc']){
														case 'nuevo':
															$con->query("SELECT * FROM tp_usuarios WHERE tpc_nickname_usuario='".$_POST['usuario']."' OR tpc_identificacion_usuario='".$_POST['identificacion']."';");
															if($con->num_rows() == 0){                                              
																if(isset($_FILES['imagen']) && $_FILES['imagen']['name'] != ''){// SI SE RECIBE IMAGEN                               
																	$prefijo = substr(md5(uniqid(rand())),0,6);
																	$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['imagen']['name'];
																	if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
																		$fichero_subido = '';
																	}
																}else{
																	$fichero_subido = '';
																}
																$con->query("INSERT INTO tp_usuarios VALUES (NULL, '".$_POST['nombre']."', '".$_POST['apellido']."', '".$_POST['identificacion']."', '".$_POST['tipoidentificacion']."', '".$_POST['paistelefono'].$_POST['telefono']."', '".$_POST['direccion']."', '".$_POST['ciudad']."', '".$_POST['tpc_departamento_usuario']."', '".$_POST['pais']."', '0', '0', '".$_POST['correo']."','".$fichero_subido."', '".$_POST['rol']."', '".$_POST['usuario']."', '".md5($_POST['clave'])."', '1', '".date("Y-m-d H:i:s")."', '".$_POST['tpc_empresa_usuario']."', '".$_POST['tpc_areaencargada_usuario']."', '".$_POST['tpc_encargado_usuario']."', '".$_POST['tpc_cargoencargado_usuario']."', '".$_POST['tpc_celular_usuario']."', '".$_POST['tpc_inversionestabs_usuario']."', '".$_POST['tpc_porcinversion_usuario']."', '".$_POST['tpc_arbolesmes_usuario']."', '".$_POST['tpc_inversionarbolesmes_usuario']."');");
																if(intval($_POST['rol']) == -1 || intval($_POST['rol']) == -2){//ACTUALIZA COORDENADAS DIRECCION ALIADOS PATROCINADORES
																	$con->query("SELECT tpc_codigo_usuario FROM tp_usuarios WHERE tpc_nombres_usuario='".$_POST['nombre']."' AND tpc_apellidos_usuario='".$_POST['apellido']."' AND tpc_email_usuario='".$_POST['correo']."' AND tpc_rol_usuario='".$_POST['rol']."' AND tpc_nickname_usuario='".$_POST['usuario']."'");
																	$con->next_record();
																	$tpc_codigo_usuario = $con->f("tpc_codigo_usuario");
																	alatitudlongitud(trim($_POST['direccion']), trim($_POST['ciudad']), trim($_POST['pais']), $con->f("tpc_codigo_usuario"), 1);
																	if(isset($_FILES['tpc_banner_usuariodetalle']) && $_FILES['tpc_banner_usuariodetalle']['name'] != ''){// SI SE RECIBE IMAGEN                               
																		$prefijo = substr(md5(uniqid(rand())),0,6);
																		$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['tpc_banner_usuariodetalle']['name'];
																		if(!move_uploaded_file($_FILES['tpc_banner_usuariodetalle']['tmp_name'], $fichero_subido)) {
																			$fichero_subido = '';
																		}
																	}else{
																		$fichero_subido = '';
																	}
																	$con->query("INSERT INTO tp_usuarios_detalle VALUES (NULL, '".$tpc_codigo_usuario."', '".$_POST['tpc_aperturahabil_usuariodetalle']."', '".$_POST['tpc_cierrehabil_usuariodetalle']."', '".$_POST['tpc_aperturafindesemana_usuariodetalle']."', '".$_POST['tpc_cierrefindesemana_usuariodetalle']."', '".$_POST['tpc_video_usuariodetalle']."', '".$fichero_subido."', '".$_POST['tpc_videofundacion_usuariodetalle']."', '".$_POST['tpc_url_usuariodetalle']."');");
																}
																$mensaje="Usuario Creado Correctamente";
															}else{
																$mensaje="Error: No Puede Haber Más De Un Usuario Con El Mismo Nick O Identificaci&oacute;n";
															}
														break;
														case 'editar':
															$autorizadoeditar = 1;
															if($_POST['idusuario'] == $mkid && $_POST['rol'] != 2){//SI NOS ESTAMOS MODIFICANDO A NOSOTROS, REVISAMOS LOS USUARIOS A VER SI NOES EL UNICO ADMINISTRADOR POR SI CAMBIO EL ROL
																$con->query("SELECT * FROM tp_usuarios WHERE tpc_codigo_usuario != '".$_POST['idusuario']."' AND tpc_rol_usuario='2';");
																if($con->num_rows() == 0){
																	$autorizadoeditar = 0;
																	$mensaje = "Tu eres el unico administrador, no te puedes cambiar el rol";
																}
															}
															if($autorizadoeditar == 1){
																$con->query("SELECT * FROM tp_usuarios WHERE (tpc_codigo_usuario != '".$_POST['idusuario']."' AND tpc_nickname_usuario='".$_POST['usuario']."') OR (tpc_codigo_usuario != '".$_POST['idusuario']."' AND tpc_identificacion_usuario='".$_POST['identificacion']."');");
																if($con->num_rows() == 0){
																	$con->query("UPDATE tp_usuarios SET tpc_telefono_usuario='".$_POST['paistelefono'].$_POST['telefono']."', tpc_direccion_usuario='".$_POST['direccion']."', tpc_ciudad_usuario='".$_POST['ciudad']."', tpc_departamento_usuario='".$_POST['tpc_departamento_usuario']."', tpc_pais_usuario='".$_POST['pais']."', tpc_identificacion_usuario='".$_POST['identificacion']."', tpc_tipoident_usuario='".$_POST['tipoidentificacion']."', tpc_email_usuario='".$_POST['correo']."', tpc_rol_usuario='".$_POST['rol']."', tpc_nickname_usuario='".$_POST['usuario']."', tpc_nombres_usuario='".$_POST['nombre']."', tpc_apellidos_usuario='".$_POST['apellido']."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."';");
																	$con->query("UPDATE tp_usuarios SET tpc_empresa_usuario='".$_POST['tpc_empresa_usuario']."', tpc_areaencargada_usuario='".$_POST['tpc_areaencargada_usuario']."', tpc_encargado_usuario='".$_POST['tpc_encargado_usuario']."', tpc_cargoencargado_usuario='".$_POST['tpc_cargoencargado_usuario']."', tpc_celular_usuario='".$_POST['tpc_celular_usuario']."', tpc_inversionestabs_usuario = '".$_POST['tpc_inversionestabs_usuario']."', tpc_porcinversion_usuario = '".$_POST['tpc_porcinversion_usuario']."', tpc_arbolesmes_usuario = '".$_POST['tpc_arbolesmes_usuario']."', tpc_inversionarbolesmes_usuario = '".$_POST['tpc_inversionarbolesmes_usuario']."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."';");
																	$con->query("INSERT INTO tp_acciones_usuario VALUES (NULL, '".$mkid."', '".date("Y-m-d H:i:s")."', 'Se modifica el usuario con el nombre ".$_POST['usuario']."', '".$_SERVER['REMOTE_ADDR']."');");
																	if($_POST['clave'] != ""){
																		$con->query("UPDATE tp_usuarios SET tpc_pass_usuario='".md5($_POST['clave'])."' WHERE tpc_codigo_usuario='".$_POST['idusuario']."'");
																		$con->query("INSERT INTO tp_acciones_usuario VALUES (NULL, '".$mkid."', '".date("Y-m-d H:i:s")."', 'Se modifica la clave al usuario ".$_POST['usuario']."', '".$_SERVER['REMOTE_ADDR']."');");
																	}
																	if(intval($_POST['rol']) == -1 || intval($_POST['rol']) == -2){//ACTUALIZA COORDENADAS DIRECCION ALIADOS PATROCINADORES Y DETALLE
																		alatitudlongitud(trim($_POST['direccion']), trim($_POST['ciudad']), trim($_POST['pais']), $_POST['idusuario'], 1);
																		$con->query("SELECT tpc_codigo_usuariodetalle FROM tp_usuarios_detalle WHERE tpc_usuario_usuariodetalle='".$_POST['idusuario']."';");
																		$nfilas = $con->num_rows();
																		if($nfilas == 0){
																			$con->query("INSERT INTO tp_usuarios_detalle VALUES (NULL, '".$_POST['idusuario']."', '".$_POST['tpc_aperturahabil_usuariodetalle']."', '".$_POST['tpc_cierrehabil_usuariodetalle']."', '".$_POST['tpc_aperturafindesemana_usuariodetalle']."', '".$_POST['tpc_cierrefindesemana_usuariodetalle']."', '".$_POST['tpc_video_usuariodetalle']."', '', '".$_POST['tpc_videofundacion_usuariodetalle']."', '".$_POST['tpc_url_usuariodetalle']."');");
																		}else{
																			$con->query("UPDATE tp_usuarios_detalle SET tpc_url_usuariodetalle='".$_POST['tpc_url_usuariodetalle']."', tpc_aperturahabil_usuariodetalle='".$_POST['tpc_aperturahabil_usuariodetalle']."', tpc_cierrehabil_usuariodetalle='".$_POST['tpc_cierrehabil_usuariodetalle']."', tpc_aperturafindesemana_usuariodetalle='".$_POST['tpc_aperturafindesemana_usuariodetalle']."', tpc_cierrefindesemana_usuariodetalle='".$_POST['tpc_cierrefindesemana_usuariodetalle']."', tpc_video_usuariodetalle='".$_POST['tpc_video_usuariodetalle']."', tpc_videofundacion_usuariodetalle='".$_POST['tpc_videofundacion_usuariodetalle']."' WHERE tpc_usuario_usuariodetalle='".$_POST['idusuario']."';");
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
																	$mensaje="Usuario Actualizado Correctamente";
																}else{
																	$mensaje="Error: No Puede Haber Más De Un Usuario Con El Mismo Nick, Identificaci&oacute;n o Rut";
																}
															}
														break;
														case 'importar':
															$tamano = $_FILES['archivo']['size'];
															$tipo = $_FILES['archivo']['type'];
															$archivo = $_FILES['archivo']['name'];
															$error = $_FILES['archivo']['error'];
															if($archivo != "" && $tipo === "application/vnd.ms-excel"){
																$guardados = 0;$erroneos = 0;
																$lineas = file($_FILES['archivo']['tmp_name']);
																$i = 1;
																$datos=explode(";",$lineas[0]);
																if(count($datos) == 8){
																	while($lineas[$i]){
																		$datos=explode(";",$lineas[$i]);
																		$con->query("SELECT * FROM tp_usuarios WHERE tpc_nickname_usuario='".$datos[6]."'");
																		if($con->num_rows() == 0){
																			$ins_persona = "INSERT INTO tp_usuarios VALUES (NULL, '".$datos[0]."', '".$datos[1]."', '".$datos[2]."', '".$datos[3]."', '".$datos[4]."', '".$datos[5]."', '', '0', '".$datos[6]."', '".md5($datos[7])."', '1', '".date("Y-m-d H:i:s")."');";
																			$con->query($ins_persona);
																			$guardados++;
																		}else{
																			$erroneos++;
																		}
																		$i++;
																	}
																	$mensaje = 'Registros Guardados: ' . $guardados . ', Registros Erroneos: '.$erroneos;
																}else{
																	$mensaje = 'Verifica las columnas del archivo';
																}
															}else{
																$mensaje = 'Verifica que el archivo sea CSV';
															}
														break;
														//SI RECIBIMOS MASIVAMENTE EN OPC LA MANDO POST YA QUE POR GET ES MAS LIMITADO
														case 'masivamente':
															switch($_POST['finalmente']){
																case 'editar':
																	echo '<div class="basic-login-inner">
																		<h3>Edición Masiva Usuarios</h3>
																		<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Imagen</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="file" name="imagen" id="imagen" class="form-control">
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Plan</label>
																					</div>
																					<div class="col-lg-8">
																						<select name="rol" id="rol" class="form-control">
																							<option value="">Seleccione.....</option>
																							<option value="0">Plan Gratuito</option>
																							<option value="1">Plan Premium</option>
																							<option value="2">Administrador</option>
																						</select>
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Identificación</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="text" name="identificacion" id="identificacion" value="" class="form-control" style="width: 100%;" placeholder="Identificacion...">
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
																							<option value="">Seleccione.....</option>
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
																						<input type="text" name="nombre" id="nombre" maxlength="12" class="form-control" style="width: 100%;" placeholder="Nombres...">
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Apellidos</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="text" name="apellido" id="apellido" maxlength="12" class="form-control" style="width: 100%;" placeholder="Apellidos...">
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Correo</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="email" name="correo" id="correo" class="form-control" style="width: 100%;" placeholder="Correo...">
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Clave de usuario</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="password" name="clave" id="clave" class="form-control" style="width: 100%;" placeholder="Clave...">
																					</div>
																				</div>
																			</div>
																			<div class="form-group-inner">
																				<div class="row">
																					<div class="col-lg-4">
																						<label class="login2">Telefono</label>
																					</div>
																					<div class="col-lg-8">
																						<input type="number" name="telefono" id="telefono" class="form-control" style="width: 100%;" placeholder="Telefono...">
																					</div>
																				</div>
																			</div>';
																			$cadena = '';
																			for($i=1;$i<=$_POST['contador'];$i++){
																				if(isset($_POST['check'.$i])){
																					$cadena .= $_POST['check'.$i].';';
																				}
																			}
																			echo '<input type="hidden" name="cadena" id="cadena" value="'.$cadena.'">
																			<input type="hidden" name="opc" id="opc" value="edicionmasivafinal">
																			<input type="hidden" name="tipo" id="tipo" value="usuario">
																			<div class="login-btn-inner">
																				<div class="row">
																					<div class="col-lg-4"></div>
																					<div class="col-lg-8">
																						<div class="login-horizental">
																							<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Edicion Masiva Usuario</button>
																						</div>
																					</div>
																				</div>
																			</div>
																		</form>
																	</div>';
																break;
																case 'eliminar':
																	$cuenta_eliminados_usuarios = 0;
																	$cuenta_eliminados_contactos = 0;
																	for($i=1;$i<=$_POST['contador'];$i++){
																		if(isset($_POST['check'.$i])){
																			$con->query("SELECT * FROM tp_establecimientos WHERE tpc_asignadoa_establecimiento='".$_POST['check'.$i]."';");
																			while($con->next_record()){
																				$cuenta_eliminados_contactos++;
																				$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_establecimientos_docuestab='".$con->f("tpc_codigo_establecimiento")."';");
																			}
																			$con->query("DELETE FROM tp_establecimientos WHERE tpc_asignadoa_establecimiento='".$_POST['check'.$i]."';");
																			$con->query("DELETE FROM tp_usuarios_detalle WHERE tpc_usuario_usuariodetalle='".$_POST['check'.$i]."';");
																			$con->query("DELETE FROM tp_usuarios WHERE tpc_codigo_usuario='".$_POST['check'.$i]."';");
																			$cuenta_eliminados_usuarios++;
																		}
																	}
																	$mensaje = $cuenta_eliminados_usuarios.' Usuarios Eliminados y ' .$cuenta_eliminados_contactos. ' Establecimientos Eliminados';
																	echo '
																	<form name="finality" method="get" action="filtros.php">
																		<input type="hidden" name="mensaje" value="'.$mensaje.'">
																		<input type="hidden" name="opcion" value="usuarios">
																	</form>
																	<script type="text/javascript">
																		alert("'.$mensaje.'");
																		finality.submit();
																	</script>';
																	exit();
																break;
															}
														break;
														case 'edicionmasivafinal':
															//COMPARAR QUE CAMPOS SE HAN LLENADO PARA ACTUALIZAR CADA REGISTRO
															if(isset($_FILES['imagen']) && $_FILES['imagen']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/profile/' . $prefijo . '_' . $_FILES['imagen']['name'];
																if(!move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
																	$fichero_subido = '';
																}
															}else{
																$fichero_subido = '';
															}
															//       
															//****************************************************************
															$arrCadena = explode(";", $_POST['cadena']);
															for($i = 0;$i < count($arrCadena); $i++){
																if($arrCadena[$i] != ''){
																	if($fichero_subido != ''){
																		$con->query("UPDATE tp_usuarios SET tpc_imagen_usuario='".$fichero_subido."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");
																	}
																	if($_POST['tipoidentificacion'] != ''){$con->query("UPDATE tp_usuarios SET tpc_tipoident_usuario='".$_POST['tipoidentificacion']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['identificacion'] != ''){$con->query("UPDATE tp_usuarios SET tpc_identificacion_usuario='".$_POST['identificacion']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['rol'] != ''){$con->query("UPDATE tp_usuarios SET tpc_rol_usuario='".$_POST['rol']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['nombre'] != ''){$con->query("UPDATE tp_usuarios SET tpc_nombres_usuario='".$_POST['nombre']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['apellido'] != ''){$con->query("UPDATE tp_usuarios SET tpc_apellidos_usuario='".$_POST['apellido']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['correo'] != ''){$con->query("UPDATE tp_usuarios SET tpc_email_usuario='".$_POST['correo']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['clave'] != ''){$con->query("UPDATE tp_usuarios SET tpc_pass_usuario='".md5($_POST['clave'])."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																	if($_POST['telefono'] != ''){$con->query("UPDATE tp_usuarios SET tpc_telefono_usuario='".$_POST['telefono']."' WHERE tpc_codigo_usuario='".$arrCadena[$i]."';");}
																}
															}
															$mensaje = 'Edición Masiva Ejecutada Correctamente';
														break;
													}
													if($_POST['opc'] != 'masivamente'){
														echo '
														<form name="finality" method="get" action="filtros.php">
															<input type="hidden" name="mensaje" value="'.$mensaje.'">
															<input type="hidden" name="opcion" value="usuarios">
														</form>
														<script type="text/javascript">
															alert("'.$mensaje.'");
															finality.submit();
														</script>';
														exit();
													}
												}
																									
												if($_POST['tipo'] == 'banner'){
													if(intval($usuario['tpc_rol_usuario']) != 2 && intval($usuario['tpc_rol_usuario']) != -1 && intval($usuario['tpc_rol_usuario']) != 1 && intval($usuario['tpc_rol_usuario']) != -2){//SI NO ES ADMIN O ALIADO O INVERSIONISTA O PATROCINADOR
														header('Location: index.php');
														exit();
													}
													switch($_POST['opc']){
														case 'nuevo':
															$files = array_filter($_FILES['tpc_archivosimagenes']['name']);	
															$total_count = count($_FILES['tpc_archivosimagenes']['name']);			
															if($total_count > 6){$total_count = 6;}
															$imagenesfinales = array("", "", "", "", "", "");
															for( $i=0 ; $i < $total_count ; $i++ ) {
															   //The temp file path is obtained
															   $tmpFilePath = $_FILES['tpc_archivosimagenes']['tmp_name'][$i];
															   //A file path needs to be present
															   if ($tmpFilePath != ""){
																  //Setup our new file path
																  $prefijo = substr(md5(uniqid(rand())),0,6);
																  $newFilePath = "banners/" . $prefijo . "_" . $_FILES['tpc_archivosimagenes']['name'][$i];
																  //File is uploaded to temp dir
																  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
																	 //Other code goes here
																	 $imagenesfinales[$i] = "https://guiakmymedio.com.co/grupoamigoscrm/Administrativo/banners/" . $prefijo . "_" .$_FILES['tpc_archivosimagenes']['name'][$i];
																	chmod("./banners/" . $prefijo . "_" .$_FILES['tpc_archivosimagenes']['name'][$i], 0777);
																  }
															   }
															}
															if($_POST['tipoban'] == 'principal'){
																$con->query("INSERT INTO tp_banner VALUES (NULL, '".$_POST['tpc_nombre_banner']."', '".date("Y-m-d H:i:s")."', '".$imagenesfinales[0]."', '".$imagenesfinales[1]."', '".$imagenesfinales[2]."', '".$imagenesfinales[3]."','".$imagenesfinales[4]."', '".$imagenesfinales[5]."', '".$_POST['tpc_ciudad_establecimiento']."', '".$_POST['tpc_departamento_establecimiento']."', '".$_POST['tpc_usuario_banner']."', '".date("Y-m-d", strtotime($_POST['tpc_validodesde_banner']))." 00:00:00', '".date("Y-m-d", strtotime($_POST['tpc_validohasta_banner']))." 23:59:59');");
																$mensaje="Banner ".$_POST['tipoban']." creado correctamente";
															}else{
																$fechaactual = date("Y-m-d H:i:s");
																$con->query("INSERT INTO tp_banner_cat VALUES (NULL, '".$_POST['tpc_nombre_banner']."', '".$fechaactual."', '".$imagenesfinales[0]."', '".$_POST['tpc_usuario_banner']."', '".$_POST['tpc_departamento_establecimiento']."', '".$_POST['tpc_ciudad_establecimiento']."', '".$_POST['tpc_categoria_establecimiento']."', '".date("Y-m-d", strtotime($_POST['tpc_validodesde_banner']))." 00:00:00', '".date("Y-m-d", strtotime($_POST['tpc_validohasta_banner']))." 23:59:59');");
																$tp_banner_cat = reg('tp_banner_cat', 'tpc_nombre_banner_cat', $_POST['tpc_nombre_banner'], " AND tpc_fechacreacion_banner_cat='".$fechaactual."' AND tpc_imagen_banner_cat='".$imagenesfinales[0]."' AND tpc_categoria_banner_cat='".$_POST['tpc_categoria_establecimiento']."'");
																if(intval($usuario['tpc_rol_usuario']) == 2){
																	$cadena = "SELECT * FROM tp_establecimientos ";
																	$cuentasi = 0;
																	if($_POST['tpc_categoria_establecimiento'] != "todas"){
																		$cuentasi++;
																		$cadena .= "WHERE tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."' ";
																	}
																	if($_POST['tpc_departamento_establecimiento'] != "todas"){
																		if($cuentasi>0){$cadena .= "AND tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' ";}
																		else{$cadena .= "WHERE tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' ";}
																		$cuentasi++;
																	}
																	if($_POST['tpc_ciudad_establecimiento'] != "todas"){
																		if($cuentasi>0){$cadena .= "AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' ";}
																		else{$cadena .= "WHERE tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' ";}
																		$cuentasi++;
																	}
																}else{
																	if($usuario['tpc_rol_usuario'] == 1){
																		$cadena = "SELECT * FROM tp_establecimientos WHERE tpc_asignadoa_establecimiento='".$mkid."';";
																	}else{
																		$cadena = "SELECT * FROM tp_establecimientos WHERE tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."' AND tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."'";
																	}
																}
																$con->query($cadena);
																$contador=0;
																while($con->next_record()){
																	$con1->query("INSERT INTO tp_establecimiento_banner VALUES (NULL, '".$con->f("tpc_codigo_establecimiento")."', '".$tp_banner_cat['tpc_codigo_banner_cat']."');");
																	$contador++;
																}
																$mensaje="Banner ".$_POST['tipoban']." creado correctamente, asociado a ".$contador." establecimientos";
															}
														break;
														case 'editar':
															if($_POST['tipoban'] == 'principal'){
																$tp_banner = reg('tp_banner', 'tpc_codigo_banner', $_POST['tpc_codigo_banner']);
																$files = array_filter($_FILES['tpc_archivosimagenes']['name']);	
																$total_count = count($_FILES['tpc_archivosimagenes']['name']);			
																if($total_count > 6){$total_count = 6;}
																$imagenesfinales = array("", "", "", "", "", "");
																for( $i=0 ; $i < $total_count ; $i++ ) {
																   $tmpFilePath = $_FILES['tpc_archivosimagenes']['tmp_name'][$i];
																   if ($tmpFilePath != ""){
																	  $newFilePath = "banners/" . $_FILES['tpc_archivosimagenes']['name'][$i];
																	  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
																		 $imagenesfinales[$i] = "https://guiakmymedio.com.co/grupoamigoscrm/Administrativo/banners/".$_FILES['tpc_archivosimagenes']['name'][$i];
																		chmod("./banners/".$_FILES['tpc_archivosimagenes']['name'][$i], 0777);
																	  }
																   }
																}
																$archivo = explode("/", $tp_banner['tpc_imagen1_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen2_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen3_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen4_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen5_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen6_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$con->query("UPDATE tp_banner SET tpc_nombre_banner='".$_POST['tpc_nombre_banner']."', tpc_imagen1_banner='".$imagenesfinales[0]."', tpc_imagen2_banner='".$imagenesfinales[1]."', tpc_imagen3_banner='".$imagenesfinales[2]."', tpc_imagen4_banner='".$imagenesfinales[3]."',tpc_imagen5_banner='".$imagenesfinales[4]."', tpc_imagen6_banner='".$imagenesfinales[5]."', tpc_ciudad_banner='".$_POST['tpc_ciudad_establecimiento']."', tpc_departamento_banner='".$_POST['tpc_departamento_establecimiento']."', tpc_usuario_banner='".$_POST['tpc_usuario_banner']."', tpc_validodesde_banner='".$_POST['tpc_validodesde_banner']."', tpc_validohasta_banner='".$_POST['tpc_validohasta_banner']."' WHERE tpc_codigo_banner='".$_POST['tpc_codigo_banner']."';");
																$mensaje="Banner Actualizado Correctamente";
															}
															if($_POST['tipoban'] == 'secundario'){
																$tp_banner_cat = reg('tp_banner_cat', 'tpc_codigo_banner_cat', $_POST['tpc_codigo_banner_cat']);
																$files = array_filter($_FILES['tpc_archivosimagenes']['name']);	
																$total_count = count($_FILES['tpc_archivosimagenes']['name']);			
																if($total_count > 6){$total_count = 6;}
																$imagenesfinales = array("", "", "", "", "", "");
																for( $i=0 ; $i < $total_count ; $i++ ) {
																   $tmpFilePath = $_FILES['tpc_archivosimagenes']['tmp_name'][$i];
																   if ($tmpFilePath != ""){
																      $prefijo = substr(md5(uniqid(rand())),0,6);
																	  $newFilePath = "banners/" . $prefijo . "_" . $_FILES['tpc_archivosimagenes']['name'][$i];
																	  if(move_uploaded_file($tmpFilePath, $newFilePath)) {
																		 $imagenesfinales[$i] = "https://guiakmymedio.com.co/grupoamigoscrm/Administrativo/banners/". $prefijo . "_" .$_FILES['tpc_archivosimagenes']['name'][$i];
																		chmod("./banners/" . $prefijo . "_" .$_FILES['tpc_archivosimagenes']['name'][$i], 0777);
																	  }
																   }
																}
																$archivo = explode("/", $tp_banner_cat['tpc_imagen_banner_cat']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$con->query("UPDATE tp_banner_cat SET tpc_nombre_banner_cat='".$_POST['tpc_nombre_banner_cat']."', tpc_imagen_banner_cat='".$imagenesfinales[0]."', tpc_usuario_banner_cat='".$_POST['tpc_usuario_banner_cat']."', tpc_departamento_banner_cat='".$_POST['tpc_departamento_establecimiento']."', tpc_ciudad_banner_cat='".$_POST['tpc_ciudad_establecimiento']."', tpc_categoria_banner_cat='".$_POST['tpc_categoria_establecimiento']."', tpc_validodesde_banner_cat='".$_POST['tpc_validodesde_banner']."', tpc_validohasta_banner_cat='".$_POST['tpc_validohasta_banner']."' WHERE tpc_codigo_banner_cat='".$_POST['tpc_codigo_banner_cat']."';");
																$con->query("DELETE FROM tp_establecimiento_banner WHERE tpc_banner_cat_establecimiento_banner='".$_POST['tpc_codigo_banner_cat']."'");
																$cadena = "";
																if(intval($usuario['tpc_rol_usuario']) == 2){
																	$cadena = "SELECT * FROM tp_establecimientos ";
																	$cuentasi = 0;
																	
																	if($_POST['tpc_categoria_establecimiento'] != "todas"){
																		$cuentasi++;
																		$cadena .= "WHERE tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."' ";
																	}
																	if($_POST['tpc_departamento_establecimiento'] != "todas"){
																		if($cuentasi>0){$cadena .= "AND tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' ";}
																		else{$cadena .= "WHERE tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' ";}
																		$cuentasi++;
																	}
																	if($_POST['tpc_ciudad_establecimiento'] != "todas"){
																		if($cuentasi>0){$cadena .= "AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' ";}
																		else{$cadena .= "WHERE tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' ";}
																		$cuentasi++;
																	}
																}else{
																	if($usuario['tpc_rol_usuario'] == 1){
																		$cadena = "SELECT * FROM tp_establecimientos WHERE tpc_asignadoa_establecimiento='".$mkid."';";
																	}else{
																		$cadena = "SELECT * FROM tp_establecimientos WHERE tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."' AND tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."'";
																	}
																}
																$con->query($cadena);
																$contador=0;
																while($con->next_record()){
																	$con1->query("INSERT INTO tp_establecimiento_banner VALUES (NULL, '".$con->f("tpc_codigo_establecimiento")."', '".$_POST['tpc_codigo_banner_cat']."');");
																	$contador++;
																}
																$mensaje="Banner ".$_POST['tipoban']." creado correctamente, asociado a ".$contador." establecimientos";
															}
														break;
													}
													echo '
														<form name="finality" method="get" action="banners.php">
															<input type="hidden" name="mensaje" value="'.$mensaje.'">
															<input type="hidden" name="opc" value="'.$_POST['tipoban'].'">
														</form>
														<script type="text/javascript">
															alert("'.$mensaje.'");
															finality.submit();
														</script>';
														exit();
												}
												if($_POST['tipo'] == 'contacto'){
													switch($_POST['opc']){
														case 'nuevo':
															$con->query("SELECT * FROM tp_establecimientos WHERE tpc_nombre_establecimiento='".$_POST['tpc_nombre_establecimiento']."' AND tpc_direccion_establecimiento='".$_POST['tpc_direccion_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."';");
															if($con->num_rows() == 0){                                              
																if(isset($_FILES['tpc_imagen_establecimiento']) && $_FILES['tpc_imagen_establecimiento']['name'] != ''){// SI SE RECIBE IMAGEN                               
																	$prefijo = substr(md5(uniqid(rand())),0,6);
																	$fichero_subido = 'img/logo/' . $prefijo . '_' . $_FILES['tpc_imagen_establecimiento']['name'];
																	if(!move_uploaded_file($_FILES['tpc_imagen_establecimiento']['tmp_name'], $fichero_subido)) {
																		$fichero_subido = '';
																	}
																}else{
																	$fichero_subido = '';
																}
																if(intval($usuario['tpc_rol_usuario']) == 2){
																	$con->query("INSERT INTO tp_establecimientos VALUES (NULL, '".$_POST['tpc_nombre_establecimiento']."', '".$_POST['tpc_categoria_establecimiento']."', '".$_POST['indicativoparticular'].$_POST['tpc_telparticular_establecimiento']."', '".$_POST['indicativomovil'].$_POST['tpc_movil_establecimiento']."', '".$_POST['tpc_email_establecimiento']."', '".$_POST['tpc_asignadoa_establecimiento']."', '".$_POST['tpc_tipodocumento_establecimiento']."', '".$_POST['tpc_numdocumento_establecimiento']."', '".$_POST['tpc_direccion_establecimiento']."', '".$_POST['tpc_pais_establecimiento']."', '".$_POST['tpc_departamento_establecimiento']."', '".$_POST['tpc_ciudad_establecimiento']."', '".$_POST['tpc_localidad_establecimiento']."', '".$_POST['tpc_estrato_establecimiento']."', '".$_POST['tpc_videofundacion_establecimiento']."', '".$_POST['tpc_videoinversionista_establecimiento']."', '".$fichero_subido."', '', '', '".$_POST['tpc_aperturahabil_establecimiento']."', '".$_POST['tpc_cierrehabil_establecimiento']."', '".$_POST['tpc_aperturafinsemana_establecimiento']."', '".$_POST['tpc_cierrefinsemana_establecimiento']."', '".$_POST['tpc_sitioweb_establecimiento']."', '".$_POST['tpc_facebook_establecimiento']."', 'CRM', '".date("Y-m-d H:i:s")."', '".$_POST['tpc_hacedomicilios_establecimiento']."', '".$_POST['tpc_valordomicilio_establecimiento']."');");
																}else{
																	$con->query("INSERT INTO tp_establecimientos VALUES (NULL, '".$_POST['tpc_nombre_establecimiento']."', '".$_POST['tpc_categoria_establecimiento']."', '".$_POST['tpc_telparticular_establecimiento']."', '".$_POST['tpc_movil_establecimiento']."', '".$_POST['tpc_email_establecimiento']."', '".$_POST['tpc_asignadoa_establecimiento']."', '".$_POST['tpc_tipodocumento_establecimiento']."', '".$_POST['tpc_numdocumento_establecimiento']."', '".$_POST['tpc_direccion_establecimiento']."', '".$_POST['tpc_pais_establecimiento']."', '".$_POST['tpc_departamento_establecimiento']."', '".$_POST['tpc_ciudad_establecimiento']."', '".$_POST['tpc_localidad_establecimiento']."', '".$_POST['tpc_estrato_establecimiento']."', 'https://www.youtube.com/embed/Z4_9oZmamIo', '".$_POST['tpc_videoinversionista_establecimiento']."', '".$fichero_subido."', '', '', '".$_POST['tpc_aperturahabil_establecimiento']."', '".$_POST['tpc_cierrehabil_establecimiento']."', '".$_POST['tpc_aperturafinsemana_establecimiento']."', '".$_POST['tpc_cierrefinsemana_establecimiento']."', '".$_POST['tpc_sitioweb_establecimiento']."', '".$_POST['tpc_facebook_establecimiento']."', 'CRM', '".date("Y-m-d H:i:s")."', '".$_POST['tpc_hacedomicilios_establecimiento']."', '".$_POST['tpc_valordomicilio_establecimiento']."');");
																}
																$con->query("SELECT tpc_codigo_establecimiento FROM tp_establecimientos WHERE tpc_nombre_establecimiento='".$_POST['tpc_nombre_establecimiento']."' AND tpc_direccion_establecimiento='".$_POST['tpc_direccion_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."'");
																$con->next_record();
																$tpc_codigo_establecimiento = $con->f("tpc_codigo_establecimiento");
																$con->query("INSERT INTO tp_establecimientos_propietario VALUES (NULL, '".$_POST["tpc_nombres_establecimientos_propietario"]."', '".$_POST["tpc_apellidos_establecimientos_propietario"]."', '".$_POST["tpc_tipodocumento_establecimientos_propietario"]."', '".$_POST["tpc_documento_establecimientos_propietario"]."', '".$_POST["tpc_fechanacimiento_establecimientos_propietario"]."', '".$_POST["tpc_genero_establecimientos_propietario"]."', '".$_POST["tpc_celular_establecimientos_propietario"]."', '".$tpc_codigo_establecimiento."');");
																alatitudlongitud($_POST['tpc_direccion_establecimiento'], $_POST['tpc_ciudad_establecimiento'], $_POST['tpc_pais_establecimiento'], $tpc_codigo_establecimiento, 0);
																$mensaje="Establecimiento Creado Correctamente";
															}else{
																$mensaje="Error: No puede haber más de un establecimiento con la misma dirección y ciudad";
															}
														break;
														case 'editar':
															$tp_establecimientos = reg('tp_establecimientos', 'tpc_codigo_establecimiento', $_POST['tpc_codigo_establecimiento']);
															$con->query("SELECT * FROM tp_establecimientos WHERE tpc_nombre_establecimiento='".$_POST['tpc_nombre_establecimiento']."' AND tpc_direccion_establecimiento='".$_POST['tpc_direccion_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' AND tpc_codigo_establecimiento != '".$_POST['tpc_codigo_establecimiento']."';");
															if($con->num_rows() == 0){                                              
																if(isset($_FILES['tpc_imagen_establecimiento']) && $_FILES['tpc_imagen_establecimiento']['name'] != ''){// SI SE RECIBE IMAGEN                               
																	$prefijo = substr(md5(uniqid(rand())),0,6);
																	$fichero_subido = 'img/logo/' . $prefijo . '_' . $_FILES['tpc_imagen_establecimiento']['name'];
																	if(move_uploaded_file($_FILES['tpc_imagen_establecimiento']['tmp_name'], $fichero_subido)) {
																		/*if($tp_establecimientos['tpc_imagen_establecimiento'] != ''){
																			unlink($tp_establecimientos['tpc_imagen_establecimiento']);
																		}*/
																		$con->query("UPDATE tp_establecimientos SET tpc_imagen_establecimiento='".$fichero_subido."' WHERE tpc_codigo_establecimiento='".$_POST['tpc_codigo_establecimiento']."';");
																	}
																}
																$con->query("UPDATE tp_establecimientos SET tpc_nombre_establecimiento='".$_POST['tpc_nombre_establecimiento']."', tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."', tpc_telparticular_establecimiento='".$_POST['tpc_telparticular_establecimiento']."', tpc_movil_establecimiento='".$_POST['tpc_movil_establecimiento']."', tpc_email_establecimiento='".$_POST['tpc_email_establecimiento']."', tpc_asignadoa_establecimiento='".$_POST['tpc_asignadoa_establecimiento']."', tpc_tipodocumento_establecimiento='".$_POST['tpc_tipodocumento_establecimiento']."', tpc_numdocumento_establecimiento='".$_POST['tpc_numdocumento_establecimiento']."', tpc_direccion_establecimiento='".$_POST['tpc_direccion_establecimiento']."', tpc_pais_establecimiento='".$_POST['tpc_pais_establecimiento']."', tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."', tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."', tpc_localidad_establecimiento='".$_POST['tpc_localidad_establecimiento']."', tpc_videofundacion_establecimiento='".$_POST['tpc_videofundacion_establecimiento']."', tpc_videoinversionista_establecimiento='".$_POST['tpc_videoinversionista_establecimiento']."', tpc_aperturahabil_establecimiento='".$_POST['tpc_aperturahabil_establecimiento']."', tpc_cierrehabil_establecimiento='".$_POST['tpc_cierrehabil_establecimiento']."', tpc_aperturafinsemana_establecimiento='".$_POST['tpc_aperturafinsemana_establecimiento']."', tpc_cierrefinsemana_establecimiento='".$_POST['tpc_cierrefinsemana_establecimiento']."', tpc_sitioweb_establecimiento='".$_POST['tpc_sitioweb_establecimiento']."', tpc_facebook_establecimiento='".$_POST['tpc_facebook_establecimiento']."', tpc_hacedomicilios_establecimiento='".$_POST['tpc_hacedomicilios_establecimiento']."', tpc_valordomicilio_establecimiento='".$_POST['tpc_valordomicilio_establecimiento']."', tpc_estrato_establecimiento='".$_POST['tpc_estrato_establecimiento']."' WHERE tpc_codigo_establecimiento='".$_POST['tpc_codigo_establecimiento']."';");
																if($_POST['tpc_direccion_establecimiento'] != $tp_establecimientos['tpc_direccion_establecimiento'] || $_POST['tpc_ciudad_establecimiento'] != $tp_establecimientos['tpc_ciudad_establecimiento'] || $_POST['tpc_pais_establecimiento'] != $tp_establecimientos['tpc_pais_establecimiento']){
																	alatitudlongitud($_POST['tpc_direccion_establecimiento'], $_POST['tpc_ciudad_establecimiento'], $_POST['tpc_pais_establecimiento'], $_POST["tpc_codigo_establecimiento"], 0);
																}
																$con->query("SELECT * FROM tp_establecimientos_propietario WHERE tpc_idestablecimiento_establecimientos_propietario = '".$_POST['tpc_codigo_establecimiento']."';");
																if($con->num_rows() == 0){
																	$con1->query("INSERT INTO tp_establecimientos_propietario VALUES (NULL, '".$_POST["tpc_nombres_establecimientos_propietario"]."', '".$_POST["tpc_apellidos_establecimientos_propietario"]."', '".$_POST["tpc_tipodocumento_establecimientos_propietario"]."', '".$_POST["tpc_documento_establecimientos_propietario"]."', '".$_POST["tpc_fechanacimiento_establecimientos_propietario"]."', '".$_POST["tpc_genero_establecimientos_propietario"]."', '".$_POST["tpc_celular_establecimientos_propietario"]."', '".$_POST['tpc_codigo_establecimiento']."');");
																}else{
																	$con1->query("UPDATE tp_establecimientos_propietario SET tpc_nombres_establecimientos_propietario='".$_POST["tpc_nombres_establecimientos_propietario"]."', tpc_apellidos_establecimientos_propietario='".$_POST["tpc_apellidos_establecimientos_propietario"]."', tpc_tipodocumento_establecimientos_propietario='".$_POST["tpc_tipodocumento_establecimientos_propietario"]."', tpc_documento_establecimientos_propietario='".$_POST["tpc_documento_establecimientos_propietario"]."', tpc_fechanacimiento_establecimientos_propietario='".$_POST["tpc_fechanacimiento_establecimientos_propietario"]."', tpc_genero_establecimientos_propietario='".$_POST["tpc_genero_establecimientos_propietario"]."', tpc_celular_establecimientos_propietario='".$_POST["tpc_celular_establecimientos_propietario"]."' WHERE tpc_idestablecimiento_establecimientos_propietario='".$_POST['tpc_codigo_establecimiento']."');");
																}
																$mensaje="Establecimiento Modificado Correctamente";
															}else{
																$mensaje="Error: Ya existe un establecimiento con la misma dirección y ciudad";
															}
														break;
														case 'importar':
															$tamano = $_FILES['archivo']['size'];
															$tipo = $_FILES['archivo']['type'];
															$archivo = $_FILES['archivo']['name'];
															$error = $_FILES['archivo']['error'];
															if($archivo != "" && $tipo === "application/vnd.ms-excel"){
																$guardados = 0;$erroneos = 0;
																$lineas = file($_FILES['archivo']['tmp_name']);
																$i = 1;
																$datos=explode(";",$lineas[0]);
																if(count($datos) >= 23){
																	while($lineas[$i]){
																		$datos=explode(";",$lineas[$i]);
																		$direccion = str_replace('"', '', $datos[8]);
																		$con->query("SELECT * FROM tp_establecimientos WHERE tpc_direccion_establecimiento='".$direccion."' AND tpc_ciudad_establecimiento='".$datos[11]."'");
																		if($con->num_rows() == 0){
																			//if($usuario['tpc_rol_usuario'] == 2){
																				$hacedomicilios = $datos[23];
																				$valordomicilios = $datos[24];
																				$con->query("SELECT * FROM tp_usuarios WHERE tpc_nickname_usuario='".$datos[5]."';");
																				if($con->num_rows() > 0){
																					$con->next_record();
																					$asignadoa = $con->f("tpc_codigo_usuario");
																				}
																			//}else{
																				$asignadoa = $mkid;
																				$hacedomicilios = $datos[22];
																				$valordomicilios = $datos[23];
																			//}
																			$ins_persona = "INSERT INTO tp_establecimientos VALUES (NULL, '".$datos[0]."', '".$datos[1]."', '".$datos[2]."', '".$datos[3]."', '".$datos[4]."', '".$asignadoa."', '".$datos[6]."', '".$datos[7]."', '".$direccion."', '".$datos[9]."', '".$datos[10]."', '".$datos[11]."', '".$datos[12]."', '".$datos[13]."', '".$datos[14]."', '', '".$datos[15]."', '".$datos[16]."', '".$datos[17]."', '".$datos[18]."', '".$datos[19]."', '".$datos[20]."', '".$datos[21]."', '".$datos[22]."', 'IMPORT', '".date("Y-m-d H:i:s")."', '".$hacedomicilios."', '".$valordomicilios."');";
																			$con->query($ins_persona);
																			//SACAR LATITUD Y LONGITUD
																			$con->query("SELECT * FROM tp_establecimientos WHERE tpc_nombre_establecimiento='".$datos[0]."' AND tpc_categoria_establecimiento='".$datos[1]."' AND tpc_direccion_establecimiento='".$direccion."' AND tpc_ciudad_establecimiento='".$datos[11]."' AND tpc_pais_establecimiento='".$datos[9]."';");
																			$con->next_record();
																			alatitudlongitud($direccion, $datos[11], $datos[9], $con->f("tpc_codigo_establecimiento"), 0);
																			//************************
																			$guardados++;
																		}else{
																			$erroneos++;
																		}
																		$i++;
																	}
																	$mensaje = 'Registros Guardados: ' . $guardados . ', Registros Erroneos: '.$erroneos;
																}else{
																	$mensaje = 'Verifica las columnas del archivo';
																}
															}else{
																$mensaje = 'Verifica que el archivo sea CSV';
															}
															exit();
														break;
														//SI RECIBIMOS MASIVAMENTE EN OPC LA MANDO POST YA QUE POR GET ES MAS LIMITADO
														case 'masivamente':
															switch($_POST['finalmente']){
																case 'editar':
																	echo '<div class="basic-login-inner">
																		<h3>Edición Masiva Establecimientos</h3>
																		<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																			<table width="100%">
																				<tr>
																					<td align="center" colspan="4"><h3>INFORMACIÓN DEL PROPIETARIO</h3></td>
																				</tr>
																				<tr><td><br></td></tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Nombres</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_nombres_establecimientos_propietario" id="tpc_nombres_establecimientos_propietario" placeholder="Nombres..." class="form-control" required="required">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Apellidos</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_apellidos_establecimientos_propietario" id="tpc_apellidos_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="Apellidos..." required="required">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Tipo documento</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_tipodocumento_establecimientos_propietario" id="tpc_tipodocumento_establecimientos_propietario" class="form-control" required="required">
																						   <option value="">Seleccione....</option>
																						   <option value="Cedula">Cedula</option>
																						   <option value="Cedula de extranjeria">Cedula de extranjeria</option>
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2"># de documento</label>
																					</td>
																					<td width="30%">
																						<input type="number" name="tpc_documento_establecimientos_propietario" id="tpc_documento_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="# Documento..." required="required">
																					</td>
																				</tr>
																				<tr>
																					<td align="center" colspan="4">
																						<label class="login2">Fecha nacimiento</label>
																						<input type="date" style="width: 250px;" name="tpc_fechanacimiento_establecimientos_propietario" id="tpc_fechanacimiento_establecimientos_propietario" value="" class="form-control" style="width: 100%;" required="required">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Genero</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_genero_establecimientos_propietario" id="tpc_genero_establecimientos_propietario" class="form-control" required="required">
																						   <option value="">Seleccione....</option>
																						   <option value="Masculino">Masculino</option>
																						   <option value="Femenino">Femenino</option>
																						   <option value="Otro">Otro</option>
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Celular</label>
																					</td>
																					<td width="30%">
																						<input type="number" name="tpc_celular_establecimientos_propietario" id="tpc_celular_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="Celular..." required="required">
																					</td>
																				</tr>
																				<tr><td><br></td></tr>
																				<tr>
																					<td align="center" colspan="4"><h3>INFORMACIÓN DEL ESTABLECIMIENTO</h3></td>
																				</tr>
																				<tr><td><br></td></tr>
																			
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Imagen</label>
																					</td>
																					<td width="30%">
																						<input type="file" name="tpc_imagen_establecimiento" id="tpc_imagen_establecimiento" class="form-control">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Nombre</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_nombre_establecimiento" id="tpc_nombre_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Nombre...">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Categoria</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_categoria_establecimiento" id="tpc_categoria_establecimiento" class="form-control">
																							<option value="">Seleccione.....</option>
																							<!--<option value="APP ALIADA">APP ALIADA</option>--> <!--<option value="ALIADOS ESTRATEGICOS">ALIADOS ESTRATEGICOS</option>-->
																						   <!--<option value="ANIMALES Y AGRICULTURA">ANIMALES Y AGRICULTURA</option>-->
																						   <!--<option value="AUTOPARTES">AUTOPARTES</option>-->
																						   <!--<option value="BELLEZA Y ESTETICA">BELLEZA Y ESTETICA</option>-->
																						   <!--<option value="CENTROS COMERCIALES">CENTROS COMERCIALES</option>-->
																						   <!--<option value="CORRESPONSALES BANCARIOS">CORRESPONSALES BANCARIOS</option>-->
																						   <!--<option value="DIVERSION Y AVENTURA">DIVERSION Y AVENTURA</option>-->
																						   <!--<option value="EDUCACION">EDUCACION</option>-->
																						   <option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
																						   <option value="ESTACIONES DE SERVICIO">ESTACIONES DE SERVICIO</option>
																						   <option value="HOGAR Y MISCELANEAS">HOGAR Y MISCELANEAS</option>
																						   <!--<option value="ELEMENTOS DEPORTIVOS">ELEMENTOS DEPORTIVOS</option>-->
																						   <!--<option value="EMPLEO">EMPLEO</option>-->
																						   <!--<option value="ESTABLECIMIENTOS DE BARRIO">ESTABLECIMIENTOS DE BARRIO</option>-->
																						   <option value="FARMACIAS">FARMACIAS</option>
																						   <!--<option value="FUNDACION ALIADA">FUNDACION ALIADA</option>-->
																						   <option value="GASTRONOMIA">GASTRONOMIA</option>
																						   <!--<option value="GIROS Y ENTREGAS">GIROS Y ENTREGAS</option>-->
																						   <!--<option value="OTROS">OTROS</option>-->
																						   <option value="ROPA Y ACCESORIOS">ROPA Y ACCESORIOS</option>
																						   <option value="SALUD Y BELLEZA">SALUD Y BELLEZA</option>
																						   <option value="SUPERMERCADOS Y TIENDAS">SUPERMERCADOS Y TIENDAS</option>
																						   <!--<option value="TECNOLOGIA">TECNOLOGIA</option>-->
																						   <!--<option value="TURISMO">TURISMO</option>-->
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Telefono Particular</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_telparticular_establecimiento" id="tpc_telparticular_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Telefono Particular...">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Telefono Móvil</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_movil_establecimiento" id="tpc_movil_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Telefono Móvil...">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Email</label>
																					</td>
																					<td width="30%">
																						<input type="email" name="tpc_email_establecimiento" id="tpc_email_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Email...">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Asignado a</label>
																					</td>
																					<td width="30%">';
																					if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
																						echo '<select name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" class="form-control">
																								<option value="">Seleccione.....</option>';
																								$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																								while($con->next_record()){
																									echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																								}
																						echo '</select>';
																					}else{
																						echo '<input type="hidden" name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																					}
																					echo '</td>
																					<td width="20%" align="center">
																						<label class="login2">Tipo de Identificación</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_tipodocumento_establecimiento" id="tpc_tipodocumento_establecimiento" class="form-control">
																							<option value="">Seleccione.....</option>
																							<option value="NIT">NIT</option>
																							<option value="CC">Cedula de Ciudadania</option>
																							<option value="CE">Cedula de Extrajeria</option>
																						</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Identificación</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_numdocumento_establecimiento" id="tpc_numdocumento_establecimiento" class="form-control" style="width: 100%;" placeholder="Identificación...">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Pais</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_pais_establecimiento" id="tpc_pais_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione....</option>
																							<option value="Argentina">Argentina</option>
																							<option value="Brasil">Brasil</option>
																							<option value="Canada">Canada</option>
																							<option value="Chile">Chile</option>
																							<option value="Colombia">Colombia</option>
																							<option value="Costa Rica">Costa Rica</option>
																							<option value="Cuba">Cuba</option>
																							<option value="Ecuador">Ecuador</option>
																							<option value="Estados Unidos">Estados Unidos</option>
																							<option value="Guatemala">Guatemala</option>
																							<option value="Guyana Francesa">Guyana Francesa</option>
																							<option value="Honduras">Honduras</option>
																							<option value="Mexico">Mexico</option>
																							<option value="Panama">Panama</option>
																							<option value="Parauay">Parauay</option>
																							<option value="San Salvador">San Salvador</option>
																							<option value="Uruguay">Uruguay</option>
																							<option value="Venezuela">Venezuela</option>
																						</select>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4"><center>
																						<label><b>Dirección</b></label><br>
																						<select name="param1direccion" id="param1direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																							<option value="">Seleccione....</option>
																							<option value="Transversal">Transversal</option>
																							<option value="Diagonal">Diagonal</option>
																							<option value="Avenida Calle">Avenida Calle</option>
																							<option value="Avenida Carrera">Avenida Carrera</option>
																							<option value="Calle">Calle</option>
																							<option value="Carrera">Carrera</option>
																						</select>
																						<input type="number" name="param2direccion" class="form-control-sm" style="width: 70px;" id="param2direccion" onkeyup="armasdir_conjunto()">
																						<select name="param3direccion" id="param3direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																							<option value=""></option>';
																							$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																							for($i = 0; $i < count($letras); $i++){
																								echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																							}
																					echo '    </select>
																						<input type="checkbox" name="param4direccion" id="param4direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param4direccion">Bis</label> &nbsp;&nbsp;
																						<select name="param5direccion" id="param5direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																							<option value=""></option>
																							<option value="Norte">Norte</option>
																							<option value="Sur">Sur</option>
																							<option value="Este">Este</option>
																							<option value="Oeste">Oeste</option>
																						</select> # 
																						<input type="number" name="param6direccion" class="form-control-sm" style="width: 70px;" id="param6direccion" onkeyup="armasdir_conjunto()">
																						<select name="param7direccion" id="param7direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																							<option value=""></option>';
																							$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																							for($i = 0; $i < count($letras); $i++){
																								echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																							}
																					echo '    </select>
																						<input type="checkbox" name="param8direccion" id="param8direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param8direccion">Bis</label> &nbsp;&nbsp;
																						<input type="number" name="param9direccion" class="form-control-sm" style="width: 70px;" id="param9direccion" onkeyup="armasdir_conjunto()">
																						<select name="param10direccion" id="param10direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																							<option value=""></option>
																							<option value="Norte">Norte</option>
																							<option value="Sur">Sur</option>
																							<option value="Este">Este</option>
																							<option value="Oeste">Oeste</option>
																						</select>
																						<br><br>
																						<input type="text" name="tpc_direccion_establecimiento" class="form-control-sm" id="tpc_direccion_establecimiento" placeholder="Dirección...." style="width:70%;" readonly="readonly">
																					</center></td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Departamento</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione....</option>
																							<option value="Amazonas">Amazonas</option>
																							<option value="Antioquia">Antioquia</option>
																							<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																							<option value="Atlantico">Atlantico</option>
																							<option value="Bogota D.C">Bogota D.C</option>
																							<option value="Bolivar">Bolivar</option>
																							<option value="Boyaca">Boyaca</option>
																							<option value="Caldas">Caldas</option>
																							<option value="Caqueta">Caqueta</option>
																							<option value="Casanare">Casanare</option>
																							<option value="Cauca">Cauca</option>
																							<option value="Cesar">Cesar</option>
																							<option value="Choco">Choco</option>
																							<option value="Cordoba">Cordoba</option>
																							<option value="Curdimanarca">Curdimanarca</option>
																							<option value="Guainia">Guainia</option>
																							<option value="Guaviare">Guaviare</option>
																							<option value="Huila">Huila</option>
																							<option value="La Guajira">La Guajira</option>
																							<option value="Magdalena">Magdalena</option>
																							<option value="Meta">Meta</option>
																							<option value="Nariño">Nariño</option>
																							<option value="Norte De Santander">Norte De Santander</option>
																							<option value="Putumayo">Putumayo</option>
																							<option value="Quindio">Quindio</option>
																							<option value="Risaralda">Risaralda</option>
																							<option value="Santander">Santander</option>
																							<option value="Sucre">Sucre</option>
																							<option value="Tolima">Tolima</option>
																							<option value="Valle Del Cauca">Valle Del Cauca</option>
																							<option value="Vaupes">Vaupes</option>
																							<option value="Vichada">Vichada</option>
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Ciudad</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione....</option><option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																						</select>
																					</td>
																				</tr>
																				<tr style="height:80px;">
																					<td width="20%" align="center" colspan="4">
																						<label class="login2">Localidad</label><br>
																						<select name="tpc_localidad_establecimiento" id="tpc_localidad_establecimiento" class="form-control" style="width:140px;">
																							<option value="" selected="selected">Seleccione....</option><option value="Antonio Narino">ANTONIO NARIÑO</option><option value="Barrios Unidos">BARRIOS UNIDOS</option><option value="Bosa">BOSA</option><option value="Candelaria">CANDELARIA</option><option value="Chapinero">CHAPINERO</option><option value="Ciudad Bolivar">CIUDAD BOLIVAR</option><option value="Engativa">ENGATIVA</option><option value="Fontibon">FONTIBON</option><option value="Kennedy">KENNEDY</option><option value="Candelaria">LA CANDELARIA</option><option value="Martires">LOS MARTIRES</option><option value="Otra">OTRA</option><option value="Puente Aranda">PUENTE ARANDA</option><option value="Rafael Uribe Uribe">RAFAEL URIBE URIBE</option><option value="San Cristobal">SAN CRISTOBAL</option><option value="Santa Fe">SANTA FE</option><option value="Suba">SUBA</option><option value="Sumapaz">SUMAPAZ</option><option value="Teusaquillo">TEUSAQUILLO</option><option value="Tunjuelito">TUNJUELITO</option><option value="Usaquen">USAQUEN</option><option value="Usme">USME</option>
																						</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Video Inversionista</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_videoinversionista_establecimiento" id="tpc_videoinversionista_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Video Inversionista...">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Video Fundación</label>
																					</td>';
																					if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<td width="30%">
																							<input type="text" name="tpc_videofundacion_establecimiento" id="tpc_videofundacion_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Video Fundación...">
																						</td>';
																					}else{
																						echo '<td width="30%">
																							<input type="text" name="tpc_videofundacion_establecimiento" id="tpc_videofundacion_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Video Fundación..." disabled="disabled">
																						</td>';
																					}
																					
																				echo '</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Apertura Dia Hábil</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_aperturahabil_establecimiento" id="tpc_aperturahabil_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione...</option>';
																							for($i = 0; $i <= 23; $i++){
																								$val = $i;
																								if($i < 10){$val = '0'.$i;}
																								echo '<option value="'.$val.':00">'.$val.':00</option>';
																							}
																					echo '</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Cierre Dia Hábil</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_cierrehabil_establecimiento" id="tpc_cierrehabil_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione...</option>';
																							for($i = 0; $i <= 23; $i++){
																								$val = $i;
																								if($i < 10){$val = '0'.$i;}
																								echo '<option value="'.$val.':00">'.$val.':00</option>';
																							}
																					echo '</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Apertura Fin de Semana</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_aperturafinsemana_establecimiento" id="tpc_aperturafinsemana_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione...</option>';
																							for($i = 0; $i <= 23; $i++){
																								$val = $i;
																								if($i < 10){$val = '0'.$i;}
																								echo '<option value="'.$val.':00">'.$val.':00</option>';
																							}
																					echo '</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Cierre Fin de Semana</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_cierrefinsemana_establecimiento" id="tpc_cierrefinsemana_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione...</option>';
																							for($i = 0; $i <= 23; $i++){
																								$val = $i;
																								if($i < 10){$val = '0'.$i;}
																								echo '<option value="'.$val.':00">'.$val.':00</option>';
																							}
																					echo '</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Sitio Web</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_sitioweb_establecimiento" id="tpc_sitioweb_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Sitio Web....">
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Facebook</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_facebook_establecimiento" id="tpc_facebook_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Facebook...">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">¿Hace Domicilios?</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_hacedomicilios_establecimiento" id="tpc_hacedomicilios_establecimiento" class="form-control">
																							<option value="" selected="selected">Seleccione...</option>
																							<option value="Si">Si</option>
																							<option value="No">No</option>
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Valor Domicilio</label>
																					</td>
																					<td width="30%">
																						<input type="number" name="tpc_valordomicilio_establecimiento" id="tpc_valordomicilio_establecimiento" min="0" class="form-control" style="width: 100%;" placeholder="Valor Domicilio...">
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td colspan="4" align="center">
																						<label class="login2">Estrato: </label>
																						<select id="tpc_estrato_establecimiento" style="width: 150px;" class="form-control" name="tpc_estrato_establecimiento" required="required">
																							<option value="">Seleccione....</option>
																							<option value="1">1</option>
																							<option value="2">2</option>
																							<option value="3">3</option>
																							<option value="4">4</option>
																							<option value="5">5</option>
																							<option value="6">6</option>
																						 </select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td colspan="4" align="center">';
																					$cadena = '';
																					for($i=1;$i<=$_POST['contador'];$i++){
																						if(isset($_POST['check'.$i])){
																							$cadena .= $_POST['check'.$i].';';
																						}
																					}
																					echo '<input type="hidden" name="cadena" id="cadena" value="'.$cadena.'">
																						<input type="hidden" name="opc" id="opc" value="edicionmasivafinal">
																						<input type="hidden" name="tipo" id="tipo" value="contacto">
																						<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Edición Masiva Establecimientos</button>
																					</td>
																				</tr>
																			</table>
																		</form>
																	</div>';
																break;
																case 'eliminar':
																	$cuenta_eliminados = 0;
																	for($i=1;$i<=$_POST['contador'];$i++){
																		if(isset($_POST['check'.$i])){
																			$con->query("DELETE FROM tp_establecimiento_banner WHERE tpc_establecimiento_establecimiento_banner='".$_POST['check'.$i]."';");
																			$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_establecimientos_docuestab='".$_POST['check'.$i]."';");
																			$con->query("DELETE FROM tp_establecimientos WHERE tpc_codigo_establecimiento='".$_POST['check'.$i]."';");
																			$cuenta_eliminados++;
																		}
																	}
																	$mensaje = $cuenta_eliminados.' Establecimientos Eliminados';
																	echo '
																	<form name="finality" method="get" action="filtros.php">
																		<input type="hidden" name="mensaje" value="'.$mensaje.'">
																		<input type="hidden" name="opcion" value="contactos">
																	</form>
																	<script type="text/javascript">
																		alert("'.$mensaje.'");
																		finality.submit();
																	</script>';
																	exit();
																break;
																case 'promocion':
																	echo '<div class="basic-login-inner">
																		<h3>Edición Masiva Establecimientos</h3>
																		<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																			<table width="100%">
																				<tr style="height:60px;">
																					<td width="20%" align="center">
																						<label class="login2">Promoción: </label>
																					</td>
																					<td width="30%">
																						<select name="tp_documento" id="tp_documento" class="form-control" required="required">
																							<option value="" selected="selected">Seleccione....</option>';
																							if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN SE MUESTRAN TODAS LAS PROMOCIONES, SINO SOLO SE MUESTRAN LOS ASIGNADOS AL USUARIO ACTUAL
																								$con->query("SELECT * FROM tp_documentos INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_asignadoa_documento AND tpc_estado_documento='1' ORDER BY tpc_codigo_usuario;");
																							}else{
																								$con->query("SELECT * FROM tp_documentos INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_asignadoa_documento AND tpc_asignadoa_documento='".$mkid."' AND tpc_estado_documento='1' ORDER BY tpc_codigo_usuario;");
																							}
																							while($con->next_record()){
																								echo '<option value="'.$con->f("tpc_id_documento").'">'.$con->f("tpc_nombre_documento").'</option>';
																							}
																					echo '</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td colspan="2" align="center">';
																					$cadena = '';
																					for($i=1;$i<=$_POST['contador'];$i++){
																						if(isset($_POST['check'.$i])){
																							$cadena .= $_POST['check'.$i].';';
																						}
																					}
																					//echo 'DATO: '.$_POST['contador'];
																					echo '<input type="hidden" name="cadena" id="cadena" value="'.$cadena.'">
																						<input type="hidden" name="opc" id="opc" value="documentomasivofinal">
																						<input type="hidden" name="tipo" id="tipo" value="contacto">
																						<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Asignar Documento</button>
																					</td>
																				</tr>
																			</table>
																		</form>
																	</div>';
																break;
															}
														break;
														case 'edicionmasivafinal':
															//COMPARAR QUE CAMPOS SE HAN LLENADO PARA ACTUALIZAR CADA REGISTRO
															if(isset($_FILES['tpc_imagen_establecimiento']) && $_FILES['tpc_imagen_establecimiento']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/logo/' . $prefijo . '_' . $_FILES['tpc_imagen_establecimiento']['name'];
																if(!move_uploaded_file($_FILES['tpc_imagen_establecimiento']['tmp_name'], $fichero_subido)) {
																	$fichero_subido = '';
																}
															}else{
																$fichero_subido = '';
															}
															//       
															//****************************************************************
															$arrCadena = explode(";", $_POST['cadena']);
															for($i = 0;$i < count($arrCadena); $i++){
																if($arrCadena[$i] != ''){
																	if($fichero_subido != ''){
																		//$actestablecimiento = reg('tp_establecimientos', 'tpc_codigo_establecimiento', $arrCadena[$i]);
																		$con->query("UPDATE tp_establecimientos SET tpc_imagen_establecimiento='".$fichero_subido."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");
																	}
																	if($_POST['tpc_nombre_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_nombre_establecimiento='".$_POST['tpc_nombre_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_categoria_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_categoria_establecimiento='".$_POST['tpc_categoria_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_telparticular_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_telparticular_establecimiento='".$_POST['tpc_telparticular_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_movil_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_movil_establecimiento='".$_POST['tpc_movil_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_email_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_email_establecimiento='".$_POST['tpc_email_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_asignadoa_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_asignadoa_establecimiento='".$_POST['tpc_asignadoa_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_tipodocumento_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_tipodocumento_establecimiento='".$_POST['tpc_tipodocumento_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_numdocumento_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_numdocumento_establecimiento='".$_POST['tpc_numdocumento_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_pais_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_pais_establecimiento='".$_POST['tpc_pais_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	//if($_POST['tpc_direccion_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_direccion_establecimiento='".$_POST['tpc_direccion_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	//if($_POST['tpc_departamento_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	//if($_POST['tpc_ciudad_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	//if($_POST['tpc_localidad_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_localidad_establecimiento='".$_POST['tpc_localidad_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_videoinversionista_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_videoinversionista_establecimiento='".$_POST['tpc_videoinversionista_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_videofundacion_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_videofundacion_establecimiento='".$_POST['tpc_videofundacion_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_aperturahabil_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_aperturahabil_establecimiento='".$_POST['tpc_aperturahabil_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_cierrehabil_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_cierrehabil_establecimiento='".$_POST['tpc_cierrehabil_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_aperturafinsemana_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_aperturafinsemana_establecimiento='".$_POST['tpc_aperturafinsemana_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_cierrefinsemana_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_cierrefinsemana_establecimiento='".$_POST['tpc_cierrefinsemana_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_sitioweb_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_sitioweb_establecimiento='".$_POST['tpc_sitioweb_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_facebook_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_facebook_establecimiento='".$_POST['tpc_facebook_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	
																	if($_POST['tpc_hacedomicilios_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_hacedomicilios_establecimiento='".$_POST['tpc_hacedomicilios_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	if($_POST['tpc_valordomicilio_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_valordomicilio_establecimiento='".$_POST['tpc_valordomicilio_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	
																	if($_POST['tpc_estrato_establecimiento'] != ''){$con->query("UPDATE tp_establecimientos SET tpc_estrato_establecimiento='".$_POST['tpc_estrato_establecimiento']."' WHERE tpc_codigo_establecimiento='".$arrCadena[$i]."';");}
																	
																	$con->query("SELECT * FROM tp_establecimientos_propietario WHERE tpc_idestablecimiento_establecimientos_propietario = '".$arrCadena[$i]."';");
																	if($con->num_rows() == 0){
																		$con1->query("INSERT INTO tp_establecimientos_propietario VALUES (NULL, '".$_POST["tpc_nombres_establecimientos_propietario"]."', '".$_POST["tpc_apellidos_establecimientos_propietario"]."', '".$_POST["tpc_tipodocumento_establecimientos_propietario"]."', '".$_POST["tpc_documento_establecimientos_propietario"]."', '".$_POST["tpc_fechanacimiento_establecimientos_propietario"]."', '".$_POST["tpc_genero_establecimientos_propietario"]."', '".$_POST["tpc_celular_establecimientos_propietario"]."', '".$arrCadena[$i]."');");
																	}else{
																		$con1->query("UPDATE tp_establecimientos_propietario SET tpc_nombres_establecimientos_propietario='".$_POST["tpc_nombres_establecimientos_propietario"]."', tpc_apellidos_establecimientos_propietario='".$_POST["tpc_apellidos_establecimientos_propietario"]."', tpc_tipodocumento_establecimientos_propietario='".$_POST["tpc_tipodocumento_establecimientos_propietario"]."', tpc_documento_establecimientos_propietario='".$_POST["tpc_documento_establecimientos_propietario"]."', tpc_fechanacimiento_establecimientos_propietario='".$_POST["tpc_fechanacimiento_establecimientos_propietario"]."', tpc_genero_establecimientos_propietario='".$_POST["tpc_genero_establecimientos_propietario"]."', tpc_celular_establecimientos_propietario='".$_POST["tpc_celular_establecimientos_propietario"]."' WHERE tpc_idestablecimiento_establecimientos_propietario='".$arrCadena[$i]."');");
																	}
																}
															}
															$mensaje = 'Edición Masiva Ejecutada Correctamente';
														break;
														case 'documentomasivofinal':
															$arrCadena = explode(";", $_POST['cadena']);
															for($i = 0;$i < count($arrCadena); $i++){
																if($arrCadena[$i] != ''){
																	$con->query("SELECT * FROM tp_documentos_establecimientos WHERE tpc_establecimientos_docuestab='".$arrCadena[$i]."' AND tpc_documento_docuestab='".$_POST['tp_documento']."';");
																	if($con->num_rows() == 0){
																		$con->query("INSERT INTO tp_documentos_establecimientos VALUES (NULL, '".$arrCadena[$i]."', '".$_POST['tp_documento']."');");
																	}
																}
															}
															$mensaje = 'Documento Asignado Correctamente';
														break;
														//*****************************************************************************
													}
													if($_POST['opc'] != 'masivamente'){
														echo '
														<form name="finality" method="get" action="index.php">
															<input type="hidden" name="mensaje" value="'.$mensaje.'">
															<input type="hidden" name="opcion" value="contactos">
														</form>
														<script type="text/javascript">
															alert("'.$mensaje.'");
															finality.submit();
														</script>';
														exit();
													}
												}
												if($_POST['tipo'] == 'promocion'){
													if($usuario['tpc_rol_usuario'] == 0){
														header('Location: index.php');
														exit();
													}
													switch($_POST['opc']){
														case 'nuevo':
															switch(intval($usuario['rol'])){
																case -4: case -3://ULTRA SON 10
																	$con->query("SELECT * FROM tp_documentos WHERE tpc_asignadoa_documento='".$mkid."';");
																	if($con->num_rows() >= 10){
																		echo '
																		<form name="finality" method="post" action="promociones.php">
																			<input type="hidden" name="mensaje" value="No puedes tener más de 10 promociones">
																		</form>
																		<script type="text/javascript">
																			alert("No puedes tener más de 10 promociones");
																			finality.submit();
																		</script>';
																		exit();
																	}
																break;
																case -2: case 1://PREMIUM SON 20
																	$con->query("SELECT * FROM tp_documentos WHERE tpc_asignadoa_documento='".$mkid."';");
																	if($con->num_rows() >= 20){
																		echo '
																		<form name="finality" method="post" action="promociones.php">
																			<input type="hidden" name="mensaje" value="No puedes tener más de 20 promociones">
																		</form>
																		<script type="text/javascript">
																			alert("No puedes tener más de 20 promociones");
																			finality.submit();
																		</script>';
																		exit();
																	}
																break;
															}
															if(isset($_FILES['tpc_archivo_documento']) && $_FILES['tpc_archivo_documento']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_documento']['name'];
																if(!move_uploaded_file($_FILES['tpc_archivo_documento']['tmp_name'], $fichero_subido)) {
																	$mensaje = "Error al subir archivo";
																}else{
																	chmod($fichero_subido, 0777);
																	$con->query("INSERT INTO tp_documentos VALUES (NULL, '".$_POST['tpc_nombre_documento']."', '".date("Y-m-d", strtotime($_POST['tpc_validodesde_documento']))." 00:00:00', '".date("Y-m-d", strtotime($_POST['tpc_validohasta_documento']))." 23:59:59', '".$fichero_subido."', '".$_POST['tpc_asignadoa_documento']."', '1', '".$_POST['tpc_descripcion_documento']."', '".$_POST['tpc_valor_documento']."', '".$_POST['tpc_porcentajedesc_documento']."', '".$_POST['tpc_codigo_documento']."', '".$_POST['tpc_ciudad_documento']."', '".$_POST['tpc_departamento_documento']."');");
																	$mensaje="Documento Creado Correctamente";
																}
															}else{
																$mensaje = "Error al subir archivo";
															}
														break;
														case 'editar':
															$tp_documentos = reg('tp_documentos', 'tpc_id_documento', $_POST['tpc_id_documento']);
															$con->query("UPDATE tp_documentos SET tpc_nombre_documento='".$_POST['tpc_nombre_documento']."', tpc_validodesde_documento='".date("Y-m-d", strtotime($_POST['tpc_validodesde_documento']))." 00:00:00', tpc_validohasta_documento='".date("Y-m-d", strtotime($_POST['tpc_validohasta_documento']))." 23:59:59', tpc_asignadoa_documento='".$_POST['tpc_asignadoa_documento']."', tpc_descripcion_documento='".$_POST['tpc_descripcion_documento']."', tpc_valor_documento='".$_POST['tpc_valor_documento']."', tpc_porcentajedesc_documento='".$_POST['tpc_porcentajedesc_documento']."', tpc_codigo_documento='".$_POST['tpc_codigo_documento']."', tpc_ciudad_documento='".$_POST['tpc_ciudad_documento']."', tpc_departamento_documento='".$_POST['tpc_departamento_documento']."' WHERE tpc_id_documento='".$_POST['tpc_id_documento']."'");
															if(isset($_FILES['tpc_archivo_documento']) && $_FILES['tpc_archivo_documento']['name'] != ''){
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_documento']['name'];
																if(move_uploaded_file($_FILES['tpc_archivo_documento']['tmp_name'], $fichero_subido)) {
																	unlink($tp_documentos['tpc_archivo_documento']);
																	$con->query("UPDATE tp_documentos SET tpc_archivo_documento='".$fichero_subido."' WHERE tpc_id_documento='".$_POST['tpc_id_documento']."';");
																	chmod($fichero_subido, 0777);
																}
															}
															$mensaje = "Documento editado correctamente";
														break;
														case 'eliminar':
															$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_documento_docuestab='".$_POST['tpc_id_documento']."';");
															$con->query("DELETE FROM tp_documentos_calificacion WHERE tpc_documento_calificacion='".$_POST['tpc_id_documento']."';");
															$con->query("DELETE FROM tp_documentos WHERE tpc_id_documento='".$_POST['tpc_id_documento']."';");
															$mensaje = "Documento eliminado correctamente";
														break;
													}
													echo '
													<form name="finality" method="post" action="promociones.php">
														<input type="hidden" name="mensaje" value="'.$mensaje.'">
													</form>
													<script type="text/javascript">
														alert("'.$mensaje.'");
														finality.submit();
													</script>';
													exit();
												}
												if($_POST['tipo'] == 'promocionaliado'){
													if($usuario['tpc_rol_usuario'] != -2 && $usuario['tpc_rol_usuario'] != -1 && $usuario['tpc_rol_usuario'] != 2 && $usuario['tpc_rol_usuario'] != -3){
														header('Location: index.php');
														exit();
													}
													switch($_POST['opc']){
														case 'nuevo':
															if(isset($_FILES['tpc_archivo_promociones_aliados']) && $_FILES['tpc_archivo_promociones_aliados']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_promociones_aliados']['name'];
																if(!move_uploaded_file($_FILES['tpc_archivo_promociones_aliados']['tmp_name'], $fichero_subido)) {
																	$mensaje = "Error al subir archivo";
																}else{
																	chmod($fichero_subido, 0777);
																	$con->query("INSERT INTO tp_promociones_aliados VALUES (NULL, '".$_POST['tpc_usuario_promociones_aliados']."', '".$_POST['tpc_nombre_promociones_aliados']."', '".date("Y-m-d", strtotime($_POST['tpc_validodesde_promociones_aliados']))." 00:00:00', '".date("Y-m-d", strtotime($_POST['tpc_validohasta_promociones_aliados']))." 23:59:59', '".$fichero_subido."', '1', '".$_POST['tpc_descripcion_promociones_aliados']."', '".$_POST['tpc_url_promociones_aliados']."');");
																	$mensaje="Promoción Creado Correctamente";
																}
															}else{
																$mensaje = "Error al subir archivo";
															}
														break;
														case 'editar':
															$tp_promociones_aliados = reg('tp_promociones_aliados', 'tpc_codigo_promociones_aliados', $_POST['tpc_codigo_promociones_aliados']);
															$con->query("UPDATE tp_promociones_aliados SET tpc_nombre_promociones_aliados='".$_POST['tpc_nombre_promociones_aliados']."', tpc_validodesde_promociones_aliados='".date("Y-m-d", strtotime($_POST['tpc_validodesde_promociones_aliados']))." 00:00:00', tpc_validohasta_promociones_aliados='".date("Y-m-d", strtotime($_POST['tpc_validohasta_promociones_aliados']))." 23:59:59', tpc_usuario_promociones_aliados='".$_POST['tpc_usuario_promociones_aliados']."', tpc_descripcion_promociones_aliados='".$_POST['tpc_descripcion_promociones_aliados']."', tpc_url_promociones_aliados='".$_POST['tpc_url_promociones_aliados']."' WHERE tpc_codigo_promociones_aliados='".$_POST['tpc_codigo_promociones_aliados']."'");
															if(isset($_FILES['tpc_archivo_promociones_aliados']) && $_FILES['tpc_archivo_promociones_aliados']['name'] != ''){
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_promociones_aliados']['name'];
																if(move_uploaded_file($_FILES['tpc_archivo_promociones_aliados']['tmp_name'], $fichero_subido)) {
																	unlink($tp_promociones_aliados['tpc_archivo_promociones_aliados']);
																	$con->query("UPDATE tp_promociones_aliados SET tpc_archivo_promociones_aliados='".$fichero_subido."' WHERE tpc_codigo_promociones_aliados='".$_POST['tpc_codigo_promociones_aliados']."';");
																	chmod($fichero_subido, 0777);
																}
															}
															$mensaje = "Promoción editada correctamente";
														break;
														case 'eliminar':
															$tp_promociones_aliados = reg('tp_promociones_aliados', 'tpc_codigo_promociones_aliados', $_POST['tpc_codigo_promociones_aliados']);
															unlink($tp_promociones_aliados['tpc_archivo_promociones_aliados']);
															$con->query("DELETE FROM tp_promociones_aliados WHERE tpc_codigo_promociones_aliados='".$_POST['tpc_codigo_promociones_aliados']."'");
															$mensaje = "Promocion eliminada correctamente";
														break;
													}
													echo '
													<form name="finality" method="post" action="promocionesaliados.php">
														<input type="hidden" name="mensaje" value="'.$mensaje.'">
													</form>
													<script type="text/javascript">
														alert("'.$mensaje.'");
														finality.submit();
													</script>';
													exit();
												}
												
												
												
												if($_POST['tipo'] == 'evento'){
													if($usuario['tpc_rol_usuario'] != 2){
														header('Location: index.php');
														exit();
													}
													switch($_POST['opc']){
														case 'nuevo':
															if(isset($_FILES['tpc_archivo_evento']) && $_FILES['tpc_archivo_evento']['name'] != ''){// SI SE RECIBE IMAGEN                               
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_evento']['name'];
																if(!move_uploaded_file($_FILES['tpc_archivo_evento']['tmp_name'], $fichero_subido)) {
																	$mensaje = "Error al subir archivo";
																}else{
																	chmod($fichero_subido, 0777);
																	$con->query("INSERT INTO tp_evento VALUES (NULL, '".$_POST['tpc_nombre_evento']."', '".date("Y-m-d", strtotime($_POST['tpc_validodesde_evento']))." 00:00:00', '".date("Y-m-d", strtotime($_POST['tpc_validohasta_evento']))." 23:59:59', '".$fichero_subido."', '".$_POST['tpc_descripcion_evento']."', '".$_POST['tpc_ciudad_evento']."', '".$_POST['tpc_departamento_evento']."', '".$_POST['direccion']."', '0', '0', '".$_POST['tpc_url_evento']."');");
																	$con->query("SELECT tpc_codigo_evento FROM tp_evento WHERE tpc_nombre_evento='".$_POST['tpc_nombre_evento']."' AND tpc_validodesde_evento='".date("Y-m-d", strtotime($_POST['tpc_validodesde_evento']))." 00:00:00' AND tpc_descripcion_evento='".$_POST['tpc_descripcion_evento']."' AND tpc_ciudad_evento='".$_POST['tpc_ciudad_evento']."' AND tpc_direccion_evento='".$_POST['direccion']."' AND tpc_latitud_evento='0';");
																	$con->next_record();
																	alatitudlongitud($_POST['direccion'], $_POST['tpc_ciudad_evento'], 'Colombia', $con->f("tpc_codigo_evento"), 2);
																	$mensaje="Evento Creado Correctamente";
																}
															}else{
																$mensaje = "Error al subir archivo";
															}
														break;
														case 'editar':
															$tp_evento = reg('tp_evento', 'tpc_codigo_evento', $_POST['tpc_codigo_evento']);
															$con->query("UPDATE tp_evento SET tpc_nombre_evento='".$_POST['tpc_nombre_evento']."', tpc_validodesde_evento='".date("Y-m-d", strtotime($_POST['tpc_validodesde_evento']))." 00:00:00', tpc_validohasta_evento='".date("Y-m-d", strtotime($_POST['tpc_validohasta_evento']))." 23:59:59', tpc_descripcion_evento='".$_POST['tpc_descripcion_evento']."', tpc_ciudad_evento='".$_POST['tpc_ciudad_evento']."', tpc_departamento_evento='".$_POST['tpc_departamento_evento']."', tpc_direccion_evento='".$_POST['direccion']."', tpc_url_evento='".$_POST['tpc_url_evento']."' WHERE tpc_codigo_evento='".$_POST['tpc_codigo_evento']."'");
															if($_POST['direccion'] != $tp_evento['tpc_direccion_evento'] || $_POST['tpc_ciudad_evento'] != $tp_evento['tpc_ciudad_evento']){//SI CAMBIO DIRECCION RECALCULAMOS POSICION
																alatitudlongitud($_POST['direccion'], $_POST['tpc_ciudad_evento'], 'Colombia', $_POST['tpc_codigo_evento'], 2);
															}
															if(isset($_FILES['tpc_archivo_evento']) && $_FILES['tpc_archivo_evento']['name'] != ''){
																$prefijo = substr(md5(uniqid(rand())),0,6);
																$fichero_subido = 'img/documentos/' . $prefijo . '_' . $_FILES['tpc_archivo_evento']['name'];
																if(move_uploaded_file($_FILES['tpc_archivo_evento']['tmp_name'], $fichero_subido)) {
																	unlink($tp_evento['tpc_archivo_evento']);
																	$con->query("UPDATE tp_evento SET tpc_archivo_evento='".$fichero_subido."' WHERE tpc_codigo_evento='".$_POST['tpc_codigo_evento']."';");
																	chmod($fichero_subido, 0777);
																}
															}
															$mensaje = "Evento editado correctamente";
														break;
														case 'eliminar':
															$tp_evento = reg('tp_evento', 'tpc_codigo_evento', $_POST['tpc_codigo_evento']);
															$archivo = explode("/", $tp_evento['tpc_archivo_evento']);
															unlink("./".$archivo[count($archivo)-3]."/".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
															$con->query("DELETE FROM tp_evento WHERE tpc_codigo_evento='".$_POST['tpc_codigo_evento']."';");
															$mensaje="Evento Eliminado Correctamente";
														break;
													}
													echo '
													<form name="finality" method="post" action="./filtros.php?opcion=eventos">
														<input type="hidden" name="mensaje" value="'.$mensaje.'">
													</form>
													<script type="text/javascript">
														alert("'.$mensaje.'");
														finality.submit();
													</script>';
													exit();
												}
												if($_POST['tipo'] == 'videorse'){
													$con->query("UPDATE tp_establecimientos SET tpc_videoinversionista_establecimiento='".$_POST['tpc_videoinversionista_establecimiento']."' WHERE tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."' AND tpc_asignadoa_establecimiento='".$mkid."';");
													echo '
													<form name="finality" method="post" action="index.php">
														<input type="hidden" name="mensaje" value="Video modificado correctamente">
													</form>
													<script type="text/javascript">
														alert("Video modificado correctamente");
														finality.submit();
													</script>';
													exit();
												}
												if($_POST['tipo'] == 'videofundacion'){
													if($usuario['tpc_rol_usuario'] != 2){
														header('Location: index.php');
														exit();
													}
													if($_POST['tpc_departamento_establecimiento'] == 'todas' && $_POST['tpc_ciudad_establecimiento'] != 'todas'){
														$con->query("UPDATE tp_establecimientos SET tpc_videofundacion_establecimiento='".$_POST['tpc_videofundacion_establecimiento']."' WHERE tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."';");
													}
													if($_POST['tpc_departamento_establecimiento'] == 'todas' && $_POST['tpc_ciudad_establecimiento'] == 'todas'){
														$con->query("UPDATE tp_establecimientos SET tpc_videofundacion_establecimiento='".$_POST['tpc_videofundacion_establecimiento']."';");
													}
													if($_POST['tpc_departamento_establecimiento'] != 'todas' && $_POST['tpc_ciudad_establecimiento'] != 'todas'){
														$con->query("UPDATE tp_establecimientos SET tpc_videofundacion_establecimiento='".$_POST['tpc_videofundacion_establecimiento']."' WHERE tpc_departamento_establecimiento='".$_POST['tpc_departamento_establecimiento']."' AND tpc_ciudad_establecimiento='".$_POST['tpc_ciudad_establecimiento']."';");
													}
													echo '
													<form name="finality" method="post" action="index.php">
														<input type="hidden" name="mensaje" value="Video modificado correctamente">
													</form>
													<script type="text/javascript">
														alert("Video modificado correctamente");
														finality.submit();
													</script>';
													exit();
												}
												//***********************************************************************************************
												if($_GET['tipo'] == 'usuario'){
													if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
														header('Location: index.php');
														exit();
													}
													switch($_GET['opc']){
														case 'nuevo':
															echo '<div class="basic-login-inner">
																<h3>Nuevo Usuario</h3>
																<p>Ingresa todos los datos del nuevo usuario</p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return check_strength()">
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Imagen</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="file" name="imagen" id="imagen" class="form-control">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Empresa</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_empresa_usuario" id="tpc_empresa_usuario" value="" class="form-control" style="width: 100%;" placeholder="Empresa..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Identificación</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="identificacion" id="identificacion" value="" class="form-control" style="width: 100%;" placeholder="Identificacion..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Tipo de identificación</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="tipoidentificacion" id="tipoidentificacion" class="form-control" required="required">
																					<option value="">Seleccione.....</option>
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
																				<label class="login2">Area Encargada</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_areaencargada_usuario" id="tpc_areaencargada_usuario" value="" class="form-control" style="width: 100%;" placeholder="Area Encargada..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Encargad</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_encargado_usuario" id="tpc_encargado_usuario" value="" class="form-control" style="width: 100%;" placeholder="Encargado..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Cargo Encargado</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_cargoencargado_usuario" id="tpc_cargoencargado_usuario" value="" class="form-control" style="width: 100%;" placeholder="Cargo..." required="required">
																			</div>
																		</div>
																	</div>
												
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Correo</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="email" name="correo" id="correo" class="form-control" style="width: 100%;" placeholder="Correo..." value="" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Nombre Generico Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="nombre" id="nombre" maxlength="12" class="form-control" style="width: 100%;" placeholder="Nombres..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Apellido Generico Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="apellido" id="apellido" maxlength="12" class="form-control" style="width: 100%;" placeholder="Apellidos..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Nick Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="usuario" id="usuario" class="form-control" style="width: 100%;" placeholder="Usuario..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Clave de usuario</label>
																			</div>
																			<div class="col-lg-7">
																				<input type="password" name="clave" id="clave" class="form-control" style="width: 100%;" placeholder="Clave..." required="required">
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
																				<label class="login2">Celular</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" name="tpc_celular_usuario" id="tpc_celular_usuario" class="form-control" value="" style="width: 100%;" placeholder="3001234567" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Fijo</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" name="telefono" id="telefono" class="form-control" style="width: 100%;" placeholder="6011234567" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Direcci&oacute;n Principal</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="direccion" id="direccion" class="form-control" style="width: 100%;" placeholder="Dirección..." required="required">
																				NOTA: Poner direccion sin tildes y completa sin ciudad ni minucipio, EJEMPLO: Calle 12b #04-79
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Departamento</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="tpc_departamento_usuario" id="tpc_departamento_usuario" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>';
																					echo '<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Ciudad</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="ciudad" id="ciudad" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>
																					<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
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
																					<option value="">Seleccione....</option>
																					<option value="Colombia">Colombia</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Plan</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="rol" id="rol" class="form-control" required="required" onchange="mostrardiv_aliado()">
																					<option value="">Seleccione.....</option>
																					<option value="2">Administrador</option>
																					<option value="-1">Plan Aliado Estrategico</option>
																					<option value="0">Plan Gratuito</option>
																					<option value="-4">Plan Inversionista Ultra</option>
																					<option value="1">Plan Inversionista Premium</option>
																					<option value="-3">Plan Patrocinador Ultra</option>
																					<option value="-2">Plan Patrocinador Premium</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Inversión Por Establecimiento</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" name="tpc_inversionestabs_usuario" id="tpc_inversionestabs_usuario" class="form-control" style="width: 100%;" placeholder="Inversión Por Establecimiento...." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">% De inversión</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" min="0" max="100" name="tpc_porcinversion_usuario" id="tpc_porcinversion_usuario" class="form-control" style="width: 100%;" placeholder="% De inversión" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Inversión por arbol</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" min="0" name="tpc_inversionarbolesmes_usuario" id="tpc_inversionarbolesmes_usuario" class="form-control" style="width: 100%;" placeholder="Inversion por arbol" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Arboles Mensuales</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" min="0" name="tpc_arbolesmes_usuario" id="tpc_arbolesmes_usuario" class="form-control" style="width: 100%;" placeholder="Arboles por mes" required="required">
																			</div>
																		</div>
																	</div>
																	<div id="divdetalleusuario" style="display: none;">
																		<center>
																			<table style="width: 100%;">
																				<tr>
																					<td align="center">
																						<label class="login2">Apertura Dia Hábil</label>
																					</td>
																					<td>
																						<select name="tpc_aperturahabil_usuariodetalle" id="tpc_aperturahabil_usuariodetalle" class="form-control">
																							<option value="">Seleccione....</option>';
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
																							<option value="">Seleccione....</option>';
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
																							<option value="">Seleccione....</option>';
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
																							<option value="">Seleccione....</option>';
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
																						<input type="text" name="tpc_video_usuariodetalle" id="tpc_video_usuariodetalle" class="form-control" placeholder="Ingresa tu link aquí.....">
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
																						<label class="login2">Video fundación embebido</label>
																					</td>
																					<td>
																						<input type="text" name="tpc_videofundacion_usuariodetalle" id="tpc_videofundacion_usuariodetalle" class="form-control" placeholder="Ingresa video de fundación.....">
																					</td>
																				</tr>
																				<tr>
																					<td align="center">
																						<label class="login2">URL (Sitio Web)</label>
																					</td>
																					<td>
																						<input type="text" name="tpc_url_usuariodetalle" id="tpc_url_usuariodetalle" class="form-control" placeholder="Ingresa tu URL.....">
																					</td>
																				</tr>
																			</table>
																		</center>
																	</div>
																	<div class="login-btn-inner">
																		<div class="row">
																			<div class="col-lg-4"></div>
																			<div class="col-lg-8">
																				<div class="login-horizental">
																					<input type="hidden" name="opc" id="opc" value="nuevo">
																					<input type="hidden" name="tipo" id="tipo" value="usuario">
																					<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Usuario</button>
																				</div>
																			</div>
																		</div>
																	</div>
																</form>
															</div>';
														break;
														case 'editar':
															$con->query("SELECT * FROM tp_usuarios WHERE tpc_codigo_usuario='".$_GET['idusuario']."' ORDER BY tpc_nickname_usuario;");
															if($con->num_rows() > 0){
																$con->next_record();
																$roles = array("Plan Gratuito", "Inversionista", "Administrador");
																$estados = array("InActivo", "Activo");
																switch($con->f("tpc_rol_usuario")){
																	case "-2": $rol = "Plan Patrocinado"; break;
																	case "-1": $rol = "Aliado Estrategico"; break;
																	case "0": $rol = "Plan Gratuito"; break;
																	case "1": $rol = "Inversionista"; break;
																	case "2": $rol = "Administrador"; break;
																}
																if(intval($con->f("tpc_rol_usuario") == -1 || $con->f("tpc_rol_usuario") == -2)){
																	$tp_usuarios_detalle = reg('tp_usuarios_detalle', 'tpc_usuario_usuariodetalle', $_GET['idusuario']);
																}
																echo '<div class="basic-login-inner">
																	<h3>Editar Usuario</h3>
																	<p>Ingresa todos los datos del usuario</p>
																	<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return check_strength()">
																		<div class="form-group-inner">
																			<div class="row">
																				<div class="col-lg-4">
																					<label class="login2">Imagen</label>
																				</div>
																				<div class="col-lg-8">
																					<input type="file" name="imagen" id="imagen" class="form-control">
																				</div>
																			</div>
																		</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Empresa</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_empresa_usuario" id="tpc_empresa_usuario" value="'.$con->f("tpc_empresa_usuario").'" class="form-control" style="width: 100%;" placeholder="Empresa..." required="required">
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
																				<label class="login2">Area Encargada</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_areaencargada_usuario" id="tpc_areaencargada_usuario" value="'.$con->f("tpc_areaencargada_usuario").'" class="form-control" style="width: 100%;" placeholder="Area Encargada..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Encargad</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_encargado_usuario" id="tpc_encargado_usuario" value="'.$con->f("tpc_encargado_usuario").'" class="form-control" style="width: 100%;" placeholder="Encargado..." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Cargo Encargado</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="tpc_cargoencargado_usuario" id="tpc_cargoencargado_usuario" value="'.$con->f("tpc_cargoencargado_usuario").'" class="form-control" style="width: 100%;" placeholder="Cargo..." required="required">
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
																				<label class="login2">Nombre Generico Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="nombre" id="nombre" class="form-control" style="width: 100%;" placeholder="Nombres..." value="'.$con->f("tpc_nombres_usuario").'" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Apellido Generico Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="apellido" id="apellido" class="form-control" style="width: 100%;" placeholder="Apellidos..." value="'.$con->f("tpc_apellidos_usuario").'" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Nick Usuario</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="text" name="usuario" id="usuario" class="form-control" style="width: 100%;" placeholder="Usuario..." value="'.$con->f("tpc_nickname_usuario").'" required="required">
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
																				<label class="login2">Celular</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" name="tpc_celular_usuario" id="tpc_celular_usuario" class="form-control" value="'.$con->f("tpc_celular_usuario").'" style="width: 100%;" placeholder="3001234567" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Fijo</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" name="telefono" id="telefono" class="form-control" value="'.$con->f("tpc_telefono_usuario").'" style="width: 100%;" placeholder="6011234567" required="required">
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
																				<label class="login2">Departamento</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="tpc_departamento_usuario" id="tpc_departamento_usuario" class="form-control" required="required">
																					<option value="'.$con->f("tpc_departamento_usuario").'" selected="selected">'.$con->f("tpc_departamento_usuario").'</option>';
																					echo '<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Ciudad</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="ciudad" id="ciudad" class="form-control" required="required">
																					<option value="'.$con->f("tpc_ciudad_usuario").'" selected="selected">'.$con->f("tpc_ciudad_usuario").'</option>
																					<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
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
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Plan</label>
																			</div>
																			<div class="col-lg-8">
																				<select name="rol" id="rol" class="form-control" onchange="mostrardiv_aliado()">
																					<option value="'.$con->f("tpc_rol_usuario").'">'.$rol.'</option>
																					<option value="2">Administrador</option>
																					<option value="-1">Plan Aliado Estrategico</option>
																					<option value="0">Plan Gratuito</option>
																					<option value="-4">Plan Inversionista Ultra</option>
																					<option value="1">Plan Inversionista Premium</option>
																					<option value="-3">Plan Patrocinador Ultra</option>
																					<option value="-2">Plan Patrocinador Premium</option>
																				</select>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2"># de Establecimientos</label>
																			</div>
																			<div class="col-lg-8">';
																				$con1->query("SELECT COUNT(*) as cuenta_est FROM tp_establecimientos WHERE tpc_asignadoa_establecimiento='".$_GET['idusuario']."';");
																				$con1->next_record();
																				echo $con1->f("cuenta_est").'<input type="hidden" name="cantidad_establecimientos" id="cantidad_establecimientos" value="'.$con1->f("cuenta_est").'">';
																		echo '</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Inversión Por Establecimiento</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" value="'.$con->f("tpc_inversionestabs_usuario").'" onchange="calcularinversion_usuario()" onkeyup="calcularinversion_usuario()" name="tpc_inversionestabs_usuario" id="tpc_inversionestabs_usuario" class="form-control" style="width: 100%;" placeholder="Inversión Por Establecimiento...." required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Valor de la factura</label>
																			</div>
																			<div class="col-lg-8">
																				<div id="valorfactura_usuario" style="width: 100%;"></div>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">% De inversión</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" value="'.$con->f("tpc_porcinversion_usuario").'" onchange="calcularinversion_usuario()" onkeyup="calcularinversion_usuario()" min="0" max="100" name="tpc_porcinversion_usuario" id="tpc_porcinversion_usuario" class="form-control" style="width: 100%;" placeholder="% De inversión" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Total Inversión</label>
																			</div>
																			<div class="col-lg-8">
																				<div id="total_inversion" style="width: 100%;"></div>
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Inversión por arbol</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" value="'.$con->f("tpc_inversionarbolesmes_usuario").'" onchange="calcularinversion_usuario()" onkeyup="calcularinversion_usuario()" min="0" name="tpc_inversionarbolesmes_usuario" id="tpc_inversionarbolesmes_usuario" class="form-control" style="width: 100%;" placeholder="Inversion por arbol" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Arboles Mensuales</label>
																			</div>
																			<div class="col-lg-8">
																				<input type="number" value="'.$con->f("tpc_arbolesmes_usuario").'" onchange="calcularinversion_usuario()" onkeyup="calcularinversion_usuario()" min="0" name="tpc_arbolesmes_usuario" id="tpc_arbolesmes_usuario" class="form-control" style="width: 100%;" placeholder="Arboles por mes" required="required">
																			</div>
																		</div>
																	</div>
																	<div class="form-group-inner">
																		<div class="row">
																			<div class="col-lg-4">
																				<label class="login2">Total Mensual Arboles</label>
																			</div>
																			<div class="col-lg-8">
																				<div id="total_inversion_menarboles" style="width: 100%;"></div>
																			</div>
																		</div>
																	</div>
																	<div id="divdetalleusuario" style="display: none;">
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
																						<label class="login2">Video fundación embebido</label>
																					</td>
																					<td>
																						<input type="text" value="'.$tp_usuarios_detalle['tpc_videofundacion_usuariodetalle'].'" name="tpc_videofundacion_usuariodetalle" id="tpc_videofundacion_usuariodetalle" class="form-control" placeholder="Ingresa video de fundación.....">
																					</td>
																				</tr>
																				<tr>
																					<td align="center">
																						<label class="login2">URL (Sitio Web)</label>
																					</td>
																					<td>
																						<input type="text" value="'.$tp_usuarios_detalle['tpc_url_usuariodetalle'].'" name="tpc_url_usuariodetalle" id="tpc_url_usuariodetalle" class="form-control" placeholder="Ingresa tu URL.....">
																					</td>
																				</tr>
																			</table>
																		</center>
																	</div>
																	<div class="login-btn-inner">
																		<div class="row">
																			<div class="col-lg-4"></div>
																			<div class="col-lg-8">
																				<div class="login-horizental">
																					<input type="hidden" name="idusuario" id="idusuario" value="'.$_GET['idusuario'].'">
																					<input type="hidden" name="opc" id="opc" value="editar">
																					<input type="hidden" name="tipo" id="tipo" value="usuario">
																					<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios</button>
																				</div>
																			</div>
																		</div>
																	</div>
																	<script>mostrardiv_aliado();calcularinversion_usuario();</script>
																	</form>
																</div>';
															}
														break;
														case 'logs':
															if($_GET['tiempologs'] == ''){
																	$cadena_ejecucion = "SELECT * FROM tp_acciones_usuario INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_idusuario_accionusuario AND tpc_idusuario_accionusuario='".$_GET['idusuario']."' ORDER BY tpc_fecha_accionusuario DESC;";
															}else{
																	$actual = strtotime(date("Y-m-d"));
																	$mesesmenos = date("Y-m-d", strtotime("-".$_GET['tiempologs']." month", $actual));
																	$cadena_ejecucion = "SELECT * FROM tp_acciones_usuario INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_idusuario_accionusuario AND tpc_idusuario_accionusuario='".$_GET['idusuario']."' AND tpc_fecha_accionusuario BETWEEN '".$mesesmenos." 00:00:00' AND '".date("Y-m-d H:i:s")."' ORDER BY tpc_fecha_accionusuario DESC;";
															}
															$con->query($cadena_ejecucion);
															$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $_GET['idusuario']);
															if($con->num_rows() > 0){
																	echo '
																	<center>
																	
																			<form method="post" action="./filtros.php?opcion=usuarios">
																					<div style="width: 80%;">
																							<h3 align="center">Logs Usuario '.$usuario['tpc_nickname_usuario'].'</h3><br>
																							<input type="submit" class="btn btn-primary btn-sm" value="Regresar">
																							<br><br>
																							<table border="1" style="width: 100%;border-collapse: collapse;" align="center">
																									<tr>
																											<th><center><label><b>Fecha Acci&oacute;n</b></label></center></th>
																											<th><center><label><b>Acci&oacute;n Realizada</b></label></center></th>
																											<th><center><label><b>Direccion IP Acci&oacute;n</b></label></center></th>
																									</tr>';
																									while($con->next_record()){
																											echo '
																											<tr>
																													<td><center><label>'.$con->f("tpc_fecha_accionusuario").'</label></center></td>
																													<td><center><label>'.$con->f("tpc_accionrealizada_accionusuario").'</label></center></td>
																													<td><center><label>'.$con->f("tpc_direccionip_accionesusuario").'</label></center></td>
																											</tr>';
																									}
																			echo '	</table><br><br>
																							<input type="submit" class="btn btn-primary btn-sm" value="Regresar">
																					</div>
																			</form>
																	</center>';
															}else{
																	echo '
																	<center>
																		<form method="post" action="./filtros.php?opcion=usuarios">
																				<h5 align="center">No se han encontrado logs para el usuario '.$usuario['tpc_nickname_usuario'].'</h5><br>
																				<input type="submit" class="btn btn-primary btn-sm" value="Regresar">
																		</form>
																	</center>';
															}
														break;
														case 'importar':
															echo '
																<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																	<input type="hidden" name="opc" id="opc" value="importar">
																	<input type="hidden" name="tipo" id="tipo" value="usuario">
																	Importar Usuarios: <br>
																	<input type="file" name="archivo" id="archivo" class="btn btn-default" style="width: 310px;" required="required"><br><br>
																	Recuerda que el archivo debe tener en cada columna el titulo, y el siguiente orden de columnas: <br><br>
																	1. Nombres Usuario <br>
																	2. Apellidos Usuario <br>
																	3. # Identificación Usuario <br>
																	4. Tipo Identificación <br>
																	5. Telefono <br>
																	6. Email <br>
																	7. Nombre de Usuario Asignado <br>
																	8. Contraseña Usuario <br><br>
																	<input type="submit" class="btn btn-primary btn-sm" value="Importar">
																</form>
															';
														break;
														case 'activacion':
															if($_GET['idusuario'] == $mkid){
																$mensaje = 'No Te Puedes Inactivar A Ti Mismo';
															}else{
																$ediusu = reg('tp_usuarios', 'tpc_codigo_usuario', $_GET['idusuario']);
																if($ediusu['tpc_estado_usuario'] == 1 || $ediusu['tpc_estado_usuario'] == "1"){
																	$con->query("UPDATE tp_usuarios SET tpc_estado_usuario='0' WHERE tpc_codigo_usuario='".$_GET['idusuario']."';");
																	$mensaje = 'Usuario InActivado Correctamente';
																}else{
																	$con->query("UPDATE tp_usuarios SET tpc_estado_usuario='1' WHERE tpc_codigo_usuario='".$_GET['idusuario']."';");
																	$mensaje = 'Usuario Activado Correctamente';
																}
															}
															echo '
															<form name="finality" method="post" action="./filtros.php?opcion=usuarios">
																<input type="hidden" name="mensaje" value="'.$mensaje.'">
															</form>
															<script type="text/javascript">
																alert("'.$mensaje.'");
																finality.submit();
															</script>';
														break;
													}
												}
												if($_GET['tipo'] == 'contacto'){
													switch($_GET['opc']){
														case 'nuevo':
															echo '<div class="basic-login-inner">
																<h3>Nuevo Establecimiento</h3>
																<p>Ingresa todos los datos del nuevo establecimiento</p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																	<table width="100%">
																		<tr>
																			<td align="center" colspan="4"><h3>INFORMACIÓN DEL PROPIETARIO</h3></td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Nombres</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombres_establecimientos_propietario" id="tpc_nombres_establecimientos_propietario" placeholder="Nombres..." class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Apellidos</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_apellidos_establecimientos_propietario" id="tpc_apellidos_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="Apellidos..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Tipo documento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_tipodocumento_establecimientos_propietario" id="tpc_tipodocumento_establecimientos_propietario" class="form-control" required="required">
																				   <option value="">Seleccione....</option>
																				   <option value="Cedula">Cedula</option>
																				   <option value="Cedula de extranjeria">Cedula de extranjeria</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2"># de documento</label>
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_documento_establecimientos_propietario" id="tpc_documento_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="# Documento..." required="required">
																			</td>
																		</tr>
																		<tr>
																			<td align="center" colspan="4">
																				<label class="login2">Fecha nacimiento</label>
																				<input type="date" style="width: 250px;" name="tpc_fechanacimiento_establecimientos_propietario" id="tpc_fechanacimiento_establecimientos_propietario" value="" class="form-control" style="width: 100%;" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Genero</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_genero_establecimientos_propietario" id="tpc_genero_establecimientos_propietario" class="form-control" required="required">
																				   <option value="">Seleccione....</option>
																				   <option value="Masculino">Masculino</option>
																				   <option value="Femenino">Femenino</option>
																				   <option value="Otro">Otro</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Celular</label>
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_celular_establecimientos_propietario" id="tpc_celular_establecimientos_propietario" value="" class="form-control" style="width: 100%;" placeholder="Celular..." required="required">
																			</td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr>
																			<td align="center" colspan="4"><h3>INFORMACIÓN DEL ESTABLECIMIENTO</h3></td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen (Opcional)</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_imagen_establecimiento" id="tpc_imagen_establecimiento" class="form-control">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_establecimiento" id="tpc_nombre_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Categoria</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_categoria_establecimiento" id="tpc_categoria_establecimiento" class="form-control" required="required">
																					<option value="">Seleccione.....</option>
																					<!--<option value="APP ALIADA">APP ALIADA</option>--> <!--<option value="ALIADOS ESTRATEGICOS">ALIADOS ESTRATEGICOS</option>-->
																					   <!--<option value="ANIMALES Y AGRICULTURA">ANIMALES Y AGRICULTURA</option>-->
																					   <!--<option value="AUTOPARTES">AUTOPARTES</option>-->
																					   <!--<option value="BELLEZA Y ESTETICA">BELLEZA Y ESTETICA</option>-->
																					   <!--<option value="CENTROS COMERCIALES">CENTROS COMERCIALES</option>-->
																					   <!--<option value="CORRESPONSALES BANCARIOS">CORRESPONSALES BANCARIOS</option>-->
																					   <!--<option value="DIVERSION Y AVENTURA">DIVERSION Y AVENTURA</option>-->
																					   <!--<option value="EDUCACION">EDUCACION</option>-->
																					   <option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
																					   <option value="ESTACIONES DE SERVICIO">ESTACIONES DE SERVICIO</option>
																					   <option value="HOGAR Y MISCELANEAS">HOGAR Y MISCELANEAS</option>
																					   <!--<option value="ELEMENTOS DEPORTIVOS">ELEMENTOS DEPORTIVOS</option>-->
																					   <!--<option value="EMPLEO">EMPLEO</option>-->
																					   <!--<option value="ESTABLECIMIENTOS DE BARRIO">ESTABLECIMIENTOS DE BARRIO</option>-->
																					   <option value="FARMACIAS">FARMACIAS</option>
																					   <!--<option value="FUNDACION ALIADA">FUNDACION ALIADA</option>-->
																					   <option value="GASTRONOMIA">GASTRONOMIA</option>
																					   <!--<option value="GIROS Y ENTREGAS">GIROS Y ENTREGAS</option>-->
																					   <!--<option value="OTROS">OTROS</option>-->
																					   <option value="ROPA Y ACCESORIOS">ROPA Y ACCESORIOS</option>
																					   <option value="SALUD Y BELLEZA">SALUD Y BELLEZA</option>
																					   <option value="SUPERMERCADOS Y TIENDAS">SUPERMERCADOS Y TIENDAS</option>
																					   <!--<option value="TECNOLOGIA">TECNOLOGIA</option>-->
																					   <!--<option value="TURISMO">TURISMO</option>-->
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Telefono Particular</label>
																			</td>
																			<td width="30%">
																				<table>
																					<tr>
																						<td>
																							<select class="form-control" id="indicativoparticular" name="indicativoparticular">
																								<option value="601">601</option>
																							</select>
																						</td>
																						<td>
																							<input type="text" name="tpc_telparticular_establecimiento" id="tpc_telparticular_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Telefono Particular..." required="required">
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Telefono Móvil</label>
																			</td>
																			<td width="30%">
																				<table>
																					<tr>
																						<td>
																							<select class="form-control" id="indicativomovil" name="indicativomovil">
																								<option value="57">+57</option>
																							</select>
																						</td>
																						<td>
																							<input type="text" name="tpc_movil_establecimiento" id="tpc_movil_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Telefono Móvil..." required="required">
																						</td>
																					</tr>
																				</table>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Email</label>
																			</td>
																			<td width="30%">
																				<input type="email" name="tpc_email_establecimiento" id="tpc_email_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Email..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%">';
																			if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
																				echo '<select name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" class="form-control" required="required">
																						<option value="">Seleccione.....</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																				echo '</select>';
																			}else{
																				echo '<input type="hidden" name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																			}
																			echo '</td>
																			<td width="20%" align="center">
																				<label class="login2">Tipo de Identificación</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_tipodocumento_establecimiento" id="tpc_tipodocumento_establecimiento" class="form-control" required="required">
																					<option value="">Seleccione.....</option>
																					<option value="NIT">NIT</option>
																					<option value="CC">Cedula de Ciudadania</option>
																					<option value="CE">Cedula de Extrajeria</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Identificación</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_numdocumento_establecimiento" id="tpc_numdocumento_establecimiento" class="form-control" style="width: 100%;" placeholder="Identificación..." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Pais</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_pais_establecimiento" id="tpc_pais_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>
																					<option value="Argentina">Argentina</option>
																					<option value="Brasil">Brasil</option>
																					<option value="Canada">Canada</option>
																					<option value="Chile">Chile</option>
																					<option value="Colombia">Colombia</option>
																					<option value="Costa Rica">Costa Rica</option>
																					<option value="Cuba">Cuba</option>
																					<option value="Ecuador">Ecuador</option>
																					<option value="Estados Unidos">Estados Unidos</option>
																					<option value="Guatemala">Guatemala</option>
																					<option value="Guyana Francesa">Guyana Francesa</option>
																					<option value="Honduras">Honduras</option>
																					<option value="Mexico">Mexico</option>
																					<option value="Panama">Panama</option>
																					<option value="Parauay">Parauay</option>
																					<option value="San Salvador">San Salvador</option>
																					<option value="Uruguay">Uruguay</option>
																					<option value="Venezuela">Venezuela</option>
																				</select>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="4"><center>
																				<label><b>Dirección</b></label><br>
																				<select name="param1direccion" id="param1direccion" class="form-control-sm" required="required" onchange="armasdir_conjunto()">
																					<option value="">Seleccione....</option>
																					<option value="Transversal">Transversal</option>
																					<option value="Diagonal">Diagonal</option>
																					<option value="Avenida Calle">Avenida Calle</option>
																					<option value="Avenida Carrera">Avenida Carrera</option>
																					<option value="Calle">Calle</option>
																					<option value="Carrera">Carrera</option>
																				</select>
																				<input type="number" name="param2direccion" class="form-control-sm" style="width: 70px;" id="param2direccion" required="required" onkeyup="armasdir_conjunto()">
																				<select name="param3direccion" id="param3direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>';
																					$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																					for($i = 0; $i < count($letras); $i++){
																						echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																					}
																			echo '    </select>
																				<input type="checkbox" name="param4direccion" id="param4direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param4direccion">Bis</label> &nbsp;&nbsp;
																				<select name="param5direccion" id="param5direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>
																					<option value="Norte">Norte</option>
																					<option value="Sur">Sur</option>
																					<option value="Este">Este</option>
																					<option value="Oeste">Oeste</option>
																				</select> # 
																				<input type="number" name="param6direccion" class="form-control-sm" style="width: 70px;" id="param6direccion" required="required" onkeyup="armasdir_conjunto()">
																				<select name="param7direccion" id="param7direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>';
																					$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																					for($i = 0; $i < count($letras); $i++){
																						echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																					}
																			echo '    </select>
																				<input type="checkbox" name="param8direccion" id="param8direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param8direccion">Bis</label> &nbsp;&nbsp;
																				<input type="number" name="param9direccion" class="form-control-sm" style="width: 70px;" id="param9direccion" required="required" onkeyup="armasdir_conjunto()">
																				<select name="param10direccion" id="param10direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>
																					<option value="Norte">Norte</option>
																					<option value="Sur">Sur</option>
																					<option value="Este">Este</option>
																					<option value="Oeste">Oeste</option>
																				</select>
																				<br><br>
																				<input type="text" name="tpc_direccion_establecimiento" class="form-control-sm" id="tpc_direccion_establecimiento" placeholder="Dirección...." style="width:70%;" readonly="readonly">
																			</center></td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Departamento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>
																					<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Ciudad</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option><option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:80px;">
																			<td width="20%" align="center" colspan="4">
																				<label class="login2">Localidad</label><br>
																				<select name="tpc_localidad_establecimiento" id="tpc_localidad_establecimiento" class="form-control" style="width:140px;" required="required">
																					<option value="" selected="selected">Seleccione....</option><option value="Antonio Narino">ANTONIO NARIÑO</option><option value="Barrios Unidos">BARRIOS UNIDOS</option><option value="Bosa">BOSA</option><option value="Candelaria">CANDELARIA</option><option value="Chapinero">CHAPINERO</option><option value="Ciudad Bolivar">CIUDAD BOLIVAR</option><option value="Engativa">ENGATIVA</option><option value="Fontibon">FONTIBON</option><option value="Kennedy">KENNEDY</option><option value="Candelaria">LA CANDELARIA</option><option value="Martires">LOS MARTIRES</option><option value="Otra">OTRA</option><option value="Puente Aranda">PUENTE ARANDA</option><option value="Rafael Uribe Uribe">RAFAEL URIBE URIBE</option><option value="San Cristobal">SAN CRISTOBAL</option><option value="Santa Fe">SANTA FE</option><option value="Suba">SUBA</option><option value="Sumapaz">SUMAPAZ</option><option value="Teusaquillo">TEUSAQUILLO</option><option value="Tunjuelito">TUNJUELITO</option><option value="Usaquen">USAQUEN</option><option value="Usme">USME</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Video Inversionista</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_videoinversionista_establecimiento" id="tpc_videoinversionista_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Video Inversionista..." required="required">
																			</td>';
																				if(intval($usuario['tpc_rol_usuario']) == 2){
																					echo '<td width="20%" align="center">
																						<label class="login2">Video Fundación</label>
																					</td>
																					<td width="30%">
																						<input type="text" name="tpc_videofundacion_establecimiento" id="tpc_videofundacion_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Video Fundación..." required="required">
																					</td>';
																				}
																		echo '</tr>
																		<tr>
																			<td align="center" colspan="4">NOTA: Se recomienda que el(los) video(s) no superen los 20 segundos de duraci&oacute;n</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Apertura Dia Hábil</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_aperturahabil_establecimiento" id="tpc_aperturahabil_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione...</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Cierre Dia Hábil</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_cierrehabil_establecimiento" id="tpc_cierrehabil_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione...</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Apertura Fin de Semana</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_aperturafinsemana_establecimiento" id="tpc_aperturafinsemana_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione...</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Cierre Fin de Semana</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_cierrefinsemana_establecimiento" id="tpc_cierrefinsemana_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione...</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Sitio Web</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_sitioweb_establecimiento" id="tpc_sitioweb_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Sitio Web...." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Facebook</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_facebook_establecimiento" id="tpc_facebook_establecimiento" value="" class="form-control" style="width: 100%;" placeholder="Facebook..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">¿Hace Domicilios?</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_hacedomicilios_establecimiento" id="tpc_hacedomicilios_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione...</option>
																					<option value="Si">Si</option>
																					<option value="No">No</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valor Domicilio</label>
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_valordomicilio_establecimiento" id="tpc_valordomicilio_establecimiento" value="0" min="0" class="form-control" style="width: 100%;" placeholder="Valor Domicilio..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<label class="login2">Estrato: </label>
																				<select id="tpc_estrato_establecimiento" style="width: 150px;" class="form-control" name="tpc_estrato_establecimiento" required="required">
																					<option value="">Seleccione....</option>
																					<option value="1">1</option>
																					<option value="2">2</option>
																					<option value="3">3</option>
																					<option value="4">4</option>
																					<option value="5">5</option>
																					<option value="6">6</option>
																				 </select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="opc" id="opc" value="nuevo">
																				<input type="hidden" name="tipo" id="tipo" value="contacto">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Establecimiento</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'editar':
															$tp_establecimientos = reg('tp_establecimientos', 'tpc_codigo_establecimiento', $_GET['tpc_codigo_establecimiento']);
															$tp_establecimientos_propietario = reg('tp_establecimientos_propietario', 'tpc_idestablecimiento_establecimientos_propietario', $_GET['tpc_codigo_establecimiento']);
															echo '<div class="basic-login-inner">
																<h3>Editar Establecimiento</h3>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																	<table width="100%">
																		<tr>
																			<td align="center" colspan="4"><h3>INFORMACIÓN DEL PROPIETARIO</h3></td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Nombres</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombres_establecimientos_propietario" id="tpc_nombres_establecimientos_propietario" placeholder="Nombres..." value="'.$tp_establecimientos_propietario["tpc_nombres_establecimientos_propietario"].'" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Apellidos</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_apellidos_establecimientos_propietario" id="tpc_apellidos_establecimientos_propietario" class="form-control" value="'.$tp_establecimientos_propietario["tpc_apellidos_establecimientos_propietario"].'" style="width: 100%;" placeholder="Apellidos..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Tipo documento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_tipodocumento_establecimientos_propietario" id="tpc_tipodocumento_establecimientos_propietario" class="form-control" required="required">
																				   <option value="'.$tp_establecimientos_propietario["tpc_tipodocumento_establecimientos_propietario"].'">'.$tp_establecimientos_propietario["tpc_tipodocumento_establecimientos_propietario"].'</option>
																				   <option value="Cedula">Cedula</option>
																				   <option value="Cedula de extranjeria">Cedula de extranjeria</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2"># de documento</label>
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_documento_establecimientos_propietario" value="'.$tp_establecimientos_propietario["tpc_documento_establecimientos_propietario"].'" id="tpc_documento_establecimientos_propietario" class="form-control" style="width: 100%;" placeholder="# Documento..." required="required">
																			</td>
																		</tr>
																		<tr>
																			<td align="center" colspan="4">
																				<label class="login2">Fecha nacimiento</label>
																				<input type="date" style="width: 250px;" name="tpc_fechanacimiento_establecimientos_propietario" id="tpc_fechanacimiento_establecimientos_propietario" value="'.date("Y-m-d", strtotime($tp_establecimientos_propietario["tpc_documento_establecimientos_propietario"])).'" class="form-control" style="width: 100%;" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Genero</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_genero_establecimientos_propietario" id="tpc_genero_establecimientos_propietario" class="form-control" required="required">
																				   <option value="'.$tp_establecimientos_propietario["tpc_genero_establecimientos_propietario"].'">'.$tp_establecimientos_propietario["tpc_genero_establecimientos_propietario"].'</option>
																				   <option value="Masculino">Masculino</option>
																				   <option value="Femenino">Femenino</option>
																				   <option value="Otro">Otro</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Celular</label>
																			</td>
																			<td width="30%">
																				<input type="number" value="'.$tp_establecimientos_propietario["tpc_celular_establecimientos_propietario"].'" name="tpc_celular_establecimientos_propietario" id="tpc_celular_establecimientos_propietario" class="form-control" style="width: 100%;" placeholder="Celular..." required="required">
																			</td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr>
																			<td align="center" colspan="4"><h3>INFORMACIÓN DEL ESTABLECIMIENTO</h3></td>
																		</tr>
																		<tr><td><br></td></tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen (Opcional)</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_imagen_establecimiento" id="tpc_imagen_establecimiento" class="form-control">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_establecimiento" id="tpc_nombre_establecimiento" value="'.$tp_establecimientos['tpc_nombre_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Categoria</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_categoria_establecimiento" id="tpc_categoria_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_categoria_establecimiento'].'">'.$tp_establecimientos['tpc_categoria_establecimiento'].'</option>
																					<!--<option value="APP ALIADA">APP ALIADA</option>--> <!--<option value="ALIADOS ESTRATEGICOS">ALIADOS ESTRATEGICOS</option>-->
																					   <!--<option value="ANIMALES Y AGRICULTURA">ANIMALES Y AGRICULTURA</option>-->
																					   <!--<option value="AUTOPARTES">AUTOPARTES</option>-->
																					   <!--<option value="BELLEZA Y ESTETICA">BELLEZA Y ESTETICA</option>-->
																					   <!--<option value="CENTROS COMERCIALES">CENTROS COMERCIALES</option>-->
																					   <!--<option value="CORRESPONSALES BANCARIOS">CORRESPONSALES BANCARIOS</option>-->
																					   <!--<option value="DIVERSION Y AVENTURA">DIVERSION Y AVENTURA</option>-->
																					   <!--<option value="EDUCACION">EDUCACION</option>-->
																					   <option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
																					   <option value="ESTACIONES DE SERVICIO">ESTACIONES DE SERVICIO</option>
																					   <option value="HOGAR Y MISCELANEAS">HOGAR Y MISCELANEAS</option>
																					   <!--<option value="ELEMENTOS DEPORTIVOS">ELEMENTOS DEPORTIVOS</option>-->
																					   <!--<option value="EMPLEO">EMPLEO</option>-->
																					   <!--<option value="ESTABLECIMIENTOS DE BARRIO">ESTABLECIMIENTOS DE BARRIO</option>-->
																					   <option value="FARMACIAS">FARMACIAS</option>
																					   <!--<option value="FUNDACION ALIADA">FUNDACION ALIADA</option>-->
																					   <option value="GASTRONOMIA">GASTRONOMIA</option>
																					   <!--<option value="GIROS Y ENTREGAS">GIROS Y ENTREGAS</option>-->
																					   <!--<option value="OTROS">OTROS</option>-->
																					   <option value="ROPA Y ACCESORIOS">ROPA Y ACCESORIOS</option>
																					   <option value="SALUD Y BELLEZA">SALUD Y BELLEZA</option>
																					   <option value="SUPERMERCADOS Y TIENDAS">SUPERMERCADOS Y TIENDAS</option>
																					   <!--<option value="TECNOLOGIA">TECNOLOGIA</option>-->
																					   <!--<option value="TURISMO">TURISMO</option>-->
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Telefono Particular</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_telparticular_establecimiento" id="tpc_telparticular_establecimiento" value="'.$tp_establecimientos['tpc_telparticular_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Telefono Particular..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Telefono Móvil</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_movil_establecimiento" id="tpc_movil_establecimiento" value="'.$tp_establecimientos['tpc_movil_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Telefono Móvil..." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Email</label>
																			</td>
																			<td width="30%">
																				<input type="email" name="tpc_email_establecimiento" id="tpc_email_establecimiento" value="'.$tp_establecimientos['tpc_email_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Email..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%">';
																			if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
																				$asignadoa = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_establecimientos['tpc_asignadoa_establecimiento']);
																				echo '<select name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" class="form-control">
																						<option value="'.$asignadoa['tpc_codigo_usuario'].'">'.$asignadoa['tpc_nickname_usuario'].'</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																				echo '</select>';
																			}else{
																				echo '<input type="hidden" name="tpc_asignadoa_establecimiento" id="tpc_asignadoa_establecimiento" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																			}
																			echo '</td>
																			<td width="20%" align="center">
																				<label class="login2">Tipo de Identificación</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_tipodocumento_establecimiento" id="tpc_tipodocumento_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_tipodocumento_establecimiento'].'">'.$tp_establecimientos['tpc_tipodocumento_establecimiento'].'</option>
																					<option value="NIT">NIT</option>
																					<option value="CC">Cedula de Ciudadania</option>
																					<option value="CE">Cedula de Extrajeria</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Identificación</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_numdocumento_establecimiento" id="tpc_numdocumento_establecimiento" value="'.$tp_establecimientos['tpc_numdocumento_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Identificación..." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Pais</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_pais_establecimiento" id="tpc_pais_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_pais_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_pais_establecimiento'].'</option>
																					<option value="Argentina">Argentina</option>
																					<option value="Brasil">Brasil</option>
																					<option value="Canada">Canada</option>
																					<option value="Chile">Chile</option>
																					<option value="Colombia">Colombia</option>
																					<option value="Costa Rica">Costa Rica</option>
																					<option value="Cuba">Cuba</option>
																					<option value="Ecuador">Ecuador</option>
																					<option value="Estados Unidos">Estados Unidos</option>
																					<option value="Guatemala">Guatemala</option>
																					<option value="Guyana Francesa">Guyana Francesa</option>
																					<option value="Honduras">Honduras</option>
																					<option value="Mexico">Mexico</option>
																					<option value="Panama">Panama</option>
																					<option value="Parauay">Parauay</option>
																					<option value="San Salvador">San Salvador</option>
																					<option value="Uruguay">Uruguay</option>
																					<option value="Venezuela">Venezuela</option>
																				</select>
																			</td>
																		</tr>
																		<!--<tr style="height:80px;">
																			<td colspan="4">
																				<center><label class="login2">Dirección: </label><br><input type="text" name="tpc_direccion_establecimiento" id="tpc_direccion_establecimiento" class="form-control" style="width: 70%;" placeholder="Dirección..." required="required"></center>
																			</td>
																		</tr>-->
																		<tr>
																			<td colspan="4"><center>
																				<label><b>Dirección</b></label><br>
																				<select name="param1direccion" id="param1direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value="">Seleccione....</option>
																					<option value="Transversal">Transversal</option>
																					<option value="Diagonal">Diagonal</option>
																					<option value="Avenida Calle">Avenida Calle</option>
																					<option value="Avenida Carrera">Avenida Carrera</option>
																					<option value="Calle">Calle</option>
																					<option value="Carrera">Carrera</option>
																				</select>
																				<input type="number" name="param2direccion" class="form-control-sm" style="width: 70px;" id="param2direccion" onkeyup="armasdir_conjunto()">
																				<select name="param3direccion" id="param3direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>';
																					$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																					for($i = 0; $i < count($letras); $i++){
																						echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																					}
																			echo '    </select>
																				<input type="checkbox" name="param4direccion" id="param4direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param4direccion">Bis</label> &nbsp;&nbsp;
																				<select name="param5direccion" id="param5direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>
																					<option value="Norte">Norte</option>
																					<option value="Sur">Sur</option>
																					<option value="Este">Este</option>
																					<option value="Oeste">Oeste</option>
																				</select> # 
																				<input type="number" name="param6direccion" class="form-control-sm" style="width: 70px;" id="param6direccion" onkeyup="armasdir_conjunto()">
																				<select name="param7direccion" id="param7direccion" style="width: 70px;" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>';
																					$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
																					for($i = 0; $i < count($letras); $i++){
																						echo '<option value="'.$letras[$i].'">'.$letras[$i].'</option>';
																					}
																			echo '    </select>
																				<input type="checkbox" name="param8direccion" id="param8direccion" onclick="armasdir_conjunto()">&nbsp;<label for="param8direccion">Bis</label> &nbsp;&nbsp;
																				<input type="number" name="param9direccion" class="form-control-sm" style="width: 70px;" id="param9direccion" onkeyup="armasdir_conjunto()">
																				<select name="param10direccion" id="param10direccion" class="form-control-sm" onchange="armasdir_conjunto()">
																					<option value=""></option>
																					<option value="Norte">Norte</option>
																					<option value="Sur">Sur</option>
																					<option value="Este">Este</option>
																					<option value="Oeste">Oeste</option>
																				</select>
																				<br><br>
																				<input type="text" name="tpc_direccion_establecimiento" class="form-control-sm" id="tpc_direccion_establecimiento" value="'.$tp_establecimientos['tpc_direccion_establecimiento'].'" placeholder="Dirección...." style="width:70%;" readonly="readonly" required="required">
																			</center></td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Departamento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_departamento_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_departamento_establecimiento'].'</option>
																					<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Ciudad</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_ciudad_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_ciudad_establecimiento'].'</option><option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:80px;">
																			<td width="20%" align="center" colspan="4">
																				<label class="login2">Localidad</label><br>
																				<select name="tpc_localidad_establecimiento" id="tpc_localidad_establecimiento" class="form-control" style="width:140px;">
																					<option value="'.$tp_establecimientos['tpc_localidad_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_localidad_establecimiento'].'</option><option value="Antonio Narino">ANTONIO NARIÑO</option><option value="Barrios Unidos">BARRIOS UNIDOS</option><option value="Bosa">BOSA</option><option value="Candelaria">CANDELARIA</option><option value="Chapinero">CHAPINERO</option><option value="Ciudad Bolivar">CIUDAD BOLIVAR</option><option value="Engativa">ENGATIVA</option><option value="Fontibon">FONTIBON</option><option value="Kennedy">KENNEDY</option><option value="Candelaria">LA CANDELARIA</option><option value="Martires">LOS MARTIRES</option><option value="Otra">OTRA</option><option value="Puente Aranda">PUENTE ARANDA</option><option value="Rafael Uribe Uribe">RAFAEL URIBE URIBE</option><option value="San Cristobal">SAN CRISTOBAL</option><option value="Santa Fe">SANTA FE</option><option value="Suba">SUBA</option><option value="Sumapaz">SUMAPAZ</option><option value="Teusaquillo">TEUSAQUILLO</option><option value="Tunjuelito">TUNJUELITO</option><option value="Usaquen">USAQUEN</option><option value="Usme">USME</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Video Inversionista</label>
																			</td>
																			<td width="30%">';
																				$disabled = '';
																				if($usuario['tpc_rol_usuario'] == 0){//SI ES PLAN FREE LE BLOQUEAMOS EL VIDEO
																					$disabled = ' disabled="disabled"';
																				}
																			echo '<input type="text" name="tpc_videoinversionista_establecimiento" id="tpc_videoinversionista_establecimiento" value="'.$tp_establecimientos['tpc_videoinversionista_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Video Inversionista..." required="required" '.$disabled.'>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Video Fundación</label>
																			</td>
																			<td width="30%">';
																				$disabled = '';
																				if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
																					$disabled = ' disabled="disabled"';
																				}
																			echo '	<input type="text" name="tpc_videofundacion_establecimiento" id="tpc_videofundacion_establecimiento" value="'.$tp_establecimientos['tpc_videofundacion_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Video Fundación..." required="required" '.$disabled.'>
																			</td>
																		</tr>
																		<tr>
																			<td align="center" colspan="4">NOTA: Se recomienda que el(los) video(s) no superen los 20 segundos de duraci&oacute;n</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Apertura Dia Hábil</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_aperturahabil_establecimiento" id="tpc_aperturahabil_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_aperturahabil_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_aperturahabil_establecimiento'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Cierre Dia Hábil</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_cierrehabil_establecimiento" id="tpc_cierrehabil_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_cierrehabil_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_cierrehabil_establecimiento'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Apertura Fin de Semana</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_aperturafinsemana_establecimiento" id="tpc_aperturafinsemana_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_aperturafinsemana_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_aperturafinsemana_establecimiento'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Cierre Fin de Semana</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_cierrefinsemana_establecimiento" id="tpc_cierrefinsemana_establecimiento" class="form-control">
																					<option value="'.$tp_establecimientos['tpc_cierrefinsemana_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_cierrefinsemana_establecimiento'].'</option>';
																					for($i = 0; $i <= 23; $i++){
																						$val = $i;
																						if($i < 10){$val = '0'.$i;}
																						echo '<option value="'.$val.':00">'.$val.':00</option>';
																					}
																			echo '</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Sitio Web</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_sitioweb_establecimiento" id="tpc_sitioweb_establecimiento" value="'.$tp_establecimientos['tpc_sitioweb_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Sitio Web...." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Facebook</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_facebook_establecimiento" id="tpc_facebook_establecimiento" value="'.$tp_establecimientos['tpc_facebook_establecimiento'].'" class="form-control" style="width: 100%;" placeholder="Facebook..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">¿Hace Domicilios?</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_hacedomicilios_establecimiento" id="tpc_hacedomicilios_establecimiento" class="form-control" required="required">
																					<option value="'.$tp_establecimientos['tpc_hacedomicilios_establecimiento'].'" selected="selected">'.$tp_establecimientos['tpc_hacedomicilios_establecimiento'].'</option>
																					<option value="Si">Si</option>
																					<option value="No">No</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valor Domicilio</label>
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_valordomicilio_establecimiento" id="tpc_valordomicilio_establecimiento" value="'.$tp_establecimientos['tpc_valordomicilio_establecimiento'].'" class="form-control" min="0" style="width: 100%;" placeholder="Valor Domicilio..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<label class="login2">Estrato: </label>
																				<select id="tpc_estrato_establecimiento" style="width: 150px;" class="form-control" name="tpc_estrato_establecimiento" required="required">
																					<option value="'.$tp_establecimientos['tpc_estrato_establecimiento'].'">'.$tp_establecimientos['tpc_estrato_establecimiento'].'</option>
																					<option value="1">1</option>
																					<option value="2">2</option>
																					<option value="3">3</option>
																					<option value="4">4</option>
																					<option value="5">5</option>
																					<option value="6">6</option>
																				 </select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="tpc_codigo_establecimiento" id="tpc_codigo_establecimiento" value="'.$_GET['tpc_codigo_establecimiento'].'">
																				<input type="hidden" name="opc" id="opc" value="editar">
																				<input type="hidden" name="tipo" id="tipo" value="contacto">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Establecimiento</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'importar':
															echo '
															<a href="./videos/Formato e edisión masiva estableciminetos.xlsx" target="_blank">Descargar formato EXCEL importación masiva</a>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																	<input type="hidden" name="opc" id="opc" value="importar">
																	<input type="hidden" name="tipo" id="tipo" value="contacto">
																	Importar Establecimientos: <br>
																	<input type="file" name="archivo" id="archivo" class="btn btn-default" style="width: 310px;" required="required"><br><br>
																	
																	<input type="submit" class="btn btn-primary btn-sm" value="Importar">
																</form>
															';
															/*
															Recuerda que el archivo debe tener en cada columna el titulo, y el siguiente orden de columnas: <br><br>
																	1. Nombre Establecimiento <br>
																	2. Categoria Establecimiento <br>
																	3. Telefono Particular Establecimiento <br>
																	4. Telefono Movil Identificación <br>
																	5. Email <br>';
																	if($usuario['tpc_rol_usuario'] == 2){
																		echo '6. Asignado A <br>';$i = 6;
																	}else{
																		$i = 5;
																	}
																	echo '
																	'.($i+1).'. Tipo Documento (NIT, CC, CE) <br>
																	'.($i+2).'. # Documento <br>
																	'.($i+3).'. Dirección <br>
																	'.($i+4).'. Pais <br>
																	'.($i+5).'. Departamento <br>
																	'.($i+6).'. Ciudad <br>
																	'.($i+7).'. Localidad <br>
																	'.($i+8).'. Video Fundación <br>
																	'.($i+9).'. Video Inversionista <br>
																	'.($i+10).'. Latitud <br>
																	'.($i+11).'. Longitud <br>
																	'.($i+12).'. Apertura Dia Hábil <br>
																	'.($i+13).'. Cierre Dia Hábil <br>
																	'.($i+14).'. Apertura Fin de Semana <br>
																	'.($i+15).'. Cierre Fin de Semana <br>
																	'.($i+16).'. Sitio Web <br>
																	'.($i+17).'. Facebook <br>
																	'.($i+18).'. ¿Hace Domicilios? ("Si" o "No") <br>
																	'.($i+19).'. Valor Domicilio <br><br> */
														break;
													}
												}
												if($_GET['tipo'] == 'promocion'){
													if($usuario['tpc_rol_usuario'] == 0){
														header('Location: index.php');
														exit();
													}
													switch($_GET['opc']){
														case 'nuevo':
															echo '<div class="basic-login-inner">
																<h3>Nueva Promoción</h3>
																<p>Ingresa todos los datos de la nueva promoción</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(1)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_documento" id="tpc_archivo_documento" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_documento" id="tpc_nombre_documento" value="" maxlength="15" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Descripción:</label>
																			</td>
																			<td width="30%" align="center">
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_documento" name="tpc_descripcion_documento" required="required" maxlength="100"></textarea><br>NOTA: Dejar texto de descripción SIN tildes
																			</td>
																			<td width="20%" align="right">
																				<label class="login2">Valor Sin Descuento</label>&nbsp;<br><br><br>
																				<label class="login2">% Promocion</label>&nbsp;
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_valor_documento" id="tpc_valor_documento" value="" class="form-control" style="width: 100%;" placeholder="Valor..." min="1" required="required" onkeyup="valortotal_promo(this.value, tpc_porcentajedesc_documento.value)"><br>
																				<select name="tpc_porcentajedesc_documento" id="tpc_porcentajedesc_documento" class="form-control" style="width: 100%" required="required" onchange="valortotal_promo(tpc_valor_documento.value, this.value.value)">
																					<option value="">Seleccione....</option>';
																					for($i = 1; $i <= 80; $i++){
																						echo '<option value="'.$i.'">'.$i.' %</option>';
																					}
																				echo '</select><br>
																				<div id="totalpromo">
																				
																				</div>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_documento" id="tpc_validodesde_documento" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_documento" id="tpc_validohasta_documento" value="" class="form-control" style="width: 100%;" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%" align="center">';
																				if($usuario['tpc_rol_usuario'] == 2){
																					echo '
																					<select name="tpc_asignadoa_documento" id="tpc_asignadoa_documento" class="form-control" style="width: 100%">
																						<option value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'].'</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																					echo '</select>';
																				}else{
																					echo '<input type="hidden" id="tpc_asignadoa_documento" name="tpc_asignadoa_documento" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																				}
																		echo '	</td>
																			<td width="20%" align="center">
																				<label class="login2">Codigo Promoción</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_codigo_documento" id="tpc_codigo_documento" value="" class="form-control" style="width: 100%;" placeholder="Codigo Promoción..." required="required">
																			</td>
																		</tr>
																		<tr>
																			<td width="20%" align="center">
																				<label class="login2">Departamento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Los Departamentos</option>';
																					//}
																					echo '<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Ciudad</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Las Ciudades</option>';
																					//}
																					echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="opc" id="opc" value="nuevo">
																				<input type="hidden" name="tipo" id="tipo" value="promocion">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Promoción</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'editar':
															$tp_documentos = reg('tp_documentos', 'tpc_id_documento', $_GET['tpc_id_documento']);
															$asignadoa = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_documentos['tpc_asignadoa_documento']);
															echo '<div class="basic-login-inner">
																<h3>Editar Promoción</h3>
																<p>Ingresa todos los datos de la promoción</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(1)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen (Opcional)</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_documento" id="tpc_archivo_documento" class="form-control">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_documento" id="tpc_nombre_documento" value="'.$tp_documentos['tpc_nombre_documento'].'" maxlength="15" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Descripción:</label>
																			</td>
																			<td width="30%" align="center">
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_documento" name="tpc_descripcion_documento" required="required" maxlength="100">'.$tp_documentos['tpc_descripcion_documento'].'</textarea><br>NOTA: Dejar texto de descripción SIN tildes
																			</td>
																			<td width="20%" align="right">
																				<label class="login2">Valor Sin Descuento</label>&nbsp;<br><br><br>
																				<label class="login2">% Promocion</label>&nbsp;
																			</td>
																			<td width="30%">
																				<input type="number" name="tpc_valor_documento" id="tpc_valor_documento" value="'.$tp_documentos['tpc_valor_documento'].'" class="form-control" style="width: 100%;" placeholder="Valor..." min="1" required="required" onkeyup="valortotal_promo(this.value, tpc_porcentajedesc_documento.value)"><br>
																				<select name="tpc_porcentajedesc_documento" id="tpc_porcentajedesc_documento" class="form-control" style="width: 100%" required="required" onchange="valortotal_promo(tpc_valor_documento.value, this.value.value)">
																					<option value="'.$tp_documentos['tpc_porcentajedesc_documento'].'">'.$tp_documentos['tpc_porcentajedesc_documento'].' %</option>';
																					for($i = 1; $i <= 80; $i++){
																						echo '<option value="'.$i.'">'.$i.' %</option>';
																					}
																				echo '</select>
																				<div id="totalpromo">
																				
																				</div>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_documento" id="tpc_validodesde_documento" value="'.date("Y-m-d", strtotime($tp_documentos['tpc_validodesde_documento'])).'" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_documento" id="tpc_validohasta_documento" value="'.date("Y-m-d", strtotime($tp_documentos['tpc_validohasta_documento'])).'" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%" align="center">';
																				if($usuario['tpc_rol_usuario'] == 2){
																					echo '
																					<select name="tpc_asignadoa_documento" id="tpc_asignadoa_documento" class="form-control" style="width: 100%;">
																						<option value="'.$asignadoa['tpc_codigo_usuario'].'">'.$asignadoa['tpc_nickname_usuario'].'</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																					echo '</select><br>';
																				}else{
																					echo '<input type="hidden" id="tpc_asignadoa_documento" name="tpc_asignadoa_documento" value="'.$asignadoa['tpc_codigo_usuario'].'">'.$asignadoa['tpc_nickname_usuario'];
																				}
																		echo '	</td>
																			<td width="20%" align="center">
																				<label class="login2">Codigo Promoción</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_codigo_documento" id="tpc_codigo_documento" value="'.$tp_documentos['tpc_codigo_documento'].'" class="form-control" style="width: 100%;" placeholder="Codigo Promoción..." required="required">
																			</td>
																		</tr>
																		
																			<tr>
																				<td width="20%" align="center">
																					<label class="login2">Departamento</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_departamento_documento" id="tpc_departamento_documento" class="form-control" required="required">
																						<option value="'.$tp_documentos['tpc_departamento_documento'].'" selected="selected">'.$tp_documentos['tpc_departamento_documento'].'</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Los Departamentos</option>';
																						//}
																						echo '<option value="Amazonas">Amazonas</option>
																						<option value="Antioquia">Antioquia</option>
																						<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																						<option value="Atlantico">Atlantico</option>
																						<option value="Bogota D.C">Bogota D.C</option>
																						<option value="Bolivar">Bolivar</option>
																						<option value="Boyaca">Boyaca</option>
																						<option value="Caldas">Caldas</option>
																						<option value="Caqueta">Caqueta</option>
																						<option value="Casanare">Casanare</option>
																						<option value="Cauca">Cauca</option>
																						<option value="Cesar">Cesar</option>
																						<option value="Choco">Choco</option>
																						<option value="Cordoba">Cordoba</option>
																						<option value="Curdimanarca">Curdimanarca</option>
																						<option value="Guainia">Guainia</option>
																						<option value="Guaviare">Guaviare</option>
																						<option value="Huila">Huila</option>
																						<option value="La Guajira">La Guajira</option>
																						<option value="Magdalena">Magdalena</option>
																						<option value="Meta">Meta</option>
																						<option value="Nariño">Nariño</option>
																						<option value="Norte De Santander">Norte De Santander</option>
																						<option value="Putumayo">Putumayo</option>
																						<option value="Quindio">Quindio</option>
																						<option value="Risaralda">Risaralda</option>
																						<option value="Santander">Santander</option>
																						<option value="Sucre">Sucre</option>
																						<option value="Tolima">Tolima</option>
																						<option value="Valle Del Cauca">Valle Del Cauca</option>
																						<option value="Vaupes">Vaupes</option>
																						<option value="Vichada">Vichada</option>
																					</select>
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Ciudad</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_ciudad_documento" id="tpc_ciudad_documento" class="form-control" required="required">
																						<option value="'.$tp_documentos['tpc_ciudad_documento'].'" selected="selected">'.$tp_documentos['tpc_ciudad_documento'].'</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Las Ciudades</option>';
																						//}
																						echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																					</select>
																				</td>
																			</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="tpc_id_documento" id="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																				<input type="hidden" name="opc" id="opc" value="editar">
																				<input type="hidden" name="tipo" id="tipo" value="promocion">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Promoción</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'verdetalle':
															?>	
																<style>
																	.tab {
																	  overflow: hidden;
																	  border: 1px solid #ccc;
																	  background-color: #f1f1f1;
																	}

																	.tab button {
																	  background-color: inherit;
																	  float: left;
																	  border: none;
																	  outline: none;
																	  cursor: pointer;
																	  padding: 14px 16px;
																	  transition: 0.3s;
																	  font-size: 17px;
																	}

																	.tab button:hover {
																	  background-color: #ddd;
																	}

																	.tab button.active {
																	  background-color: #ccc;
																	}

																	.tabcontent {
																	  display: none;
																	  padding: 6px 12px;
																	  border: 1px solid #ccc;
																	  border-top: none;
																	}
																</style>
																<div class="tab">
																  <button class="tablinks" onclick="openCity(event, 'Detalle')">Detalle</button>
																  <button class="tablinks" onclick="openCity(event, 'Establecimientos')">Establecimientos</button>
																  <button class="tablinks" onclick="openCity(event, 'Calificaciones')">Calificaciones</button>
																</div>
																<div id="Detalle" class="tabcontent">
																	<?php
																		$estado = array("Inactivo", "Activo");
																		$tp_documentos = reg('tp_documentos', 'tpc_id_documento', $_GET['tpc_id_documento']);
																		$asignadoa = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_documentos['tpc_asignadoa_documento']);
																		echo '
																		<table style="width:500px;">
																			<tr>
																				<td><b>Nombre: </b></td>
																				<td>'.$tp_documentos['tpc_nombre_documento'].'</td>
																			</tr>
																			<tr>
																				<td><b>Valido: </b></td>
																				<td>Desde '.$tp_documentos['tpc_validodesde_documento'].' A '.$tp_documentos['tpc_validohasta_documento'].'</td>
																			</tr>
																			<tr>
																				<td><b>Asignado a: </b></td>
																				<td>'.$asignadoa['tpc_nombres_usuario'].' '.$asignadoa['tpc_apellidos_usuario'].' ('.$asignadoa['tpc_nickname_usuario'].')</td>
																			</tr>
																			<tr>
																				<td><b>Estado: </b></td>
																				<td>'.$estado[$tp_documentos['tpc_estado_documento']].'</td>
																			</tr>
																			<tr>
																				<td><b>Imagen: </b></td>
																				<td><img src="'.$tp_documentos['tpc_archivo_documento'].'" height="250px" width="250px"></td>
																			</tr>
																		</table>';
																	?>
																</div>
																<div id="Establecimientos" class="tabcontent">
																	<?php
																		$con->query("SELECT * FROM tp_documentos INNER JOIN tp_documentos_establecimientos ON tpc_documento_docuestab=tpc_id_documento INNER JOIN tp_establecimientos ON tpc_codigo_establecimiento=tpc_establecimientos_docuestab AND tpc_id_documento='".$_GET['tpc_id_documento']."' ORDER BY tpc_codigo_establecimiento;");
																		if($con->num_rows() > 0){
																			?>
																			<form name="reportecontactos" id="reportecontactos">
																				<input type="hidden" id="tipo" name="tipo" value="promocion">
																				<input type="hidden" id="opc" name="opc" value="desvincularseleccion">
																				<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="false" data-show-columns="false" data-show-pagination-switch="true" data-show-refresh="false" data-key-events="false" data-show-toggle="true" data-resizable="false" data-cookie="false" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
																					<thead>
																						<tr>
																							<th data-field="state"><center><input type="checkbox" id="checktodos" name="checktodos" value="1" onclick="marcardesmarcar(this.checked)"></center></th>
																							<th data-field="Nombre"><center>Nombre</center></th>
																							<th data-field="Archivo"><center>Dirección</center></th>
																							<th data-field="Asignado"><center>Ciudad</center></th>
																							<th data-field="Editar"><center>Departamento</center></th>
																							<th data-field="Detalle"><center>Categoria</center></th>
																							<th data-field="Activar"><center>Desvincular</center></th>
																						</tr>
																					</thead>
																				<?php
																				$contador = 0;
																				echo '
																				<tbody>';
																					while($con->next_record()){
																						$contador++;
																						if($con->f("tpc_estado_documento") == 0){$activo='<i class="fa fa-check-square-o">';}else{$activo='<i class="fa fa-check-square">';}
																						echo '<tr>
																							<td><center><input type="checkbox" id="checkespecifico'.$contador.'" name="checkespecifico'.$contador.'" value="'.$con->f("tpc_establecimientos_docuestab").'"></center></td>
																							<td><center><label>'.$con->f("tpc_nombre_establecimiento").'</label></center></td>
																							<td><center><label>'.$con->f("tpc_direccion_establecimiento").'</center></td>
																							<td><center><label>'.$con->f("tpc_ciudad_establecimiento").'</label></center></td>
																							<td><center><label>'.$con->f("tpc_departamento_establecimiento").'</label></center></td>
																							<td><center><label>'.$con->f("tpc_categoria_establecimiento").'</label></center></td>
																							<td><center><a href="gestContenido.php?tpc_id_documento='.$con->f("tpc_id_documento").'&tpc_codigo_establecimiento='.$con->f("tpc_codigo_establecimiento").'&opc=desvincular&tipo=promocion">'.$activo.' '.$estados[$con->f("tpc_estado_usuario")].'</a></center></td>
																						</tr>';
																					}
																				echo '
																				</tbody></table><br><br>
																				<table style="width:100%">
																					<tr>
																						<td align="center">
																							<input type="hidden" name="contadordocumentos" id="contadordocumentos" value="'.$contador.'">
																							<input type="hidden" name="tpc_id_documento" id="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																							
																							<input type="submit" class="btn btn-sm btn-primary login-submit-cs" value="Desvincular Seleccion">
																						</td>
																					</tr>
																				</table>
																			</form>
																			<br>
																			<center>
																				<table style="width:100%">
																					<tr>
																						<td align="center">
																							<form method="get" action="gestContenido.php">
																								<input type="hidden" id="tipo" name="tipo" value="promocion">
																								<input type="hidden" id="opc" name="opc" value="desvinculartodo">
																								<input type="hidden" id="tpc_id_documento" name="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																								<input type="submit" class="btn btn-sm btn-primary login-submit-cs" value="Desvincular Todo">
																							</form>
																						</td>
																					</tr>
																				</table>
																			</center>';
																		}else{
																			echo '<center>No se encontraron establecimientos asociados</center>';
																		}
																	?>
																</div>
																<div id="Calificaciones" class="tabcontent">
																	<?php
																		$con->query("SELECT * FROM tp_documentos INNER JOIN tp_documentos_calificacion ON tpc_documento_calificacion=tpc_id_documento AND tpc_id_documento='".$_GET['tpc_id_documento']."';");
																		if($con->num_rows() > 0){
																			?>
																			<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="false" data-show-columns="false" data-show-pagination-switch="true" data-show-refresh="false" data-key-events="false" data-show-toggle="true" data-resizable="false" data-cookie="false" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
																				<thead>
																					<tr>
																						<th data-field="Fecha"><center>Fecha</center></th>
																						<th data-field="Calificacion"><center>Calificación</center></th>
																					</tr>
																				</thead>
																			<?php
																			$contador = 0;$sumatoria = 0;
																			echo '
																			<tbody>';
																				while($con->next_record()){
																					$contador++;
																					$sumatoria += $con->f("tpc_calificacion_calificacion");
																					if($con->f("tpc_estado_documento") == 0){$activo='<i class="fa fa-check-square-o">';}else{$activo='<i class="fa fa-check-square">';}
																					echo '
																					<tr>
																						<td><center><label>'.$con->f("tpc_fechahora_calificacion").'</label></center></td>
																						<td><center><label>'.$con->f("tpc_calificacion_calificacion").'</center></td>
																					</tr>';
																				}
																			echo'
																			</tbody></table><br><br><h3>Calificación Promedio: '.number_format($sumatoria / $contador, 1, ",", ".").'</h3>';
																		}else{
																			echo '<center>No existen calificaciones actualmente</center>';
																		}
																	?>
																</div>
																<script>
																	openCity(event, 'Detalle');
																	function openCity(evt, cityName) {
																		var i, tabcontent, tablinks;
																		tabcontent = document.getElementsByClassName("tabcontent");
																		for (i = 0; i < tabcontent.length; i++) {
																			tabcontent[i].style.display = "none";
																		}
																		tablinks = document.getElementsByClassName("tablinks");
																		for (i = 0; i < tablinks.length; i++) {
																			tablinks[i].className = tablinks[i].className.replace(" active", "");
																		}
																		document.getElementById(cityName).style.display = "block";
																		evt.currentTarget.className += " active";
																	}
																</script>
															<?php
														break;
														case 'activacion':
															$tp_documentos = reg('tp_documentos', 'tpc_id_documento', $_GET['tpc_id_documento']);
															if($tp_documentos['tpc_estado_documento'] == 1 || $tp_documentos['tpc_estado_documento'] == "1"){
																$con->query("UPDATE tp_documentos SET tpc_estado_documento='0' WHERE tpc_id_documento='".$_GET['tpc_id_documento']."';");
																$mensaje = 'Promoción InActivada Correctamente';
															}else{
																$con->query("UPDATE tp_documentos SET tpc_estado_documento='1' WHERE tpc_id_documento='".$_GET['tpc_id_documento']."';");
																$mensaje = 'Promoción Activada Correctamente';
															}
															echo '
															<form name="finality" method="post" action="promociones.php">
																<input type="hidden" name="mensaje" value="'.$mensaje.'">
															</form>
															<script type="text/javascript">
																alert("'.$mensaje.'");
																finality.submit();
															</script>';
														break;
														case 'desvincular':
															$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_establecimientos_docuestab='".$_GET['tpc_codigo_establecimiento']."' AND tpc_documento_docuestab='".$_GET['tpc_id_documento']."';");
															echo '
															<form name="finality" method="get" action="gestContenido.php">
																<input type="hidden" name="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																<input type="hidden" name="opc" value="verdetalle">
																<input type="hidden" name="tipo" value="promocion">
															</form>
															<script type="text/javascript">
																finality.submit();
															</script>';
															exit();
														break;
														case 'desvincularseleccion':
															for($i=1;$i <= $_GET['contadordocumentos'];$i++){
																if(isset($_GET['checkespecifico'.$i])){
																	$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_establecimientos_docuestab='".$_GET['checkespecifico'.$i]."' AND tpc_documento_docuestab='".$_GET['tpc_id_documento']."';");
																}
															}
															echo '
															<form name="finality" method="get" action="gestContenido.php">
																<input type="hidden" name="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																<input type="hidden" name="opc" value="verdetalle">
																<input type="hidden" name="tipo" value="promocion">
															</form>
															<script type="text/javascript">
																finality.submit();
															</script>';
															exit();
														break;
														case 'desvinculartodo':
															$con->query("DELETE FROM tp_documentos_establecimientos WHERE tpc_documento_docuestab='".$_GET['tpc_id_documento']."';");
															echo '
															<form name="finality" method="get" action="gestContenido.php">
																<input type="hidden" name="tpc_id_documento" value="'.$_GET['tpc_id_documento'].'">
																<input type="hidden" name="opc" value="verdetalle">
																<input type="hidden" name="tipo" value="promocion">
															</form>
															<script type="text/javascript">
																finality.submit();
															</script>';
															exit();
														break;
													}
												}
												
												if($_GET['tipo'] == 'promocionaliado'){
													if($usuario['tpc_rol_usuario'] != -3 && $usuario['tpc_rol_usuario'] != -2 && $usuario['tpc_rol_usuario'] != -1 && $usuario['tpc_rol_usuario'] != 2 && $usuario['tpc_rol_usuario'] != -3){
														header('Location: index.php');
														exit();
													}
													switch($_GET['opc']){
														case 'nuevo':
															echo '<div class="basic-login-inner">
																<h3>Nueva Promoción Aliado</h3>
																<p>Ingresa todos los datos de la nueva promoción</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(3)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_promociones_aliados" id="tpc_archivo_promociones_aliados" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_promociones_aliados" id="tpc_nombre_promociones_aliados" value="" maxlength="25" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Descripción:</label>
																			</td>
																			<td width="30%" align="center">
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_promociones_aliados" name="tpc_descripcion_promociones_aliados" required="required"></textarea>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%" align="center">';
																				if($usuario['tpc_rol_usuario'] == 2){
																					echo '
																					<select name="tpc_usuario_promociones_aliados" id="tpc_usuario_promociones_aliados" class="form-control" style="width: 100%" required="required">
																						<option value="">Seleccione....</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' AND (tpc_rol_usuario='-1' OR tpc_rol_usuario='-2') ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																					echo '</select>';
																				}else{
																					echo '<input type="hidden" id="tpc_usuario_promociones_aliados" name="tpc_usuario_promociones_aliados" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																				}
																		echo '	</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_promociones_aliados" id="tpc_validodesde_promociones_aliados" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_promociones_aliados" id="tpc_validohasta_promociones_aliados" value="" class="form-control" style="width: 100%;" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">URL (Ver más)</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_url_promociones_aliados" id="tpc_url_promociones_aliados" class="form-control" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="opc" id="opc" value="nuevo">
																				<input type="hidden" name="tipo" id="tipo" value="promocionaliado">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Promoción</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'editar':
															$tp_promociones_aliados = reg('tp_promociones_aliados', 'tpc_codigo_promociones_aliados', $_GET['tpc_codigo_promociones_aliados']);
															$asignadoa = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_promociones_aliados['tpc_usuario_promociones_aliados']);
															echo '<div class="basic-login-inner">
																<h3>Editar Promoción Aliado</h3>
																<p>Ingresa todos los datos de la promoción</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(3)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen (Opcional)</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_promociones_aliados" id="tpc_archivo_promociones_aliados" class="form-control">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_promociones_aliados" id="tpc_nombre_promociones_aliados" value="'.$tp_promociones_aliados['tpc_nombre_promociones_aliados'].'" maxlength="25" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Descripción:</label>
																			</td>
																			<td width="30%" align="center">
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_promociones_aliados" name="tpc_descripcion_promociones_aliados" required="required">'.$tp_promociones_aliados['tpc_descripcion_promociones_aliados'].'</textarea>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%" align="center">';
																				if($usuario['tpc_rol_usuario'] == 2){
																					echo '
																					<select name="tpc_usuario_promociones_aliados" id="tpc_usuario_promociones_aliados" class="form-control" style="width: 100%;">
																						<option value="'.$asignadoa['tpc_codigo_usuario'].'">'.$asignadoa['tpc_nickname_usuario'].'</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' AND (tpc_rol_usuario='-1' OR tpc_rol_usuario='-2') ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																					echo '</select><br>';
																				}else{
																					echo '<input type="hidden" id="tpc_usuario_promociones_aliados" name="tpc_usuario_promociones_aliados" value="'.$asignadoa['tpc_codigo_usuario'].'">'.$asignadoa['tpc_nickname_usuario'];
																				}
																		echo '	</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_promociones_aliados" id="tpc_validodesde_promociones_aliados" value="'.date("Y-m-d", strtotime($tp_promociones_aliados['tpc_validodesde_promociones_aliados'])).'" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_promociones_aliados" id="tpc_validohasta_promociones_aliados" value="'.date("Y-m-d", strtotime($tp_promociones_aliados['tpc_validohasta_promociones_aliados'])).'" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">URL (Ver más)</label>
																			</td>
																			<td width="30%">
																				<input type="text" value="'.$tp_promociones_aliados['tpc_url_promociones_aliados'].'" name="tpc_url_promociones_aliados" id="tpc_url_promociones_aliados" class="form-control" required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="tpc_codigo_promociones_aliados" id="tpc_codigo_promociones_aliados" value="'.$_GET['tpc_codigo_promociones_aliados'].'">
																				<input type="hidden" name="opc" id="opc" value="editar">
																				<input type="hidden" name="tipo" id="tipo" value="promocionaliado">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Promoción</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'activacion':
															$tp_promociones_aliados = reg('tp_promociones_aliados', 'tpc_codigo_promociones_aliados', $_GET['tpc_codigo_promociones_aliados']);
															if($tp_promociones_aliados['tpc_estado_promociones_aliados'] == 1 || $tp_promociones_aliados['tpc_estado_promociones_aliados'] == "1"){
																$con->query("UPDATE tp_promociones_aliados SET tpc_estado_promociones_aliados='0' WHERE tpc_codigo_promociones_aliados='".$_GET['tpc_codigo_promociones_aliados']."';");
																$mensaje = 'Promoción InActivada Correctamente';
															}else{
																$con->query("UPDATE tp_promociones_aliados SET tpc_estado_promociones_aliados='1' WHERE tpc_codigo_promociones_aliados='".$_GET['tpc_codigo_promociones_aliados']."';");
																$mensaje = 'Promoción Activada Correctamente';
															}
															echo '
															<form name="finality" method="post" action="promocionesaliados.php">
																<input type="hidden" name="mensaje" value="'.$mensaje.'">
															</form>
															<script type="text/javascript">
																alert("'.$mensaje.'");
																finality.submit();
															</script>';
														break;
													}
												}
												
												if($_GET['tipo'] == 'banner'){
													if(intval($usuario['tpc_rol_usuario']) == 0){
														header('Location: index.php');
														exit();
													}
													
													switch($_GET['opc']){
														case 'nuevo'://multiple="multiple"
															echo '<div class="basic-login-inner">
																<h3>Nuevo Banner '.$_GET['tipoban'].'</h3>
																<p>Ingresa todos los datos del nuevo banner</p>
																<!--<p align="justify"><b><font color="green">NOTA: Puedes subir hasta 6 imagenes, te recomendamos que no supere cada una 1MB</font></b></p>-->
																<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivosimagenes[]" accept=".jpeg,.jpg,.png" id="tpc_archivosimagenes[]" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_banner" id="tpc_nombre_banner" value="" maxlength="15" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Asignado a</label>
																			</td>
																			<td width="30%" align="center">';
																				if($usuario['tpc_rol_usuario'] == 2){
																					echo '
																					<select name="tpc_usuario_banner" id="tpc_usuario_banner" class="form-control" style="width: 100%">
																						<option value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'].'</option>';
																						$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$mkid."' ORDER BY tpc_nombres_usuario;");
																						while($con->next_record()){
																							echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																						}
																					echo '</select>';
																				}else{
																					echo '<input type="hidden" id="tpc_usuario_banner" name="tpc_usuario_banner" value="'.$mkid.'">'.$usuario['tpc_nickname_usuario'];
																				}
																		echo '</td>
																		</tr>';
																		
																			echo '
																			<tr>
																				<td width="20%" align="center">
																					<label class="login2">Departamento</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																						<option value="" selected="selected">Seleccione....</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Los Departamentos</option>';
																						//}
																						echo '<option value="Amazonas">Amazonas</option>
																						<option value="Antioquia">Antioquia</option>
																						<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																						<option value="Atlantico">Atlantico</option>
																						<option value="Bogota D.C">Bogota D.C</option>
																						<option value="Bolivar">Bolivar</option>
																						<option value="Boyaca">Boyaca</option>
																						<option value="Caldas">Caldas</option>
																						<option value="Caqueta">Caqueta</option>
																						<option value="Casanare">Casanare</option>
																						<option value="Cauca">Cauca</option>
																						<option value="Cesar">Cesar</option>
																						<option value="Choco">Choco</option>
																						<option value="Cordoba">Cordoba</option>
																						<option value="Curdimanarca">Curdimanarca</option>
																						<option value="Guainia">Guainia</option>
																						<option value="Guaviare">Guaviare</option>
																						<option value="Huila">Huila</option>
																						<option value="La Guajira">La Guajira</option>
																						<option value="Magdalena">Magdalena</option>
																						<option value="Meta">Meta</option>
																						<option value="Nariño">Nariño</option>
																						<option value="Norte De Santander">Norte De Santander</option>
																						<option value="Putumayo">Putumayo</option>
																						<option value="Quindio">Quindio</option>
																						<option value="Risaralda">Risaralda</option>
																						<option value="Santander">Santander</option>
																						<option value="Sucre">Sucre</option>
																						<option value="Tolima">Tolima</option>
																						<option value="Valle Del Cauca">Valle Del Cauca</option>
																						<option value="Vaupes">Vaupes</option>
																						<option value="Vichada">Vichada</option>
																					</select>
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Ciudad</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																						<option value="" selected="selected">Seleccione....</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Las Ciudades</option>';
																						//}
																						echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																					</select>
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Valido Desde</label>
																				</td>
																				<td width="30%">
																					<input type="date" name="tpc_validodesde_banner" id="tpc_validodesde_banner" class="form-control" required="required">
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Valido Hasta</label>
																				</td>
																				<td width="30%">
																					<input type="date" name="tpc_validohasta_banner" id="tpc_validohasta_banner" value="" class="form-control" style="width: 100%;" required="required">
																				</td>
																			</tr>';
																			if($_GET['tipoban'] != 'principal' && $usuario['tpc_rol_usuario'] != 1){
																				echo '<tr style="height:60px;">
																					<td colspan="4" align="center">
																						<label class="login2">Categoria</label>
																						<select class="form-control-sm" name="tpc_categoria_establecimiento" required="required">
																							<option selected="selected" value="">Seleccione....</option>';
																							if(intval($usuario['tpc_rol_usuario']) == 2){
																								echo '<option value="todas">Todas Las Categorias</option>';
																							}
																							echo '<option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
																							<option value="ESTACIONES DE SERVICIO">ESTACIONES DE SERVICIO</option>
																							<option value="HOGAR Y MISCELANEAS">HOGAR Y MISCELANEAS</option>
																							<option value="FARMACIAS">FARMACIAS</option>
																							<option value="GASTRONOMIA">GASTRONOMIA</option>
																							<option value="ROPA Y ACCESORIOS">ROPA Y ACCESORIOS</option>
																							<option value="SALUD Y BELLEZA">SALUD Y BELLEZA</option>
																							<option value="SUPERMERCADOS Y TIENDAS">SUPERMERCADOS Y TIENDAS</option>
																						</select>
																					</td>
																				</tr>';
																			}
																		echo '<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="opc" id="opc" value="nuevo">
																				<input type="hidden" name="tipo" id="tipo" value="banner">
																				<input type="hidden" name="tipoban" id="tipoban" value="'.$_GET['tipoban'].'">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Banner</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'editar':
															if($_GET['tipoban'] == 'principal'){// multiple="multiple" 
																$tp_banner = reg('tp_banner', 'tpc_codigo_banner', $_GET['tpc_codigo_banner']);
																$tp_usuarios = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_banner['tpc_usuario_banner']);
																echo '<div class="basic-login-inner">
																	<h3>Editar Banner</h3>
																	<p>Ingresa todos los datos del banner</p>
																	<!--<p align="justify"><b><font color="green">NOTA: Puedes subir hasta 6 imagenes, te recomendamos que no supere cada una 1MB</font></b></p>-->
																	<p align="justify"><b><font color="green">NOTA: La imagen subida en la edición reemplazará a la actual</font></b></p>
																	<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																		<table width="100%">
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Imagen(es)</label>
																				</td>
																				<td width="30%">
																					<input type="file" name="tpc_archivosimagenes[]" accept=".jpeg,.jpg,.png" id="tpc_archivosimagenes[]" class="form-control" required="required">
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Nombre</label>
																				</td>
																				<td width="30%">
																					<input type="text" value="'.$tp_banner['tpc_nombre_banner'].'" name="tpc_nombre_banner" id="tpc_nombre_banner" value="" maxlength="15" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Asignado a</label>
																				</td>
																				<td width="30%" align="center">';
																					if($usuario['tpc_rol_usuario'] == 2){
																						echo '
																						<select name="tpc_usuario_banner" id="tpc_usuario_banner" class="form-control" style="width: 100%">
																							<option value="'.$tp_banner['tpc_usuario_banner'].'">'.$tp_usuarios['tpc_nickname_usuario'].'</option>';
																							$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$tp_banner['tpc_usuario_banner']."' ORDER BY tpc_nombres_usuario;");
																							while($con->next_record()){
																								echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																							}
																						echo '</select>';
																					}else{
																						echo '<input type="hidden" id="tpc_usuario_banner" name="tpc_usuario_banner" value="'.$tp_usuarios['tpc_codigo_usuario'].'">'.$tp_usuarios['tpc_nickname_usuario'];
																					}
																			echo '
																			<tr>
																				<td width="20%" align="center">
																					<label class="login2">Departamento</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																						<option value="'.$tp_banner['tpc_departamento_banner'].'" selected="selected">'.$tp_banner['tpc_departamento_banner'].'</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Los Departamentos</option>';
																						//}
																						echo '<option value="Amazonas">Amazonas</option>
																						<option value="Antioquia">Antioquia</option>
																						<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																						<option value="Atlantico">Atlantico</option>
																						<option value="Bogota D.C">Bogota D.C</option>
																						<option value="Bolivar">Bolivar</option>
																						<option value="Boyaca">Boyaca</option>
																						<option value="Caldas">Caldas</option>
																						<option value="Caqueta">Caqueta</option>
																						<option value="Casanare">Casanare</option>
																						<option value="Cauca">Cauca</option>
																						<option value="Cesar">Cesar</option>
																						<option value="Choco">Choco</option>
																						<option value="Cordoba">Cordoba</option>
																						<option value="Curdimanarca">Curdimanarca</option>
																						<option value="Guainia">Guainia</option>
																						<option value="Guaviare">Guaviare</option>
																						<option value="Huila">Huila</option>
																						<option value="La Guajira">La Guajira</option>
																						<option value="Magdalena">Magdalena</option>
																						<option value="Meta">Meta</option>
																						<option value="Nariño">Nariño</option>
																						<option value="Norte De Santander">Norte De Santander</option>
																						<option value="Putumayo">Putumayo</option>
																						<option value="Quindio">Quindio</option>
																						<option value="Risaralda">Risaralda</option>
																						<option value="Santander">Santander</option>
																						<option value="Sucre">Sucre</option>
																						<option value="Tolima">Tolima</option>
																						<option value="Valle Del Cauca">Valle Del Cauca</option>
																						<option value="Vaupes">Vaupes</option>
																						<option value="Vichada">Vichada</option>
																					</select>
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Ciudad</label>
																				</td>
																				<td width="30%">
																					<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																						<option value="'.$tp_banner['tpc_ciudad_banner'].'" selected="selected">'.$tp_banner['tpc_ciudad_banner'].'</option>';
																						//if(intval($usuario['tpc_rol_usuario']) == 2){
																							echo '<option value="todas">Todas Las Ciudades</option>';
																						//}
																						echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																					</select>
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Valido Desde</label>
																				</td>
																				<td width="30%">
																					<input type="date" name="tpc_validodesde_banner" id="tpc_validodesde_banner" class="form-control" required="required" value="'.date("Y-m-d", strtotime($tp_banner['tpc_validodesde_banner'])).'">
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Valido Hasta</label>
																				</td>
																				<td width="30%">
																					<input type="date" name="tpc_validohasta_banner" id="tpc_validohasta_banner" class="form-control" style="width: 100%;" required="required" value="'.date("Y-m-d", strtotime($tp_banner['tpc_validohasta_banner'])).'">
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td colspan="4" align="center">
																					<input type="hidden" name="opc" id="opc" value="editar">
																					<input type="hidden" name="tpc_codigo_banner" id="tpc_codigo_banner" value="'.$_GET['tpc_codigo_banner'].'">
																					<input type="hidden" name="tipo" id="tipo" value="banner">
																					<input type="hidden" name="tipoban" id="tipoban" value="'.$_GET['tipoban'].'">
																					<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Banner</button>
																				</td>
																			</tr>
																		</table><br>
																		<center>
																			<table>
																				<tr style="height:60px;">';
																					if($tp_banner['tpc_imagen1_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen1_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																					if($tp_banner['tpc_imagen2_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen2_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																					if($tp_banner['tpc_imagen3_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen3_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																			echo '
																				</tr>
																				<tr style="height:60px;">';
																					if($tp_banner['tpc_imagen4_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen4_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																					if($tp_banner['tpc_imagen5_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen5_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																					if($tp_banner['tpc_imagen6_banner'] != ''){
																						echo '<td><center><img src="'.$tp_banner['tpc_imagen6_banner'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																			echo '
																				</tr>
																			</table>
																		</center>
																	</form>
																</div>';
															}
															if($_GET['tipoban'] == 'secundario'){
																$tp_banner_cat = reg('tp_banner_cat', 'tpc_codigo_banner_cat', $_GET['tpc_codigo_banner_cat']);
																$tp_usuarios = reg('tp_usuarios', 'tpc_codigo_usuario', $tp_banner_cat['tpc_usuario_banner_cat']);
																echo '<div class="basic-login-inner">
																	<h3>Editar Banner</h3>
																	<p>Ingresa todos los datos del banner</p>
																	<p align="justify"><b><font color="green">NOTA: La imagen subida en la edición reemplazará a la actual</font></b></p>
																	<form method="post" action="gestContenido.php" enctype="multipart/form-data">
																		<table width="100%">
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Imagen</label>
																				</td>
																				<td width="30%">
																					<input type="file" name="tpc_archivosimagenes[]" accept=".jpeg,.jpg,.png" id="tpc_archivosimagenes[]" class="form-control" required="required">
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Nombre</label>
																				</td>
																				<td width="30%">
																					<input type="text" value="'.$tp_banner_cat['tpc_nombre_banner_cat'].'" name="tpc_nombre_banner_cat" id="tpc_nombre_banner_cat" value="" maxlength="15" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Asignado a</label>
																				</td>
																				<td width="30%" align="center">';
																					if($usuario['tpc_rol_usuario'] == 2){
																						echo '
																						<select name="tpc_usuario_banner_cat" id="tpc_usuario_banner_cat" class="form-control" style="width: 100%">
																							<option value="'.$tp_banner_cat['tpc_usuario_banner_cat'].'">'.$tp_usuarios['tpc_nickname_usuario'].'</option>';
																							$con->query("SELECT * FROM tp_usuarios WHERE tpc_estado_usuario='1' AND tpc_codigo_usuario != '".$tp_banner_cat['tpc_usuario_banner_cat']."' ORDER BY tpc_nombres_usuario;");
																							while($con->next_record()){
																								echo '<option value="'.$con->f("tpc_codigo_usuario").'">'.$con->f("tpc_nombres_usuario").' '.$con->f("tpc_apellidos_usuario").' ('.$con->f("tpc_nickname_usuario").')</option>';
																							}
																						echo '</select>';
																					}else{
																						echo '<input type="hidden" id="tpc_usuario_banner_cat" name="tpc_usuario_banner_cat" value="'.$tp_usuarios['tpc_codigo_usuario'].'">'.$tp_usuarios['tpc_nickname_usuario'];
																					}
																			if(intval($usuario['tpc_rol_usuario']) != 1){
																				echo '
																				<tr>
																					<td width="20%" align="center">
																						<label class="login2">Departamento</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																							<option value="'.$tp_banner_cat['tpc_departamento_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_departamento_banner_cat'].'</option>';
																							//if(intval($usuario['tpc_rol_usuario']) == 2){
																								echo '<option value="todas">Todas Los Departamentos</option>';
																							//}
																							echo '<option value="Amazonas">Amazonas</option>
																							<option value="Antioquia">Antioquia</option>
																							<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																							<option value="Atlantico">Atlantico</option>
																							<option value="Bogota D.C">Bogota D.C</option>
																							<option value="Bolivar">Bolivar</option>
																							<option value="Boyaca">Boyaca</option>
																							<option value="Caldas">Caldas</option>
																							<option value="Caqueta">Caqueta</option>
																							<option value="Casanare">Casanare</option>
																							<option value="Cauca">Cauca</option>
																							<option value="Cesar">Cesar</option>
																							<option value="Choco">Choco</option>
																							<option value="Cordoba">Cordoba</option>
																							<option value="Curdimanarca">Curdimanarca</option>
																							<option value="Guainia">Guainia</option>
																							<option value="Guaviare">Guaviare</option>
																							<option value="Huila">Huila</option>
																							<option value="La Guajira">La Guajira</option>
																							<option value="Magdalena">Magdalena</option>
																							<option value="Meta">Meta</option>
																							<option value="Nariño">Nariño</option>
																							<option value="Norte De Santander">Norte De Santander</option>
																							<option value="Putumayo">Putumayo</option>
																							<option value="Quindio">Quindio</option>
																							<option value="Risaralda">Risaralda</option>
																							<option value="Santander">Santander</option>
																							<option value="Sucre">Sucre</option>
																							<option value="Tolima">Tolima</option>
																							<option value="Valle Del Cauca">Valle Del Cauca</option>
																							<option value="Vaupes">Vaupes</option>
																							<option value="Vichada">Vichada</option>
																						</select>
																					</td>
																					<td width="20%" align="center">
																						<label class="login2">Ciudad</label>
																					</td>
																					<td width="30%">
																						<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																							<option value="'.$tp_banner_cat['tpc_ciudad_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_ciudad_banner_cat'].'</option>';
																							//if(intval($usuario['tpc_rol_usuario']) == 2){
																								echo '<option value="todas">Todas Las Ciudades</option>';
																							//}
																							echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																						</select>
																					</td>
																				</tr>
																				<tr style="height:60px;">
																					<td colspan="4" align="center">
																						<label class="login2">Categoria</label>
																						<select class="form-control-sm" name="tpc_categoria_establecimiento" required="required">
																							<option selected="selected" value="'.$tp_banner_cat['tpc_categoria_banner_cat'].'">'.$tp_banner_cat['tpc_categoria_banner_cat'].'</option>';
																							if(intval($usuario['tpc_rol_usuario']) == 2){
																								echo '<option value="todas">Todas Las Categorias</option>';
																							}
																							echo '<option value="ENTRETENIMIENTO">ENTRETENIMIENTO</option>
																							<option value="ESTACIONES DE SERVICIO">ESTACIONES DE SERVICIO</option>
																							<option value="HOGAR Y MISCELANEAS">HOGAR Y MISCELANEAS</option>
																							<option value="FARMACIAS">FARMACIAS</option>
																							<option value="GASTRONOMIA">GASTRONOMIA</option>
																							<option value="ROPA Y ACCESORIOS">ROPA Y ACCESORIOS</option>
																							<option value="SALUD Y BELLEZA">SALUD Y BELLEZA</option>
																							<option value="SUPERMERCADOS Y TIENDAS">SUPERMERCADOS Y TIENDAS</option>
																						</select>
																					</td>
																				</tr>';
																			}
																			echo '
																			<tr style="height:60px;">
																				<td width="20%" align="center">
																					<label class="login2">Valido Desde</label>
																				</td>
																				<td width="30%">
																					<input type="date" value="'.date("Y-m-d", strtotime($tp_banner_cat['tpc_validodesde_banner_cat'])).'" name="tpc_validodesde_banner" id="tpc_validodesde_banner" class="form-control" required="required" >
																				</td>
																				<td width="20%" align="center">
																					<label class="login2">Valido Hasta</label>
																				</td>
																				<td width="30%">
																					<input type="date" name="tpc_validohasta_banner"
																					value="'.date("Y-m-d", strtotime($tp_banner_cat['tpc_validohasta_banner_cat'])).'" id="tpc_validohasta_banner" class="form-control" style="width: 100%;" required="required" >
																				</td>
																			</tr>
																			<tr style="height:60px;">
																				<td colspan="4" align="center">
																					<input type="hidden" name="opc" id="opc" value="editar">
																					<input type="hidden" name="tpc_codigo_banner_cat" id="tpc_codigo_banner_cat" value="'.$_GET['tpc_codigo_banner_cat'].'">
																					<input type="hidden" name="tipo" id="tipo" value="banner">
																					<input type="hidden" name="tipoban" id="tipoban" value="'.$_GET['tipoban'].'">
																					<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Banner</button>
																				</td>
																			</tr>
																		</table><br>
																		<center>
																			<table>
																				<tr style="height:60px;">';
																					if($tp_banner_cat['tpc_imagen_banner_cat'] != ''){
																						echo '<td><center><img src="'.$tp_banner_cat['tpc_imagen_banner_cat'].'" style="height: 100px; width: 150px;"></center></td>';
																					}
																			echo '
																				</tr>
																			</table>
																		</center>
																	</form>
																</div>';
															}
														break;
														case 'eliminar':
															if($_GET['tipoban'] == "principal"){
																$tp_banner = reg('tp_banner', 'tpc_codigo_banner', $_GET['tpc_codigo_banner']);
																$archivo = explode("/", $tp_banner['tpc_imagen1_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen2_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen3_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen4_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen5_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$archivo = explode("/", $tp_banner['tpc_imagen6_banner']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$con->query("DELETE FROM tp_banner WHERE tpc_codigo_banner='".$_GET['tpc_codigo_banner']."';");
															}else{
																$tp_banner_cat = reg('tp_banner_cat', 'tpc_codigo_banner_cat', $_GET['tpc_codigo_banner_cat']);
																$archivo = explode("/", $tp_banner_cat['tpc_imagen_banner_cat']);
																unlink("./".$archivo[count($archivo)-2]."/".$archivo[count($archivo)-1]);
																$con->query("DELETE FROM tp_establecimiento_banner WHERE tpc_banner_cat_establecimiento_banner='".$_GET['tpc_codigo_banner_cat']."';");
																$con->query("DELETE FROM tp_banner_cat WHERE tpc_codigo_banner_cat='".$_GET['tpc_codigo_banner_cat']."';");
															}
															$mensaje="Banner Eliminado Correctamente";
															echo '
															<form name="finality" method="get" action="banners.php">
																<input type="hidden" name="mensaje" value="'.$mensaje.'">
																<input type="hidden" name="opc" value="'.$_GET['tipoban'].'">
															</form>
															<script type="text/javascript">
																alert("'.$mensaje.'");
																finality.submit();
															</script>';
															exit();
														break;
													}
												}
												if($_GET['tipo'] == 'evento'){
													if($usuario['tpc_rol_usuario'] != 2){
														header('Location: index.php');
														exit();
													}
													switch($_GET['opc']){
														case 'nuevo':
															echo '<div class="basic-login-inner">
																<h3>Nuevo Evento</h3>
																<p>Ingresa todos los datos del nuevo evento</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(2)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_evento" id="tpc_archivo_evento" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_evento" id="tpc_nombre_evento" value="" maxlength="25" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" colspan="4" align="center">
																				<label class="login2">Descripción:</label>
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_evento" name="tpc_descripcion_evento" required="required" maxlength="150"></textarea>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_evento" id="tpc_validodesde_evento" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_evento" id="tpc_validohasta_evento" value="" class="form-control" style="width: 100%;" required="required">
																			</td>
																		</tr>
																		
																		<tr>
																			<td width="20%" align="center">
																				<label class="login2">Departamento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_departamento_evento" id="tpc_departamento_evento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Los Departamentos</option>';
																					//}
																					echo '<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Ciudad</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_ciudad_evento" id="tpc_ciudad_evento" class="form-control" required="required">
																					<option value="" selected="selected">Seleccione....</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Las Ciudades</option>';
																					//}
																					echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Direcci&oacute;n</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="direccion" id="direccion" value="" maxlength="50" class="form-control" style="width: 100%;" placeholder="Dirección..." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">URL Evento</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_url_evento" id="tpc_url_evento" value="" class="form-control" style="width: 100%;" placeholder="URL Evento..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="opc" id="opc" value="nuevo">
																				<input type="hidden" name="tipo" id="tipo" value="evento">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Evento</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
														case 'editar':
															$tp_evento = reg('tp_evento', 'tpc_codigo_evento', $_GET['tpc_codigo_evento']);
															echo '<div class="basic-login-inner">
																<h3>Editar Evento</h3>
																<p>Ingresa todos los datos del evento</p>
																<p align="justify"><b><font color="green">NOTA: te recomendamos subir la imagen con el mismo ancho y alto para que se vea homogénea en la aplicación</font></b></p>
																<form method="post" action="gestContenido.php" enctype="multipart/form-data" onSubmit="return vimagen_promocion(2)">
																	<table width="100%">
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Imagen (Opcional)</label>
																			</td>
																			<td width="30%">
																				<input type="file" name="tpc_archivo_evento" id="tpc_archivo_evento" class="form-control">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Nombre</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="tpc_nombre_evento" id="tpc_nombre_evento" value="'.$tp_evento['tpc_nombre_evento'].'" maxlength="25" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" colspan="4" align="center">
																				<label class="login2">Descripción:</label>
																				<textarea class="form-control" style="width: 100%; height: 140px;" id="tpc_descripcion_evento" name="tpc_descripcion_evento" required="required" maxlength="150">'.$tp_evento['tpc_descripcion_evento'].'</textarea>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Valido Desde</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validodesde_evento" id="tpc_validodesde_evento" value="'.date("Y-m-d", strtotime($tp_evento['tpc_validodesde_evento'])).'" class="form-control" required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Valido Hasta</label>
																			</td>
																			<td width="30%">
																				<input type="date" name="tpc_validohasta_evento" id="tpc_validohasta_evento" value="'.date("Y-m-d", strtotime($tp_evento['tpc_validohasta_evento'])).'" class="form-control" style="width: 100%;" placeholder="Nombre..." required="required">
																			</td>
																		</tr>
																		<tr>
																			<td width="20%" align="center">
																				<label class="login2">Departamento</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_departamento_evento" id="tpc_departamento_evento" class="form-control" required="required">
																					<option value="'.$tp_evento['tpc_departamento_evento'].'" selected="selected">'.$tp_evento['tpc_departamento_evento'].'</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Los Departamentos</option>';
																					//}
																					echo '<option value="Amazonas">Amazonas</option>
																					<option value="Antioquia">Antioquia</option>
																					<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																					<option value="Atlantico">Atlantico</option>
																					<option value="Bogota D.C">Bogota D.C</option>
																					<option value="Bolivar">Bolivar</option>
																					<option value="Boyaca">Boyaca</option>
																					<option value="Caldas">Caldas</option>
																					<option value="Caqueta">Caqueta</option>
																					<option value="Casanare">Casanare</option>
																					<option value="Cauca">Cauca</option>
																					<option value="Cesar">Cesar</option>
																					<option value="Choco">Choco</option>
																					<option value="Cordoba">Cordoba</option>
																					<option value="Curdimanarca">Curdimanarca</option>
																					<option value="Guainia">Guainia</option>
																					<option value="Guaviare">Guaviare</option>
																					<option value="Huila">Huila</option>
																					<option value="La Guajira">La Guajira</option>
																					<option value="Magdalena">Magdalena</option>
																					<option value="Meta">Meta</option>
																					<option value="Nariño">Nariño</option>
																					<option value="Norte De Santander">Norte De Santander</option>
																					<option value="Putumayo">Putumayo</option>
																					<option value="Quindio">Quindio</option>
																					<option value="Risaralda">Risaralda</option>
																					<option value="Santander">Santander</option>
																					<option value="Sucre">Sucre</option>
																					<option value="Tolima">Tolima</option>
																					<option value="Valle Del Cauca">Valle Del Cauca</option>
																					<option value="Vaupes">Vaupes</option>
																					<option value="Vichada">Vichada</option>
																				</select>
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">Ciudad</label>
																			</td>
																			<td width="30%">
																				<select name="tpc_ciudad_evento" id="tpc_ciudad_evento" class="form-control" required="required">
																					<option value="'.$tp_evento['tpc_ciudad_evento'].'" selected="selected">'.$tp_evento['tpc_ciudad_evento'].'</option>';
																					//if(intval($usuario['tpc_rol_usuario']) == 2){
																						echo '<option value="todas">Todas Las Ciudades</option>';
																					//}
																					echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																				</select>
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td width="20%" align="center">
																				<label class="login2">Direcci&oacute;n</label>
																			</td>
																			<td width="30%">
																				<input type="text" name="direccion" id="direccion" value="'.$tp_evento['tpc_direccion_evento'].'" maxlength="50" class="form-control" style="width: 100%;" placeholder="Dirección..." required="required">
																			</td>
																			<td width="20%" align="center">
																				<label class="login2">URL Evento</label>
																			</td>
																			<td width="30%">
																				<input type="text" value="'.$tp_evento['tpc_url_evento'].'" name="tpc_url_evento" id="tpc_url_evento" class="form-control" style="width: 100%;" placeholder="URL Evento..." required="required">
																			</td>
																		</tr>
																		<tr style="height:60px;">
																			<td colspan="4" align="center">
																				<input type="hidden" name="tpc_codigo_evento" id="tpc_codigo_evento" value="'.$_GET['tpc_codigo_evento'].'">
																				<input type="hidden" name="opc" id="opc" value="editar">
																				<input type="hidden" name="tipo" id="tipo" value="evento">
																				<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Cambios Evento</button>
																			</td>
																		</tr>
																	</table>
																</form>
															</div>';
														break;
													}
												}
												if($_GET['tipo'] == 'videorse'){
													if($usuario['tpc_rol_usuario'] != 1){//SI NO ES INVERSIONISTA PREMIUM
														header('Location: index.php');
														exit();
													}
													echo '<div class="basic-login-inner">
														<h3>Subir mi video RSE</h3>
														<p>Ingresa todos los datos del nuevo video</p>
														<form method="post" action="gestContenido.php">
															<table width="100%">
																<tr style="height:60px;">
																	<td width="20%" align="center">
																		<label class="login2">Link Video (Embebido)</label>
																	</td>
																	<td width="30%">
																		<input type="text" name="tpc_videoinversionista_establecimiento" id="tpc_videoinversionista_establecimiento" class="form-control" placeholder="Ingresa tu link aquí....." required="required">
																	</td>
																</tr>
																<tr>
																		<td width="20%" align="center">
																			<label class="login2">Departamento</label>
																		</td>
																		<td width="30%">
																			<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																				<option value="'.$tp_banner_cat['tpc_departamento_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_departamento_banner_cat'].'</option>';
																				//if(intval($usuario['tpc_rol_usuario']) == 2){
																					echo '<option value="todas">Todas Los Departamentos</option>';
																				//}
																				echo '<option value="Amazonas">Amazonas</option>
																				<option value="Antioquia">Antioquia</option>
																				<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																				<option value="Atlantico">Atlantico</option>
																				<option value="Bogota D.C">Bogota D.C</option>
																				<option value="Bolivar">Bolivar</option>
																				<option value="Boyaca">Boyaca</option>
																				<option value="Caldas">Caldas</option>
																				<option value="Caqueta">Caqueta</option>
																				<option value="Casanare">Casanare</option>
																				<option value="Cauca">Cauca</option>
																				<option value="Cesar">Cesar</option>
																				<option value="Choco">Choco</option>
																				<option value="Cordoba">Cordoba</option>
																				<option value="Curdimanarca">Curdimanarca</option>
																				<option value="Guainia">Guainia</option>
																				<option value="Guaviare">Guaviare</option>
																				<option value="Huila">Huila</option>
																				<option value="La Guajira">La Guajira</option>
																				<option value="Magdalena">Magdalena</option>
																				<option value="Meta">Meta</option>
																				<option value="Nariño">Nariño</option>
																				<option value="Norte De Santander">Norte De Santander</option>
																				<option value="Putumayo">Putumayo</option>
																				<option value="Quindio">Quindio</option>
																				<option value="Risaralda">Risaralda</option>
																				<option value="Santander">Santander</option>
																				<option value="Sucre">Sucre</option>
																				<option value="Tolima">Tolima</option>
																				<option value="Valle Del Cauca">Valle Del Cauca</option>
																				<option value="Vaupes">Vaupes</option>
																				<option value="Vichada">Vichada</option>
																			</select>
																		</td>
																		<td width="20%" align="center">
																			<label class="login2">Ciudad</label>
																		</td>
																		<td width="30%">
																			<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																				<option value="'.$tp_banner_cat['tpc_ciudad_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_ciudad_banner_cat'].'</option>';
																				//if(intval($usuario['tpc_rol_usuario']) == 2){
																					echo '<option value="todas">Todas Las Ciudades</option>';
																				//}
																				echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																			</select>
																		</td>
																	</tr>
																<tr>
																	<td align="center" colspan="4">NOTA: Se recomienda que el(los) video(s) no superen el minuto de duraci&oacute;n</td>
																</tr>
																<tr style="height:60px;">
																	<td colspan="4" align="center">
																		<input type="hidden" name="tipo" id="tipo" value="videorse">
																		<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Video</button>
																	</td>
																</tr>
															</table>
														</form>
													</div>';
												}
												if($_GET['tipo'] == 'videofundacion'){
													if($usuario['tpc_rol_usuario'] != 2){
														header('Location: index.php');
														exit();
													}
													echo '<div class="basic-login-inner">
														<h3>Subir video fundación</h3>
														<p>Ingresa todos los datos del nuevo video</p>
														<form method="post" action="gestContenido.php">
															<table width="100%">
																<tr style="height:60px;">
																	<td width="20%" align="center">
																		<label class="login2">Link Video (Embebido)</label>
																	</td>
																	<td width="30%">
																		<input type="text" name="tpc_videofundacion_establecimiento" id="tpc_videofundacion_establecimiento" class="form-control" placeholder="Ingresa el link aquí....." required="required">
																	</td>
																</tr>
																<tr>
																		<td width="20%" align="center">
																			<label class="login2">Departamento</label>
																		</td>
																		<td width="30%">
																			<select name="tpc_departamento_establecimiento" id="tpc_departamento_establecimiento" class="form-control" required="required">
																				<option value="'.$tp_banner_cat['tpc_departamento_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_departamento_banner_cat'].'</option>';
																				//if(intval($usuario['tpc_rol_usuario']) == 2){
																					echo '<option value="todas">Todas Los Departamentos</option>';
																				//}
																				echo '<option value="Amazonas">Amazonas</option>
																				<option value="Antioquia">Antioquia</option>
																				<option value="Archipielago de San Andres Providencia Y Sata Catalina">Archipielago de San Andres Providencia Y Sata Catalina</option>
																				<option value="Atlantico">Atlantico</option>
																				<option value="Bogota D.C">Bogota D.C</option>
																				<option value="Bolivar">Bolivar</option>
																				<option value="Boyaca">Boyaca</option>
																				<option value="Caldas">Caldas</option>
																				<option value="Caqueta">Caqueta</option>
																				<option value="Casanare">Casanare</option>
																				<option value="Cauca">Cauca</option>
																				<option value="Cesar">Cesar</option>
																				<option value="Choco">Choco</option>
																				<option value="Cordoba">Cordoba</option>
																				<option value="Curdimanarca">Curdimanarca</option>
																				<option value="Guainia">Guainia</option>
																				<option value="Guaviare">Guaviare</option>
																				<option value="Huila">Huila</option>
																				<option value="La Guajira">La Guajira</option>
																				<option value="Magdalena">Magdalena</option>
																				<option value="Meta">Meta</option>
																				<option value="Nariño">Nariño</option>
																				<option value="Norte De Santander">Norte De Santander</option>
																				<option value="Putumayo">Putumayo</option>
																				<option value="Quindio">Quindio</option>
																				<option value="Risaralda">Risaralda</option>
																				<option value="Santander">Santander</option>
																				<option value="Sucre">Sucre</option>
																				<option value="Tolima">Tolima</option>
																				<option value="Valle Del Cauca">Valle Del Cauca</option>
																				<option value="Vaupes">Vaupes</option>
																				<option value="Vichada">Vichada</option>
																			</select>
																		</td>
																		<td width="20%" align="center">
																			<label class="login2">Ciudad</label>
																		</td>
																		<td width="30%">
																			<select name="tpc_ciudad_establecimiento" id="tpc_ciudad_establecimiento" class="form-control" required="required">
																				<option value="'.$tp_banner_cat['tpc_ciudad_banner_cat'].'" selected="selected">'.$tp_banner_cat['tpc_ciudad_banner_cat'].'</option>';
																			//	if(intval($usuario['tpc_rol_usuario']) == 2){
																					echo '<option value="todas">Todas Las Ciudades</option>';
																			//	}
																				echo '<option value="Abejorral">Abejorral</option><option value="Abriaqui">Abriaqui</option><option value="Acacias">Acacias</option><option value="Acandi">Acandi</option><option value="Acevedo">Acevedo</option><option value="Achi">Achi</option><option value="Agrado">Agrado</option><option value="Agua de Dios">Agua de Dios</option><option value="Aguachica">Aguachica</option><option value="Aguada">Aguada</option><option value="Aguadas">Aguadas</option><option value="Aguazul">Aguazul</option><option value="Agustin Codazzi">Agustin Codazzi</option><option value="Aipe">Aipe</option><option value="Alban">Alban</option><option value="Albania">Albania</option><option value="Alcala">Alcala</option><option value="Aldana">Aldana</option><option value="Alejandria">Alejandria</option><option value="Algarrobo">Algarrobo</option><option value="Algeciras">Algeciras</option><option value="Almaguer">Almaguer</option><option value="Almeida">Almeida</option><option value="Alpujarra">Alpujarra</option><option value="Altamira">Altamira</option><option value="Alto Baudo">Alto Baudo</option><option value="Altos del Rosario">Altos del Rosario</option><option value="Alvarado">Alvarado</option><option value="Amaga">Amaga</option><option value="Amalfi">Amalfi</option><option value="Ambalema">Ambalema</option><option value="Anapoima">Anapoima</option><option value="Ancuya">Ancuya</option><option value="Andes">Andes</option><option value="Angelopolis">Angelopolis</option><option value="Angostura">Angostura</option><option value="Anolaima">Anolaima</option><option value="Anori">Anori</option><option value="Anserma">Anserma</option><option value="Ansermanuevo">Ansermanuevo</option><option value="Antioquia">Antioquia</option><option value="Anza">Anza</option><option value="Anzoategui">Anzoategui</option><option value="Apartado">Apartado</option><option value="Apia">Apia</option><option value="Apulo">Apulo</option><option value="Aquitania">Aquitania</option><option value="Aracataca">Aracataca</option><option value="Aranzazu">Aranzazu</option><option value="Aratoca">Aratoca</option><option value="Arauca">Arauca</option><option value="Arauquita">Arauquita</option><option value="Arbelaez">Arbelaez</option><option value="Arboleda">Arboleda</option><option value="Arboledas">Arboledas</option><option value="Arboletes">Arboletes</option><option value="Arcabuco">Arcabuco</option><option value="Arenal">Arenal</option><option value="Argelia">Argelia</option><option value="Ariguani">Ariguani</option><option value="Arjona">Arjona</option><option value="Armenia">Armenia</option><option value="Armero">Armero</option><option value="Arroyohondo">Arroyohondo</option><option value="Astrea">Astrea</option><option value="Ataco">Ataco</option><option value="Atrato">Atrato</option><option value="Ayapel">Ayapel</option><option value="Bagado">Bagado</option><option value="Bahia Solano">Bahia Solano</option><option value="Bajo Baudo">Bajo Baudo</option><option value="Balboa">Balboa</option><option value="Baranoa">Baranoa</option><option value="Baraya">Baraya</option><option value="Barbacoas">Barbacoas</option><option value="Barbosa">Barbosa</option><option value="Barichara">Barichara</option><option value="Barranca de Upia">Barranca de Upia</option><option value="Barrancabermeja">Barrancabermeja</option><option value="Barrancas">Barrancas</option><option value="Barranco de Loba">Barranco de Loba</option><option value="Barranco Minas">Barranco Minas</option><option value="Barranquilla">Barranquilla</option><option value="Becerril">Becerril</option><option value="Belalcazar">Belalcazar</option><option value="Belen">Belen</option><option value="Belen de Bajira">Belen de Bajira</option><option value="Belen de Los Andaquies">Belen de Los Andaquies</option><option value="Belen de Umbria">Belen de Umbria</option><option value="Bello">Bello</option><option value="BELLO ANTIOQUIA">BELLO ANTIOQUIA</option><option value="Belmira">Belmira</option><option value="Beltran">Beltran</option><option value="Berbeo">Berbeo</option><option value="Betania">Betania</option><option value="Beteitiva">Beteitiva</option><option value="Betulia">Betulia</option><option value="Bituima">Bituima</option><option value="Boavita">Boavita</option><option value="Bochalema">Bochalema</option><option value="Bogota D.C">Bogota D.C</option><option value="Bojaca">Bojaca</option><option value="Bojaya">Bojaya</option><option value="Bolivar">Bolivar</option><option value="Bosconia">Bosconia</option><option value="Boyaca">Boyaca</option><option value="Briceno">Briceno</option><option value="Bucaramanga">Bucaramanga</option><option value="Buena Vista">Buena Vista</option><option value="Buenaventura">Buenaventura</option><option value="Buenavista">Buenavista</option><option value="Buenos Aires">Buenos Aires</option><option value="Buesaco">Buesaco</option><option value="Bugalagrande">Bugalagrande</option><option value="Buritica">Buritica</option><option value="Busbanza">Busbanza</option><option value="Cabrera">Cabrera</option><option value="Cabuyaro">Cabuyaro</option><option value="Cacahual">Cacahual</option><option value="Caceres">Caceres</option><option value="Cachipay">Cachipay</option><option value="Cachira">Cachira</option><option value="Cacota">Cacota</option><option value="Caicedo">Caicedo</option><option value="Caicedonia">Caicedonia</option><option value="Caimito">Caimito</option><option value="Cajamarca">Cajamarca</option><option value="Cajibio">Cajibio</option><option value="Cajica">Cajica</option><option value="Calamar">Calamar</option><option value="Calarca">Calarca</option><option value="Caldas">Caldas</option><option value="Caldono">Caldono</option><option value="Cali">Cali</option><option value="California">California</option><option value="Caloto">Caloto</option><option value="Campamento">Campamento</option><option value="Campo de La Cruz">Campo de La Cruz</option><option value="Campoalegre">Campoalegre</option><option value="Campohermoso">Campohermoso</option><option value="Canalete">Canalete</option><option value="Canasgordas">Canasgordas</option><option value="Candelaria">Candelaria</option><option value="Cantagallo">Cantagallo</option><option value="Caparrapi">Caparrapi</option><option value="Capitanejo">Capitanejo</option><option value="Caqueza">Caqueza</option><option value="Caracoli">Caracoli</option><option value="Caramanta">Caramanta</option><option value="Carcasi">Carcasi</option><option value="Carepa">Carepa</option><option value="Carmen de Apicala">Carmen de Apicala</option><option value="Carmen de Carupa">Carmen de Carupa</option><option value="Carmen del Darien">Carmen del Darien</option><option value="Carolina">Carolina</option><option value="Cartagena">Cartagena</option><option value="Cartagena del Chaira">Cartagena del Chaira</option><option value="Cartago">Cartago</option><option value="Caruru">Caruru</option><option value="Casabianca">Casabianca</option><option value="Castilla la Nueva">Castilla la Nueva</option><option value="Caucasia">Caucasia</option><option value="Cepita">Cepita</option><option value="Cerete">Cerete</option><option value="Cerinza">Cerinza</option><option value="Cerrito">Cerrito</option><option value="Cerro San Antonio">Cerro San Antonio</option><option value="Certegui">Certegui</option><option value="Chachag?i">Chachag?i</option><option value="Chaguani">Chaguani</option><option value="Chalan">Chalan</option><option value="Chameza">Chameza</option><option value="Chaparral">Chaparral</option><option value="Charala">Charala</option><option value="Charta">Charta</option><option value="Chia">Chia</option><option value="Chigorodo">Chigorodo</option><option value="Chima">Chima</option><option value="Chimichagua">Chimichagua</option><option value="Chinavita">Chinavita</option><option value="Chinchina">Chinchina</option><option value="Chinu">Chinu</option><option value="Chipaque">Chipaque</option><option value="Chipata">Chipata</option><option value="Chiquinquira">Chiquinquira</option><option value="Chiquiza">Chiquiza</option><option value="Chiriguana">Chiriguana</option><option value="Chiscas">Chiscas</option><option value="Chita">Chita</option><option value="Chitaraque">Chitaraque</option><option value="Chivata">Chivata</option><option value="Chivolo">Chivolo</option><option value="Chivor">Chivor</option><option value="Choachi">Choachi</option><option value="Choconta">Choconta</option><option value="Cicuco">Cicuco</option><option value="Cienaga">Cienaga</option><option value="Cienaga de Oro">Cienaga de Oro</option><option value="Cienega">Cienega</option><option value="Cimitarra">Cimitarra</option><option value="Circasia">Circasia</option><option value="Cisneros">Cisneros</option><option value="Ciudad Bolivar">Ciudad Bolivar</option><option value="Clemencia">Clemencia</option><option value="Cocorna">Cocorna</option><option value="Coello">Coello</option><option value="Cogua">Cogua</option><option value="Colon">Colon</option><option value="Coloso">Coloso</option><option value="Combita">Combita</option><option value="Concepcion">Concepcion</option><option value="Concordia">Concordia</option><option value="Condoto">Condoto</option><option value="Confines">Confines</option><option value="Consaca">Consaca</option><option value="Contadero">Contadero</option><option value="Contratacion">Contratacion</option><option value="Convencion">Convencion</option><option value="Copacabana">Copacabana</option><option value="Coper">Coper</option><option value="Cordoba">Cordoba</option><option value="Corinto">Corinto</option><option value="Coromoro">Coromoro</option><option value="Corozal">Corozal</option><option value="Corrales">Corrales</option><option value="Cota">Cota</option><option value="Cotorra">Cotorra</option><option value="Covarachia">Covarachia</option><option value="Covenas">Covenas</option><option value="Coyaima">Coyaima</option><option value="Cravo Norte">Cravo Norte</option><option value="Cuaspud">Cuaspud</option><option value="Cubara">Cubara</option><option value="Cubarral">Cubarral</option><option value="Cucaita">Cucaita</option><option value="Cucunuba">Cucunuba</option><option value="Cucuta">Cucuta</option><option value="Cucutilla">Cucutilla</option><option value="Cuitiva">Cuitiva</option><option value="Cumaral">Cumaral</option><option value="Cumaribo">Cumaribo</option><option value="Cumbal">Cumbal</option><option value="Cumbitara">Cumbitara</option><option value="Cunday">Cunday</option><option value="Curillo">Curillo</option><option value="Curiti">Curiti</option><option value="Curumani">Curumani</option><option value="Dabeiba">Dabeiba</option><option value="Dagua">Dagua</option><option value="Dibula">Dibula</option><option value="Distraccion">Distraccion</option><option value="Dolores">Dolores</option><option value="Don Matias">Don Matias</option><option value="Dosquebradas">Dosquebradas</option><option value="Duitama">Duitama</option><option value="Durania">Durania</option><option value="Ebejico">Ebejico</option><option value="El aguila">El aguila</option><option value="El Bagre">El Bagre</option><option value="El Banco">El Banco</option><option value="El Cairo">El Cairo</option><option value="El Calvario">El Calvario</option><option value="El Canton del San Pablo">El Canton del San Pablo</option><option value="El Carmen">El Carmen</option><option value="El Carmen de Atrato">El Carmen de Atrato</option><option value="El Carmen de Bolivar">El Carmen de Bolivar</option><option value="El Carmen de Chucuri">El Carmen de Chucuri</option><option value="El Carmen de Viboral">El Carmen de Viboral</option><option value="El Castillo">El Castillo</option><option value="El Cerrito">El Cerrito</option><option value="El Charco">El Charco</option><option value="El Cocuy">El Cocuy</option><option value="El Colegio">El Colegio</option><option value="El Copey">El Copey</option><option value="El Doncello">El Doncello</option><option value="El Dorado">El Dorado</option><option value="El Dovio">El Dovio</option><option value="El Encanto">El Encanto</option><option value="El Espino">El Espino</option><option value="El Guacamayo">El Guacamayo</option><option value="El Guamo">El Guamo</option><option value="El Litoral del San Juan">El Litoral del San Juan</option><option value="El Molino">El Molino</option><option value="El Paso">El Paso</option><option value="El Paujil">El Paujil</option><option value="El Penol">El Penol</option><option value="El Penon">El Penon</option><option value="El Pinon">El Pinon</option><option value="El Playon">El Playon</option><option value="El Reten">El Reten</option><option value="El Retorno">El Retorno</option><option value="El Roble">El Roble</option><option value="El Rosal">El Rosal</option><option value="El Rosario">El Rosario</option><option value="El Santuario">El Santuario</option><option value="El Tablon de Gomez">El Tablon de Gomez</option><option value="El Tambo">El Tambo</option><option value="El Tarra">El Tarra</option><option value="El Zulia">El Zulia</option><option value="Elias">Elias</option><option value="Encino">Encino</option><option value="Enciso">Enciso</option><option value="Entrerrios">Entrerrios</option><option value="Envigado">Envigado</option><option value="Espinal">Espinal</option><option value="Facatativa">Facatativa</option><option value="Falan">Falan</option><option value="Filadelfia">Filadelfia</option><option value="Filandia">Filandia</option><option value="Firavitoba">Firavitoba</option><option value="Flandes">Flandes</option><option value="Florencia">Florencia</option><option value="Floresta">Floresta</option><option value="Florian">Florian</option><option value="Florida">Florida</option><option value="Floridablanca">Floridablanca</option><option value="Fomeque">Fomeque</option><option value="Fonseca">Fonseca</option><option value="Fortul">Fortul</option><option value="Fosca">Fosca</option><option value="Francisco Pizarro">Francisco Pizarro</option><option value="Fredonia">Fredonia</option><option value="Fresno">Fresno</option><option value="Frontino">Frontino</option><option value="Fuente de Oro">Fuente de Oro</option><option value="Fundacion">Fundacion</option><option value="Funes">Funes</option><option value="Funza">Funza</option><option value="Fuquene">Fuquene</option><option value="Fusagasuga">Fusagasuga</option><option value="G?epsa">G?epsa</option><option value="G?ican">G?ican</option><option value="Gachala">Gachala</option><option value="Gachancipa">Gachancipa</option><option value="Gachantiva">Gachantiva</option><option value="Gacheta">Gacheta</option><option value="Galan">Galan</option><option value="Galapa">Galapa</option><option value="Galeras">Galeras</option><option value="Gama">Gama</option><option value="Gamarra">Gamarra</option><option value="Gambita">Gambita</option><option value="Gameza">Gameza</option><option value="Garagoa">Garagoa</option><option value="Garzon">Garzon</option><option value="Genova">Genova</option><option value="Gigante">Gigante</option><option value="Ginebra">Ginebra</option><option value="Giraldo">Giraldo</option><option value="Girardot">Girardot</option><option value="Girardota">Girardota</option><option value="Giron">Giron</option><option value="Gomez Plata">Gomez Plata</option><option value="Gonzalez">Gonzalez</option><option value="Gramalote">Gramalote</option><option value="Granada">Granada</option><option value="Guaca">Guaca</option><option value="Guacamayas">Guacamayas</option><option value="Guacari">Guacari</option><option value="Guachene">Guachene</option><option value="Guacheta">Guacheta</option><option value="Guachucal">Guachucal</option><option value="Guadalupe">Guadalupe</option><option value="Guaduas">Guaduas</option><option value="Guaitarilla">Guaitarilla</option><option value="Gualmatan">Gualmatan</option><option value="Guamal">Guamal</option><option value="Guamo">Guamo</option><option value="Guapi">Guapi</option><option value="Guapota">Guapota</option><option value="Guaranda">Guaranda</option><option value="Guarne">Guarne</option><option value="Guasca">Guasca</option><option value="Guatape">Guatape</option><option value="Guataqui">Guataqui</option><option value="Guatavita">Guatavita</option><option value="Guateque">Guateque</option><option value="Guatica">Guatica</option><option value="Guavata">Guavata</option><option value="Guayabal de Siquima">Guayabal de Siquima</option><option value="Guayabetal">Guayabetal</option><option value="Guayata">Guayata</option><option value="Gutierrez">Gutierrez</option><option value="Hacari">Hacari</option><option value="Hatillo de Loba">Hatillo de Loba</option><option value="Hato">Hato</option><option value="Hato Corozal">Hato Corozal</option><option value="Hatonuevo">Hatonuevo</option><option value="Heliconia">Heliconia</option><option value="Herran">Herran</option><option value="Herveo">Herveo</option><option value="Hispania">Hispania</option><option value="Hobo">Hobo</option><option value="Honda">Honda</option><option value="huila">huila</option><option value="Ibague">Ibague</option><option value="Icononzo">Icononzo</option><option value="Iles">Iles</option><option value="Imues">Imues</option><option value="Inirida">Inirida</option><option value="Inza">Inza</option><option value="Ipiales">Ipiales</option><option value="Iquira">Iquira</option><option value="Isnos">Isnos</option><option value="Istmina">Istmina</option><option value="Itagui">Itagui</option><option value="Ituango">Ituango</option><option value="Iza">Iza</option><option value="Jambalo">Jambalo</option><option value="Jamundi">Jamundi</option><option value="Jardin">Jardin</option><option value="Jenesano">Jenesano</option><option value="Jerico">Jerico</option><option value="Jerusalen">Jerusalen</option><option value="Jesus Maria">Jesus Maria</option><option value="Jordan">Jordan</option><option value="Juan de Acosta">Juan de Acosta</option><option value="Junin">Junin</option><option value="Jurado">Jurado</option><option value="La Apartada">La Apartada</option><option value="La Argentina">La Argentina</option><option value="La Belleza">La Belleza</option><option value="La Calera">La Calera</option><option value="La Capilla">La Capilla</option><option value="La Ceja">La Ceja</option><option value="La Celia">La Celia</option><option value="La Chorrera">La Chorrera</option><option value="La Cruz">La Cruz</option><option value="La Cumbre">La Cumbre</option><option value="La Dorada">La Dorada</option><option value="La Estrella">La Estrella</option><option value="La Florida">La Florida</option><option value="La Gloria">La Gloria</option><option value="La Guadalupe">La Guadalupe</option><option value="La Jagua de Ibirico">La Jagua de Ibirico</option><option value="La Jagua del Pilar">La Jagua del Pilar</option><option value="La Llanada">La Llanada</option><option value="La Macarena">La Macarena</option><option value="La Merced">La Merced</option><option value="La Mesa">La Mesa</option><option value="La Montanita">La Montanita</option><option value="La Palma">La Palma</option><option value="La Paz">La Paz</option><option value="La Pedrera">La Pedrera</option><option value="La Pena">La Pena</option><option value="La Pintada">La Pintada</option><option value="La Plata">La Plata</option><option value="La Playa">La Playa</option><option value="La Primavera">La Primavera</option><option value="La Salina">La Salina</option><option value="La Sierra">La Sierra</option><option value="La Tebaida">La Tebaida</option><option value="La Tola">La Tola</option><option value="La Union">La Union</option><option value="La Uvita">La Uvita</option><option value="La Vega">La Vega</option><option value="La Victoria">La Victoria</option><option value="La Virginia">La Virginia</option><option value="Labateca">Labateca</option><option value="Labranzagrande">Labranzagrande</option><option value="Landazuri">Landazuri</option><option value="Lebrija">Lebrija</option><option value="Leguizamo">Leguizamo</option><option value="Leiva">Leiva</option><option value="Lejanias">Lejanias</option><option value="Lenguazaque">Lenguazaque</option><option value="Lerida">Lerida</option><option value="Leticia">Leticia</option><option value="Libano">Libano</option><option value="Liborina">Liborina</option><option value="Linares">Linares</option><option value="Lloro">Lloro</option><option value="Lopez">Lopez</option><option value="Lorica">Lorica</option><option value="Los Andes">Los Andes</option><option value="Los Cordobas">Los Cordobas</option><option value="Los Palmitos">Los Palmitos</option><option value="Los Santos">Los Santos</option><option value="Lourdes">Lourdes</option><option value="Luruaco">Luruaco</option><option value="Macanal">Macanal</option><option value="Macaravita">Macaravita</option><option value="Maceo">Maceo</option><option value="Macheta">Macheta</option><option value="Madrid">Madrid</option><option value="Mag?i">Mag?i</option><option value="Magangue">Magangue</option><option value="Mahates">Mahates</option><option value="Maicao">Maicao</option><option value="Majagual">Majagual</option><option value="Malaga">Malaga</option><option value="Malambo">Malambo</option><option value="Mallama">Mallama</option><option value="Manati">Manati</option><option value="Manaure">Manaure</option><option value="Mani">Mani</option><option value="Manizales">Manizales</option><option value="MANIZALEZ">MANIZALEZ</option><option value="Manta">Manta</option><option value="Manzanares">Manzanares</option><option value="Mapiripan">Mapiripan</option><option value="Mapiripana">Mapiripana</option><option value="Margarita">Margarita</option><option value="Maria la Baja">Maria la Baja</option><option value="Marinilla">Marinilla</option><option value="Maripi">Maripi</option><option value="Mariquita">Mariquita</option><option value="Marmato">Marmato</option><option value="Marquetalia">Marquetalia</option><option value="Marsella">Marsella</option><option value="Marulanda">Marulanda</option><option value="Matanza">Matanza</option><option value="Medellin">Medellin</option><option value="Medina">Medina</option><option value="Medio Atrato">Medio Atrato</option><option value="Medio Baudo">Medio Baudo</option><option value="Medio San Juan">Medio San Juan</option><option value="Melgar">Melgar</option><option value="Mercaderes">Mercaderes</option><option value="Mesetas">Mesetas</option><option value="Milan">Milan</option><option value="Miraflores">Miraflores</option><option value="Miranda">Miranda</option><option value="Miriti Parana">Miriti Parana</option><option value="Mistrato">Mistrato</option><option value="Mitu">Mitu</option><option value="Mocoa">Mocoa</option><option value="Mogotes">Mogotes</option><option value="Molagavita">Molagavita</option><option value="Momil">Momil</option><option value="Mompos">Mompos</option><option value="Mongua">Mongua</option><option value="Mongui">Mongui</option><option value="Moniquira">Moniquira</option><option value="Monitos">Monitos</option><option value="Montebello">Montebello</option><option value="Montecristo">Montecristo</option><option value="Montelibano">Montelibano</option><option value="Montenegro">Montenegro</option><option value="Monteria">Monteria</option><option value="Monterrey">Monterrey</option><option value="Morales">Morales</option><option value="Morelia">Morelia</option><option value="Morichal">Morichal</option><option value="Morroa">Morroa</option><option value="Mosquera">Mosquera</option><option value="Motavita">Motavita</option><option value="Murillo">Murillo</option><option value="Murindo">Murindo</option><option value="Mutata">Mutata</option><option value="Mutiscua">Mutiscua</option><option value="Muzo">Muzo</option><option value="Narino">Narino</option><option value="Nataga">Nataga</option><option value="Natagaima">Natagaima</option><option value="Nechi">Nechi</option><option value="Necocli">Necocli</option><option value="Neira">Neira</option><option value="Neiva">Neiva</option><option value="Nemocon">Nemocon</option><option value="Nilo">Nilo</option><option value="Nimaima">Nimaima</option><option value="Nobsa">Nobsa</option><option value="Nocaima">Nocaima</option><option value="Norcasia">Norcasia</option><option value="Norosi">Norosi</option><option value="Novita">Novita</option><option value="Nueva Granada">Nueva Granada</option><option value="Nuevo Colon">Nuevo Colon</option><option value="Nunchia">Nunchia</option><option value="Nuqui">Nuqui</option><option value="Obando">Obando</option><option value="Ocamonte">Ocamonte</option><option value="Oiba">Oiba</option><option value="Oicata">Oicata</option><option value="Olaya">Olaya</option><option value="Olaya Herrera">Olaya Herrera</option><option value="Onzaga">Onzaga</option><option value="Oporapa">Oporapa</option><option value="Orito">Orito</option><option value="Orocue">Orocue</option><option value="Ortega">Ortega</option><option value="Ospina">Ospina</option><option value="Otanche">Otanche</option><option value="Ovejas">Ovejas</option><option value="Pachavita">Pachavita</option><option value="Pacho">Pacho</option><option value="Pacoa">Pacoa</option><option value="Pacora">Pacora</option><option value="Padilla">Padilla</option><option value="Paez">Paez</option><option value="Paicol">Paicol</option><option value="Pailitas">Pailitas</option><option value="Paime">Paime</option><option value="Paipa">Paipa</option><option value="Pajarito">Pajarito</option><option value="Palermo">Palermo</option><option value="Palestina">Palestina</option><option value="Palmar">Palmar</option><option value="Palmar de Varela">Palmar de Varela</option><option value="Palmas del Socorro">Palmas del Socorro</option><option value="Palmira">Palmira</option><option value="Palmito">Palmito</option><option value="Palocabildo">Palocabildo</option><option value="Pamplona">Pamplona</option><option value="Pamplonita">Pamplonita</option><option value="Pana Pana">Pana Pana</option><option value="Pandi">Pandi</option><option value="Panqueba">Panqueba</option><option value="Papunaua">Papunaua</option><option value="Paramo">Paramo</option><option value="Paratebueno">Paratebueno</option><option value="Pasca">Pasca</option><option value="Pasto">Pasto</option><option value="Patia">Patia</option><option value="Pauna">Pauna</option><option value="Paya">Paya</option><option value="Paz de Ariporo">Paz de Ariporo</option><option value="Paz de Rio">Paz de Rio</option><option value="Pedraza">Pedraza</option><option value="Pelaya">Pelaya</option><option value="Penol">Penol</option><option value="Pensilvania">Pensilvania</option><option value="Peque">Peque</option><option value="Pereira">Pereira</option><option value="Pesca">Pesca</option><option value="Piamonte">Piamonte</option><option value="Piedecuesta">Piedecuesta</option><option value="Piedras">Piedras</option><option value="Piendamo">Piendamo</option><option value="Pijao">Pijao</option><option value="Pijino del Carmen">Pijino del Carmen</option><option value="Pinchote">Pinchote</option><option value="Pinillos">Pinillos</option><option value="Piojo">Piojo</option><option value="Pisba">Pisba</option><option value="Pital">Pital</option><option value="Pitalito">Pitalito</option><option value="Pivijay">Pivijay</option><option value="Planadas">Planadas</option><option value="Planeta Rica">Planeta Rica</option><option value="Plato">Plato</option><option value="Policarpa">Policarpa</option><option value="Polonuevo">Polonuevo</option><option value="Ponedera">Ponedera</option><option value="Popayan">Popayan</option><option value="Pore">Pore</option><option value="Potosi">Potosi</option><option value="Prado">Prado</option><option value="Providencia">Providencia</option><option value="Pueblo Bello">Pueblo Bello</option><option value="Pueblo Nuevo">Pueblo Nuevo</option><option value="Pueblo Rico">Pueblo Rico</option><option value="Pueblo Viejo">Pueblo Viejo</option><option value="Pueblorrico">Pueblorrico</option><option value="Puente Nacional">Puente Nacional</option><option value="Puerres">Puerres</option><option value="Puerto Alegria">Puerto Alegria</option><option value="Puerto Arica">Puerto Arica</option><option value="Puerto Asis">Puerto Asis</option><option value="Puerto Berrio">Puerto Berrio</option><option value="Puerto Boyaca">Puerto Boyaca</option><option value="Puerto Caicedo">Puerto Caicedo</option><option value="Puerto Carreno">Puerto Carreno</option><option value="Puerto Colombia">Puerto Colombia</option><option value="Puerto Concordia">Puerto Concordia</option><option value="Puerto Escondido">Puerto Escondido</option><option value="Puerto Gaitan">Puerto Gaitan</option><option value="Puerto Guzman">Puerto Guzman</option><option value="Puerto Libertador">Puerto Libertador</option><option value="Puerto Lleras">Puerto Lleras</option><option value="Puerto Lopez">Puerto Lopez</option><option value="Puerto Nare">Puerto Nare</option><option value="Puerto Narino">Puerto Narino</option><option value="Puerto Parra">Puerto Parra</option><option value="Puerto Rico">Puerto Rico</option><option value="Puerto Rondon">Puerto Rondon</option><option value="Puerto Salgar">Puerto Salgar</option><option value="Puerto Santander">Puerto Santander</option><option value="Puerto Tejada">Puerto Tejada</option><option value="Puerto Triunfo">Puerto Triunfo</option><option value="Puerto Wilches">Puerto Wilches</option><option value="Puli">Puli</option><option value="Pupiales">Pupiales</option><option value="Purace">Purace</option><option value="Purificacion">Purificacion</option><option value="Purisima">Purisima</option><option value="Quebradanegra">Quebradanegra</option><option value="Quetame">Quetame</option><option value="Quibdo">Quibdo</option><option value="Quimbaya">Quimbaya</option><option value="Quinchia">Quinchia</option><option value="Quipama">Quipama</option><option value="Quipile">Quipile</option><option value="Ramiriqui">Ramiriqui</option><option value="Raquira">Raquira</option><option value="Recetor">Recetor</option><option value="Regidor">Regidor</option><option value="Remedios">Remedios</option><option value="Remolino">Remolino</option><option value="Repelon">Repelon</option><option value="Restrepo">Restrepo</option><option value="Retiro">Retiro</option><option value="Ricaurte">Ricaurte</option><option value="Rio Blanco">Rio Blanco</option><option value="Rio de Oro">Rio de Oro</option><option value="Rio Iro">Rio Iro</option><option value="Rio Quito">Rio Quito</option><option value="Rio Viejo">Rio Viejo</option><option value="Riofrio">Riofrio</option><option value="Riohacha">Riohacha</option><option value="Rionegro">Rionegro</option><option value="Riosucio">Riosucio</option><option value="Risaralda">Risaralda</option><option value="Rivera">Rivera</option><option value="Roberto Payan">Roberto Payan</option><option value="Roldanillo">Roldanillo</option><option value="Roncesvalles">Roncesvalles</option><option value="Rondon">Rondon</option><option value="Rosas">Rosas</option><option value="Rovira">Rovira</option><option value="Sabana de Torres">Sabana de Torres</option><option value="Sabanagrande">Sabanagrande</option><option value="Sabanalarga">Sabanalarga</option><option value="Sabanas de San Angel">Sabanas de San Angel</option><option value="Sabaneta">Sabaneta</option><option value="Saboya">Saboya</option><option value="Sacama">Sacama</option><option value="Sachica">Sachica</option><option value="Sahagun">Sahagun</option><option value="Saladoblanco">Saladoblanco</option><option value="Salamina">Salamina</option><option value="Salazar">Salazar</option><option value="Saldana">Saldana</option><option value="Salento">Salento</option><option value="Salgar">Salgar</option><option value="Samaca">Samaca</option><option value="Samana">Samana</option><option value="Samaniego">Samaniego</option><option value="Sampues">Sampues</option><option value="San Agustin">San Agustin</option><option value="San Alberto">San Alberto</option><option value="San Andres">San Andres</option><option value="San Andres de Cuerquia">San Andres de Cuerquia</option><option value="San Andres de Tumaco">San Andres de Tumaco</option><option value="San Andres Sotavento">San Andres Sotavento</option><option value="San Antero">San Antero</option><option value="San Antonio">San Antonio</option><option value="San Antonio del Tequendama">San Antonio del Tequendama</option><option value="San Benito">San Benito</option><option value="San Benito Abad">San Benito Abad</option><option value="San Bernardo">San Bernardo</option><option value="San Bernardo del Viento">San Bernardo del Viento</option><option value="San Calixto">San Calixto</option><option value="San Carlos">San Carlos</option><option value="San Carlos de Guaroa">San Carlos de Guaroa</option><option value="San Cayetano">San Cayetano</option><option value="San Cristobal">San Cristobal</option><option value="San Diego">San Diego</option><option value="San Eduardo">San Eduardo</option><option value="San Estanislao">San Estanislao</option><option value="San Felipe">San Felipe</option><option value="San Fernando">San Fernando</option><option value="San Francisco">San Francisco</option><option value="San Gil">San Gil</option><option value="San Jacinto">San Jacinto</option><option value="San Jacinto del Cauca">San Jacinto del Cauca</option><option value="San Jeronimo">San Jeronimo</option><option value="San Joaquin">San Joaquin</option><option value="San Jose">San Jose</option><option value="San Jose de La Montana">San Jose de La Montana</option><option value="San Jose de Miranda">San Jose de Miranda</option><option value="San Jose de Pare">San Jose de Pare</option><option value="San Jose de Ure">San Jose de Ure</option><option value="San Jose del Fragua">San Jose del Fragua</option><option value="San Jose del Guaviare">San Jose del Guaviare</option><option value="San Jose del Palmar">San Jose del Palmar</option><option value="San Juan de Arama">San Juan de Arama</option><option value="San Juan de Betulia">San Juan de Betulia</option><option value="San Juan de Rio Seco">San Juan de Rio Seco</option><option value="San Juan de Uraba">San Juan de Uraba</option><option value="San Juan del Cesar">San Juan del Cesar</option><option value="San Juan Nepomuceno">San Juan Nepomuceno</option><option value="San Juanito">San Juanito</option><option value="San Lorenzo">San Lorenzo</option><option value="San Luis">San Luis</option><option value="San Luis de Gaceno">San Luis de Gaceno</option><option value="San Luis de Since">San Luis de Since</option><option value="San Marcos">San Marcos</option><option value="San Martin">San Martin</option><option value="San Martin de Loba">San Martin de Loba</option><option value="San Mateo">San Mateo</option><option value="San Miguel">San Miguel</option><option value="San Miguel de Sema">San Miguel de Sema</option><option value="San Onofre">San Onofre</option><option value="San Pablo">San Pablo</option><option value="San Pablo de Borbur">San Pablo de Borbur</option><option value="San Pedro">San Pedro</option><option value="San Pedro de Cartago">San Pedro de Cartago</option><option value="San Pedro de Uraba">San Pedro de Uraba</option><option value="San Pelayo">San Pelayo</option><option value="San Rafael">San Rafael</option><option value="San Roque">San Roque</option><option value="San Sebastian">San Sebastian</option><option value="San Sebastian de Buenavista">San Sebastian de Buenavista</option><option value="San Vicente">San Vicente</option><option value="San Vicente de Chucuri">San Vicente de Chucuri</option><option value="San Vicente del Caguan">San Vicente del Caguan</option><option value="San Zenon">San Zenon</option><option value="Sandona">Sandona</option><option value="Santa Ana">Santa Ana</option><option value="Santa Barbara">Santa Barbara</option><option value="Santa Barbara de Pinto">Santa Barbara de Pinto</option><option value="Santa Catalina">Santa Catalina</option><option value="Santa Helena del Opon">Santa Helena del Opon</option><option value="Santa Isabel">Santa Isabel</option><option value="Santa Lucia">Santa Lucia</option><option value="Santa Maria">Santa Maria</option><option value="Santa Marta">Santa Marta</option><option value="Santa Rosa">Santa Rosa</option><option value="Santa Rosa de Cabal">Santa Rosa de Cabal</option><option value="Santa Rosa de Osos">Santa Rosa de Osos</option><option value="Santa Rosa de Viterbo">Santa Rosa de Viterbo</option><option value="Santa Rosa del Sur">Santa Rosa del Sur</option><option value="Santa Rosalia">Santa Rosalia</option><option value="Santa Sofia">Santa Sofia</option><option value="Santacruz">Santacruz</option><option value="Santafe de Antioquia">Santafe de Antioquia</option><option value="Santana">Santana</option><option value="Santander de Quilichao">Santander de Quilichao</option><option value="Santiago">Santiago</option><option value="Santiago de Tolu">Santiago de Tolu</option><option value="Santo Domingo">Santo Domingo</option><option value="Santo Tomas">Santo Tomas</option><option value="Santuario">Santuario</option><option value="Sapuyes">Sapuyes</option><option value="Saravena">Saravena</option><option value="Sasaima">Sasaima</option><option value="Sativanorte">Sativanorte</option><option value="Sativasur">Sativasur</option><option value="Segovia">Segovia</option><option value="Sesquile">Sesquile</option><option value="Sevilla">Sevilla</option><option value="Siachoque">Siachoque</option><option value="Sibate">Sibate</option><option value="Sibundoy">Sibundoy</option><option value="Silos">Silos</option><option value="Silvania">Silvania</option><option value="Silvia">Silvia</option><option value="Simacota">Simacota</option><option value="Simijaca">Simijaca</option><option value="Simiti">Simiti</option><option value="Sincelejo">Sincelejo</option><option value="Sipi">Sipi</option><option value="Sitionuevo">Sitionuevo</option><option value="Soacha">Soacha</option><option value="Soata">Soata</option><option value="Socha">Socha</option><option value="Socorro">Socorro</option><option value="Socota">Socota</option><option value="Sogamoso">Sogamoso</option><option value="Solano">Solano</option><option value="Soledad">Soledad</option><option value="Solita">Solita</option><option value="Somondoco">Somondoco</option><option value="Sonson">Sonson</option><option value="Sopetran">Sopetran</option><option value="Soplaviento">Soplaviento</option><option value="Sopo">Sopo</option><option value="Sora">Sora</option><option value="Soraca">Soraca</option><option value="Sotaquira">Sotaquira</option><option value="Sotara">Sotara</option><option value="Suaita">Suaita</option><option value="Suan">Suan</option><option value="Suarez">Suarez</option><option value="Suaza">Suaza</option><option value="Subachoque">Subachoque</option><option value="Sucre">Sucre</option><option value="Suesca">Suesca</option><option value="Supata">Supata</option><option value="Supia">Supia</option><option value="Surata">Surata</option><option value="Susa">Susa</option><option value="Susacon">Susacon</option><option value="Sutamarchan">Sutamarchan</option><option value="Sutatausa">Sutatausa</option><option value="Sutatenza">Sutatenza</option><option value="Tabio">Tabio</option><option value="Tado">Tado</option><option value="Talaigua Nuevo">Talaigua Nuevo</option><option value="Tamalameque">Tamalameque</option><option value="Tamara">Tamara</option><option value="Tame">Tame</option><option value="Tamesis">Tamesis</option><option value="Taminango">Taminango</option><option value="Tangua">Tangua</option><option value="Taraira">Taraira</option><option value="Tarapaca">Tarapaca</option><option value="Taraza">Taraza</option><option value="Tarqui">Tarqui</option><option value="Tarso">Tarso</option><option value="Tasco">Tasco</option><option value="Tauramena">Tauramena</option><option value="Tausa">Tausa</option><option value="Tello">Tello</option><option value="Tena">Tena</option><option value="Tenerife">Tenerife</option><option value="Tenjo">Tenjo</option><option value="Tenza">Tenza</option><option value="Teorama">Teorama</option><option value="Teruel">Teruel</option><option value="Tesalia">Tesalia</option><option value="Tibacuy">Tibacuy</option><option value="Tibana">Tibana</option><option value="Tibasosa">Tibasosa</option><option value="Tibirita">Tibirita</option><option value="Tibu">Tibu</option><option value="Tierralta">Tierralta</option><option value="Timana">Timana</option><option value="Timbio">Timbio</option><option value="Timbiqui">Timbiqui</option><option value="Tinjaca">Tinjaca</option><option value="Tipacoque">Tipacoque</option><option value="Tiquisio">Tiquisio</option><option value="Titiribi">Titiribi</option><option value="Toca">Toca</option><option value="Tocaima">Tocaima</option><option value="Tocancipa">Tocancipa</option><option value="Tog?i">Tog?i</option><option value="Toledo">Toledo</option><option value="Tolu Viejo">Tolu Viejo</option><option value="Tona">Tona</option><option value="Topaga">Topaga</option><option value="Topaipi">Topaipi</option><option value="Toribio">Toribio</option><option value="Toro">Toro</option><option value="Tota">Tota</option><option value="Totoro">Totoro</option><option value="Trinidad">Trinidad</option><option value="Trujillo">Trujillo</option><option value="Tubara">Tubara</option><option value="Tuchin">Tuchin</option><option value="Tulua">Tulua</option><option value="Tunja">Tunja</option><option value="Tunungua">Tunungua</option><option value="Tuquerres">Tuquerres</option><option value="Turbaco">Turbaco</option><option value="Turbana">Turbana</option><option value="Turbo">Turbo</option><option value="Turmeque">Turmeque</option><option value="Tuta">Tuta</option><option value="Tutaza">Tutaza</option><option value="Ubala">Ubala</option><option value="Ubaque">Ubaque</option><option value="Ulloa">Ulloa</option><option value="Umbita">Umbita</option><option value="Une">Une</option><option value="Unguia">Unguia</option><option value="Union Panamericana">Union Panamericana</option><option value="Uramita">Uramita</option><option value="Uribe">Uribe</option><option value="Uribia">Uribia</option><option value="Urrao">Urrao</option><option value="Urumita">Urumita</option><option value="Usiacuri">Usiacuri</option><option value="utica">utica</option><option value="Valdivia">Valdivia</option><option value="Valencia">Valencia</option><option value="Valle de Guamez">Valle de Guamez</option><option value="Valle de San Jose">Valle de San Jose</option><option value="Valle de San Juan">Valle de San Juan</option><option value="Valledupar">Valledupar</option><option value="Valparaiso">Valparaiso</option><option value="Vegachi">Vegachi</option><option value="Velez">Velez</option><option value="Venadillo">Venadillo</option><option value="Venecia">Venecia</option><option value="Ventaquemada">Ventaquemada</option><option value="Vergara">Vergara</option><option value="Versalles">Versalles</option><option value="Vetas">Vetas</option><option value="Viani">Viani</option><option value="Victoria">Victoria</option><option value="Vigia del Fuerte">Vigia del Fuerte</option><option value="Vijes">Vijes</option><option value="Villa Caro">Villa Caro</option><option value="Villa de Leyva">Villa de Leyva</option><option value="Villa de San Diego de Ubate">Villa de San Diego de Ubate</option><option value="Villa Rica">Villa Rica</option><option value="Villagarzon">Villagarzon</option><option value="Villagomez">Villagomez</option><option value="Villahermosa">Villahermosa</option><option value="Villamaria">Villamaria</option><option value="Villanueva">Villanueva</option><option value="Villapinzon">Villapinzon</option><option value="Villarrica">Villarrica</option><option value="Villavicencio">Villavicencio</option><option value="Villavieja">Villavieja</option><option value="Villeta">Villeta</option><option value="Villvicencio">Villvicencio</option><option value="Viota">Viota</option><option value="Viracacha">Viracacha</option><option value="Vista Hermosa">Vista Hermosa</option><option value="Viterbo">Viterbo</option><option value="Yacopi">Yacopi</option><option value="Yacuanquer">Yacuanquer</option><option value="Yaguara">Yaguara</option><option value="Yali">Yali</option><option value="Yarumal">Yarumal</option><option value="Yavarate">Yavarate</option><option value="Yolombo">Yolombo</option><option value="Yondo">Yondo</option><option value="Yopal">Yopal</option><option value="Yotoco">Yotoco</option><option value="Yumbo">Yumbo</option><option value="Zambrano">Zambrano</option><option value="Zapatoca">Zapatoca</option><option value="Zapayan">Zapayan</option><option value="Zaragoza">Zaragoza</option><option value="Zarzal">Zarzal</option><option value="Zetaquira">Zetaquira</option><option value="Zipacon">Zipacon</option><option value="Zipaquira">Zipaquira</option><option value="Zona Bananera">Zona Bananera</option>
																			</select>
																		</td>
																	</tr>
																<tr>
																	<td align="center" colspan="2">NOTA: Se recomienda que el(los) video(s) no superen los 20 segundos de duraci&oacute;n</td>
																</tr>
																<tr style="height:60px;">
																	<td colspan="2" align="center">
																		<input type="hidden" name="tipo" id="tipo" value="videofundacion">
																		<button class="btn btn-sm btn-primary login-submit-cs" type="submit">Guardar Video</button>
																	</td>
																</tr>
															</table>
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
	<script type="text/javascript">
		function marcardesmarcar(checkeado)
		{
			if(checkeado == true)
			{
				for (i=0;i<document.reportecontactos.elements.length;i++){
					if(document.reportecontactos.elements[i].type == "checkbox"){
						document.reportecontactos.elements[i].checked=1;
					}
				}
			}else{
				for (i=0;i<document.reportecontactos.elements.length;i++){
					if(document.reportecontactos.elements[i].type == "checkbox"){
						document.reportecontactos.elements[i].checked=0;
					}
				}
			}
		}
		function valortotal_promo(valor_total, porc_descuento){
			if(valor_total != '' && valor_total > 0 && porc_descuento != ''){
				var total = valor_total - ((valor_total / 100) * porc_descuento);
				var total_formateado = number_format(total, 0, ',', '.');
				document.getElementById('totalpromo').innerHTML = 'Total: $ ' + total_formateado;
			}else{
				document.getElementById('totalpromo').innerHTML = '';
			}
		}
		function number_format(number,decimals,dec_point,thousands_sep) {
			number  = number*1;//makes sure `number` is numeric value
			var str = number.toFixed(decimals?decimals:0).toString().split('.');
			var parts = [];
			for ( var i=str[0].length; i>0; i-=3 ) {
				parts.unshift(str[0].substring(Math.max(0,i-3),i));
			}
			str[0] = parts.join(thousands_sep?thousands_sep:',');
			return str.join(dec_point?dec_point:'.');
		}
	</script>
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>
