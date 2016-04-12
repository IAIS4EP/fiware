 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fiware_Login extends CI_Controller {

	
	 
	function __construct()
	{
		parent::__construct();
		$this->load->model('model_user','',true);
    	}
	
	public function index()
	{
		if($this->session->userdata('access_token')){
			redirect(base_url().'index.php/user');
		}
		else
		    $this->load->view('login');
	}
	
	public function fwlogin(){
	
		session_start();
		
		//fiware application
		$fiwareClientId = $this->config->item( 'fiwareClientId' );
		$fiwareSecret = $this->config->item( 'fiwareSecret' );

		$fwconfig['appid' ]     = $fiwareClientId;
		$fwconfig['secret']     = $fiwareSecret;
		$fwconfig['baseurl']    = base_url();

		//fiware user uid
		$user            =   null; 
		$code = $this->input->get('code');
		
		/*-----------------
			IF FIWARE CODE
		-----------------*/		
				
	   if(empty($code)) {

			
	   		$rand = md5(uniqid(rand(), TRUE));
			$this->session->set_userdata('state',$rand);
			$dialog_url = "https://account.lab.fiware.org/oauth2/authorize?response_type=code&client_id=" 
			. $fwconfig['appid'] . "&redirect_uri=" . urlencode(base_url().'index.php/fiware_login/fwlogin') . "&state="
			. $rand;
			

			 echo("<script> top.location.href='" . $dialog_url . "'</script>");
	   }
		
		/*--------------------------------
			GET ACCESS TOKEN FOR FIWARE USER
		---------------------------------*/
		
		$sessionState=$this->session->userdata('state');
		$requestState=$this->input->get('state');
		if($sessionState && ($sessionState === $requestState)){
    		
			$url="https://account.lab.fiware.org/oauth2/token";
			$params = array();
			$params["client_id"] = $fwconfig['appid'];
			$params["client_secret"] = $fwconfig['secret'];
			$params["grant_type"] = "authorization_code";
			$params["redirect_uri"] = urlencode(base_url().'index.php/fiware_login/fwlogin');
			$params["code"] = $code;
			$fields_string = "";
			//url-ify the data for the POST
			foreach ($params as $key => $value) {
				$fields_string .= $key . '=' . $value . '&';
			}
			rtrim($fields_string, '&');
			//open connection
			$ch = curl_init();
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			//set basic authentication header
			curl_setopt($ch, CURLOPT_USERPWD, $fwconfig['appid'] . ":" . $fwconfig['secret']);
			curl_setopt($ch, CURLOPT_POST, count($params));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			/* Tell cURL NOT to return the headers */
			curl_setopt($ch, CURLOPT_HEADER, false);
			/* Execute cURL, Return Data */
			$data = curl_exec($ch);
			
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			curl_close($ch);
			/* 200 Response! */
			if ($status != 200) {
				$data = false;
			}
			
			$data=json_decode($data,true);
			
     		$this->session->set_userdata('access_token',$data['access_token']); 
     		/*----------* SAVE FIWARE USER DATA *----------*/
 
			$result = $this->model_user->saveFiwareUser($data['access_token']);
			
			if($result['status']){
 				redirect(base_url().'index.php/user');			
			} else{
			//	$this->messages->setMessage($result['message'],'alert alert-warning');
			echo $result['message'];
			}
   		}
   		else {
     		echo("The state does not match. You may be a victim of CSRF.");
   		}
  
		echo "<script>window.opener.location.reload();window.close();</script>";
		die;
	
	}
}



/* End of file fiware_login.php */
/* Location: ./application/controllers/fiware_login.php */
