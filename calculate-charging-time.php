<?php
/*
Plugin Name: Calculate Charging Time
Description: A simple plugin to calculate battery charging time in real time taking losses into account.
Version: 1.0
Author: Aditya Kumar
*/

// Function to render the form and handle form submission
function charging_time_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <pre>
Battery (Ah) <input type="number" name="ampMin" step="any" required> 
        </pre>
        <input type="submit" name="calculate_time" value="Submit">
    </form>
    <?php
    if (isset($_POST['calculate_time'])) {
        $ampMin = $_POST['ampMin'];
        $chargingCurrentPercentage = 10;
        $chargingLossPercentage = 40;

        $charCurr = $ampMin * ($chargingCurrentPercentage / 100);
        $charLoss = $ampMin * ($chargingLossPercentage / 100);
        $totalAmp = $ampMin + $charLoss;

        if ($charCurr != 0) {
            $timeTaken = $totalAmp / $charCurr;
            echo "<p>Time Taken = " . $timeTaken . " hours</p>";
        } else {
            echo "<p>Error: Charging current cannot be zero.</p>";
        }
    }
    return ob_get_clean();
}

// Register shortcode
function register_charging_time_shortcode() {
    add_shortcode('charging_time_form', 'charging_time_form');
}

add_action('init', 'register_charging_time_shortcode');