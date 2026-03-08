<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const generalSettings = computed(() => page.props.generalSettings);

defineProps<{
    title?: string;
    description?: string;
}>();

const companyName = computed(
    () => generalSettings.value?.name || 'أميركو العالمية المحدودة',
);
const logoUrl = computed(() => {
    const settings = generalSettings.value;
    const logo = settings?.logo;
    return logo ? `/image/logo/${logo}` : '/image/login/bayan.jpg';
});
</script>

<template>
    <div class="bg-primary-transparent flex min-h-svh w-full">
        <!-- Login Form Side -->
        <div class="flex w-full bg-white md:w-5/12">
            <div class="login flex w-full items-center py-2">
                <div class="w-full px-0">
                    <div class="flex w-full">
                        <div class="mx-auto w-full max-w-md">
                            <div class="card-sigin">
                                <div class="mb-5 flex">
                                    <h1
                                        class="main-logo1 tx-28 my-auto mr-0 ml-1"
                                    >
                                        <span>{{ title || companyName }}</span>
                                    </h1>
                                </div>
                                <div class="card-sigin">
                                    <div class="main-signup-header">
                                        <h2>مرحبــــاً بك</h2>
                                        <h5 class="font-weight-semibold mb-4">
                                            {{ description }}
                                        </h5>
                                        <slot />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Side -->
        <div
            class="bg-primary-transparent hidden items-center justify-center md:flex md:w-7/12"
        >
            <div class="flex w-full text-center">
                <div class="mx-auto my-auto w-full">
                    <img
                        :src="logoUrl"
                        class="mx-auto h-auto max-h-[80vh] w-full max-w-[80%]"
                        alt="logo"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
