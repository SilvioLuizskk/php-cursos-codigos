<template>
    <div class="reviews-section mt-10">
        <h2 class="text-xl font-bold mb-4 flex items-center">
            <i class="fas fa-star text-yellow-400 mr-2"></i> Avaliações dos
            Clientes
        </h2>
        <div v-if="reviews.length === 0" class="text-gray-400 mb-6">
            Nenhuma avaliação ainda.
        </div>
        <ul v-else class="mb-6">
            <li
                v-for="review in reviews"
                :key="review.id"
                class="mb-4 border-b pb-2"
            >
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-bold">{{ review.user }}</span>
                    <span class="text-yellow-400">
                        <i
                            v-for="n in 5"
                            :key="n"
                            :class="
                                n <= review.rating
                                    ? 'fas fa-star'
                                    : 'far fa-star'
                            "
                        ></i>
                    </span>
                </div>
                <div class="text-gray-600 text-sm">{{ review.comment }}</div>
                <div v-if="review.photo" class="mt-2">
                    <img
                        :src="review.photo"
                        class="w-20 h-20 object-cover rounded border"
                        alt="Foto do cliente"
                    />
                </div>
            </li>
        </ul>
        <div class="bg-gray-50 rounded-lg p-4 shadow-sm">
            <h3 class="font-semibold mb-2">Deixe sua avaliação</h3>
            <form @submit.prevent="submitReview">
                <div class="flex items-center mb-2">
                    <span
                        v-for="n in 5"
                        :key="n"
                        class="cursor-pointer text-2xl"
                        @click="form.rating = n"
                    >
                        <i
                            :class="
                                n <= form.rating
                                    ? 'fas fa-star text-yellow-400'
                                    : 'far fa-star text-gray-300'
                            "
                        ></i>
                    </span>
                </div>
                <input
                    v-model="form.user"
                    type="text"
                    placeholder="Seu nome"
                    class="input mb-2"
                    required
                />
                <textarea
                    v-model="form.comment"
                    placeholder="Seu comentário"
                    class="input mb-2"
                    required
                    rows="2"
                ></textarea>
                <input type="file" @change="onFileChange" class="mb-2" />
                <button class="btn btn-primary w-full" type="submit">
                    Enviar Avaliação
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    productId: { type: [String, Number], required: true },
    initialReviews: { type: Array, default: () => [] },
});

const reviews = ref([...props.initialReviews]);
const form = ref({ user: "", rating: 0, comment: "", photo: null });

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
            form.value.photo = ev.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submitReview = () => {
    if (!form.value.user || !form.value.rating || !form.value.comment) return;
    reviews.value.unshift({
        id: Date.now(),
        user: form.value.user,
        rating: form.value.rating,
        comment: form.value.comment,
        photo: form.value.photo,
    });
    form.value = { user: "", rating: 0, comment: "", photo: null };
};
</script>

<style scoped>
.input {
    width: 100%;
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    outline: none;
    margin-bottom: 0.25rem;
}
.input:focus {
    box-shadow: 0 0 0 2px #2563eb33;
    border-color: #2563eb;
}
.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-weight: 600;
    transition:
        background 0.2s,
        color 0.2s;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
</style>
