<?php
include("./includes/header.php");
include("./includes/connection.php");

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
    <a href='./jobcard_menu.php?engineering_works=engineering_WorkThisPage' class='art-button-green'>
        BACK
    </a>





<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<br/><br/>
<center>
    <table width="95%">
        <tr>
            <td width="20%"> 
                <input type='text' name='Employee_ID' title='Incase You want to filter by Engineer Name'  id='Employee_ID' style='text-align: center;' onkeyup='filterJobcard()' placeholder='~~~~~~~Search Engineer Name~~~~~~~~'>
            </td>
            <td width="20%"> 
                <input type='text' name='Jobcard_Number' title='Incase You want to filter by Jobcard Number'  id='Jobcard_Number' style='text-align: center;'  onkeyup='filterJobcard()' placeholder='~~~~~~~~Search Jobcard Number~~~~~~~~~'>
            </td>
                <td style='text-align: center;' width='5%'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterJobcard()' class='art-button-green' value='FILTER'>
                </td>
        </tr>
    </table>
</center>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i> Click Jobcard Details in case you want to Correct it </i></p></center>
<fieldset>  
    <legend align=center><b>REJECTED JOBCARD LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
		    <td id='Search_Iframe'>
			<iframe width='100%' height=380px src='rejected_jobcard_iframe.php?Patient_Name='></iframe>
		    </td>
		</tr>
            </table>
        </center>
</fieldset><br/>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script language="javascript" type="text/javascript">
    function filterJobcard(){
		Employee_ID = document.getElementById('Employee_ID').value;
		Jobcard_Number = document.getElementById('Jobcard_Number').value;

        // document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='rejected_jobcard_iframe.php?Employee_ID="+Employee_ID+"&Jobcard_Number="+Jobcard_Number+"'></iframe>";
    }

</script>

<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
         $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#number").select2();
    });

    $('#Date_Time').on('click',function(){ 
        var id=$(this).attr('id');
        $('#date_From_val').val(id);
        $('#changeDateDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'Change Surgery Date'
//            close: function (event, ui) {
//               
//            }
        });

    });

</script>

<?php
    include("./includes/footer.php");
?>