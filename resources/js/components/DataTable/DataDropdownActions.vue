<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { MoreHorizontal } from 'lucide-vue-next';

const props = defineProps<{
    id: number | string;
    editRoute: string;
}>();

const emit = defineEmits(['delete']);
const deleteUser = () => {
    emit('delete', props.id);
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem>
                <Link class="w-full" :href="route(editRoute, id)"> Edit </Link>
            </DropdownMenuItem>
            <DropdownMenuItem class="cursor-pointer text-rose-500 hover:text-rose-600!" @click="deleteUser">
                Hapus
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
