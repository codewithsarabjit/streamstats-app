<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Jetstream/Welcome.vue';
const props = defineProps(["user", "totalNumberOfStreamsPerGame", "topGamesByViewersPerGame", "medianViewersOfAllStreams", "top100StreamsByViewersCount", "totalNumberOfStreamsByStartTime", "followedStreamsIntop1000"]);
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
                    <div class="flex p-4">
                        <div>
                            <h2><strong>Median number of viewers for all streams:</strong> {{medianViewersOfAllStreams}}</h2>
                            <h2><strong>Total number of streams by their start time(in last 1 hour):</strong> {{totalNumberOfStreamsByStartTime}}</h2>
                        </div>
                    </div>
                    <div class="flex">
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
                                    <tr v-for="item in totalNumberOfStreamsPerGame" :key="item">
                                        <td class=" p-2">{{item.game}}</td>
                                        <td class=" p-2">{{item.streamsCount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                                    <tr v-for="item in topGamesByViewersPerGame" :key="item">
                                        <td class=" p-2">{{item.game}}</td>
                                        <td class=" p-2">{{item.viewsCount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4">
                            <h2><strong>4. Which of the top 1000 streams is the logged in user following?</strong></h2>
                            <table class="border-collapse border border-slate-500">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-600">Channel name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in followedStreamsIntop1000" :key="item">
                                        <td class=" p-2">{{item.channel_name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="p-4">
                            <h2><strong>3. List of top 100 streams by viewer count that can be sorted asc & desc</strong></h2>
                            <table class="border-collapse border border-slate-500" style="display: block; max-height: 300px; overflow-y: scroll;">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-600">Stream Title</th>
                                        <th class="border border-slate-600">Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, iIndex) in top100StreamsByViewersCount" :key="iIndex">
                                        <td class=" p-2">{{`${iIndex+1}. ${item.title}`}}</td>
                                        <td class=" p-2">{{item.views}}</td>
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
