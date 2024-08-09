<script setup>
import Sidebar from '@/Layouts/Sidebar.vue';
import { defineProps, ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  student: Object,
});

const showModal = ref(false);
const showAddChargeModal = ref(false);
const showPaymentModal = ref(false);
const accountCreated = ref(false);
const accountDetails = ref({
  section: '',
  tuition: '',
  miscellaneous: '',
  books: '',
  charges: [],
});

const paymentAmount = ref(0);
const currentBalance = ref(0); // New ref to hold the current balance

// Payment history array
const paymentHistory = ref([]);

// New charge details
const newCharge = ref({
  name: '',
  amount: ''
});

// Computed property to format the student's name
const formattedName = computed(() => {
  const { first_name, mi, last_name } = props.student;
  return `${first_name.toUpperCase()} ${mi ? mi.toUpperCase() : ''} ${last_name.toUpperCase()}`;
});

function formatNumber(value) {
  return new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value);
}

// Computed property to calculate the total of tuition, miscellaneous, books, and charges
const totalAmount = computed(() => {
  const tuition = parseFloat(accountDetails.value.tuition) || 0;
  const miscellaneous = parseFloat(accountDetails.value.miscellaneous) || 0;
  const books = parseFloat(accountDetails.value.books) || 0;
  const charges = accountDetails.value.charges.reduce((sum, charge) => sum + (parseFloat(charge.amount) || 0), 0);

  return tuition + miscellaneous + books + charges;
});

const formattedTotalAmount = computed(() => formatNumber(totalAmount.value));

const formattedTuition = computed(() => formatNumber(parseFloat(accountDetails.value.tuition) || 0));
const formattedMiscellaneous = computed(() => formatNumber(parseFloat(accountDetails.value.miscellaneous) || 0));
const formattedBooks = computed(() => formatNumber(parseFloat(accountDetails.value.books) || 0));

function createAccount() {
  accountCreated.value = true;
  showModal.value = false;
  currentBalance.value = totalAmount.value; // Set the initial balance
}

function closeModal() {
  showModal.value = false;
  showAddChargeModal.value = false;
  showPaymentModal.value = false;
}


// Function to add a charge
function addCharge() {
  if (newCharge.value.name && newCharge.value.amount) {
    accountDetails.value.charges.push({ ...newCharge.value });
    newCharge.value.name = '';
    newCharge.value.amount = '';
    closeModal();
    currentBalance.value = totalAmount.value; // Update balance after adding charge
  }
}

// Function to handle payment
function makePayment() {
  if (paymentAmount.value > 0 && paymentAmount.value <= currentBalance.value) {
    currentBalance.value -= paymentAmount.value; // Decrease balance by payment amount

    // Get current date and time
    const currentDateTime = new Date();
    const formattedDateTime = currentDateTime.toLocaleString('en-US', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit',
      hour12: true, // Use 12-hour format
    });

    paymentHistory.value.push({
      date: formattedDateTime, // Record date and time of payment
      amount: paymentAmount.value,
      balance: currentBalance.value,
    });
    paymentAmount.value = 0; // Reset payment amount
    closeModal(); // Close modal after successful payment
  } else {
    alert('Payment amount exceeds the current balance or is invalid');
  }
}

function editStudent() {
  router.visit(route('students.edit', { student: props.student.id }));
}

// Account History Placeholder
const accountHistory = ref([
  {
    schoolYear: 'SY 2023-2024',
    level: 'Grade V',
    section: 'St. Bruce'
  },
  {
    schoolYear: 'SY 2022-2023',
    level: 'Grade IV',
    section: 'St. Unabia'
  }
])

// In case need ug view details
function viewDetails(history) {
    alert(`Viewing details for  ${history.schoolYear}`)
  }
</script>

<template>
    <Head title="Student Details" />

    <Sidebar>
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student Information</h2>
      </template>

      <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="flex-container">
            <div class="student-info">
              <h1 class="text-2xl mb-4 name"><strong>{{ formattedName }}</strong></h1>
              <div>
                <p><strong>Middle Initial:</strong> {{ student.mi }}</p>
                <p><strong>Gender:</strong> {{ student.gender }}</p>
                <p><strong>Birth Date:</strong> {{ student.birth_date }}</p>
                <p><strong>Birth Place:</strong> {{ student.birth_place }}</p>
                <p><strong>Address:</strong> {{ student.address }}</p>
                <p><strong>Father's Name:</strong> {{ student.father }}</p>
                <p><strong>Mother's Name:</strong> {{ student.mother }}</p>
                <p><strong>Phone Number:</strong> {{ student.phone }}</p>
              </div>
              <div class="flex mt-6">
                <button class="btn-primary" @click="editStudent">Update</button>
              </div>
            </div>

            <div class="account-section">
              <button v-if="!accountCreated" class="btn-primary" @click="showModal = true">
                Create Account
              </button>

              <div v-else>
                <h1 class="text-2xl mb-4 name"><strong>{{ accountDetails.section }}</strong></h1>
                <div class="info-container">
                  <div class="info-item">
                    <strong>Tuition:</strong> <span class="info-value">{{ formattedTuition }}</span>
                  </div>
                  <div class="info-item">
                    <strong>Miscellaneous:</strong> <span class="info-value">{{ formattedMiscellaneous }}</span>
                  </div>
                  <div class="info-item">
                    <strong>Books:</strong> <span class="info-value">{{ formattedBooks }}</span>
                  </div>
                  <div v-for="(charge, index) in accountDetails.charges" :key="index" class="info-item">
                    <strong>{{ charge.name }}:</strong> <span class="info-value">{{ formatNumber(charge.amount) }}</span>
                  </div>
                  <div class="total-separator"></div>
                  <div class="info-item">
                    <strong>Total:</strong> <span class="info-value">{{ formattedTotalAmount }}</span>
                  </div>
                </div>
                <button class="btn-primary mt-4" @click="showAddChargeModal = true">Add Charge</button>
              </div>
            </div>

            <!-- Payment History Container -->
            <div v-if="accountCreated" class="payment-history-section">
              <h2 class="text-xl mb-4">Payment History</h2>
              <p><strong>Total Due:</strong> {{ formattedTotalAmount }}</p>
              <table class="table-auto w-full">
                <thead>
                  <tr>
                    <th class="border px-4 py-2">Date</th>
                    <th class="border px-4 py-2">Amount</th>
                    <th class="border px-4 py-2">Balance</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="payment in paymentHistory" :key="payment.date">
                    <td class="border px-4 py-2">{{ payment.date }}</td>
                    <td class="border px-4 py-2">{{ formatNumber(payment.amount) }}</td>
                    <td class="border px-4 py-2">{{ formatNumber(payment.balance) }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="mt-4">
                <button class="btn-primary" @click="showPaymentModal = true">Make Payment</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Account Creation Modal -->
      <div v-if="showModal" class="modal" @click.self="closeModal">
        <div class="modal-content">
          <span class="close" @click="closeModal">&times;</span>
          <h2>Create an account for <strong>{{ formattedName }}</strong></h2>
          <form @submit.prevent="createAccount">
            <div class="flex flex-col mb-3">
              <label for="section"><strong>Section:</strong></label>
              <input type="text" id="section" v-model="accountDetails.section" required />
            </div>
            <div class="flex flex-col mb-3">
              <label for="tuition"><strong>Tuition:</strong></label>
              <input type="text" id="tuition" v-model="accountDetails.tuition" required />
            </div>
            <div class="flex flex-col mb-3">
              <label for="miscellaneous"><strong>Miscellaneous:</strong></label>
              <input type="text" id="miscellaneous" v-model="accountDetails.miscellaneous" required />
            </div>
            <div class="flex flex-col mb-3">
              <label for="books"><strong>Books:</strong></label>
              <input type="text" id="books" v-model="accountDetails.books" required />
            </div>
            <button class="btn-primary" type="submit">Create Account</button>
          </form>
        </div>
      </div>

      <!-- Add Charge Modal -->
      <div v-if="showAddChargeModal" class="modal" @click.self="closeModal">
        <div class="modal-content">
          <span class="close" @click="closeModal">&times;</span>
          <h2>Add Charge for <strong>{{ formattedName }}</strong></h2>
          <form @submit.prevent="addCharge">
            <div class="flex flex-col mb-3">
              <label for="chargeName"><strong>Charge Name:</strong></label>
              <input type="text" id="chargeName" v-model="newCharge.name" required />
            </div>
            <div class="flex flex-col mb-3">
              <label for="chargeAmount"><strong>Charge Amount:</strong></label>
              <input type="text" id="chargeAmount" v-model="newCharge.amount" required />
            </div>
            <button class="btn-primary" type="submit">Add Charge</button>
          </form>
        </div>
      </div>

      <!-- Payment Modal -->
      <div v-if="showPaymentModal" class="modal" @click.self="closeModal">
        <div class="modal-content">
          <span class="close" @click="closeModal">&times;</span>
          <h2>Make Payment for <strong>{{ formattedName }}</strong></h2>
          <form @submit.prevent="makePayment">
            <div class="flex flex-col mb-3">
              <label for="paymentAmount"><strong>Payment Amount:</strong></label>
              <input type="number" id="paymentAmount" v-model.number="paymentAmount" required />
            </div>
            <button class="btn-primary" type="submit">Submit Payment</button>
          </form>
        </div>
      </div>

      <!-- Account History -->
      <div class="account-history-section">
        <h2 class="text-xl mb-4">Account History</h2>
        <table class="table-auto w-full">
          <thead>
            <tr>
              <th class="border px-4 py-2">School Year</th>
              <th class="border px-4 py-2">Level</th>
              <th class="border px-4 py-2">Section</th>
              <th class="border px-4 py-2"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="history in accountHistory" :key="history.schoolYear">
              <td class="border px-4 py-2">{{ history.schoolYear }}</td>
              <td class="border px-4 py-2">{{ history.level }}</td>
              <td class="border px-4 py-2">{{ history.section }}</td>
              <td class="border px-4 py-2 text-center">
                <button @click="viewDetails(history)" class="view-details-button"><i class="fa-solid fa-arrow-up-right-from-square"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </Sidebar>
  </template>

<style scoped>
.flex-container {
  display: flex;
  gap: 20px;
  align-items: flex-start;
}

.student-info {
  flex: 1;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.name {
  text-transform: uppercase;
}

.account-section {
  flex: 0 0 300px;
  padding: 20px;
  background-color: #f0f0f0;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.payment-history-section {
  flex: 0 0 300px;
  padding: 20px;
  background-color: #f0f0f0;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.modal {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  width: 400px;
  position: relative;
}

.close {
  position: absolute;
  top: 10px;
  right: 10px;
  cursor: pointer;
  font-size: 20px;
}

.info-container {
  display: flex;
  flex-direction: column;
  margin-right: 20px;
}

.info-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.info-value {
  margin-left: 120px;
}

.total-separator {
  border-top: 1px solid #ddd;
  margin: 10px 0;
}

.table-auto {
  width: 100%;
  border-collapse: collapse;
}

.table-auto th,
.table-auto td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.account-history-section {
  flex: 1;
  padding: 20px;
  background-color: #f0f0f0;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.view-details-button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  font-size: 18px;
  color: #007bff;
}

.view-details-button:hover {
  color: #0056b3;
}
</style>
