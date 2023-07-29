<template>
    <a href="#" @click.prevent="removeFromCart()" class="text-purple-600 hover:text-purple-500">Remove</a>
</template>

<script setup>
import axios from 'axios';
import { cart } from '../stores/cart.js';

let props = defineProps({
    productTitle: Number,
});

function removeFromCart() {
    axios.post('/cart/remove/' + props.productTitle)
        .then(response => {
            cart.itemsCount = response.data.count;
        })
}

</script>