<?php
	include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			@session_start();
			if(!isset($_SESSION['Optical_Supervisor'])){ 
			    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
			}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Start_Date = $Today.' 00:00';
    }
?>
<!-- <a href="patientslistoptical.php?PatientsListOptical=PatientsListOpticalThisPage" class="art-button-green">PATIENTS FROM OUTSIDE</a>
<a href="opticalpendingtransactions.php?OpticalPendingTransactions=OpticalPendingTransactionsThisPage" class="art-button-green">PENDING TRANSACTIONS</a> -->
<a href="opticalworkspage.php?OpticalWorks=OpticalWorksThisPage" class="art-button-green">BACK</a>
<br/><br/>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
 </style>
<center>
<fieldset>
    <table width="100%">
        <tr>
            <td width="7%" style="text-align: right;">Start Date</td>
            <td width="15%">
                <input type='text' name='Date_From' id='date_From' style="text-align: center;" value="" autocomplete='off' style="text-align: center;"readonly="readonly">
            </td>
            <td width="7%" style="text-align: right;">End Date</td>
            <td width="15%">
                <input type='text' name='Date_To' id='date_To' style="text-align: center;" value="" autocomplete='off'readonly="readonly">
            </td>
            <td width="7%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID">
                    <option selected="selected">All</option>
                <?php
                    //select sponsors
                    $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if($num > 0){
                        while ($data = mysqli_fetch_array($select)) {
                ?>
                        <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                <?php
                        }
                    }
                ?>
                </select>
            </td>
            <td width="8%" style="text-align: center;">
            <input type="button" name="Filter" id="Filter" class="art-button-green" value="FILTER" onclick="Filter_Patients()">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder=' ~~~~~ Enter Patient Name ~~~~~' autocomplete='off'>
            </td>
            <td colspan="3">
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search2()' onkeyup='Patient_List_Search2()' placeholder=' ~~~~~ Enter Patient Number ~~~~~' autocomplete='off'>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 360px;' id='Patient_List'>
	<legend align='right'><b>OPTICAL ~ PATIENTS FROM DOCTORS</b></legend>
    <table width="100%" class="table table-striped table-hover">

        <thead style="background-color:#bdb5ac;">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;"><b>PATIENT NUMBER</b></th>
                <th style="text-align:left;"><b>SPONSOR</b></th>
                <th style="text-align:left;"><b>AGE</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>MEMBER NUMBER</b></th>
            </tr>
        </thead>
            
        

        <?php
            $select = mysqli_query($conn,"SELECT pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, s.consultation_ID, s.Spectacle_ID, s.Patient_Type from
                                    tbl_patient_registration pr, tbl_sponsor sp, tbl_spectacles s where
                                    pr.Sponsor_ID = sp.Sponsor_ID and
                                    s.Registration_ID = pr.Registration_ID and
                                    s.Spectacle_Status = 'pending' order by Spectacle_ID desc limit 100") or die(mysqli_error($conn));
            $select_lens=" SELECT * FROM tbl_refraction WHERE Registration_ID=17045 group BY `refraction_ID` DESC limit 1";
            $no = mysqli_num_rows($select);
            if($no > 0){
                $temp = 0;
                while ($data = mysqli_fetch_array($select)) {
                    //calculate age
                    $Date_Of_Birth = $data['Date_Of_Birth'];
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    $diff = $date1 -> diff($date2);
                    $age = $diff->y." Years, ";
                    $age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";
                    $Patient_Type = $data['Patient_Type'];
                    if(strtolower($Patient_Type) == 'inpatient'){
        ?>
                        <tr>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Patient_Name']; ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Registration_ID']; ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Guarantor_Name']; ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['Gender'])); ?></a></td>
                            <td><a href="inpatientopticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Member_Number']; ?></a></td>
                        </tr>
        <?php
                    }else{
        ?>
                        <tr>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ++$temp; ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Patient_Name']; ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Registration_ID']; ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Guarantor_Name']; ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $age; ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo ucwords(strtolower($data['Gender'])); ?></a></td>
                            <td><a href="opticaltransaction.php?consultation_ID=<?php echo $data['consultation_ID']; ?>&Registration_ID=<?php echo $data['Registration_ID']; ?>&Spectacle_ID=<?php echo $data['Spectacle_ID']; ?>&OpticalTransaction=OpticalTransactionThisPage" style="text-decoration: none;"><?php echo $data['Member_Number']; ?></a></td>
                        </tr>
        <?php
                    }
                }
            }
        ?>
    	</table>
</fieldset>

<script type="text/javascript">
    function Patient_List_Search(){
        document.getElementById("Patient_Number").value = '';
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;

        if (window.XMLHttpRequest) {
            myObjectFilter = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilter.overrideMimeType('text/xml');
        }

        myObjectFilter.onreadystatechange = function () {
            dataFilter = myObjectFilter.responseText;
            if (myObjectFilter.readyState == 4) {
                document.getElementById('Patient_List').innerHTML = dataFilter;
            }
        }; //specify name of function that will handle server response........

        myObjectFilter.open('GET','Optical_Patient_List_Filter.php?Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Patient_Name='+Patient_Name, true);
        myObjectFilter.send();
    }
</script>

<script type="text/javascript">
    function Patient_List_Search2(){
        document.getElementById("Search_Patient").value = '';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;

        if (window.XMLHttpRequest) {
            myObjectFilterSearch2 = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectFilterSearch2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectFilterSearch2.overrideMimeType('text/xml');
        }

        myObjectFilterSearch2.onreadystatechange = function () {
            dataFilter2 = myObjectFilterSearch2.responseText;
            if (myObjectFilterSearch2.readyState == 4) {
                document.getElementById('Patient_List').innerHTML = dataFilter2;
            }
        }; //specify name of function that will handle server response........

        myObjectFilterSearch2.open('GET','Optical_Patient_List_Filter.php?Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Patient_Number='+Patient_Number, true);
        myObjectFilterSearch2.send();
    }    
</script>

<script type="text/javascript">
    function Filter_Patients(){
        document.getElementById("Search_Patient").value = '';
        document.getElementById("Patient_Number").value = '';
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("date_To").style = 'border: 2px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObjectFilterPatient = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectFilterPatient = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilterPatient.overrideMimeType('text/xml');
            }

            myObjectFilterPatient.onreadystatechange = function () {
                dataFilter3 = myObjectFilterPatient.responseText;
                if (myObjectFilterPatient.readyState == 4) {
                    document.getElementById('Patient_List').innerHTML = dataFilter3;
                }
            }; //specify name of function that will handle server response........

            myObjectFilterPatient.open('GET','Optical_Patient_List_Filter.php?Sponsor_ID='+Sponsor_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date, true);
            myObjectFilterPatient.send();
        }else{
            if(Start_Date=='' || Start_Date == null){
                document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_From").style = 'border: 2px solid black; text-align: center;';
                document.getElementById("date_From").style = 'border: 2px solid black; text-align: center;';
            }

            if(End_Date=='' || End_Date == null){
                document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_To").style = 'border: 2px solid black; text-align: center;';
                document.getElementById("date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:05});
    $('#date_To').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:05});
</script>

<?php
    include("./includes/footer.php");
?>