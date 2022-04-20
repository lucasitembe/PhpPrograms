<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }

    //get employee name
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	    if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            echo "<a href='Laboratory_Control_Consumption_Sessions.php?New_Consumption=True' class='art-button-green'>NEW CONSUMPTION</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            echo "<a href='laboratorypendingConsumptions.php?PendingConsumptions=PendingConsumptionsThisPage' class='art-button-green'>PENDING CONSUMPTIONS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            echo "<a href='laboratorypreviousConsumptions.php?PreviousConsumptions=PreviousConsumptionsThisPage' class='art-button-green'>PREVIOUS CONSUMPTIONS</a>";
        }
    }

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
            echo "<a href='employeeconsumptionnote.php?EmployeeeConsumptionNote=EmployeeeConsumptionNoteThisPage' class='art-button-green'>BACK</a>";
        }
    }


    //get sub department id & name
    if(isset($_SESSION['Laboratory_ID'])){
        $Sub_Department_ID = $_SESSION['Laboratory_ID'];

        //get sub department name
        $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if($num > 0){
            while($data = mysqli_fetch_array($select)){
                $Sub_Department_Name = $data['Sub_Department_Name'];
            }
        }else{
            $Sub_Department_Name = '';
        }
    }
?>




    <!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () {
            $("#date").datepicker({
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                //buttonImageOnly: true,
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });

        });
    </script>

<!--    end of datepicker script-->

<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () {
            $("#date2").datepicker({
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                //buttonImageOnly: true,
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });

        });
    </script>
<!--    end of datepicker script-->

<br/><br/>
<center>
    <table width=60%>
        <tr>
            <td><b>From<b></td>
            <td width=30%>
                <input type='text' name='Date_From' id='date' required='required' autocomplete='off'>
            </td>
            <td><b>To<b></td>
            <td width=30%>
                <input type='text' name='Date_To' id='date2' required='required' autocomplete='off'>
            </td>
            <td>
                <input type='button' name='submit' value='FILTER' class='art-button-green'>
            </td>
        </tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 400px;' id='Items_Fieldset'>
    <legend align=right><b><?php if(isset($_SESSION['Laboratory_ID'])){ echo $Sub_Department_Name; }?>, Pending Consumptions prepared by : <?php echo $Employee_Name; ?></b></legend>
<table width="100%">
    <tr>
        <td width="5%"><b>SN</b></td>
        <td width="10%"><b>CONSUMPTION#</b></td>
        <td width=""><b>DESCRIPTION</b></td>
        <td width=""><b>DEPARTMENT NAME</b></td>
        <td width=""><b>EMPLOYEE RECEIVES</b></td>
        <td width="10%"><b>ACTION</b></td>
    </tr>

<?php
    $select = mysqli_query($conn,"select c.Consumption_ID, c.Consumption_Description, emp.Employee_Name, sd.Sub_Department_Name
                            from tbl_consumption c, tbl_employee emp, tbl_sub_department sd where 
                            c.Sub_Department_ID = '$Sub_Department_ID' and 
                            emp.Employee_ID = c.Employee_Need and
                            sd.Sub_Department_ID = c.Sub_Department_ID and
                            Consumption_Status = 'pending' order by Consumption_ID") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
?>
            <tr>
                <td width="5%"><?php echo ++$temp; ?></td>
                <td width="10%"><?php echo $data['Consumption_ID']; ?></td>
                <td width=""><?php echo $data['Consumption_Description']; ?></td>
                <td width=""><?php echo $data['Sub_Department_Name']; ?></td>
                <td width=""><?php echo $data['Employee_Name']; ?></td>
                <td width="">
                    <a href="Laboratory_Control_Consumption_Sessions.php?Pending_Consumption=true&Consumption_ID=<?php echo $data['Consumption_ID']; ?>" class="art-button-green">Process</a>
                </td>
            </tr>
<?php
        }
    }
?>
</table>
    <!--<iframe src='Laboratory_Pending_Consumptions_Iframe.php?Employee_ID=<?php echo $Employee_ID; ?>&Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>' width=100% height=380px></iframe>-->
</fieldset>

<?php
    include('./includes/footer.php');
?>