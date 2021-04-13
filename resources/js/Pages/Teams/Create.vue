<template>
  <app-layout>
    <template #content>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <create-team-form />
      </div>
    </template>
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
