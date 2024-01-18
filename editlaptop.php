<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$brand = $model = $rental_fee = "";
$brand_err = $model_err = $rental_fee_err = "";

// Processing form data when form is submitted
if (isset($_POST["laptop_id"]) && !empty($_POST["laptop_id"])) {
    // Get hidden input value
    $laptop_id = $_POST["laptop_id"];

    // Validate brand
    $input_brand = trim($_POST["brand"]);
    if (empty($input_brand)) {
        $brand_err = "Please enter a brand.";
    } elseif (!filter_var($input_brand, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $brand_err = "Please enter a valid brand.";
    } else {
        $brand = $input_brand;
    }

    // Validate model
    $input_model = trim($_POST["model"]);
    if (empty($input_model)) {
        $model_err = "Please enter an model.";
    } else {
        $model = $input_model;
    }

    // Validate rental_fee
    $input_rental_fee = trim($_POST["rental_fee"]);
    if (empty($input_rental_fee)) {
        $rental_fee_err = "Please enter the rental_fee amount.";
    } elseif (!ctype_digit($input_rental_fee)) {
        $rental_fee_err = "Please enter a positive value.";
    } else {
        $rental_fee = $input_rental_fee;
    }

    // Check input errors before inserting in database
    if (empty($brand_err) && empty($model_err) && empty($rental_fee_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO laptop (brand, model, rental_fee) VALUES (?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_brand, $param_model, $param_rental_fee);

            // Set parameters
            $param_brand = $brand;
            $param_model = $model;
            $param_rental_fee = $rental_fee;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
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
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row d-flex vh-100 justify-content-center align-items-center">
                <div class="col-6 border rounded rounded-3 bg-light p-3">
                    <h2 class="">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group mb-3">
                            <label>Brand</label>
                            <input type="text" name="brand" class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
                            <span class="invalid-feedback"><?php echo $brand_err; ?></span>
                        </div>
                        <div class="form-group mb-3">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control <?php echo (!empty($model_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $model; ?>">
                            <span class="invalid-feedback"><?php echo $model_err; ?></span>
                        </div>
                        <div class="form-group mb-3">
                            <label>Daily Rental Fee</label>
                            <input type="text" name="rental_fee" class="form-control <?php echo (!empty($rental_fee_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rental_fee; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err; ?></span>
                        </div>
                        <input type="hidden" name="laptop_id" value="<?php echo $laptop_id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>