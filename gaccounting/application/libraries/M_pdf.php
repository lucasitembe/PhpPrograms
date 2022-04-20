<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH . '/third_party/mpdf/mpdf.php';

class M_pdf {

    public $is_land;
    public $pdf;

    public function __construct($param) {
        $this->is_land = $param[0];
        if ($this->is_land) {
            $this->pdf = new mPDF("c", "A4-L");
        } else {
            $this->pdf = new mPDF("c", "A4");
        }
    }

}
