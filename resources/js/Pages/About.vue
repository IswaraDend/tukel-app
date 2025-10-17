<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const datas = [
    { name: 'Aflaha', img: '/images/team/aflaha.png' },
    { name: 'Ridho', img: '/images/team/ridho.png' },
    { name: 'Iswara', img: '/images/team/iswara.png' },
    { name: 'Della', img: '/images/team/della.png' },
    { name: 'Alima', img: '/images/team/alima.png' },
];
</script>

<template>
    <Head title="About Us" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                About Us
            </h2>
        </template>

        <section class="py-12 bg-gray-50 min-h-screen animate-fadeIn">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-8">
                    <!-- App info -->
                    <div class="text-center mb-10">
                        <h1 class="text-3xl font-extrabold text-gray-800 mb-3">Tukel App</h1>
                        <p class="text-gray-600 max-w-2xl mx-auto">
                            Tukel is a web platform to <strong>assign and manage study assignments</strong>.<br />
                            Created by <span class="font-semibold">LHCA Group 4</span>.
                        </p>
                    </div>

                    <!-- Team section -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
                            Meet Our Team
                        </h2>

                        <ul
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 justify-items-center"
                        >
                            <li
                                v-for="data in datas"
                                :key="data.name"
                                class="flex flex-col items-center bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 p-4 w-36 sm:w-40"
                            >
                                <img
                                    :src="data.img"
                                    :alt="`Photo of ${data.name}`"
                                    class="h-28 w-28 object-cover rounded-full border border-gray-300 shadow-sm"
                                />
                                <p class="mt-3 text-base font-semibold text-gray-800 text-center">
                                    {{ data.name }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template>
