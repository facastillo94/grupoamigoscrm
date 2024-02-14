<?php
	require '../includes/Conectar.php';
	include ('../includes/funciones.php');
	session_start();
	$mkid=$_SESSION["mkid"];
	$mksesion=$_SESSION["mksesion"];
	validar2($mkid, $mksesion);
	$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $mkid);
	if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
		header('Location: index.php');
		exit();
	}
	$con=new conectar();
	$con->conectar();
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Usuarios</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
		include('head.php');
	?>
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
									<div class="col-lg-6">
                                        
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Gestión</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">Usuarios</span>
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
			<!-- Mobile Menu start -->
			<?php
				include('menumobil.php');
			?>
            <!-- Mobile Menu end -->
            <div class="inbox-mailbox-area mg-b-15">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="tab-content">
                                        <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                                            <div class="mail-title inbox-bt-mg">
												<a href="gestContenido.php?opc=nuevo&tipo=usuario" class="btn btn-primary btn-sm" style="background-color: blue;width:150px;"><font color="white">+ Crear Usuario</font></a>
												<a href="gestContenido.php?opc=importar&tipo=usuario" class="btn btn-primary btn-sm" style="background-color: blue;width:100px;"><font color="white">Importar</font></a>
                                                <h2>Usuarios</h2>
                                                <div class="view-mail-action view-mail-ov-d-n">
                                                    <a href="usuarios.php"><i class="fa fa-reply"></i> Recargar</a>
                                                    <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Imprimir</a>
                                                    <!-- <a class="compose-discard-bt" href="#"><i class="fa fa-trash-o"></i> Eliminar</a>-->
                                                </div>
                                            </div><br>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
												<div id="opcionesmasivas" style="display:block;">
													<p>Para los elementos seleccionados</p>
													<button onclick="enviarformu('editar')">
														Editar
													</button>
													<button onclick="enviarformu('eliminar')">
														Eliminar
													</button>
												</div><br><br>
												<form name="reportecontactos" id="reportecontactos" method="post" action="gestContenido.php">
													<input type="hidden" id="tipo" name="tipo" value="usuario">
													<input type="hidden" id="opc" name="opc" value="masivamente">
													<input type="hidden" id="finalmente" name="finalmente" value="">
													<input type="hidden" id="cadenaseleccionados" name="cadenaseleccionados" value="">
													<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
														<thead>
															<tr>
																<th><center><input type="checkbox" id="checktodos" name="checktodos" value="1" onclick="marcardesmarcar(this.checked)"></center></th>
																<th data-field="imagen">Imagen</th>
																<th data-field="nombrecompleto"><center>Nombre Completo</center></th>
																<th data-field="Usuario"><center>Usuario</center></th>
																<th data-field="Correo"><center>Correo</center></th>
																<th data-field="Rol"><center>Rol</center></th>
																<th data-field="Editar"><center>Editar</center></th>
																<th data-field="Activar"><center>Activar / InActivar</center></th>
															</tr>
														</thead>
														<?php
															if(isset($_POST['tpc_nombre_establecimiento'], $_POST['tpc_nickname_usuario'], $_POST['tpc_numdocumento_establecimiento'], $_POST['tpc_pais_establecimiento'], $_POST['tpc_departamento_establecimiento'], $_POST['tpc_ciudad_establecimiento'], $_POST['tpc_categoria_establecimiento'], $_POST['tpc_direccion_establecimiento'])){
																if(intval($_POST['rol']) != -1 && intval($_POST['rol']) != -2){
																	$adicional = "";
																	if($_POST['tpc_nombre_establecimiento'] != ''){
																		$adicional .= "AND tpc_nombre_establecimiento LIKE '%".$_POST['tpc_nombre_establecimiento']."%'";
																	}
																	if($_POST['tpc_nickname_usuario'] != ''){
																		$adicional .= "AND tpc_nickname_usuario LIKE '%".$_POST['tpc_nickname_usuario']."%'";
																	}
																	if($_POST['tpc_numdocumento_establecimiento'] != ''){
																		$adicional .= "AND tpc_numdocumento_establecimiento LIKE '%".$_POST['tpc_numdocumento_establecimiento']."%'";
																	}
																	if($_POST['tpc_pais_establecimiento'] != ''){
																		$adicional .= "AND tpc_pais_establecimiento LIKE '%".$_POST['tpc_pais_establecimiento']."%'";
																	}
																	if($_POST['tpc_departamento_establecimiento'] != ''){
																		$adicional .= "AND tpc_departamento_establecimiento LIKE '%".$_POST['tpc_departamento_establecimiento']."%'";
																	}
																	if($_POST['tpc_ciudad_establecimiento'] != ''){
																		$adicional .= "AND tpc_ciudad_establecimiento LIKE '%".$_POST['tpc_ciudad_establecimiento']."%'";
																	}
																	if($_POST['tpc_categoria_establecimiento'] != ''){
																		$adicional .= "AND tpc_categoria_establecimiento LIKE '%".$_POST['tpc_categoria_establecimiento']."%'";
																	}
																	if($_POST['tpc_direccion_establecimiento'] != ''){
																		$adicional .= "AND tpc_direccion_establecimiento LIKE '%".$_POST['tpc_direccion_establecimiento']."%'";
																	}
																	if($_POST['rol'] != ''){
																		$adicional .= "AND tpc_rol_usuario='".$_POST['rol']."'";
																	}
																	if($adicional == ""){
																		$aejecutar = "SELECT * FROM tp_usuarios ORDER BY tpc_codigo_usuario;";
																	}else{
																		$aejecutar = "SELECT * FROM tp_usuarios INNER JOIN tp_establecimientos ON tpc_asignadoa_establecimiento=tpc_codigo_usuario ".$adicional." GROUP BY tpc_nickname_usuario ORDER BY tpc_codigo_usuario;";
																	}
																	
																}else{
																	$aejecutar = "SELECT * FROM tp_usuarios WHERE tpc_rol_usuario='".$_POST['rol']."' ORDER BY tpc_codigo_usuario;";
																}
																$con->query($aejecutar);
															}else{
																$con->query("SELECT * FROM tp_usuarios ORDER BY tpc_codigo_usuario;");
															}
															if($con->num_rows() > 0){
																$roles = array("-4" => "Plan Inversionista Ultra", "-3" => "Plan Patrocinador Ultra", "-2" => "Plan Patrocinador Premium", "-1" => "Aliado Estrategico", "0" => "Plan Gratuito", "1" => "Plan Inversionista Premium", "2" => "Administrador");
																$estados = array("InActivo", "Activo");
																$contador = 0;$total = $con->num_rows();
																echo '
																<tbody>';
																	while($con->next_record()){
																		$contador++;
																		if($con->f("tpc_estado_usuario") == 0){$activo='<i class="fa fa-check-square-o">';}else{$activo='<i class="fa fa-check-square">';}
																		$imagen = 'N/A';
																		if($con->f("tpc_imagen_usuario") != ''){
																			$imagen = '<img src="'.$con->f("tpc_imagen_usuario").'" height="60px" width="60px">';
																		}
																		echo '<tr>
																			<td>
																				<center><input type="checkbox" id="check'.$contador.'" name="check'.$contador.'" value="'.$con->f("tpc_codigo_usuario").'"></center>
																			</td>
																			<td>'.$imagen.'</td>
																			<td><center><label>'.$con->f("tpc_nombres_usuario").' <br> '.$con->f("tpc_apellidos_usuario").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_nickname_usuario").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_email_usuario").'</label></center></td>
																			<td><center><label>'.$roles[$con->f("tpc_rol_usuario")].'</label></center></td>
																					<!--<td><center>
																						<form method="get" action="gestContenido.php">
																							<input type="hidden" name="idusuario" value="'.$con->f("tpc_codigo_usuario").'">
																							<input type="hidden" name="opc" value="logs">
																							<input type="hidden" name="tipo" value="usuario">
																							<select name="tiempologs" class="form-control-sm">
																								<option value="">Todos Los Logs</option>
																								<option value="1">Ultimo Mes</option>';
																								for($i = 2; $i <= 12; $i++){
																									echo '<option value="'.$i.'">Ultimos '.$i.' Meses</option>';
																								}
																					echo '	</select>
																							<input type="submit" class="btn btn-primary btn-sm" value="Ver Logs">
																						</form>
																					</center></td>-->
																			<td><center><label><a href="gestContenido.php?idusuario='.$con->f("tpc_codigo_usuario").'&opc=editar&tipo=usuario"><i class="fa fa-edit"></a></label></center></td>
																			<td><center><label><a href="gestContenido.php?idusuario='.$con->f("tpc_codigo_usuario").'&opc=activacion&tipo=usuario">'.$activo.' '.$estados[$con->f("tpc_estado_usuario")].'</a></label></center></td>
																			
																			
																		</tr>';
																	}
																echo'</tbody><input type="hidden" name="contador" id="contador" value="'.$total.'">';
															}else{
																echo '
																<tbody>
																	<tr>
																		<td colspan="8"><center>No Existen Registros Actualmente</center></td>
																	</tr>
																</tbody>';
															}
														?>
													</table>
												</form>
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
											function enviarformu(accion){
												document.getElementById('finalmente').value=accion;
												if(accion == 'eliminar' && confirm('¿Estas seguro que deseas eliminar los usuarios seleccionados?') == false){
													return;
												}
												reportecontactos.submit();
											}
										</script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Start-->
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>