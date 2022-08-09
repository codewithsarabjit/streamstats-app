<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
const props = defineProps(["user", "topGames"]);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex">
                        <div class="p-4">
                            <h2><strong>Top Games</strong></h2>
                            <table class="border-collapse border border-slate-500">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-600">Art</th>
                                        <th class="border border-slate-600">ID</th>
                                        <th class="border border-slate-600">Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="topGame in topGames" :key="topGame">
                                        <td class=" p-2"><img :src="topGame.box_art_url.replace('{width}', '50').replace('{height}', '50')" width="50" height="50" alt=""></td>
                                        <td class=" p-2">{{topGame.id}}</td>
                                        <td class=" p-2">{{topGame.name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
