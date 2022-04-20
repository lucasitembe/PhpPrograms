<?php
include("includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
$Employee_ID = $_GET['Current_Employee_ID'];
$consultation_ID = $_GET['consultation_ID'];

?>
<script>

</script>
<div class="box box-primary" style="height: 630px;overflow-y: scroll;overflow-x: hidden">
                        <select name="Radical_Type" id="Radical_Type" style='margin-top: 10px; width: 80%' onchange='Select_Radical_Type(<?= $consultation_ID ?>)'>
                            <option value="" selected='selected'>SELECT RADICAL TREATMENT</option>
                            <option value="RADIOTHERAPY">RADIOTHERAPY TREATMENT</option>
                            <option value="BRACHYTHERAPY">BRACHYTHERAPY TREATMENT</option>
                        </select>             
            <div style='margin-top: 10px;' id='radical_panel'></div>
</div>



        
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script>
        $(document).ready(function (e){
        $("#Radical_Type").select2();
    });
</script>