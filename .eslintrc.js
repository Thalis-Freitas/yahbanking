module.exports = {
    'extends': [
        'plugin:vue/vue3-recommended',
    ],
    'parserOptions': {
        'ecmaVersion': 'latest',
        'sourceType': 'module'
    },
    'plugins': [
        'vue'
    ],
    'rules': {
        'indent': [
            'error',
            4
        ],
        'linebreak-style': [
            'error',
            'unix'
        ],
        'quotes': [
            'error',
            'single'
        ],
        'semi': [
            'error',
            'always'
        ],
        'vue/html-indent': [
            'error',
            4
        ],
        'vue/multi-word-component-names': 'off',
        'vue/no-mutating-props': 'off',
        'vue/valid-v-slot': 'off',
        'vue/no-textarea-mustache': 'off'
    }
};
