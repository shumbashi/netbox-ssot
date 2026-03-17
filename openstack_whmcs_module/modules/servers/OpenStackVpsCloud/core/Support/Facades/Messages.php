<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Support\Facades;


/**
 * @see \ModulesGarden\OpenStackVpsCloud\Core\Services\Messages
 * @method static alert(string $message)
 * @method static toast(string $message)
 * @method static flash(string $message)
 */
class Messages extends AbstractFacade
{
    protected static function getFacadeAccessor(): string
    {
        return \ModulesGarden\OpenStackVpsCloud\Core\Services\Messages::class;
    }
}
