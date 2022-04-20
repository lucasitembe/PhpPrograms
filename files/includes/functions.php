   <?php
     function filterByDate(){
        ?>
           <div style="margin-left:20%;margin-top:10px;margin-buttom:10px;"> 
   <form name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data" action="" method="post">
            <table width=60%> 
                    <tr> 
                        <td><b>From<b></td>
                        <td width=30%><input type='text' name='Date_From' id='date_From' required='required'></td>
                        <td><b>To<b></td>
                        <td width=30%><input type='text' name='Date_To' id='date_To' required='required'></td>
                        <td><input name='' type='submit' value='FILTER' class='art-button-green'></td>
                    </tr> 
            </table>
        </form>
    </Div>
    <?php
     }