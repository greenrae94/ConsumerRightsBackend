<?php

function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateDate($date)
{
    return preg_match("/^\d{4}-\d{2}-\d{2}$/", $date);
}

function validateFutureDate($selectedDate)
{
    return strtotime($selectedDate) >= strtotime('today');
}

function validateValidSlotTime($selectedSlot)
{
    return strtotime($selectedSlot) !== false;
}

function validateSlotAndTimeRange($slot)
{
    list(, $minutes) = explode(':', $slot);

    if ($minutes != '00' && $minutes != '30') {
        return false;
    }

    $selected_time = strtotime($slot);
    $start_time = strtotime("08:00");
    $end_time = strtotime("18:00");

    return ($selected_time >= $start_time && $selected_time <= $end_time);
}

function validateFutureTime($selectedSlot)
{
    $current_time = date('H:i');
    return strtotime($selectedSlot) > strtotime($current_time);
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 255;
}

function validateContactNumber($contactNumber)
{
    return preg_match("/^[\d\s+()-\.]*$/", $contactNumber);
}

function validateLength($string, $maxLength)
{
    return strlen($string) <= $maxLength;
}

?>
