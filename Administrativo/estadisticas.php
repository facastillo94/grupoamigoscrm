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
		function traer_estadisticas(){
			var rol='<?php echo $usuario['tpc_rol_usuario']; ?>';
			var tpc_categoria_establecimiento = "";
			if(rol == 2){
				tpc_categoria_establecimiento = document.getElementById("tpc_categoria_establecimiento").value;
			}
			var xmlhttp;
			xmlhttp = new XMLHttpRequest();
			xmlhttp.open("GET", "./db.php?opc=1&tpc_categoria_establecimiento=" + tpc_categoria_establecimiento, false);
			xmlhttp.onreadystatechange = function () {
			  if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var resul = JSON.parse(xmlhttp.responseText);
				document.getElementById("divventas").innerHTML=resul.ventas;
				document.getElementById("divvisitas").innerHTML=resul.visitas;
				document.getElementById("divpromedio").innerHTML=resul.calificaciones;
			  }
			}
			xmlhttp.send(null);
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
                                            <li><span class="bread-blod">Estadisticas</span>
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
													<h3>Tus Estadisticas</h3>
													<p>Aquí verás tus estadisticas</p>
													<?php
														if(intval($usuario['tpc_rol_usuario']) == 2){//SI ES ADMIN
															echo '<div class="form-group-inner">
																<div class="row">
																	<div class="col-lg-4">
																		<label class="login2">Categoria</label>
																	</div>
																	<div class="col-lg-6">
																		<select name="tpc_categoria_establecimiento" id="tpc_categoria_establecimiento" class="form-control" onchange="traer_estadisticas()">
																			<option value="">Todas Las Categorias.....</option>
																			<!--<option value="APP ALIADA">APP ALIADA</option>--> <!--<option value="ALIADOS ESTRATEGICOS">ALIADOS ESTRATEGICOS</option>-->
																		   <!--<option value="ANIMALES Y AGRICULTURA">ANIMALES Y AGRICULTURA</option>-->
																		   <!--<option value="AUTOPARTES">AUTOPARTES</option>-->
																		   <option value="BARES Y DIVERSION">BARES Y DIVERSION</option>
																		   <!--<option value="BELLEZA Y ESTETICA">BELLEZA Y ESTETICA</option>-->
																		   <!--<option value="CENTROS COMERCIALES">CENTROS COMERCIALES</option>-->
																		   <!--<option value="CORRESPONSALES BANCARIOS">CORRESPONSALES BANCARIOS</option>-->
																		   <!--<option value="DIVERSION Y AVENTURA">DIVERSION Y AVENTURA</option>-->
																		   <!--<option value="EDUCACION">EDUCACION</option>-->
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
																	</div>
																</div>
															</div>';
														}
													?>
													<div class="form-group-inner">
														<div class="row">
															<div class="col-lg-4">
																<label class="login2">Ventas Este Mes</label>
															</div>
															<div class="col-lg-6" id="divventas">
																
															</div>
														</div>
													</div>
													<div class="form-group-inner">
														<div class="row">
															<div class="col-lg-4">
																<label class="login2">Visitas a tus establecimientos</label>
															</div>
															<div class="col-lg-6" id="divvisitas">
															
															</div>
														</div>
													</div>
													<div class="form-group-inner">
														<div class="row">
															<div class="col-lg-4">
																<label class="login2">Promedio de calificaciones</label>
															</div>
															<div class="col-lg-6" id="divpromedio">
															
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
			</div>
        </div>
    </div>
	<script>traer_estadisticas();</script>
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>