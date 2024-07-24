<?php
   
    

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
             $json_data = '[
                {
                    "name": "CTEK MXS 5.0 T EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxs-5-0-t-eu/",
                    "value": 5.0
                },
                {
                    "name": "CTEK CT5 Time To Go EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-ct5-time-to-go-eu/",
                    "value": 5
                },
                {
                    "name": "CTEK MXS 3.8 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxs-3-8-eu/",
                    "value": 3.8
                },
                {
                    "name": "CTEK MXS 7.0 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxs-7-0-eu/",
                    "value": 7
                },
                {
                    "name": "CTEK XS 0.8 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-xs-0-8-eu/",
                    "value": 0.8
                },
                {
                    "name": "CTEK MXS 10 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxs-10-eu/",
                    "value": 10
                },
                {
                    "name": "CTEK CT5 Start Stop EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-ct5-start-stop-eu/",
                    "value": 5
                },
                {
                    "name": "CTEK Lithium XS EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-lithium-xs-eu/",
                    "value": 3.8
                },
                {
                    "name": "CTEK MXTS 40 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxts-40-eu/",
                    "value": 40
                },
                {
                    "name": "CTEK Pro 25s EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-pro-25s-eu/",
                    "value": 25
                },
                {
                    "name": "CTEK PRO 60",
                    "url": "https://naredi.starvikphotography.com/product/ctek-pro-60/",
                    "value": 60
                },
                {
                    "name": "CTEK MXTS 70 - 50",
                    "url": "https://naredi.starvikphotography.com/product/ctek-mxts-70-50/",
                    "value": 70
                },
                {
                    "name": "CTEK Pro 120",
                    "url": "https://naredi.starvikphotography.com/product/ctek-pro-120/",
                    "value": 120
                },
                {
                    "name": "CTEK PRO 60 EU",
                    "url": "https://naredi.starvikphotography.com/product/ctek-pro-60-eu/",
                    "value": 60
                },
                {
                    "name": "CTEK M15",
                    "url": "https://naredi.starvikphotography.com/product/ctek-m15/",
                    "value": 15
                },
                 {
                    "name": "CTEK CT5 PowerSport",
                    "url": "https://naredi.starvikphotography.com/product/ctek-ct5-powersport/",
                    "value": 3.84
                }
            ]';
            
            $data = json_decode($json_data, true);
            function filter_data_by_charAmp($data, $charAmp) {
                $filtered_data = array_filter($data, function($product) use ($charAmp) {
                    return $product['value'] == $charAmp;
                });
                return array_values($filtered_data); // Reset array keys
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_time'])) {
                $ampMin = floatval($_POST['ampMin']); // Battery capacity in Ampere-hours
                $charAmp = floatval($_POST['charger']); // Charging current in Amps

                $chargingLossPercentage = 40;
                $charLoss = $ampMin * ($chargingLossPercentage / 100);
                $totalAmp = $ampMin + $charLoss;
                $filtered_data = filter_data_by_charAmp($data, $charAmp);
                $url = $filtered_data[0]['url'];
                $name = $filtered_data[0]['name'];
                if ($charAmp != 0) {
                    $timeTaken = $totalAmp / $charAmp;
                    $hours = floor($timeTaken);
                    $minutes = ($timeTaken - $hours) * 60;
                    echo '<p>Time Taken by <a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($name) . '</a>: ' . $hours . ' hours and ' . round($minutes) . ' minutes</p>';
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

