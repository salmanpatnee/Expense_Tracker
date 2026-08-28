<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import ExpensesChart from './ExpensesChart.vue';

const expenses = ref([]);
const categories = ref([]);
const totals = ref([]);
const confirmingDeleteId = ref(null);
const formError = ref('');

function today() {
    return new Date().toISOString().slice(0, 10);
}

const form = ref({
    date: today(),
    category_id: '',
    description: '',
    amount: '',
});

function resetForm() {
    form.value = {
        date: today(),
        category_id: '',
        description: '',
        amount: '',
    };
}

async function fetchCategories() {
    const { data } = await api.get('/categories');
    categories.value = data;
}

async function fetchExpenses() {
    const { data } = await api.get('/expenses');
    expenses.value = data;
}

async function fetchTotals() {
    const { data } = await api.get('/expenses/totals');
    totals.value = data;
}

async function submitForm() {
    formError.value = '';

    try {
        await api.post('/expenses', form.value);
        resetForm();
        await Promise.all([fetchExpenses(), fetchTotals()]);
    } catch (error) {
        const errors = error.response?.data?.errors ?? {};
        formError.value = Object.values(errors)[0]?.[0] ?? 'Something went wrong. Please try again.';
    }
}

function askDelete(id) {
    confirmingDeleteId.value = id;
}

function cancelDelete() {
    confirmingDeleteId.value = null;
}

async function confirmDelete(id) {
    await api.delete(`/expenses/${id}`);
    confirmingDeleteId.value = null;
    await Promise.all([fetchExpenses(), fetchTotals()]);
}

function formatAmount(amount) {
    return `$${Number(amount).toFixed(2)}`;
}

onMounted(() => {
    fetchCategories();
    fetchExpenses();
    fetchTotals();
});
</script>

<template>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form @submit.prevent="submitForm" class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:items-start">
                <div>
                    <input
                        v-model="form.date"
                        type="date"
                        required
                        class="w-full rounded-sm border border-border px-3 py-2 font-body text-body focus:outline-none focus:ring-2 focus:ring-gold"
                    />
                </div>

                <div>
                    <select
                        v-model="form.category_id"
                        required
                        class="w-full rounded-sm border border-border px-3 py-2 font-body text-body focus:outline-none focus:ring-2 focus:ring-gold"
                    >
                        <option value="" disabled>Select a category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <input
                        v-model="form.description"
                        type="text"
                        placeholder="Description"
                        class="w-full rounded-sm border border-border px-3 py-2 font-body text-body focus:outline-none focus:ring-2 focus:ring-gold"
                    />
                </div>

                <div>
                    <input
                        v-model="form.amount"
                        type="number"
                        step="0.01"
                        min="0.01"
                        placeholder="Amount"
                        class="w-full rounded-sm border border-border px-3 py-2 font-body text-body focus:outline-none focus:ring-2 focus:ring-gold"
                    />
                </div>

                <div class="sm:col-span-2">
                    <p v-if="formError" class="mb-2 font-body text-small text-danger">{{ formError }}</p>
                    <button type="submit" class="rounded-md bg-ink px-4 py-2 font-body font-semibold text-white">
                        Add expense
                    </button>
                </div>
            </form>

            <div class="rounded-lg border border-border">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border bg-bg">
                            <th class="px-4 py-2 text-left font-body text-small font-semibold text-muted">Date</th>
                            <th class="px-4 py-2 text-left font-body text-small font-semibold text-muted">Category</th>
                            <th class="px-4 py-2 text-left font-body text-small font-semibold text-muted">Description</th>
                            <th class="px-4 py-2 text-right font-body text-small font-semibold text-muted">Amount</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="expense in expenses" :key="expense.id">
                            <td class="px-4 py-3 font-body text-small text-text">{{ expense.date }}</td>
                            <td class="px-4 py-3 font-body text-small text-text">{{ expense.category?.name }}</td>
                            <td class="px-4 py-3 font-body text-small text-text">{{ expense.description }}</td>
                            <td class="px-4 py-3 text-right font-mono text-figure text-text">
                                {{ formatAmount(expense.amount) }}
                            </td>
                            <td class="relative px-4 py-3 text-right">
                                <button
                                    type="button"
                                    class="rounded-md border border-danger px-3 py-1 font-body text-small font-semibold text-danger"
                                    @click="askDelete(expense.id)"
                                >
                                    Delete
                                </button>

                                <div
                                    v-if="confirmingDeleteId === expense.id"
                                    class="absolute right-4 top-full z-10 mt-2 w-56 rounded-md border border-border bg-danger-bg p-3 text-left shadow-md"
                                >
                                    <p class="mb-2 font-body text-small text-text">Delete this expense?</p>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="rounded-sm px-2 py-1 font-body text-small text-muted"
                                            @click="cancelDelete"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="button"
                                            class="rounded-sm bg-danger px-2 py-1 font-body text-small font-semibold text-white"
                                            @click="confirmDelete(expense.id)"
                                        >
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="expenses.length === 0">
                            <td colspan="5" class="px-4 py-6 text-center font-body text-small text-muted">
                                No expenses yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-lg border border-border bg-surface p-4 shadow-sm">
            <h2 class="mb-4 font-display text-h3 font-semibold text-text">Spending by category</h2>
            <ExpensesChart :totals="totals" />
        </div>
    </div>
</template>
