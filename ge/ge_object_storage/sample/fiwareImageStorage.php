<?php
$fs=new FiwareStorage();

if(isset($_POST['action'])) {
	if($_POST['action']=='upload') $fs->upload();
	if($_POST['action']=='remove') $fs->remove();
	if($_POST['action']=='get') {
		$fs->get();
		return;
	}
}

// -----------------------------------------------------------------------------------
class FiwareStorage {

	private $SWIFT_HOST = "localhost"; //object storage server IP or domain. e.g. "http://fiware.objectstorage.com"
	private $SWIFT_PORT = "9999"; //object storage server port. e.g. "80"
	private $SWIFT_CONTAINER = "swiftfun"; //container name for storing images. e.g. "images"
	private $SWIFT_USER = "test:tester"; //user name of existing object storage account. e.g. "test:tester"
	private $SWIFT_KEY = "testing"; //user secret key of existing object storage account. e.g. "testing"
	private $SWIFT_URL = "";

	function __construct() {
		$this->SWIFT_URL = $this->SWIFT_HOST.":".$this->SWIFT_PORT."/auth/v1.0";
	}

	public function upload() {
		if(!isset($_FILES["file"]) || $_FILES["file"]['error'] != UPLOAD_ERR_OK || !is_uploaded_file($_FILES["file"]['tmp_name'])) {
			$fileError = isset($_FILES["file"]) ? $_FILES["file"]['error'] : 'file not uploaded - got empty file';
			$this->showMessage("File Upload Error: " . $fileError);
			return;
		}

		//uploaded file name
		$fileName = $_FILES["file"]['name'];
		$filePath = $_FILES["file"]['tmp_name'];

		try {
			$auth = $this->fiwareAuth();
			if ($auth["errorcode"] != 0) {
				$this->showMessage("Failed to upload file: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"]);
				return;
			}

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
			if (!in_array($httpStatus,array(200,201,202,204)))
				$this->showMessage("Failed to store file. Http Error: "+$httpStatus);
			else
				$this->showMessage("File ".$fileName." successfully uploaded. (size: ".$uploadedFileSize.")");
		} catch(Exception $e) {
			$this->showMessage("Failed to upload file: Error : ".$e->getMessage());
		}
	}

	public function get() {
		$fileName = $_POST['fileName'];

		try {
			$auth = $this->fiwareAuth();
			if ($auth["errorcode"] != 0) {
				$this->showMessage("Failed to get file: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"]);
				return;
			}

			$SWIFT_FILE_URL = $auth["storageUrl"].'/'.$this->SWIFT_CONTAINER.'/'.$fileName;

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$SWIFT_FILE_URL);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ch,CURLOPT_TIMEOUT,60);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch,CURLOPT_HTTPHEADER, array(
				'X-Auth-Token: '.$auth["authToken"]
			));

			$resp = curl_exec ($ch);
			curl_close ($ch);

			return $resp;

		} catch(Exception $e) {
			$this->showMessage("Failed to get file");
		}
	}

	public function remove() {
		$fileName = $_POST['fileName'];

		try {
			$auth = $this->fiwareAuth();
			if ($auth["errorcode"] != 0) {
				$this->showMessage("Failed to remove file: Error [".$auth["errorcode"]."]. Message: ".$auth["errormessage"]);
				return;
			}

			$SWIFT_FILE_URL = $auth["storageUrl"].'/'.$this->SWIFT_CONTAINER.'/'.$fileName;

			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$SWIFT_FILE_URL);
			curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "DELETE");
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
			curl_setopt($ch,CURLOPT_TIMEOUT,60);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_HEADER,1);
			curl_setopt($ch,CURLOPT_HTTPHEADER, array(
				'X-Auth-Token: '.$auth["authToken"]
			));

			curl_exec ($ch);
			curl_close ($ch);
			$this->showMessage($fileName." Has been removed.");
		} catch(Exception $e) {
			$this->showMessage("Failed to remove file");
		}
	}

	public function listFiles() {
		try {
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
			foreach ($files as $fileDesc) {
				$filename = $fileDesc["name"];
				print "<form action=\"./fiwareImageStorage.php\" method=\"post\" enctype=\"multipart/form-data\">
								<input type='hidden' name='action' value='remove'>
								<input type='hidden' name='fileName' value='{$filename}'>
								<b>{$filename}</b> | \t <a href=\"#\" class=\"show-image\" filename='{$filename}'>Show</a> | \t
								<input type=\"submit\" name=\"submit\" value=\"X\">
								<br />
						</form>";
			}

			curl_close ($ch);
		} catch(Exception $e) {
			$this->showMessage("Failed to list files");
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
?>

<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function (e) {
			$(".show-image").click(function(){
				getImage($(this).attr('filename'));
			});
		});

		/*
		 Requires Browsers versions: Firefox: 13.0+ Chrome: 20+ Internet Explorer: 10.0+ Safari: 6.0 Opera: 12.10
		 */
		function getImage(fileName) {
			var data = new FormData();
			data.append('fileName', fileName);
			data.append('action', 'get');

			$.ajax({
				url: "./fiwareImageStorage.php",
				type: "POST",
				data:  data, // Form fields and values
				contentType: false, // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
				cache: false, // Disable request pages caching
				processData:false, // Prevent data processing (we need raw image data)
				dataType: "binary", // Get the response as Binary
				responseType: 'arraybuffer',
				success: function(imageData)
				{
					var arr = new Uint8Array(imageData);

					// Convert the int array to a binary string
					// We have to use apply() as we are converting an *array*
					// and String.fromCharCode() takes one or more single values, not
					// an array.
					var raw = String.fromCharCode.apply(null,arr);

					// convert to BASE64 and set image
					var b64=btoa(raw);
					var dataURL="data:image/jpeg;base64,"+b64;
					$('#image-display').attr('src',dataURL);
				}
			});
		}

		// binary ajax transport plugin for "binary" jQuery dataType
		$.ajaxTransport("+binary", function(options, originalOptions, jqXHR){
			// check for conditions and support for blob / arraybuffer response type
			if (window.FormData && ((options.dataType && (options.dataType == 'binary')) || (options.data && ((window.ArrayBuffer && options.data instanceof ArrayBuffer) || (window.Blob && options.data instanceof Blob)))))
			{
				return {
					// create new XMLHttpRequest
					send: function(headers, callback){
						// setup all variables
						var xhr = new XMLHttpRequest(),
							url = options.url,
							type = options.type,
							async = options.async || true,
						// blob or arraybuffer. Default is blob
							dataType = options.responseType || "blob",
							data = options.data || null,
							username = options.username || null,
							password = options.password || null;

						xhr.addEventListener('load', function(){
							var data = {};
							data[options.dataType] = xhr.response;
							// make callback and send data
							callback(xhr.status, xhr.statusText, data, xhr.getAllResponseHeaders());
						});

						xhr.open(type, url, async, username, password);

						// setup custom headers
						for (var i in headers ) {
							xhr.setRequestHeader(i, headers[i] );
						}

						xhr.responseType = dataType;
						xhr.send(data);
					},
					abort: function(){
						jqXHR.abort();
					}
				};
			}
		});
	</script>
</head>
<body>
<div style='background-color:#dfdfdf; border-width:thin; border-color:#333333; border-style:solid;padding:5px; margin:5px;'>
	<big>Upload new file</big>
	<form action="./fiwareImageStorage.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" id="action" value="upload"><br>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Upload ">
	</form>
</div>
<br />
<table>
	<tr>
		<td>
			<div style='background-color:#dfdfdf; border-width:thin; border-color:#333333; border-style:solid;padding:5px; margin:5px; height:800px; overflow: scroll;'>
				<?php
				$fs->listFiles();
				?>
			</div>
		</td>
		<td valign="top"><div style="width: 500px; height: 500px; border: 2px;"><img id="image-display" src="" style="width: 490px; height: auto;"></div></td>
	</tr>
</table>
</body>
</html>
