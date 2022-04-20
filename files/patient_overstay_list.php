<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

    <a href='admissionreports.php?section=Admission&Admissionreports=AllReports&ActiveReports' class='art-button-green'>
	BACK
    </a>
    <div style='float: right;'>
<span style='font-weight: bold; font-size: 18px;'>COLOR CODE KEY: <span>
<input type="button"  style='background: green; padding: 10px; color: #fff; border: none;' value='PATIENT SIGNED' name="" id="">
</div>
    <!-- <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green"> -->






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
            <td width="22%">
                <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Start Date' id='date_From' style="text-align: center">    
            </td>
            <td width="22%"><input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' placeholder='End Date' id='date_To' style="text-align: center"></td>  
            <td width="22%"> 
                <input type='text' name='Patient_Name' title='Incase You want to filter by Name'  id='Patient_Name' style='text-align: center;' onkeyup='filterPatient()' placeholder='~~~~~~~Search Patient Name~~~~~~~~'>
            </td>
            <td width="22%"> 
                <input type='text' name='Patient_Number' title='Incase You want to filter by Registration Number'  id='Patient_Number' style='text-align: center;'  onkeyup='filterPatient()' placeholder='~~~~~~~~Search Patient Number~~~~~~~~~'>
            </td>
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterPatient()' class='art-button-green' value='FILTER'>
                </td>
        </tr>
    </table>
</center>
<br>
<fieldset>  
    <legend align=center><b>PATIENT OVERSTAY LIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
		    <td id='Search_Iframe'>
			<iframe width='100%' height=380px src='patient_overstay_list_iframe.php?Patient_Name='></iframe>
		    </td>
		</tr>
            </table>
        </center>
</fieldset><br/>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold; display: none;"><i> Click Surgery Date in case you want to edit it </i></p></center>


<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script language="javascript" type="text/javascript">
    function filterPatient(){
		Patient_Number = document.getElementById('Patient_Number').value;
		Patient_Name = document.getElementById('Patient_Name').value;
		date_From = document.getElementById('date_From').value;
		date_To = document.getElementById('date_To').value;

        // document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='patient_overstay_list_iframe.php?date_To="+date_To+"&date_From="+date_From+"&Patient_Name="+Patient_Name+"&Patient_Number="+Patient_Number+"'></iframe>";
    }

    // $('#Print_List').on('click',function(){
    //  var Patient_Number=$('#Patient_Number').val();
    //  var Patient_Name=$('#Patient_Name').val();
    //  var date_From=$('#date_From').val();
    //  var date_To=$('#date_To').val();
    //  var Employee_ID=$('#Employee_ID').val();
    //  var number=$('#number').val();
    // //  if(date_From=='' || date_From=='NULL' || date_To=='' || date_To=='NULL'){
    // //      alert('Select dates to continue');
    // //      return false;
    // //  }
    //  var Sponsor_ID=$('#Sponsor_ID').val(); 
     
    //  window.open('Print_Doctor_Surgery_Schedule.php?action=filter&number='+number+'&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID+'&Patient_Name='+Patient_Name+'&Patient_Number='+Patient_Number);
        
    // });
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

  function change_this_date(Payment_Item_Cache_List_ID){
      alert(Payment_Item_Cache_List_ID)
      exit();
        // var pay_ID=$('#date_From_val').val();
        // var DateOfService=$('#date_Fromx').val();
        // if(DateOfService=='' ||DateOfService=='NULL'){
        //     alert('Date cannot be empty,please select date');
        //     return false;
        // }
        // if(confirm('Are you sure you want change this date?')){
        //     $.ajax({
        //     type:'POST',
        //     url:'requests/Update_sugery_date.php',
        //     data:'action=update&pay_ID='+Payment_Item_Cache_List_ID+'&DateOfService='+DateOfService,
        //     cache:false,
        //     success:function(e){
        //         alert(e);
        //     }
        //     }); 
        // }else{
        //     return false;
        // }
  }
</script>

<?php
    include("./includes/footer.php");
?>