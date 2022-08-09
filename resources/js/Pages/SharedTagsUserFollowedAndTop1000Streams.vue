<script setup>
const props = defineProps(["records"]);
</script>
<template>
    <div>
        <h2><strong>8. Which tags are shared between the user followed streams and the top 1000 streams? Also make sure to translate the tags to their respective name?</strong></h2>
        <table class="border-collapse border border-slate-500">
            <thead>
                <tr>
                    <th class="border border-slate-600">Tag Id</th>
                    <th class="border border-slate-600">Tag name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in props.records" :key="item">
                    <td class=" p-2">{{item.tag_id}}</td>
                    <td class=" p-2">{{item.localization_names["en-us"]}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
