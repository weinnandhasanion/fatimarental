<?php
include_once './redirect.php';
include "./../../services/connect.php";

$month = date("n");
$previousMonth = date("n", strtotime(" -1 month"));
$sql = "SELECT SUM(amount) AS monthTotal FROM payments WHERE MONTH(date_added) = $month";
$res = $conn->query($sql);
$monthTotal = $res->fetch_assoc()['monthTotal'] ?? 0;
$sql = "SELECT SUM(amount) AS monthTotal FROM payments WHERE MONTH(date_added) = $previousMonth";
$res = $conn->query($sql);
$previousMonthTotal = $res->fetch_assoc()['monthTotal'] ?? 0;
$monthDifferential = 0;

if ($monthTotal > 0 || $previousMonthTotal > 0) {
    $monthDifferential = ceil((1 - $previousMonthTotal / $monthTotal) * 100);
}

$year = date("Y");
$previousYear = date("Y", strtotime(" -1 year"));
$sql = "SELECT SUM(amount) AS yearTotal FROM payments WHERE YEAR(date_added) = $year";
$res = $conn->query($sql);
$yearTotal = $res->fetch_assoc()['yearTotal'] ?? 0;
$sql = "SELECT SUM(amount) AS yearTotal FROM payments WHERE YEAR(date_added) = $previousYear";
$res = $conn->query($sql);
$previousYearTotal = $res->fetch_assoc()['yearTotal'] ?? 0;
$yearDifferential = 0;

if ($yearTotal > 0 || $previousYearTotal > 0) {
    $yearDifferential = ceil((1 - $previousYearTotal / $yearTotal) * 100);
}

$getPercentageClass = function ($kind) use ($monthDifferential, $yearDifferential) {
    if ($kind === 'year') {
        if ($yearDifferential === 0) {
            return "text-warning";
        } else if ($yearDifferential > 0) {
            return "text-success";
        } else {
            return "text-danger";
        }
    }

    if ($monthDifferential === 0) {
        return "text-warning";
    } else if ($monthDifferential > 0) {
        return "text-success";
    } else {
        return "text-danger";
    }
}
?>
<!doctype html>
<html lang="en">

<?php include './../templates/head.php'?>

<body>
  <div class="wrapper">
    <div class="body-overlay"></div>

    <?php include './../templates/nav.php'?>

    <!-- Page Content  -->
    <div id="content">

      <?php include './../templates/topnav.php'?>

      <div class="main-content">
        <div class="w3-container">
          <div class="card row flex-row position-relative">
            <div class="col-sm-6">
              <div class="card-body text-center p-5" style="height: 325px">
                <h1 class=" font-weight-light">Php <?=$monthTotal?>.00</h1>
                <p class="text-secondary">Total sales for the month of July</p>
                <p class="mt-3 lead"><strong class="<?=$getPercentageClass('month')?>"><?=$monthDifferential?>% differential</strong> from last month</p>
                <p class="text-secondary"><?=date("F", strtotime(" -1 month"))?> sales: <strong>P<?=$previousMonthTotal?>.00</strong></p>
              </div>
            </div>
            <div class="bg-light col-sm-6">
              <div class="card-body text-center p-5">
                <h1 class="font-weight-light">Php <?=$yearTotal?>.00</h1>
                <p class="text-secondary">Total sales for the current year</p>
                <p class="mt-3 lead"><strong class="<?=$getPercentageClass('year')?>"><?=$yearDifferential?>% differential</strong> from last year</p>
                <p class="text-secondary"><?=date("Y", strtotime(" -1 year"))?> sales: <strong>P<?=$previousYearTotal?>.00</strong></p>
              </div>
            </div>

            <div class="position-absolute w-100 d-flex justify-content-center mb-4" style="bottom: 0">
              <button class="btn btn-primary btn-lg mb-3 d-flex align-items-center" data-toggle="modal"
                data-target="#generate-report-modal" style="gap: 5px">
                <i class="material-icons">receipt</i> Generate Income Report
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="generate-report-modal" tabindex="-1" class="modal fade" role="dialog">
      <div class="modal-dialog" role='document'>
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Generate Income Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <label for="report-type">Time Period</label>
                <select name="report-type" id="report-type" class="form-control">
                  <option value="yearly">Current year</option>
                  <option value="previous">Previous year</option>
                  <option value="alltime">All Time</option>
                  <option value="custom">Custom</option>
                </select>
              </div>
            </div>
            <div class="row my-3" id="custom-fields" style="display: none">
              <div class="col-sm-6">
                <label for="from-date">From Date</label>
                <input id="from-date" type="month" class="form-control">
                <small class="text-danger error-message" id="from-date-error"></small>
              </div>
              <div class="col-sm-6">
                <label for="to-date">To Date</label>
                <input id="to-date" type="month" class="form-control">
                <small class="text-danger error-message" id="to-date-error"></small>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" id="print-report">Print</button>
          </div>
        </div>
      </div>
    </div>

    <?php include './../templates/scripts.php'?>

    <script type="text/javascript">
    $(document).ready(function() {
      $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#content').toggleClass('active');
      });

      $('.more-button,.body-overlay').on('click', function() {
        $('#sidebar,.body-overlay').toggleClass('show-nav');
      });

      $('.sidebar-link').click(function(e) {
        if ($(this).hasClass('active')) {
          e.preventDefault();
          return;
        }
        $('.sidebar-link').removeClass('active');
        $(this).addClass('active');
      });

      const reportType = $('#report-type');
      const printBtn = $('#print-report');

      reportType.change(function(e) {
        $('.error-message').text('');

        if (e.currentTarget.value === 'custom') {
          $('#custom-fields').css('display', 'flex');
        } else {
          $('#custom-fields').css('display', 'none');
        }
      })

      printBtn.click(function() {
        const fromDate = $('#from-date').val();
        const toDate = $('#to-date').val();

        const data = {
          type: reportType.val(),
          fromDate,
          toDate
        }

        $.post('./../functions/get_reports.php', data, function(res) {
          $('.error-message').text('');

          const { data, filename, errors } = JSON.parse(res);

          if (errors !== null) {
            for (key in errors) {
              $(`#${key}-error`).text(errors[key]);
            }
            return;
          }

          if (data === null) {
            alert('There are no reports for the selected period.')
            return;
          }

          let csvContent = `data:text/csv;charset=utf-8,${data}`;
          const encodedUri = encodeURI(csvContent);
          const link = document.createElement('a');
          link.setAttribute("href", encodedUri);
          link.setAttribute("download", filename);
          link.style.display = 'none';
          document.body.appendChild(link);
          link.click();

          window.location.reload();
        });
      })

      $('#logout-link').click(function() {
        let x = confirm("Do you want to logout?");
        if (x) {
          $.get("./../../services/logout.php", function(message) {
            alert(message);
            window.location = './../login.php';
          });
        }
      });
    });
    </script>
</body>

</html>