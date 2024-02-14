<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
		<script language="javascript">
			function exportarles() {
				$("#datos_a_enviar").val( $("<div>").append( $("#tablaexp").eq(0).clone()).html());
				$("#FormularioExportacion").submit();
			}
		</script>
	</head>
	<body>
<?php
$servername = "localhost";
$database = "grupoamigoscrm_final";
$username = "facastillo";
$password = "M01ses8o8o";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	exit();
}
/*$res = mysqli_query($conn, "SELECT * FROM tp_documentos;");
while($row = mysqli_fetch_array($res)){
	$rand = rand(1, 5);
	$res1=mysqli_query($conn, "INSERT INTO tp_documentos_calificacion VALUES (NULL, '".$row['tpc_id_documento']."', '".$rand."', '".date("Y-m-d H:i:s")."');");
}*/
/*$res = mysqli_query($conn, "SELECT * FROM semiestablecimientos;");
echo '
<table border="1" id="tablaexp" >
<tr>
	<td>Nombre Establecimiento</td>
	<td>Categoria Establecimiento</td>
	<td>Telefono Particular Establecimiento</td>
	<td>Telefono Movil Identificación</td>
	<td>Email</td>
	<td>Asignado A</td>
	<td>Tipo Documento</td>
	<td># Documento</td>
	<td>Dirección</td>
	<td>Pais</td>
	<td>Departamento</td>
	<td>Ciudad</td>
	<td>Localidad</td>
	<td>Video Fundación</td>
	<td>Video Inversionista</td>
	<td>Latitud</td>
	<td>Longitud</td>
	<td>Apertura Dia Hábil</td>
	<td>Cierre Dia Hábil</td>
	<td>Apertura Fin de Semana</td>
	<td>Cierre Fin de Semana</td>
	<td>Sitio Web</td>
	<td>Facebook</td>
</tr>';
while($row = mysqli_fetch_array($res)){
	echo '<tr>
		<td>'.$row[0].'</td>
		<td>'.$row[1].'</td>
		<td>'.$row[2].'</td>
		<td>'.$row[3].'</td>
		<td>'.$row[4].'</td>
		<td>'.$row[5].'</td>
		<td>'.$row[6].'</td>
		<td>'.$row[7].'</td>
		<td>'.$row[8].'</td>
		<td>'.$row[9].'</td>
		<td>'.$row[10].'</td>
		<td>'.$row[11].'</td>
		<td>'.$row[12].'</td>
		<td>'.$row[13].'</td>
		<td>'.$row[14].'</td>
		<td>'.$row[15].'</td>
		<td>'.$row[16].'</td>
		<td>'.$row[17].'</td>
		<td>'.$row[18].'</td>
		<td>'.$row[19].'</td>
		<td>'.$row[20].'</td>
		<td>'.$row[21].'</td>
		<td>'.$row[22].'</td>
	</tr>';
}
echo '</table>
<form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
		<p>Exportar a Excel  <img src="export_to_excel.gif" class="botonExcel" onclick="exportarles()"/></p>
		<input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
	</form>';*/
/*
$res = mysqli_query($conn, "SELECT * FROM vtiger_contactdetails WHERE lastname = 'Zeuss';");
while($row = mysqli_fetch_array($res)){
	$resfi = mysqli_query($conn, "SELECT * FROM vtiger_seattachmentsrel WHERE crmid='".$row['contactid']."';");
	if(mysqli_num_rows($resfi) == 0){
		$res1=mysqli_query($conn, "INSERT INTO vtiger_seattachmentsrel VALUES ('".$row['contactid']."', '39429');");
	}
}*/
/*for($i = 52888; $i <= 53742; $i++){
	echo "INSERT INTO vtiger_seattachmentsrel (crmid, attachmentsid) VALUES ('".$i."', '53743'); <br>";
}
$letras = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "X", "Y", "Z");
for($i = 0; $i <= count($letras); $i++){
	echo "UPDATE vtiger_contactdetails SET lastname=REPLACE(lastname, 'Drogueria".$letras[$i]."', 'Drogueria ".$letras[$i]."'); <br>";
}*/
?>
	</body>
</html>