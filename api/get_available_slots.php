<?php
include '../includes/db_connection.php';
include '../includes/validation_functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['date'])) {
        $selected_date = sanitizeInput($_POST['date']);

        if (!validateDate($selected_date)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid date format"]);
            exit;
        }

        if (!validateFutureDate($selected_date)) {
            http_response_code(400);
            echo json_encode(["error" => "Selected date must be today or in the future"]);
            exit;
        }

        $available_slots = getAvailableSlots($conn, $selected_date);

        echo json_encode($available_slots);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Date not provided"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

function getAvailableSlots($conn, $selected_date)
{
    $booked_slots = getBookedSlots($conn, $selected_date);

    $start_time = strtotime("08:00");
    $end_time = strtotime("18:00");
    $interval = 30 * 60;
    $current_time = strtotime(date('H:i'));
    $available_slots = [];

    while ($start_time < $end_time) {
        $time_slot = date('H:i', $start_time);
        if (!in_array($time_slot, $booked_slots) && $start_time > $current_time) {
            $available_slots[] = $time_slot;
        }
        $start_time += $interval;
    }

    return $available_slots;
}

function getBookedSlots($conn, $selected_date)
{
    $sql_booked = "SELECT start_time FROM appointments WHERE date = ?";
    $stmt = $conn->prepare($sql_booked);
    $stmt->bind_param("s", $selected_date);
    $stmt->execute();
    $result_booked = $stmt->get_result();
    $booked_slots = [];

    if ($result_booked->num_rows > 0) {
        while ($row = $result_booked->fetch_assoc()) {
            $booked_time = date('H:i', strtotime($row['start_time']));
            $booked_slots[] = $booked_time;
        }
    }

    $stmt->close();

    return $booked_slots;
}
?>
