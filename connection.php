<?php

$conn = new mysqli('localhost', 'root', '', 'php_final_project');

if ($conn->connect_error) {
    die('Connection Error!' . $conn->connect_error);
}
