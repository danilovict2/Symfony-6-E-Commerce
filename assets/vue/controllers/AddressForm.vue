<template>
    <div class="mb-6">
        <h2 class="text-xl mb-5">{{ props.addressName }} Address</h2>
        <div class="flex gap-3">
            <div class="mb-4 flex-1">
                <input placeholder="Address 1" type="text" :name="props.addressName.toLocaleLowerCase() + '[address1]'"
                    v-model.lazy="address.address1"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" />
            </div>
            <div class="mb-4 flex-1">
                <input placeholder="Address 2" type="text" :name="props.addressName.toLocaleLowerCase() + '[address2]'"
                    v-model.lazy="address.address2"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" />
            </div>
        </div>
        <div class="flex gap-3">
            <div class="mb-4 flex-1">
                <input placeholder="City" type="text" :name="props.addressName.toLocaleLowerCase() + '[city]'"
                    v-model.lazy="address.city"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" />
            </div>
            <div class="mb-4 flex-1">
                <select placeholder="State" :name="props.addressName.toLocaleLowerCase() + '[state]'"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full">
                    <option value="">Select State</option>
                    <option 
                        v-for="state in states" 
                        :key="state" 
                        :value="state" 
                        v-text="state"
                        :selected="address.state === state"
                    ></option>
                </select>
            </div>
        </div>
        <div class="flex gap-3">
            <div class="mb-4 flex-1">
                <select placeholder="Country" :name="props.addressName.toLocaleLowerCase() + '[country]'"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full">
                    <option value="">Select Country</option>
                    <option 
                        v-for="country in props.countries" 
                        :key="country.code"
                        :value="country.code" 
                        v-text="country.name" 
                        :selected="address.country.code === country.code"
                        @click="states = country.states;"
                    ></option>
                </select>
            </div>
            <div class="mb-4 flex-1">
                <input placeholder="Zipcode" type="text" :name="props.addressName.toLocaleLowerCase() + '[zipcode]'"
                    v-model.lazy="address.zipcode"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

let props = defineProps({
    addressName: String,
    address: Array,
    countries: Array
})

let address = ref(props.address);
let states = ref(address.value.country.states);
</script>