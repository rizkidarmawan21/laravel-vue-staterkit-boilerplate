import { createGlobalState } from '@vueuse/core';
import { ref } from 'vue';

export type AlertType = 'default' | 'success' | 'destructive' | 'warning' | 'info';

interface AlertState {
    show: boolean;
    title: string;
    type: AlertType;
    description: string;
}

export const useAlertStore = createGlobalState(() => {
    const show = ref(false);
    const title = ref('');
    const type = ref<AlertType>('default');
    const description = ref('');

    function showAlert(payload: Partial<AlertState>) {
        show.value = true;
        title.value = payload.title ?? '';
        type.value = payload.type ?? 'default';
        description.value = payload.description ?? '';
    }

    function hideAlert() {
        show.value = false;
        title.value = '';
        type.value = 'default';
        description.value = '';
    }

    return {
        show,
        title,
        type,
        description,
        showAlert,
        hideAlert,
    };
});
