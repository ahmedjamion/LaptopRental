<?php
// Check existence of id parameter before processing further
if (isset($_GET["laptop_id"]) && !empty(trim($_GET["laptop_id"]))) {
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $sql = "SELECT * FROM laptop WHERE laptop_id = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_laptop_id);

        // Set parameters
        $param_laptop_id = trim($_GET["laptop_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $brand = $row["brand"];
                $model = $row["model"];
                $rental_fee = $row["rental_fee"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laptop Details</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row d-flex vh-100 justify-content-center align-items-center">
                <div class="col-4 border rounded rounded-3 bg-light p-3">
                    <h1 class="">Laptop Details</h1>
                    <div class="form-group">
                        <label>Brand</label>
                        <p><b><?php echo $row["brand"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <p><b><?php echo $row["model"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Daily Rental Fee</label>
                        <p><b><?php echo $row["rental_fee"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>