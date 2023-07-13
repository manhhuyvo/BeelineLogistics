<?php
namespace App\Enums;

class ResponseMessageEnum
{
    const FAILED_VALIDATE_INPUT = "Invalid input values. Please try again.";
    const SUCCESS_ADD_NEW_RECORD = "Successfully created new record.";
    const FAILED_ADD_NEW_RECORD = "Some errors occurred. Failed to create new record.";   
    const FAILED_SHOW_RECORD = "Unable to find record with provided details."; 
    const SUCCESS_UPDATE_RECORD = "Successfully updated records.";
    const FAILED_UPDATE_RECORD = "Some errors occurred. Failed to update records.";   
    const SUCCESS_DELETE_RECORD = "Successfully deleted record.";
    const FAILED_DELETE_RECORD = "Some errors occurred. Failed to deleted records.";   

    /** Unknown error */
    const UNKNOWN_ERROR = "Some errors occurred. Please try again later.";

    /** Auth Errors */
    const LOGIN_REQUIRED = "You are not logged in. Please login to your account.";
    const LOGOUT_MESSAAGE = "You have been logged out.";
    const INVALID_ACCESS = "You don't have permission to access this page.";
    const WRONG_CREDENTIALS = "Your username and password does not match our records or yoru account is not active. Please try again";

    /** User Profile */
    const CONRIM_PASSWORD_NOT_MATCH = "Password and confirm password do not match. Please try again.";
}