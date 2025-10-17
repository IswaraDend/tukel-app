<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// =========================
// KONFIGURASI AXIOS
// =========================
axios.defaults.withCredentials = true; // penting agar cookie session dikirim
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// =========================
// STATE DASAR
// =========================
const form = ref({
    team_id: '',
    title: '',
    notes: '',
    questions: [{ text: '', weight: 1 }],
});

const teams = ref([]);
const loadingTeams = ref(false);
const showTeamModal = ref(false);

// =========================
// LOAD TEAM (API)
// =========================
onMounted(async () => {
    loadingTeams.value = true;
    try {
        const res = await axios.get('/api/teams');
        teams.value = res.data;
    } catch (e) {
        console.error('Gagal memuat teams:', e);
    } finally {
        loadingTeams.value = false;
    }
});

// =========================
// QUESTION LOGIC
// =========================
const addQuestion = () => form.value.questions.push({ text: '', weight: 1 });
const removeQuestion = (i) => form.value.questions.splice(i, 1);

// =========================
// SUBMIT ASSIGNMENT
// =========================
const submitting = ref(false);
const submitForm = async () => {
    submitting.value = true;
    try {
        const res = await axios.post('/api/assignments', form.value);

        const assignmentId = res.data?.assignment?.id;
        if (assignmentId) {
            router.visit(`/assignments/${assignmentId}/distribution`);
        } else {
            router.visit('/dashboard');
        }
    } catch (e) {
        if (e.response?.status === 422) {
            const errs = e.response.data.errors || {};
            const msg = Object.values(errs).flat().join('\n');
            alert('Validasi gagal:\n' + msg);
        } else {
            console.error('Error menyimpan assignment:', e);
            alert('Gagal menyimpan assignment');
        }
    } finally {
        submitting.value = false;
    }
};

// =========================
// CREATE TEAM MODAL
// =========================
const newTeam = ref({
    name: '',
    members: [{ name: '', email: '' }],
});

const addMember = () => newTeam.value.members.push({ name: '', email: '' });
const removeMember = (i) => newTeam.value.members.splice(i, 1);

const creatingTeam = ref(false);
const createTeam = async () => {
    creatingTeam.value = true;
    try {
        const res = await axios.post('/api/teams', newTeam.value);
        teams.value.push(res.data);
        form.value.team_id = res.data.id;
        showTeamModal.value = false;
        newTeam.value = { name: '', members: [{ name: '', email: '' }] };
    } catch (e) {
        if (e.response?.status === 422) {
            const errs = e.response.data.errors || {};
            const msg = Object.values(errs).flat().join('\n');
            alert('Validasi gagal:\n' + msg);
        } else {
            console.error('Gagal membuat team:', e);
            alert('Gagal membuat team');
        }
    } finally {
        creatingTeam.value = false;
    }
};
</script>

<template>
    <Head title="Create Assignment" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Assignment
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-6">
                    <!-- ================= TEAM ================= -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Team</h3>

                        <div class="flex items-center space-x-2 mb-3">
                            <select v-model="form.team_id" class="border rounded p-2 flex-1">
                                <option disabled value="">Pilih Team</option>
                                <option v-for="team in teams" :key="team.id" :value="team.id">
                                    {{ team.name }}
                                </option>
                            </select>
                            <button
                                @click="showTeamModal = true"
                                class="bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700"
                            >
                                + Create Team
                            </button>
                        </div>
                    </div>

                    <!-- ================= ASSIGNMENT ================= -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Assignment</h3>
                        <label class="block mb-1">Title</label>
                        <input v-model="form.title" class="w-full border rounded p-2" />

                        <label class="block mt-2 mb-1">Notes</label>
                        <textarea v-model="form.notes" class="w-full border rounded p-2"></textarea>
                    </div>

                    <!-- ================= QUESTIONS ================= -->
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Questions</h3>

                        <div
                            v-for="(q, index) in form.questions"
                            :key="index"
                            class="flex items-start space-x-2 mb-2"
                        >
                            <textarea
                                v-model="q.text"
                                placeholder="Question text"
                                class="flex-1 border rounded p-2"
                            ></textarea>

                            <div class="flex flex-col items-center">
                                <label class="text-xs text-gray-500">Points</label>
                                <input
                                    v-model.number="q.weight"
                                    type="number"
                                    min="0"
                                    class="w-16 border rounded p-1 text-center"
                                />
                            </div>

                            <button
                                @click="removeQuestion(index)"
                                v-if="form.questions.length > 1"
                                class="bg-red-500 text-white px-2 rounded self-center"
                            >
                                X
                            </button>
                        </div>

                        <button @click="addQuestion" class="text-indigo-600 text-sm mt-1">
                            + Add Question
                        </button>
                    </div>

                    <!-- ================= SUBMIT ================= -->
                    <div class="text-right">
                        <button
                            @click="submitForm"
                            :disabled="submitting"
                            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
                        >
                            {{ submitting ? 'Saving...' : 'Save Assignment' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= CREATE TEAM MODAL ================= -->
        <div
            v-if="showTeamModal"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <h3 class="text-lg font-semibold mb-4 text-center">
                    Create New Team
                </h3>

                <label class="block mb-1 font-medium">Team Name</label>
                <input
                    v-model="newTeam.name"
                    type="text"
                    class="w-full border rounded p-2 mb-4"
                    placeholder="e.g. Dream Team"
                />

                <div>
                    <h4 class="font-medium mb-2">Members</h4>
                    <div
                        v-for="(m, i) in newTeam.members"
                        :key="i"
                        class="flex space-x-2 mb-2"
                    >
                        <input
                            v-model="m.name"
                            class="flex-1 border p-2 rounded"
                            placeholder="Name"
                        />
                        <button
                            v-if="newTeam.members.length > 1"
                            @click="removeMember(i)"
                            class="bg-red-500 text-white px-2 rounded"
                        >
                            X
                        </button>
                    </div>
                    <button @click="addMember" class="text-indigo-600 text-sm mt-1">
                        + Add Member
                    </button>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button
                        @click="showTeamModal = false"
                        class="px-4 py-2 border rounded hover:bg-gray-100"
                    >
                        Cancel
                    </button>
                    <button
                        @click="createTeam"
                        :disabled="creatingTeam"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ creatingTeam ? 'Saving...' : 'Create Team' }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
