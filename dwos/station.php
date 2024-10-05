<?php
include('dwos.php');

$error = []; // Initialize the error array

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get stations (owners)
$sql = "SELECT user_name, address, phone_number FROM users WHERE user_type = 'O'";
$result = $conn->query($sql);

// Prepare an array to hold the data
$stations = [];

if ($result->num_rows > 0) {
    // Fetch all the station owners
    while ($row = $result->fetch_assoc()) {
        $stations[] = $row;
    }
} else {
    echo "No stations found.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="station.css">
    <title>Water Stations</title>
</head>
<body>
    
     <?php include 'adminnavbar.php'; ?>

    <div class="header">
        <h1>Water Stations</h1>
    </div>
    <div class="station-container">
    <?php if (!empty($stations)) { ?>
        <?php foreach ($stations as $index => $station) { ?>
            <div class="station">
                <span class="station-id"><?php echo $index + 1; ?>.</span> <!-- Ordered numbering -->
                <div>
                    <strong><?php echo isset($station['user_name']) ? $station['user_name'] : 'Unknown'; ?></strong><br>
                    <?php echo isset($station['address']) ? $station['address'] : 'No address'; ?><br>
                    <?php echo isset($station['phone_number']) ? $station['phone_number'] : 'No phone number'; ?>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div>No stations available</div>
    <?php } ?>
</div>

</body>
</html>
