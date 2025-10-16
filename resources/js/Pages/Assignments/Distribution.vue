<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  assignment: Object,
  questions: Array,
  members: Array,
});

const pairs = ref(props.questions.map(q => ({
  question_id: q.id,
  member_id: null,
  question_text: q.question_text,
})));

// auto shuffle
const shuffle = () => {
  const shuffledMembers = [...props.members].sort(() => Math.random() - 0.5);
  pairs.value.forEach((p, i) => {
    p.member_id = shuffledMembers[i % shuffledMembers.length].id;
  });
};

// save
const saving = ref(false);
const saveDistribution = async () => {
  saving.value = true;
  try {
    await axios.post(`/assignments/${props.assignment.id}/distribution`, {
      pairs: pairs.value.map(p => ({
        question_id: p.question_id,
        member_id: p.member_id,
      })),
    });
    router.visit('/dashboard');
  } catch (e) {
    console.error(e);
    alert('Failed to save distribution');
  } finally {
    saving.value = false;
  }
};
</script>

<template>
  <Head :title="`Distribute Questions - ${assignment.title}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Distribute Questions — {{ assignment.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow space-y-6">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold">Team: {{ assignment.team.name }}</h3>
          <button @click="shuffle" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
            Shuffle Distribution
          </button>
        </div>

        <table class="w-full border border-gray-200 rounded">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2 border">#</th>
              <th class="p-2 border text-left">Question</th>
              <th class="p-2 border text-left">Assigned To</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(p, i) in pairs" :key="i">
              <td class="p-2 border text-center">{{ i + 1 }}</td>
              <td class="p-2 border">{{ p.question_text }}</td>
              <td class="p-2 border">
                <select v-model="p.member_id" class="border rounded p-1 w-full">
                  <option disabled value="">Select Member</option>
                  <option v-for="m in members" :key="m.id" :value="m.id">
                    {{ m.member_name }}
                  </option>
                </select>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="text-right">
          <button
            @click="saveDistribution"
            :disabled="saving"
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
          >
            {{ saving ? 'Saving...' : 'Save Distribution' }}
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
