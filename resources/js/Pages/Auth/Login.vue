<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineProps({
  canResetPassword: Boolean,
  status: String,
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};

const loginWithGoogle = () => {
  window.location.href = route("google.redirect");
};
</script>

<template>
  <Head title="Login" />

  <GuestLayout>
    <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">
      Login ke TUKEL
    </h1>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="current-password"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="flex items-center justify-center mt-4">
        <PrimaryButton class="ms-4" :disabled="form.processing">
          Log in
        </PrimaryButton>
      </div>
    </form>

    <div class="mt-6">
      <div class="flex items-center justify-center">
        <div class="border-t border-gray-300 w-1/4"></div>
        <span class="mx-2 text-gray-500 text-sm">atau</span>
        <div class="border-t border-gray-300 w-1/4"></div>
      </div>

      <button
        type="button"
        @click="loginWithGoogle"
        class="mt-4 flex items-center justify-center gap-2 w-full py-2 px-4 border border-gray-300 rounded hover:bg-gray-100 transition"
      >
        <img
          src="https://www.svgrepo.com/show/475656/google-color.svg"
          width="20"
          alt="Google Logo"
        />
        <span class="text-gray-700 font-medium">Login dengan Google</span>
      </button>
    </div>
  </GuestLayout>
</template>
