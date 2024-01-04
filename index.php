<?php

$token = '';
$customer_id = '';
$client_id = '';
$client_secret = '';

// Set the POST data
$postData = http_build_query([
    'client_id' => $client_id, // Replace with your actual Client ID
    'client_secret' => $client_secret, // Replace with your actual Client Secret
    'grant_type' => 'client_credentials',
    'scope' => 'eats.deliveries', // The scope of access required
]);

// Set cURL options
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://auth.uber.com/oauth/v2/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
]);

// Send the request to Authorization API
$response = curl_exec($ch);
if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo $response; // Print the response body containing the access token
    $token = json_decode($response)->access_token;
}

// Close cURL resource
curl_close($ch);

if($token == '') {
    die('No se pudo obtener el token');
}

echo PHP_EOL;

//Create quote

// Datos de la solicitud
$data = [
    "pickup_address" => '{"street_address": ["20 W 34th St", "Floor 2"],"state":"NY","city":"New York","zip_code":"10001","country":"US"}',
    "dropoff_address" => '{"street_address": ["285 Fulton St", ""],"state":"NY","city":"New York","zip_code":"10006","country":"US"}'
];

// Codificar los datos en formato JSON
$postData = json_encode($data);

// URL y cabeceras
$url = 'https://api.uber.com/v1/customers/'.$customer_id.'/delivery_quotes'; // Reemplaza <customer_id> con el ID real
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer '.$token // Reemplaza <TOKEN> con el token real
];

// Inicializar cURL
$ch = curl_init($url);

// Establecer opciones de cURL
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($ch);

// Verificar errores
if ($response === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    echo $response;
}

// Cerrar la conexión cURL
curl_close($ch);

?>



