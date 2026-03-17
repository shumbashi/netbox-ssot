const EventsListMixin =
    {
        created()
        {
            this.$root.$eventManager().onReload(this, this.onReload, this.cid);
            this.$root.$eventManager().onPassAjaxData(this, this.onPassAjaxData, this.cid);
            //this.$root.$eventManager().onGetAjaxData(this, this.onGetAjaxData, this.cid);
        },
        updated()
        {
            this.$emit('updated', this);
        },
        methods: {
            onReload: function (data = {}) {
                this.propagateSlotsData_(data.slots ? data.slots : []);
                this.loadDataFromServer_({}, this.ajaxData_);
            },
            onPassAjaxData: function (data = {}) {
                this.propagateSlotsData_({
                    'ajaxData': data
                });
            },
            resetErrors: function () {
                this.$root.$eventManager().resetFieldErrors()
            }
        },
        beforeDestroy()
        {
            this.$root.$eventManager().clear(this);
        },
    }
