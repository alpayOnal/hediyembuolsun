<?php
require_once('ipage.php');
class usersController extends ipage {
	
	public function initialize(){
		parent::initialize();
		$this->users=new \models\users();

		$this->addLib('mailHandler');
	}

	public function register(){
		
		$r=$this->r;

		if($r['origin']=='facebook'){

				$userInfo=$this->getFacebookUserGraph($r['userId'],$r['accessToken']);

				// Return error
				if(is_string($userInfo) && substr($userInfo,0,1)=='0')
					return substr($userInfo,1);

				if(!isset($userInfo->email)) 
					return 'E-posta adresi bilginiz olmadan kayıt olamazsınız!';
			
				$err=$this->checkEmail($userInfo->email);
				if($err!==true)
					return $err;

				$userInfo->first_name=(isset($userInfo->first_name)) ? $userInfo->first_name : NULL;
				$userInfo->last_name=(isset($userInfo->last_name)) ? $userInfo->last_name : NULL;
				$userInfo->user_hometown=(isset($userInfo->user_hometown)) ? $userInfo->user_hometown : NULL;
				$userInfo->user_birthday=(isset($userInfo->user_birthday)) ? $userInfo->user_birthday : NULL;
				
				$c=$this->users->register($r['origin'],	$userInfo);

				// If register okay
				if($c>0){
					// Login for starting the session
					$this->login();
					return 1;
				}
				return 0;



		}
		elseif($r['origin']=='twitter'){
			$origin='twitter';	

		}
	}
	
	public function checkEmail($email=null){
		$r=$this->r;
		if($email!=null)
			$r['email']=$email;
		
		if (isset($r['email'])){
			if ($this->users->checkUserInfo('email',$r['email']))
				return 'E-posta adresi kullanılıyor. Lütfen değiştiriniz.';
			else 
				return true;
		}
	}
	
	public function login(){
		$r=$this->r;

		if($r['origin']=='facebook'){

			$userInfo=$this->getFacebookUserGraph($r['userId'],$r['accessToken']);

			// Return error
			if(is_string($userInfo) && substr($userInfo,0,1)=='0')
				return substr($userInfo,1);

			// Check if the fb. user is registered or not
			// If registered, create a session
			$rtn=$this->users->validateLogin('facebook',$userInfo->email);
			if($rtn!==false){

				$rtn->fbInfo=array('userId'=>$r['userId'],'accessToken'=>$r['accessToken']);
				$this->u=$rtn;
				$this->session->create($rtn);

				setcookie(
					session_name(),session_id(),
					time()+3600*24*150 // oturum ömrü 150 gün olarak belirleniyor
				);

				return true;

			}
			// If not registered, register
			else{
				$r=$this->register();
				if($r!==true)
					return $r;
			}

		}
		elseif($r['origin']=='twitter'){

		}
	}

	public function logout(){
		$this->session->kill();
		header('location:/');
	}
	
	public function getFacebookUserGraph($userId,$accessToken){
		
		if(!isset($accessToken,$userId) 
			|| empty($userId) || empty($accessToken)
			|| !is_numeric($userId))
			return 'OFacebook girişi için geçersiz parametreler!';

		$scope='email,user_birthday,user_hometown';

		//$graphUrl='https://graph.facebook.com/me?access_token='.$accessToken;
		$graphUrl='https://graph.facebook.com/';
		$graphUrl.=$userId.'?fileds='.$scope.'&access_token='.$accessToken;
		
		$userInfoJson=file_get_contents($graphUrl);

		// Return file_get_contents error
		if($userInfoJson===false)
			return '0Facebook\'dan bilgileriniz alınamadı!';

		$userInfo=json_decode($userInfoJson);		
		
		// Return json error
		if($userInfo===NULL)
			return '0Facebook json bilgileri nesneye çevrilemedi!';

		return $userInfo;
	}

}
?>
