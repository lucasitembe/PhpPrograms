<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Multipledb {
  var $db = NULL;
  function __construct(){
    $CI = &get_instance();
    $this->db = $CI->load->database('ehms', TRUE); 
  }
  // Add more functions two use commonly.
  public function Department(){
        return $this->db->get('tbl_department')->result();
  }
  public function Branch(){
       return $this->db->get('tbl_branches')->result();
  }
  public function save($data){
     $this->db->insert('tbl_employee', $data);
  }
}