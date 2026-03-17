<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Conversation;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;

class MessageReceived extends Message
{
    public function __construct(string|AbstractComponent $content, string $author, string $date, string $avatar)
    {
        parent::__construct($content, $author, $date, $avatar, MessageType::RECEIVED);
    }
}