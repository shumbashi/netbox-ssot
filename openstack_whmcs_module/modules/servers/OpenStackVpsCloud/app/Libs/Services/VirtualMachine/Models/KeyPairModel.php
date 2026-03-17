<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class KeyPairModel extends BaseVpsModel
{
    const PRIVATE = 'private';
    const PUBLIC  = 'public';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $public;

    /**
     * @var string|null
     */
    protected $private;

    /**
     * @var string
     */
    protected $fingerPrint;

    /**
     * KeyPairModel constructor.
     * @param string|null $tenantID
     * @param string|null $UUID
     * @param array $params
     * @throws \Exception
     */
    public function __construct(string $tenantID = null, string $UUID = null, array $params = [])
    {
        if (empty($params) && $UUID)
        {
            $params = Api::getInstance()->compute()->getKeyPair($UUID);
        }

        parent::__construct($tenantID, $UUID, $params);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function listSource()
    {
        return Api::getInstance()->compute()->listKeyPairs();
    }

    /**
     * @throws Exception
     * @throws \Exception
     *
     */
    public function create()
    {
        if (empty($this->name))
        {
            throw new \Exception('Provide name at first');
        }

        $result = Api::getInstance()->compute()->createKeyPair($this->name, $this->public);

        if ($result['private'])
        {
            $this->private = $result['private'];
        }

        $this->public      = $result['public'];
        $this->fingerPrint = $result['fingerPrint'];
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        Api::getInstance()->compute()->deleteKeyPair($this->name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param string|null $public
     */
    public function setPublic(string $public = null)
    {
        $this->public = $public;
    }

    /**
     * @return string|null
     */
    public function getPrivate()
    {
        return $this->private;
    }

    /**
     * @param string|null $private
     */
    public function setPrivate(string $private = null)
    {
        $this->private = $private;
    }

    /**
     * @return string
     */
    public function getFingerPrint()
    {
        return $this->fingerPrint;
    }

    /**
     * @param string $fingerPrint
     */
    public function setFingerPrint(string $fingerPrint)
    {
        $this->fingerPrint = $fingerPrint;
    }

    public function jsonSerialize()
    {
        $array = parent::jsonSerialize();
        foreach ($array as &$attribute) {
            $attribute = \encrypt($attribute);
        }
        return $array;
    }

    public static function fromArray(?array $array)
    {
        foreach ($array as &$attribute) {
            $attribute = \decrypt($attribute);
        }

        return parent::fromArray($array);
    }
}