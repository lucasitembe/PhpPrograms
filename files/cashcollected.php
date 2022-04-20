<?php
 include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['General_Ledger'])){
	    if($_SESSION['userinfo']['General_Ledger'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    } 
    
    $data='';
    $title_for_report='';
    
    $query_string='';
    
	?>
<a href='./generalledgercenter.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<br/><br/>

<fieldset style='overflow-y:scroll; height:500px'>
    <center>
	
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;">
		<br/>
    <table width='100%' border='0' id='actionsTable'>
        <tr>
		 <td style="text-align: center">
		   <select name="patientStatus" id="patientStatus" style="padding:auto; font-size: 17px;width:100%;text-align:center ">
                            <option style="text-align:center">OUTPATIENT AND INPATIENT</option>
                            <option>INPATIENT</option>
                            <option>OUTPATIENT</option>
            </select>
		 </td>
		 <td style="text-align: right"><b>Data Entry Name</b></td><td ><input type="hidden" name="employeeID" id='employeeID'><input type="text" name="DataEntryName" id="DataEntryName" value="All Data Entry"></td><td colspan="2" style="text-align: center"><button type="button" style="width:auto;font-size:18px; " class="art-button-green" id='selectDataEntry' onclick="openItemDialog('DataEntryNameHolder')">Select</button></td>
            <td style="text-align: center"><b>From</b></td>
	    <td style="text-align: center">
		<input type='text' name='Date_From' id='date_From' required='required'>    
	    </td>
            <td style="text-align: center">To</td>
	    <td style="text-align: center"><input type='text' name='Date_To' id='date_To' required='required'></td>    
            <td style='text-align: center;'>
                <input type='button' name='Print_Filter' onclick='filterEmployeePatients()' id='Print_Filter' class='art-button-green' value='FILTER'>
            </td>
        </tr>	
    </table>
   
</center>
    <br>
    <legend align=center><b>DOCTOR'S PERFORMANCE REPORT SUMMARY</b></legend>
       
		<div >
	
		</div>
		<div id="DataEntryNameHolderDiv" style="width:100%;overflow:hidden;display:none" >
          <fieldset>
           <center>
                <table width = "100%" style="border:0; ">
                    <tr>
                        <td width="40%" style="text-align: right">Data Entry Name</td>
                        <td width="60%" style="text-align: center">
                            <select id="DataEntryNameHolder" style=" padding:5px;" onchange="setDataEntryName(this.value)"></select>
                        </td>
                    </tr>
                </table>
          </fieldset>
        </div>
</fieldset>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
	<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
	<script src="media/js/jquery.js"></script>
	<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>

<script>
 $('#doctorsperformancetbl').dataTable({
    "bJQueryUI":true,
	});
</script>

<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
#displaySelectedTests,#items_to_choose{
    overflow-y:scroll;
    overflow-x:hidden; 
}
</style>
    <script type='text/javascript'>
        
        $(document).ready(function(){
             $("#DataEntryNameHolderDiv").dialog({ autoOpen: false,width:400, title:'Choose Data Entry Name',modal: true});

        //       $(".ui-widget-header").css("background-color","blue");  
       
        $(".ui-icon-closethick").click(function(){
//         $(this).hide();
            $("#loader").hide();
        });
        
    });
            
       function openItemDialog(item){
     //Load data to the div
       var datastring=item+'=true';
       // alert(datastring);
     
        $.ajax({
         type: 'GET',
         url: "generalledgerrequests.php",
         data: datastring,
	   success: function (data) {
		     // alert(data['data']);
              $('#DataEntryNameHolder').html(data);
              $("#DataEntryNameHolderDiv").dialog("open");
	    			  
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
      });
      
               
     }
     
     function setDataEntryName(entryName){
        
         if(entryName ==="All Data Entry"){
             $("#DataEntryName").val("All Data Entry");
         }else{
           var data=entryName.split('$$>');
         
            $("#employeeID").val(data[0]);
            $("#DataEntryName").val(data[1]);

         }
         
         $("#DataEntryNameHolderDiv").dialog("close");
        
     }
     
        
    </script>	
<?php		
  include("./includes/footer.php");