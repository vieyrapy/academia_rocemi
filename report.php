<?php
include "php/dbconnect.php";
include "php/checklogin.php";

?>


<?php
include "php/header.php";
?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Reportes</h1>
                    </div>
                </div>






<div class="row" style="margin-bottom:20px;">
<div class="col-md-12">
<fieldset class="scheduler-border" >
    <legend  class="scheduler-border">Buscar:</legend>
<form class="form-inline" role="form" id="searchform">
  <div class="form-group">
    <label for="email">Nombre</label>
    <input type="text" class="form-control" id="student" name="student">
  </div>

   <div class="form-group">
    <label for="email"> Fecha </label>
    <input type="text" class="form-control" id="doj" name="doj" >
  </div>

  <div class="form-group">
    <label for="email"> Curso </label>
    <select  class="form-control" id="branch" name="branch" >
    <option value="" >Seleccionar curso</option>
        <?php
            $sql = "select * from branch where delete_status='0' order by branch.branch asc";
            $q   = $conn->query($sql);

            while ($r = $q->fetch_assoc()) {
                echo '<option value="' . $r['id'] . '"  ' . (($branch == $r['id']) ? 'selected="selected"' : '') . '>' . $r['branch'] . '</option>';
            }
        ?>
  </select>
  </div>

   <button type="button" class="btn btn-success btn-sm" id="find" > Encontrar </button>
  <button type="reset" class="btn btn-danger btn-sm" id="clear" > Limpiar </button>
</form>
</fieldset>

</div>
</div>

<script type="text/javascript">
$(document).ready( function() {

/*
$('#doj').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }
    });

*/

/******************/
   $("#doj").datepicker({

        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).val($.datepicker.formatDate('MM yy', new Date(year, month, 1)));
        }
    });

    $("#doj").focus(function () {
        $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

/*****************/

$('#student').autocomplete({
            source: function( request, response ) {
              $.ajax({
                url : 'ajx.php',
                dataType: "json",
            data: {
               name_startsWith: request.term,
               type: 'report'
            },
             success: function( data ) {

               response( $.map( data, function( item ) {

                return {
                  label: item,
                  value: item
                }
              }));
            }



              });
            }
        /*,
            autoFocus: true,
            minLength: 0,
                 select: function( event, ui ) {
              var abc = ui.item.label.split("-");
              //alert(abc[0]);
               $("#student").val(abc[0]);
               return false;

              },
                 */



          });


$('#find').click(function () {
mydatatable();
        });


$('#clear').click(function () {

$('#searchform')[0].reset();
mydatatable();
        });

function mydatatable()
{

              $("#subjectresult").html('<table class="table table-striped table-bordered table-hover" id="tSortable22"><thead><tr><th>Name/Contact</th><th>Costo</th><th>Saldo</th><th>Curso</th><th>Fecha</th><th>Acción</th></tr></thead><tbody></tbody></table>');

          $("#tSortable22").dataTable({
                    'sPaginationType' : 'full_numbers',
                   "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": false,
                     'bProcessing' : true,
                     'bServerSide': true,
                     'sAjaxSource': "datatable.php?"+$('#searchform').serialize()+"&type=report",
                     'aoColumnDefs': [{
                                   'bSortable': false,
                                   'aTargets': [-1] /* 1st one, start by the right */
                                                }]
                                   });


}

////////////////////////////
 $("#tSortable22").dataTable({

                  'sPaginationType' : 'full_numbers',
          "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": false,

                  'bProcessing' : true,
          'bServerSide': true,
                  'sAjaxSource': "datatable.php?type=report",

            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1] /* 1st one, start by the right */
              }]
            });

///////////////////////////



});


function GetFeeForm(sid)
{

$.ajax({
            type: 'post',
            url: 'getfeeform.php',
            data: {student:sid,req:'2'},
            success: function (data) {
              $('#formcontent').html(data);
        $("#myModal").modal({backdrop: "static"});
            }
          });


}

</script>




<style>
#doj .ui-datepicker-calendar
{
display:none;
}

</style>

    <div class="panel panel-default">
                        <div class="panel-heading">
                            Administrar pagos
                        </div>
                        <div class="panel-body">
                            <div class="table-sorting table-responsive" id="subjectresult">
                                <table class="table table-striped table-bordered table-hover" id="tSortable22">
                                    <thead>
                                        <tr>
                                            <th>Name/Telef.</th>
                                            <th>Costo</th>
                                            <th>Saldo</th>
                                            <th>Curso</th>
                                            <th>Fecha Inscripción</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


  <!-------->
  <style type="text/css">.modal-dialog {
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
}
.modal-content {
    height: 100%;
    min-height: 100%;
    height: auto;
    border-radius: 0;
}</style>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Reporte de pagos</h4>
        </div>
        <div class="modal-body" id="formcontent">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


    <!--------->


            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->

    <?php
include "footer.php";
?>

