<?php

$acc1 = file_get_contents("http://localhost/Final_One/files/sponsor_invoice_bydate.php");
 $data = json_decode($acc1);

 $length=count( $data);

?>

<script type="text/javascript">
    function  delete_invoice(){
        con = confirm("Are you sure you want to delete this invoice ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
    
</script>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?php echo base_url();?>gledger">General Ledger</a></li>						  	
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Suplier Invoice </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
            <table class="table table-striped table-bordered" id="dataTables-district-3"> 
                        <thead> 
                            <tr> 
                                <th>SN</th> 
                                <th>Sponsor</th> 
                                <th>Invoice No.</th> 
                                <th>Amount</th> 
                                <th>Action</th> 
                               
                            </tr> </thead> 
                        <tbody> 
                             <?php
                            $x =1;
                            if($invoice_List){
                            for ($i=0; $i < $length ; $i++) { 
                           
                                 // $supplier_name =$this->Helper->getSupplierById($dt->supplier_id)->suppliername;
                                echo "<tr>";
                                echo "<td>" . $x++ . "</td>";
                                echo "<td>" . $data[$i]->sponsor. "</td>";
                                echo "<td>" . $data[$i]->number. "</td>";
                                echo "<td>" . $data[$i]->amount."</td>";

                                 echo"<td>
                                  <div class='btn-group-sm'>
                                    
                                            <a class='btn btn-info btn-xs' href='#'><i class='fa fa-eye''></i>Priview</a>
                                        </div>


                                  </td>";
                                echo "</tr>";
                                  
                                echo "</tr>";
                          }
                        }
                            ?>

                
                        </tbody> 
                    </table> 
                    
                   
                </div>

                <!--currency form model-->

                

 <script>
      $(document).ready(function() {
        var handleDataTableButtons = function() {
          if ($("#datatable-buttons").length) {
            $("#datatable-buttons").DataTable({
              dom: "Bfrtip",
              buttons: [
                {
                  extend: "copy",
                  className: "btn-sm"
                },
                {
                  extend: "csv",
                  className: "btn-sm"
                },
                {
                  extend: "excel",
                  className: "btn-sm"
                },
                {
                  extend: "pdfHtml5",
                  className: "btn-sm"
                },
                {
                  extend: "print",
                  className: "btn-sm"
                },
              ],
              responsive: true
            });
          }
        };

        TableManageButtons = function() {
          "use strict";
          return {
            init: function() {
              handleDataTableButtons();
            }
          };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
          keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
          ajax: "js/datatables/json/scroller-demo.json",
          deferRender: true,
          scrollY: 380,
          scrollCollapse: true,
          scroller: true
        });

        $('#datatable-fixed-header').DataTable({
          fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
          'order': [[ 1, 'asc' ]],
          'columnDefs': [
            { orderable: false, targets: [0] }
          ]
        });
        $datatable.on('draw.dt', function() {
          $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green'
          });
        });

        TableManageButtons.init();
      });
       <!-- /Datatables -->