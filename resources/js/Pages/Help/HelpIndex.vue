<template>
  <AppLayout>
    <SiteHead title="Help" />

    <div class="bg-gray-50">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto divide-y-2 divide-gray-200">
          <h2 class="text-center text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Frequently Asked Questions
          </h2>
          <dl
            v-if="faqs.length"
            class="mt-6 space-y-6 divide-y divide-gray-200"
          >
            <Disclosure
              as="div"
              v-for="faq in faqs"
              :key="faq.id"
              class="pt-6"
              v-slot="{ open }"
            >
              <dt class="text-lg">
                <div>
                  <DisclosureButton
                    class="text-left w-full flex justify-between items-start text-gray-400"
                    :class="{'bg-yellow-200 rounded-md' : shouldBeHinted(faq.id)}"
                    @click="controlHinting(faq.id)"
                  >
                    <span class="font-medium text-gray-900">
                      {{ faq.question }}
                    </span>
                    <span class="ml-6 h-7 flex items-center">
                      <ChevronDownIcon
                        :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
                        aria-hidden="true"
                      />
                    </span>
                  </DisclosureButton>
                </div>
              </dt>
              <DisclosurePanel
                as="dd"
                class="mt-2 pr-12"
              >
                <p
                  class="text-base text-gray-500"
                  v-html="faq.answer"
                >
                </p>
              </DisclosurePanel>
            </Disclosure>
          </dl>
          <dl
            v-else
            class="flex justify-center pt-10"
          >
            <p class="block font-bold text-2xl text-gray-400 p-4">
              No FAQs were found.
            </p>
          </dl>
        </div>
      </div>
    </div>
  </AppLayout>
</template>


<script>
import { ref } from "vue";

import AppLayout from "@/Layouts/AppLayout";
import SiteHead from "@/Components/SiteHead.vue";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";

export default {
  components: {
    AppLayout,
    SiteHead,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
  },

  props: {
    faqs: {
      type: Array,
      required: true,
    },
    faqToFocus: {
      type: String,
      required: false,
    },
  },

  setup(props) {
    const query = ref(props.faqToFocus);

    const shouldBeHinted = (id) => {
      if (query.value == id) return true;

      return false;
    };

    const controlHinting = (id) => {
      if (query.value != id) return;

      query.value = null;

      window.history.pushState({}, window.document.title, "help");
    };

    return {
      controlHinting,
      shouldBeHinted,
    };
  },
};
</script>

<style>
</style>
