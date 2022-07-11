<div class="card"
  style="min-height: 485px; margin-top: 0; border: 1px solid rgba(0,0,0,.125) !important; border-top: 0px !important">
  <div class="card-header card-header-text d-flex justify-content-start align-items-center">
    <h4 class="card-title flex-grow-1"><?=$statuses[$record][1]?></h4>
    <button class="btn btn-primary print-btn" id="print_<?= $record ?>">Print a record</button>
  </div>
  <div class="card-content table-responsive">
    <table class="table table-hover" id='reservations-table'>
      <thead class="text-primary">
        <tr>
          <th style="width: 200px">Name</th>
          <th>Payment Reference ID</th>
          <th>Amount</th>
          <th>Date of Payment</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($records as $record) {?>
        <tr>
          <td class="align-middle"><?=$record['first_name']." ".$record['last_name']?></td>
          <td class="align-middle">
            <?=$record['reference_id']?>
          </td>
          <td class="align-middle">P<?=$record['amount']?>.00</td>
          <td class="align-middle"><?=date("F d, Y", strtotime($record['date_added']))?></td>
          <td class="align-middle"><?=$record['remarks']?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>