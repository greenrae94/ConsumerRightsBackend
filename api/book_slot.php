<?php
include '../includes/db_connection.php';
include '../includes/validation_functions.php';
include '../includes/validate_book_data.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = validateInput($_POST);
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input", "details" => $errors]);
        exit;
    }

    try {
        $bookingResult = bookAppointment($_POST, $conn);
        http_response_code(200);
        echo json_encode(["message" => $bookingResult]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Internal Server Error", "details" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method Not Allowed"]);
}

function bookAppointment($data, $conn)
{
    $selected_date = sanitizeInput($data['date']);
    $selected_slot = sanitizeInput($data['slot']);
    $first_name = sanitizeInput($data['first_name']);
    $last_name = sanitizeInput($data['last_name']);
    $email = sanitizeInput($data['email']);
    $contact_number = sanitizeInput($data['contact_number']);
    $subject = sanitizeInput($data['subject']);
    $comments = !empty($data['comments']) ? sanitizeInput($data['comments']) : null;
    $location = !empty($data['location']) ? sanitizeInput($data['location']) : null;

    $check_statement = $conn->prepare("SELECT * FROM appointments WHERE date = ? AND start_time = ?");
    $check_statement->bind_param("ss", $selected_date, $selected_slot);
    $check_statement->execute();
    $check_result = $check_statement->get_result();
    if ($check_result->num_rows > 0) {
        throw new Exception("Slot already booked");
    }
    $check_statement->close();

    $user_statement = $conn->prepare("INSERT INTO users (first_name, last_name, email, contact_number) VALUES (?, ?, ?, ?)");
    $user_statement->bind_param("ssss", $first_name, $last_name, $email, $contact_number);
    $user_statement->execute();
    $user_id = $conn->insert_id;
    $user_statement->close();

    $start_time = $selected_slot;
    $end_time = date('H:i', strtotime($start_time . ' +30 minutes'));
    $appointment_statement = $conn->prepare("INSERT INTO appointments (user_id, date, start_time, end_time, subject, comments, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $appointment_statement->bind_param("issssss", $user_id, $selected_date, $start_time, $end_time, $subject, $comments, $location);
    $appointment_statement->execute();
    $appointment_statement->close();

    return "Booking successful!";
}
