<?php
require_once('ipage.php');
class giftsController extends ipage {
	
	public function initialize(){
		parent::initialize();
		$this->gifts=new \models\gifts();

	}

	public function add(){
		
		$r=$this->r;

		if(isset($r['title'])){
			$rs=$this->gifts->add($this->u->id,$r['title']);
			if ($rs)
				return 'Hediye tanımlandı.';
			else
				return 'Hediye tanımlanamadı! Benzer hediye kaydı bulunuyor.';
		}
	}
	
	public function delete(){
		
		$r=$this->r;

		if(isset($r['giftId'])){
			$rs=$this->gifts->delete($this->u->id,$r['giftId']);
			if ($rs)
				return 'Hediye silindi.';
			else
				return 'Hediye silinemedi! Bir hata oluştu.';
		}
	}
	
	public function changeStatus(){
		
		$r=$this->r;

		if(isset($r['giftId']) && isset($r['status'])){
			$rs=$this->gifts->changeStatus($this->u->id,$r['giftId'],$r['status']);
			if ($rs && $r['status']==1)
				return 'Hediye alındı.';
			elseif ($rs && $r['status']==0)
				return 'Hediye alınmadı.';
		}
	}
	
	public function changeRate(){
		
		$r=$this->r;

		if(isset($r['giftId']) && isset($r['rate'])){
			$rs=$this->gifts->changeRate(
				$this->u->id,$r['giftId'],$r['rate']);
			if ($rs)
				return 1;
			else
				return 0;
		}
	}
	
	public function changeOrder(){
		
		$r=$this->r;

		if(isset($r['gifts'])){
			$gifts=array_reverse($r['gifts']);
			$rs=$this->gifts->changeOrder($this->u->id,$gifts);
			if ($rs)
				return 1;
			else
				return 'Sıralama güncellenemedi! Bir hata oluştu!';
		}
	}
	
	
	public function getGifts(){
		
		$r=$this->r;
		$rs=$this->gifts->getGifts($this->u->id);
		
		return $this->loadView(
			'gifts.php',
			$rs,
			false
		);
	}
}
?>
