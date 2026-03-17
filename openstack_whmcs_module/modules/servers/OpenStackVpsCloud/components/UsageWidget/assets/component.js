var component =
    {
        extends: BaseDataComponent,
        mixins: [],
        template: '#template-name#',
        props: [
            "css",
            "elements",
            "title",
            "icon",
            "visualisationElement",
            "usage",
            "limit",
            "unit",
            "disableLabels",
        ],
        data()
        {
            return {
                css_: [],
                elements_: [],
                title_: '',
                icon_: '',
                visualisationElement_: null,
                usage_: 0,
                limit_: 0,
                unit_: '',
                disableLabels_: false,
            }
        },
        mounted()
        {
        },
        created()
        {
        },
        computed: {
            iconClass: function () {
                return this.icon_ ? 'mdi-' + this.icon_ : ''
            },
            currentLabel: function () {
                return this.translations_['current_usage'] + ' ' + this.usage_ + ' ' + this.unit_
            },
            limitLabel: function () {
                return this.translations_['limit_usage'] + ' ' + this.limit_ + ' ' + this.unit_
            }
        },
        methods: {
        }
    }