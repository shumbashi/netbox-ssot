<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Smarty;


use Smarty;

/**
 * Class SmartyService
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Smarty
 */
class SmartyService
{
    /**
     * @var Smarty
     */
    protected $smarty;

    /**
     * SmartyService constructor.
     */
    public function __construct()
    {
        $this->smarty = new Smarty;
        global $templates_compiledir;
        if($templates_compiledir)
        {
            $this->smarty->compile_dir = $templates_compiledir;
        }
    }

    /**
     * @param array $vars
     * @return Smarty
     */
    public function assign(array $vars)
    {
        foreach ($vars as $key => $value)
        {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty;
    }
}