<?php
if(isset($_POST["submit"])){
	if($_POST["submit"]=="send"){	

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://quebragalho.esy.es/server.php');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array("method"=>$_POST["method"],"data"=>$_POST["data"]));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);  // RETURN THE CONTENTS OF THE CALL
		$resp = curl_exec($ch);
		var_dump($resp);
		curl_close($ch);
		echo $resp;
	}
}
?>