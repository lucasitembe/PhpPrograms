<?php
include("../includes/connection.php");
if(isset($_POST['action'])){
   $item=$_POST['itemId'];
   $specimen=$_POST['id'];
   if($_POST['action']=='delete'){
      $deleQuery="DELETE FROM tbl_tests_specimen WHERE tests_item_ID='".$item."' AND ref_specimen_ID='".$specimen."'";
      $result=  mysql_query($deleQuery);
      if($result){
            $getItemSpecimen=mysql_query("SELECT * FROM tbl_tests_specimen WHERE tests_item_ID='".$item."'");
            $array=array();
            $numrows=  mysql_num_rows($getItemSpecimen);
            if($numrows>0){
            while ($result=  mysql_fetch_assoc($getItemSpecimen)){
                $array[]=$result['ref_specimen_ID'];
            }
            $rows= implode(',', $array); 
            $getspecimenIDS="SELECT * FROM tbl_laboratory_specimen WHERE Specimen_ID NOT IN($rows) ORDER BY Specimen_Name";
            
            $getIDS=  mysql_query($getspecimenIDS);
            while ($result5=  mysql_fetch_assoc($getIDS))
                {
                  echo "<tr id='unset".$result5['Specimen_ID']."'><td width='10px'><input name='Specimen_ID' class='Set_Specimen'  type='checkbox' id='".$item."' value='".$result5['Specimen_ID']."'></td><td>".$result5['Specimen_Name']."</td></tr>";
                }
                
             } else {
               $select_specimen =mysql_query("SELECT * FROM tbl_laboratory_specimen ORDER BY Specimen_Name");
               while ($result3=  mysql_fetch_assoc($select_specimen)){
                     echo "<tr id='unset".$result3['Specimen_ID']."'><td width='10px'><input name='Specimen_ID' class='Set_Specimen'  type='checkbox' id='".$item."' value='".$result3['Specimen_ID']."'></td><td>".$result3['Specimen_Name']."</td></tr>";
                 }
            }
         
           } else {
        //  echo 'Specimen unset error';
      }
    }
} else {
   $item=$_POST['itemId'];
   $specimen=$_POST['specimen'];
    $checkExist="SELECT * FROM tbl_tests_specimen WHERE tests_item_ID='".$item."' AND ref_specimen_ID='".$specimen."'";
    $getResult=  mysql_query($checkExist);
  $numrows=  mysql_num_rows($getResult);
  if($numrows>0){
     // echo 'Specimen is set already';
  } else {
     $saveData="INSERT INTO tbl_tests_specimen (tests_item_ID,ref_specimen_ID) VALUES ('$item','$specimen')";
     $query=  mysql_query($saveData);
     if($query){
         $getItemSpecimen=  mysql_query("SELECT * FROM tbl_tests_specimen INNER JOIN tbl_laboratory_specimen tbl_laboratory_specimen ON ref_specimen_ID=Specimen_ID WHERE tests_item_ID='".$item."' GROUP BY Specimen_ID ORDER BY Specimen_Name");
         while ($result=  mysql_fetch_assoc($getItemSpecimen)){
             echo "<tr id='unset".$result['tests_specimen_ID']."'><td width='10px'><input name='Specimen_ID' class='Specimen_ID'  type='checkbox' id='".$result['tests_specimen_ID']."' value='".$item."' checked='true'></td><td>".$result['Specimen_Name']."</td></tr>";
         }
         
     }else{
         echo 'Specimen not set';
     }
      
}   
}
?>


<script type="text/javascript">
    $('.Specimen_ID').click(function(){
      var id=$(this).attr('id');
      var specimen=$(this).val();
      $('#unset'+id).fadeOut(1000);
     $.ajax({
     type:'POST',
     url:'requests/DeleteSpecimen.php',
     data:'specimen='+specimen+'&id='+id,
     cache:false,
      success:function(html){
        // alert(html);
         $('#assignParameter').html(html);
      }
       
   });
      
    });
    
    //assign specimen to tests
$('.Set_Specimen').click(function(){
   var specimen=$(this).val();
   $('#unset'+specimen).fadeOut(1000);
   var itemId=$(this).attr('id');
   $.ajax({
     type:'POST',
     url:'requests/SaveTestsSpecimen.php',
     data:'asign=asignspcmn&specimen='+specimen+'&itemId='+itemId,
     cache:false,
      success:function(html){
        // alert(html);
         $('#addedParameter').html(html);
      }
       
   });
});
    
    
</script>