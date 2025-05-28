<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Preferences',
        href: '/settings/preferences',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Preferences" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Preferences" description="Manage your personal preferences and settings" />
                
                <div class="rounded-lg border border-neutral-200 p-6">
                    <p class="text-sm text-neutral-600">
                        Personal preference settings will be available here soon.
                    </p>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template> 