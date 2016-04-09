<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_Storage extends CI_Controller {

	private $SWIFT_HOST = "http://54.213.242.73"; //object storage server IP or domain. e.g. "http://fiware.objectstorage.com"
	private $SWIFT_PORT = "8080"; //object storage server port. e.g. "80"
	private $SWIFT_CONTAINER = "testcontainer"; //container name for storing images. e.g. "images"
	private $SWIFT_USER = "test:tester"; //user name of existing object storage account. e.g. "test:tester"
	private $SWIFT_KEY = "testing"; //user secret key of existing object storage account. e.g. "testing"
	private $SWIFT_URL = "";
	function __construct(){
		parent::__construct();
		$this->SWIFT_URL = $this->SWIFT_HOST.":".$this->SWIFT_PORT."/auth/v1.0";
	}
	
	
	public function index()
	{ 
		$status=''; 
		try {
		
			if(isset($_POST['action'])) {  echo $_POST['action'];
				if($_POST['action']=='upload') 
					echo $status =	$this->upload();
				
			} 
			$auth = $this->fiwareAuth();
			if ($auth["errorcode"] != 0) {
				$this->showMessage("Failed to list files: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"]);
				return;
			}
			$SWIFT_STORAGE_URL = $auth["storageUrl"].'/'.$this->SWIFT_CONTAINER;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$SWIFT_STORAGE_URL.'?format=json');
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ch,CURLOPT_TIMEOUT,60);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch,CURLOPT_HTTPHEADER, array(
				'X-Auth-Token: '.$auth["authToken"]
			));
			$resp = curl_exec ($ch);
			$files = json_decode($resp,true);
			
			$this->data['files']=$files;
			$this->data['status']=$status;
			
			curl_close ($ch);
			
			$this->load->view('fiwareImageStorage', $this->data );
		
			
		} catch(Exception $e) {
			$this->showMessage("Failed to list files");
		}
	}
	
	public function upload(){
	echo 'naina';
		if(!isset($_FILES["file"]) || $_FILES["file"]['error'] != UPLOAD_ERR_OK || !is_uploaded_file($_FILES["file"]['tmp_name'])) {
			$fileError = isset($_FILES["file"]) ? $_FILES["file"]['error'] : 'file not uploaded - got empty file';
			$status = "File Upload Error: " . $fileError;
			return $status;
		}
		//uploaded file name
		echo $fileName = $_FILES["file"]['name'];
		$filePath = $_FILES["file"]['tmp_name'];
		try {
			$auth = $this->fiwareAuth(); print_r($auth);
			if ($auth["errorcode"] != 0) {
				$status = "Failed to upload file: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"];
				return $status;
			}
			
			//header("Content-Type: image/jpeg");
			$SWIFT_FILE_URL = $auth["storageUrl"].'/'.$this->SWIFT_CONTAINER.'/'.$fileName;
			$uploadedFileSize = filesize($filePath);
			$fh_res = fopen($filePath, 'r');
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$SWIFT_FILE_URL);
			curl_setopt($ch,CURLOPT_PUT, 1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ch,CURLOPT_TIMEOUT,60);
			curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
			curl_setopt($ch,CURLOPT_HEADER,1);
			curl_setopt($ch,CURLOPT_HTTPHEADER, array(
				'X-Auth-Token: '.$auth["authToken"],
				'content-length: '.$uploadedFileSize
			));
			curl_setopt($ch,CURLOPT_INFILE, $fh_res);
			curl_setopt($ch,CURLOPT_INFILESIZE, $uploadedFileSize);
			$resp = curl_exec ($ch); 
			fclose($fh_res);
			$info = curl_getinfo($ch);
			$httpStatus = isset($info['http_code']) ? (int)$info['http_code'] : 500;
			curl_close ($ch);
			if (!in_array($httpStatus,array(200,201,202,204))){
				echo $status = "Failed to store file. Http Error: "+$httpStatus;
				return $status;
			}	
			else{
				echo $status = "File ".$fileName." successfully uploaded. (size: ".$uploadedFileSize.")"; 
				return $status; 
				}
		} catch(Exception $e) {
			echo $status = "Failed to upload file: Error : ".$e->getMessage();
			return $status; 
		}
		
	}
	
	public function getImage(){
		$fileName = $_POST['fileName'];
		try {
			$auth = $this->fiwareAuth(); //print_r($auth);
			if ($auth["errorcode"] != 0) { 
				$this->showMessage("Failed to get file: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"]);
				return;
			}
		
			$SWIFT_FILE_URL = $auth["storageUrl"].'/'.$this->SWIFT_CONTAINER.'/'.$fileName;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$SWIFT_FILE_URL); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ch, CURLOPT_TIMEOUT,60);
			curl_setopt($ch, CURLOPT_HEADER,0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'X-Auth-Token: '.$auth["authToken"]
			));
			$resp = curl_exec($ch);
			curl_close($ch);
			return $resp; 
			
		} catch(Exception $e) {
			$this->showMessage("Failed to get file");
		}
	}
	
	private function fiwareAuth() {
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$this->SWIFT_URL);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		curl_setopt($ch,CURLOPT_TIMEOUT,60);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_HEADER,1);
		curl_setopt($ch,CURLOPT_HTTPHEADER, array(
			/*'X-Storage-User: '*/'X-Auth-User: '.$this->SWIFT_USER,
			/*'X-Storage-Pass: '*/'X-Auth-Key: '.$this->SWIFT_KEY
		));
		$response = curl_exec($ch);
		$headers = $this->getCurlHeaders($response);
		$info = curl_getinfo($ch);
		$httpStatus = isset($info['http_code']) ? (int)$info['http_code'] : 500;
		if ($httpStatus != 200) {
			curl_close ($ch);
			return array("errorcode"=>1,"errormessage"=>"Authentication Failed");
		}
		$storageUrl = $headers['x-storage-url'];
		$authToken = $headers['x-auth-token'];
		if (!isset($storageUrl) || $storageUrl == '' || !isset($authToken) || $authToken == '') {
			curl_close ($ch);
			return array("errorcode"=>2,"errormessage"=>"Authentication Failed");
		}
		curl_close ($ch);
		return array("errorcode"=>0,"storageUrl"=>$storageUrl,"authToken"=>$authToken);
	}
	private function getCurlHeaders($response) {
		$headers = array();
		$header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
		foreach (explode("\r\n", $header_text) as $i => $line)
			if ($i === 0)
				$headers['http_code'] = $line;
			else {
				list ($key, $value) = explode(': ', $line);
				$headers[strtolower($key)] = $value;
			}
		return $headers;
	}
	private function showMessage($message) {
		print "<div style='background-color:#efefef; border-width:thin; border-color:#00ef00; border-style:solid;padding:5px; margin:5px; id='message'>{$message}</div><br /><hr />";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */