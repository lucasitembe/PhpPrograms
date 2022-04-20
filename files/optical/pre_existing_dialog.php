
<center>               
<table width=80%>
    <tr>
        <td>
    <fieldset>  
            <legend align=center><b>ADD NEW EXISTING CONDITION</b></legend>
        <form name='myForm' id='myForm'>
            <table width=100%>
                <tr>
                    <td width=25%><b>Existing Condition</b></td>
                    <td width=75%>
                        <input type='text' name='existing_condition' id='existing_condition' required='required' placeholder='Enter Existing Condition'>
                    </td> 
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                    <input type='button' name='submit1' id='submit1' value='   SAVE   ' class='art-button-green' onclick=save_existing_data();>
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
        function save_existing_data(){
          var existing_condition=$("#existing_condition").val();
          if(existing_condition !=''){
            $.ajax({
            type:'POST',
            url:'save_existing_data.php',
            data : {existing_condition:existing_condition},
                success : function(response){  
                    // $('#show').html(response);
                    alert("Condition Added Successfully");
                    location.reload(true);

                }
            });
          }
          else{
              alert("Please fill pre existing Name");
          }
        
    }
</script>