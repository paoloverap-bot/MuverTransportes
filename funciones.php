<?php
function geocodeAddress(string $address): array
{
    $apiKey = "AIzaSyAiqMuCjLrbE7h6sW87VOq7Cy0OgluTqDU";
    $encodedAddress = urlencode($address);

    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$encodedAddress}&key={$apiKey}";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        throw new Exception('Error cURL: ' . curl_error($ch));
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if ($data['status'] !== 'OK') {
        throw new Exception('Error Geocoding: ' . $data['status']);
    }

    $result = $data['results'][0];

    return [
        'lat' => $result['geometry']['location']['lat'],
        'lng' => $result['geometry']['location']['lng'],
        'formatted_address' => $result['formatted_address']
    ];
}
?>