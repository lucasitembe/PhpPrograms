<?php
    include("./includes/connection.php");
    $allergy_id=$_POST['allergy_id'];
    //echo $allergy_id;
    $select_allergies = mysqli_query($conn,"SELECT allergies_Name FROM allergies WHERE allergies_ID='$allergy_id'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_allergies)){
        $allergies_Name= $row['allergies_Name'];
    }
   
?>
<html>
    <head></head>
        <body>
<center>               
<table width=80%>
    <tr>
        <td>
    <fieldset>  
        <legend align=center><b>EDIT ALLERGIES</b></legend>
        <form name='myForm' id='myForm'>
            <table width=100%>
                <tr>
                    <td width=25%><b>Allergies</b></td>
                    <td width=75%>
                        <input type='text' name='allergies_Name' id='allergies_Name' class='allergies_Name' class="form-control"  value="<?php echo $allergies_Name;?>"
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                    <input type='button' name='submit1' id='submit1' value=' UPDATE' class='art-button-green' onclick="update_allargy()">
                    </td>
                    <input type="hidden" id="allergy_id" class="allergy_id" value="<?php echo $allergy_id;?>">
                </tr>
            </table>
	    </form>
    </fieldset>
        </td>
    </tr>
</table>


</body>
</html>
    </center>
<script>
        function update_allargy(){
          var allergies_Name=$(".allergies_Name").val();
          var allergy_id=$(".allergy_id").val();
          if(allergies_Name !=''){
            $.ajax({
            type:'POST',
            url:'update_allergy.php',
            data : {allergies_Name:allergies_Name,
                allergy_id:allergy_id
            },
                success : function(response){  
                    // $('#show').html(response);
                    alert("Allergy Updated Successfully");
                    location.reload(true);

                }
            })
          }
          else{
              alert("Please fill allergy name");
          }
         
    }
</script>