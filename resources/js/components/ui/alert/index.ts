import { cva, type VariantProps } from 'class-variance-authority'

export { default as Alert } from './Alert.vue'
export { default as AlertDescription } from './AlertDescription.vue'
export { default as AlertTitle } from './AlertTitle.vue'

export const alertVariants = cva(
  'relative w-full shadow-lg rounded-lg border px-4 py-3 text-sm grid grid-cols-[0_1fr] has-[>svg]:grid-cols-[calc(var(--spacing)*4)_1fr] has-[>svg]:gap-x-3 gap-y-0.5 items-start [&>svg]:size-4 [&>svg]:translate-y-0.5 [&>svg]:text-current',
  {
    variants: {
      variant: {
        default: 'bg-card text-card-foreground [&_[data-slot=alert-description]]:text-card-foreground',
        info: 'bg-blue-500 text-white [&_[data-slot=alert-description]]:text-white',
        success: 'bg-teal-500 text-white [&_[data-slot=alert-description]]:text-white',
        warning: 'bg-yellow-500 text-white [&_[data-slot=alert-description]]:text-white',
        destructive:
          'text-destructive bg-card [&>svg]:text-current [&_[data-slot=alert-description]]:text-destructive/90',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
)

export type AlertVariants = VariantProps<typeof alertVariants>
