<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/functions.php");
//        $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
//	
//    if(!isset($_SESSION['userinfo'])){ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");}
//    if(isset($_SESSION['userinfo']))
//	{
//		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work']))
//		{
//			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
//		}else
//			{
//				header("Location: ./index.php?InvalidPrivilege=yes");
//			}
//    }else
//		{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
//
//
//	if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
//            { 
//            echo "<a href='storageandsupply.php' class='art-button-green'>STORAGE AND SUPPLY</a>";
//            }
//    }
//
//    if(isset($_SESSION['userinfo'])){
//        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
//            { 
//            echo "<a href='requizition.php?status=new' class='art-button-green'>NEW</a>";
//            }
//    }
//                
//              if(isset($_SESSION['userinfo'])){
//                  if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
//                                  { 
//                                          echo "<a href='requisition_list.php?requisition=list&lForm=saveData&page=requisition_list' class='art-button-green'>PROCESS REQUISITION</a>";
//                                  }
//                  }
//           
//            if(isset($_SESSION['userinfo'])){
//                    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
//                    echo "<a href='requisition_list.php?requisition=list&lForm=sentData&page=requisition_list' class='art-button-green'>
//                    PREVIOUS ORDER</a>";
//                    }
//            }
//
// 
//    /*
//	*check where the page should go
//	*/
//	If(isset($_GET['requisition']))
//		switch($_GET['requisition']){
//		case "new":
//			$action="issuenotes.php?fr=list";
//                        $whereV="Sent";
//		break;
//		case "list":
//                    if($_GET['lForm']=='sentData'){
//                        $action=''; $whereV="Sent";
//                                            }
//                                            else if($_GET['lForm']=='saveData'){
//                                                $action="requizition.php?page=requizition";
//                                                $whereV="Saved";
//                                                }
//		break;
//	}


    filterByDate();
		
?>
<form name="myform" action="" method="POST">
<fieldset>  
        <center> 
	<?php
          $display_table="<table width=100%> 
				<tr> 
					<td><b>Requisition Number<b></td>
					<td><b>Requisition Date<b></td>
					<td><b>Requisition Description<b></td>
                                        <td><b>Reported  by</td>
                                        <td><b>Requisition jobcard Number</td>
					<td><b>Departiment need services<b></td>
                                        
                                          ";
             $display_table.="<td width='7%'></td>";
             
             
             
             
             
             
			$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT* FROM tbl_requisition_for_engineering 
									 ORDER BY requisition_ID DESC ");
			
			
			while($result_query=mysqli_fetch_array($query_data)){
				$display_table.=" 
				<tr> 
					<td><a href='requisition_report.php'>".$result_query['requisition_ID']."</a></td>
					<td><a href='requisition_report.php'>".$result_query['date_of_requisition']."</a></td>
					<td><a href='requisition_report.php'>".$result_query['description_works_to_done']."</a></td>
                                        <td><a href='requisition_report.php'>".$result_query['employee_name']."</a></td>
                                        <td><a href='requisition_report.php'>".$result_query['job_card_ID']."</a></td>
					<td><a href='requisition_report.php'>".$result_query['select_dept']."</a></td>
                                        <td><a href='requisition_report.php'>".$result_query['approved_by']."</a></td>
                                            <td>
                                                <select>
                                                    <option>Select Section</option>
                                                    <option>Plumber</option>
                                                    <option>Electrical</option>
                                                    <option>Biometric</option>
                                                </select>
                                            </td>
                                        <td><a href='requisition_list.php?requisition=list&lForm=sentData&page=requisition_list' class='art-button-green'>ASSIGN REQUISITION</a></td>"
                                        ;
                        if(isset($_GET['lForm']))
			if($_GET['lForm']=='saveData'){
                            $display_table.="<td><input name='check_value' type='checkbox' value='".$result_query['requisition_ID']."'></td>";
                        }else{
				$display_table.="<td><a href='requisition_report.php?requision_id={$result_query['requisition_ID']}' class='art-button-green' target='_blank' >Print Preview</a></td>";
			}
				$display_table.="</tr>";
			}
			
			$display_table.="</table>";
			
			echo $display_table;




?>
</form>	
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
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