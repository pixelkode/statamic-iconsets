import IconsetFieldtype from './components/fieldtypes/IconsetFieldtype.vue';

Statamic.booting(() => {
    Statamic.$components.register('iconset-fieldtype', IconsetFieldtype);
});
