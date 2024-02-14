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
    <title>Establecimientos</title>
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
                                            <li><span class="bread-blod">Establecimientos</span>
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
												<a href="gestContenido.php?opc=nuevo&tipo=contacto" class="btn btn-primary btn-sm" style="background-color: #FF00FF;width:150px;"><font color="white">+ Crear Establecimiento</font></a>
												<a href="gestContenido.php?opc=importar&tipo=contacto" class="btn btn-primary btn-sm" style="background-color: #FF00FF;width:100px;"><font color="white">Importar</font></a>
                                                <h2>Establecimientos</h2>
                                                <div class="view-mail-action view-mail-ov-d-n">
                                                    <a href="contactos.php"><i class="fa fa-reply"></i> Recargar</a>
                                                    <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Imprimir</a>
                                                    <!-- <a class="compose-discard-bt" href="#"><i class="fa fa-trash-o"></i> Eliminar</a>-->
                                                </div>
                                            </div><br>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
                                                <div id="toolbar1">
                                                    <!--<select class="form-control">
                                                        <option value="">Exportación Básica</option>
                                                        <option value="all">Exportar Todo</option>
                                                        <option value="selected">Exportar Seleccionados</option>
                                                    </select>-->
                                                </div>
												<div id="opcionesmasivas" style="display:block;">
													<p>Para los elementos seleccionados</p>
													<button onclick="enviarformu('editar')">
														Editar
													</button>
													<button onclick="enviarformu('eliminar')">
														Eliminar
													</button>
													<button onclick="enviarformu('promocion')">
														Asignar Promocion
													</button>
												</div><br><br>
												<form name="reportecontactos" id="reportecontactos" method="post" action="gestContenido.php">
													<input type="hidden" id="tipo" name="tipo" value="contacto">
													<input type="hidden" id="opc" name="opc" value="masivamente">
													<input type="hidden" id="finalmente" name="finalmente" value="">
													<input type="hidden" id="cadenaseleccionados" name="cadenaseleccionados" value="">
													<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
														<thead>
															<tr>
																<th><center><input type="checkbox" id="checktodos" name="checktodos" value="1" onclick="marcardesmarcar(this.checked)"></center></th>
																<th data-field="Imagen"><center>Imagen</center></th>
																<th data-field="FechaCreacion"><center>Fecha Creación</center></th>
																<th data-field="Nombre"><center>Nombre</center></th>
																<th data-field="Particular"><center># Particular</center></th>
																<th data-field="Correo"><center>Correo</center></th>
																<th data-field="Direccion"><center>Dirección</center></th>
																<th data-field="Ciudad"><center>Ciudad</center></th>
																<th data-field="Departamento"><center>Departamento</center></th>
																<th data-field="Pais"><center>Pais</center></th>
																<th data-field="Asignadoa"><center>Asignado a</center></th>
																<th data-field="Categoria"><center>Categoria</center></th>
																<th data-field="Editar"><center>Editar</center></th>
															</tr>
														</thead>
														<?php
															$adicional = "";
															if(isset($_POST['tpc_nombre_establecimiento'], $_POST['tpc_nickname_usuario'], $_POST['tpc_numdocumento_establecimiento'], $_POST['tpc_pais_establecimiento'], $_POST['tpc_departamento_establecimiento'], $_POST['tpc_ciudad_establecimiento'], $_POST['tpc_categoria_establecimiento'], $_POST['tpc_direccion_establecimiento'], $_POST['tpc_fechacreacion_establecimiento_desde'], $_POST['tpc_fechacreacion_establecimiento_hasta'], $_POST['tpc_valordomicilio_establecimiento'])){
																if($_POST['tpc_nombre_establecimiento'] != ''){
																	$adicional .= " AND tpc_nombre_establecimiento LIKE '%".$_POST['tpc_nombre_establecimiento']."%'";
																}
																if($_POST['tpc_nickname_usuario'] != ''){
																	$adicional .= " AND tpc_nickname_usuario LIKE '%".$_POST['tpc_nickname_usuario']."%'";
																}
																if($_POST['tpc_numdocumento_establecimiento'] != ''){
																	$adicional .= " AND tpc_numdocumento_establecimiento LIKE '%".$_POST['tpc_numdocumento_establecimiento']."%'";
																}
																if($_POST['tpc_pais_establecimiento'] != ''){
																	$adicional .= " AND tpc_pais_establecimiento LIKE '%".$_POST['tpc_pais_establecimiento']."%'";
																}
																if($_POST['tpc_departamento_establecimiento'] != ''){
																	$adicional .= " AND tpc_departamento_establecimiento LIKE '%".$_POST['tpc_departamento_establecimiento']."%'";
																}
																if($_POST['tpc_ciudad_establecimiento'] != ''){
																	$adicional .= " AND tpc_ciudad_establecimiento LIKE '%".$_POST['tpc_ciudad_establecimiento']."%'";
																}
																if($_POST['tpc_categoria_establecimiento'] != ''){
																	$adicional .= " AND tpc_categoria_establecimiento LIKE '%".$_POST['tpc_categoria_establecimiento']."%'";
																}
																if($_POST['tpc_direccion_establecimiento'] != ''){
																	$adicional .= " AND tpc_direccion_establecimiento LIKE '%".$_POST['tpc_direccion_establecimiento']."%'";
																}
																//FECHAS ==>
																if($_POST['tpc_fechacreacion_establecimiento_desde'] != '' && $_POST['tpc_fechacreacion_establecimiento_hasta'] == ''){
																	$adicional .= " AND tpc_fechacreacion_establecimiento > '".$_POST['tpc_fechacreacion_establecimiento_desde']." 00:00:00'";
																}else{
																	if($_POST['tpc_fechacreacion_establecimiento_desde'] != '' && $_POST['tpc_fechacreacion_establecimiento_hasta'] != ''){
																		$adicional .= " AND tpc_fechacreacion_establecimiento BETWEEN '".$_POST['tpc_fechacreacion_establecimiento_desde']." 00:00:00' AND '".$_POST['tpc_fechacreacion_establecimiento_hasta']." 23:59:59'";
																	}
																}
																//**********
																if($_POST['tpc_valordomicilio_establecimiento'] != ''){
																	$adicional .= " AND tpc_valordomicilio_establecimiento = '".$_POST['tpc_valordomicilio_establecimiento']."'";
																}
															}
															if($usuario['tpc_rol_usuario'] == 2){
																$cadena = "SELECT * FROM tp_establecimientos INNER JOIN tp_usuarios ON tpc_asignadoa_establecimiento=tpc_codigo_usuario ".$adicional." ORDER BY tpc_codigo_establecimiento;";
																$con->query($cadena);
															}else{
																$cadena = "SELECT * FROM tp_establecimientos INNER JOIN tp_usuarios ON tpc_asignadoa_establecimiento=tpc_codigo_usuario AND tpc_asignadoa_establecimiento='".$mkid."' ".$adicional." ORDER BY tpc_codigo_establecimiento;";
																$con->query($cadena);
															}
															if($con->num_rows() > 0){
																$roles = array("Plan Gratuito", "Inversionista", "Administrador");
																$estados = array("InActivo", "Activo");
																$contador = 0;$total = $con->num_rows();
																echo '
																<tbody>';
																	while($con->next_record()){
																		$contador++;
																		if($con->f("tpc_estado_usuario") == 0){$activo='<i class="fa fa-check-square-o">';}else{$activo='<i class="fa fa-check-square">';}
																		$imagen = 'N/A';
																		if($con->f("tpc_imagen_establecimiento") != ''){
																			$imagen = '<img src="'.$con->f("tpc_imagen_establecimiento").'" height="40px" width="40px">';
																		}
																		echo '<tr>
																			<td><center><input type="checkbox" id="check'.$contador.'" name="check'.$contador.'" value="'.$con->f("tpc_codigo_establecimiento").'"></center></td>
																			<td><center>'.$imagen.'</center></td>
																			<td><center><label>'.$con->f("tpc_fechacreacion_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_nombre_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_telparticular_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_email_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_direccion_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_ciudad_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_departamento_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_pais_establecimiento").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_nombres_usuario") . ' ' . $con->f("tpc_apellidos_usuario").'</label></center></td>
																			<td><center><label>'.$con->f("tpc_categoria_establecimiento").'</label></center></td>
																			<td><center><label><a href="gestContenido.php?tpc_codigo_establecimiento='.$con->f("tpc_codigo_establecimiento").'&opc=editar&tipo=contacto"><i class="fa fa-edit"></a></label></center></td>
																		</tr>';
																	}
																echo'
																	</tbody><input type="hidden" name="contador" id="contador" value="'.$total.'">';
															}else{
																echo '
																<tbody>
																	<tr>
																		<td colspan="12"><center>No Existen Registros Actualmente</center></td>
																	</tr>
																</tbody>';
															}
														?>
													</table>
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
			if(accion == 'eliminar' && confirm('¿Estas seguro que deseas eliminar los establecimientos seleccionados?') == false){
				return;
			}
			reportecontactos.submit();
		}
	</script>
    <!-- Footer Start-->
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>