<template>
  <jet-form-section @submitted="createTeam">
    <template #title> Institution Details </template>

    <template #description>
      Create a new Institution to collaborate with others on projects.
    </template>

    <template #form>
      <div class="col-span-6">
        <jet-label value="Institution Owner" />

        <div class="flex items-center mt-2">
          <img
            class="w-12 h-12 rounded-full object-cover"
            :src="$page.props.user.profile_photo_url"
            :alt="$page.props.user.name"
          />

          <div class="ml-4 leading-tight">
            <div>{{ $page.props.user.name }}</div>
            <div class="text-gray-700 text-sm">
              {{ $page.props.user.email }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label
          for="name"
          value="Intitution Name"
        />
        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autofocus
        />
        <jet-input-error
          :message="form.errors.name"
          class="mt-2"
        />
      </div>
    </template>

    <template #actions>
      <PrimaryButton :disabled="form.processing">
        Create
      </PrimaryButton>
    </template>
  </jet-form-section>
</template>

<script>
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
  components: {
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    PrimaryButton,
  },

  data() {
    return {
      form: this.$inertia.form({
        name: "",
      }),
    };
  },

  methods: {
    createTeam() {
      this.form.post(route("teams.store"), {
        errorBag: "createTeam",
        preserveScroll: true,
      });
    },
  },
};
</script>
