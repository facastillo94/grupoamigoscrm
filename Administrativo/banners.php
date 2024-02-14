<?php
	require '../includes/Conectar.php';
	include ('../includes/funciones.php');
	session_start();
	$mkid=$_SESSION["mkid"];
	$mksesion=$_SESSION["mksesion"];
	validar2($mkid, $mksesion);
	$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $mkid);
	if($usuario['tpc_rol_usuario'] == 0){
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
    <title>Banners</title>
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
                                            <li><span class="bread-blod">Banners <?php echo $_GET['opc']; ?></span>
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
												<?php
													echo '<a href="gestContenido.php?opc=nuevo&tipo=banner&tipoban='.$_GET['opc'].'" class="btn btn-primary btn-sm" style="background-color: #FF00FF;width:150px;"><font color="white">+ Crear Banner</font></a>
													<h2>Banners '.$_GET['opc'].'</h2>
													<div class="view-mail-action view-mail-ov-d-n">
														<a href="banners.php?opc='.$_GET['opc'].'"><i class="fa fa-reply"></i> Recargar</a>
														<a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Imprimir</a>
														<!-- <a class="compose-discard-bt" href="#"><i class="fa fa-trash-o"></i> Eliminar</a>-->
													</div>';
												?>
                                            </div><br>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
                                                <div id="toolbar1">
                                                    <select class="form-control">
                                                        <option value="">Exportación Básica</option>
                                                        <option value="all">Exportar Todo</option>
                                                        <option value="selected">Exportar Seleccionados</option>
                                                    </select>
                                                </div>
												<form name="reportecontactos" id="reportecontactos" method="post" action="gestContenido.php">
													<?php
														if($_GET['opc'] == "principal"){
															echo '
															<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
																<thead>
																	<tr>
																		<th><center><input type="checkbox" id="checktodos" name="checktodos" value="1" onclick="marcardesmarcar(this.checked)"></center></th>
																		<th data-field="FechaCreacion"><center>Fecha Creación</center></th>
																		<th data-field="Nombre"><center>Nombre</center></th>
																		<!--<th data-field="Particular"><center># De Imagenes</center></th>-->
																		<th data-field="Asignadoa"><center>Asignado a</center></th>
																		<th data-field="Editar"><center>Editar</center></th>
																		<th data-field="Eliminar"><center>Eliminar</center></th>
																	</tr>
																</thead>';
																if($usuario['tpc_rol_usuario'] == 2){
																	$cadena = "SELECT * FROM tp_banner INNER JOIN tp_usuarios ON tpc_usuario_banner=tpc_codigo_usuario GROUP BY tpc_codigo_banner ORDER BY tpc_fechacreacion_banner;";
																	$con->query($cadena);
																}else{
																	$cadena = "SELECT * FROM tp_banner INNER JOIN tp_usuarios ON tpc_usuario_banner='".$mkid."' AND tpc_usuario_banner=tpc_codigo_usuario GROUP BY tpc_codigo_banner ORDER BY tpc_fechacreacion_banner;";
																	$con->query($cadena);
																}
																if($con->num_rows() > 0){
																	$estados = array("InActivo", "Activo");
																	$contador = 0;$total = $con->num_rows();
																	echo '
																	<tbody>';
																		while($con->next_record()){
																			$contador++;$nimagenes = 0;
																			for($i = 1; $i <= 6; $i++){
																				if($con->f("tpc_imagen".$i."_banner") != ""){
																					$nimagenes++;
																				}
																			}
																			echo '<tr>
																				<td><center><input type="checkbox" id="check'.$contador.'" name="check'.$contador.'" value="'.$con->f("tpc_codigo_banner").'"></center></td>
																				<td><center><label>'.$con->f("tpc_fechacreacion_banner").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_nombre_banner").'</label></center></td>
																				<!--<td><center><label>'.$nimagenes.'</label></center></td>-->
																				<td><center><label>'.$con->f("tpc_nombres_usuario") . ' ' . $con->f("tpc_apellidos_usuario").'</label></center></td>
																				<td><center><label><a href="gestContenido.php?tpc_codigo_banner='.$con->f("tpc_codigo_banner").'&opc=editar&tipo=banner&tipoban='.$_GET['opc'].'"><i class="fa fa-edit"></a></label></center></td>
																				<td><center><label><a href="gestContenido.php?tpc_codigo_banner='.$con->f("tpc_codigo_banner").'&opc=eliminar&tipo=banner&tipoban='.$_GET['opc'].'">ELIMINAR</a></label></center></td>
																			</tr>';
																		}//'.$con->f("tpc_codigo_banner").'
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
															echo '</table>';
														}
														if($_GET['opc'] == "secundario"){
															echo '
															<table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
																<thead>
																	<tr>
																		<th><center><input type="checkbox" id="checktodos" name="checktodos" value="1" onclick="marcardesmarcar(this.checked)"></center></th>
																		<th data-field="FechaCreacion"><center>Fecha Creación</center></th>
																		<th data-field="Nombre"><center>Nombre</center></th>
																		<th data-field="Departamento"><center>Departamento</center></th>
																		<th data-field="Ciudad"><center>Ciudad</center></th>
																		<th data-field="Categoria"><center>Categoria</center></th>
																		<th data-field="Asignadoa"><center>Asignado a</center></th>
																		<th data-field="Editar"><center>Editar</center></th>
																		<th data-field="Eliminar"><center>Eliminar</center></th>
																	</tr>
																</thead>';
																if($usuario['tpc_rol_usuario'] == 2){
																	$cadena = "SELECT * FROM tp_banner_cat INNER JOIN tp_usuarios ON tpc_usuario_banner_cat=tpc_codigo_usuario GROUP BY tpc_codigo_banner_cat ORDER BY tpc_fechacreacion_banner_cat;";
																	$con->query($cadena);
																}else{
																	$cadena = "SELECT * FROM tp_banner_cat INNER JOIN tp_usuarios ON tpc_usuario_banner_cat='".$mkid."' AND tpc_usuario_banner_cat=tpc_codigo_usuario GROUP BY tpc_codigo_banner_cat ORDER BY tpc_fechacreacion_banner_cat;";
																	$con->query($cadena);
																}
																if($con->num_rows() > 0){
																	$estados = array("InActivo", "Activo");
																	$contador = 0;$total = $con->num_rows();
																	echo '
																	<tbody>';
																		while($con->next_record()){
																			$contador++;
																			echo '<tr>
																				<td><center><input type="checkbox" id="check'.$contador.'" name="check'.$contador.'" value="'.$con->f("tpc_codigo_banner_cat").'"></center></td>
																				<td><center><label>'.$con->f("tpc_fechacreacion_banner_cat").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_nombre_banner_cat").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_departamento_banner_cat").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_ciudad_banner_cat").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_categoria_banner_cat").'</label></center></td>
																				<td><center><label>'.$con->f("tpc_nombres_usuario") . ' ' . $con->f("tpc_apellidos_usuario").'</label></center></td>
																				<td><center><label><a href="gestContenido.php?tpc_codigo_banner_cat='.$con->f("tpc_codigo_banner_cat").'&tipo=banner&opc=editar&tipoban='.$_GET['opc'].'"><i class="fa fa-edit"></a></label></center></td>
																				<td><center><label><a href="gestContenido.php?tpc_codigo_banner_cat='.$con->f("tpc_codigo_banner_cat").'&tipo=banner&opc=eliminar&tipoban='.$_GET['opc'].'">ELIMINAR</a></label></center></td>
																			</tr>';
																		}//'.$con->f("tpc_codigo_banner_cat").'
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
															echo '</table>';
														}
													?>
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
	</script>
    <!-- Footer Start-->
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>