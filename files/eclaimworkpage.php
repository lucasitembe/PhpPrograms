<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    echo "<link rel='stylesheet' href='fixHeader.css'>";

    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Quality_Assurance'])){
	    if($_SESSION['userinfo']['Quality_Assurance'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ./index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = 0;
    }
?>

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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Quality_Assurance'] == 'yes'){ 
?>
    <a href='./qualityassuarancework.php?QualityAssuranceWork=QualityAssuranceWorkThiPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<center>
    <table width=100% style="background-color: white;">
        <tr>
            <td style='text-align: right;' width="8%"><b>Start Date</b></td>
            <td width="15%"><input type='text' name='Start_Date' id='date' autocomplete='off' style="text-align: center;"></td> 
            <td style='text-align: right;' width="8%"><b>End Date</b></td>
            <td width="15%"><input type='text' name='End_Date' id='date2' autocomplete='off' style="text-align: center;"></td>
            <td style='text-align: right;' width="9%"><b>Sponsor Name</b></td>
            <td width="10%">
                <select name='Sponsor_ID' id='Sponsor_ID' required='required'>
                    <option selected='selected'></option>
                    <?php
                        $select_Insurances = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor where Guarantor_name <> 'CASH'") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_Insurances);
                        if($no > 0){
                            while($row = mysqli_fetch_array($select_Insurances)){
                    ?>
                                <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
                    <?php
                            }
                        }
                    ?>
                </select>
            </td>
            <td style='text-align: center;' width="7%">
                <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Filter_Transaction()">
            </td>
            <td>
                <input type="text" name="Patient_Name" id="Patient_Name" placeholder="~~~~~~~~~ Enter Patient Name ~~~~~~~~~" autocomplete="off" style="text-align: center;" oninput="Filter_Transaction()">
            </td>
        </tr>
    </table>
</center>

<fieldset style='overflow-y: scroll; height: 380px; background-color: white;' id='Transaction_List'>
    <table width ='100%' class="fixTableHead">
        <thead>
            <tr style="background-color: #ccc;">
                <td width=5%><b>SN</b></td>
                <td><b>PATIENT NAME</b></td>
                <td width="15%" style="text-align: center;"><b>PATIENT #</b></td>
                <td width="15%" style="text-align: left;"><b>AGE</b></td>
                <td width="15%" style="text-align: center;"><b>SPONSOR NAME</b></td>
                <td width="12%" style="text-align: center;"><b>FOLIO NUMBER</b></td>
                <td width="15%" style="text-align: right;"><b>AMOUNT</b></td>
            </tr>
        </thead>
    </table>
    <!-- <center>
        <table width=100% border=1>
            <tr>
                <td id='Iframe_Area'>
                    <iframe width='100%' height='400px' src="eclaim_Iframe.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Payment_Type=<?php echo $Payment_Type; ?>&Insurance=<?php echo $Insurance; ?>&Patient_Type=<?php echo $Patient_Type; ?>" ></iframe>
                </td>
            </tr>
        </table>
    </center> -->
</fieldset>

<script>    
    function Filter_Transaction(){
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Name = document.getElementById("Patient_Name").value;

        if(Sponsor_ID != null && Sponsor_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById('Transaction_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if(window.XMLHttpRequest){
                myObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }
            myObject.onreadystatechange = function (){
                myData = myObject.responseText;
                if (myObject.readyState == 4) {
                  //alert(myData);
                document.getElementById('Transaction_List').innerHTML = myData;
                }
            }; //specify name of function that will handle server response........
            
            if(Patient_Name != '' && Patient_Name != null){
                myObject.open('GET','eclaim_Iframe.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
            }else{
                myObject.open('GET','eclaim_Iframe.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
            }
            myObject.send();
        }else{
            if(Start_Date=='' || Start_Date == null){
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }
            
            if(End_Date=='' || End_Date == null){
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }
            
            if(Sponsor_ID=='' || Sponsor_ID == null){
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Details(Folio_Number,Sponsor_ID,Registration_ID,Patient_Bill_ID,Check_In_ID){
        var winClose=popupwindow('eclaimreport.php?Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Registration_ID='+Registration_ID+'&Patient_Bill_ID='+Patient_Bill_ID+'&Check_In_ID='+Check_In_ID, 'e-CLAIM DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<?php
    include("./includes/footer.php");
?>