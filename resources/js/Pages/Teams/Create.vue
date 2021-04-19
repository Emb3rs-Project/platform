<template>
  <app-layout>
    <div class="max-w-7xl mx-auto mt-20 sm:px-6 lg:px-8">
      <create-team-form />
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import CreateTeamForm from "./CreateTeamForm";

export default {
  components: {
    AppLayout,
    CreateTeamForm,
  },
};
</script>
