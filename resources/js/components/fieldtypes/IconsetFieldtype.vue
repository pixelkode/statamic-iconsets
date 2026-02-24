<template>
    <div class="flex iconset-fieldtype-wrapper">
        <div v-if="!loading" class="w-full">
            <select
                ref="input"
                class="input-text w-full"
                :name="name"
                :disabled="config.disabled || isReadOnly"
                :value="value"
                @change="handleChange"
                @focus="$emit('focus')"
                @blur="$emit('blur')"
            >
                <option value="">{{ __(config.placeholder || 'pixelkode-iconsets::messages.ui.search_placeholder') }}</option>
                <template v-for="option in options" :key="option.value ?? option.label">
                    <optgroup v-if="option.isHeader" :label="option.label" />
                    <option v-else :value="option.value">{{ option.label }}</option>
                </template>
            </select>
        </div>
        <div v-else class="text-xs text-gray-600 dark:text-dark-150 py-2">
            {{ __('pixelkode-iconsets::messages.ui.loading') }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    ...Fieldtype.props,
});

const emit = defineEmits([...Fieldtype.emits]);

const { expose, update } = Fieldtype.use(emit, props);
defineExpose(expose);

const iconsCache = ref({});
const loaders = ref({});

const icons = ref({});
const loading = ref(true);

const cacheKey = computed(() => {
    const directory = props.meta.directory || 'resources/svg/icons';
    const folders = (props.meta.folders || []).join(',');
    return `${directory}/${folders}`;
});

const options = computed(() => {
    const result = [];
    const folders = props.meta.folders || [];

    folders.forEach(folder => {
        if (!icons.value[folder]) return;

        result.push({
            label: formatFolderName(folder),
            value: null,
            isHeader: true,
        });

        const folderIcons = icons.value[folder];
        Object.keys(folderIcons).sort().forEach(filename => {
            result.push({
                value: `${folder}/${filename}`,
                label: filename,
                html: folderIcons[filename],
                folder,
                isHeader: false,
            });
        });
    });

    return result;
});

function formatFolderName(folder) {
    return folder
        .split(/[-_]/)
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

function handleChange(event) {
    const val = event.target.value;
    update(val || null);
}

function focus() {
    expose.input?.focus();
}

function request() {
    if (loaders.value[cacheKey.value]) return;

    if (iconsCache.value[cacheKey.value]) {
        icons.value = iconsCache.value[cacheKey.value];
        loading.value = false;
        return;
    }

    loaders.value = { ...loaders.value, [cacheKey.value]: true };

    Statamic.$axios.post(props.meta.url, {
        config: utf8btoa(JSON.stringify(props.config)),
    }).then(response => {
        const data = response.data.icons;
        icons.value = data;
        iconsCache.value = { ...iconsCache.value, [cacheKey.value]: data };
    }).catch(error => {
        console.error('Failed to load icons:', error);
        icons.value = {};
    }).finally(() => {
        loaders.value = { ...loaders.value, [cacheKey.value]: false };
        loading.value = false;
    });
}

watch(
    () => loaders.value[cacheKey.value],
    (isLoading) => {
        icons.value = iconsCache.value[cacheKey.value] || {};
        if (isLoading !== undefined) loading.value = isLoading;
    }
);

onMounted(() => {
    request();
});
</script>

<style scoped>
.icon-option-grid {
    display: grid;
    grid-template-columns: 1.25rem 1fr;
    gap: 0.75rem;
    align-items: center;
}

.icon-container {
    width: 1.25rem;
    height: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-container :deep(svg) {
    width: 1.25rem;
    height: 1.25rem;
}
</style>
