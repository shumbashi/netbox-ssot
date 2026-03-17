var component =
    {
        extends: BaseDataComponent,
        template: '#template-name#',
        data: function () {
            return {
                text_: '',
                css_: '',
                elements_: '',
                content_: '',
                pointer_: 0
            }
        },
        props: [
            'text',
            'css',
            'elements',
            'content',
            'pointer'
        ],
        methods: {
            incrementPointer: function () {
                this.pointer_++;
                if (this.pointer_ >= this.elements_.elements.length)
                {
                    this.pointer_ = 0;
                }
            }
        },

    }