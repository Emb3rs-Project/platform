<template>
  <AppLayout>

    <SiteHead title="Create an Institution" />
    <div class="max-w-7xl mx-auto mt-20 sm:px-6 lg:px-8">
      <create-team-form />
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import SiteHead from "@/Components/SiteHead.vue";
import CreateTeamForm from "./CreateTeamForm";

export default {
  components: {
    AppLayout,
    SiteHead,
    CreateTeamForm,
  },
};
</script>
