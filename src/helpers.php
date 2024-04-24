<?php

use Adplay\Handler\Response;

function dd($inputData)
{
    echo "<pre>";
    print_r($inputData);
    exit;
}

function responseJSON($data, $http_code){
    return Response::json($data,$http_code);
}

// calculate distance from lat,lon
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Radius of the earth in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $distance = $earthRadius * $c; // Distance in kilometers
    return $distance;
}


function getUrlToDomain($url) {
    // Parse the URL
    $parsedUrl = parse_url($url);

    // Get the domain
    $domain = $parsedUrl['host'];

    // Check if scheme is present in the URL
    if (isset($parsedUrl['scheme'])) {
        // URL has scheme
        $scheme = $parsedUrl['scheme'];
        return $domain;
    } else {
        // URL does not have scheme
        return $domain;
    }
}