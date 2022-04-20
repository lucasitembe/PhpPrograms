<?php
$retrieve = mysqli_query($conn,"SELECT Hospital_Name,Box_Address,Telephone,Cell_Phone,Fax,Tin FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
         $data=  mysqli_fetch_assoc($retrieve);
         
    $hospital_Name = strtoupper($data['Hospital_Name']);
    $box_Address = $data['Box_Address'];
    $tel_phone = $data['Telephone'];
    $cell_phone =$data['Cell_Phone'];
    $fax = $data['Fax'];
    $tin = $data['Tin'];
    
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
    
    
//    $htm .= "<table width=30% border=0 cellpadding=0 cellspacing=0>
//    <tr>
//	<td colspan=2>
//	    <center>
//		<b>
//		    KINONDONI HOSPITAL<br/> 
//		</b>
//		P.O.Box 35024,<br/> 
//		DAR ES SALAAM.<br/>
//		Kawawa Road<br/>
//		Tel No. : 2760469 <br/>
//	    Cell No: +255 754 562922<br/> 
//	    TIN:	    </center> 
//	</td>
//    </tr>"; 
    
    
   /* 
    $htm .= "<table width=30% border=0 cellpadding=0 cellspacing=0>
    <tr>
	<td colspan=2>
	    <center>
		<b>
		    KAIRUKI HOSPITAL<br/> 
		</b>
		P.O.Box 1234,<br/> 
		DAR ES SALAAM.<br/>
		Old Bagamoyo Road<br/>
		Tel No. : 1234567 <br/>
	    Cell No: +255 123 456 789<br/> 
	    TIN: 123 - 456 - 789   </center> 
	</td>
    </tr>";*/


   
//    $htm .= "<table width=30% border=0 cellpadding=0 cellspacing=0>
//    <tr>
//	<td colspan=2>
//	    <center>
//		<b>
//		    HAFFORD HEALTH CARE<br/>
//		    DISPENSARY<br/>
//		</b>
//		P.O.Box 46246,<br/> 
//		Maduka Mawili.<br/>
//		Temeke, DSM.<br/>
//		Tel No. : 0713-478739 <br/>
//		E-mail: fordchisso@yahoo.com<br/>
//            </center> 
//	</td>
//    </tr>";
//    
//    
    
    ?>