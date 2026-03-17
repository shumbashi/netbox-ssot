<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Http;


class ParamStringBuilder
{
    private $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function buildParamString()
    {
        $paramString = '';

        if (!empty($this->params)) {
            if (is_array($this->params)) {
                foreach ($this->params as $key => $value) {
                    $paramString .= $this->processKeyValue($key, $value);
                }
            } else {
                $paramString .= '/' . $this->params;
            }
        }

        return $paramString;
    }

    private function processKeyValue($key, $value)
    {
        if (is_array($value)) {
            $value = array_map('rawurlencode', $value);
        } else {
            $value = rawurlencode($value);
        }

        if (is_string($key)) {
            $key = rawurlencode($key);
            if (is_array($value)) {
                return '/' . $key . '/["' . implode('","', $value) . '"]';
            } else {
                return '/' . $key . '/' . $value;
            }
        } else {
            return '/' . $value;
        }
    }
}