<?php

$conf = parse_ini_file('config.ini');
$con = new mysqli($conf['host'], $conf['username'], $conf['password'], $conf['db']);

$restaurantId = $argv[1];

$sql = "SELECT * FROM orders WHERE restaurantId = " . $restaurantId;
$result = $con->query($sql);

if ($result->num_rows == 0) {
    $con->close();
    return;
}

$sumAmount = 0;
$sumTax = 0;

while ($row = $result->fetch_assoc()) {
    $orderId = $row['id'];
    $totalCost = $row['amount'] * $row['cost'];
    $totalTax = $totalCost / 100 * $row['tax'];

    $sumAmount += $totalCost;
    $sumTax += $totalTax;

    $sql = "INSERT INTO invoice_positions (orderId, totalCost, taxAmount)
            VALUES (" . $orderId . ", " . $totalCost . ", " . $totalTax . ")";

    if ($con->query($sql) !== TRUE) {
        echo "Error: " . $sql . ", " . $con->error;
    }
}

$sql = "INSERT INTO invoice (restaurantId, totalCost, taxAmount)
            VALUES (" . $restaurantId . ", " . $sumAmount . ", " . $sumTax . ")";

if ($con->query($sql) !== TRUE) {
    echo "Error: " . $sql . ", " . $con->error;
}
