<?php
require_once __DIR__.'/../../bootstrap.php';

$e_bill = isset($_POST['utility-bill']) ? $_POST['utility-bill'] : null;
$est_cost = isset($_POST['estimated-cost']) ? $_POST['estimated-cost'] : null;
$zip_code = isset($_POST['zip-code']) ? $_POST['zip-code'] : null;

if (!$e_bill || !$est_cost || !$zip_code) {
    header('Location: /index.php?error=missing_data');
    die;
}

$e_bill = floatval($e_bill);
$est_cost = floatval($est_cost);
$zip_code = trim($zip_code);

$container = new Container($configuration);
$caller = $container->getCaller();

//Get Zip Code Data
$zipUrl = 'http://maps.googleapis.com/maps/api/geocode/json';
$zip_data = array(
    'address' => $zip_code,
    'sensor' => 'false',
);
$zip_response = $caller->request($zipUrl, $zip_data);

$state = '';
//Retrieve State data from Zip Response 
foreach ($zip_response->results[0]->address_components as $key => $component) {
    if(in_array('administrative_area_level_1', $component->types)) {
        $state = $component->short_name;
    }
}

//Get Utility Rates for the zip code
$rateUrl = 'https://developer.nrel.gov/api/utility_rates/v3.json';
$rate_data = array(
    'api_key' => '7KSU4GDr24J5Vv6lWZg94Tm3cxxNEhlHJO8jzVWj',
    'lat' => $zip_response->results[0]->geometry->location->lat,
    'lon' => $zip_response->results[0]->geometry->location->lng,
);
$rate_response = $caller->request($rateUrl, $rate_data);

//Get Cost Per Watt based on State and zipcode
$costURL = 'https://developer.nrel.gov/api/solar/open_pv/installs/summaries';
$cost_data = array(
    'api_key' => '7KSU4GDr24J5Vv6lWZg94Tm3cxxNEhlHJO8jzVWj',
    'state' => $state,
    'zipcode' => $zip_code,
);
$cost_response = $caller->request($costURL, $cost_data);

//echo '<pre>' . var_export($cost_response, true) . '</pre>';

//Calculate System Capacity based on $e_bill, $est_cost and Utility Rates 
$best_cost = $cost_response->result->best_avg_cost_pw ? floatval($cost_response->result->best_avg_cost_pw) : '4.71';

//Apply Federal Investment Tax Credit Discount of 30%;
$best_cost *= 0.7;

$system_capacity = round(($est_cost / $best_cost) / 1000.00, 2);

//Get PV Watts estimate based on location 
$pvURL = 'https://developer.nrel.gov/api/pvwatts/v5.json';
$pv_data = array(
    'api_key' => '7KSU4GDr24J5Vv6lWZg94Tm3cxxNEhlHJO8jzVWj',
    'lat' => $zip_response->results[0]->geometry->location->lat,
    'lon' => $zip_response->results[0]->geometry->location->lng,
    'system_capacity' => $system_capacity,
    'azimuth' => 180,
    'tilt' => 45,
    'array_type' => 1,
    'module_type' => 1,
    'losses' => 10,
);
$pv_response = $caller->request($pvURL, $pv_data);

$location = $zip_response->results[0]->formatted_address;

//Savings and ROI
$solarkWh = floatval($pv_response->outputs->ac_annual); //This is the total kWh produced by solar for the year

$totalElectrickWh = ($e_bill * 12) / floatval($rate_response->outputs->residential);

$percentSaved = ($solarkWh / $totalElectrickWh) * 100;

$payback = floor($est_cost / (($percentSaved / 100) * $e_bill * 12));

//Write Values to the database
$pdo = $container->getPDO();
$statement = $pdo->prepare("INSERT INTO calculator(e_bill, est_cost, zip_code, percent_saved, payback_years) VALUES(:e_bill, :est_cost, :zip_code, :percent_saved, :payback_years)");
$statement->execute(array(
    'e_bill' => round($e_bill, 2),
    'est_cost' => round($est_cost, 2),
    'zip_code' => $zip_code,
    'percent_saved' => round($percentSaved, 2),
    'payback_years' => $payback,
));