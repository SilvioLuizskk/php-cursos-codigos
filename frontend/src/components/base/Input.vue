<template>
  <div class="w-full">
    <label v-if="label" class="block text-sm font-semibold text-white mb-2">
      {{ label }}
      <span v-if="required" class="text-red-400">*</span>
    </label>
    <div class="relative">
      <input
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :class="[
          'w-full px-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all duration-300',
          error ? 'border-red-400 focus:ring-red-400' : '',
          disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white/15',
        ]"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur')"
        @focus="$emit('focus')"
      />
      <div
        v-if="type === 'password'"
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
      >
        <svg
          class="w-5 h-5 text-white/60"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
          ></path>
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
          ></path>
        </svg>
      </div>
      <div
        v-else-if="type === 'email'"
        class="absolute inset-y-0 right-0 pr-3 flex items-center"
      >
        <svg
          class="w-5 h-5 text-white/60"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
          ></path>
        </svg>
      </div>
    </div>
    <p v-if="error" class="text-red-400 text-sm mt-2 flex items-center">
      <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
        <path
          fill-rule="evenodd"
          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
          clip-rule="evenodd"
        ></path>
      </svg>
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: String,
  type: {
    type: String,
    default: "text",
  },
  label: String,
  placeholder: String,
  error: String,
  required: Boolean,
  disabled: Boolean,
});

defineEmits(["update:modelValue", "blur", "focus"]);
</script>
