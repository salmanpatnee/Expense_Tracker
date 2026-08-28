<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';

const PALETTE = ['#14213D', '#C9A227', '#6B4226', '#3E6259', '#7A5980', '#8A8D91'];

const categories = ref([]);
const name = ref('');
const editingId = ref(null);
const formError = ref('');
const confirmingDeleteId = ref(null);

function swatchFor(index) {
    return PALETTE[index % PALETTE.length];
}

async function fetchCategories() {
    const { data } = await api.get('/categories');
    categories.value = data;
}

function startEdit(category) {
    editingId.value = category.id;
    name.value = category.name;
    formError.value = '';
}

function cancelEdit() {
    editingId.value = null;
    name.value = '';
    formError.value = '';
}

async function submitForm() {
    formError.value = '';

    try {
        if (editingId.value) {
            await api.put(`/categories/${editingId.value}`, { name: name.value });
        } else {
            await api.post('/categories', { name: name.value });
        }

        cancelEdit();
        await fetchCategories();
    } catch (error) {
        formError.value = error.response?.data?.errors?.name?.[0] ?? 'Something went wrong. Please try again.';
    }
}

function askDelete(id) {
    confirmingDeleteId.value = id;
}

function cancelDelete() {
    confirmingDeleteId.value = null;
}

async function confirmDelete(id) {
    await api.delete(`/categories/${id}`);
    confirmingDeleteId.value = null;
    await fetchCategories();
}

onMounted(fetchCategories);
</script>

<template>
    <div>
        <form @submit.prevent="submitForm" class="mb-6 flex items-start gap-3">
            <div class="flex-1">
                <input
                    v-model="name"
                    type="text"
                    placeholder="Category name"
                    class="w-full rounded-sm border border-border px-3 py-2 font-body text-body focus:outline-none focus:ring-2 focus:ring-gold"
                />
                <p v-if="formError" class="mt-1 font-body text-small text-danger">{{ formError }}</p>
            </div>
            <button
                type="submit"
                class="rounded-md bg-ink px-4 py-2 font-body font-semibold text-white"
            >
                {{ editingId ? 'Update' : 'Add' }}
            </button>
            <button
                v-if="editingId"
                type="button"
                class="rounded-md border border-border px-4 py-2 font-body font-semibold text-muted"
                @click="cancelEdit"
            >
                Cancel
            </button>
        </form>

        <ul class="divide-y divide-border rounded-lg border border-border">
            <li
                v-for="(category, index) in categories"
                :key="category.id"
                class="flex items-center justify-between gap-4 px-4 py-3"
            >
                <div class="flex items-center gap-3">
                    <span
                        class="inline-block h-3 w-3 rounded-full"
                        :style="{ backgroundColor: swatchFor(index) }"
                    ></span>
                    <span class="font-body text-body text-text">{{ category.name }}</span>
                </div>

                <div class="relative flex items-center gap-2">
                    <button
                        type="button"
                        class="rounded-md border border-border px-3 py-1 font-body text-small font-semibold text-ink"
                        @click="startEdit(category)"
                    >
                        Edit
                    </button>
                    <button
                        type="button"
                        class="rounded-md border border-danger px-3 py-1 font-body text-small font-semibold text-danger"
                        @click="askDelete(category.id)"
                    >
                        Delete
                    </button>

                    <div
                        v-if="confirmingDeleteId === category.id"
                        class="absolute right-0 top-full z-10 mt-2 w-56 rounded-md border border-border bg-danger-bg p-3 shadow-md"
                    >
                        <p class="mb-2 font-body text-small text-text">Delete "{{ category.name }}"?</p>
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
                                @click="confirmDelete(category.id)"
                            >
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </li>

            <li v-if="categories.length === 0" class="px-4 py-6 text-center font-body text-small text-muted">
                No categories yet.
            </li>
        </ul>
    </div>
</template>
