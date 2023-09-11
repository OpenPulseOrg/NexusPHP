<?php

/**
 * Returns an associative array with configuration settings for the keys file.
 * This code defines various configuration settings used for storing sensitive keys and data, such as encryption keys,
 * nonces, API keys, and any other sensitive information that needs to be securely stored. These settings are returned as an associative array.
 * The keys config file is typically used to centralize and manage all the sensitive keys and data used by an application.
 * It provides a convenient way to store and access these keys in a secure manner, separate from the main codebase. This
 * helps in maintaining good security practices and facilitates easy key rotation if required.
 * 
 * ******************************* WARNING WARNING WARNING ******************************* 
 * ** DO NOT CHANGE ANY OF THE BELOW VALUES. THIS CAN AND WILL SCREW A LOT OF THINGS UP **
 * **       FOR YOU. THIS INCLUDES LOSING ACCESS TO ENCRYPTED DATA, LOGIN TOKENS,       **
 * **                                OAUTH AND 2FA!                                     **
 * **              ALL BELOW KEYS ARE RANDOMLY GENERATED ON FIRST LOAD UP.              **
 * **            PLEASE ONLY EDIT THE BELOW IF YOU KNOW WHAT YOU ARE DOING.             **
 * ******************************* WARNING WARNING WARNING ******************************* 

 * @return array An associative array containing the configuration settings for the keys file.
 * 
 */

return array(
    "CIPHER_KEY" => "your_cipher_key_here",
    "SIGNING_KEY" => "your_signing_key_here",
    "API_KEY" => "your_api_key_here",
    "SECRET_KEY" => "your_secret_key_here",
    "PASSWORD_SALT" => "your_password_salt_here",
    "PRIVATE_KEY_PEM" => "your_private_key_in_pem_format_here",
    "PUBLIC_KEY_PEM" => "your_public_key_in_pem_format_here",
    "API_SECRET" => "your_api_secret_here",
    "REFRESH_TOKEN_KEY" => "your_refresh_token_key_here",
    "ACCESS_TOKEN_KEY" => "your_access_token_key_here",
    "SESSION_KEY" => "your_session_key_here",
    "OAUTH_CONSUMER_KEY" => "your_oauth_consumer_key_here",
    "OAUTH_CONSUMER_SECRET" => "your_oauth_consumer_secret_here",
    "TWO_FACTOR_AUTHENTICATION_KEY" => "your_two_factor_authentication_key_here"
);
