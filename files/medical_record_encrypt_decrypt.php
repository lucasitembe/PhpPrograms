<?php
////////class for encryption decription///////////////////////////////
class Crypt{
 
	private $key;
	private $init_vector_size;
	private $init_vector;
	private $cipher;
	private $mode;
 
	public function __construct() {
		$this->cipher = MCRYPT_RIJNDAEL_128;
		$this->mode   = MCRYPT_MODE_ECB;
		$this->key    = 'gpitg#secret#mronline#';
		$this->init_vector_size = mcrypt_get_iv_size($this->cipher, $this->mode ) ;
		$this->init_vector = mcrypt_create_iv($this->init_vector_size ) ;
	}
 
	public function encrypt( $data ) {
		$data = mcrypt_encrypt( $this->cipher, $this->key, $data, $this->mode, $this->init_vector ) ;
		$data = base64_encode($data) ;
		return $data;
	}
 
 
	public function decrypt ($data) {
		$data = base64_decode($data) ;
		$data = mcrypt_decrypt($this->cipher, $this->key, $data, $this->mode, $this->init_vector);
		return $data;
	}
 
 
}
$crypt = new Crypt();
 
