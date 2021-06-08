<template>
  <app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <update-team-name-form
        :team="team"
        :permissions="permissions"
      />

      <team-member-manager
        class="mt-10 sm:mt-0"
        :team="team"
        :available-roles="availableRoles"
        :available-permissions="availablePermissions"
        :user-permissions="permissions"
      />

      <template v-if="permissions.canDeleteTeam && !team.personal_team">
        <jet-section-border />

        <delete-team-form
          class="mt-10 sm:mt-0"
          :team="team"
        />
      </template>
    </div>
  </app-layout>
</template>

<script>
import TeamMemberManager from "./TeamMemberManager";
import AppLayout from "@/Layouts/AppLayout";
import DeleteTeamForm from "./DeleteTeamForm";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import UpdateTeamNameForm from "./UpdateTeamNameForm";

export default {
  props: {
    team: {
      type: Object,
      required: true,
    },
    availableRoles: {
      type: Array,
      required: true,
    },
    availablePermissions: {
      type: Array,
      required: true,
    },
    permissions: {
      type: Object,
      required: true,
    },
  },

  components: {
    AppLayout,
    DeleteTeamForm,
    JetSectionBorder,
    TeamMemberManager,
    UpdateTeamNameForm,
  },

  setup(props) {},
};
</script>
