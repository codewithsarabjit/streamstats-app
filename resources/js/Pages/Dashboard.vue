<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TotalNumberOfStreamsPerGame from '@/Pages/TotalNumberOfStreamsPerGame.vue';
import TopGamesByViewersPerGame from '@/Pages/TopGamesByViewersPerGame.vue';
import Top100StreamsByViewersCount from '@/Pages/Top100StreamsByViewersCount.vue';
import FollowedStreamsIntop1000 from '@/Pages/FollowedStreamsIntop1000.vue';
import SharedTagsUserFollowedAndTop1000Streams from '@/Pages/SharedTagsUserFollowedAndTop1000Streams.vue';
const props = defineProps(["user", "totalNumberOfStreamsPerGame", "topGamesByViewersPerGame", "medianViewersOfAllStreams", "top100StreamsByViewersCount", "totalNumberOfStreamsByStartTime", "followedStreamsIntop1000", "diffViewersUserFollowedAnd1000thStream", "sharedTagsUserFollowedAndTop1000Streams"]);
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
                        <TotalNumberOfStreamsPerGame :records="totalNumberOfStreamsPerGame"></TotalNumberOfStreamsPerGame>
                        <TopGamesByViewersPerGame :records="topGamesByViewersPerGame"></TopGamesByViewersPerGame>
                    </div>
                    <div class="flex p-4">
                        <div>
                            <h2><strong>3. Median number of viewers for all streams:</strong> {{medianViewersOfAllStreams}}</h2>
                        </div>
                    </div>
                    <div class="flex">
                        <Top100StreamsByViewersCount :records="top100StreamsByViewersCount"></Top100StreamsByViewersCount>
                    </div>
                    
                    <div class="flex p-4">
                        <div>
                            <h2><strong>5. Total number of streams by their start time(in last 1 hour):</strong> {{totalNumberOfStreamsByStartTime}}</h2>
                        </div>
                    </div>
                    <div class="flex">
                        <FollowedStreamsIntop1000 :records="followedStreamsIntop1000"></FollowedStreamsIntop1000>
                    </div>
                    
                    <div class="flex p-4">
                        <div>
                            <h2><strong>7. How many viewers does the lowest viewer count stream that the logged in user is following need to gain in order to make it into the top 1000?:</strong> {{diffViewersUserFollowedAnd1000thStream}}</h2>
                        </div>
                    </div>
                    <div class="flex p-4">
                        <SharedTagsUserFollowedAndTop1000Streams :records="sharedTagsUserFollowedAndTop1000Streams"></SharedTagsUserFollowedAndTop1000Streams>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
