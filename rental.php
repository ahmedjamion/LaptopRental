<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Retrieve user ID from the session
$user_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script defer src="js/bootstrap.bundle.min.js"></script>
    <style>
        .wrapper {
            max-height: 100vh;
        }

        .selection {
            overflow-y: scroll;
            max-height: 83vh;
        }
    </style>
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
                    <li><a class="dropdown-item" href="changepassword.php">Change Password</a></li>
                    <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid flex-grow-1">
            <div class="row h-100">
                <div class="col-2 h-100 bg-light">
                    <div class="nav flex-column nav-pills me-3 mt-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-laptops" aria-selected="true">Laptops</button>

                        <button class="nav-link" id="v-pills-customers-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-customers" aria-selected="false">Rentals</button>

                        <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-history" aria-selected="false">History</button>

                        <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-about" aria-selected="false">About Us</button>
                    </div>
                </div>
                <div class="col-10 h-100 d-flex flex-column">
                    <div class="tab-content h-100 d-flex flex-column" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">

                            <div class="d-flex py-2">
                                <h2>Available Laptops</h2>
                            </div>
                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "SELECT
                                        laptop.laptop_id AS laptop_id,
                                        MAX(laptop.brand) AS brand,
                                        MAX(laptop.model) AS model,
                                        MAX(laptop.rental_fee) AS rental_fee,
                                        MAX(rental.rental_id) AS rental_id,
                                        MAX(rental.is_returned) AS is_returned
                                    FROM laptop
                                    LEFT JOIN rental ON laptop.laptop_id = rental.laptop_id
                                    WHERE NOT EXISTS (
                                        SELECT 1
                                        FROM rental r
                                        WHERE r.laptop_id = laptop.laptop_id
                                        AND r.is_returned = false
                                    )
                                    GROUP BY laptop.laptop_id";

                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<div class="row d-flex justify-content-center flex-grow-1 selection">';
                                    while ($row = $result->fetch_array()) {
                                        echo '<div class="col-4 card px-0 me-3 mb-3">';
                                        echo '<img src="images/macbook.jpg" class="w-100 card-img-top object-fit-fill" alt="...">';
                                        echo '<div class="card-body">';
                                        echo '<h5 class="card-title"><b>' . $row["brand"] . ' ' . $row["model"] . '</b></h5>';
                                        echo '<p class="card-text">Php <b>' . $row["rental_fee"] . '</b> per day</p>';
                                        echo '<a href="createrental.php?laptop_id=' . $row['laptop_id'] . '&user_id=' . $user_id . '" class="btn btn-primary">Rent</a>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    echo '</div>';

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
                            <div class="d-flex py-2">
                                <h2>Your Current Rentals</h2>
                            </div>
                            <?php
                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution

                            $sql = "SELECT 
                                            laptop.laptop_id AS laptop_id,
                                            laptop.brand AS brand,
                                            laptop.model AS model,
                                            laptop.rental_fee AS rental_fee,
                                            rental.rental_id AS rental_id,
                                            rental.is_returned AS is_returned,
                                            rental.date_from,
                                            rental.date_to
                                        FROM 
                                            laptop
                                        LEFT JOIN 
                                            rental ON laptop.laptop_id = rental.laptop_id
                                        WHERE 
                                            rental.user_id = $user_id AND rental.is_returned = false";

                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<div class="row d-flex justify-content-center flex-grow-1 selection">';
                                    while ($row = $result->fetch_array()) {
                                        echo '<div class="col-4 card px-0 me-3 mb-3">';
                                        echo '<img src="images/macbook.jpg" class="w-100 card-img-top object-fit-fill" alt="...">';
                                        echo '<div class="card-body">';
                                        echo '<h5 class="card-title"><b>' . $row["brand"] . ' ' . $row["model"] . '</b></h5>';
                                        echo '<p class="card-text">Php <b>' . $row["rental_fee"] . '</b> per day</p>';
                                        echo "<p>Total Rent Fee: Php <b>" . number_format($row['rental_fee'] * (strtotime($row['date_to']) - strtotime($row['date_from'])) / (60 * 60 * 24), 2) . "</b></p>";
                                        echo '<a href="returnlaptop.php?rental_id=' . $row['rental_id'] . '" class="btn btn-primary">Return</a>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    echo '</div>';

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
                                <h2>Your Rental History</h2>
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
                                    LEFT JOIN laptop ON laptop.laptop_id = rental.laptop_id
                                    LEFT JOIN user_account ON rental.user_id = user_account.user_id
                                    WHERE user_account.user_id = $user_id AND rental.is_returned = true";
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