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
                messages_: [],
            }
        },
        props: [
            'text',
            'css',
            'elements',
            'content',
            'messages',
        ],
        created: function () {
        },
        
        methods: {
            getType: function (message) {
                console.log(message.type);
                return message.type === 'sent' ? 'success' : 'danger';
            },
            getAlignment: function (message) {
                return message.type === 'sent' ? 'lu-m-r-2x' : 'lu-m-l-2x';
            }
        },
    }