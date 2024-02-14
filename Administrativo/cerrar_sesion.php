<?php
session_start();
/*if (isset($_SERVER['HTTP_COOKIE'])) {
	$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
	foreach($cookies as $cookie) {
		$parts = explode('=', $cookie);
		$name = trim($parts[0]);
		setcookie($name, '', time()-9999);
		setcookie($name, '', time()-9999, '/');
	}
}*/
session_destroy();
session_commit();
echo '<script type="text/javascript">location.href="../index.php";</script>';
exit();
?>