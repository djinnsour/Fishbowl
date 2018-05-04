<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Djinnsour">
    <title>Inventory Summary</title>
    <link rel="icon" href="/assets/img/favicon.png" type="image/x-icon">
    <!-- Style Sheets CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://foo.bar.com/assets/2017/css/zstyle.css">
    <link rel="stylesheet" href="/assets/css/main.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.0/css/tableexport.css" rel="stylesheet">


    <!-- End Style Sheets CSS -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
        <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.12.2/xlsx.core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blob-polyfill/2.0.20171115/Blob.min.js"></script>
    <script src="https://fastcdn.org/FileSaver.js/1.1.20151003/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.0/js/tableexport.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.js"></script>
    <script>
      $( function() {
        $( "#fromdate" ).datepicker({
          orientation: 'top',
          dateFormat: 'yy-mm-dd'
        });
      } );

      $( function() {
        $( "#todate" ).datepicker({
          orientation: 'top',
          dateFormat: 'yy-mm-dd'
        });
      } );
    </script>
    <!-- End Scripts -->
<style>
.bottom-three {
  margin-bottom: 8cm;}
.style8 {
  height: 4px;
  margin-bottom:-3px;
  background-color: #6b9ff6;
  width:100%;}
td {
  width: auto;
}
td.min {
  width: 20px;
  white-space: nowrap;
}
</style>
</head>

<body>
    <div class="section-hero uk-background-blend-color-burn uk-background-top-center uk-background-cover uk-section-large1 cta" >
        <nav class="uk-navbar-container uk-margin uk-navbar-transparent uk-light">
            <div class="uk-container">
                <div uk-navbar>
                    <div class="uk-navbar-left">
                        <a class="uk-navbar-item uk-logo uk-text-uppercase" style="font-weight: bold; font-size: 30px" href="">Inventory Summary </br>收发存汇总表</a>
                    </div>
      <!-- Include main menu header from /var/www/html/menuheader.html -->
			<?php include('../../../menuheader.html');?>
                </div>
            </div>
        </nav>

    </div>
    <!-- Part of search form -->
    <div class ="container col-md-12">

                    <!--  <div class="row"> -->
              <!-- <div class="col-md-6"> -->
                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight: bold; font-size: 20px; text-align: center">Report Options  选择选项</div>
                    <div class="panel-body">
                        <div class="row">


                            <form method="POST" action="index.php">
                                <div class="form-group">
    <div class="col-md-1"></div>
    <div class="col-md-2">
    				<label>From Date  第一天</label>
<input type="text" class="form-control" required="required" name="fromdate" id="fromdate"></p>
            </div>
    <div class="col-md-1"></div>
    <div class="col-md-2">
            <label>To Date  最后一天</label>
<input type="text" class="form-control" required="required" name="todate" id="todate"></p>
            </div>
    <div class="col-md-1"></div>
    <div class="col-md-2 checkbox">
</br><label><input class="checkbox" type="checkbox" name="zeroqty" id="zeroqty" value="1">Include Zero QTYs  包括零</label></p>
</div>
                                      </div>
                                      <div class="col-md-2 form-actions" style="text-align: center" style="padding-bottom:25px">
                                      </br><button type="submit" class="btn btn-primary" name="submit" >Search</button>

                                      </div>
                                  </form>
                        </div>  <!--    </div>  ROW -->
                    </div>
                </div>
            <!-- </div> -->
        </div>
    </div>
    <!-- End of search form -->
    <!-- Results -->
    <div class="container  col-md-12" style="padding-left: 50px; padding-right: 50px">

        <!-- <div class="row">  -->
        <!-- This row div make the table only mid screen -->

                <div class="panel panel-default">
                    <div class="panel-heading" style="font-weight: bold; font-size: 20px; text-align: center">Results  结果</div>
                    <div class="panel-body">
                        <div class="row">
    <div class="container">
    <div>
    <?php
    $begindate = $_POST['fromdate'];
    $enddate = $_POST['todate'];
//    echo "<b>Date Range:</b> $daterangemsg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Ship Status:</b> $shipstatusmsg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>RMA Status:</b> $rmastatusmsg<br>";
    echo "<div  class='container col-md-6'><b>Begin Date  第一天 :&nbsp;&nbsp;</b> $begindate</div>";
    echo "<div  class='container col-md-6'><b>End Date 最后一天 :&nbsp;&nbsp;</b> $enddate</div>";
    ?>
</div>
</div>
</div>
</div>
</div>
</div>
    <div class="container  col-md-12" style="padding-left: 20px; padding-right: 20px">
    <?php

    //phpinfo(INFO_VARIABLES);
    $begindate = $_POST['fromdate'];
    $enddate = $_POST['todate'];
    $zeroqty = $_POST['zeroqty'];
     $user       = "username";
     $pass       = "password";
     $db = new PDO( 'mysql:host=sqlserver;dbname=fishbowlmirrordatabase', $user, $pass );
     // Set the query based off of the checkbox -->
     // Set the query based off of the checkbox -->
     if ($zeroqty == 1){$stmt = $db->prepare("call INVSUMTABLEBUILD1('".$begindate."','".$enddate."');");
     } else {
       $stmt = $db->prepare("call INVSUMTABLEBUILD3('".$begindate."','".$enddate."');");
     }
     $stmt->execute();
     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     echo '<table id="reporttable" class="table table-bordered table-striped table-condensed">
         <col>
         <col>
         <col>
         <col>
         <colgroup span="3"></colgroup>
         <colgroup span="3"></colgroup>
         <colgroup span="3"></colgroup>
         <colgroup span="3"></colgroup>
         <thead>
          <tr style="background-color: #00FF7F">
            <th scope="col"></th>
            <th scope="col" style="text-align: center; font-weight: bold">仓库</th>
            <th scope="col" style="text-align: center; font-weight: bold">存货编码</th>
            <th scope="col"style="text-align: center; font-weight: bold; white-space: nowrap;" >主单位</th>
            <th colspan="3" scope="colgroup" style="text-align: center; font-weight: bold">期初结存 Open Balance</th>
            <th colspan="3" scope="colgroup" style="text-align: center; font-weight: bold">本期入库 Stock In</th>
            <th colspan="3" scope="colgroup" style="text-align: center; font-weight: bold">本期出库 Stock Out</th>
            <th colspan="3" scope="colgroup" style="text-align: center; font-weight: bold">期末结存 End Balance</th>
          </tr>
        </thead>
        <tbody>
          <tr style="background-color: #00FF7F">
             <th scope="col" style="text-align: center">ID</th>
             <th scope="col" style="text-align: center">Location</th>
             <th scope="col" style="text-align: center">Part</th>
             <th scope="col" style="text-align: center">UOM</th>
             <th scope="col" style="text-align: center">QTY</th>
             <th scope="col" style="text-align: center">Cost</th>
             <th scope="col" style="text-align: center">Total</th>
             <th scope="col" style="text-align: center">QTY</th>
             <th scope="col" style="text-align: center">Cost</th>
             <th scope="col" style="text-align: center">Total</th>
             <th scope="col" style="text-align: center">QTY</th>
             <th scope="col" style="text-align: center">Cost</th>
             <th scope="col" style="text-align: center">Total</th>
             <th scope="col" style="text-align: center">QTY</th>
             <th scope="col" style="text-align: center">Cost</th>
             <th scope="col" style="text-align: center">Total</th>
           </tr>
         <tbody>';
// Zero out the total and subtotal variables
$openqtytotal = $opencosttotal = $opentotaltotal = $inqtytotal = $incosttotal = $intotaltotal = $outqtytotal = $outcosttotal = $outtotaltotal = $endqtytotal = $endcosttotal = $endtotaltotal = 0;
$sub_grouptotal = $sub_openqtytotal = $sub_opencosttotal = $sub_opentotaltotal = $sub_inqtytotal = $sub_incosttotal = $sub_intotaltotal = $sub_outqtytotal = $sub_outcosttotal = $sub_outtotaltotal = $sub_endqtytotal = $sub_endcosttotal = $sub_endtotaltotal = 0;
$groupflag = 0;
//
// Basically what we are going to do is have a flag that says to zero out the subtotal variables. If the flag is set we zero out.
// If not we continue accumulating the values.  Every time we loop through a row we make sure the LocationId is the same.  If it is, we're still in thead
// same warehouse (Main, JMA, etc.). If not, we need to display the subtotals on the screen, add them to the grand total, and set the flag to tell
// the code to zero out the subtotal variables on the next loop
     foreach ($result as $key => $row)
     {
       if ($groupflag == 1) {
         $sub_grouptotal = $sub_openqtytotal = $sub_opencosttotal = $sub_opentotaltotal = $sub_inqtytotal = $sub_incosttotal = $sub_intotaltotal = $sub_outqtytotal = $sub_outcosttotal = $sub_outtotaltotal = $sub_endqtytotal = $sub_endcosttotal = $sub_endtotaltotal = 0;
         $groupflag = 0;
       }
// Write the column values to the table
     	echo '<tr>
     			<td style="text-align: center">'.$row['LocationId'].'</td>
     			<td >'.$row['Location'].'</td>
     			<td style="max-width: 15%">'.$row['Partnumber'].'</td>
     			<td style="text-align: center">'.$row['UOM'].'</td>
     			<td style="text-align: center; font-weight: bold; background-color: #F0F0F0">'.$row['OpenQty'].'</td>
     			<td style="text-align: center">'.$row['OpenCost'].'</td>
     			<td style="text-align: center">'.$row['OpenTotal'].'</td>
          <td style="text-align: center; font-weight: bold; background-color: #F0F0F0">'.$row['InQty'].'</td>
     			<td style="text-align: center">'.$row['InAvgCost'].'</td>
     			<td style="text-align: center">'.$row['InTotal'].'</td>
          <td style="text-align: center; font-weight: bold; background-color: #F0F0F0">'.$row['OutQty'].'</td>
     			<td style="text-align: center">'.$row['OutAvgCost'].'</td>
     			<td style="text-align: center">'.$row['OutTotal'].'</td>
          <td style="text-align: center; font-weight: bold; background-color: #F0F0F0">'.$row['EndQty'].'</td>
     			<td style="text-align: center">'.$row['EndCost'].'</td>
     			<td style="text-align: center">'.$row['EndTotal'].'</td>
     		</tr>';
// Add the column values to the subtotals
        $openqtytotal += $row['OpenQty'];
        $opencosttotal += $row['OpenCost'];
        $opentotaltotal += $row['OpenTotal'];
        $inqtytotal += $row['InQty'];
        $incosttotal += $row['InAvgCost'];
        $intotaltotal += $row['InTotal'];
        $outqtytotal += $row['OutQty'];
        $outcosttotal += $row['OutAvgCost'];
        $outtotaltotal += $row['OutTotal'];
        $endqtytotal += $row['EndQty'];
        $endcosttotal += $row['EndCost'];
        $endtotaltotal += $row['EndTotal'];
        $sub_openqtytotal += $row['OpenQty'];
        $sub_opencosttotal += $row['OpenCost'];
        $sub_opentotaltotal += $row['OpenTotal'];
        $sub_inqtytotal += $row['InQty'];
        $sub_incosttotal += $row['InAvgCost'];
        $sub_intotaltotal += $row['InTotal'];
        $sub_outqtytotal += $row['OutQty'];
        $sub_outcosttotal += $row['OutAvgCost'];
        $sub_outtotaltotal += $row['OutTotal'];
        $sub_endqtytotal += $row['EndQty'];
        $sub_endcosttotal += $row['EndCost'];
        $sub_endtotaltotal += $row['EndTotal'];
 	      if (@$result[$key+1]['LocationId'] != $row['LocationId']) {
// Set the groupflag to 1 to indicate the group is about to change, write the subtotals to the table
          $groupflag = 1;
          echo '<tr style="background-color: #94F2FF;font-weight: bold">
              <td></td>
     			    <td >'.$row['Location'].'</td>
              <td style="text-align: center; font-weight: bold">期间合计 Subtotal</td>
              <td></td>
              <td style="text-align: center; font-weight: bold">'.$sub_openqtytotal.'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_opencosttotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_opentotaltotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.$sub_inqtytotal.'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_incosttotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_intotaltotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.$sub_outqtytotal.'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_outcosttota,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_outtotaltotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.$sub_endqtytotal.'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_endqtytotal,2, '.', ',').'</td>
              <td style="text-align: center; font-weight: bold">'.number_format($sub_endtotaltotal,2, '.', ',').'</td>
            </tr>';
        }
}
//We have looped through the entire array so display the grand total values
echo '<tr style="background-color: #FCFF8F;font-weight: bold">
    <td></td>
    <td >COMPANYNAME</td>
    <td style="text-align: center; font-weight: bold">合计 Total</td>
    <td></td>
    <td style="text-align: center; font-weight: bold">'.$openqtytotal.'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($opencosttotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($opentotaltotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.$inqtytotal.'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($incosttotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($intotaltotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.$outqtytotal.'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($outcosttotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($outtotaltotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.$endqtytotal.'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($endcosttotal,2, '.', ',').'</td>
    <td style="text-align: center; font-weight: bold">'.number_format($endtotaltotal,2, '.', ',').'</td>
  </tr>';
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
    <!-- End of Results Table -->
    </div>
    </div>
    </div>
    </div>
    </div>
    </body>
    </html>
    <script type="text/javascript">
        var FormatsTable = document.getElementById('reporttable');
        new TableExport(FormatsTable, {
            formats: ['xlsx']
        });
        // **** jQuery **************************
        //    $(FormatsTable).tableExport({
        //        formats: ['xlsx']
        //    });
        // **************************************


        // **** Configuration **************************
        // TableExport.prototype. {{ PROPERTY BELOW }}

    /*        xlsx: {
                defaultClass: 'xlsx',
                    buttonContent: 'Export to xlsx',
                    mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    fileExtension: '.xlsx'
            },*/
        /**
         * XLS (Binary spreadsheet) file extension configuration
         * @memberof TableExport.prototype
            },*/
        /**
         * CSV (Comma Separated Values) file extension configuration
         * @memberof TableExport.prototype
         */
    /*        csv: {
                defaultClass: 'csv',
                    buttonContent: 'Export to csv',
                    separator: ',',
                    mimeType: 'text/csv',
                    fileExtension: '.csv'
            },*/
        /**
         * TXT (Plain Text) file extension configuration
         * @memberof TableExport.prototype
         */
    /*        txt: {
                defaultClass: 'txt',
                    buttonContent: 'Export to txt',
                    separator: '  ',
                    mimeType: 'text/plain',
                    fileExtension: '.txt'
            },*/


    </script>
