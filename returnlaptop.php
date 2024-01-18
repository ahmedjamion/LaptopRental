<?php
// Process delete operation after confirmation
if (isset($_POST["rental_id"]) && !empty($_POST["rental_id"])) {
    // Include config file
    require_once "config.php";

    // Prepare a delete statement
    $sql = "UPDATE rental
            SET is_returned = true
            WHERE rental_id = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_rental_id);

        // Set parameters
        $param_rental_id = trim($_POST["rental_id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: rental.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["rental_id"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Return Laptop?</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-success">
                            <input type="hidden" name="rental_id" value="<?php echo trim($_GET["rental_id"]); ?>" />
                            <p>Thank you for renting, have a nice day.</p>
                            <p>
                                <input type="submit" value="Return" class="btn btn-success">
                                <a href="rental.php" class="btn btn-secondary ml-2">Back</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>