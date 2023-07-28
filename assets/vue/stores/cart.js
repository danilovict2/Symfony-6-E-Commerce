import { defineStore } from "pinia";
import { ref } from "vue";
import useProduct from '../composables/product';

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
        }
    },
    getters: {
        cartItemsCount: (state) => {
            return state.items.length;
        },
        
        getTotal: async (state) => {
            let total = 0;
            for(let item of state.items) {
                //let product = await useProduct(item.id).product;
                //total += product.price * item.quantity;
            }
            return total;
        }
    },
});