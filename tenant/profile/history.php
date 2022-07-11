<?php 
$rooms = [];
$sql = "SELECT t.end_date, t.date_added, r.room_name 
  FROM tenant_room_history AS t
  INNER JOIN rooms AS r
  ON r.id = t.room_id
  WHERE t.tenant_id = '" . $user['id'] . "'
  ORDER BY t.id ASC";
$res = $conn->query($sql);
if ($res->num_rows > 0) {
  foreach ($res->fetch_all(MYSQLI_ASSOC) as $row) {
    $row['end_date'] = isset($row['end_date']) ? date('F d, Y', strtotime($row['end_date'])) : 'Present';
    $row['period_of_stay'] = date('F d, Y', strtotime($row['date_added']))." - ".$row['end_date'];
    $rooms[] = $row;
  }
}
?>

<table class="table table-striped">
  <thead class="thead-dark"> 
    <tr>
      <th scope="col">Room</th>
      <th scope="col">Period of Stay</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rooms as $room): ?>
    <tr>
      <td><?= $room['room_name'] ?></td>
      <td><?= $room['period_of_stay'] ?></td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>