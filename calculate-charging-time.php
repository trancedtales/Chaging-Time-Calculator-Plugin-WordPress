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
    <div class="calc-wrap">
        <form method="post" action="">
            <pre>
Battery (Ah) <input type="number" name="ampMin" step="any" required> 
Charger (A) <input type="number" name="charAmp" step="any" required> 
            </pre>
            <input type="submit" name="calculate_time" value="Submit">
        </form>
        <?php
        if (isset($_POST['calculate_time'])) {
            $ampMin = $_POST['ampMin'];
            $charAmp = $_POST['charAmp'];
            
            $chargingLossPercentage = 40;
            $charLoss = $ampMin * ($chargingLossPercentage / 100);
            $totalAmp = $ampMin + $charLoss;

            if ($charAmp != 0) {
                $timeTaken = $totalAmp / $charAmp;
                $hours = floor($timeTaken);
                $minutes = ($timeTaken - $hours) * 60;
                echo "<p>Time Taken = " . $hours . " hours and " . round($minutes) . " minutes</p>";
            } else {
                echo "<p>Error: Charging current cannot be zero.</p>";
            }
        }
        ?>
    </div>
    <?php
    return ob_get_clean();
}

// Register shortcode
function register_charging_time_shortcode() {
    add_shortcode('charging_time_form', 'charging_time_form');
}

add_action('init', 'register_charging_time_shortcode');

// Enqueue plugin styles
function enqueue_charging_time_styles() {
    wp_enqueue_style('charging-time-style', plugin_dir_url(__FILE__) . 'style.css');
}

add_action('wp_enqueue_scripts', 'enqueue_charging_time_styles');