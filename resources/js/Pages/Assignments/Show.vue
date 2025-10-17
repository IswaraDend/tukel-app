<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    assignment: Object,
    team: Object,
    questions: Array,
    distributions: Array,
    score: Object,
});

const distributions = ref(
    props.distributions.map((d) => ({ ...d, loading: false }))
);

const toggleConfirm = async (distribution, event) => {
    const index = distributions.value.findIndex((d) => d.id === distribution.id);
    if (index === -1) return;

    const newStatus = event.target.checked;
    distributions.value[index].is_confirmed = newStatus;
    distributions.value[index].loading = true;

    try {
        const response = await axios.put(`/api/distributions/${distribution.id}`, {
            is_confirmed: Boolean(newStatus),
        });

        if (response.data?.distribution) {
            distributions.value[index] = {
                ...response.data.distribution,
                loading: false,
            };
        }
    } catch (err) {
        console.error('Gagal update status:', err);
        alert('Gagal update status.');
        distributions.value[index].is_confirmed = !newStatus;
    } finally {
        distributions.value[index].loading = false;
    }
};

const totalScore = computed(() => {
    let total = 0;
    distributions.value.forEach((d) => {
        if (d.is_confirmed) {
            const q = props.questions.find((q) => q.id === d.question_id);
            if (q) total += q.weight;
        }
    });
    return total;
});

const maxScore = computed(() => {
    return props.questions.reduce((sum, q) => sum + q.weight, 0);
});

const exportCSV = () => {
    const headers = ['Member Name', 'Question', 'Weight', 'Confirmed'];
    const rows = [];

    distributions.value.forEach((d) => {
        const q = props.questions.find((q) => q.id === d.question_id);
        rows.push([
            d.member_name || '-',
            q?.question_text || '-',
            q?.weight || 0,
            d.is_confirmed ? 'Yes' : 'No',
        ]);
    });

    const csvContent =
        headers.join(',') +
        '\n' +
        rows.map((r) => r.map((v) => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${props.assignment.title.replace(/\s+/g, '_')}_scores.csv`;
    link.click();
    URL.revokeObjectURL(url);
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
                <button @click="$inertia.visit('/dashboard')" class="text-indigo-600 hover:underline text-sm">
                    ← Back to Dashboard
                </button>
            </div>
        </template>

        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-sm space-y-6">

                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Team: {{ team.name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ team.members?.length || 0 }} members
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button @click="exportCSV"
                            class="flex-1 flex items-center justify-center bg-green-600 hover:bg-green-700 text-white px-4 py-4 rounded-md text-sm font-semibold transition text-center min-w-[140px]">
                            ⬇️ Export CSV
                        </button>

                        <div class="text-center bg-gray-100 p-4 rounded-md">
                            <p class="text-sm text-gray-500">Total Score</p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ totalScore }} / {{ maxScore }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-for="(q, index) in questions" :key="q.id" class="border rounded-md bg-gray-50 p-4 relative">
                    <div class="flex justify-between items-center">
                        <h4 class="font-semibold text-gray-800">
                            Question {{ index + 1 }} ({{ q.weight }} pt)
                        </h4>
                        <span class="text-sm font-bold text-green-600 border border-green-400 px-2 py-0.5 rounded-md">
                            {{ q.weight }}
                        </span>
                    </div>

                    <p class="text-gray-700 mt-2 leading-relaxed">
                        {{ q.question_text }}
                    </p>

                    <div v-for="d in distributions.filter(dd => dd.question_id === q.id)" :key="d.id"
                        class="flex justify-between items-center mt-3 bg-white border border-gray-200 rounded-md p-3 hover:shadow-sm transition">
                        <div>
                            <p class="font-medium text-gray-700">
                                {{ d.member_name || '-' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                ID: {{ d.id }}
                            </p>
                        </div>

                        <label class="flex items-center space-x-2">
                            <input type="checkbox" :checked="d.is_confirmed" :disabled="d.loading"
                                @change="(e) => toggleConfirm(d, e)"
                                class="w-5 h-5 accent-indigo-600 cursor-pointer disabled:opacity-50" />
                            <span class="text-sm" :class="{
                                'text-green-600 font-medium': d.is_confirmed,
                                'text-gray-500': !d.is_confirmed,
                            }">
                                {{ d.loading
                                    ? 'Updating...'
                                    : d.is_confirmed
                                        ? 'Done'
                                        : 'Pending' }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
