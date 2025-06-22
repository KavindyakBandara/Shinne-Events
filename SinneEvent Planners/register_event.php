<?php
// Database connection (update with your credentials)
$host = 'localhost';
$db = 'event_db';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// File upload handler
function uploadFile($fileField, $targetDir) {
    if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == 0) {
        $targetFile = $targetDir . basename($_FILES[$fileField]['name']);
        move_uploaded_file($_FILES[$fileField]['tmp_name'], $targetFile);
        return $targetFile;
    }
    return null;
}

$eventID = $_POST['eventID'];
$eventName = $_POST['eventName'];
$eventType = $_POST['eventType'];
$clientName = $_POST['clientName'];
$clientEmail = $_POST['clientEmail'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$venueName = $_POST['venueName'];
$venueContact = $_POST['venueContact'];
$venuePhone = $_POST['venuePhone'];
$venueAddress = $_POST['venueAddress'];
$expectedGuests = $_POST['expectedGuests'];
$budget = $_POST['budget'];
$theme = $_POST['theme'];
$eventDescription = $_POST['eventDescription'];
$caterer = $_POST['caterer'];
$decorator = $_POST['decorator'];
$entertainment = $_POST['entertainment'];
$photographer = $_POST['photographer'];
$videographer = $_POST['videographer'];
$eventStatus = $_POST['eventStatus'];
$eventManager = $_POST['eventManager'];
$priorityLevel = $_POST['priorityLevel'];
$adminNotes = $_POST['adminNotes'];

// Handle file uploads
$contractFile = uploadFile('contractFile', 'uploads/');
$floorPlan = uploadFile('floorPlan', 'uploads/');
$eventImage = uploadFile('eventImage', 'uploads/');

// Agenda
$agendaTimes = $_POST['agendaTime'];
$agendaItems = $_POST['agendaItem'];
$agenda = "";
for ($i = 0; $i < count($agendaTimes); $i++) {
    $agenda .= $agendaTimes[$i] . " - " . $agendaItems[$i] . "\n";
}

// SQL Insert
$sql = "INSERT INTO events (
    eventID, eventName, eventType, clientName, clientEmail,
    startDate, endDate, venueName, venueContact, venuePhone,
    venueAddress, expectedGuests, budget, theme, eventDescription,
    caterer, decorator, entertainment, photographer, videographer,
    eventStatus, eventManager, priorityLevel, adminNotes,
    contractFile, floorPlan, eventImage, agenda
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssidssssssssssssss",
    $eventID, $eventName, $eventType, $clientName, $clientEmail,
    $startDate, $endDate, $venueName, $venueContact, $venuePhone,
    $venueAddress, $expectedGuests, $budget, $theme, $eventDescription,
    $caterer, $decorator, $entertainment, $photographer, $videographer,
    $eventStatus, $eventManager, $priorityLevel, $adminNotes,
    $contractFile, $floorPlan, $eventImage, $agenda
);

if ($stmt->execute()) {
    echo "Event registered successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
