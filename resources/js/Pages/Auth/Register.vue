<template>
  <div class="bg-gray-100 h-screen">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <jet-authentication-card-logo />
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create an account
        </h2>
      </div>

      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
          <form
            @submit.prevent="submit"
            class="space-y-6"
          >
            <jet-validation-errors class="mb-4" />

            <div>
              <label
                for="name"
                class="block text-sm font-medium text-gray-700"
              >
                Name
              </label>
              <div class="mt-1">
                <input
                  v-model="form.name"
                  id="name"
                  name="name"
                  type="text"
                  autocomplete="name"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
              </div>
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
                  autocomplete="new-password"
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
                Confirm Password
              </label>
              <div class="mt-1">
                <input
                  v-model="form.password_confirmation"
                  id="password"
                  name="password"
                  type="password"
                  autocomplete="new-password"
                  required
                  class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                />
              </div>
            </div>
              <div class="mt-4">
                  <jet-label for="terms">
                      <div class="flex items-center">
                          <jet-checkbox v-model="form.terms" name="terms" id="terms" />
                          <div class="ml-2">
                              I agree to the <a target="_blank" href="https://www.pdmfc.com/privacy_policy.html" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a>
                              and
                              <a target="_blank" href="/privacy-policy" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                          </div>
                      </div>
                  </jet-label>
              </div>
            <div>
              <button
                type="submit"
                :class="[form.processing ? 'opacity-25 hover:bg-blue-600': 'hover:bg-blue-700', 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']"
                :disabled="form.processing"
              >
                Sign up
              </button>
            </div>
            <div class="flex items-center justify-center">
              <div class="text-sm">
                <Link
                  :href="route('login')"
                  class="font-medium text-blue-600 hover:text-blue-500"
                >
                Already having an account?
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
import JetValidationErrors from "@/Jetstream/ValidationErrors";
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    Link,
    JetCheckbox,
    JetLabel,
    JetAuthenticationCardLogo,
    JetValidationErrors,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("register"), {
        onFinish: () => this.form.reset("password", "password_confirmation"),
      });
    },
  },
};
</script>
