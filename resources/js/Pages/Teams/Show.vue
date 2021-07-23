<template>
  <AppLayout>

    <SiteHead :title="`${team.name} Institution`" />
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
  </AppLayout>
</template>

<script>
import TeamMemberManager from "./TeamMemberManager";
import AppLayout from "@/Layouts/AppLayout";
import SiteHead from "@/Components/SiteHead.vue";
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
      default: [],
    },
    availablePermissions: {
      type: Array,
      default: [],
    },
    permissions: {
      type: Object,
      required: true,
    },
  },

  components: {
    AppLayout,
    SiteHead,
    DeleteTeamForm,
    JetSectionBorder,
    TeamMemberManager,
    UpdateTeamNameForm,
  },
};
</script>
