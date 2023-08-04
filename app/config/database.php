<?php

/**
 * Returns an associative array with configuration settings for the database connection.
 *
 * This code defines various configuration settings used for connecting to the database, including the host address,
 * username, password, database name, and port number. These settings are returned as an associative array.
 *
 * @return array An associative array containing the configuration settings for the database connection.
 */
return array(
    "DATABASE_TYPE" => "mysql", // Select SQL driver (mysql, pgsql, cockroachdb)
    "DATABASE_HOST" => "localhost", // The host address for the database server.
    "DATABASE_USER" => "root", // The username for the database user.
    "DATABASE_PASS" => "", // The password for the database user.
    "DATABASE_NAME" => "opencad", // The name of the database to connect to.
    "DATABASE_PORT" => "3306", // The port number for the database connection.
);
