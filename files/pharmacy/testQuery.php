<?php 
    declare(strict_types=1);

    include '../../includes/connection.php';
    include 'Library.php';

    $Crud = new Library($conn);

    # a select query that selects from a single table
    $getAllSuppliers = json_decode($Crud->readDataFromTable(array('tbl_supplier AS ts'),array('*'),array('Supplier_Name != ""','Postal_Address != ""'),3,true,'ts.Supplier_ID'),true);
    # a select query that selects from a multiple table
    $getAllPatients = json_decode($Crud->readDataFromTable(array('tbl_patient_registration as tpr','tbl_sponsor as ts'),array('ts.Guarantor_Name','tpr.Patient_Name'),array('tpr.Sponsor_ID = ts.Sponsor_ID','ts.Sponsor_ID = 12'),8,true,'tpr.Registration_ID'),true);

    print_r($getAllSuppliers);
    echo "<br/><br/>* * * * * * * * * * * * * * * * * * * * * * * * * * * * * *</br><br/>";
    print_r($getAllPatients);
?>