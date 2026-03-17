<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http;

class HttpHeadersParser
{
    public function httpParseHeaders($header)
    {
        $fields = $this->splitHeaderFields($header);
        $headerFields = $this->parseHeaderFields($fields);
        $body = $this->extractBody($fields);

        return array_merge($headerFields, ['body' => $body]);
    }

    private function splitHeaderFields($header)
    {
        return explode("\r\n", $header);
    }

    private function parseHeaderFields(array &$fields)
    {
        $retVal = [];
        $isHeader = true;
        $isChunked = false;
        $chunkedIndex = 0;

        foreach ($fields as $key => $field) {
            if ($isHeader) {
                $this->processHeaderField($fields, $retVal, $isHeader, $isChunked, $chunkedIndex, $key, $field);
            } else {
                $this->processBodyField($fields, $isChunked, $chunkedIndex, $key);
            }
        }

        return $retVal;
    }

    private function processHeaderField(&$fields, &$retVal, &$isHeader, &$isChunked, &$chunkedIndex, $key, $field)
    {
        if ($this->isEmptyField($field)) {
            $isHeader = false;
            unset($fields[$key]);
            return;
        }

        $headerField = $this->parseHeaderField($field);
        if ($headerField !== null) {
            list($name, $value) = $headerField;
            $this->storeHeaderField($retVal, $name, $value);

            if ($this->isChunkedEncoding($name, $value)) {
                $isChunked = true;
                $chunkedIndex = $key;
            }
        }
        unset($fields[$key]);
    }

    private function processBodyField(&$fields, $isChunked, $chunkedIndex, $key)
    {
        if ($this->shouldUnsetChunkedField($isChunked, $key, $chunkedIndex)) {
            unset($fields[$key]);
        }
    }

    private function isEmptyField($field)
    {
        return $field === '';
    }

    private function parseHeaderField($field)
    {
        if (preg_match('/([^:]+): (.+)/m', $field, $match)) {
//            $field = preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $field);
            $name = $this->normalizeHeaderName($match[1]);
            return [trim($name), trim($match[2])];
        }
        return null;
    }

    private function normalizeHeaderName($name)
    {
        return preg_replace_callback('/(?<=^|[\x09\x20\x2D])./', function ($matches) {
            return strtolower($matches[0]);
        }, strtolower(trim($name)));
    }

    private function storeHeaderField(&$retVal, $name, $value)
    {
        if (isset($retVal[$name])) {
            if (is_array($retVal[$name])) {
                $retVal[$name][] = $value;
            } else {
                $retVal[$name] = [$retVal[$name], $value];
            }
        } else {
            $retVal[$name] = $value;
        }
    }

    private function isChunkedEncoding($name, $value)
    {
        return $name === "transfer-encoding" && strtolower(trim($value)) === "chunked";
    }

    private function shouldUnsetChunkedField($isChunked, $key, $chunkedIndex)
    {
        return $isChunked && ($key - $chunkedIndex) % 2 == 0;
    }

    private function extractBody(array $fields)
    {
        return implode('', $fields);
    }
}

