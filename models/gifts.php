<?php
namespace models;
use \db;
/**
 * hediye işlemlerini yapan sınıftır.
 * 
 * @copyright copyleft
 * @author Alpay ÖNAL
 * @date 26 Jan 2012 11:00
 */
class gifts
{
	
	public function __construct(){

		$this->db=new db();
	}

	/**
	 * hediye tanımlama işlemini yapar.
	 * 
	 * @param int $userId
	 * @param string $title
	 * @access public
	 * @return bool
	 */
	public function add($userId,$title){
		
		$sql='insert into gifts(userId,title) 
			values(
			\''.$this->db->escape($userId).'\',
			\''.$this->db->escape(serialize($title)).'\')';
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}
	
	/**
	 * hediye silme işlemini yapar.
	 * 
	 * @param int $userId
	 * @param int $giftId
	 * @access public
	 * @return bool
	 */
	public function delete($userId,$giftId){
		
		$sql='delete from gifts where 
			userId=\''.$this->db->escape($userId).'\' and 
			id=\''.$this->db->escape($giftId).'\'';			
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}
	
	
	/**
	 * hediyenin alınıp alınmama durumunu günceller
	 * 
	 * @param int $userId
	 * @param int $giftId
	 * @param int $status
	 * @access public
	 * @return bool
	 */
	public function getGifts($userId){
		
		$sql='
			select * from 
				gifts 
			order by row asc';			
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}
	
	/**
	 * hediyenin alınıp alınmama durumunu günceller
	 * 
	 * @param int $userId
	 * @param int $giftId
	 * @param int $status
	 * @access public
	 * @return bool
	 */
	public function changeStatus($userId,$giftId,$status){
		
		$sql='
			update 
				gifts 
			set 
				status=\''.$this->db->escape($status).'\'
			where
				userId=\''.$this->db->escape($userId).'\' and 
				id=\''.$this->db->escape($giftId).'\'';			
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}
	
	/**
	 * hediyelerin sıralarını günceller
	 * 
	 * @param int $userId
	 * @param int $gifts
	 * @param int $rows
	 * @access public
	 * @return bool
	 */
	public function changeOrder($userId,$gifts,$rows){
				
		foreach($gifts as $i=>$gift){
			$sql='
			update 
				gifts 
			set 
				row=\''.$this->db->escape($rows[$i]).'\'
			where
				userId=\''.$this->db->escape($userId).'\' and 
				id=\''.$this->db->escape($gift).'\'';			
		
			$r=$this->db->query($sql);
			
			if (!$r)
				return false;			
		}
	}
	
	
	
}
?>
