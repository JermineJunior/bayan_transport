<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';

interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    links: Link[];
}

defineProps<Props>();
</script>

<template>
    <div v-if="links.length > 3" class="flex items-center justify-center gap-1 py-4">
        <template v-for="(link, index) in links" :key="index">
            <div v-if="link.url === null" v-html="link.label" class="px-3 py-2 text-sm text-muted-foreground" />
            <Link v-else :href="link.url" preserve-state>
                <Button
                    :variant="link.active ? 'default' : 'ghost'"
                    size="sm"
                    class="min-w-9"
                    v-html="link.label"
                />
            </Link>
        </template>
    </div>
</template>
