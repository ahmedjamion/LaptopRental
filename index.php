<?php
// Initialize the session
session_start();

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    // Check if the user type is "Customer"
    if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] === "Customer") {
        // User is logged in and is a Customer, redirect to rental.php
        header("location: rental.php");
        exit;
    }
} else {
    // User is not logged in, redirect to login.php
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="wrapper d-flex flex-column vh-100">
        <div class="container-fluid d-flex align-items-center justify-content-between pt-2 pb-2 bg-light">
            <h3 class="mb-0">Lorem Ipsum Laptop Rental</h3>
            <div class="dropdown">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo htmlspecialchars($_SESSION["username"]); ?>
                </a>
                <ul class="dropdown-menu bg-light shadow">
                    <li><a class="dropdown-item" href="resetpassword.php">Change Password</a></li>
                    <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid flex-grow-1">
            <div class="row h-100">
                <div class="col-2 h-100 bg-light">
                    <div class="nav flex-column nav-pills me-3 mt-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-laptops" aria-selected="true">Laptops</button>

                        <button class="nav-link" id="v-pills-customers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-customers" aria-selected="false">Customers</button>

                        <button class="nav-link" id="v-pills-rentals-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rentals" type="button" role="tab" aria-controls="v-pills-rentals" aria-selected="false">Rentals</button>

                        <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-history" aria-selected="false">History</button>

                        <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-about" aria-selected="false">About Us</button>
                    </div>
                </div>
                <div class="col-10">
                    <div class="tab-content mt-2" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">

                            <div class="d-flex justify-content-between mb-3">
                                <h2>All Laptops</h2>
                                <a href="addnewlaptop.php" class="btn btn-info"></i>Add New Laptop</a>
                            </div>

                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "WITH RankedRentals AS (
                                    SELECT
                                        laptop.laptop_id,
                                        laptop.brand,
                                        laptop.model,
                                        laptop.rental_fee,
                                        rental.rental_id,
                                        rental.is_returned,
                                        ROW_NUMBER() OVER (PARTITION BY laptop.laptop_id ORDER BY rental.rental_id DESC) AS RowNum
                                    FROM
                                        laptop
                                    LEFT JOIN
                                        rental ON laptop.laptop_id = rental.laptop_id
                                )
                                SELECT
                                    laptop_id,
                                    brand,
                                    model,
                                    rental_fee,
                                    rental_id,
                                    is_returned
                                FROM
                                    RankedRentals
                                WHERE
                                    RowNum = 1;";

                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Brand</th>";
                                    echo "<th>Model</th>";
                                    echo "<th>Rental Fee</th>";
                                    echo "<th>Status</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['laptop_id'] . "</td>";
                                        echo "<td>" . $row['brand'] . "</td>";
                                        echo "<td>" . $row['model'] . "</td>";
                                        echo "<td>" . $row['rental_fee'] . " Per Day</td>";

                                        $status = "Available";
                                        if ($row["is_returned"] == 1 || $row["is_returned"] == null) {
                                            $status = "Available";
                                        } else if ($row["is_returned"] == 0) {
                                            $status = "Rented";
                                        }
                                        echo "<td>" . $status . "</td>";
                                        echo "<td>";
                                        echo '<a href="viewlaptop.php?laptop_id=' . $row['laptop_id'] . '" class="me-2 btn btn-info">View</a>';
                                        echo '<a href="editlaptop.php?laptop_id=' . $row['laptop_id'] . '" class="me-2 btn btn-primary">Edit</a>';
                                        echo '<a href="deletelaptop.php?laptop_id=' . $row['laptop_id'] . '" class="btn btn-danger">Delete</a>';
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // Free result set
                                    $result->free();
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            ?>
                        </div>


                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
                            <div class="d-flex justify-content-between mb-3">
                                <h2>All Customers</h2>
                            </div>

                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "SELECT * FROM user_account
                                    WHERE user_type = 'Customer'";
                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Last Name</th>";
                                    echo "<th>Username</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["user_id"] . "</td>";
                                        echo "<td>" . $row['first_name'] . "</td>";
                                        echo "<td>" . $row['last_name'] . "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // Free result set
                                    $result->free();
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-rentals" role="tabpanel" aria-labelledby="v-pills-rentals-tab" tabindex="0">
                            <div class="d-flex justify-content-between mb-3">
                                <h2>Current Rentals</h2>
                            </div>

                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "SELECT rental.rental_id,
                                            laptop.brand,
                                            laptop.model,
                                            user_account.first_name,
                                            user_account.last_name,
                                            rental.date_from,
                                            rental.date_to,
                                            laptop.rental_fee,
                                            rental.is_returned
                                    FROM rental
                                    JOIN laptop ON laptop.laptop_id = rental.laptop_id
                                    JOIN user_account ON rental.user_id = user_account.user_id
                                    WHERE is_returned = false";
                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Brand</th>";
                                    echo "<th>Model</th>";
                                    echo "<th>Customer</th>";
                                    echo "<th>From</th>";
                                    echo "<th>To</th>";
                                    echo "<th>Total Fee</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["rental_id"] . "</td>";
                                        echo "<td>" . $row['brand'] . "</td>";
                                        echo "<td>" . $row['model'] . "</td>";
                                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                        echo "<td>" . $row['date_from'] . "</td>";
                                        echo "<td>" . $row['date_to'] . "</td>";
                                        echo "<td>" . number_format($row['rental_fee'] * (strtotime($row['date_to']) - strtotime($row['date_from'])) / (60 * 60 * 24), 2) . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // Free result set
                                    $result->free();
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">
                            <div class="d-flex justify-content-between mb-3">
                                <h2>Rental History</h2>
                            </div>

                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "SELECT rental.rental_id,
                                            laptop.brand,
                                            laptop.model,
                                            user_account.first_name,
                                            user_account.last_name,
                                            rental.date_from,
                                            rental.date_to,
                                            laptop.rental_fee,
                                            rental.is_returned
                                    FROM rental
                                    JOIN laptop ON laptop.laptop_id = rental.laptop_id
                                    JOIN user_account ON rental.user_id = user_account.user_id
                                    WHERE is_returned = true";
                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>#</th>";
                                    echo "<th>Brand</th>";
                                    echo "<th>Model</th>";
                                    echo "<th>Customer</th>";
                                    echo "<th>From</th>";
                                    echo "<th>To</th>";
                                    echo "<th>Total Fee</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row["rental_id"] . "</td>";
                                        echo "<td>" . $row['brand'] . "</td>";
                                        echo "<td>" . $row['model'] . "</td>";
                                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                        echo "<td>" . $row['date_from'] . "</td>";
                                        echo "<td>" . $row['date_to'] . "</td>";
                                        echo "<td>" . number_format($row['rental_fee'] * (strtotime($row['date_to']) - strtotime($row['date_from'])) / (60 * 60 * 24), 2) . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // Free result set
                                    $result->free();
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }

                            // Close connection
                            $mysqli->close();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">
                            <h1>Group 1</h1>
                            <ul>
                                <li>
                                    <h3>Besira, Mark Laurenz M.</h3>
                                </li>
                                <li>
                                    <h3>Dizon, Arjec Jose A.</h3>
                                </li>
                                <li>
                                    <h3>Jamion, Ahmed Rashad I.</h3>
                                </li>
                                <li>
                                    <h3>Pagotaisidro, Marco Jean F.</h3>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>