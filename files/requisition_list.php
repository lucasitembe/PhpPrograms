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
            echo "<a href='requizition.php?status=new' class='art-button-green'>NEW</a>";
            }
    }
                
              if(isset($_SESSION['userinfo'])){
                  if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
                                  { 
                                          echo "<a href='requisition_list.php?requisition=list&lForm=saveData&page=requisition_list' class='art-button-green'>PROCESS REQUISITION</a>";
                                  }
                  }
           
            if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                    echo "<a href='requisition_list.php?requisition=list&lForm=sentData&page=requisition_list' class='art-button-green'>
                    PREVIOUS ORDER</a>";
                    }
            }

 
    /*
	*check where the page should go
	*/
	If(isset($_GET['requisition']))
		switch($_GET['requisition']){
		case "new":
			$action="issuenotes.php?fr=list";
                        $whereV="Sent";
		break;
		case "list":
                    if($_GET['lForm']=='sentData'){
                        $action=''; $whereV="Sent";
                                            }
                                            else if($_GET['lForm']=='saveData'){
                                                $action="requizition.php?page=requizition";
                                                $whereV="Saved";
                                                }
		break;
	}


    filterByDate();
		
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
             $display_table.="<td width='8%'></td>";
			$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT req.*,sDep.Sub_Department_Name AS store FROM tbl_requizition as req
									JOIN tbl_sub_department as sDep ON sDep.Sub_Department_ID=req.Store_Need
									WHERE Requizition_Status='$whereV' ORDER BY Requizition_ID DESC  ");
			
			
			while($result_query=mysqli_fetch_array($query_data)){
				$display_table.=" 
				<tr> 
					<td>".$result_query['Requizition_ID']."</td>
					<td>".$result_query['Created_Date_Time']."</td>
					<td>".$result_query['Requizition_Descroption']."</td>
					<td>".$result_query['store']."</td>";
                        if(isset($_GET['lForm']))
			if($_GET['lForm']=='saveData'){
                            $display_table.="<td><input name='check_value' type='checkbox' value='".$result_query['Requizition_ID']."'></td>";
                        }else{
				$display_table.="<td><a href='requisition_report.php?requision_id={$result_query['Requizition_ID']}' class='art-button-green' target='_blank' >Print Preview</a></td>";
			}
				$display_table.="</tr>";
			}
			
			$display_table.="</table>";
			
			echo $display_table;
if(isset($_GET['lForm'])){
if($_GET['lForm']=='saveData'){?>
<input name='submit' type='submit' value="SELECT" style="padding:2px;font-size:16px;width:20%;float:right;">
<?php }
}

if(isset($_GET['action'])){
if($_GET['action']=='edited'){
        ?>
<script type="text/javascript">
            alert("Item(s) Edited Successfully");

</script>
     <?php   
    }
}

?>
</form>	
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