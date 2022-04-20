<?php
    session_start();
    unset($_SESSION['NHIF_Member']);
    $Member_Number = $_GET['Member_Number'];
    $_SESSION['NHIF_Member']['Member_Number'] = $Member_Number;
    
    $reply = @file("http://41.59.13.149/NHIFService/breeze/Verification/GetCard?CardNo=$Member_Number");
    
    if($reply){
    //$response = explode(',',$reply[0]);
    $responce = str_replace(':','=>',$reply[0]);
    $responce = str_replace('"',"",$responce);
    //$result = explode(':',$response[15])[1];
    //$result = trim($result,'"');
    //    if(strtolower($result)=='active'){
    //        $_SESSION['NHIF_Member']['Status'] = 'Active';
    //    }elseif(strtolower($result)=='inactive'){
    //        $_SESSION['NHIF_Member']['Status'] = 'Inactive';
    //    }
    //echo strtolower($result);
    $responce = str_replace('{','',$responce);
    $responce = str_replace('}','',$responce);
    
    $mstr = explode(",",$responce);
    $res_array = array();
    
        foreach($mstr as $nstr=>$value)
        { 
            $value = explode("=>",$value);
            @$res_array[$value[0]] = $value[1];
        }
    }
    $now = new DateTime(date("Y-m-d"));
    $dob = new DateTime(date("Y-m-d",strtotime(substr($res_array["DateOfBirth"],0,10))));
    $diff = $now->diff($dob);
    $age = $diff->y." Years, ";
    $age.= $diff->m." Months. ";
    //$age.= $diff->d." Days. ";
?>
<?php
    header("Content-type: text/xml");
    echo "<?xml version='1.0' encoding='UTF-8'?>
        <CardDetails>
        <CardNo>".$res_array["CardNo"]."</CardNo>
        <MembershipNo>".$res_array["MembershipNo"]."</MembershipNo>
        <FullName>".$res_array["FullName"]." </FullName>
        <PFNumber>".$res_array["PFNumber"]." </PFNumber>
        <Gender>".$res_array["Gender"]."</Gender>
        <DateOfBirth>".substr($res_array["DateOfBirth"],0,10)."</DateOfBirth>
        <Age>".$age."</Age>
        <CHNationalID>".$res_array["CHNationalID"]."</CHNationalID>
        <ExpiryDate>".substr($res_array["ExpiryDate"],0,10)."</ExpiryDate>
        <CardStatusID>".$res_array["CardStatusID"]."</CardStatusID>
        <CardStatus>".$res_array["CardStatus"]."</CardStatus>
        <StatusDescription>".$res_array["StatusDescription"]."</StatusDescription>
        <IsActive>".$res_array["IsActive"]."</IsActive>
        <LatestContribution>".$res_array["LatestContribution"]."</LatestContribution>
        <AuthorizationStatus>".$res_array["AuthorizationStatus"]."</AuthorizationStatus>
        <AuthorizationNo>".$res_array["AuthorizationNo"]."</AuthorizationNo>
        <Remarks>".$res_array["Remarks"]."</Remarks>
    </CardDetails>";
?>