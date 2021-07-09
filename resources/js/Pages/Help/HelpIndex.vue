<template>
  <AppLayout>
    <div class="bg-white">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
          <div>
            <h2 class="text-3xl font-extrabold text-gray-900">
              Frequently asked questions
            </h2>
          </div>
          <div class="mt-12 lg:mt-0 lg:col-span-2">
            <dl class="space-y-12">
              <div
                v-for="faq of faqs"
                :key="faq.id"
              >
                <dt class="text-lg leading-6 font-medium text-gray-900">
                  {{ faq.question }}
                </dt>
                <dd class="mt-2 text-base text-gray-500">
                  {{ faq.answer }}
                </dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
  props: {
    faqs: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>
</style>
