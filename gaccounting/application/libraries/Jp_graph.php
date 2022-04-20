<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH.'/third_party/jp-graph/jpgraph.php';
include_once APPPATH.'/third_party/jp-graph/jpgraph_bar.php';
include_once APPPATH.'/third_party/jp-graph/jpgraph_line.php';
 
class Jp_graph {
 
    public $param;
    public $graph;
    
 
    public function __construct($param = '750,320,"auto"')
    {
        $this->param =$param;
        $this->graph =new Graph(750,320,"auto");
    }
}
