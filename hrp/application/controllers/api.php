<?php

class Api extends CI_Controller{
function __construct(){

	parent::__construct();

	$this->load->model('api_model');
}

function index(){
$mydata =array();

$mydata=$this->api_model->get_loan();

}


}