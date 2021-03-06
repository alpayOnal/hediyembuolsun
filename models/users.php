<?php
namespace models;
use \db;
/**
 * kullanıcı işlemlerini yapan sınıftır.
 * 
 * @copyright copyleft
 * @author Alpay ÖNAL
 * @date 26 Jan 2012 11:00
 */
class users
{

	/**
	 * login olunduğunda kullanıcı ids'si saklanıyor.
	 * 
	 * @var int
	 * @access public
	 */
	public $userId;
	
	public function __construct(){

		$this->db=new db();
	}

	/**
	 * kullanıcı tanımlama işlemini yapar.
	 * 
	 * @param string $origin
	 * @param object $fields
	 * @access public
	 * @return int
	 */
	public function register($origin,$fields){
		if($origin=='facebook'){

			$sql='insert into 
				users(fname,lname,email,city,birthDate,origin,metadata) 
				values(
				\''.$this->db->escape($fields->first_name).'\',
				\''.$this->db->escape($fields->last_name).'\',
				\''.$this->db->escape($fields->email).'\',
				\''.$this->db->escape($fields->user_hometown).'\',
				\''.$this->db->escape($fields->user_birthday).'\',
				\''.$this->db->escape($origin).'\',
				\''.$this->db->escape(serialize($fields)).'\')';

		}
		elseif($origin=='twitter'){
			$sql='';
		}
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}

	/**
	*login kontrolü yapar.
	*
	*@param string $origin
	*@param string $field1
	*@param string $field2
	*
	* Giriş kontrolü $origin'e göre değişiklik gösterir;
	* eğer $origin "facebook" ise e-posta ve origin alanları ile kontrol edilir,
	* eğer $origin "twitter" ise e-posta ve origin alanları ile kontrol edilir
	* 
	*return bool
	*/
	public function validateLogin($origin,$field1,$field2=null){

		$sql='select * from users where ';

		if($origin=='facebook'){
			$sql.='email=\''.$this->db->escape($field1).'\' and origin=\'facebook\'';
		}
		elseif($origin=='twitter'){
			$sql='';
		}		

		$r=$this->db->fetchFirst($sql);

		if ($r!==false)
			return $r;
		else
			return false;
	}
	
	/**
	 * gönderilen alanla ilgili kullanıcı var mı yok mu kontrol eder.
	 * 
	 * @param string $field
	 * @param string $value
	 * 
	 * @return bool
	 * */
	public function checkUserInfo($field,$value){
		
		$sql='select * from  users where '.$this->db->escape($field).'=\''.
			$this->db->escape($value).'\'';
		
		if (count($this->db->fetch($sql))>0)
			return true;
		else 
			return false;
	}
	
	/**
	 * kullanıcı id sine bağlı bilgileri verir.
	 * 
	 * 
	 * @return object
	 * */
	public function getUserInfo($userId){
	
		$sql='select * from users where id=\''.$userId.'\' limit 1';
		return $this->db->fetchFirst($sql);
	}
}
?>
