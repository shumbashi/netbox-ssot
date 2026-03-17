<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Arr;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\EmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\EmailTemplates\Source\AbstractEmailTemplate;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Enums\MessageTypes;
use ModulesGarden\OpenStackVpsCloud\Packages\Mailer\Models\Participants\RecipientWithModel;

class TemplatesFactory
{
    const TEMPLATES_DIR = 'Templates';

    public static function byName($templateName, $relId): AbstractEmailTemplate
    {
        $model = EmailTemplate::name($templateName)->first();
        if (!$model)
        {
            throw new \Exception("Template not found: $templateName");
        }

        if ($language = static::resolveLanguage($model, $relId))
        {
            $languageModel = EmailTemplate::name($templateName)
                ->where('language', $language)
                ->first();

            if ($languageModel) {
                $model = $languageModel;
            }
        }

        return self::fromModel($model, $relId);
    }

    public static function byId($templateId, $relId):AbstractEmailTemplate
    {
        $model = EmailTemplate::find($templateId);

        return self::fromModel($model, $relId);
    }

    public static function fromModel(EmailTemplate $emailTemplateModel, $relId):AbstractEmailTemplate
    {
        $className = __NAMESPACE__ . '\\' .self::TEMPLATES_DIR . '\\' . ucfirst($emailTemplateModel->type);

        if (!class_exists($className))
        {
            throw new \Exception("Email template not found. Provided template: {$emailTemplateModel->name}");
        }

        $templateObject = new $className($relId, $emailTemplateModel);

        if (!is_a($templateObject,AbstractEmailTemplate::Class ))
        {
            throw new \Exception('Invalid email template found. Template must be instance of AbstractEmailTemplate');
        }

        return $templateObject;
    }

    private static function resolveLanguage($model, $relId)
    {
        if ($model->type === MessageTypes::TYPE_ADMIN) {
            $systemLanguage = \WHMCS\Config\Setting::getValue("Language");
            if (empty($systemLanguage)) {
                return null;
            }
            return $systemLanguage;
        }

        $template = self::fromModel($model, $relId);
        $recipient = $template->getRecipient();
        if ($recipient instanceof RecipientWithModel && $recipient->getModel())
        {
            $language = $recipient->getModel()->language;
        }
        else
        {
            $language = Arr::get($template->getRelatedParams(), 'client_language');
        }

        if (empty($language)) {
            return null;
        }

        return $language;
    }
}