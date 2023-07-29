<template>
    <a href="/cart" class="relative flex items-center justify-between py-2 px-3 transition-colors hover:bg-slate-800">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -mt-1" fill="none" viewbox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Cart
        </div>
        <!-- Cart Items Counter -->
        <small v-show="cart.itemsCount" v-text="cart.itemsCount"
            class="py-[3px] px-[8px] mb-3 rounded-full bg-red-500"></small>
        <!--/ Cart Items Counter -->
    </a>
</template>

<script setup>
import { cart } from '../stores/cart';
</script>