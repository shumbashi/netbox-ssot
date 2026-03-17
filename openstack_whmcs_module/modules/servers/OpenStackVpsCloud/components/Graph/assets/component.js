var component =
    {
        extends: BaseDataComponent,
        mixins: [AjaxMixin],
        template: '#template-name#',
        props: [
            'options',
            'width',
            'height',
            'title',
            'icon',
            'config',
            'elements',
            'isInline'
        ],
        data: function () {
            return {
                options_: {},
                chart_: null,
                width_: '',
                height_: '',
                title_: '',
                icon_: '',
                config_: null,
                elements_: [],
                isInline_: false,
                formatters: {
                    showOnlyIntegers: function (val) {
                        return Number.isInteger(val) ? val : "";
                    }
                },
            };
        },
        mounted(){
            this.loadComponentScript('Graph', 'js/chart.min.js');
        },
        created: function () {
            this.waitFor('ApexCharts').then(() => {
                this.createChart();
            });
        },
        computed: {
            iconClass: function () {
            
            },

            showHeader: function () {
                return this.hasTitle() || this.hasElements();
            },

            isSparkline: function () {
                return this.options_.chart && this.options_.chart.sparkline && this.options_.chart.sparkline.enabled;
            },
        },
        methods: {
            ajaxLoaded_: function () {
                this.updateChart();
            },

            hasTitle: function () {
                return typeof this.title_ != "undefined" && this.title_.length > 0;
            },

            hasElements: function () {
                return typeof this.elements_.elements != "undefined" && this.elements_.elements.length > 0;
            },

            onReload: function (input = null) {
                this.propagateSlotsData_(input.slots ? input.slots : []);

                this.config_ = input.data ? input.data : {};
                this.loadDataFromServer_(this.config_, this.ajaxData_).then(this.updateChart);
            },
            
            createChart: function () {
                this.$nextTick(function () {

                    this.fixDataStructure(this.options_);
                    this.chart_ = new ApexCharts(document.querySelector("#" + this.cid_ + " .chartCanvas"), this.options_);
                    this.chart_.render();

                    this.updateChart();
                });
            },
            
            updateChart: function () {
                if (this.chart_)
                {
                    this.fixDataStructure(this.options_);
                    this.chart_.updateOptions(this.options_);
                }
            },

            fixDataStructure: function (options) {
                for (const key in options) {
                    if (options.hasOwnProperty(key)) {
                        if (typeof options[key] === 'object' && options[key] !== null)
                        {
                            this.fixDataStructure(options[key]);
                            continue;
                        }

                        if (typeof options[key] === 'string' && options[key].startsWith('function'))
                        {
                            options[key] = this.parseOptionAsRawFunction(options[key]);
                            continue;
                        }

                        if (typeof options[key] === 'string' && options[key].startsWith('formatter'))
                        {
                            options[key] = this.parseOptionAsFormatter(options[key]);
                        }
                    }
                }
            },
            parseOptionAsRawFunction: function (option) {
                try {
                    return new Function('return ' + option)();
                } catch (e) {
                    return option;
                }
            },
            parseOptionAsFormatter: function (option) {
                const formatterName = option.split('.')[1];
                if (this.formatters[formatterName] && typeof this.formatters[formatterName] === 'function') {
                    return this.formatters[formatterName];
                }
                return option;
            },
        }
    }