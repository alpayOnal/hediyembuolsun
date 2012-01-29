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
		
		// daha önceden benzer bir hediye tanımı yapıldı ise false döner
		$sql='select id from gifts where 
			title=\''.$this->db->escape($title).'\' and
			userId=\''.$this->db->escape($userId).'\'
			 limit 1';
		$r=$this->db->fetchFirst($sql);
		if ($r) return false;
		
		// en son sıra numarası alınıyor
		$sql='select row from gifts order by row desc limit 1';
		$r=$this->db->fetchFirst($sql);
		
		if (!$r)
			$row=1;
		else
			$row=$r->row+1;
		
		
		$sql='insert into gifts(userId,title,row) 
			values(
			\''.$this->db->escape($userId).'\',
			\''.$this->db->escape($title).'\',
			\''.$this->db->escape($row).'\')';
		
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
		
		$sql='update gifts set removed=1 where 
			userId=\''.$this->db->escape($userId).'\' and 
			id=\''.$this->db->escape($giftId).'\'';			
		
		if ($this->db->query($sql))
			return true;
		else
			return false;
	}
	
	
	/**
	 * hediyelerin listesini verir.
	 * 
	 * @param int $userId
	 * @param int $giftId
	 * @param int $status
	 * @access public
	 * @return bool
	 */
	public function getGifts($userId){
		
		$sql='
			select id,title,row,status from gifts
			where userId=\''.$this->db->escape($userId).'\' and 
			removed=\'0\' 
			order by row desc';			
		$r=$this->db->fetch($sql);
		
		if ($r)
			return $r;
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
	 * hediyenin derecesini günceller
	 * 
	 * @param int $userId
	 * @param int $giftId
	 * @param int $rate
	 * @access public
	 * @return bool
	 */
	public function changeRate($userId,$giftId,$rate){
		
		$sql='
			update 
				gifts 
			set 
				rate=\''.$this->db->escape($rate).'\'
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
	public function changeOrder($userId,$gifts){
		
		foreach($gifts as $i=>$gift){
			$sql='
			update 
				gifts 
			set 
				row=\''.$this->db->escape($i).'\'
			where
				userId=\''.$this->db->escape($userId).'\' and 
				id=\''.$this->db->escape($gift).'\'';			
		
			$r=$this->db->query($sql);
			
			if (!$r)
				return false;			
		}
		
		return true;
	}	
}

?>
