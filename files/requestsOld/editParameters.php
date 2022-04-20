

<?php

include("../includes/connection.php");
   $id=$_POST['id'];
  $select_lab_products = mysql_query("SELECT * FROM tbl_parameters WHERE parameter_ID='".$id."'") or die(mysql_error());
     
    while($row = mysql_fetch_array($select_lab_products)){
     echo '<table style="margin_top:20px;width: 100%" class="hiv_table" >
                                    <tr>
                                        <td>
                                            <fieldset>  
                                                <p id="parameterstatus" style="font-weight: bold"></p>
                                                    <form action="" method="post" name="myForm" id="myFormx">
                                                        <table class="hiv_table" style="width:100%">
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Parameter Name</td>
                                                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                <input type="text" class="ParameterName" value="'.$row['Parameter_Name'].'" />
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Unit of measure</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                    <input type="text" name="Parameter_Name" class="unitofmeasure" required="required" value="'.$row['unit_of_measure'].'"
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Lower Value</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                    <input type="text" name="Parameter_Name" class="lowervalue" required="required" value="'.$row['lower_value'].'"
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Higher Value</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                    <input type="text" name="Parameter_Name" class="highervalue" required="required" value="'.$row['higher_value'].'"
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Operator</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                    <input type="text" name="Parameter_Name" class="Operator" required="required" value="'.$row['operator'].'"
                                                                </td> 
                                                            </tr>
                                                            <tr>
                                                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Result type</td>
                                                                    <td width=75%  style="color:blue;border:1px solid #ccc;">
                                                                    <input type="text" name="Parameter_Name" class="results" required="required" value="'.$row['result_type'].'"
                                                                </td> 
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td colspan=2 style="text-align: right;">
                                                                <input type="submit" id="'.$row['parameter_ID'].'" name="submit" value=" SAVE CHANGES" class="art-button-green submitedit">
                                                                <input type="reset" name="clear" id="canceledit" value="CLEAR" class="art-button-green">
                                                                </td>

                                                            </tr>
                                                    </table>
                                                </form>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>';  
        
  
    }

?>

<script>
  $('.submitedit').click(function(e){
    e.preventDefault();
    var id=$(this).attr('id');
    var ParameterName=$('.ParameterName').val();
    var unitofmeasure=$('.unitofmeasure').val();
    var lowervalue=$('.lowervalue').val();
    var highervalue=$('.highervalue').val();
    var Operator=$('.Operator').val();
    var results=$('.results').val();
   $.ajax({
       type:'POST',
       url:'requests/editDelete.php',
       data:'id='+id+'&ParameterName='+ParameterName+'&unitofmeasure='+unitofmeasure+'&lowervalue='+lowervalue+'&highervalue='+highervalue+'&Operator='+Operator+'&results='+results,
       success:function(html){
         alert(html);
//          $('#parameterstatus').html(html); 
          $('#showParameters').load('requests/getParameters.php');
       }
   });
  });
  
</script>