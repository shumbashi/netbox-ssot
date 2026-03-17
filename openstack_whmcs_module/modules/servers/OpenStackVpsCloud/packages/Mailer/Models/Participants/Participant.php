<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants;

abstract class Participant
{
    public const TYPE_ADMIN     = "admin";
    public const TYPE_CLIENT    = "client";
    public const TYPE_USER      = "user";
    public const TYPE_CUSTOM    = "custom";

    protected string $email;
    protected string $name;
    protected string $type;
    protected ?int $relId;

    public function __construct(string $email, string $name = "", string $type = self::TYPE_CUSTOM, ?int $relId = null)
    {
        $this->email = mb_strtolower(trim($email));
        $this->name = trim($name);
        $this->type = $type;
        $this->relId = $relId;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): self
    {
        $this->email = mb_strtolower($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param int $relId
     */
    public function setRelId(int $relId): self
    {
        $this->relId = $relId;

        return $this;
    }

    /**
     * @return ?int
     */
    public function getRelId(): ?int
    {
        return $this->relId;
    }
}