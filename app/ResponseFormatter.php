<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;

/**
 * This class is responsible for formatting the response as JSON.
 */
class ResponseFormatter
{
    /**
     * Formats the response as JSON and returns the response with the JSON content type header.
     *
     * @param ResponseInterface $response The response to be formatted.
     * @param mixed $data The data to be formatted as JSON.
     * @param int $flags The JSON encoding options. Default is JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR.
     * @return ResponseInterface The response with the JSON content type header and the formatted JSON data.
     */
    public function asJson(
        ResponseInterface $response,
        mixed $data,
        int $flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
    ): ResponseInterface {

        $response = $response->withHeader('Content-Type', 'application/json');

        $response->getBody()->write(json_encode($data, $flags));

        return $response;
    }
}
