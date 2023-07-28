import { defineStore } from "pinia";
import { ref } from "vue";

export const useCartStore = defineStore('cart', {
    state: () => {
        return {
            items: ref([
                {
                    id: 11,
                    quantity: 1
                },
                {
                    id: 10,
                    quantity: 3
                }
            ])
        }
    },
    actions: {
        addToCart(productId, quantity = 1) {
            this.items.push({
                id: productId,
                quantity: quantity
            });
        },
        removeFromCart(productId) {
            this.items = this.items.filter((product) => product.id !== productId);
            console.log(this.items);
        }
    },
    getters: {
        cartItemsCount: (state) => {
            return state.items.length;
        },
    },
});