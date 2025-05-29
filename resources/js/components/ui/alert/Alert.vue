<script setup lang="ts">
import { ref, type HTMLAttributes, watch, onMounted } from 'vue'
import { cn } from '@/lib/utils'
import { type AlertVariants, alertVariants } from '.'
import { X } from 'lucide-vue-next'

const props = defineProps<{
  class?: HTMLAttributes['class']
  variant?: AlertVariants['variant']
  modelValue?: boolean
  duration?: number
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const isVisible = ref(props.modelValue || false)

const close = () => {
  isVisible.value = false
}

let timer: number | null = null

const startAutoCloseTimer = () => {
  if (timer) clearTimeout(timer)
  timer = setTimeout(() => {
    close()
  }, props.duration || 2000)
}

watch(() => props.modelValue, (newValue) => {
  isVisible.value = newValue
  if (newValue) {
    startAutoCloseTimer()
  } else if (timer) {
    clearTimeout(timer)
  }
})

const handleAfterLeave = () => {
  emit('update:modelValue', false)
}

onMounted(() => {
  if (props.modelValue) {
    startAutoCloseTimer()
  }
})
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 ease-out"
    enter-from-class="transform -translate-y-4 opacity-0"
    enter-to-class="transform translate-y-0 opacity-100"
    leave-active-class="transition duration-200 ease-in"
    leave-from-class="transform translate-y-0 opacity-100"
    leave-to-class="transform -translate-y-4 opacity-0"
    @after-leave="handleAfterLeave"
  >
    <div
      v-show="isVisible"
      data-slot="alert"
      :class="cn(alertVariants({ variant }), props.class)"
      role="alert"
    >
      <slot />
      <X 
        class="absolute right-2 top-2 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 cursor-pointer" 
        @click="close" 
      />
    </div>
  </Transition>
</template>