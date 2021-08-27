<template>
  <JetFormSection @submitted="updatePassword">
    <template #title>
      Update Password
    </template>

    <template #description>
      Ensure your account is using a long, random password to stay secure.
    </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <TextInput
          v-model="form.current_password"
          type="password"
          label="Current Password"
          autocomplete="current-password"
          ref="current_password"
        />
        <JetInputError
          :message="form.errors.current_password"
          class="mt-2"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <TextInput
          v-model="form.password"
          type="password"
          label="New Password"
          autocomplete="new-password"
          ref="password"
        />
        <JetInputError
          :message="form.errors.password"
          class="mt-2"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <TextInput
          v-model="form.password_confirmation"
          type="password"
          label="Confirm Password"
          autocomplete="new-password"
        />
        <JetInputError
          :message="form.errors.password_confirmation"
          class="mt-2"
        />
      </div>
    </template>

    <template #actions>
      <JetActionMessage
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Saved.
      </JetActionMessage>

      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </JetFormSection>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

export default {
  components: {
    PrimaryButton,
    SecondaryButton,
    TextInput,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
  },

  data() {
    return {
      form: this.$inertia.form({
        current_password: "",
        password: "",
        password_confirmation: "",
      }),
    };
  },

  methods: {
    updatePassword() {
      this.form.put(route("user-password.update"), {
        errorBag: "updatePassword",
        preserveScroll: true,
        onSuccess: () => this.form.reset(),
        onError: () => {
          if (this.form.errors.password) {
            this.form.reset("password", "password_confirmation");
            this.$refs.password.focus();
          }

          if (this.form.errors.current_password) {
            this.form.reset("current_password");
            this.$refs.current_password.focus();
          }
        },
      });
    },
  },
};
</script>
