<?php 
    include ("./includes/connection.php");

    include ("./includes/header.php");
    if (!isset($_SESSION['userinfo'])) {
        // @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_SESSION['userinfo'])) {
        if ($_SESSION['userinfo']['Mtuha_Reports'] != 'yes') {
            // @session_destroy();
            header("Location: ../index.php?InvalidPrivilege=yes");
        }
    }

    $tablecolumn = $_GET['tablecolumn'];
    $tables = $_GET['tables'];
    $selectin = $_GET['selectin'];
    $End_Date = $_GET['End_Date'];
    $Start_Date = $_GET['Start_Date'];
    $whereclaus = $_GET['whereclaus'];
    $datastring = $_GET['datastring'];
    $whereclausand = str_replace(",", "AND", $whereclaus );
    $selectinOR = str_replace(",", "OR", $selectin); 
    $dateFilter = $_GET['dateFilter'];
    $column = explode(',', $tablecolumn);

    $select_or = explode(',', $selectin);
    $count_or = count($select_or);
    if($dateFilter=='tc.Consultation_Date_And_Time'){
        $whereclausand.=" AND  pr.Registration_ID=tc.Registration_ID  ";
    }else if($dateFilter=='Admission_Date_Time'){
        $whereclausand.=" AND pr.Registration_ID=ad.Registration_ID AND  DATE(Ward_Round_Date_And_Time)  BETWEEN '$Start_Date' AND '$End_Date' GROUP BY wr.consultation_ID , wr.Registration_ID";
//GROUP BY wr.consultation_ID, ad.Registration_ID
    }
    for($i=0; $i<$count_or; $i++){
        $insidevalue =explode('=', $select_or[$i]);

    }
    
    if($selectinOR !=''){
        $selectinOR =$selectinOR." AND  DATE($dateFilter) BETWEEN '$Start_Date' AND '$End_Date'"; 
    }else{
        $selectinOR =$selectinOR." DATE($dateFilter) BETWEEN '$Start_Date' AND '$End_Date'  ";
    }

    $length = count($column);
    $htm='';
    $sql = mysqli_query($conn, "SELECT $tablecolumn FROM $tables WHERE $whereclausand AND $selectinOR ") or die(mysqli_error($conn));

$htm.= "<table class='table'>
    <tr><th>#</th>"; 
        for($i=0; $i<$length; $i++){
            $str = strpos($column[$i], '.');
            if($str !== false)
            {
                $rwaname = str_replace(substr($column[$i], 0, 3), "", $column[$i]);
                $columnname = str_replace("_", " ", $rwaname);
            } else {
                $columnname=str_replace("_", " ", $column[$i]);
            }
            
            $htm.=  "<td><b>".$columnname."</b></td>";
        }
    $htm.= "</tr>";
        $num=1;
        $column = explode(',', $tablecolumn);
        if(mysqli_num_rows($sql)>0){
            while($rw = mysqli_fetch_assoc($sql)){
                $htm.= "<tr><td>$num</td>";
                for($i=0; $i<$length; $i++){
                    $str = strpos($column[$i], '.');
                    if($str !== false)
                    {
                        $columnnames = str_replace(substr($column[$i], 0, 3), "", $column[$i]);
                    } else {
                        $columnnames= $column[$i];
                    }
                    $htm.= "<td>".$rw[$columnnames]."</td>";
                }
                $htm.= "</tr>";
                $num++;
            }
        }
    
$htm.= "</table>";

// SIMPLE EXCELL FUNCTION STARTS HERE 
header("Content-Type:application/xls");
header("content-Disposition: attachement; filename= rwdata.xls");
echo $htm;