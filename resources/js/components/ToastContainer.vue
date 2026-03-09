<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, XCircle, Info, X } from 'lucide-vue-next';

interface Toast {
    id: number;
    type: 'success' | 'error' | 'info';
    message: string;
}

const page = usePage<{ flash?: { success?: string; error?: string } }>();
const toasts = ref<Toast[]>([]);
let toastId = 0;
let lastFlash = '';

const addToast = (type: Toast['type'], message: string) => {
    const id = ++toastId;
    toasts.value.push({ id, type, message });
    setTimeout(() => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    }, 4000);
};

const removeToast = (id: number) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
};

const checkFlash = () => {
    const flash = page.props.flash;
    if (!flash) return;

    const message = flash.success || flash.error;
    if (message && message !== lastFlash) {
        lastFlash = message;
        addToast(flash.success ? 'success' : 'error', message);
    }
};

onMounted(() => {
    checkFlash();
});

watch(
    () => page.props.flash,
    () => {
        checkFlash();
    },
    { deep: true },
);
</script>

<template>
    <Teleport to="body">
        <div class="toast-container">
            <TransitionGroup name="toast">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    class="toast"
                    :class="toast.type"
                >
                    <CheckCircle
                        v-if="toast.type === 'success'"
                        class="h-5 w-5"
                    />
                    <XCircle
                        v-else-if="toast.type === 'error'"
                        class="h-5 w-5"
                    />
                    <Info v-else class="h-5 w-5" />
                    <span>{{ toast.message }}</span>
                    <button @click="removeToast(toast.id)" class="toast-close">
                        <X class="h-4 w-4" />
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style>
.toast-container {
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.toast {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    min-width: 280px;
    max-width: 400px;
    font-size: 0.875rem;
    direction: rtl;
}

.toast.success {
    background-color: #10b981;
    color: white;
}

.toast.error {
    background-color: #ef4444;
    color: white;
}

.toast.info {
    background-color: #3b82f6;
    color: white;
}

.toast-close {
    margin-right: auto;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.25rem;
    opacity: 0.8;
}

.toast-close:hover {
    opacity: 1;
}

.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(-100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>
