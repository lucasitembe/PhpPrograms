<?php
include("../includes/connection.php");
$itemID=$_POST['itemID'];
if(isset($_POST['action'])){
	$itemID=$_POST['itemID'];
    $id=$_POST['id'];
    if($_POST['action']=='delete'){
       $delete="DELETE FROM tbl_tests_parameters WHERE ref_parameter_ID='".$id."' AND ref_item_ID='".$itemID."'";
       $query=  mysqli_query($conn,$delete);
       if($query){
          echo '<table style="width:55%" class="hiv_table">';
            echo '<tr>
                    <th>S/N</th>
                    <th>Paramenter Name</th>
                    <th>Action</th>
                </tr>';

                 $i=1;
                $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$itemID."'");
                while($disp = mysqli_fetch_array($select_sample)){                 
                echo "<tr>";
                echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i++."</td>";
                echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
                        <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$itemID."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
                echo "</tr>";
               }
               echo '</table>';
       }  else {
           echo 'Parameter unset failed';
       }
    }
}else if(isset($_POST['saveparameter'])){
$ParameterName=$_POST['ParameterName'];
$unitofmeasure=$_POST['unitofmeasure'];
$lowervalue=$_POST['lowervalue'];
$highervalue=$_POST['highervalue'];
$Operator=$_POST['Operator'];
$results=$_POST['results'];
$insert=sprintf("INSERT INTO tbl_parameters (Parameter_Name,unit_of_measure,lower_value,operator,higher_value,result_type) VALUES ('%s','%s','%s','%s','%s','%s')",
 mysqli_real_escape_string($conn,$ParameterName),  mysqli_real_escape_string($conn,$unitofmeasure),  mysqli_real_escape_string($conn,$lowervalue),  mysqli_real_escape_string($conn,$Operator),  mysqli_real_escape_string($conn,$highervalue),  mysqli_real_escape_string($conn,$results));
 
 //echo 1;exit();
$query=  mysqli_query($conn,$insert) or die(mysqli_error($conn));

  
if($query){
    echo 'Parameter registered successfully';
}  else {
    echo 'Parameter not saved successfully';
}
    
}

?>

<script>
$('.removeParameter').click(function(){
   var itemID=$(this).attr('name');
   var id=$(this).attr('id');
   $.ajax({
        type:'POST',
        url:'requests/Save_Parameters.php',
        data:'action=delete&itemID='+itemID+'&id='+id,
        success:function(html){
           // alert(html);
            $('#relodParameter').html(html);
        }
});
});
</script>