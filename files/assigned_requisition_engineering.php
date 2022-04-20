<?php
include("./includes/header.php");
include("./includes/connection.php");


if(isset($_SESSION['userinfo'])) {
    if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }else{
            @session_start();
            if(!isset($_SESSION['Storage_Supervisor'])){
                header("Location: ./engineeringsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    }else{
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
}else{
     @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
     }
?>
<a href='./engineering_works.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>

		<br>
        <?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function SerchEngineering(){
        var assigned_engineer=$('#assigned_engineer').val();
        var section_required=$('#section_required').val();
        Mrv_Number = $("#Mrv_Number").val();

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Search_Iframe').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'search_list_engineering_iframe2.php?assigned_engineer='+assigned_engineer+'&section_required='+section_required+'&Mrv_Number='+Mrv_Number, true);
        myObjectPost.send();
    }
     $(document).ready(function () {
        SerchEngineering();
        $("#section_required").select2();

    });
</script>

<br/>
<center>
    <table width='100%' class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black"><center>
        <tr>
            <td width='30%'>
                <input type='text' id='assigned_engineer' name='assigned_engineer' oninput='SerchEngineering()' style='text-align: center;' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Type Employee Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' style='width: 100%'>
            </td>
            <td width='30%'>
                <input type='text' id='Mrv_Number' name='Mrv_Number' oninput='SerchEngineering()' style='text-align: center;' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Type MRV Number~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' style='width: 100%'>
            </td>
            <td width='30%'>
                <select name="section_required" id="section_required" style="width: 100%;" onchange='SerchEngineering()'>
                    <option value='All'>Select Required Section</option>
                    <option value='Biomedical'>Biomedical</option>
                    <option value='Electrical'>Electrical</option>
                    <option value='Mechanical'>Mechanical</option>
                    <option value='Mason'>Mason</option>
                    <option value='Plumber'>Plumber</option>
                    <option value='Carpentry'>Carpentry</option>
                    <option value='Welding'>Welding</option>
                    <option value='Painting'>Painting</option>
                </select>
            </td>
        </tr>
        </center>
    </table>
</center>
<fieldset>  
<legend align=center><b>ASSIGNED ENGINEERING JOBS</b></legend>
<!-- <fieldset> -->
<div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden">
        <table class="table table-collapse table-striped " style="border-collapse: collapse;border:1px solid black">
            <tr  style='background: #dedede;'>
                <td><b><h5>SN</h5></b></td>
	            <td style='width: 4%'><b><h5>MRV #:</h5></b></td>
                <td style='width: 9%'><b><h5>REQUESTED DATE</h5></b></td>
                <td style='width: 14%'><b><h5>REQUESTED STAFF</h5></b></td>
                <td style='width: 11%'><b><h5>ADMINISTRATIVE RESP.</h5></b></td>
				<td><b><h5>EQUIPMENT NAME</h5></b></td>
                <td><b><h5>FLOOR / LOCATION</h5></b></td>
                <td style='width: 14%'><b><h5>ASSIGNED ENGINEER</h5></b></td>
                <td style='width: 10%'><b><h5>ASSIGNED SECTION</h5></b></td>
                <td style='width: 10%'><b><h5>NO OF DAYS</h5></b></td>
            </tr>
            <tbody id='Search_Iframe'>
            </tbody>
        </table>
    </div>
    <div style='text-align: center;'>
    <span style='font-weight: bold;'><h3>COLOR CODE KEY: 
    <input type="button"  style='background: #dedede; padding: 2px 10px; border: 1px solid #000;font-weight: bold;' title='This color will only appear to the Jobs Within Time Frame' value='JOB WITHIN SPECIFIED PERIOD' name="" id="">
    <input type="button"  style='background: #bd0d0d; padding: 2px 10px; color: #fff; border: none;font-weight: bold;' title='This color will only appear to the Jobs which exceeded the time Frame' value='JOB OVERDUE' name="" id="">
    </h3><span>
    </div>
</fieldset>
<script type="text/javascript" src="js/afya_card.js"></script>
<?php
    include("./includes/footer.php");
?>
