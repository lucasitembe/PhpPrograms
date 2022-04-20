<?php
include("../includes/connection.php");
$id=$_POST['id'];
$item=$_POST['specimen'];
$deletespecimen="DELETE FROM tbl_tests_specimen WHERE tests_specimen_ID='".$id."'";
$deleteStatus=  mysqli_query($conn,$deletespecimen);
if($deleteStatus){
    $getItemSpecimen=  mysqli_query($conn,"SELECT * FROM tbl_tests_specimen WHERE tests_item_ID='".$item."'");
    $array=array();
    $numrows=  mysqli_num_rows($getItemSpecimen);
    if($numrows>0){
    while ($result=  mysqli_fetch_assoc($getItemSpecimen)){
  
        $array[]=$result['ref_specimen_ID'];
      
    }
    $rows= implode(',', $array);  
    
    $getspecimenIDS="SELECT * FROM tbl_laboratory_specimen WHERE Specimen_ID NOT IN($rows) ORDER BY Specimen_Name";
    $getIDS=  mysqli_query($conn,$getspecimenIDS);
         while ($resultz=  mysqli_fetch_assoc($getIDS)){
             echo "<tr id='unset".$resultz['Specimen_ID']."'><td width='10px'><input name='Specimen_ID1' class='Specimen_ID'  type='checkbox' id='".$item."' value='".$resultz['Specimen_ID']."'></td><td>".$resultz['Specimen_Name']."</td></tr>";
         }
    }  else {
       $select_specimen =mysqli_query($conn,"SELECT * FROM tbl_laboratory_specimen ORDER BY Specimen_Name");
        while ($result3=  mysqli_fetch_assoc($select_specimen)){
             echo "<tr id='unset".$result3['Specimen_ID']."'><td width='10px'><input name='Specimen_ID' class='Specimen_ID1'  type='checkbox' id='".$item."' value='".$result3['Specimen_ID']."'></td><td>".$result3['Specimen_Name']."</td></tr>";
         }
    }
 
  } else {
    echo 'Delete failed';
}
?>


<script type="text/javascript">
    $('.Specimen_ID1').click(function(){
        var id=$(this).attr('id');
        var specimen=$(this).val();
        $('#unset'+specimen).fadeOut(1000);
            $.ajax({
            type:'POST',
            url:'requests/SaveTestsSpecimen.php',
            data:'asign=asignspcmn&specimen='+specimen+'&itemId='+id,
            cache:false,
             success:function(html){
               // alert(html);
                $('#addedParameter').html(html);
             }
          });
    });

</script>