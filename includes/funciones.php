<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
date_default_timezone_set('America/Bogota');
$cabecera_mail="";
$pie_mail="";


foreach($_POST as $key=>$value) { $$key = $value; } 
foreach($_GET as $key=>$value) { $$key = $value; }
foreach($_COOKIE as $key=>$value) { $$key = $value; }
foreach($_FILES as $key=>$value) { $$key = $value; }
$tsesion=3600;
function actualiza($tabla,$campo,$variable,$datos)
{
        $conexion = new Database;
        $conexion->query("SHOW COLUMNS FROM $tabla");
        while($conexion->next_record())
        {
            $campo1=$conexion->f(0);
            $variable1=$datos[$campo1];
            if($variable1)
            {
                $query.=" $campo1='$variable1',";
            }
        }
        $query=substr($query,0,-1);
        $query="UPDATE $tabla SET $query WHERE $campo='$variable'";
        $conexion->query($query);
}
function guarda($tabla,$datos)
{
    $conexion = new Database;    
    $conexion->query("SHOW COLUMNS FROM $tabla");
    while($conexion->next_record())
    {
        $campo=$conexion->f(0);
        $variable=$datos[$campo];
        if($variable)
            {
                $fields.=" $campo,";
                $vars.=" '$variable',";
            }
        }
        $fields=substr($fields,0,-1);
        $vars=substr($vars,0,-1);
        $query="INSERT INTO $tabla ($fields) VALUES ($vars)";        
        $conexion->query($query);
}
function getStringBetween($str,$from,$to)
{
    $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
    return substr($sub,0,strpos($sub,$to));
}
function exe_query($query)
{
    $conexion44 = new Database;    
    $conexion44->query($query);
    return '';
}

function f2ts($fecha) 
{
$fecha=split('/',$fecha);
$fecha=mktime(0,0,0,$fecha[1],$fecha[0],$fecha[2]);
return $fecha;
}

function f2ts2($fecha) 
{
$fecha=split('-',$fecha);
$fecha=mktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]);
return $fecha;
}

function reg($table,$field,$value,$adicional='',$sufijo='')
{
    $i=0;
    $conexion = new conectar;	
    $conexion->query("SHOW COLUMNS FROM $table");
    while($conexion->next_record())
    {
        $fields[$i]=$conexion->f(0);
        $i++;
    }
    $conexion->query("SELECT * FROM $table WHERE $field='$value' $adicional");
    $conexion->next_record();
    for($i=0;$i<count($fields);$i++)
    {
        $reg[$fields[$i]]= $conexion->f($i);        
    }
    return $reg;    
}

function dia($dia)
{
    if ($dia==1) return "Lunes";
    if ($dia==2) return "Martes";
    if ($dia==3) return "Miercoles";
    if ($dia==4) return "Jueves";
    if ($dia==5) return "Viernes";
    if ($dia==6) return "Sabado";
    if ($dia==7) return "Domingo";
}

function normaliza ($cadena){
    $originales =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtolower($cadena);
    return utf8_encode($cadena);
}
function quitar_tildes($cadena) {
	$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã","Ã‹");
	$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
	$texto = str_replace($no_permitidas, $permitidas ,$cadena);
	return $texto;
}

function mes ($numero_mes){

    switch ($numero_mes){
        case 1:
            $mes='Ene';break;
        case 2:
            $mes='Feb';break;
        case 3:
            $mes='Mar';break;
        case 4:
            $mes='Abr';break;
        case 5:
            $mes='Mayo';break;
        case 6:
            $mes='Jun';break;
        case 7:
            $mes='Jul';break;
        case 8:
            $mes='Ago';break;
        case 9:
            $mes='Sep';break;
        case 10:
            $mes='Oct';break;
        case 11:
            $mes='Nov';break;
        case 12:
            $mes='Dic';break;
    }
    return $mes;
}
function validar2($mkid,$mksesion)
{
	session_start();
	if (!isset($_SESSION["mkid"]))  {
		session_destroy();
		echo '<script type="text/javascript">location.href="../index.php";</script>';
		exit();
	}
}
function crearNombre($length) 
{ 
    if( ! isset($length) or ! is_numeric($length) ) $length=6; 
     
    $str  = "0123456789abcdefghijklmnopqrstuvwxyz";
    $path = ''; 
     
    for($i=1 ; $i<$length ; $i++) 
      $path .= $str{rand(0,strlen($str)-1)};

    return $path.'_'.date("d-m-Y_H-i-s").'.pdf';
}
function alatitudlongitud($direccion_recibida, $ciudad, $pais, $tpc_codigo_establecimiento, $entidad = 0){
	$conn=new conectar();
	$conn->conectar();
	$direccion = $direccion_recibida . ' '.$ciudad.', '.$pais;
	$desglozada = $direccion;
	//AC AK CL CR CALLE CARRERA CRA TV TRANSVERSAL NO.
	$desglozada = str_replace("AC", "Cl.", $desglozada);
	$desglozada = str_replace("AK", "Cra.", $desglozada);
	$desglozada = str_replace("CL", "Cl.", $desglozada);
	$desglozada = str_replace("CRA", "Cra.", $desglozada);
	$desglozada = str_replace("CR", "Cra.", $desglozada);
	$desglozada = str_replace("CALLE", "Cl.", $desglozada);
	$desglozada = str_replace("CARRERA", "Cra.", $desglozada);
	$desglozada = str_replace("TV", "Tv.", $desglozada);
	$desglozada = str_replace("TRANSVERSAL", "Tv.", $desglozada);
	$desglozada = str_replace("NO.", "N.", $desglozada);
	$desglozada = str_replace("Avenida Carrera", "Cra.", $desglozada);
	$desglozada = str_replace("Carrera", "Cra.", $desglozada);
	$desglozada = str_replace("#", "N.", $desglozada);
	$desglozada = str_replace("Diagonal", "Cl.", $desglozada);
	$desglozada = str_replace("Avenida Calle", "Cl.", $desglozada);
	$desglozada = str_replace("Calle", "Cl.", $desglozada);
	$desglozada = str_replace(" D.C", "", $desglozada);
	$desglozada = str_replace("..", ".", $desglozada);
	$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.urlencode($desglozada).'&key=AIzaSyB2o5-m_UGT3yYU8M5HJUpTW4FNKvga90s');
	// Convertir el JSON en array.
	$geo = json_decode($geo, true);
	// Si todo esta bien
	if ($geo['status'] == 'OK') {
		// Obtener los valores
		$latitud = $geo['results'][0]['geometry']['location']['lat'];
		$longitud = $geo['results'][0]['geometry']['location']['lng'];
		switch($entidad){
			case 0: //ESTABLECIMIENTOS; POR DEFECTO
				$conn->query("UPDATE tp_establecimientos SET tpc_longitud_establecimiento='".$longitud."', tpc_latitud_establecimiento='".$latitud."' WHERE tpc_codigo_establecimiento='".$tpc_codigo_establecimiento."';");
			break;
			case 1: //USUARIOS ALIADOS O PATROCINADORES
				$conn->query("UPDATE tp_usuarios SET tpc_longitud_usuario='".$longitud."', tpc_latitud_usuario='".$latitud."' WHERE tpc_codigo_usuario='".$tpc_codigo_establecimiento."';");
			break;
			case 2: //EVENTOS
				$conn->query("UPDATE tp_evento SET tpc_longitud_evento='".$longitud."', tpc_latitud_evento='".$latitud."' WHERE tpc_codigo_evento='".$tpc_codigo_establecimiento."'");
			break;
		}
	}
}
function sendMensajeEmail($correo, $subject, $message){
	//ENVIAR CORREO
	$to = $correo;
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; charset=UTF-8 \r\n";  
	$headers .= "From: no-reply@guiakmymedio.com.co";
	mail($to, $subject, $message, $headers);
	//*************
}
?>