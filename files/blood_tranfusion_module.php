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

<a href="laboratory.php?section=Laboratory&LaboratoryWorks=LaboratoryWorksThisPage" class="art-button-green">BACK</a>

    <div style='float: right;'>
<span style='font-weight: bold; font-size: 18px;'>COLOR CODE KEY: <span>
<input type="button"  style='background: red; color: white;padding: 10px; border: none; border-radius: 5px; font-weight: bold;' value='URGENT REQUEST' name="" id="">
</div>
    <!-- <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green"> -->






<?php
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;
?>

<br/><br/>
<center>
    <table width="95%">
        <tr style='background: #fff !important;'>
            <td width="20%">
                <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' value='<?php echo $Date_From ?>' placeholder='Start Date' id='Date_From' style="text-align: center">    
            </td>
            <td width="20%"><input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' value='<?php echo $Date_To ?>'  placeholder='End Date' id='Date_To' style="text-align: center"></td>  
            <td width="20%"> 
                <input type='text' name='Patient_Name' title='Incase You want to filter by Name'  id='Patient_Name' style='text-align: center;' onkeyup='filterPatient()' placeholder='~~~~~~~Search Patient Name~~~~~~~~'>
            </td>
            <td width="20%"> 
                <input type='text' name='Patient_Number' title='Incase You want to filter by Registration Number'  id='Patient_Number' style='text-align: center;'  onkeyup='filterPatient()' placeholder='~~~~~~~~Search Patient Number~~~~~~~~~'>
            </td>
            <td width="20%" class='hide'>
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange='filterPatient()'  style='text-align: center;width:100%;display:inline'>
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
                </td>

                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterPatient()' class='art-button-green' value='FILTER'>
                </td>
<!--                 
                <td style='text-align: center;'>
                <input type='button' name='Print_Filter' id='Print_List' class='art-button-green' value='PRIVIEW &amp; PRINT'>
                </td> -->
        </tr>
    </table>
</center>
<br>
<fieldset>  
    <legend style='font-size: 17px;' align=center><b>BLOOD TRANSFUSION - PATIENT LIST</b></legend>
        <center>
            <table width=100% border=1  class="hiv_table" >
                <tr style='background: #fff !important;'>
                    <td id='Search_Iframe'>
                        <!-- <iframe width='100%' height=420px src='blood_tranfusion_module_iframe.php?Patient_Name='></iframe> -->
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
    	$(document).ready(function(){
            filterPatient();
	})
    function filterPatient(){
		Patient_Number = document.getElementById('Patient_Number').value;
		Patient_Name = document.getElementById('Patient_Name').value;
		Sponsor_ID = document.getElementById('Sponsor_ID').value;
		Date_From = document.getElementById('Date_From').value;
		Date_To = document.getElementById('Date_To').value;

        // document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        

        $.ajax({
            type: 'GET',
            url: 'blood_tranfusion_module_iframe.php',
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&Patient_Number=' + Patient_Number,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '' && html != '0') {

                    $('#Search_Iframe').html(html);
                    $('.display').dataTable({
                        "bJQueryUI": true
                    });
                } else if (html == '0') {
                    $('#Search_Iframe').html('');
                }
            }

        });

        // document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='blood_tranfusion_module_iframe.php?Date_To="+Date_To+"&Date_From="+Date_From+"&Employee_ID="+Employee_ID+"&Sponsor_ID="+Sponsor_ID+"&Patient_Name="+Patient_Name+"&number="+number+"&Patient_Number="+Patient_Number+"'></iframe>";
    }

    $('#Print_List').on('click',function(){
     var Patient_Number=$('#Patient_Number').val();
     var Patient_Name=$('#Patient_Name').val();
     var Date_From=$('#Date_From').val();
     var Date_To=$('#Date_To').val();
     var Employee_ID=$('#Employee_ID').val();
     var number=$('#number').val();
    //  if(Date_From=='' || Date_From=='NULL' || Date_To=='' || Date_To=='NULL'){
    //      alert('Select dates to continue');
    //      return false;
    //  }
     var Sponsor_ID=$('#Sponsor_ID').val(); 
     
     window.open('Print_Doctor_Surgery_Schedule.php?action=filter&number='+number+'&Date_From='+Date_From+'&Date_To='+Date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID+'&Patient_Name='+Patient_Name+'&Patient_Number='+Patient_Number);
        
    });
</script>

<script>
    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 1});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Date_To').datetimepicker({value: '', step: 1});
    
         $('#Date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Date_Fromx').datetimepicker({value: '', step: 1});

    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#number").select2();
    });

    $('#Date_Time').on('click',function(){ 
        var id=$(this).attr('id');
        $('#Date_From_val').val(id);
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
        // var pay_ID=$('#Date_From_val').val();
        // var DateOfService=$('#Date_Fromx').val();
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