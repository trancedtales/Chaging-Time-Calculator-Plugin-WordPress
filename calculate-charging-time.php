<?php
/*
Plugin Name: Calculate Charging Time (Standalone)
Description: A standalone script to calculate battery charging time.
Version: 1.0
Author: Aditya Kumar
*/

// Function to render the form and handle form submission
function charging_time_form() {
    ob_start();
    ?>
    <div class="calc-wrap">
        <form method="post" action="">
            <label for="ampMin">Battery Capacity (Ah):</label>
            <input type="number" id="ampMin" name="ampMin" step="any" required>
            
            <label for="charger">Choose a Charger:</label>
            <select name="charger" id="charger">
                <option value="5.0">CTEK MXS 5.0 T EU</option>
                <option value="5">CTEK CT5 Time To Go EU</option>
                <option value="3.8">CTEK MXS 3.8 EU</option>
                <option value="7">CTEK MXS 7.0 EU</option>
                <option value="0.8">CTEK XS 0.8 EU</option>
                <option value="10">CTEK MXS 10 EU</option>
                <option value="5">CTEK CT5 Start Stop EU</option>
                <option value="3.8">CTEK Lithium XS EU</option>
                <option value="40">CTEK MXTS 40 EU</option>
                <option value="25">CTEK Pro 25s EU</option>
                <option value="60">CTEK PRO 60</option>
                <option value="70">CTEK MXTS 70 - 50</option>
                <option value="120">CTEK Pro 120</option>
                <option value="60">CTEK PRO 60 EU</option>
                <option value="15">CTEK M15</option>
                <option value="3.8">CTEK CT5 PowerSport</option>
            </select>

            <input type="submit" name="calculate_time" value="Calculate Time">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_time'])) {
            $ampMin = floatval($_POST['ampMin']); // Battery capacity in Ampere-hours
            $charAmp = floatval($_POST['charger']); // Charging current in Amps

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