<?php
$servername = "localhost";
$database = "grupoamigoscrm";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
	exit();
}
$res = mysqli_query($conn, "SELECT * FROM semiestablecimientos;");

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