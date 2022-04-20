<?php
        header("Content-Type:application/json");
        require_once('ServiceManager.php');
      
        $manager=new ServiceManager();
        $result="";
	    
        if(isset($_GET['action']) && $_GET['action']=="getCardDetails"){
            //verifying the card using the given card number
            if (isset($_GET['CardNo']) && !empty($_GET['CardNo'])) {
            
                $cardno = mysqli_escape_string($conn,$_GET['CardNo']);
                $result = $manager->GetDetails($cardno);
                echo $result;
            } 
        } else if(isset($_GET['action']) && $_GET['action']=="authenticateCard"){
            //authorizing the card
            if (isset($_GET['CardNo']) && !empty($_GET['CardNo'])) {
                $CardNo = mysqli_escape_string($conn,$_GET['CardNo']);
                $VisitType = mysqli_escape_string($conn,$_GET['VisitTypeID']);
                $referral_no = mysqli_escape_string($conn,$_GET['referral_no']);
                $Remarks = mysqli_real_escape_string($conn, $_GET['Remarks']);
                $ReferralNo="";
                if($VisitType == 2){
                    $VisitTypeID = $VisitType;
                }else if($VisitType == 3 && empty($referral_no)){
                    $VisitTypeID = $VisitType;
                    $ReferralNo = $referral_no;
                }else{
                    $VisitTypeID=1;
                }
            
                $result = $manager->AuthorizeCard($CardNo,$VisitTypeID,$ReferralNo, $Remarks);
                echo $result;
            } 
        }
        else if(isset($_GET['getprice'])){
            echo $result =$manager->GetPricePackage(FacilityCode); 
        }
?>
