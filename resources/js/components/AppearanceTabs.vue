<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const { appearance, updateAppearance } = useAppearance();

const tabs = computed(() => [
    { value: 'light', Icon: Sun, label: t.value.light_mode || 'فاتح' },
    { value: 'dark', Icon: Moon, label: t.value.dark_mode || 'داكن' },
    { value: 'system', Icon: Monitor, label: t.value.system || 'النظام' },
]);
</script>

<template>
    <div class="flex flex-col gap-1 rounded-lg bg-muted p-1">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-4 py-2 text-sm font-medium transition-all',
                appearance === value
                    ? 'bg-background text-foreground shadow-sm'
                    : 'text-muted-foreground hover:bg-background/50 hover:text-foreground',
            ]"
        >
            <component :is="Icon" class="ml-2 h-4 w-4" />
            {{ label }}
        </button>
    </div>
</template>
