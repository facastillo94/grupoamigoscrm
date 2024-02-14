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
    <title>Promociones Aliados</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript">
		function confirmar_eliminar(){
			return confirm("¿Seguro/a que deseas eliminar la promoción?");
		}
	</script>
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
                                            <li><span class="bread-blod">Promociones Aliados</span>
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
												<a href="gestContenido.php?opc=nuevo&tipo=promocionaliado" class="btn btn-primary btn-sm" style="background-color: green;width:150px;"><font color="white">+ Crear Promoción</font></a>
                                                <h2>Promociones Aliados</h2>
                                                <div class="view-mail-action view-mail-ov-d-n">
                                                    <a href="promocionesaliados.php"><i class="fa fa-reply"></i> Recargar</a>
                                                    <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Imprimir</a>
                                                    <!-- <a class="compose-discard-bt" href="#"><i class="fa fa-trash-o"></i> Eliminar</a>-->
                                                </div>
                                            </div><br>
                                            <div class="datatable-dashv1-list custom-datatable-overright">
                                                <div id="toolbar1">
                                                    <select class="form-control">
                                                        <option value="">Exportación Básica</option>
                                                        <option value="all">Exportar Todo</option>
                                                        <option value="selected">Exportar Seleccionados</option>
                                                    </select>
                                                </div>
                                                <table id="table1" style="width:100%" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar1">
                                                    <thead>
                                                        <tr>
                                                            <th data-field="state"><center><input type="checkbox" id="checktodos" name="checktodos" value="1"></center></th>
															<th data-field="Nombre"><center>Nombre</center></th>
															<th data-field="Valido"><center>Valido</center></th>
                                                            <th data-field="Archivo"><center>Archivo</center></th>
                                                            <th data-field="Asignado"><center>Asignado A</center></th>
															<th data-field="Editar"><center>Editar</center></th>
															<th data-field="Activar"><center>Activar / InActivar</center></th>
															<th data-field="Eliminar"><center>Eliminar</center></th>
                                                        </tr>
                                                    </thead>
													<?php
														if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN SE MUESTRAN TODAS LAS PROMOCIONES, SINO SOLO SE MUESTRAN LOS ASIGNADOS AL USUARIO ACTUAL
															$con->query("SELECT * FROM tp_promociones_aliados INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_usuario_promociones_aliados AND tpc_rol_usuario='".$_GET['tpc_rol_usuario']."' ORDER BY tpc_codigo_usuario;");
														}else{
															$con->query("SELECT * FROM tp_promociones_aliados INNER JOIN tp_usuarios ON tpc_codigo_usuario=tpc_usuario_promociones_aliados AND tpc_usuario_promociones_aliados='".$mkid."' ORDER BY tpc_codigo_usuario;");
														}
														if($con->num_rows() > 0){
															$estados = array("InActivo", "Activo");
															$contador = 0;
															echo '
															<tbody>';
																while($con->next_record()){
																	$contador++;
																	if($con->f("tpc_estado_promociones_aliados") == 0){$activo='<i class="fa fa-check-square-o">';}else{$activo='<i class="fa fa-check-square">';}
																	echo '<tr>
																		<td><center><input type="checkbox" id="checkespecifico'.$contador.'" name="checktodos" value="'.$con->f("tpc_codigo_promociones_aliados").'"></center></td>
																		<td><center><label>'.$con->f("tpc_nombre_promociones_aliados").'</label></center></td>
																		<td><center><label>De '.$con->f("tpc_validodesde_promociones_aliados").' A '.$con->f("tpc_validohasta_promociones_aliados").'</label></center></td>
																		<td><center><label><a href="'.$con->f("tpc_archivo_promociones_aliados").'" download>Descargar Archivo</label></center></td>
																		<td><center><label>'.$con->f("tpc_nickname_usuario").'</label></center></td>
																		<td><center><label><a href="gestContenido.php?tpc_codigo_promociones_aliados='.$con->f("tpc_codigo_promociones_aliados").'&opc=editar&tipo=promocionaliado"><i class="fa fa-edit"></a></label></center></td>
																		<td><center><label><a href="gestContenido.php?tpc_codigo_promociones_aliados='.$con->f("tpc_codigo_promociones_aliados").'&opc=activacion&tipo=promocionaliado">'.$activo.' '.$estados[$con->f("tpc_estado_promociones_aliados")].'</a></label></center></td>
																		<td>
																			<center>
																				<form method="post" action="gestContenido.php" onSubmit="return confirmar_eliminar()">
																					<input type="hidden" name="tpc_codigo_promociones_aliados" id="tpc_codigo_promociones_aliados" value="'.$con->f("tpc_codigo_promociones_aliados").'">
																					<input type="hidden" name="opc" id="opc" value="eliminar">
																					<input type="hidden" name="tipo" id="tipo" value="promocionaliado">
																					<input type="submit" value="Eliminar" class="btn btn-primary">
																				</form>
																			</center>
																		</td>
																	</tr>';
																}
															echo'<input type="hidden" name="contador" id="contador" value="'.$contador.'">
																</tbody>';
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
    <!-- Footer Start-->
    <?php
		include('footer.php');
		include('pie.php');
	?>
</body>

</html>