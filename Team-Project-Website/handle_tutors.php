<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
$con = db_connect();

// Only insert if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['first_name'])) {
    // Insert for tutor table
    $sql1 = $con->prepare("INSERT INTO tutor (first_name, last_name, email, phone, experience) VALUES (?, ?, ?, ?, ?)");
    $sql1->bind_param("sssss", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone'], $_POST['experience']);
    $sql1->execute();
    $tutor_id = $con->insert_id;
    $sql1->close();

    // Insert for tutor_req table
    $sql2 = $con->prepare("INSERT INTO tutor_req (tutor_id, availability, format, rate_free) VALUES (?, ?, ?, ?)");
    $sql2->bind_param("isss", $tutor_id, $_POST['availability'], $_POST['format'], $_POST['rate_free']);
    $sql2->execute();
    $sql2->close();
}

// Query to display all tutors
$sql = "SELECT t.tutor_id, t.first_name, t.last_name, t.email, t.phone, t.experience,
tr.availability, tr.format, tr.rate_free
FROM tutor AS t
LEFT JOIN tutor_req AS tr 
    ON t.tutor_id = tr.tutor_id
ORDER BY t.tutor_id DESC";

// Run query
$result = mysqli_query($con, $sql);

if (!$result) {
    echo "Query error: " . mysqli_error($con);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tutors</title>
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
      position: sticky;
      top: 0;
      z-index: 10;
    }

    header h1 {
      text-align: center;
      font-size: 1.8rem;
      padding: 1rem;
      color: #fff;
    }

    
    nav ul {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      list-style: none;
      background: rgba(255, 255, 255, 0.05);
    }

    nav li {
      margin: 0.5rem;
    }

    nav a {
      text-decoration: none;
      color: #f1f1f1;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    
    main {
      max-width: 90%;
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
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      padding-bottom: 0.5rem;
    }

    
    table {
      width: 100%;
      border-collapse: collapse;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    th, td {
      padding: 0.8rem 1rem;
      text-align: left;
      color: #e5e7ff;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    th {
      background: rgba(0, 0, 0, 0.3);
      color: #fff;
      text-align: center;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:hover {
      background: rgba(255, 255, 255, 0.08);
      transition: 0.2s;
    }

  
    @media (max-width: 600px) {
      header h1 {
        font-size: 1.5rem;
      }

      main {
        padding: 1.5rem;
      }

      table, th, td {
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>
  <?php if (!isset($dashboard_mode)) { ?>
  <header>
    <h1>Student Tutoring Help</h1>
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

  <main>
    <h2>Available Tutors</h2>
    <?php } ?>
    <table>
      <tr>
        <th>Tutor ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Experience</th>
        <th>Availability</th>
        <th>Format</th>
        <th>Rate</th>
      </tr>

      <?php
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['tutor_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
          echo "<td>" . htmlspecialchars($row['experience']) . "</td>";
          echo "<td>" . htmlspecialchars($row['availability'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['format'] ?? 'N/A') . "</td>";
          echo "<td>" . htmlspecialchars($row['rate_free'] ?? 'N/A') . "</td>";
          echo "</tr>";
      }
      ?>

    </table>
    <?php if (!isset($dashboard_mode)) { ?>
  </main>
</body>
</html>
    <?php } ?>

<?php
mysqli_close($con);
?>
