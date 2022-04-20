<?php
include("./includes/header.php");
include("./includes/connection.php");
?>

<a href='./job_closure_list.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>

		<br>
<?php
include("./includes/functions.php");
if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    
    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }

?>
<style>
.row th{
        background: grey;
    }

    .row tr th{
        border: 1px solid #fff;
    }
    .row tr:nth-child(even){
        background-color: #eee;
    }
    .row tr:nth-child(odd){
        background-color: #fff;
        
    }
</style>
<br/>
<script language="javascript" type="text/javascript">
    function SerchEngineering(){
        var assigned_engineer=$('#assigned_engineer').val();
        var section_required=$('#section_required').val();
    
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'GET',
            url:'completed_engineering_worklist.php',
            data:{assigned_engineer:assigned_engineer,section_required:section_required},
            success:function(data){
                $("#Search_Iframe").html(data);
            }
        });
    }
     $(document).ready(function () {
        SerchEngineering();
    });
</script>

<br/>
<center>
    <table width='100%'><center>
        <tr>
            <td width='50%'>
                <input type='text' id='assigned_engineer' name='assigned_engineer' oninput='SerchEngineering()' style='text-align: center;' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Type Employee Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~' style='width: 100%'>
            </td>
            <td width='50%'>
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
            <legend align=center><b>LIST OF COMPLETED ENGINEERING JOBS</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
		<!-- <iframe width='100%' height=400px src='search_engineers_list_Iframe.php'></iframe> -->
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>