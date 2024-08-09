

<script setup>
import { defineProps, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import Sidebar from '@/Layouts/Sidebar.vue';

const props = defineProps({
  student: Object,
});

const form = ref({ ...props.student });

function updateStudent() {
  router.put(route('students.update', { student: form.value.id }), form.value)
    .then(() => {
      router.visit(route('students.show', { student: form.value.id }));
    })
    .catch(error => {
      console.error(error);
    });
}

function cancelEdit() {
  // Redirect back to the student's show page or a previous route
  router.visit(route('students.show', { student: form.value.id }));
}
</script>

<template>
  <div class="edit-student-page">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">Edit Student Information</h2>
    <form @submit.prevent="updateStudent">
      <div class="flex flex-col mb-3">
        <label for="editFirstName"><strong>First Name:</strong></label>
        <input type="text" id="editFirstName" v-model="form.first_name" required />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editMI"><strong>Middle Initial:</strong></label>
        <input type="text" id="editMI" v-model="form.mi" />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editLastName"><strong>Last Name:</strong></label>
        <input type="text" id="editLastName" v-model="form.last_name" required />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editGender"><strong>Gender:</strong></label>
        <select id="editGender" v-model="form.gender">
            <option value="" disabled>Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
      </div>
      <div class="flex flex-col mb-3">
        <label for="editBirthDate"><strong>Birth Date:</strong></label>
        <input type="date" id="editBirthDate" v-model="form.birth_date" required />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editBirthPlace"><strong>Birth Place:</strong></label>
        <input type="text" id="editBirthPlace" v-model="form.birth_place" required />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editAddress"><strong>Address:</strong></label>
        <input type="text" id="editAddress" v-model="form.address" required />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editFather"><strong>Father's Name:</strong></label>
        <input type="text" id="editFather" v-model="form.father" />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editMother"><strong>Mother's Name:</strong></label>
        <input type="text" id="editMother" v-model="form.mother" />
      </div>
      <div class="flex flex-col mb-3">
        <label for="editPhone"><strong>Phone Number:</strong></label>
        <input type="text" id="editPhone" v-model="form.phone" />
      </div>
      <button class="btn-primary" type="submit">Save Changes</button>
      <button class="btn-secondary ml-4" type="button" @click="cancelEdit">Cancel</button>
    </form>
  </div>
</template>

<style scoped>
.edit-student-page {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-primary {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
