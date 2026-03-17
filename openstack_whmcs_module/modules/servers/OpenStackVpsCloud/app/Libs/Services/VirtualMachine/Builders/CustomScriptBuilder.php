<?php


namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Builders;


use Exception;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Smarty\SmartyService;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Order;
use Smarty;
use Symfony\Component\Yaml\Yaml;

class CustomScriptBuilder extends BaseBuilder
{
    /**
     * @var SmartyService
     */
    protected $smartyService;

    /**
     * CustomScriptBuilder constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->smartyService = new SmartyService();

        parent::__construct($params);
    }

    /**
     * @return array|string
     */
    public function build(string $userData = '')
    {
        $scripts = $this->getConfigOptionsWithScriptTag();

        $scriptContents = $userData . $this->getScriptFromFiles($scripts);

        $smarty = $this->setSmarty();

        if (isset($this->params['domain']))
        {
            $domain              = explode('.', $this->params['domain'], 2);
            $this->params['sld'] = $domain[0];
            $this->params['tld'] = $domain[1];
        }

        $scriptContents = $smarty->fetch('string:' . html_entity_decode($scriptContents, ENT_QUOTES, "UTF-8"));

        $scripts = array_map(function ($v) {
            return "#" . $v;
        }, array_slice(explode("\zn#", $scriptContents), 1));

        if (empty($scripts) && !empty($scriptContents))
        {
            $scripts[] = $scriptContents;
        }

        $cloudConfig  = [];
        $otherScripts = '';
        foreach ($scripts as $script)
        {
            if (preg_match('/^(#cloud-config.*)$/m', $script))
            {
                $cloudConfig = array_merge_recursive(Yaml::parse($script, Yaml::PARSE_CONSTANT | Yaml::PARSE_CUSTOM_TAGS), $cloudConfig);
            }
            else
            {
                $otherScripts .= $script;
            }
        }

        $cloudConfig = (count($cloudConfig) > 0) ? "#cloud-config\n" . html_entity_decode(Yaml::dump($cloudConfig, 4, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK), ENT_HTML5, "UTF-8") : '';
        $cloudConfig = $cloudConfig . htmlspecialchars_decode($otherScripts, ENT_QUOTES);

        return $cloudConfig;
    }


    /**
     * @return array
     */
    public function getConfigOptionsWithScriptTag()
    {
        return array_filter($this->params['configoptions'], function ($k) {
            return (bool)preg_match('/^script_/i', $k);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $scripts
     * @return string
     */
    public function getScriptFromFiles(array $scripts)
    {
        $scriptContents = '';

        foreach ($scripts as $key => $script)
        {
            if (is_int($script) && $script == 1)
            {
                $script = $key;
            }
            $file = ModuleConstants::getModuleRootDir() . '/scripts/' . basename($script);
            if (file_exists($file))
            {
                $scriptContents .= "\n" . file_get_contents($file) . "\n";
            }
        }

        return $scriptContents;
    }

    /**
     * @return Smarty
     */
    public function setSmarty()
    {
        $clients                        = Client::find($this->params['userid']);
        $this->params['clientsdetails'] = array_merge($this->params['clientsdetails'], (array)$clients);

        $order                 = Order::find($this->params['model']->orderid);
        $this->params['order'] = (array)$order;


        return $this->smartyService->assign($this->params);

    }
}
