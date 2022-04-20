<?php

class workerThread extends Thread{

    function __construct($i) {
        $this->i=$i;
    }
    
    public function run() {
        while (true) {
            echo $this->i;
            sleep(10);
        }
    }
    
  

}


for ($index = 0;$index < 50;$index++) {
 $worker[$index]=new workerThread($index);
 
 $worker[$index]->start();

}