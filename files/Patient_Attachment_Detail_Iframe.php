<?php
include("./includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
session_start();
?>

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<link rel="stylesheet" href="style.css" media="screen">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>

<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
</style>

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
<fieldset style='background: white; color:black; height:300px;oveflow-y:scroll;overflow-x:hidden'>
        <table width='100%' border = 0 >  
			<tr><tr><td colspan="5"><hr></td></tr>
		        <td><b>DEPARTMENT</b></td>
                <td><br><b>DESCRIPTION</b>
                </td><td><br><b>ATTACHED BY</b></td>
                <td><br><b>ACTION</b></td>
				<tr><td colspan="5"><hr></td></tr>
            </tr>
</fieldset>
                      <?php
                        $select_attachments = "SELECT * FROM tbl_attachment,tbl_employee WHERE
                        tbl_attachment.Employee_ID = tbl_employee.Employee_ID
                        AND Registration_ID = $Registration_ID";
                        $reslt = mysqli_query($conn,$select_attachments);
			
                        while($r = mysqli_fetch_assoc($reslt)){
                        ?>
                        <tr>
                            <td>
			    <?php echo $r['Attachment_Date'];?>
                            </td>
                            <td>
			    <?php echo $r['Check_In_Type'];?>
                            </td>
                            <td>
                            <?php echo $r['Description'];?>
                            </td>
                            <td>
                            <?php echo $r['Employee_Name']." (".$r['Employee_Type'].")";?>
                            </td>
                            <td>
                            <a class='art-button-green' href="<?php echo "./patient_attachments/".$r['Attachment_Url'];?>" target='_blank'>
                                VIEW
                            </a>
                            </td>
			</tr>
                        <?php }?>
		    </table>
		    </center>