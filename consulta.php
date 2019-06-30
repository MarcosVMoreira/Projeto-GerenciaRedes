<?php

	//$host = $_POST['ipAlvo'];
	$host = "192.168.100.8";

	$tipoConsulta = $_POST['tipoConsulta'];

	$sec_name = "gerente";
	$sec_level = "authPriv";
	$auth_protocol = "MD5";
	$auth_passphrase = "novasenha123";
	$priv_protocol = "DES";
	$priv_passphrase = "novasenha123";
	$object_id = "1.3.6.1.2.1.2.2.1.10.2";
	$timeout = "100000";
	$retries = "3";


	if ($tipoConsulta == "banda") {
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);

		$data_out = explode(" ", $ifInOctets);
	
		$data_ifIn = array();
		$label = array();
	
		$object_id = "1.3.6.1.2.1.2.2.1.16.2";
	
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
	
		$data_in = explode(" ", $ifInOctets);
	
		$resultado = array(label => date("H:i:s"), y => (int)$data_out[1], y1 => (int)$data_in[1]);
	
	} else if ($tipoConsulta == "tcpudp") {
		$object_id = "1.3.6.1.2.1.6.11.0";

		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);

		$data_out = explode(" ", $ifInOctets);

		$data_ifIn = array();
		$label = array();
	
		$object_id = "1.3.6.1.2.1.7.4.0";
	
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
	
		$data_in = explode(" ", $ifInOctets);
	
		$resultado = array(label => date("H:i:s"), y => (int)$data_out[1], y1 => (int)$data_in[1]);
	
	} else if ($tipoConsulta == "tcpudpIn") {
		$object_id = "1.3.6.1.2.1.6.10.0";

		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);

		$data_out = explode(" ", $ifInOctets);

		$data_ifIn = array();
		$label = array();
	
		$object_id = "1.3.6.1.2.1.7.1.0";
	
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
	
		$data_in = explode(" ", $ifInOctets);
	
		$resultado = array(label => date("H:i:s"), y => (int)$data_out[1], y1 => (int)$data_in[1]);
	
	} else if ($tipoConsulta == "maquinaInfo") {
		//tempo ligado
		$object_id = "1.3.6.1.2.1.25.1.1.0";

		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
		
		$data_1 = explode(" ", $ifInOctets);

		//total de RAM
		$object_id = "1.3.6.1.4.1.2021.4.5.0";
	
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
		
		$data_2 = explode(" ", $ifInOctets);

		$object_id = "1.3.6.1.2.1.25.3.2.1.3.196608";

		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
		
		$data_3 = explode("\"", $ifInOctets);
		
		$object_id = "1.3.6.1.2.1.1.1.0";
	
		$ifInOctets = snmp3_get($host, $sec_name, $sec_level, $auth_protocol, $auth_passphrase, $priv_protocol, $priv_passphrase , $object_id, $timeout, $retries);
		

		$data_4 = explode("\"", $ifInOctets);
	
		$resultado = array(1 => $data_1[2], 2 => $data_2[1], 
		3 => $data_3[1], 4 => $data_4[1]);
	
	}
	


	//3.6.1.2.1.1.1.0 SO
	//3.6.1.2.1.2.2.1.2.2 placa de rede
	//3.6.1.2.1.25.3.2.1.3.196608 cpu
	//3.6.1.2.1.25.6.3.1.2.6 servidor web do cara

	echo json_encode($resultado, JSON_NUMERIC_CHECK);

?>

