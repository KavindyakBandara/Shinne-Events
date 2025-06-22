<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration
require_once 'config.php';

// Initialize variables
$errors = [];
$success = false;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $firstName = $conn->real_escape_string(trim($_POST['firstName']));
    $lastName = $conn->real_escape_string(trim($_POST['lastName']));
    $nic = $conn->real_escape_string(trim($_POST['nic']));
    $gender = $conn->real_escape_string(trim($_POST['gender']));
    $dob = $conn->real_escape_string(trim($_POST['dateOfBirth']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $role = $conn->real_escape_string(trim($_POST['role']));
    $employeeId = $conn->real_escape_string(trim($_POST['employeeId']));
    $position = $conn->real_escape_string(trim($_POST['position']));
    $department = isset($_POST['department']) ? $conn->real_escape_string(trim($_POST['department'])) : '';
    $hireDate = $conn->real_escape_string(trim($_POST['hireDate']));
    $salary = isset($_POST['salary']) ? $conn->real_escape_string(trim($_POST['salary'])) : 0;
    $employmentType = $conn->real_escape_string(trim($_POST['employmentType']));
    $supervisor = isset($_POST['supervisor']) ? $conn->real_escape_string(trim($_POST['supervisor'])) : '';
    $username = $conn->real_escape_string(trim($_POST['username']));
    $password = password_hash($conn->real_escape_string(trim($_POST['password'])), PASSWORD_DEFAULT);
    $status = isset($_POST['status']) ? 1 : 0;
    $accessLevel = $conn->real_escape_string(trim($_POST['accessLevel']));
    
    // Handle file uploads
    $profilePhoto = '';
    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePhoto']['tmp_name'];
        $fileName = $_FILES['profilePhoto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Sanitize file name and generate unique name
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = UPLOAD_DIR . $newFileName;
        
        // Check if image file is an actual image
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $allowedExtensions)) {
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePhoto = $newFileName;
            } else {
                $errors[] = 'There was an error uploading the profile photo.';
            }
        } else {
            $errors[] = 'Only JPG, JPEG, and PNG files are allowed for profile photos.';
        }
    }

    // Validate required fields
    $requiredFields = [
        'firstName' => 'First name',
        'lastName' => 'Last name',
        'nic' => 'NIC',
        'gender' => 'Gender',
        'dateOfBirth' => 'Date of birth',
        'phone' => 'Phone',
        'email' => 'Email',
        'address' => 'Address',
        'role' => 'Role',
        'employeeId' => 'Employee ID',
        'position' => 'Position',
        'hireDate' => 'Hire date',
        'username' => 'Username',
        'password' => 'Password'
    ];
    
    foreach ($requiredFields as $field => $name) {
        if (empty($_POST[$field])) {
            $errors[] = "$name is required.";
        }
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $sql = "INSERT INTO staff (
            first_name, last_name, nic, gender, dob, contact_number, 
            email, address, profile_photo, role, employee_id, position,
            department, hire_date, salary, employment_type, supervisor,
            username, password, status, access_level
        ) VALUES (
            '$firstName', '$lastName', '$nic', '$gender', '$dob', '$phone',
            '$email', '$address', '$profilePhoto', '$role', '$employeeId', '$position',
            '$department', '$hireDate', '$salary', '$employmentType', '$supervisor',
            '$username', '$password', '$status', '$accessLevel'
        )";
        
        if ($conn->query($sql)) {
            $success = true;
            $last_id = $conn->insert_id;
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

// Close connection
$conn->close();

// Redirect or display result
if ($success) {
    header("Location: registration_success.php?id=$last_id");
    exit();
} else {
    session_start();
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: staff_registration.html");
    exit();
}
?>