<?php

class Load_loan extends CI_Controller{
function __construct(){

	parent::__construct();

	//$this->load->model('api_model');
}

function index(){
	$this->load->view('loan_view');

}


}