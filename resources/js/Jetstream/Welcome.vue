<script setup>
const props = defineProps(["user", "subscription"]);
</script>
<template>
    <div>
        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <div class="mt-8 text-2xl">
                Welcome {{props.user.name}}
            </div>
            <div class="mt-8">
                Your Current subscription: {{props.subscription!==null ? props.subscription.name : ''}}
            </div>
        </div>
    </div>
</template>
