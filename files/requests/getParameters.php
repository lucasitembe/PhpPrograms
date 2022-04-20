<?php
    include("../includes/connection.php");
    $temp = 1;
    $filterval = "";
    echo '<br/>';
    echo '<center><table><input type="text" id="searchfilter" style="width:300px;" onkeyup="filterFunction()" placeholder="~~~~~~~~~~~~~~~~~~~Search~~~~~~~~~~~~~~~~~"/></table></center>';
    echo '<center><table width =100% border=0 id="searchtd">';
    echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
    <td><b>Parameter Name</b></td>
    <td><b>Unit_of_Measure</b></td>
    <td><b>Lower value</b></td>
    <td><b>Higher value</b></td>
    <td><b>Operator</b></td>
    <td><b>Result type</b></td>
    <td>Action</td>';

  $select_lab_products = mysqli_query($conn,"SELECT * FROM tbl_parameters")
        or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_lab_products)){
        echo "<tr><td id='thead'>".$temp."</td>
        <td>".$row['Parameter_Name']."</td>
        <td>".$row['unit_of_measure']."</td>
        <td>".$row['lower_value']."</td>
        <td>".$row['higher_value']."</td>
        <td>".$row['operator']."</td>
        <td>".$row['result_type']."</td>
        <td style='width:150px'>
            <button id='".$row['parameter_ID']."' value='".$row['Parameter_Name']."' class='editParameter'>EDIT</button>
            <button id='".$row['parameter_ID']."' class='deleteParameter'>DELETE</button>
         </td>";

        $temp++;
        echo "</tr>";
      }   
       
  ?>
</table>
</center>
<script>

$('.editParameter').click(function(e){
  e.preventDefault();
  var id=$(this).attr('id');
  var name=$(this).val();
  $.ajax({
    type:'POST',
    url:'requests/editParameters.php',
    data:'id='+id,
    success:function(html){
        $('#showParameterlist').html(html);
    }
  });
  
  $('#editthisParameter').dialog({
      modal:true,
      title:'Edit '+name,
      width:600,
	  resizable:false,
      draggable:false,
  }); 
});


//delete parameter
  $('.deleteParameter').click(function(e){
    e.preventDefault();
    if(confirm('Are you sure you want to delete this parameter?')){
    var id=$(this).attr('id');
    $.ajax({
      type:'POST',
      url:'requests/editDelete.php',
      data:'action=delete&id='+id,
      success:function(){
         // alert(html);
     $('#showParameters').load('requests/getParameters.php');
          
      }
    });
    }else{
        return false;
    }
  });

//assign parameters to items
$('#assignsubmit').click(function(e){
    e.preventDefault();
    var item=$('.itemId').attr('id');
    var parameter=$('#Laboratory_Parameter_ID').val();
    $.ajax({
        type:'POST',
        url:'requests/saveTestParameter.php',
        data:'item='+item+'&parameter='+parameter,
        success:function(html){
           // alert(html);
            $('#relodParameter').html(html);
        }
    }); 
});

</script>
<script>
function filterFunction() {
    var input, filter, table, tr, td, i;
    input = document.getElementById("searchfilter");
    filter = input.value.toUpperCase();
    table = document.getElementById("searchtd");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
        }
    }
    }
</script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>