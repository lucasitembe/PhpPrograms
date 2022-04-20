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
    }
?>
<a href="opticalreports.php?OpticalReports=OpticalReportsThisPage" class="art-button-green">BACK</a>
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
<fieldset>
    <center>
    	<table width="90%">
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" class="form-control">
                    <option selected="selected" value="0">All</option>
            <?php
                //get list of sponsors
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
            <td style='text-align: right;'><b>Start Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Select Start Date ~~~' readonly='readonly' value='' class="form-control">
            </td>
            <td style='text-align: right;'><b>End Date</b></td>
            <td>
                <input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='~~~ Select End Date ~~~' readonly='readonly' value='' class="form-control">
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_list()'>
            </td>
            <td>
                <input type="button" name="Preview" id="Preview" value="PREVIEW" class="art-button-green" onclick="Preview_Report()">
            </td>
    	</table>
    </center>
</fieldset>
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:01});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:01});
    </script>
    <!--End datetimepicker-->    

<fieldset style='overflow-y: scroll; height: 380px;' id='List_Area'>
    <legend align="right">SPECTACLES SALES REPORT</legend>
    <table width="100%" class="table table-striped table-hover">
        <tr style="background-color:#bdb5ac">
            <td width="5%"><b>SN</b></td>
            <td><b>PARTICULARS</b></td>
            <td colspan="2" width="20%" style="text-align: center;"><b>CASH</b></td>
            <td colspan="2" width="20%" style="text-align: center;"><b>CREDIT</b></td>
            <td colspan="2" width="20%" style="text-align: center;"><b>TOTAL</b></td>
            <td colspan="2"></td>
            <td width="7%" style="text-align: left;"><b>QTY</b></td>
            <td width="13%" style="text-align: left;"><b>VALUE</b></td>
            <td width="7%" style="text-align: left;"><b>QTY</b></td>
            <td width="13%" style="text-align: left;"><b>VALUE</b></td>
            <td width="7%" style="text-align: left;"><b>QTY</b></td>
            <td width="13%" style="text-align: left;"><b>VALUE</b></td>
        </tr>
    </table>
</fieldset>

<script type="text/javascript">
    function filter_list(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Date_From").value;
        var End_Date = document.getElementById("Date_To").value;    

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            if(window.XMLHttpRequest){
                mySalesObject = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                mySalesObject = new ActiveXObject('Micrsoft.XMLHTTP');
                mySalesObject.overrideMimeType('text/xml');
            }
            mySalesObject.onreadystatechange = function (){
                dataSales = mySalesObject.responseText;
                if (mySalesObject.readyState == 4) {
                    document.getElementById('List_Area').innerHTML = dataSales;
                }
            }; //specify name of function that will handle server response........
            
            mySalesObject.open('GET','Spectacle_Sales_Report.php?Start_Date='+Start_Date+'&End_Date='+End_Date+'&Sponsor_ID='+Sponsor_ID,true);
            mySalesObject.send();
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid red; text-align: center;';
            }

            if(End_Date == null || End_Date == ''){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid red; text-align: center;';
            }
        }
    }    
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("Date_From").value;
        var End_Date = document.getElementById("Date_To").value;
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            window.open("spectaclereport.php?Start_Date="+Start_Date+"&End_Date="+End_Date+"&Sponsor_ID="+Sponsor_ID+"SpectacleReport=SpectacleReportThisPage","_blank");
        }else{
            if(Start_Date == null || Start_Date == ''){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(End_Date == null || End_Date == ''){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<?php
    include("./includes/footer.php");
?>