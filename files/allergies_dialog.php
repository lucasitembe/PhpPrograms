
<center>               
<table width=80%>
    <tr>
        <td>
    <fieldset>  
            <legend align=center><b>ADD NEW ALLERGIES</b></legend>
        <form name='myForm' id='myForm'>
            <table width=100%>
                <tr>
                    <td width=25%><b>Allergies</b></td>
                    <td width=75%>
                        <input type='text' name='allergies_Name' id='allergies_Name' required='required' placeholder='Enter allergies Name'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                    <input type='button' name='submit1' id='submit1' value='   SAVE   ' class='art-button-green' onclick=save_allergies_data();>
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    </td>
                </tr>
            </table>
	    </form>
    </fieldset>
        </td>
    </tr>
</table>
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
                    alert(response);
                    location.reload(true);

                }
            })
          }
          else{
              alert("Please fill allergy name");
          }
         
    }
</script>