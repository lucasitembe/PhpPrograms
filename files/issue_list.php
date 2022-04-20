<?php

        include("./includes/header.php");
        include("./includes/connection.php");
        include("./includes/functions.php");
        $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
    if(isset($_SESSION['userinfo']))
	{
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
		{
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else
			{
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
    }else
		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }

          if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a href='storageandsupply.php' class='art-button-green'>STORAGE AND SUPPLY</a>";
            }
    }

    if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='issue_list.php?requisition=new&page=issue_list' class='art-button-green'>NEW ISSUE</a>";
			}
	}
           if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='issue_list.php?lform=saveData' class='art-button-green'>PROCESS ISSUE</a>";
			}
	}
 
//            if(isset($_SESSION['userinfo'])){
//		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
//			{ 
//			echo "<a href='#' class='art-button-green'>EDIT ISSUE</a>";
//			}
//	}  
            if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='issue_list.php?lform=sentData' class='art-button-green'>PREVIOUS ISSUE</a>";
			}
	}
        if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }

filterByDate();

if(isset($_GET['lform'])){
    switch($_GET['lform']){
        case 'sentData':
                $where="Sent";
            break;
        case 'saveData':
                 $where="Save";
            break;
    }
    
    
   
                $issTable="<fieldset>
                        <form name='' action='issuenotes.php?fr=list&issued=true&page=issuenotes' method='POST'>
                        <center>";
                $issTable.="<table width=100%>";
                $issTable.="<tr>";
                $issTable.="<td>Issue Number</td><td>Requision Number</td><td>Employee Name</td>";
                        $issTable.="<td></td>";
                $issTable.="</tr>";
                
                $sIssued=mysqli_query($conn,"SELECT * FROM tbl_issued WHERE issue_status='$where' ORDER BY issue_ID DESC");
                while($disp4=mysqli_fetch_array($sIssued)){             
                        $issTable.="<tr>";
                        $issTable.="<td>".$disp4['issue_ID']."</td>"; 
                        $issTable.="<td>".$disp4['req_id']."</td>";
                        $issTable.="<td>".$disp4['employeeIssue']."</td>";
                        if($_GET['lform']=='saveData'){
                        $issTable.="<td>"
                                 . "<input name='check_value' type='checkbox' value='".$disp4['req_id']."/".$disp4['issue_ID']."'></td>";
                        }else{
                            $issTable.="<td width='8%'>"
                                 . "<a href='issue_report.php?issue_ID={$disp4['issue_ID']}&req_id={$disp4['req_id']}' target='_blank' class='art-button-green'>Print Priview</a></td>";
                        }
                        $issTable.="</tr>";
                    }
                 if($_GET['lform']=='saveData'){
                         $issTable.="<tr><td colspan='4'><input name='submit' type='submit' value='SELECT' style='padding:2px;font-size:16px;width:20%;float:right;'></td></tr>";
                        }
                $issTable.="</table>";
                $issTable.="</center></form> 
                        </fieldset>";

echo $issTable;
}

If(isset($_GET['requisition'])){
		switch($_GET['requisition']){
		case "new":
			$action="issuenotes.php?fr=list";
                        $whereV="Sent";
		break;
	}
		
?>

<form name="myform" action="<?php echo $action; ?>" method="POST">
<fieldset>  
        <center> 
	<?php
          $display_table="<table width=100%> 
				<tr> 
					<td><b>Requisition Number<b></td>
					<td><b>Requisition Date<b></td>
					<td><b>Requisition Description<b></td>
					<td><b>From<b></td>";
                $display_table.="<td></td>";
		$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT req.*,sDep.Sub_Department_Name AS store FROM tbl_requizition as req
									JOIN tbl_sub_department as sDep ON sDep.Sub_Department_ID=req.Store_Need
									WHERE Requizition_Status='$whereV' AND req.isISSUED='0' ORDER BY Requizition_ID DESC  ");
			
			
			while($result_query=mysqli_fetch_array($query_data)){
				$display_table.=" 
				<tr> 
					<td>".$result_query['Requizition_ID']."</td>
					<td>".$result_query['Created_Date_Time']."</td>
					<td>".$result_query['Requizition_Descroption']."</td>
					<td>".$result_query['store']."</td>";
                        $display_table.=" <td><input name='check_value' type='checkbox' value='".$result_query['Requizition_ID']."'></td>";}
			$display_table.="</tr>";
			$display_table.="</table>";
			
			echo $display_table;
                        ?>
<input name='submit' type='submit' value="SELECT" style="padding:2px;font-size:16px;width:20%;float:right;">
</form>	


<?php
}
if(isset($_GET['action']))
if($_GET['action']=='saved'){
                ?>
            <script type="text/javascript">
                    alert("Item(s) Saved Successfully");

            </script>
             <?php   
    }else if($_GET['action']=='sent'){
            ?>
<script type="text/javascript">
            alert("Item(s) Sent Successfully");

</script>
     <?php     
    }
	include("./includes/footer.php");

?>
<!--functions to display date and time for filters-->
<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
    </script>
    <!--End datetimepicker-->