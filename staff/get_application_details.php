<?php
require_once "../includes/authentication.php";
require_once "../classes/application.class.php";

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Application ID is required']);
    exit;
}

$applicationObj = new Application();
$details = $applicationObj->getApplicationDetails($_GET['id']);

if ($details) {
    echo json_encode($details);
} else {
    echo json_encode(['error' => 'Application not found']);
} 