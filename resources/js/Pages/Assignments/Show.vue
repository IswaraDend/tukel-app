<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    assignment: Object,
    team: Object,
    questions: Array,
    distributions: Array,
    score: Object,
});

const distributions = ref(props.distributions);

// update toggle
const toggleConfirm = async (distribution) => {
    try {
        distribution.is_confirmed = !distribution.is_confirmed;
        await axios.put(`/api/distributions/${distribution.id}`, {
            is_confirmed: distribution.is_confirmed,
        });
    } catch (err) {
        console.error(err);
        alert('Gagal update status.');
        distribution.is_confirmed = !distribution.is_confirmed; // rollback
    }
};
</script>

<template>
    <Head :title="`Assignment Detail - ${assignment.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ assignment.title }}
                </h2>
                <button
                    @click="$inertia.visit('/dashboard')"
                    class="text-indigo-600 hover:underline text-sm"
                >
                    ← Back to Dashboard
                </button>
            </div>
        </template>

        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-sm space-y-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">Team: {{ team.name }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ team.members?.length || 0 }} members
                        </p>
                    </div>

                    <!-- total score -->
                    <div class="text-center bg-gray-100 p-4 rounded-md">
                        <p class="text-sm text-gray-500">Total Score</p>
                        <p class="text-3xl font-bold text-gray-800">
                            {{ score.total }} / {{ score.max }}
                        </p>
                    </div>
                </div>

                <!-- LIST QUESTIONS -->
                <div v-for="(q, index) in questions" :key="q.id" class="border rounded-md bg-gray-50 p-4 relative">
                    <div class="flex justify-between items-center">
                        <h4 class="font-semibold text-gray-800">
                            Soal ke {{ index + 1 }} ({{ q.weight }} pt)
                        </h4>
                        <span
                            class="text-sm font-bold text-green-600 border border-green-400 px-2 py-0.5 rounded-md"
                        >
                            {{ q.weight }}
                        </span>
                    </div>

                    <p class="text-gray-700 mt-2">{{ q.question_text }}</p>

                    <div
                        v-for="d in distributions.filter(dd => dd.question_id === q.id)"
                        :key="d.id"
                        class="flex justify-between items-center mt-3"
                    >
                        <p class="font-medium text-gray-600">
                            {{ d.member_name || '-' }}
                        </p>
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="d.is_confirmed"
                                @change="toggleConfirm(d)"
                                class="w-5 h-5 accent-indigo-600"
                            />
                            <span class="text-sm text-gray-600">
                                {{ d.is_confirmed ? 'Done' : 'Pending' }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
