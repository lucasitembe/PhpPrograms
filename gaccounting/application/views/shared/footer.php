<input type="hidden" id="crsTokenName" value="<?= $this->security->get_csrf_token_name();?>">
<input type="hidden" id="baseurl" value="<?= base_url();?>">
</div>
<!-- /#wrapper --> 
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jquery-validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.form.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.datetimepicker.full.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/iosOverlay.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/spin.min.js"></script>

<!-- jquery ui script -->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/js/jquery_ui/jquery-ui.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery_ui/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/functions.inc.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/main.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/actions.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/form.validate.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jplot/jquery.jqplot.min.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/jplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jplot/plugins/jqplot.barRenderer.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jplot/plugins/jqplot.highlighter.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jplot/plugins/jqplot.cursor.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/jplot/plugins/jqplot.pointLabels.js"></script>

<script>
  $(document).ready(function () {
    var s1 = [[2002, 112000], [2003, 122000], [2004, 104000], [2005, 99000], [2006, 121000], 
    [2007, 148000], [2008, 114000], [2009, 133000], [2010, 161000], [2011, 173000]];
    var s2 = [[2002, 10200], [2003, 10800], [2004, 11200], [2005, 11800], [2006, 12400], 
    [2007, 12800], [2008, 13200], [2009, 12600], [2010, 13100]];
 
    plot1 = $.jqplot("chart1", [s2, s1], {
        // Turns on animatino for all series in this plot.
        animate: true,
        // Will animate plot on calls to plot1.replot({resetAxes:true})
        animateReplot: true,
        cursor: {
            show: true,
            zoom: true,
            looseZoom: true,
            showTooltip: false
        },
        series:[
            {
                pointLabels: {
                    show: true
                },
                renderer: $.jqplot.BarRenderer,
                showHighlight: false,
                yaxis: 'y2axis',
                rendererOptions: {
                    // Speed up the animation a little bit.
                    // This is a number of milliseconds.  
                    // Default for bar series is 3000.  
                    animation: {
                        speed: 2500
                    },
                    barWidth: 15,
                    barPadding: -15,
                    barMargin: 0,
                    highlightMouseOver: false
                }
            }, 
            {
                rendererOptions: {
                    // speed up the animation a little bit.
                    // This is a number of milliseconds.
                    // Default for a line series is 2500.
                    animation: {
                        speed: 2000
                    }
                }
            }
        ],
        axesDefaults: {
            pad: 0
        },
        axes: {
            // These options will set up the x axis like a category axis.
            xaxis: {
                tickInterval: 1,
                drawMajorGridlines: false,
                drawMinorGridlines: true,
                drawMajorTickMarks: false,
                rendererOptions: {
                tickInset: 0.5,
                minorTicks: 1
            }
            },
            yaxis: {
                tickOptions: {
                    formatString: "$%'d"
                },
                rendererOptions: {
                    forceTickAt0: true
                }
            },
            y2axis: {
                tickOptions: {
                    formatString: "$%'d"
                },
                rendererOptions: {
                    // align the ticks on the y2 axis with the y axis.
                    alignTicks: true,
                    forceTickAt0: true
                }
            }
        },
        highlighter: {
            show: true, 
            showLabel: true, 
            tooltipAxes: 'y',
            sizeAdjust: 7.5 , tooltipLocation : 'ne'
        }
    });
   
});
</script>


<!--used to call ehms users || called on account.php view || changed on 06/02/2019 @mfoy dn -->

<script type="text/javascript">
    $('#rad2').click(function(){
        $("#myModal1").modal({backdrop: false});
        $('#myModal1').modal('show');
    });
</script>

<script type="text/javascript">
    $('#rad1').click(function(){
        $('input[name=ehmsuserid]').val('');
        $('input[name=username]').val('');
        $('input[name=password]').val('');

        $("#myModal1").modal({backdrop: false});
        $('#myModal1').modal('hide');
    });
</script>

<style>
#myModal1 {
top:3%;
left:50%;
outline: none;
}

.radio{
    cursor: pointer;
}
</style>

<!-- Modal -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><b style="color:red;">X</b></button>
        <h4 class="modal-title">eHMS USERS</h4>
      </div>
      <div class="modal-body">

      <?php
          $ehmsdb = $this->load->database('ehms', TRUE); // load ehms db

          $query = $ehmsdb->select('tbl_employee.Employee_ID, Employee_Name,Given_Password,Given_Username')
                            ->from('tbl_employee')
                            ->join('tbl_privileges','tbl_privileges.Employee_ID = tbl_employee.Employee_ID','inner')
                            ->order_by('Employee_Name','ASC')
                            ->get();
      ?>

      <div class="row">
        <div class="col-md-7">
            <p> <b>Please, select user to proceed.</b> </p>
        </div>
        <!-- <div class="col-md-5">
            <input type="text" name="searchUser" id="searchUser" onkeyup="search_user(this.value)" class="form-control pull-right" placeholder="Search here">
        </div> -->
      </div> <hr>
        

        <div id="users_list"  style="height:400px;overflow-y: auto;overflow-x: auto;">
            <table class="table table-hover table-responsive table-bordered" id="tableID">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Username</th>
                        <th style="display: none;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($query->result() as $key => $row){?>
                    <tr style="cursor: pointer;" class="tr">
                        <td><?= $key+1 ?></td>
                        <td><?= $row->Employee_Name ?></td>
                        <td><?= $row->Given_Username ?></td>
                        <td style="display: none;"><?= $row->Given_Password ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

function search_user(search_value){
    $.get("<?= base_url(); ?>Account/search_user/"+search_value, function(data){
        $("#users_list").html(data);
    });
}

$(document).ready(function(){

    $(".tr").on('click',function(){
        
     var currentRow=$(this).closest("tr"); 
     
     var col2=currentRow.find("td:eq(2)").text(); 
     var col3=currentRow.find("td:eq(3)").text();
     
     $('input[name=username]').val(col2);
     $('input[name=password]').val(col3);

     $("#myModal1").modal({backdrop: false});
     $('#myModal1').modal('hide');
    
});

$(".tr_ajax").click(function(){
        
        var currentRow=$(this).closest("tr"); 
        
        var col2=currentRow.find("td:eq(2)").text(); 
        var col3=currentRow.find("td:eq(3)").text();
        
        $('input[name=username]').val(col2);
        $('input[name=password]').val(col3);
   
        $("#myModal1").modal({backdrop: false});
        $('#myModal1').modal('hide');
       
   });

});

function select_tr(){

    // var rows = document.getElementById("tableID").rows.length;
    // document.getElementById("tableID").rows[0].cells[0].innerHTML
        // $('input[name=username]').val(col2);
        // $('input[name=password]').val(col3);
   
        // $("#myModal1").modal({backdrop: false});
        // $('#myModal1').modal('hide');
       
   };


</script>

<!-- changed on 06/02/2019 -->


</body>
</html>
