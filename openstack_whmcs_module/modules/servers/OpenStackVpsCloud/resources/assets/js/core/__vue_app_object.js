const mgDefaultVueObject = {
    el: null,//'#'+controlerId,
    data: {
        pageLoading: false,
        loading: false,
        pagePreLoader: null,
        rootElements: rootElements,
    },
    created: function () {
        this.installPlugins();
    },
    methods: {
        installPlugins: function () {
            ResponseValidator.install(this);
            EventManager.install(this);
            ActionHandler.install(this);
            RequestHandler.install(this);
            AlertManager.install(this);
        }
    }
};
