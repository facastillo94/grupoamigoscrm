<?php
require '../includes/Conectar.php';
include ('../includes/funciones.php');
$con=new conectar();
$con->conectar();
session_start();
$mkid=$_SESSION["mkid"];
$mksesion=$_SESSION["mksesion"];
validar2($mkid, $mksesion);
$usuario = reg('tp_usuarios', 'tpc_codigo_usuario', $mkid);
$con=new conectar();
$con->conectar();
//$con2
if(isset($_GET['opc'])){
	switch($_GET['opc']){
		case 1://GUADAR INFORME DESDE LANDING PAGE
			if(isset($_GET['tpc_categoria_establecimiento'], $_GET['tpc_categoria_establecimiento'])){
				$ventas=0;$visitas=0;$calificaciones=0;
				//ESTADISTICAS =>
				if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
					$tpc_categoria_establecimiento = $_GET['tpc_categoria_establecimiento'];
					if($tpc_categoria_establecimiento == ""){
						$con->query("SELECT SUM(tpc_valor_establecimiento_pedidos) AS suma FROM tp_establecimiento_pedidos WHERE tpc_fecharegistro_establecimiento_pedidos >= '".date("Y-m")."-01' AND tpc_fecharegistro_establecimiento_pedidos <= '".date("Y-m")."-30';");
						$con->next_record();
						$ventas = intval($con->f("suma"));
						$con->query("SELECT COUNT(*) as visitas FROM tp_solicitudes_analisis WHERE tpc_tipo_solicitud_a='1';");
						$con->next_record();
						$visitas = intval($con->f("visitas"));
						$con->query("SELECT AVG(tpc_calificacion_establecimiento_calificacion) AS promedio FROM tp_establecimiento_calificacion;");
						$con->next_record();
						$calificaciones = number_format($con->f("promedio"), 1, ",", ".");
					}else{
						$con->query("SELECT SUM(tpc_valor_establecimiento_pedidos) AS suma FROM tp_establecimiento_pedidos INNER JOIN tp_documentos_establecimientos ON tpc_documento_establecimiento_pedidos=tpc_documento_docuestab INNER JOIN tp_establecimientos ON tpc_establecimientos_docuestab=tpc_codigo_establecimiento AND tpc_categoria_establecimiento='".$tpc_categoria_establecimiento."' AND tpc_fecharegistro_establecimiento_pedidos >= '".date("Y-m")."-01' AND tpc_fecharegistro_establecimiento_pedidos <= '".date("Y-m")."-30' GROUP BY tpc_codigo_establecimiento_pedidos;");
						while($con->next_record()){
							$ventas += intval($con->f("suma"));
						}
						$con->query("SELECT COUNT(*) as visitas FROM tp_solicitudes_analisis INNER JOIN tp_establecimientos ON tpc_tipo_solicitud_a=tpc_codigo_establecimiento AND tpc_tipo_solicitud_a='1' AND tpc_categoria_establecimiento='".$tpc_categoria_establecimiento."';");
						$con->next_record();
						$visitas = intval($con->f("visitas"));
						$con->query("SELECT AVG(tpc_calificacion_establecimiento_calificacion) AS promedio FROM tp_establecimiento_calificacion INNER JOIN tp_establecimientos ON tpc_codigo_establecimiento=tpc_establecimiento_establecimiento_calificacion AND tpc_categoria_establecimiento='".$tpc_categoria_establecimiento."';");
						$con->next_record();
						$calificaciones = number_format($con->f("promedio"), 1, ",", ".");
					}
				}else{
					$con->query("SELECT AVG(tpc_valor_establecimiento_pedidos) AS suma FROM tp_establecimiento_pedidos INNER JOIN tp_documentos ON tpc_id_documento=tpc_documento_establecimiento_pedidos AND tpc_asignadoa_documento='".$mkid."' AND tpc_fecharegistro_establecimiento_pedidos = '".date("Y-m")."-01' AND tpc_fecharegistro_establecimiento_pedidos <= '".date("Y-m")."-30';");
					$con->next_record();
					$ventas = intval($con->f("suma"));
					$con->query("SELECT COUNT(*) as visitas FROM tp_solicitudes_analisis INNER JOIN tp_establecimientos ON tpc_tipo_solicitud_a=tpc_codigo_establecimiento AND tpc_tipo_solicitud_a='1' AND tpc_asignadoa_establecimiento='".$mkid."';");
					$con->next_record();
					$visitas = intval($con->f("visitas"));
					$con->query("SELECT AVG(tpc_calificacion_establecimiento_calificacion) AS promedio FROM tp_establecimiento_calificacion INNER JOIN tp_establecimientos ON tpc_codigo_establecimiento=tpc_establecimiento_establecimiento_calificacion AND tpc_asignadoa_establecimiento='".$mkid."';");
					$con->next_record();
					$calificaciones = number_format($con->f("promedio"), 1, ",", ".");
				}
				//***************
				$respuesta = array("ventas" => "$".number_format($ventas,0,".", ","), "visitas" => $visitas, "calificaciones" => $calificaciones);
				echo json_encode($respuesta);
			}
		break;
	}
}
?>