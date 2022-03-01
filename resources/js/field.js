import FormField from './components/FormField'
import IndexField from './components/IndexField'

App.booting((Vue, router) => {
    Vue.component('form-tags-field', FormField);
    Vue.component('index-tags-field', IndexField);
})
