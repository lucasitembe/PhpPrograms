<?php
include("./includes/connection.php");
include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
        if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='hospitalconsultation.php?hospitalconsultationConfigurations=hospitalconsultationConfigurationsThisForm' class='art-button-green'> 
    BACK
</a>

<br/><br/>
<center>
    <fieldset style="width:80%; background-color:#fff; ">
        <legend align="center" ><b>ITEMS MIN DURATION INTERVAL CONFIGURARION</b></legend>
        
            <table class="table" >
                <tr style="background-color:#006400;color:#fff;">
                    <th colspan="1" style="text-align:right; border-right:0px;">
                        <span style="text-align:left;" >
                            <input onkeyup="control_function()" style="max-width:250px; text-align:center;" type="text" name="serch_item" id="serch_item" placeholder="Search Item Name">
                        </span>
                    </th>
                    <th colspan="3">
                    <select id="Consultation_Type" style="width:250px;">
                    <option value="All">Select Consultation Type</option>
                   <option>Pharmacy</option>
                   <option>Laboratory</option>
                   <option>Radiology</option>
                   <option>Surgery</option>
                   <option>Procedure</option>
                   <option>Optical</option>
                   <option>Others</option>
                </select>
                    </th>
                    <th colspan="1" style="text-align:right; border-left:0px;">
                        <span > SPONSOR&nbsp;NAME </span>
                        <select onchange="control_function()" name="sponsor_id" id="sponsor_id" style="min-width: 200px;">
                            <option value="">SELECT&nbsp;SPONSOR&nbsp;NAME</option>
                            <?php 
                                $sponsors = mysqli_query($conn,"SELECT * FROM tbl_sponsor");
                                while($sponsor = mysqli_fetch_assoc($sponsors) ){
                            ?>
                            <option value="<?= $sponsor['Sponsor_ID'] ?>" class="Sponsor_ID"><?= $sponsor['Guarantor_Name']  ?></option>
                            <?php } ?>
                        </select>
                    </th>
                </tr>
            </table>
            <div style="height: 420px; overflow-x:auto;">
                <div id="loading" style="display:none;"><center><img src="images/ajax-loader_1.gif" alt="loading gif"></center></div>
                <div id="items_list_table">
                    <table class="table table-bordered" >
                        <tr>
                            <th width="3%">S/N</th>
                            <th> ITEM NAME </th>
                            <th width="20%"> MIN DURATION <br> ( <i> only days allowed </i> ) </th>
                            <th style="text-align:center" width="3%"> ACTION </th>
                        </tr>
                        <tr><td colspan="4" style="text-align:center" ><i>SELECT SPONSOR TO SEE ITEMS.</i></td></tr>
                    </table>
                </div>
            </div>
    </fieldset>
</center>

<?php
include("./includes/footer.php");
?> 

<script>
    function control_function(){
        var sponsor_id=$('#sponsor_id option:selected').val();
        var serch_item=$('#serch_item').val();
        var Consultation_Type=$('#Consultation_Type').val();
        if(sponsor_id==''||serch_item=='null'){
            alert('SPONSOR NAME MUST BE SELECTED.');
        }else{
            $('#loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_item_alert_control.php',
                data:{sponsor_id:sponsor_id,serch_item:serch_item,Consultation_Type:Consultation_Type},
                success:function(data){
                    $("#items_list_table").html(data); 
                    $("#sponsor_id").select2();
                    $('#loading').hide();
                }
            });
        }
    }

    function save_alert_control(item_id){
        var sponsor_id=$('#sponsor_id option:selected').val();
        var duration=$('#duration-'+item_id).val();
        if(sponsor_id==''||duration=='null'){
            alert('SPONSOR NAME MUST BE SELECTED.');
        }else{
            $('#loading').show();
            $.ajax({
                type:'POST',
                url:'ajax_add_item_alert_control.php',
                data:{sponsor_id:sponsor_id,duration:duration,item_id:item_id},
                success:function(data){
                    $('#loading').hide();
                    alert(data);
                }
            });
        } 
    }

    $(document).ready(function (){
        $("#sponsor_id").select2(); 
        $("#Consultation_Type").select2(); 
    });
</script>