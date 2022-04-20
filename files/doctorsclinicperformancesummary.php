<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
        $Date_From='';//@$_POST['Date_From'];
        $Date_To='';//@$_POST['Date_To'];
        
        if(!isset($_GET['Date_From'])){
             $Date_From= date('Y-m-d H:m');
        }else{
            $Date_From=$_GET['Date_From']; 
        }
        if(!isset($_GET['Date_To'])){
             $Date_To=date('Y-m-d H:m');;
        }else{
            $Date_To=$_GET['Date_To'];
        }
?>
<a href='./managementworkspage.php?ManagementWorksPage=ManagementWorksPageThisPage' class='art-button-green'>
        BACK
    </a>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/><br/>
<center>
    <fieldset>  
        <div style="width:100%;">   
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:20%;display:inline'>
                    <option value="All">All Sponsors</option>
                    <?php
                    $qr = "SELECT * FROM tbl_sponsor";
                    $sponsor_results = mysqli_query($conn,$qr);
                    while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                        ?>
                        <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                 <select width="20%"  name='clinic_id' style='text-align: center;width:20%;display:inline' onchange="filterPatient()" id="clinic_id">
                       <option value="All">All Clinics</option>
                    <?php
                    $qr = "SELECT Clinic_ID,Clinic_Name FROM tbl_clinic ";
                    $clinic_results = mysqli_query($conn,$qr);
                    while ($clinic_rows = mysqli_fetch_assoc($clinic_results)) {
                        ?>
                        <option value='<?php echo $clinic_rows['Clinic_ID']; ?>'><?php echo $clinic_rows['Clinic_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                
        </div>
        </fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">DOCTORS CLINIC PERFORMANCE SUMMERY REPORT </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'doctorsclinicperformance_Iframe.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function filterPatient() {
      document.getElementById('Date_From').style.border="1px solid #C0C1C6";
      document.getElementById('Date_To').style.border="1px solid #C0C1C6";
      
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Clinic_id = document.getElementById('clinic_id').value;
        var range='<center>';
        
        var SponsorName=$('#Sponsor_ID option:selected').text();
        var ClinicName=$('#clinic_id option:selected').text();
       
        
        
        if(Date_From =='' && Date_To !=''){
             alert("Please enter start date");
             
             document.getElementById('Date_From').style.border="2px solid red";
             exit;
        }if(Date_From !='' && Date_To ==''){
             alert("Please enter end date");
             document.getElementById('Date_To').style.border="2px solid red";
             exit;
        }
        
        if(Date_From !='' && Date_To !=''){
              range +=" FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
        }
        if(Sponsor != 'All'){
             if(Date_From !='' && Date_To !=''){
                 range +="<br/>Sponsor:<span class='dates'>"+SponsorName+"</span>";
             }else{
                 range +="Sponsor:<span class='dates'>"+SponsorName+"</span>";
             }
             
        }
        if(Clinic_id != 'All'){
             if(Sponsor != 'All'){
                       range +=" | Clinic Name:<span class='dates'>"+ClinicName+"</span>";
       
             }else{
                  if(Date_From !='' && Date_To !=''){
                      range +="<br/>Clinic Name:<span class='dates'>"+ClinicName+"</span>";
                  }else{
                      range +="Clinic Name:<span class='dates'>"+ClinicName+"</span>";
                  }
       
             }
        }
         range +="</center>";
        
        
         document.getElementById('dateRange').innerHTML ="DOCTORS CLINIC PERFORMANCE SUMMERY REPORT"+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "doctorsclinicperformance_Iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor+ '&clinic=' + Clinic_id,
            
            success: function (html) {
              if(html != ''){
               
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientslist').DataTable({
                    'bJQueryUI': true
                });
            }
            }
        });
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patientslist').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>