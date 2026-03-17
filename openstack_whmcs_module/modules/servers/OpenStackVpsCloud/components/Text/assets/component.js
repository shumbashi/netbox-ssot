var component =
    {
        extends: BaseDataComponent,
        template: '#template-name#',
        data: function () {
            return {
                text_: '',
                css_: '',
                elements_: '',
                noWrap_: false
            }
        },
        props: [
            'text',
            'css',
            'elements',
            'noWrap'
        ],
        created: function () {
        },
    }