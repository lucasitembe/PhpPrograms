<?php 

   #middleware
   require 'middleware/middleware.php';

   $data = array(
      array(
         "patient_registration_id" => $_POST['registration_id'],
         "monitoring_date" => $_POST['monitoring_date'],
         "body_weight" => $_POST['body_weight'],
         "comment" => $_POST['comment'],
         " 	done_by" => $_POST['employee_id']
      )
   );

   $receive_data = json_encode($data);
   function save_speech_details($receive_data){
         $json_data=json_encode(array("table"=>"tbl_monitoring_body_weight",
             "data"=>json_decode($receive_data,true)
     ));
     return $json_data;
   }

   query_insert(save_speech_details($receive_data));

?>