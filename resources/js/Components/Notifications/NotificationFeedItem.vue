<template>
  <div class="p-2">
    <div v-if="notification.data.type === 'invitation'">
      <div class="flex items-center">
        <div class="flex-shrink-0 inline-flex rounded-full border-2 border-white">
          <img
            class="h-12 w-12 rounded-full"
            :src="notification.data.from.profile_photo_url"
            alt=""
          />
        </div>
        <div class="ml-3">
          <div class="text-sm font-medium text-gray-900">
            <span class="font-semibold">{{ notification.data.from.name }}</span>
            ({{ notification.data.from.email }})
            added you in
            <span class="font-semibold">
              "{{ notification.data.team.name }}"
            </span>
            Institution
          </div>
        </div>
      </div>
    </div>
    <div v-else>DEBUG: Unknown notification type passed</div>

  </div>
</template>

<script>
export default {
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },
};
</script>
