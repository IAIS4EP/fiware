<?php
error_reporting(1);
class Model_user extends CI_Model
{	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();

		
	}
	
	
	/**
	 * User login credential
	 */	
	function login($credential,$userId=0)
	{
		$username = $credential['username'];
		$password = $credential['password'];
		
		$return				= 	new	stdClass();
		$return->status 	= 	false;
		$return->message  	= 	"Username and password could not be blank.";
		
		
		if(($username!='' && $password!='')  || $userId )
		{
			$queryString  = " SELECT u.userId, u.facebookId, u.countryId, u.cityId, u.firstName, u.lastName, u.profileDisplayName, ";
			$queryString .=	" u.aboutMe, u.email, u.profilePhoto, u.gender,u.password, u.privacySetting, u.photoHistory, ";
			$queryString .=	" u.totalBoard, u.totalFollowers, u.totalPhoto, u.totalWishlist, u.totalReview, u.totalFriends, u.totalBooking, ";
			$queryString .=	" (SELECT GROUP_CONCAT(w.restId) FROM rest_wishlist as w WHERE w.userId=u.userId) as wishlistRestIds,";
			$queryString .=	" (SELECT GROUP_CONCAT(vote.sectionId) FROM rest_like_dislike as vote WHERE vote.userId=u.userId and vote.sectionName='reviews' and vote.status='1') as reviewLikeIds,";
			$queryString .=	" (SELECT GROUP_CONCAT(vote.sectionId) FROM rest_like_dislike as vote WHERE vote.userId=u.userId and vote.sectionName='reviews' and vote.status='2') as reviewDislikeIds,";
			$queryString .=	" (SELECT GROUP_CONCAT(flag.sectionId) FROM rest_flag_content as flag WHERE flag.userId=u.userId and flag.sectionName='reviews') as reviewFlagIds,";
			$queryString .=	" u.memberSince, u.mobile, u.sendEmail, u.wrongAttempt, u.activation, u.resetPasswordLinkSend, ";
			$queryString .=	" u.isVerified, u.isMobileVerified, u.isDeleted, u.block, u.restaurantUserType, u.eliteStatus FROM rest_users as u ";
			if($userId)
				$queryString .=	" WHERE  u.userId = '".$userId."'LIMIT 1"; 
			else
				$queryString .=	" WHERE  email = '".$username."' AND password = '".md5($password)."' LIMIT 1"; 	
			
			$result = $this->db->query($queryString);
		
			
			if($result->num_rows()>0)
			{
				$result	=	$result->row();
				if($userId)
					return $result;

				if($result->isVerified !=1){

					$return->status = false;
					$return->message  = "Please activate your account first.";
					
				}elseif($result->isDeleted !=0){

					$return->status = false;
					$return->message  = "Account deleted by user.";
						
				}elseif($result->block !=0){

					$return->status = false;
					$return->message  = "Your account has been blocked by admin due to unauthorize access.";
					
				}else{
					$result->status = true;
					$return	=	$result;
					$this->_insertUserLoginStats($result->userId);
				}				
			}
			else{
				$return->status 	= 	false;
				$return->message  	= 	"Invalid username or password.";
			}
		}		
		return $return;

	}
		 

	public function saveFiwareUser($access_token){
		$this->access_token=$access_token;
		$graph_url 	= "http://account.lab.fi-ware.org/user?access_token=".$access_token;
		$user 		= json_decode(file_get_contents($graph_url));					// USER DATA FROM FACEBOOK
		
		$return['status']	=	false;
		$return['message']	=	"Oops! TableGrabber OverWorked";
		
		if ($user) {
			$iUser  =" SELECT * FROM rest_users as up ";
			$iUser .="WHERE up.fiwareId='".$user->id."' limit 1";	
			$query 	=$this->db->query($iUser);
			$result = $query->row();

			if(!empty($result)){					
				$session =$this->session();
				$session->set_userdata('loggedUser',$result);	
				$return['status']	=	true;	
				$return['message']	=	"Welcome to TableGrabber.";											
			}else{				
				$return	=	$this->_saveFiwareUser((Array)$user);
			}							
							
		}
	return 		$return;
	}
	
	
	private function _saveFiwareUser($userData=array()){
	
		$return	= array();
		$firstName	=	$userData['displayName'];
		$profileDisplayName = $userData['displayName'];
		
		$sUser 	=	" SELECT userId,block,profilePhoto FROM rest_users  WHERE  email='".trim($userData['email'])."'  ";
		$rows 	= 	$this->db->query($sUser);
			
		$userDetails	=	$rows->row();
		
			/*------------------------------------------------------
				IF USER IS ALREADY REGISTERED WITH TABLEGRABBER
			------------------------------------------------------*/
		if(count($userDetails)>0){

			if($userDetails->block){
				$return['status']	=	false;				
				$return['message']	=	"Your account is blocked.";
				return $return;
			}

			

			$userId 		=	$userDetails->userId;
			$iUser 	 =	" UPDATE rest_users SET ";
			$iUser	.=	" fiwareId		=	'".trim($userData['id'])."' , ";										
			$iUser	.=	" isVerified		=	'1',  ";
			$iUser	.=	" publish			=	'1',  ";
			$iUser	.=	" isDeleted			=	'0',  ";
			$iUser	.=	" loginFromWebsite  =	'fiware', ";
			$iUser	.=	" userRegisterFrom 		=	'fiware',";
			$iUser	.=	" fiwareToken  =	'".$this->access_token."' ";
 			$iUser	.=	" WHERE userId ='".$userId."'";

			$this->db->query($iUser);
			//user logged in
			$this->_insertUserLoginStats($userId);
			

			$return['status']	=	true;				
			$return['message']	=	"Wow! you have registered with TableGrabber.";
			$return['userData']	=	$this->login(array(),$userId);
			return $return;	
		}
	
				/*------------------------------------------
					INSERT A NEW USER WITH FiWARE DATA
				-------------------------------------------*/

        $iUser 	=	" 	INSERT INTO rest_users SET 
						fiwareId				=	'".trim($userData['id'])."',
						firstName 				=	'".$firstName."',
						profileDisplayName 		=	'".$profileDisplayName."',
						email 					=	'".trim($userData['email'])."',
						password 				=	'".md5(trim($userData['displayName']))."',
						memberSince 			=	'".date('Y-m-d')."',
						verificationCode 		=	'".$this->tablegrabber->getActivationCode($userData['email'])."',
						referalCode 			=	'".$this->tablegrabber->getReferalCode()."',
						activation 				=	'".$this->tablegrabber->getActivationCode($userData['email'])."',
						isVerified 				=	'1',
						lastvisitDate 			=	'".date('Y-m-d')."',
						publish 				=	'1',
						lastloggedIP 			=	'".$_SERVER['REMOTE_ADDR']."',
						loginFromWebsite 		=	'fiware',
						fiwareToken  			=	'".$this->access_token."',
						userRegisterFrom 		=	'fiware' ";

		$this->db->query($iUser);

		
		$userId = $this->db->insert_id();
		$return['status']	=	true;		
		$return['userData']	=	$this->login(array(),$userId);

		$settingUrl		     = BASEURL.'user/'.$profileDisplayName.'/account-setting';	
		$settingUrl="<a href=' ".$settingUrl	."'>here</a>";
		$return['message']	=	" Thanks for your joining. you can create your own password and change your display name. ".$settingUrl." ";	

		return $return;					
	
	}
	


	private function _insertUserLoginStats($userId){
		$iUpdate 	=	" INSERT INTO rest_users_login_time SET sessionId ='".$this->session->userdata('session_id')."', userId=".$userId.", loginDateTime='".date('Y-m-d H:i:s')."', loggedIP='".$_SERVER['REMOTE_ADDR']."' ";
		$this->db->query($iUpdate);

	}

	

}

?>
