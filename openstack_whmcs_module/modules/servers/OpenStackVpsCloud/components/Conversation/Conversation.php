<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Conversation;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;

class Conversation extends Container
{
    public const COMPONENT = 'Conversation';

    protected $messages = [];

    public function __construct()
    {
        $this->setSlot('messages');

        parent::__construct();;
    }


    public function addMessage(Message $message): self
    {
        $this->messages[] = $message;

        return $this;
    }

    public function addMessages(array $messages): self
    {
        foreach ($messages as $message)
        {
            $this->addMessage($message);
        }

        return $this;
    }
}