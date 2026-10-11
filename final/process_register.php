<?php
/**
 * Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON File Storage
 * ------------------------------------------------------------------------------------
 * - Receives submitted registration form data via POST
 * - Sanitizes and validates all inputs server-side
 * - Handles CSRF token validation (Advanced Extension)
 * - Safely saves records to both CSV and JSON formats using file locking (LOCK_EX)
 * - Renders user-friendly success / error messages
 */

// Define data directory and storage paths
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0777, true);
}
$csvFile = $dataDir . '/students.csv';
$jsonFile = $dataDir . '/students.json';

// Initialize response variables
$errors = [];
$success = false;
$submittedData = [];

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. CSRF Token Verification (Advanced Extension)
    $csrfToken = isset($_POST['csrf_token']) ? trim($_POST['csrf_token']) : '';
    if ($csrfToken !== 'sh_csrf_token_register_2026') {
        $errors[] = 'Invalid or expired CSRF security token. Please resubmit the form.';
    }

    // 2. Input Sanitization & Retrieval
    $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8')) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $mobile = isset($_POST['mobile']) ? trim(htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8')) : '';
    $course = isset($_POST['course']) ? trim(htmlspecialchars($_POST['course'], ENT_QUOTES, 'UTF-8')) : '';
    $year = isset($_POST['year']) ? trim($_POST['year']) : '';
    $gender = isset($_POST['gender']) ? trim(htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8')) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $terms = isset($_POST['terms']) ? true : false;

    // 3. Server-Side Validation Checks
    // Name validation: 3 to 30 characters, letters and spaces only
    if (empty($name)) {
        $errors['name'] = 'Full Name is required.';
    } elseif (!preg_match('/^[A-Za-z\s]{3,30}$/', $name)) {
        $errors['name'] = 'Name must be 3-30 letters without digits or special symbols.';
    }

    // Email validation: valid email syntax
    if (empty($email)) {
        $errors['email'] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please provide a valid email address format.';
    }

    // Mobile validation: 10 digits starting with 6-9
    if (empty($mobile)) {
        $errors['mobile'] = 'Mobile number is required.';
    } elseif (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
        $errors['mobile'] = 'Mobile number must be exactly 10 digits starting with 6, 7, 8, or 9.';
    }

    // Course validation
    $allowedCourses = [
        'B.Tech Computer Science',
        'B.Tech Information Technology',
        'BCA',
        'MCA',
        'B.Sc IT'
    ];
    if (empty($course) || !in_array($course, $allowedCourses)) {
        $errors['course'] = 'Please select a valid course from the list.';
    }

    // Year of study validation (1 to 4)
    $yearInt = filter_var($year, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 4]]);
    if ($yearInt === false) {
        $errors['year'] = 'Year of study must be a number between 1 and 4.';
    }

    // Gender validation
    $allowedGenders = ['Male', 'Female', 'Other'];
    if (empty($gender) || !in_array($gender, $allowedGenders)) {
        $errors['gender'] = 'Please select a valid gender option.';
    }

    // Password validation: at least 6 characters
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must contain at least 6 characters.';
    }

    // Confirm password check
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Confirm password does not match password.';
    }

    // Terms & conditions checkbox
    if (!$terms) {
        $errors['terms'] = 'You must accept the Terms & Conditions.';
    }

    // 4. Safe File Storage if No Validation Errors
    if (empty($errors)) {
        $studentId = '25DCS' . str_pad(rand(101, 999), 3, '0', STR_PAD_LEFT);
        $registrationTime = date('Y-m-d H:i:s');

        $record = [
            'id'           => $studentId,
            'name'         => $name,
            'email'        => $email,
            'mobile'       => $mobile,
            'course'       => $course,
            'year'         => (int)$year,
            'gender'       => $gender,
            'registered_at'=> $registrationTime
        ];

        // A. Safe CSV Writing with Exclusive Lock
        $isNewCsv = !file_exists($csvFile) || filesize($csvFile) === 0;
        $fp = fopen($csvFile, 'a');
        if ($fp && flock($fp, LOCK_EX)) {
            if ($isNewCsv) {
                // Write CSV Header
                fputcsv($fp, ['Student ID', 'Full Name', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'Registered At']);
            }
            fputcsv($fp, [
                $record['id'],
                $record['name'],
                $record['email'],
                $record['mobile'],
                $record['course'],
                $record['year'],
                $record['gender'],
                $record['registered_at']
            ]);
            flock($fp, LOCK_UN);
            fclose($fp);
        }

        // B. Safe JSON Writing with Exclusive Lock
        $existingRecords = [];
        if (file_exists($jsonFile) && filesize($jsonFile) > 0) {
            $jsonContent = file_get_contents($jsonFile);
            $decoded = json_decode($jsonContent, true);
            if (is_array($decoded)) {
                $existingRecords = $decoded;
            }
        }
        $existingRecords[] = $record;
        file_put_contents($jsonFile, json_encode($existingRecords, JSON_PRETTY_PRINT), LOCK_EX);

        $success = true;
        $submittedData = $record;
    }
} else {
    $errors[] = 'Invalid request method. Form must be submitted using POST.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Hub Portal - Form Processing (Practical 7)</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Skip link for accessibility -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <header>
    <div class="header">
      <a href="index.html"><img src="logo.jpg" alt="Student Hub Portal Logo" class="logo"></a>
      <strong>Student Hub Portal</strong>
    </div>
    <div class="headerlinks">
      <button onclick="toggleTheme()">🌓 Theme</button>
      <button><a href="login.html">Login</a></button>
      <button><a href="register.html">Register</a></button>
    </div>
  </header>

  <div class="navlinks">
    <button class="hamburger" onclick="toggleMenu()">☰ Menu</button>
    <nav id="nav-menu" align="center">
      <a href="index.html">Home</a>
      <a href="about.html">About</a>
      <a href="events.html">Events</a>
      <a href="dashboard.html">Dashboard</a>
      <a href="faq.html">FAQ</a>
      <a href="contact.html">Contact</a>
      <a href="feedback.html">Feedback</a>
    </nav>
  </div>

  <main id="main-content" style="max-width: 850px; margin: 30px auto; padding: 10px;">
    <?php if ($success): ?>
      <!-- SUCCESS NOTIFICATION BOX -->
      <div class="table1" style="padding: 30px; text-align: center; border-left: 6px solid #10B981;">
        <div style="font-size: 50px; margin-bottom: 10px;">🎉</div>
        <h2 style="color: #10B981; font-size: 26px; margin-bottom: 8px;">Registration Processed Successfully!</h2>
        <p style="color: #CBD5E1; font-size: 15px; margin-bottom: 24px;">
          (Practical 7: Server-side validation passed &bull; Record safely saved to CSV and JSON storage)
        </p>

        <div style="background-color: #141E2E; border-radius: 12px; padding: 20px; text-align: left; max-width: 580px; margin: 0 auto 24px auto;">
          <h3 style="color: #EC9F05; margin-bottom: 12px; font-size: 18px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 6px;">
            Submitted Student Record
          </h3>
          <p><strong>Student ID:</strong> <span style="color: #EC9F05; font-weight: bold;"><?php echo htmlspecialchars($submittedData['id']); ?></span></p>
          <p><strong>Full Name:</strong> <?php echo htmlspecialchars($submittedData['name']); ?></p>
          <p><strong>Email Address:</strong> <?php echo htmlspecialchars($submittedData['email']); ?></p>
          <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($submittedData['mobile']); ?></p>
          <p><strong>Course:</strong> <?php echo htmlspecialchars($submittedData['course']); ?></p>
          <p><strong>Year of Study:</strong> Year <?php echo htmlspecialchars($submittedData['year']); ?></p>
          <p><strong>Gender:</strong> <?php echo htmlspecialchars($submittedData['gender']); ?></p>
          <p><strong>Registration Time:</strong> <?php echo htmlspecialchars($submittedData['registered_at']); ?></p>
        </div>

        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
          <a href="view_records.php" style="text-decoration: none;">
            <button style="padding: 11px 24px; font-size: 15px; background: #6B4EFD;">📂 View All Stored Records</button>
          </a>
          <a href="register.html" style="text-decoration: none;">
            <button style="padding: 11px 24px; font-size: 15px;">➕ Register Another Student</button>
          </a>
          <a href="dashboard.html" style="text-decoration: none;">
            <button style="padding: 11px 24px; font-size: 15px; background: #10B981;">Go to Dashboard &rarr;</button>
          </a>
        </div>
      </div>
    <?php else: ?>
      <!-- ERROR NOTIFICATION BOX -->
      <div class="table1" style="padding: 30px; text-align: center; border-left: 6px solid #EF4444;">
        <div style="font-size: 50px; margin-bottom: 10px;">⚠️</div>
        <h2 style="color: #EF4444; font-size: 26px; margin-bottom: 8px;">Validation Error Occurred</h2>
        <p style="color: #CBD5E1; font-size: 15px; margin-bottom: 20px;">
          The server detected errors in your submitted form. Please review the issues below and correct your input:
        </p>

        <div style="background-color: #141E2E; border-radius: 12px; padding: 20px; text-align: left; max-width: 580px; margin: 0 auto 24px auto;">
          <ul style="color: #FF8A80; line-height: 1.8; margin-left: 20px;">
            <?php foreach ($errors as $field => $err): ?>
              <li><strong><?php echo ucfirst($field); ?>:</strong> <?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <a href="register.html" style="text-decoration: none;">
          <button style="padding: 11px 26px; font-size: 15px;">&larr; Back to Registration Form</button>
        </a>
      </div>
    <?php endif; ?>
  </main>

  <div class="footer">
    <footer align="center">
      <p>
        <a href="about.html">About</a> &bull;
        <a href="contact.html">Contact</a> &bull;
        <a href="feedback.html">Feedback</a> &bull;
        Copyright &copy; Student Hub Portal
      </p>
    </footer>
  </div>

  <script src="script.js"></script>
</body>

</html>
