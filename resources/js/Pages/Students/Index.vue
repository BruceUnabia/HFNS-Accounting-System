<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Layouts/Sidebar.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps(['students']);

function openCreateStudent() {
    router.get('/students/create')
}

</script>

<template>
    <Head title="Students" />

    <Sidebar>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Students</h2>
                <div class="flex align-center">
                    <button class="btn-primary" @click="openCreateStudent()">
                        <font-awesome-icon icon="fa-solid fa-user-plus" /> &nbsp;
                        Create Student
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl mb-4">Students List</h1>
                        <div class="space-y-4">
                            <ul>
                                <li v-for="student in props.students" :key="student.id" class="border-b">
                                    <Link :href="`/students/${student.id}`" class="block p-4 hover:bg-gray-100">
                                        <span class="font-semibold">{{ student.first_name }} {{ student.last_name }}</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div> -->
    </Sidebar>
</template>

