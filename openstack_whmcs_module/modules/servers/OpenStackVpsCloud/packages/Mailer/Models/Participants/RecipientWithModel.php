<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants;

use \WHMCS\Model\AbstractModel;

abstract class RecipientWithModel extends Recipient
{
    protected AbstractModel $model;

    public function __construct(AbstractModel $model, string $email, string $name = "", string $type = self::TYPE_CUSTOM, ?int $relId = null)
    {
        $this->model = $model;
        parent::__construct($email, $name, $type, $relId);
    }

    public function getModel(): AbstractModel
    {
        return $this->model;
    }
}