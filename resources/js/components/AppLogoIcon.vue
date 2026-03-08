<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

const props = defineProps<Props>();

const resolvedClass = () => {
    return props.className || '';
};
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 40 42"
        :class="resolvedClass()"
        v-bind="$attrs"
    ></svg>
</template>
