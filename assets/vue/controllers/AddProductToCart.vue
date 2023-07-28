<template>
    <button class="btn-primary" @click="cartStore.addToCart(props.productId)" :class="{ 'py-4 text-lg flex justify-center min-w-0 w-full mb-6' : props.isBig }">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewbox="0 0 24 24" stroke="currentColor"
            stroke-width="2" v-show="props.isBig">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Add to Cart
    </button>
</template>

<script setup>
import { useCartStore } from '../stores/cart';

let props = defineProps({
    productId: Number, 
    isBig: {
        type: Boolean,
        default: false
    }
});

let cartStore = useCartStore();
</script>