<?php
class conectar{
	var $link;
	public $bd;
	public $horas = "-1";
	 var $Link_ID  = 0;
	  var $Query_ID = 0;
	  var $Record   = array();
	  var $Row;

	  var $Errno    = 0;
	  var $Error    = "";
	public function conectar()
	{
		$enlace = mysqli_connect('localhost', 'root', '', 'grupoamigoscrm');
		if(mysqli_ping($enlace)){
			$this->link =  mysqli_connect('localhost', 'root', '', 'grupoamigoscrm');
			if (!$this->link) {
				die('<script type="text/javascript">alert("fallo conexion");</script>'.mysql_errno().mysql_error());
			}
		}else{
			$this->link =  mysql_connect('localhost', 'root', '');
			if (!$this->link) {
				die('<script type="text/javascript">alert("fallo conexion");</script>'.mysql_errno().mysql_error());
			}
			$this->bd = mysql_select_db('grupoamigoscrm', $this->link);
			if (!$this->bd) {
				die('<script type="text/javascript">alert("fallo bd");</script>'.mysql_errno().mysql_error());
			}
		}
	}
	public function query($Query_String) {
		$this->conectar();
		if(mysqli_ping($this->link)){
			$this->Query_ID = mysqli_query($this->link,$Query_String);
			$this->Row   = 0;
			if (!$this->Query_ID) {
			  $this->halt("SQL Invalido: ".$Query_String);
			}
		}else{
			$this->Query_ID = mysql_query($Query_String,$this->link);
			$this->Row   = 0;
			$this->Errno = mysql_errno();
			$this->Error = mysql_error();
			if (!$this->Query_ID) {
			  $this->halt("SQL Invalido: ".$Query_String);
			}
		}
		return $this->Query_ID;
	}
	function next_record() {
		if(mysqli_ping($this->link)){
			$this->Record = mysqli_fetch_array($this->Query_ID);
			$this->Row += 1;
			$stat = is_array($this->Record);
			if (!$stat && $this->auto_free) {
			  mysqli_free_result($this->Query_ID);
			  $this->Query_ID = 0;
			}
		}else{
			$this->Record = mysql_fetch_array($this->Query_ID);
			$this->Row   += 1;
			$this->Errno = mysql_errno();
			$this->Error = mysql_error();

			$stat = is_array($this->Record);
			if (!$stat && $this->auto_free) {
			  mysql_free_result($this->Query_ID);
			  $this->Query_ID = 0;
			}
		}
		return $stat;
	}
	function num_rows() {
		if(mysqli_ping($this->link)){
			return mysqli_num_rows($this->Query_ID);
		}else{
			return mysql_num_rows($this->Query_ID);
		}
    }
	function f($Name) {
		return $this->Record[$Name];
	}
	function halt($msg) {
		printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
		/*
		printf("<b>MySQL Error</b>: %s (%s)<br>\n",
		  $this->Errno,
		  $this->Error);
		  */
		die("Session halted.");
	}
}
?>
