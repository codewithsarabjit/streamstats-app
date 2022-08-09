<script setup>
const props = defineProps(["records"]);
</script>
<template>
    <div class="p-4">
        <h2><strong>2. Top games by viewer count for each game</strong></h2>
        <table class="border-collapse border border-slate-500">
            <thead>
                <tr>
                    <th class="border border-slate-600">Game</th>
                    <th class="border border-slate-600">Views</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in props.records" :key="item">
                    <td class=" p-2">{{item.game}}</td>
                    <td class=" p-2">{{item.viewsCount}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
