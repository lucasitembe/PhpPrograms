<?php
include("./includes/header.php");
include("./includes/connection.php");
?>
<a href='./received_engineering_requisition.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
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
    
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type:'GET',
            url:'reasign_engineering_iframe.php',
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
            <legend align=center><b>LIST OF ASSIGNED ENGINEERING JOBS</b></legend>
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
<script type="text/javascript" src="js/afya_card.js"></script>
<?php
    include("./includes/footer.php");
?>
