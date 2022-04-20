<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Dialysis_Works'])){
	    if($_SESSION['userinfo']['Dialysis_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			
			@session_start();
			if(!isset($_SESSION['Dialysis_Supervisor'])){ 
				header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
			}
					
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>


<a href='./dialysisSessions.php' class='art-button-green'>SESSIONS SETUP</a>

<a href='./dialysisworkspage.php' class='art-button-green'>BACK</a>


<center>

    <fieldset style="background-color:white">
        <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>HAEMODIALYIS SESSION REPORT</b></legend> 
        <center>
            <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
                <tr>
                    <td style="text-align:center">

                        <input type="text" autocomplete="off" style='text-align: center;width:25%;display:inline' id="session_date" placeholder="Select Session Date"/>

                        <select name='Session_ID' id='Session_ID' onchange="filterPatient()" style='text-align: center;width:15%;display:inline;padding: 4px;'>
                            <option value="All">All Sessions</option>
                            <?php
                            $session_qr = "SELECT * FROM tbl_dialysis_session_time_setup";
                            $session_results = mysqli_query($conn,$session_qr);
                            while ($session_rows = mysqli_fetch_assoc($session_results)) {
                                ?>
                                <option value='<?php echo $session_rows['session_time_setup_id']; ?>'><?php echo $session_rows['session_description']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type='text' name='Search_Patient' style='text-align: center;width:18%;display:inline' id='Search_Patient' oninput="cleaother('phone');filterPatient();" placeholder='Search By Patient Name'>
                        <input type='text' name='Search_Patient_No' style='text-align: center;width:15%;display:inline' id='Search_Patient_No' oninput="cleaother('name');filterPatient();" placeholder='Search By Patient Number'>
                        <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                        <input type="button" value="PREVIEW" class="art-button-green" onclick="preview_patient()">
                    </td>
                </tr>
            </table>
        </center>

        <center>
            <table width=100% border=1>
                <tr>
                    <td>
                        <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                        <div id="Search_Iframe" style="min-height: 400px;overflow-y: auto;overflow-x: hidden">
                            <?php include 'dialysisSessionReport_Iframe.php'; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </center>
    </fieldset><br/>
    <script>
      function cleaother(target){
         if(target=='phone'){
             $('#Search_Patient_No').val('');
         } else if(target=='name'){
              $('#Search_Patient').val('');
         }
      }
    </script>
 <script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#session_date').datepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                lang: 'en'
            });
            $('#session_date').datepicker({value: '', step: 1});
           
        });
    </script>
    <script>
        function filterPatient() {
            var Patient_Name = document.getElementById("Search_Patient").value;
            var Patient_Number = document.getElementById("Search_Patient_No").value;
            var session_date = document.getElementById('session_date').value;
            var Session_ID = document.getElementById("Session_ID").value;
            var datastring = 'Patient_Name=' + Patient_Name+ '&Patient_Number=' + Patient_Number +'&session_date=' + session_date + '&Session_ID=' + Session_ID;
            if(session_date==''){alert('Please, select session date');exit;}
            
            $.ajax({
                type: 'GET',
                url: 'dialysisSessionReport_Iframe.php',
                data: datastring,
                cache: false,
                beforeSend: function (xhr) {
                    $("#progressStatus").show();
                },
                success: function (html) {
                    if (html != '') {
                        $("#Search_Iframe").html(html);

                        $.fn.dataTableExt.sErrMode = 'throw';
                        $('#patients-list').DataTable({
                            "bJQueryUI": true

                        });
                    }
                }, complete: function (jqXHR, textStatus) {
                    $("#progressStatus").hide();
                }, error: function (html) {
                    $("#progressStatus").hide();
                }
            });

        }

        function preview_patient() {
            var Patient_Name = document.getElementById("Search_Patient").value;
            var Patient_Number = document.getElementById("Search_Patient_No").value;
            var session_date = document.getElementById('session_date').value;
            var Session_ID = document.getElementById("Session_ID").value;
            var datastring = 'Patient_Name=' + Patient_Name+ '&Patient_Number=' + Patient_Number +'&session_date=' + session_date + '&Session_ID=' + Session_ID;
            if(session_date==''){alert('Please, select session date');exit;}
            
            $.ajax({
                type: 'GET',
                url: 'dialysis_session_report.php.php',
                data: datastring,
                cache: false,
                beforeSend: function (xhr) {
                    $("#progressStatus").show();
                },
                success: function (html) {
                    if (html != '') {
                        $("#Search_Iframe").html(html);

                        $.fn.dataTableExt.sErrMode = 'throw';
                        $('#patients-list').DataTable({
                            "bJQueryUI": true

                        });
                    }
                }, complete: function (jqXHR, textStatus) {
                    $("#progressStatus").hide();
                }, error: function (html) {
                    $("#progressStatus").hide();
                }
            });

        }
    </script>


    <?php
    include("./includes/footer.php");
    ?>

    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
<!-- <br/> -->
<?php
    // include("./includes/footer.php");
?>

