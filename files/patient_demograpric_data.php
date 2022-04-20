<?php 

$select_Patient = mysqli_query($conn,"select
Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
        Gender,pr.Region,pr.Country,pr.Diseased,pr.District,pr.Ward,pr.Patient_Picture,
            Member_Number,Member_Card_Expire_Date,
                pr.Phone_Number,Email_Address,Occupation,
                    Employee_Vote_Number,Emergence_Contact_Name,
                        Emergence_Contact_Number,Company,Registration_ID,
                            Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                            Registration_ID,sp.Postal_Address,sp.Benefit_Limit
          from tbl_patient_registration pr, tbl_sponsor sp
            where pr.Sponsor_ID = sp.Sponsor_ID and 
            Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_Patient);
if ($no > 0) {
while ($row = mysqli_fetch_array($select_Patient)) {
$Registration_ID = $row['Registration_ID'];
$Old_Registration_Number = $row['Old_Registration_Number'];
$Title = $row['Title'];
$Patient_Name = $row['Patient_Name'];
$Sponsor_ID = $row['Sponsor_ID'];
$Date_Of_Birth = $row['Date_Of_Birth'];
$Gender = $row['Gender'];
$Country = $row['Country'];
$Patient_Picture = $row['Patient_Picture'];
$Deseased = ucfirst(strtolower($row['Diseased']));
$Sponsor_Postal_Address = $row['Postal_Address'];
$Benefit_Limit = $row['Benefit_Limit'];
$Region = $row['Region'];
$District = $row['District'];
$Ward = $row['Ward'];
$Guarantor_Name = $row['Guarantor_Name'];
$Claim_Number_Status = $row['Claim_Number_Status'];
$Member_Number = $row['Member_Number'];
$Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
$Phone_Number = $row['Phone_Number'];
$Email_Address = $row['Email_Address'];
$Occupation = $row['Occupation'];
$Employee_Vote_Number = $row['Employee_Vote_Number'];
$Emergence_Contact_Name = $row['Emergence_Contact_Name'];
$Emergence_Contact_Number = $row['Emergence_Contact_Number'];
$Company = $row['Company'];
$Employee_ID = $row['Employee_ID'];
$Registration_Date_And_Time = $row['Registration_Date_And_Time'];
// echo $Ward."  ".$District."  ".$Ward; exit;
}

$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
} else {
$Registration_ID = '';
$Old_Registration_Number = '';
$Title = '';
$Patient_Name = '';
$Sponsor_ID = '';
$Date_Of_Birth = '';
$Gender = '';
$Country = '';
$Deseased = '';
$Sponsor_Postal_Address = '';
$Benefit_Limit = '';
$Patient_Picture = '';
$Region = '';
$District = '';
$Ward = '';
$Guarantor_Name = '';
$Claim_Number_Status = '';
$Member_Number = '';
$Member_Card_Expire_Date = ''; 
$Phone_Number = '';
$Email_Address = '';
$Occupation = '';
$Employee_Vote_Number = '';
$Emergence_Contact_Name = '';
$Emergence_Contact_Number = '';
$Company = '';
$Employee_ID = '';
$Registration_Date_And_Time = '';
$age = 0;
}

