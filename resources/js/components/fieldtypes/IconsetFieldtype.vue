<template>
    <div class="flex iconset-fieldtype-wrapper">
        <v-select
            v-if="!loading"
            ref="input"
            class="w-full"
            append-to-body
            clearable
            :name="name"
            :disabled="config.disabled || isReadOnly"
            :options="options"
            :placeholder="__(config.placeholder || 'pixelkode-iconsets::messages.ui.search_placeholder')"
            :searchable="true"
            :multiple="false"
            :close-on-select="true"
            :value="selectedOption"
            :selectable="option => !option.isHeader"
            :create-option="(value) => ({ value, label: value })"
            @input="vueSelectUpdated"
            @search:focus="$emit('focus')"
            @search:blur="$emit('blur')">
            <template slot="option" slot-scope="option">
                <div v-if="option.isHeader" class="font-bold text-xs uppercase text-gray-600 dark:text-dark-100 py-1 px-2 bg-gray-100 dark:bg-dark-600">
                    {{ option.label }}
                </div>
                <div v-else class="icon-option-grid py-1 px-2">
                    <div v-if="option.html" v-html="option.html" class="icon-container" />
                    <span class="text-xs text-gray-800 dark:text-dark-150 truncate">{{ option.label }}</span>
                </div>
            </template>
            <template slot="selected-option" slot-scope="option">
                <div class="icon-option-grid">
                    <div v-if="option.html" v-html="option.html" class="icon-container" />
                    <span class="text-xs text-gray-800 dark:text-dark-150 truncate">{{ option.label }}</span>
                </div>
            </template>
        </v-select>
        <div v-else class="text-xs text-gray-600 dark:text-dark-150 py-2">
            {{ __('pixelkode-iconsets::messages.ui.loading') }}
        </div>
    </div>
</template>

<script>
import { ref, watch } from 'vue';

const iconsCache = ref({});
const loaders = ref({});

export default {
    mixins: [Fieldtype],

    data() {
        return {
            icons: {},
            loading: true,
        }
    },

    computed: {
        cacheKey() {
            const directory = this.meta.directory || 'resources/svg/icons';
            const folders = (this.meta.folders || []).join(',');
            return `${directory}/${folders}`;
        },

        options() {
            let options = [];

            // Use the folder order from config (not alphabetical)
            const folders = this.meta.folders || [];

            folders.forEach(folder => {
                // Skip if this folder has no icons
                if (!this.icons[folder]) {
                    return;
                }

                // Add folder header
                options.push({
                    label: this.formatFolderName(folder),
                    value: null,
                    isHeader: true,
                });

                // Add icons from this folder (sorted alphabetically within folder)
                const folderIcons = this.icons[folder];
                Object.keys(folderIcons).sort().forEach(filename => {
                    options.push({
                        value: `${folder}/${filename}`,
                        label: filename,
                        html: folderIcons[filename],
                        folder: folder,
                        isHeader: false,
                    });
                });
            });

            return options;
        },

        selectedOption() {
            return this.options.find(option => !option.isHeader && option.value === this.value) || null;
        }
    },

    created() {
        this.request();

        watch(
            () => loaders.value[this.cacheKey],
            (loading) => {
                this.icons = iconsCache.value[this.cacheKey] || {};
                this.loading = loading;
            }
        );
    },

    methods: {
        focus() {
            this.$refs.input?.focus();
        },

        vueSelectUpdated(value) {
            if (value && !value.isHeader) {
                this.update(value.value);
            } else {
                this.update(null);
            }
        },

        request() {
            if (loaders.value[this.cacheKey]) return;

            loaders.value = {...loaders.value, [this.cacheKey]: true};

            this.$axios.post(this.meta.url, {
                config: utf8btoa(JSON.stringify(this.config)),
            }).then(response => {
                const icons = response.data.icons;
                this.icons = icons;
                iconsCache.value = {...iconsCache.value, [this.cacheKey]: icons};
            })
            .catch(error => {
                console.error('Failed to load icons:', error);
                this.icons = {};
            })
            .finally(() => {
                loaders.value = {...loaders.value, [this.cacheKey]: false};
            });
        },

        formatFolderName(folder) {
            // Convert folder names to title case for display
            return folder
                .split(/[-_]/)
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    }
};
</script>

<style scoped>
.iconset-fieldtype-wrapper >>> .vs__dropdown-option--disabled {
    cursor: default;
    background-color: transparent;
}

.iconset-fieldtype-wrapper >>> .vs__dropdown-option--disabled:hover {
    background-color: transparent;
}

/* Grid layout for icon + label */
.icon-option-grid {
    display: grid;
    grid-template-columns: 1.25rem 1fr;
    gap: 0.75rem;
    align-items: center;
}

/* Icon container - fixed size */
.icon-container {
    width: 1.25rem;
    height: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-container >>> svg {
    width: 1.25rem;
    height: 1.25rem;
}

/* Make placeholder span both columns in selected option */
.iconset-fieldtype-wrapper >>> .vs__selected-options .vs__search::placeholder {
    grid-column: 1 / -1;
}

/* Also handle the case when no value is selected */
.iconset-fieldtype-wrapper >>> .vs__selected .vs__search {
    grid-column: 1 / -1;
}
</style>
