<?php  
$retrieve = mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
         $data=  mysqli_fetch_assoc($retrieve);
         
    $hospital_Name = strtoupper($data['Hospital_Name']);
    $box_Address = $data['Box_Address'];
    $tel_phone = $data['Telephone'];
    $cell_phone =$data['Cell_Phone'];
    $fax = $data['Fax'];
    $tin = $data['Tin'];
    
    $htm='';

	   $htm .= "<table width=30% border=0 cellpadding=0 cellspacing=0>
    <tr>
	<td colspan=2>
	    <center>
		<b>
		    $hospital_Name <br/> 
		</b>
		$box_Address<br/> 
		Tel No. : $tel_phone <br/>
                Cel No. : $cell_phone <br/>
                Cel No. : $fax <br/>    
	    TIN: $tin  </center> 
	</td>
    </tr>";
    ?>