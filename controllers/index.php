<?php
require_once('ipage.php');
class indexController extends ipage {

	public function initialize(){
		$this->title='hediyembuolsun';
		$this->autRequired=false;
		parent::initialize();

	}

	public function run(){

		if($this->isLogined){

			header('location:/gifts');

		}else
			echo 'Giriş yapılmadı';

		parent::run();
	}
}
?>
