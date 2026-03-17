<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http\Validator;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OpenStackApiException;

class ResponseValidator {

    //TODO: differenciate based on response code

    const ALLOWED_RESPONSE_CODES = [
        'GET'    => [200, 300, 302],
        'POST'   => [200, 201, 202, 204],
        'DELETE' => [202, 204],
        'PUT'    => [200, 202],
        'PATCH'  => [200]
    ];

    public static function validate($response, string $code, array $request, string $method)
    {
        if (in_array($code, self::ALLOWED_RESPONSE_CODES[$method]))
        {
            return;
        }

        if (!is_array($response) && is_string($response))
        {
            throw new OpenStackApiException($response, $code, $request);
        }

        if (is_iterable($response)) {
            foreach ($response as $value) {
                if (!isset($value['message']) || !is_string($value['message'])) {
                    continue;
                }

                throw new OpenStackApiException($value['message'], $code, $request);
            }
        }
    }
}