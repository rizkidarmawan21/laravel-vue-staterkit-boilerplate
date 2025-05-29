<script setup lang="ts">
import { cn } from '@/lib/utils'
import { DialogDescription, type DialogDescriptionProps, useForwardProps } from 'reka-ui'
import { type HTMLAttributes } from 'vue'
import { reactiveOmit } from '@vueuse/core'

const props = defineProps<DialogDescriptionProps & { class?: HTMLAttributes['class'] }>()

const delegatedProps = reactiveOmit(props, ['class'])

const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
  <DialogDescription
    data-slot="dialog-description"
    v-bind="forwardedProps"
    :class="cn('text-muted-foreground text-sm', props.class)"
  >
    <slot />
  </DialogDescription>
</template>
