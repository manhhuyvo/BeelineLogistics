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
    const LOGOUT_MESSAGE = "You have been logged out.";
    const INVALID_ACCESS = "You don't have permission to access this page.";
    const WRONG_CREDENTIALS = "Your username and password does not match our records or your account is not active. Please try again";

    /** User Profile */
    const CONRIM_PASSWORD_NOT_MATCH = "Password and confirm password do not match. Please try again.";

    /** Ajax message */
    const AJAX_SUCCESS_FOUND = "Successfully retrieved data.";
    const AJAX_EMPTY_FOUND = "No records found.";

    /** Other customized error messages */
    const INVALID_PRODUCTS_PROVIDED = "There was no valid products provided.";
    const INVALID_ITEMS_PROVIDED = "There was no valid items provided.";
    const FAILED_PRODUCT_PRICING_RETRIEVE = "Some errors occurred. Failed to retrieve product pricing.";
    const FAILED_CUSTOMER_RETRIEVE = "Some errors occurred. Failed to retrieve customer details.";
    const FAILED_CUSTOMER_PRICING_RETRIEVE = "Some errors occurred. Failed to retrieve customer pricing.";
    const FAILED_FULFILLMENT_RETRIEVE = "Some errors occurred. Failed to retrieve fulfillment details";
    const FAILED_ORDER_RETRIEVE = "Some errors occurred. Failed to retrieve order details";

    /** Bulk Actions messages */
    const INVALID_BULK_ACTION = "The action provided is unvalid. Please try again.";
    const SUCCESS_BULK_ACTION = "Successfully completed bulk action for selected records.";
    const FAILED_BULK_ACTION = "Unable to complete bulk action for selected records. Please try again.";

    /** Invoice Messages */
    const MANY_CUSTOMERS_ITEMS = "Some errors occurred. Please only select the records belong to one customer.";
}