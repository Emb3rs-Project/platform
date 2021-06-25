<template>
  <jet-form-section @submitted="updateTeamName">
    <template #title> Institution Name </template>

    <template #description>
      The institution's name and owner information.
    </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="col-span-6">
        <jet-label value="Team Owner" />

        <div class="flex items-center mt-2">
          <img
            class="w-12 h-12 rounded-full object-cover"
            :src="team.owner.profile_photo_url"
            :alt="team.owner.name"
          />

          <div class="ml-4 leading-tight">
            <div>{{ team.owner.name }}</div>
            <div class="text-gray-700 text-sm">{{ team.owner.email }}</div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4">
        <jet-label
          for="name"
          value="Team Name"
        />

        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          :disabled="!permissions.canUpdateTeam"
        />

        <jet-input-error
          :message="form.errors.name"
          class="mt-2"
        />
      </div>
    </template>

    <template
      #actions
      v-if="permissions.canUpdateTeam"
    >
      <jet-action-message
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Saved.
      </jet-action-message>

      <primary-button :disabled="form.processing">
        Save
      </primary-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
  components: {
    JetActionMessage,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    PrimaryButton,
  },

  props: ["team", "permissions"],

  data() {
    return {
      form: this.$inertia.form({
        name: this.team.name,
      }),
    };
  },

  methods: {
    updateTeamName() {
      this.form.put(route("teams.update", this.team), {
        errorBag: "updateTeamName",
        preserveScroll: true,
      });
    },
  },
};
</script>
