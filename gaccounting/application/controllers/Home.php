<?php
/**
 * gAccounting
 *
 * An accounting application development framework for PHP
 *
 * Designed for eHMS and can be used as standalone application
 * 
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2016 - 2017, Kawe Tanzania
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	gAccounting
 * @author	GPITG Dev Team, Fadhili Habib Mvungi, Gabriel Patrick, Nassor H. Nassor,Daniel Elias Ngungath
 * @copyright	Copyright (c) 2016 - 2017, Gpitg, Inc. (https://gpitg.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */

defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends CI_Controller{
    public function __construct() {
        parent::__construct();
        
        if(!isset($_SESSION['userinfo'])){
            redirect('account/login');
        }
    }
    
    public function index() {
        $this->load->view('shared/header');
        $this->load->view("home/index");
        $this->load->view('shared/footer');
    }
}
