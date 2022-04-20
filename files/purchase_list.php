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
			echo "<a href='purchaseorder.php?status=new' class='art-button-green'>NEW ORDER</a>";
			}
	}
if(!isset($_GET['status'])){
        if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='#' class='art-button-green'>EDIT ORDER</a>";
			}
	}

}
            if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='purchase_list.php?purchase=list&lForm=saveData&page=purchase_list' class='art-button-green'>PROCESS ORDER</a>";
			}
	} 
            if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='purchase_list.php?purchase=list&lForm=sentData&page=purchase_list' class='art-button-green'>PREVIOUS ORDER</a>";
			}
	}
	    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
            { 
            echo "<a onclick='goBack()' class='art-button-green'>BACK</a>";
            }
    }
 
        
If(isset($_GET['purchase']))
		switch($_GET['purchase']){
		case "new":
			$action="purchaseorder.php?fr=list";
                        $whereV="Sent";
		break;
		case "list":
                    if($_GET['lForm']=='sentData'){
                        $action=''; $whereV="Sent";
                                            }
                                            else if($_GET['lForm']=='saveData'){
                                                $action="purchaseorder.php?page=requizition";
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
					<td><b>Order Number<b></td>
					<td><b>Order Date<b></td>
					<td><b>Order Description<b></td>
					<td><b>From<b></td>";
             $display_table.="<td></td>";
			$display_table.="</tr>";
				
		
			$query_data=mysqli_query($conn,"SELECT ord.*,sDep.Sub_Department_Name AS store FROM tbl_purchase_order as ord
									JOIN tbl_sub_department as sDep ON sDep.Sub_Department_ID=ord.Store_Need
									WHERE order_status='$whereV' ORDER BY order_id DESC  ");
			
			
			while($result_query=mysqli_fetch_array($query_data)){
				$display_table.=" 
				<tr> 
					<td>".$result_query['order_id']."</td>
					<td>".$result_query['created_date']."</td>
					<td>".$result_query['order_description']."</td>
					<td>".$result_query['store']."</td>";
                        if(isset($_GET['lForm']))
			if($_GET['lForm']=='saveData'){
                            $display_table.=" <td><input name='check_value' type='checkbox' value='".$result_query['order_id']."'></td>";
                        }else{
                            $display_table.="<td width='8%s'><a href='purchase_report.php?order_id=".$result_query['order_id']."' class='art-button-green' target='_blank'>Print Priview</a></td>";
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
    </script>
     <?php   
    }
}
include('./includes/footer.php');

?>
</form>	
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
			
			
			

