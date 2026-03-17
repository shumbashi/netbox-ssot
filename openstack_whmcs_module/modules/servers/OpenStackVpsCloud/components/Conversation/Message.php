<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Conversation;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;

class Message implements \JsonSerializable
{
    protected string|AbstractComponent $content;
    protected string $date;
    protected string $author;
    protected string $type;
    protected string $avatar;

    public function __construct(string|AbstractComponent $content, string $author, string $data, string $avatar, MessageType $type)
    {
        $this->setContent($content);
        $this->setAuthor($author);
        $this->setDate($data);
        $this->setAvatar($avatar);
        $this->setType($type);
    }

    public function setContent(string|AbstractComponent $content): Message
    {
        $this->content = $content;
        return $this;
    }

    public function setDate(string $date): Message
    {
        $this->date = $date;
        return $this;
    }

    public function setAuthor(string $author): Message
    {
        $this->author = $author;
        return $this;
    }

    public function setType(MessageType $type): Message
    {
        $this->type = $type->value;
        return $this;
    }

    public function setAvatar(string $avatar): Message
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content,
            'date'    => $this->date,
            'author'  => $this->author,
            'type'    => $this->type,
            'avatar'  => $this->avatar,
        ];
    }
}