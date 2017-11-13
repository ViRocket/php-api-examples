<?php
/**
 * Just a function to simplify cUrl requests.
 *
 * @param string $url
 * @param string $method
 * @param array  $postFields
 * @param array  $headers
 *
 * @return mixed
 */
function sendRequest(
    $url,
    $method = 'GET',
    array $postFields = array(),
    array $headers = array()
) {
    $curl = curl_init();

    $params = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS      => 10,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
    );

    if ('GET' !== strtoupper($method)) {
        $params[CURLOPT_CUSTOMREQUEST] = $method;
    }

    if (count($postFields)) {
        $params[CURLOPT_POSTFIELDS] = $postFields;
    }

    if (count($headers)) {
        $params[CURLOPT_HTTPHEADER] = $headers;
    }

    curl_setopt_array($curl, $params);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        throw new \RuntimeException('cUrl Error #:' . $err);
    }

    curl_close($curl);

    return $response;
}