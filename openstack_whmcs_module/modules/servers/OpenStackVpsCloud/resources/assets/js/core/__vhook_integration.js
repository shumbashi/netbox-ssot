const mgIntegrationHelper = {
    //help determine what part of app should be integrated
    getIngegrationInsertType: function (isIntegration) {
        const allowedInsertTypes = ['full', 'content', 'mc_content'];
        const intParam = $(isIntegration).attr('mg-integration-insert-type');
        if (allowedInsertTypes.includes(intParam))
        {
            return intParam;
        } else
        {
            return 'full';
        }
    },
    //get integration HTML code to be passed on the page
    getIngegrationCode: function (selector, integrationInsertType) {
        if (integrationInsertType === 'full')
        {
            return $(selector).parents('.mg-integration-container').children('#layers2').first()[0].outerHTML;
        } else if (integrationInsertType === 'mc_content')
        {
            return $(selector)[0].innerHTML;
        } else
        {
            return $(selector)[0].outerHTML;
        }
    },
    removeOldContainer: function (selector, integrationInsertType, integrationTarget) {
        return $(selector)[0].remove();
    },
    afterInsertActions: function (selector, integrationInsertType, integrationTarget) {
        if (integrationInsertType === 'full')
        {
            const modalContId = $('#' + $(selector).attr('id') + '_modal');
            $(integrationTarget).find(modalContId).first().remove();
        }
    }
}

mgEventHandler.on('AppsPreLoad', null, function (id, params) {
    if (typeof params.appContainers !== 'undefined')
    {
        //check all app container, looking for integration containers
        for (var key in params.appContainers)
        {
            if (!params.appContainers.hasOwnProperty(key))
            {
                continue;
            }
            
            //each integration container needs to have 'mg-integration-container' class
            var isIntegration = $(params.appContainers[key]).parents('.mg-integration-container');
            
            if (isIntegration.length === 1)
            {
                const integrationInsertType = mgIntegrationHelper.getIngegrationInsertType(isIntegration);
                const tempIntCode = mgIntegrationHelper.getIngegrationCode(params.appContainers[key], integrationInsertType);
                const integrationType = $(isIntegration).attr('mg-integration-type');
                let integrationTarget = $(isIntegration).attr('mg-integration-target');
                const relatedFieldSelector = $(isIntegration).attr('mg-integration-related-field-selector');
                const relatedFieldValues = $(isIntegration).attr('mg-integration-related-field-values').split(',');

                if (integrationTarget && integrationTarget !== 'null')
                {
                    const integrationTargetToDelete = $(isIntegration).attr('mg-integration-target');
                    integrationTarget = integrationTarget.split(',').find((selector) => $(selector).length > 0);
        
                    if (typeof integrationTarget === 'undefined')
                    {
                        $("[mg-integration-target='" + integrationTargetToDelete +"']").remove();
                        continue;
                    }
                }
                
                if (integrationType === 'append')
                {
                    mgIntegrationHelper.removeOldContainer(params.appContainers[key], integrationInsertType, integrationTarget);
                    $(integrationTarget).first().append(tempIntCode);
                    mgIntegrationHelper.afterInsertActions(params.appContainers[key], integrationInsertType, integrationTarget);
                } else if (integrationType === 'replace')
                {
                    mgIntegrationHelper.removeOldContainer(params.appContainers[key], integrationInsertType, integrationTarget);
                    $(integrationTarget).first().replaceWith(tempIntCode);
                    mgIntegrationHelper.afterInsertActions(params.appContainers[key], integrationInsertType, integrationTarget);
                } else if (integrationType === 'after')
                {
                    mgIntegrationHelper.removeOldContainer(params.appContainers[key], integrationInsertType, integrationTarget);
                    $(integrationTarget).first().after(tempIntCode);
                    mgIntegrationHelper.afterInsertActions(params.appContainers[key], integrationInsertType, integrationTarget);
                } else if (integrationType === 'before')
                {
                    mgIntegrationHelper.removeOldContainer(params.appContainers[key], integrationInsertType, integrationTarget);
                    $(integrationTarget).first().before(tempIntCode);
                    mgIntegrationHelper.afterInsertActions(params.appContainers[key], integrationInsertType, integrationTarget);
                } else if (integrationType === 'prepend')
                {
                    mgIntegrationHelper.removeOldContainer(params.appContainers[key], integrationInsertType, integrationTarget);
                    $(integrationTarget).first().prepend(tempIntCode);
                    mgIntegrationHelper.afterInsertActions(params.appContainers[key], integrationInsertType, integrationTarget);
                } else if (integrationType === 'custom')
                {
                    const contId = $(params.appContainers[key]).attr('id');
                    const integrationFunction = $(isIntegration).attr('mg-integration-function');
                    if (integrationFunction && typeof window[integrationFunction] === "function")
                    {
                        window[integrationFunction](integrationTarget, contId);
                    }
                    if (integrationTarget !== 'null')
                    {
                        $(params.appContainers[key])[0].remove();
                        $(integrationTarget).addClass('modulesgarden-app-main-container');
                        if (typeof $(integrationTarget).attr('id') === 'undefined')
                        {
                            $(integrationTarget).attr('id', contId);
                        }
                    }
                }

                if (relatedFieldSelector)
                {
                    const relatedField = $(relatedFieldSelector.split(',').find((selector) => $(selector).length > 0));

                    if (relatedField.length > 0)
                    {
                        const checkRelatedValue = function () {
                            const selectedValue = relatedField.val();
                            const foundValue = relatedFieldValues.find(value => value === selectedValue);
                            const integrationContainer = $("#" + $(params.appContainers[key]).attr('id'));

                            if (integrationContainer)
                            {
                                const visible = integrationContainer.css("display") !== "none";
                                if (visible === (foundValue !== undefined)) {
                                    return;
                                }

                                if (foundValue) {
                                    integrationContainer.show();
                                } else {
                                    integrationContainer.hide();
                                }
                            }
                        }

                        checkRelatedValue();
                        setInterval(checkRelatedValue, 500);
                    }
                }
            }
        }
    }
}, 3000);
