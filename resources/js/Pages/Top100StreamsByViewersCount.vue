<script setup>
const props = defineProps(["records"]);
</script>
<template>
    <div class="p-4">
        <h2><strong>4. List of top 100 streams by viewer count that can be sorted asc & desc</strong></h2>
        <table class="border-collapse border border-slate-500" style="display: block; max-height: 300px; overflow-y: scroll;">
            <thead>
                <tr>
                    <th class="border border-slate-600">Stream Title</th>
                    <th class="border border-slate-600">Views</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, iIndex) in props.records" :key="iIndex">
                    <td class=" p-2">{{`${iIndex+1}. ${item.title}`}}</td>
                    <td class=" p-2">{{item.views}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
