<template>
  <app-layout>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <api-token-manager
        :tokens="tokens"
        :available-permissions="availablePermissions"
        :default-permissions="defaultPermissions"
      />
    </div>
  </app-layout>
</template>

<script>
import ApiTokenManager from "./ApiTokenManager";
import AppLayout from "@/Layouts/AppLayout";

export default {
  props: ["tokens", "availablePermissions", "defaultPermissions"],

  components: {
    ApiTokenManager,
    AppLayout,
  },
};
</script>
