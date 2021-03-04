<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>
    <div class="grid grid-col-2">
      <div>
        <h2>New Location</h2>
      </div>
      <div></div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
};
</script>

<style>
</style>
