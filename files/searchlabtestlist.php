<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Laboratory_Works'])) {
        if ($_SESSION['userinfo']['Laboratory_Works'] != 'yes') {
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

<!-- link menu -->


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Laboratory_Works'] == 'yes') {
        echo "<a href='laboratory_setup.php' class='art-button-green'>BACK</a>";
    }
}
?>


<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br/>
<br/>
<br/>
<fieldset>  
    <legend align="center" style="width: auto">
        <b>
            LABORATORY ITEMS LIST
        </b>
    </legend>
    <center>
        <table width='100%' border=1>
            <tr>
                <td id='Search_Iframe'>
                    <div style="width:100%;height:400px;overflow-x: hidden;overflow-y: auto">
                        <?php include 'search_lab_test_list.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<div id="showAssignedParameters">
    <div id="parametersList" style="width: 100%">

    </div>
</div>


<div id="addNewParameter" style="display: none">
    <form id="newPara">
        <table style="margin_top:20px;width: 100%" class="hiv_table" border=0>
            <tr>
                <td>
                    <fieldset>  
                        <p id="parameterstatus" style="font-weight: bold"></p>

                        <table class="hiv_table" style="width:100%">
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Parameter Name</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <input type="text" name="Parameter_Name" id="ParameterName"  placeholder=""SS
                                </td> 
                            </tr>
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Unit of measure</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <input type="text" name="Parameter_Name" id="unitofmeasure"  placeholder=""
                                </td> 
                            </tr>
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Lower Value</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <input type="text" name="Parameter_Name" id="lowervalue"  placeholder=""
                                </td> 
                            </tr>
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Higher Value</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <input type="text" name="Parameter_Name" id="highervalue"  placeholder=""
                                </td> 
                            </tr>
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Operator</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <input type="text" name="Parameter_Name" id="Operator"  placeholder=""
                                </td> 
                            </tr>
                            <tr>
                                <td width=25%  style="color:black;border:1px solid #ccc;text-align:right;">Result type</td>
                                <td width=75%  style="color:blue;border:1px solid #ccc;">
                                    <select id="results" >
                                        <option></option>
                                        <option>Quantitative</option>
                                        <option>Qualitative</option>
                                    </select>
                                </td> 
                            </tr>

                            <tr>
                                <td colspan=2 style="text-align: right;">
                                    <input type="button" id="saveParameter" name="submit" id="submit" value=" SAVE " class="art-button-green">
                                    <input type="reset" name="clear" id="clear" value="CLEAR" class="art-button-green">

                                </td>

                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </form>
</div>

<div id="showAssignedSpecimen" style="display: none;width: 100%">
    <div id="specimenList">
    </div> 
</div>
<style>
    .viewParameterhere:hover{
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#listAllLabItems').DataTable({
            "bJQueryUI": true
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click', '.viewParameterhere', function () {
        $('#addNewParameter').fadeOut();
        $('#parametersList').fadeIn();
        var id = $(this).attr('id');
        var itemName = $(this).attr('name');
        
        fixSelect2();
        $('#showAssignedParameters').dialog({
            modal: true,
            width: 900,
            minHeight: 450,
            resizable: true,
            draggable: true,
        });
        $('#showAssignedParameters').dialog("option", "title", itemName);

        $.ajax({
            type: 'POST',
            url: "requests/ParameterListPopup.php",
            data: "action=parameterList&id=" + id,
            success: function (html) {
                $('#parametersList').html(html);
                $('#Laboratory_Parameter_ID').select2();

            }
        });
    });


    $(document).on('click', '.viewSpecimenhere', function () {
//        var id=$(this).attr('id');
//        var itemName=$(this).attr('name');
//       
//          $('#showAssignedSpecimen').dialog({
//            modal:true, 
//            width:900,
//            minHeight:450,
//            resizable:true,
//            draggable:true, 
//          });
//           $('#showAssignedSpecimen').dialog("option", "title", itemName);
//                //SpecimenListPopup
    });
</script>

<script type="text/javascript">
    //Add item here
    $(document).on('click', '#assignsubmit', function () {
        var item = $(this).attr('name');
        var itemVal = $('#Laboratory_Parameter_ID').val();
        if (itemVal == '') {
            alert('Choose a parameter to assign to this test');
            exit();
        }
        $.ajax({
            type: 'POST',
            url: "requests/ParameterListPopup.php",
            data: "action=AddparameterList&item=" + item + '&itemVal=' + itemVal,
            success: function (html) {
                $('#parametersList').html(html);
                $('#Laboratory_Parameter_ID').select2();
            }
        });
    });


    //Remove item here
    $(document).on('click', '.removeParameter', function () {
        var item = $(this).attr('name');
        var itemVal = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: "requests/ParameterListPopup.php",
            data: "action=deleteparameterList&item=" + item + '&itemVal=' + itemVal,
            success: function (html) {
                $('#parametersList').html(html);
                 $('#Laboratory_Parameter_ID').select2();
            }
        });

    });

    $(document).on('click', '#Newparameter', function () {
        $('#addNewParameter').dialog({
            modal: true,
            width: 900,
            minHeight: 450,
            resizable: true,
            draggable: true,
            title: "ADD NEW PARAMETER",
        });

        $.ajax({
            type: 'POST',
            url: "requests/ParameterListPopup.php",
            data: "action=AddNewparameterList&item=",
            success: function (html) {
                //$('#Laboratory_Parameter_ID').load(); 
            }
        });


    });

    $('#saveParameter').on('click', function (e) {
        e.preventDefault();
        var ParameterName = $('#ParameterName').val();
        var unitofmeasure = $('#unitofmeasure').val();
        var lowervalue = $('#lowervalue').val();
        var highervalue = $('#highervalue').val();
        var Operator = $('#Operator').val();
        var results = $('#results').val();
        if (ParameterName == '' || results == '') {
            alert("Parameter name and result type must be filled. Please fill all important details to continue!");
            exit();
        }
        $.ajax({
            type: 'POST',
            url: 'requests/ParameterListPopup.php',
            data: 'action=AddNewparameterDetails&ParameterName=' + ParameterName + '&unitofmeasure=' + unitofmeasure + '&lowervalue=' + lowervalue + '&highervalue=' + highervalue + '&Operator=' + Operator + '&results=' + results,
            success: function (html) {
                // var statusmsg= $('#submissionStatus').html(html);
                alert('Parameter saved successfully!');
                // alert("Parameter saved successfully!");
                $("#newPara input[type=text]").val('');
                refreshParameter();


                //$('#Laboratory_Parameter_ID').html(html);
                // $('#Laboratory_Parameter_ID').load(); 
            }
        });

    });

</script>
<script>
    function refreshParameter() {
        $.ajax({
            type: 'GET',
            url: "requests/ParameterListPopup.php",
            data: "action=getParameters",
            success: function (html) {
                $('#Laboratory_Parameter_ID').html(html);
                $('#Laboratory_Parameter_ID').select2();
            }
        });
    }
</script>
<script>
    function fixSelect2() {
        if ($.ui && $.ui.dialog && $.ui.dialog.prototype._allowInteraction) {
            var ui_dialog_interaction = $.ui.dialog.prototype._allowInteraction;
            $.ui.dialog.prototype._allowInteraction = function (e) {
                if ($(e.target).closest('.select2-dropdown').length)
                    return true;
                return ui_dialog_interaction.apply(this, arguments);
            };
        }
    }
</script>
<br/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="css/jquery-ui.js"></script>
<?php
include("./includes/footer.php");
?>
