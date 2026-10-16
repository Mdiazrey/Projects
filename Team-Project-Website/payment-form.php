<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';
$con = db_connect();

// Get DISTINCT payment statuses
$sql = "SELECT DISTINCT payment_status FROM tutor_match ORDER BY payment_status";
$result = mysqli_query($con, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Status Report</title>

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
      transition: 0.3s ease;
    }
    nav a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    main {
      max-width: 600px;
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
      border-bottom: 1px solid rgba(255, 255, 255, 0.3);
      padding-bottom: 0.4rem;
    }

    label {
      display: block;
      font-size: 1rem;
      margin-bottom: 0.5rem;
      text-align: left;
      color: #e5e7ff;
    }

    select, input[type="submit"] {
      width: 100%;
      padding: 0.7rem;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      margin-top: 0.3rem;
    }

    select {
      background: rgba(255, 255, 255, 0.9);
      color: #333;
    }

    input[type="submit"] {
      margin-top: 1.5rem;
      background: #4c51ff;
      color: #fff;
      cursor: pointer;
      transition: 0.3s ease;
    }

    input[type="submit"]:hover {
      background: #3943ff;
      transform: scale(1.03);
    }
  </style>
</head>

<body>

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
  <h2>Search Payment Status</h2>

  <form action="payment-report.php" method="POST">

      <label for="payment_status">Choose Payment Status:</label>

      <select name="payment_status" id="payment_status" required>
          <option value="">-- Select Status --</option>

          <?php
          while ($row = mysqli_fetch_assoc($result)) {
              $status = htmlspecialchars($row['payment_status']);
              echo "<option value='$status'>$status</option>";
          }
          ?>
      </select>

      <input type="submit" value="Generate Report">

  </form>
</main>

</body>
</html>

<?php mysqli_close($con); ?>
