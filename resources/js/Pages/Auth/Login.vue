<template>
  <div class="bg-gray-100 h-screen">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <jet-authentication-card-logo />
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
      </div>

      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <form
            @submit.prevent="submit"
            class="space-y-6"
          >
            <jet-validation-errors class="mb-4" />

            <div
              v-if="status"
              class="mb-4 font-medium text-sm text-green-600"
            >
              {{ status }}
            </div>
            <div>
              <label
                for="email"
                class="block text-sm font-medium text-gray-700"
              >
                Email address
              </label>
              <div class="mt-1">
                <input
                  v-model="form.email"
                  id="email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
              </div>
            </div>

            <div>
              <label
                for="password"
                class="block text-sm font-medium text-gray-700"
              >
                Password
              </label>
              <div class="mt-1">
                <input
                  v-model="form.password"
                  id="password"
                  name="password"
                  type="password"
                  autocomplete="current-password"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
              </div>
            </div>

            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <jet-checkbox
                  id="remember-me"
                  name="remember-me"
                  v-model:checked="form.remember"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label
                  for="remember-me"
                  class="ml-2 block text-sm text-gray-900"
                >
                  Remember me
                </label>
              </div>

              <div class="text-sm">
                <Link
                  v-if="canResetPassword"
                  :href="route('password.request')"
                  class="font-medium text-blue-600 hover:text-blue-500"
                >
                Forgot your password?
                </Link>
              </div>
            </div>

            <div>
              <button
                type="submit"
                :class="[form.processing ? 'opacity-25 hover:bg-blue-600': 'hover:bg-blue-700', 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']"
                :disabled="form.processing"
              >
                Sign in
              </button>
            </div>
            <div class="flex items-center justify-center">
              <div class="text-sm">
                <Link
                  :href="route('register')"
                  class="font-medium text-blue-600 hover:text-blue-500"
                >
                Don't have an account?
                </Link>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetValidationErrors from "@/Jetstream/ValidationErrors";

export default {
  components: {
    Link,
    JetAuthenticationCardLogo,
    JetCheckbox,
    JetValidationErrors,
  },

  props: {
    canResetPassword: Boolean,
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: "",
        password: "",
        remember: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? "on" : "",
        }))
        .post(this.route("login"), {
          onFinish: () => this.form.reset("password"),
        });
    },
  },
};
</script>
