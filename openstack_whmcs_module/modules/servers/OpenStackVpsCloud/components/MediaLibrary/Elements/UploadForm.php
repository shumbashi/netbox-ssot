<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\MediaLibrary\Elements;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;


abstract class UploadForm extends Form
{
    protected const UPLOAD_ACTION = 'upload';
    protected $providerAction = self::UPLOAD_ACTION;

 
}
