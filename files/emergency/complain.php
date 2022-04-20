<?php 
// $update_ = "SELECT maincomplain FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID'  AND employee_ID='$employee_ID'";
$update_ = "SELECT maincomplain FROM tbl_consultation_history WHERE consultation_ID = '$consultation_ID'";
$result  = mysqli_query($conn, $update_) or die(mysqli_error($conn));
$histcomplain = mysqli_fetch_assoc($result)['maincomplain'];
?>
<div id="complain">
    <form action="#" method='post' onsubmit="return validateForm();" enctype="multipart/form-data">
        <h4 align="center"><strong style="color:red;">Please Take and Write Comprehesive Medical Notes</strong></h4>
        <h3 style="margin-left: 100px" >Complain</h3>
        <center>
            <table width=70% style='border: 0px;'>
                <tr>
                    <td width='15%' style="text-align:right;" >
                        Main Complain
                    </td>
                    <td colspan="6">
                        <table width="100%">
                            <tr>
                                <td colspan="10">
                                    <textarea onkeyup="update_consultation()" style='padding-left:5px;height: 200px;' required="required" id='maincomplain' name='maincomplain'><?=$histcomplain;?></textarea>
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
            <table>
                <tr>
                    <td colspan="8"></td>
                    <td>
                        <div style="width:100%;text-align:right;padding-right:10px;">
                            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
                            <input type="hidden" name="Patient_Payment_ID" value="<?php echo $_GET['Patient_Payment_ID'] ?>"/>
                            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
                            <input type="submit" class="art-button-green" value='SAVE' onclick="return confirm('Are sure you want to save information?')"   >
                            <input type='hidden' name='submit_complain' value='true'/> 
                        </div>

                    </td>
                </tr>
            </table>
        </center>
    </form>
</div>

<script>
    function update_consultation(){
        var consultation_ID = <?php echo $consultation_ID;?>;
        var maincomplain = $("#maincomplain").val();
                $.ajax({
                type:'get',
                url: 'emergency/emergency_clinicalautosave.php',
                data : {
                    consultation_ID:consultation_ID,
                    maincomplain:maincomplain
                },
                success : function(response){
                }
            });
    }
</script>
