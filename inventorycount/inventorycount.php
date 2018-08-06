<!DOCTYPE html>
<html lang="en-us" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Djinnsour">
    <title>Inventory Count Report</title>
    <link rel="icon" href="/assets/img/favicon.png" type="image/x-icon">
    <!-- Style Sheets CSS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/assets/css/uikit-main.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <!-- End Style Sheets CSS -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/assets/js/uikit-main.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script> 
    <script>
      $( function() {
        $( "#fromdate" ).datepicker();
      } );

      $( function() {
        $( "#todate" ).datepicker();
      } );
    </script>
    <!-- End Scripts -->
<style>
.bottom-three {
  margin-bottom: 8cm;}
.style8 {
  height: 4px;
  margin-bottom:-3px;
  background-color: #6b9ff6;}
div.dt-buttons {
    float: right;
}
table.dataTable tbody td {
max-width: 575px;
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
}
</style>
</head>
<body>
<body>
    <div class="section-hero uk-background-blend-color-burn uk-background-top-center uk-background-cover uk-section-large1 cta" >
        <nav class="uk-navbar-container uk-margin uk-navbar-transparent uk-light">
            <div class="uk-container">
                <div uk-navbar>
                    <div class="uk-navbar-left">
                        <a class="uk-navbar-item uk-logo uk-text-uppercase" style="font-weight: bold; font-size: 30px" href="">Inventory Count</a>
                    </div>
			<?php include('../../../menuheader.html');?>
                </div>
            </div>
        </nav>

    </div>
<div class ="container"><div class="panel-heading" style="text-align: center; font-size: 200%"><b>Main Office Inventory Count Report</b></div></div>
<div class="container">
<?php
$user       = "username";
$pass       = "password";
$dbhost     = "databaseserver";
$dbname     = "databasename";
$dbport     = "portnumber";
$db = new PDO( 'mysql:host=$dbhost;port=$dbport;dbname=$dbname', $user, $pass );
$sql='select part.num as partNum, SUBSTR(part.description,1,128) as partDescription, CASE WHEN qtyonhand.LOCATIONGROUPID = 1 THEN "Main" WHEN qtyonhand.LOCATIONGROUPID = 3 THEN "RMA" END AS Location, ROUND(qtyonhand.QTY,0) as QTY, "" as Counted from qtyonhand left join (select id, num, description from part) part on part.id=qtyonhand.PARTID where qtyonhand.LOCATIONGROUPID IN (1,3) order by qtyonhand.LOCATIONGROUPID, part.num';
$stmt = $db->prepare($sql);
$stmt->execute();
echo '<table id="countreport" class="table table-bordered table-striped table-condensed table-responsive">
	<caption>&nbsp;&nbsp;Date:&nbsp;&nbsp;&nbsp;'. date("Y/m/d") . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Note:&nbsp;&nbsp; Report lists only inventory in the MAIN and RMA locations</caption>
	<thead>
		<tr>
			<td><b>Part Number</b></td>
			<td><b>Part Description</b></td>
			<td><b>Location</b></td>
			<td><b>QTY</b></td>
			<td><b>Count</b></td>
		</tr>
	</thead>
	<tbody>';
$total = 0;
while ($row = $stmt->fetch())
{
	echo '<tr>
			<td>' . $row['partNum'] . '</td>
			<td>' . $row['partDescription'] . '</td>
			<td>' . $row['Location'] . '</td>
			<td>' . $row['QTY'] . '</td>
			<td>' . $row['Counted'] . '</td>
		</tr>';
}
echo '</tbody>
</table>';

                            ?>
                                <br/>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- End Pivot Table -->
<br/>
<br/>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
$(document).ready( function () {
    $('#countreport').DataTable({
    "bLengthChange": false,
    "bPaginate": false,
    "searching": false,
    "bSort" : false,
    dom: '<rBf<t>rip>',
    buttons: [
    'excel','pdf'
    ]
  }); 
} );
</script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- End of Results Table -->
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

</body>
</html>
