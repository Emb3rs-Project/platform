<template>
  <!-- <div v-if="notification.data.type === 'invitation'">
    <div>
      <div class="relative px-1">
        <div :class="[
                        notification.read_at
                          ? 'ring-white'
                          : 'ring-blue-500',
                          'h-8 w-8 bg-gray-100 rounded-full ring-8 flex items-center justify-center'
                        ]">
          <img
            class="rounded-full bg-gray-100 flex items-center justify-center ring-8"
            :src="notification.data.from.profile_photo_url"
            alt=""
          />
        </div>
      </div>
    </div>
    <div class="min-w-0 flex-1 py-0">
      <div class="text-sm leading-8 text-gray-500">
        <span>
          <span class="font-semibold">
            {{notification.data.from.email}}
          </span>
          invited you to join
          <span class="font-semibold">
            {{notification.data.team.name}} Institution
          </span>
        </span>
        {{ ' ' }}
        <span class="mr-0.5">
          <template
            v-for="(tagItem, tagItemIdx) in notification.data.tags"
            :key="tagItemIdx"
          >
            <div class="relative inline-flex items-center rounded-full border border-gray-300 px-3 py-0.5 text-sm">
              <span class="absolute flex-shrink-0 flex items-center justify-center">
                <span
                  :class="['bg-blue-300', 'h-1.5 w-1.5 rounded-full']"
                  aria-hidden="true"
                />
              </span>
              <span class="ml-3.5 font-medium text-gray-900">{{ tagItem }}</span>
            </div>
            {{ ' ' }}
          </template>
        </span>
        <span class="whitespace-nowrap">{{ notification.created_at }}</span>
      </div>
      <div class="mt-2 text-sm text-gray-700">
        <p>
          {{ notification.data.description }}
        </p>
      </div>
    </div>
  </div> -->
  <div class="flex min-w-full">
    <p>{{notification.id}}</p>
    <img
      class="h-10 w-10 rounded-full"
      :src="notification.data.from.profile_photo_url"
      alt=""
    />
    <div class="ml-3">
      <p class="text-sm font-medium text-gray-900 text-gray-500">
        <span class="font-semibold">
          {{ notification.data.from.email }}
        </span> invited you to join
        <span class="font-semibold">
          {{ notification.data.team.name }} Institution
        </span>
      </p>
      <!-- <p class="text-sm text-gray-500">{{ person.email }}</p> -->
    </div>
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
