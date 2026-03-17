<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Enums;

class MessageTypes
{
    const TYPE_ADMIN        = "admin";
    const TYPE_USER         = "user";
    const TYPE_GENERAL      = "general";
    const TYPE_PRODUCT      = "product";
    const TYPE_DOMAIN       = "domain";
    const TYPE_INVOICE      = "invoice";
    const TYPE_INVITE       = "invite";
    const TYPE_SUPPORT      = "support";
    const TYPE_AFFILIATE    = "affiliate";
    const TYPE_NOTIFICATION = "notification";

    public static function getTypes():array
    {
        $reflectionClass = new \ReflectionClass(self::class);
        return $reflectionClass->getConstants();
    }

}