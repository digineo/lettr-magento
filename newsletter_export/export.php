<?php
/*************************************
	Newsletter Export Script für Magento
	Digineo GmbH 2009 | www.digineo.de
	Author: Tim Kretschmer
	Version 1.0
	Lizenz: GNU 3
*************************************/


//The Authentication. Please Update!
$user = "IMPORTANT TO";
$password = "CHANGE!!!";

//API Connection. Please Update!
$api_name = "API_USER";
$api_pass  = "API_PASS";


   



	function getGroupById($id) {
		global $groups;
		foreach ($groups as $group) {
			if( $group['customer_group_id'] == $id ) {
				return $group['customer_group_code'];			
			}
		}
	}
		
	function export(){
		global $proxy;
		global $sessionId;
		global $sessionId;
		
		header ("content-type: text/xml");
		echo "<?xml version='1.0' encoding='utf-8' ?>\n";
		echo "<recipients>\n";
		
		$sql = "SELECT * FROM newsletter_subscriber";
		$ret = mysql_query($sql);
		
		while($return= mysql_fetch_array($ret)) {	
			$user = $proxy->call($sessionId, 'customer.info', array('customer_id' => $return['customer_id']));
			$address = $proxy->call($sessionId, 'customer_address.info', array('addressId' => $user['customer_id'] ));
			echo "
					<recipient>
						<key>". encode_field($user['customer_id'])  ."</key>
						<email>".encode_field($user['email'])."</email>
						<firstname>".encode_field($user['firstname'])."</firstname>
						<lastname>".encode_field($user['lastname'])."</lastname>
						<gender>".encode_field($user['customers_gender'])."</gender>					
						<city>".$address['city']."</city>
						<street>".$address['street']."</street>
						<pcode>".$address['postcode']."</pcode>
						<tag_list>".getGroupById($user['group_id'])."</tag_list>
						<only_text>0</only_text>
						<approved>1</approved>
					</recipient>				
				";	
		}
		echo "</recipients>";
	}
	
	function unsubscribe($recipient){
		$sql = "DELETE FROM newsletter_subscriber WHERE customer_id=".mysql_real_escape_string($_POST['recipient']['key']);
		mysql_query($sql);		
		header("HTTP/1.0 200 OK");
		die("Erfolgreich ausgetragen");
	}
	
	function encode_field($field) {
		return htmlspecialchars(utf8_encode($field));
	}
	
	
if($_SERVER['PHP_AUTH_PW'] != $password || $_SERVER['PHP_AUTH_USER'] != $user) {
	header('WWW-Authenticate: Basic realm="Export"');
	header('HTTP/1.0 401 Unauthorized');
	die("not authorized");
}
if(!file_exists('../app/etc/local.xml')) {
	die("Konfigurationsdatei nicht gefunden");
}

$xml = simplexml_load_file('../app/etc/local.xml');
$db_host= $xml->global->resources->default_setup->connection->host;
$db_user = $xml->global->resources->default_setup->connection->username;
$db_pass = $xml->global->resources->default_setup->connection->password;
$db_name = $xml->global->resources->default_setup->connection->dbname;

mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);

$proxy = new SoapClient('http://'.$_SERVER['HTTP_HOST'].'/magento/index.php/api/soap/index/wsdl/1');
$sessionId = $proxy->login($api_name, $api_pass);	
$groups = $proxy->call($sessionId, 'customer_group.list');	


switch($_SERVER['REQUEST_METHOD']){
	case "GET":
		export();
		break;
	case "POST":
		unsubscribe();
		break;
		
	default:
		header("HTTP/1.0 405 Method Not Allowed");
		die("Method not allowed");
}
?>