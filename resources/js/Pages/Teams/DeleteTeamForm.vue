<template>
  <jet-action-section>
    <template #title> Delete Institution </template>

    <template #description> Permanently delete this Institution. </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
        Once a Institution is deleted, all of its resources and data will be
        permanently deleted. Before deleting this institution, please download
        any data or information regarding this institution that you wish to
        retain.
      </div>

      <div class="mt-5">
        <danger-button @click="confirmTeamDeletion">
          Delete Institution
        </danger-button>
      </div>

      <!-- Delete Team Confirmation Modal -->
      <jet-confirmation-modal
        :show="confirmingTeamDeletion"
        @close="confirmingTeamDeletion = false"
      >
        <template #title> Delete Institution </template>

        <template #content>
          Are you sure you want to delete this Institution? Once an institution
          is deleted, all of its resources and data will be permanently deleted.
        </template>

        <template #footer>
          <secondary-outlined-button @click="confirmingTeamDeletion = false">
            Cancel
          </secondary-outlined-button>

          <danger-button
            :disabled="form.processing"
            class="ml-2"
            @click="deleteTeam"
          >
            Delete Institution
          </danger-button>
        </template>
      </jet-confirmation-modal>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";

import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

export default {
  props: ["team"],

  components: {
    JetActionSection,
    JetConfirmationModal,
    SecondaryOutlinedButton,
    DangerButton,
  },

  data() {
    return {
      confirmingTeamDeletion: false,
      deleting: false,

      form: this.$inertia.form(),
    };
  },

  methods: {
    confirmTeamDeletion() {
      this.confirmingTeamDeletion = true;
    },

    deleteTeam() {
      this.form.delete(route("teams.destroy", this.team), {
        errorBag: "deleteTeam",
      });
    },
  },
};
</script>
