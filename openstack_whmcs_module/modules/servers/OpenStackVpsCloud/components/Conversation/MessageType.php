<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Conversation;

enum MessageType: string
{
    case SENT = 'sent';
    case RECEIVED = 'received';
}