<script setup>
const props = defineProps(["records"]);
</script>
<template>
    <div class="p-4">
        <h2><strong>1. Total number of streams for each game</strong></h2>
        <table class="border-collapse border border-slate-500">
            <thead>
                <tr>
                    <th class="border border-slate-600">Game</th>
                    <th class="border border-slate-600">Count</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in props.records" :key="item">
                    <td class=" p-2">{{item.game}}</td>
                    <td class=" p-2">{{item.streamsCount}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
