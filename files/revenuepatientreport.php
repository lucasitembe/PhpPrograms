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
    
    
    
    $query=  mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
    $data='<option value=""></option>';
    $data.='<option value="All Sponsors">All Sponsors</option>';
    
    while ($row = mysqli_fetch_array($query)) {
         $data.= '<option value="'.$row['Sponsor_ID'].'$$>'.$row['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
    }
    
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/>
<fieldset>
        <legend align=center><b>REVENUE HOME</b></legend>
        <form action="revenuesummary.php" method="post">
        <center>
	    <table width = '60%'>
                <tr>
                    <td>From Date</td><td><input type="text" name="fromDate" class="FromDate" id="date"> <td>To Date</td><td><input type="text" name="todate" class="toDate" id="date2"></td>
                </tr> 
                <tr>
                    <td>Sponsor Name</td><td colspan="2"><input type="hidden" name="sponsorID" id='sponsorID'><input type="text" name="sponsorname" id="sponsorname" value="All Sponsors"> <td><button type="button" class="art-button-green" id='selectSponsor' onclick="openItemDialog('sponsorNameHolder')">Select</button></td>
                </tr>    
                <td>Data Entry Name</td><td colspan="2"><input type="hidden" name="employeeID" id='employeeID'><input type="text" name="DataEntryName" id="DataEntryName" value="All Data Entry"> <td><button type="button" class="art-button-green" id='selectDataEntry' onclick="openItemDialog('DataEntryNameHolder')">Select</button></td>
                <tr>    
                    <td>Patient Status</td>
                    <td colspan="2">
                        <select name="patientStatus" id="patientStatus" style="padding:5px; font-size: 17px;width:100% ">
                            <option>OUTPATIENT AND INPATIENT</option>
                            <option>INPATIENT</option>
                            <option>OUTPATIENT</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td style="float:right "><button type="button" class="art-button-green" id='Refresh'>Refresh</button>
                    <button type="submit" name="credit_cash" class="art-button-green" id='selectDataEntry'>Credit And Cash</button>
                    </td>
                    <td><button type="submit" name="credit" class="art-button-green" id='selectDataEntry'>Credit</button>
                    <button type="submit" name="cash" class="art-button-green" id='selectDataEntry'>Cash</button>
                    <button type="submit" name="canceled" class="art-button-green" id='selectDataEntry'>Canceled</button>
                    </td>
                </tr>
        </table>
        </center>
            </form>
        <div id="sponsorNameHolderDiv" style="width:100%;overflow:hidden;" >
          <fieldset>
           <center>
                <table width = "100%" style="border:0; ">
                    <tr>
                        <td width="40%">Sponsor Name</td>
                        <td width="60%" style="text-align: center">
                            <select id="sponsorNameHolder" style=" padding:5px;" onchange="setSponsorName(this.value)"></select>
                        </td>
                    </tr>
                </table>
          </fieldset>
        </div>
        <div id="DataEntryNameHolderDiv" style="width:100%;overflow:hidden;" >
          <fieldset>
           <center>
                <table width = "100%" style="border:0; ">
                    <tr>
                        <td width="40%">Data Entry Name</td>
                        <td width="60%" style="text-align: center">
                            <select id="DataEntryNameHolder" style=" padding:5px;" onchange="setDataEntryName(this.value)"></select>
                        </td>
                    </tr>
                </table>
          </fieldset>
        </div>
</fieldset><br/>

<!--Sponsors Revenue-->

<br/>
<fieldset>
        <legend align=center><b>REVENUE SPONSORS</b></legend>
        <form action="revenuesummary.php" method="post">
        <center>
	    <table width = '60%'>
                <tr>
                    <td>From Date</td><td><input type="text" name="fromDate" class="FromDate" id="date3"> <td>To Date</td><td><input type="text" name="todate" class="toDate" id="date4"></td>
                </tr> 
                <tr>
                   <td>Sponsor Name</td>
                   <td colspan="3">
                       <select name="sponsor" style="padding:5px; font-size: 17px;width:100% ">
                            <?php echo $data;?>
                        </select>
                   </td>    
                </tr>    
                
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">
                     <button type="submit" name="showSponsorData" class="art-button-green" id='selectDataEntry' style="padding:5px; font-size: 17px;width:98.5% ">Show sponsor Data</button>
                    </td>
                </tr>
        </table>
        </center>
            </form>
        
</fieldset><br/>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> <!--<script src="js/jquery-ui-1.10.1.custom.min.js"></script>-->
<script src="script.js"></script>
<script src="script.responsive.js"></script>


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
            $("#sponsorNameHolderDiv").dialog({ autoOpen: false, title:'Choose Sponsor Name',modal: true});
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
              $('#'+item).html(data);
              
	    			  
         },error: function (jqXHR, textStatus, errorThrown) {
           alert(errorThrown);               
         }
      });
      $("#"+item+"Div").dialog("open");
               
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
     
     function setSponsorName(sponsor){
        
         if(sponsor ==="All Sponsors"){
             $("#sponsorname").val("All Sponsors");
         }else{
           var data=sponsor.split('$$>');
         
            $("#sponsorID").val(data[0]);
            $("#sponsorname").val(data[1]);

         }
         
         $("#sponsorNameHolderDiv").dialog("close");
        
     }
        
    </script>
    
<?php
    include("./includes/footer.php");
?>