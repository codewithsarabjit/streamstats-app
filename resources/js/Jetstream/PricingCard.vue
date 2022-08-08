<script setup>
import { Inertia } from '@inertiajs/inertia';
const props = defineProps(["product", "price", "subscription"]);
const choosePlan = (plan) => {
    Inertia.put(route('plans.update'), plan);
}
</script>
<template>
<div class="p-4 max-w-sm bg-white rounded-lg border shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700 inline-block mr-5">
    <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">{{props.product.name}}</h5>
    <div class="flex items-baseline text-gray-900 dark:text-white">
        <span class="text-3xl font-semibold" v-if="props.price.unit_amount !== 0">{{props.price.currency.toUpperCase()}}</span>
        <span class="text-5xl font-extrabold tracking-tight">{{props.price.unit_amount === 0 ? "Free" : props.price.unit_amount / 100}}</span>
        <span class="ml-1 text-xl font-normal text-gray-500 dark:text-gray-400" v-if="props.price.unit_amount !== 0">/{{props.price.recurring.interval}}</span>
    </div>

    <ul role="list" class="my-7 space-y-5">
        <li class="flex space-x-3" v-if="props.price.unit_amount !== 0">
        <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400">Recurring {{props.price.type=='recurring' ? "Yes" : "No"}}</span>
        </li>
        <li class="flex space-x-3" v-else>
        <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
        <span class="text-base font-normal leading-tight text-gray-500 dark:text-gray-400">Try free</span>
        </li>
    </ul>
    <span v-if="subscription && subscription.stripe_price === props.price.id && subscription.name === props.product.name" type="button" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">Current plan</span>
    <button v-else type="button" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center" @click="choosePlan(props.price)">Choose plan</button>
</div>
</template>