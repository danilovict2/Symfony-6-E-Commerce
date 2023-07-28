<template>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto">
        <h1 class="text-3xl font-bold mb-6">Your Cart Items</h1>

        <div class="bg-white p-4 rounded-lg shadow">
            <!-- Product Items -->
            <template v-if="cartItems.length">
                <div>
                    <!-- Product Item -->
                    <template v-for="cartItem of cartItems" :key="cartItem.product.id">
                        <div>
                            <div class="w-full flex items-center gap-4 flex-1">
                                <a :href="'/product/' + cartItem.product.title"
                                    class="w-36 h-32 flex items-center justify-center overflow-hidden">
                                    <img :src="'/uploads/photos/' + cartItem.product.image" class="object-cover" alt="" />
                                </a>
                                <div class="flex flex-col justify-between flex-1">
                                    <div class="flex justify-between mb-3">
                                        <h3 v-text="cartItem.product.title" class="text-3xl"></h3>
                                        <span class="text-lg font-semibold">
                                            $<span v-text="cartItem.product.price"></span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            Qty:
                                            <input type="number" min="1" v-model="cartItem.quantity"
                                                class="ml-3 py-1 border-gray-200 focus:border-purple-600 focus:ring-purple-600 w-16" 
                                                @change="calculateTotal"    
                                            />
                                        </div>
                                        <a href="#" @click.prevent="useCartStore().removeFromCart(cartItem.product.id);fetchCartItems();"
                                            class="text-purple-600 hover:text-purple-500">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <!--/ Product Item -->
                            <hr class="my-5" />
                        </div>
                    </template>
                    <!-- Product Item -->

                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold">Subtotal</span>
                            <span id="cartTotal" class="text-xl" v-text="`$${total}`"></span>
                        </div>
                        <p class="text-gray-500 mb-6">
                            Shipping and taxes calculated at checkout.
                        </p>

                        <button class="btn-primary w-full py-3 text-lg">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
                <!--/ Product Items -->
            </template>
            <template v-else>
                <div class="text-center py-8 text-gray-500">
                    You don't have any items in cart
                </div>
            </template>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import useProduct from '../composables/product';
import { useCartStore } from '../stores/cart'

let cartItems = ref([]);
let total = ref(0);

async function fetchCartItems() {
    cartItems.value = [];
    for(let item of useCartStore().items) {
        let cartItem = await useProduct(item.id);
        cartItems.value.push({
            product: cartItem.product.product,
            quantity: item.quantity
        });
    }
    calculateTotal();
}

function calculateTotal() {
    total.value = 0;
    for(let cartItem of cartItems.value) {
        total.value += cartItem.product.price * cartItem.quantity;
    }
}

fetchCartItems();
</script>