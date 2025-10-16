<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// =========================
// STATE
// =========================
const assignments = ref([]);
const loading = ref(true);
const error = ref(null);

// =========================
// FETCH DATA DARI BACKEND
// =========================
const fetchAssignments = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/assignments');
        assignments.value = response.data;
    } catch (err) {
        console.error(err);
        error.value = 'Gagal memuat data assignment.';
    } finally {
        loading.value = false;
    }
};

// =========================
// ACTION
// =========================
const createAssignment = () => {
    router.visit(route('assignments.create'));
};

// klik item untuk buka distribusi
const goToDistribution = (assignmentId) => {
    router.visit(`/assignments/${assignmentId}`);
};

// =========================
// LIFECYCLE
// =========================
onMounted(() => {
    fetchAssignments();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Assignments</h3>
                        <button
                            @click="createAssignment"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-md transition"
                        >
                            + Create
                        </button>
                    </div>

                    <!-- LOADING -->
                    <div v-if="loading" class="text-gray-500 py-8 text-center">
                        Memuat data...
                    </div>

                    <!-- ERROR -->
                    <div v-else-if="error" class="text-red-600 text-center py-8">
                        {{ error }}
                    </div>

                    <!-- EMPTY -->
                    <div
                        v-else-if="assignments.length === 0"
                        class="text-gray-500 text-center py-12 border border-dashed border-gray-300 rounded-lg bg-gray-50"
                    >
                        Belum ada assignment.
                    </div>

                    <!-- LIST ASSIGNMENTS -->
                    <div v-else class="space-y-3">
                        <div
                            v-for="(item, index) in assignments"
                            :key="item.id"
                            @click="goToDistribution(item.id)"
                            class="cursor-pointer flex justify-between items-center p-4 rounded-md border hover:shadow-md transition"
                            :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-gray-100'"
                        >
                            <div>
                                <h4 class="font-semibold text-gray-800 text-md">
                                    {{ item.team?.name || 'Unknown Team' }}
                                    |
                                    <span class="font-normal text-gray-600">
                                        Score {{ item.score_total ?? 0 }} / {{ item.score_max ?? 0 }}
                                        | {{ item.questions_count ?? 0 }} Question
                                    </span>
                                </h4>

                                <p class="text-sm text-gray-600 mt-1">
                                    Members:
                                    <span v-if="item.team?.members?.length">
                                        {{ item.team.members.map(m => m.member_name).join(', ') }}
                                    </span>
                                    <span v-else>-</span>
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    Created:
                                    {{
                                        new Date(item.created_at).toLocaleString('en-US', {
                                            dateStyle: 'medium',
                                            timeStyle: 'short',
                                        })
                                    }}
                                </p>
                            </div>

                            <button
                                @click.stop="alert('Delete feature belum diaktifkan')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded transition"
                            >
                                🗑
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
