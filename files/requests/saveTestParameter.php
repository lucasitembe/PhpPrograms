<link rel="stylesheet" href="../table.css" media="screen">

<?php
include("../includes/connection.php");
$item=$_POST['item'];
$parameter=$_POST['parameter'];
$getparameter="SELECT * FROM tbl_tests_parameters WHERE ref_parameter_ID='".$parameter."' AND ref_item_ID='".$item."'";
$getNumber=  mysqli_query($conn,$getparameter);
$number_rows=  mysqli_num_rows($getNumber);
if($number_rows>0){
   // echo 'This parameter is already assigned';  
}  else {
    $save="INSERT INTO tbl_tests_parameters(ref_parameter_ID,ref_item_ID) VALUES ('$parameter','$item')";
    $result=  mysqli_query($conn,$save);
    if($result){
        //echo 'Parameter assigned successfully';
    } else {
       // echo 'Failed to assign parameter';
    }
}

echo '<table style="width:55%" class="hiv_table">';
echo '<tr>
        <th>S/N</th>
        <th>Paramenter Name</th>
        <th>Action</th>
    </tr>';
    
     $i=1;
    $select_sample =mysqli_query($conn,"SELECT * FROM tbl_tests_parameters inner join tbl_parameters on ref_parameter_ID=parameter_ID where ref_item_ID='".$item."'");
    while($disp = mysqli_fetch_array($select_sample)){                 
    echo "<tr>";
    echo "<td style='color:blue;border:1px solid #ccc;width:4px;'>".$i++."</td>";
    echo "<td style='color:blue;border:1px solid #ccc;width:500px;'>".$disp['Parameter_Name']."</td>
            <td style='color:blue;border:1px solid #ccc;width:4px;'><button class='removeParameter' name='".$item."' id='".$disp['ref_parameter_ID']."'>Remove</button></td>";
    echo "</tr>";
   }
   echo '</table>';
?>

<script>
  $('.removeParameter').click(function(){
      var id=$(this).attr('id');
      var itemID=$(this).attr('name');
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