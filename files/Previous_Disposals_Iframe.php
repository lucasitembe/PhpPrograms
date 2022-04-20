<link rel="stylesheet" href="table.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
    <link rel="stylesheet" href="css_style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
        <script src="js/tabcontent.js" type="text/javascript"></script>
    


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>
<?php
    @session_start();
    include("./includes/connection.php");
?>
<table width=100% border=0>
    <tr id='thead'>
        <td width=4% style='text-align: center;'><b>Sn</b></td>
        <td width=8% style='text-align: center;'><b>Disposal N<u>o</u></b></td>
        <td width=13%><b>Created Date & Time</b></td>
        <td><b>Disposal Description</b></td>
        <td></td>
    </tr>
    
    
    
<?php
    $temp = 1;
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = '';
    }
    
    //get sub department id
    if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }

    //get details depends on date from and date to (if and only if is inserted or not)
    if(isset($_GET['Date_From']) && isset($_GET['Date_To']) && ($_GET['Date_From'] != '') && ($_GET['Date_To'] != '') && ($_GET['Date_From'] != null)){
        $Date_From = $_GET['Date_From'];
        $Date_To = $_GET['Date_To'];
        $select_data = "select dp.Disposal_ID, dp.Created_Date_And_Time, dp.Disposal_Description from
                            tbl_disposal dp where dp.Sub_Department_ID = '$Sub_Department_ID' and
                                dp.Employee_ID = '$Employee_ID' and
                                    dp.Disposal_Status= 'submitted' and
                                    Created_Date between '$Date_From' and '$Date_To' order by dp.Disposal_ID desc limit 100";
    }else{
        $select_data = "select dp.Disposal_ID, dp.Created_Date_And_Time, dp.Disposal_Description from
                            tbl_disposal dp where dp.Sub_Department_ID = '$Sub_Department_ID' and
                                    dp.Disposal_Status= 'submitted' and
                                dp.Employee_ID = '$Employee_ID' order by dp.Disposal_ID desc limit 100";
    }
    
    
    
    $result = mysqli_query($conn,$select_data) or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($result)){
        echo "<tr><td>".$temp."</td>";
        echo "<td>".$row['Disposal_ID']."</td>";
        echo "<td>".$row['Created_Date_And_Time']."</td>";
        echo "<td>".$row['Disposal_Description']."</td>";
        echo "<td width=4%><button class='art-button-green'>&nbsp;&nbsp;&nbsp;Preview Disposed&nbsp;&nbsp;&nbsp;</button></a></td></tr>";
        //echo "<td width=4%><a href='#Control_Disposal_Items_Session.php?Disposal_ID=".$row['Disposal_ID']."&Pending_Disposal=True' class='art-button-green' target='_parent'>&nbsp;&nbsp;&nbsp;Preview Disposed&nbsp;&nbsp;&nbsp;</a></td></tr>";
        $temp++;
    }
?>
</table>