<?php
    $allergy_id=$_POST['allergy_id'];
    //echo $allergy_id;
    $select_allergies = mysqli_query($conn,"SELECT allergies_Name FROM allergies WHERE allergies_ID='$allergy_id'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_allergies)){
        $allergies_Name= $row['allergies_Name'];
    }
   
?>
<html>
    <head>
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
                        <input type='text' name='allergies_Name' id='allergies_Name'  value="<?php echo $allergies_Name;?>">
                        <input type='text' name='allergies_Name' id='allergies_Name'  value="<?php echo $allergy_id;?>">
                        <input type='text' name='allergies_Name' id='allergies_Name'  value="<?php echo $allergy_id;?>">
                        <input type='text' name='allergies_Name' id='allergies_Name'  value="<?php echo $allergy_id;?>">
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                    <input type='button' name='submit1' id='submit1' value='   SAVE   ' class='art-button-green' >
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    </td>
                </tr>
            </table>
	    </form>
    </fieldset>
        </td>
    </tr>
</table>

</head>
</body>
</html>
        </center>
<script>
        function save_allergies_data(){
          var allergies_Name=$("#allergies_Name").val();
          if(allergies_Name !=''){
            $.ajax({
            type:'POST',
            url:'save_allergies_data.php',
            data : {allergies_Name:allergies_Name},
                success : function(response){  
                    // $('#show').html(response);
                    alert("Allergy Added Successfully");
                    location.reload(true);

                }
            })
          }
          else{
              alert("Please fill allergy name");
          }
         
    }
</script>