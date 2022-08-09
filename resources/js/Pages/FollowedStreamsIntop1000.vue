<script setup>
const props = defineProps(["records"]);
</script>
<template>
    <div class="p-4">
        <h2><strong>6. Which of the top 1000 streams is the logged in user following?</strong></h2>
        <table class="border-collapse border border-slate-500">
            <thead>
                <tr>
                    <th class="border border-slate-600">Channel name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in props.records" :key="item">
                    <td class=" p-2">{{item.channel_name}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
