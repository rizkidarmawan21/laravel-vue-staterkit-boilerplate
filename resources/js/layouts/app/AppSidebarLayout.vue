<script setup lang="ts">
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import type { BreadcrumbItemType, NavGroup } from '@/types';
import { computed, Teleport, watch, onMounted } from 'vue';
import { Toaster } from '@/components/ui/sonner'
import { toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import { usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

interface FlashMessage {
    success?: string;
    error?: string;
    warning?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage()
const flashMessage = computed(() => page.props.flash as FlashMessage);
const sidebarMenu = computed(() => page.props.sidebarMenu as NavGroup[]);

watch(flashMessage, (value) => {
    if(value?.success) toast.success(value.success)
    if(value?.warning) toast.warning(value.warning)
    if(value?.error) toast.error(value.error,{
        duration: 5000,
        action: {
            label: 'Copy',
            onClick: () => {
                navigator.clipboard.writeText(value?.error || '')
            }
        }
    })
}, { immediate: true })

// Backup handler untuk flash message
onMounted(() => {
    const flash = page.props.flash as FlashMessage
    if(flash?.success) toast.success(flash.success)
    if(flash?.warning) toast.warning(flash.warning)
    if(flash?.error) toast.error(flash.error)
})
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar :menus="sidebarMenu" />
        <AppContent variant="sidebar" id="app-content">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
    
    <Teleport to="#app-content">
        <Toaster closeButton richColors position="top-right" />
    </Teleport>
</template>
