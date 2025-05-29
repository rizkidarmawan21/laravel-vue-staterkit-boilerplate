<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavGroup, type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, User } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const props = defineProps<{
    menus: NavGroup[];
}>();

const icons = {
    LayoutGrid,
    User,
    BookOpen,
    Folder,
} as const;

// const mainNavItems = [
//     {
//         items: [
//             {
//                 title: 'Dashboard',
//                 href: '/dashboard',
//                 icon: LayoutGrid,
//             },
//         ],
//     },
//     {   
//         label: "Core",
//         items: [
//             {
//                 title: 'User Management',
//                 href: '/users',
//                 icon: User,
//             },
//         ],
//     },
// ];

const footerNavItems: NavItem[] = [];

type IconType = keyof typeof icons;

// Gunakan computed untuk transform menu
const transformedMenus = computed(() => {
    return props.menus.map(menu => ({
        ...menu,
        items: menu.items.map(item => ({
            ...item,
            icon: icons[(item.icon as unknown) as IconType]
        }))
    }));
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <div class="flex items-center justify-center gap-2">
                        <AppLogo />
                    </div>
                    <br />
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="transformedMenus" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
