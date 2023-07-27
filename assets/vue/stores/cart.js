import { defineStore } from "pinia";
import { ref } from "vue";

export const useCartStore = defineStore('cart', {
    state: () => {
        return {
            items: ref([])
        }
    }
});