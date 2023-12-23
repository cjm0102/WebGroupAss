<?php
include 'db_connect.php';

// Retrieve form data
$viewDate = $_POST['view_date'];
$sportType = $_POST['sport_type'];

// Query the database
$sql = "SELECT t.startTime, c.courtNo, u.matricNo
        FROM timeslot t
        CROSS JOIN court c
        LEFT JOIN booking b ON t.slotID = b.slotID AND c.courtID = b.courtID AND b.bookDate = '$viewDate'
        LEFT JOIN users u ON b.userID = u.userID
        WHERE c.sportName = '$sportType'
        ORDER BY c.courtNo, t.startTime";

$result = $conn->query($sql);

if ($result) {
    $startTimes = array();
    $reservationData = array();

    // Collect reservation data
    while ($row = $result->fetch_assoc()) {
        $courtNo = $row['courtNo'];
        $startTime = $row['startTime'];
        $matricNo = empty($row['matricNo']) ? 'Available' : $row['matricNo'];
        $reservationData[$courtNo][$startTime] = $matricNo;
    }

    echo '<table border="1">
            <tr>
                <th>Court</th>';

    // Generate headers with startTime
    $result->data_seek(0); // Reset result pointer

    while ($row = $result->fetch_assoc()) {
        // Display a new header for each unique startTime
        if (!in_array($row['startTime'], $startTimes)) {
            echo '<th>' . $row['startTime'] . '</th>'; // Display startTime as headers
            array_push($startTimes, $row['startTime']);
        }
    }

    echo '</tr>';

    // Generate rows with court numbers and reservations
    for ($i = 1; $i <= 8; $i++) {
        echo '<tr>';
        echo '<td>Court ' . $i . '</td>'; // Display courtNo in the first column

        foreach ($startTimes as $startTime) {
            echo '<td>';
            // Check if a reservation exists for the specific time, court, and date
            if (isset($reservationData[$i][$startTime])) {
                echo $reservationData[$i][$startTime];
            } else {
                echo 'Available';
            }
            echo '</td>';
        }

        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'Error: ' . $conn->error;
}

$conn->close();
?>
