<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/db.php';

$adminPassword = getenv('ADMIN_PASSWORD') ?: '';
if ($adminPassword === '') {
    http_response_code(503);
    exit('ADMIN_PASSWORD must be configured before the overseer dashboard can be used.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredPassword = $_POST['password'] ?? '';
    if (!hash_equals($adminPassword, $enteredPassword)) {
        header('Location: overseer-login.html');
        exit();
    }
    session_regenerate_id(true);
    $_SESSION['overseer_authenticated'] = true;
}

if (empty($_SESSION['overseer_authenticated'])) {
    header('Location: overseer-login.html');
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Overseer Dashboard</title>
  <style>

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, Helvetica, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #6b73ff, #000dff);
      color: #f1f1f1;  
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    header {
      width: 100%;
      background: rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(5px);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      padding: 1.5rem;
      text-align: center;
    }

    header h1 {
      font-size: 1.8rem;
      color: #fff;
    }

    main {
      max-width: 1200px;
      width: 90%;
      background: rgba(255, 255, 255, 0.1);
      margin: 3rem 1rem;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    main h2 {
      font-size: 1.6rem;
      margin-bottom: 1.5rem;
      color: #fff;
    }

    main p {
      font-size: 1rem;
      color: #e5e7ff;
      margin-bottom: 2rem;
    }

    .back-link {
      display: inline-block;
      padding: 0.8rem 1.5rem;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .back-link:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: scale(1.05);
    }

    @media (max-width: 600px) {
      header h1 {
        font-size: 1.5rem;
      }
    }

    details {
      background: rgba(255,255,255,0.08);
      margin-bottom: 1rem;
      border-radius: 8px;
      padding: 0.5rem 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    summary {
      font-size: 1.1rem;
      font-weight: bold;
      color: #fff;
      cursor: pointer;
      outline: none;
    }

    .table-container {
      margin-top: 1rem;
    }

    .table-container {
      overflow-x: auto;
      margin: 2rem 0;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.12);
      padding: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    } 

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 auto;
      font-size: 0.95rem;
      color: #fff;
    }

    table thead {
      background: rgba(0, 0, 0, 0.4);
    }

    table thead th {
      padding: 0.75rem;
      border-bottom: 2px solid rgba(255,255,255,0.2);
      font-weight: bold;
      text-align: left;
    }

    table tbody tr {
      background: rgba(255, 255, 255, 0.05);
      transition: background 0.2s ease;
    }

    table tbody tr:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    table td {
      padding: 0.7rem;
      border-bottom: 1px solid rgba(255,255,255,0.15);
    }

    table thead th:first-child {
      border-top-left-radius: 8px;
    }
    table thead th:last-child {
      border-top-right-radius: 8px;
    }

    table tbody tr:nth-child(even) {
      background: rgba(255, 255, 255, 0.08);
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      color: #e5e7ff;
    }

    label {
      display: flex;
      flex-direction: column;
      font-size: 1rem;
    }

    input, textarea, select {
      margin-top: 0.4rem;
      padding: 0.6rem;
      border: none;
      border-radius: 8px;
      outline: none;
      font-size: 1rem;
      color: #333;
      background: rgba(255, 255, 255, 0.9);
      transition: all 0.3s ease;
    }

    input:focus, textarea:focus, select:focus {
      box-shadow: 0 0 8px rgba(107, 115, 255, 0.7);
      background: #fff;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    button {
      margin-top: 1rem;
      padding: 0.8rem;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      background: #4c51ff;
      color: #fff;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #3943ff;
      transform: scale(1.03);
    }

  </style>
</head>
<body>
  <header>
    <h1>Overseer Dashboard</h1>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="student-form.html">Student Form</a></li>
        <li><a href="tutor-form.html">Tutor Form</a></li>
        <li><a href="handle_tutors.php">Tutors</a></li>
        <li><a href="handle_students.php">Students</a></li>
        <li><a href="handle_matches.php">Matches</a></li>
        <li><a href="payment-form.php">Payment Status Report</a></li>
        <li><a href="faq.html">FAQ</a></li>
      </ul>
    </nav>
  </header>
  <br><br>
    <details>
      <summary>Student Requests</summary>
      <div class="table-container">
        <?php $dashboard_mode = true; include "handle_students.php"; ?>
      </div>
    </details>
    <details>
      <summary>Available Tutors</summary>
      <div class="table-container">
        <?php $dashboard_mode = true; include "handle_tutors.php"; ?>
      </div>
    </details>
  <main>
    <h2>Match Fullfilments</h2>

    <div class="form-section">
      <form method="POST" action="handle_matches.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <label for="request_id">Select Request:</label>
        <select id="request_id" name="request_id" required>
            <?php
            $con = db_connect();
            if ($con) {
                $sql = "SELECT r.request_id, s.first_name, s.last_name, c.course_number
                        FROM request r
                        JOIN student s ON r.student_id = s.student_id
                        JOIN course c ON r.course_id = c.course_id";
                $result = $con->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $id = $row['request_id'];
                    $name = $row['first_name'] . " " . $row['last_name'];
                    $course = $row['course_number'];
                    $label = htmlspecialchars("Request #$id – $name ($course)");
                    echo "<option value='$id'>$label</option>";
                }
            }
            ?>
        </select>

        <label for="tutor_id">Select Tutor:</label>
        <select id="tutor_id" name="tutor_id" required>
            <?php
            if (!$con->connect_error) {
                $sql = "SELECT tutor_id, first_name, last_name FROM tutor";
                $result = $con->query($sql);

                while ($row = $result->fetch_assoc()) {
                    $tid = $row['tutor_id'];
                    $tname = $row['first_name'] . " " . $row['last_name'];
                    echo "<option value='$tid'>" . htmlspecialchars($tname) . "</option>";
                }
            }
            ?>
        </select>
        <label for="match_status">Match Status:</label>
        <select name="match_status" id="match_status" required>
            <option value="Active">Active</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
        </select>

        <label for="payment_status">Payment Status:</label>
        <select name="payment_status" id="payment_status" required>
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
            <option value="Free">Free</option>
            <option value="Pending">Pending</option>
        </select>

        <label for="notes">Additional Notes:</label>
        <textarea name="notes" id="notes" rows="4" placeholder="Any additional notes..."></textarea>


        <button type="submit">Create Match</button>
      </form>
      <br><br><br>
    </div>
    <a href="index.html" class="back-link">Back to Home</a>
</body>
</html>
