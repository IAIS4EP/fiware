 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	
	public function index()
	{
		if($this->session->userdata('access_token')){
				$loggedUser = $this->session->userdata('loggedUser');
				$this->data['loggedUser'] = $loggedUser;
				$this->load->view('user',$this->data);
		
		}
		else
			redirect(base_url());
	}
	
	
	function logout(){
		if(!$this->session->userdata('access_token')){
			redirect(base_url());
		}
		$this->session->unset_userdata('access_token');
		$this->session->unset_userdata('loggedUser');
		redirect(base_url());
	
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */
