<script setup>
import { ref } from 'vue';

const activeTab = ref('expenses');
</script>

<template>
    <div class="min-h-screen bg-bg">
        <header class="bg-ink text-white px-8 py-6 shadow-md">
            <h1 class="font-display text-2xl font-bold">Expense Tracker</h1>
        </header>

        <nav class="flex gap-6 border-b border-border bg-surface px-8">
            <button
                type="button"
                class="border-b-2 px-2 py-4 font-body font-semibold"
                :class="activeTab === 'expenses' ? 'border-gold text-ink' : 'border-transparent text-muted'"
                @click="activeTab = 'expenses'"
            >
                Expenses
            </button>
            <button
                type="button"
                class="border-b-2 px-2 py-4 font-body font-semibold"
                :class="activeTab === 'categories' ? 'border-gold text-ink' : 'border-transparent text-muted'"
                @click="activeTab = 'categories'"
            >
                Categories
            </button>
        </nav>

        <main class="p-8">
            <section v-if="activeTab === 'expenses'" class="rounded-lg border border-border bg-surface p-6 shadow-sm">
                <p class="font-body text-muted">Expenses panel — coming in Phase 4.</p>
            </section>
            <section v-else class="rounded-lg border border-border bg-surface p-6 shadow-sm">
                <p class="font-body text-muted">Categories panel — coming in Phase 2.</p>
            </section>
        </main>
    </div>
</template>
