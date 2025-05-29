<script setup>
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-vue-next'
import { computed } from 'vue'

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    pageSizes: {
        type: Array,
        default: () => [10, 15, 20, 30, 40, 50]
    }
})

const emit = defineEmits(['update'])

const lastPage = computed(() => Math.ceil(props.total / props.perPage))

const handlePageChange = (page) => {
    emit('update', page, props.perPage)
}

const handlePerPageChange = (value) => {
    emit('update', 1, parseInt(value))
}
</script>

<template>
    <div class="flex items-center justify-between px-2 py-4">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <p class="text-sm font-medium">Rows per page</p>
                <Select :model-value="perPage.toString()" @update:model-value="handlePerPageChange">
                    <SelectTrigger class="h-8 w-[70px]">
                        <SelectValue :placeholder="perPage.toString()" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="size in pageSizes" :key="size" :value="size.toString()">
                            {{ size }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                Page {{ currentPage }} of {{ lastPage }}
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <Button variant="outline" class="h-8 w-8 p-0" :disabled="currentPage === 1" @click="handlePageChange(1)">
                <span class="sr-only">Go to first page</span>
                <ChevronsLeft class="h-4 w-4" />
            </Button>
            <Button variant="outline" class="h-8 w-8 p-0" :disabled="currentPage === 1" @click="handlePageChange(currentPage - 1)">
                <span class="sr-only">Go to previous page</span>
                <ChevronLeft class="h-4 w-4" />
            </Button>
            <Button variant="outline" class="h-8 w-8 p-0" :disabled="currentPage >= lastPage" @click="handlePageChange(currentPage + 1)">
                <span class="sr-only">Go to next page</span>
                <ChevronRight class="h-4 w-4" />
            </Button>
            <Button variant="outline" class="h-8 w-8 p-0" :disabled="currentPage >= lastPage" @click="handlePageChange(lastPage)">
                <span class="sr-only">Go to last page</span>
                <ChevronsRight class="h-4 w-4" />
            </Button>
        </div>
    </div>
</template>
