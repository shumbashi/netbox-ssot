<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Helpers;

class JsonContentParser
{
    public function parse($input)
    {
        if ($this->containsContentTypeHeader($input)) {
            $input = $this->removeContentTypeHeader($input);
            return $this->decodeJSON($input);
        }

        $decodedJSON = $this->decodeJSON($input);
        if ($decodedJSON !== null) {
            return $decodedJSON;
        }

        $jsonString = $this->extractJSONString($input);
        return $this->decodeJSON($jsonString);
    }

    private function containsContentTypeHeader($input)
    {
        return strpos($input, "Content-Type: application/json") !== false;
    }

    private function removeContentTypeHeader($input)
    {
        return str_replace("Content-Type: application/json", "", $input);
    }

    private function decodeJSON($input)
    {
        $json = json_decode(trim($input), true);
        return json_last_error() === JSON_ERROR_NONE ? $json : null;
    }

    private function extractJSONString($input)
    {
        $jsonBoundary = $this->findJSONBoundaries($input);
        if ($jsonBoundary === null) {
            return '';
        }

        [$start, $stop, $add] = $jsonBoundary;
        return substr($input, $start, ($stop - $start + $add));
    }

    private function findJSONBoundaries($input)
    {
        $start = strpos($input, '[{');
        $stop  = strrpos($input, '}]');
        $add   = 2;

        $start2 = strpos($input, '{');
        $stop2  = strrpos($input, '}');

        if ($start === false || $stop === false || ($start > $start2 && $stop < $stop2)) {
            $start = $start2;
            $stop  = $stop2;
            $add   = 1;
        }

        if ($start === false || $stop === false) {
            return null;
        }

        return [$start, $stop, $add];
    }
}