<?php
namespace App\Enums;

class ResponseMessageEnum
{
    const FAILED_VALIDATE_INPUT = "Invalid input values. Please try again.";
    const SUCCESS_ADD_NEW_RECORD = "Successfully created new record.";
    const FAILED_ADD_NEW_RECORD = "Some errors occurred. Failed to create new record.";   
    const FAILED_SHOW_RECORD = "Unable to find record with provided details."; 
}