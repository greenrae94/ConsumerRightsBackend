<?php

function validateInput($data)
{
    $errors = [];

    if (empty($data['date']) || !validateDate($data['date'])) {
        $errors[] = "Invalid or missing date";
    }
    if (empty($data['slot']) || !validateValidSlotTime($data['slot']) || !validateSlotAndTimeRange($data['slot'])) {
        $errors[] = "Invalid or missing slot";
    }
    if (empty($data['first_name']) || !validateLength($data['first_name'], 255)) {
        $errors[] = "Invalid or missing first name";
    }
    if (empty($data['last_name']) || !validateLength($data['last_name'], 255)) {
        $errors[] = "Invalid or missing last name";
    }
    if (empty($data['email']) || !validateEmail($data['email'])) {
        $errors[] = "Invalid or missing email";
    }
    if (empty($data['contact_number']) || !validateContactNumber($data['contact_number'])) {
        $errors[] = "Invalid or missing contact number";
    }
    if (empty($data['subject']) || !validateLength($data['subject'], 100)) {
        $errors[] = "Invalid or missing subject";
    }
    if (!empty($data['location']) && !validateLength($data['location'], 255)) {
        $errors[] = "Invalid location";
    }
    if (!empty($data['comments']) && !validateLength($data['comments'], 500)) {
        $errors[] = "Invalid comments";
    }

    return $errors;
}