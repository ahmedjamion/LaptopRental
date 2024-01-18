<?php
// Include config file
require_once "config.php";

$date_from = $date_to = "";
$date_from_err = $date_to_err = "";

// Processing form data when form is submitted
if (isset($_POST["laptop_id"]) && !empty($_POST["laptop_id"])) {
    $laptop_id =  trim($_GET["laptop_id"]);
    $user_id = trim($_GET["user_id"]);
    // Validate rental_fee
    $input_date_from = trim($_POST["date_from"]);
    if (empty($input_date_from)) {
        $date_from_err = "Please enter date.";
    } else {
        $date_from = $input_date_from;
    }

    $input_date_to = trim($_POST["date_to"]);
    if (empty($input_date_to)) {
        $date_to_err = "Please enter date.";
    } else {
        $date_to = $input_date_to;
    }

    // Check input errors before inserting in database
    if (empty($date_from_err) && empty($date_to_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO rental (laptop_id, user_id, date_from, date_to, is_returned) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssss", $param_laptop_id, $param_user_id, $param_date_from, $param_date_to, $param_is_returned);

            // Set parameters
            $param_laptop_id = $laptop_id;
            $param_user_id = $user_id;
            $param_date_from = $date_from;
            $param_date_to = $date_to;
            $param_is_returned = 0;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: rental.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["laptop_id"]) && !empty(trim($_GET["laptop_id"]))) {
        // Get URL parameter
        $laptop_id =  trim($_GET["laptop_id"]);
        $user_id = trim($_GET["user_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM laptop WHERE laptop_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_laptop_id);

            // Set parameters
            $param_laptop_id = $laptop_id;

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
                    // URL doesn't contain valid id. Redirect to error page
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="wrapper vh-100">
        <div class="container-fluid">
            <div class="row vh-100 d-flex align-items-center justify-content-center">
                <div class="col-md-4 d-flex align-items-center">
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="card">
                            <img src="images/macbook.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h3 class="card-title"><b><?php echo $brand . " " . $model ?></b></h3>
                                <p class="card-text">Php <b><?php echo $rental_fee ?></b> per Day</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Date From<?php echo $user_id ?></label>
                            <?php
                            $formatted_date = (!empty($date_from)) ? date('Y-m-d', strtotime($date_from)) : '';
                            ?>
                            <input type="date" name="date_from" class="form-control <?php echo (!empty($date_from_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $formatted_date; ?>">
                            <span class="invalid-feedback"><?php echo $date_from_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Date To</label>
                            <?php
                            $formatted_date = (!empty($date_to)) ? date('Y-m-d', strtotime($date_to)) : '';
                            ?>
                            <input type="date" name="date_to" class="form-control <?php echo (!empty($date_to_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $formatted_date; ?>">
                            <span class="invalid-feedback"><?php echo $date_to_err; ?></span>
                        </div>

                        <input type="hidden" name="laptop_id" value="<?php echo $laptop_id; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="rental.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>