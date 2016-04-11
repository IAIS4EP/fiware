 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */