const mgVuePageControler = function (controlerId)
{
    //main app container id
    this.vueLoaderId = controlerId;
    //main app instance
    this.vueLoader = false;
    
    //main app instance init
    this.vinit = function () {
        const self = this;
        Vue.use(Vuex);
        self.vueLoader = new Vue(self.getVueAppInits());
    };
    
    //prepare main app config object
    this.getVueAppInits = function () {
        const vAppId = this.vueLoaderId;
        const newVueAppConfig = mgDefaultVueObject;

        newVueAppConfig.el = '#' + vAppId;
        newVueAppConfig.data.targetId = vAppId;

        if (typeof precompiledMode != "undefined" && precompiledMode)
        {
            if (typeof compiledTemplates == 'object' && Object.keys(compiledTemplates).length > 0)
            {
                for (const [componentName, content] of Object.entries(compiledTemplates))
                {
                    if (typeof vueComponents[componentName] == "undefined")
                    {
                        console.error("No component defined for compiled template: " + componentName);
                        continue;
                    }

                    vueComponents[componentName].render = content.render;
                    vueComponents[componentName].staticRenderFns = content.staticRenderFns;

                    delete vueComponents[componentName].template;
                }
            }
            else
            {
                console.error("The Precompiled Mode is enabled, but compiled output file was no generated correctly. Run the build:assets command.");
            }
        }

        //build in AssetsBuilder
        newVueAppConfig.components = vueComponents;

        newVueAppConfig.store = new Vuex.Store({
            state: {
                componentsParams: {},
                overlayComponents: [],
                ...vueStoreData
            },
            getters: {
                getOverlayComponents: (state) => {
                    return state.overlayComponents;
                },
                getComponentParams: (state) => (id) => {
                    return state.componentsParams[id] ? state.componentsParams[id] : null;
                }
            },
            mutations: {
                setOverlayComponent(state, payload)
                {
                    state.overlayComponents = [
                        {
                            component: payload.component,
                            parent: payload.parent ? payload.parent : null
                        }
                    ];
                },
                clearOverlayComponent(state)
                {
                    state.overlayComponents = []
                },
                setComponentParams(state, payload)
                {
                    state.componentsParams[payload.id] = payload.data;
                }
            }
        });

        return newVueAppConfig;
    }
}
