<template>
  <SlideOver
    :title="!form.segments.length ? 'New Link' : 'New Segment'"
    :subtitle="!form.segments.length ? 'Get started by selecting a marker to start your segments.' : `Create a new segment for the link ${form.name}`"
    :headerBackground="!form.segments.length ? 'bg-blue-700' : 'bg-green-700'"
    :closeOnEscape="false"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 items-center">
      <div class="flex flex-col place-content-center">
        <label
          for="name"
          class="block text-sm font-medium text-gray-900"
        >
          {{!form.segments.length ? 'Name' : 'Link Name'}}
        </label>
      </div>
      <div class="flex flex-col place-content-center sm:col-span-2">
        <TextInput
          v-model="form.name"
          placeholder="Link's Name"
          :disabled="form.segments.length"
        />
      </div>
    </div>
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 items-center">
      <div class="flex flex-col place-content-center">
        <label
          for="description"
          class="block text-sm font-medium text-gray-900"
        >
          {{!form.segments.length ? 'Description' : 'Link Description'}}
        </label>
      </div>
      <div class="flex flex-col place-content-center sm:col-span-2">
        <TextInput
          v-model="form.description"
          placeholder="Link's Description"
          :disabled="form.segments.length"
        />
      </div>
    </div>

    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <h1>Segments</h1>
    </div>

    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="(link, index) in linkList"
      :key="link"
    >
      <div class="sm:col-span-3">
        <Disclosure
          as="div"
          v-slot="{ open }"
        >
          <dt class="text-lg">
            <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
              <span class="font-medium text-gray-900">
                Segment #{{ index + 1 }}</span>
              <span class="ml-6 h-7 flex items-center">
                <ChevronDownIcon
                  :class="[
                    open ? '-rotate-180' : 'rotate-0',
                    'h-6 w-6 transform',
                  ]"
                  aria-hidden="true"
                />
              </span>
            </DisclosureButton>
          </dt>
          <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
          >
            <DisclosurePanel
              as="dd"
              class="mt-2 pr-12"
            >
              <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div>
                  <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                    From
                  </label>
                </div>
                <div class="sm:col-span-2">
                  <div>
                    <TextInput
                      v-model="link.from"
                      :disabled="true"
                    />
                  </div>
                </div>
              </div>
              <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div>
                  <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                    To
                  </label>
                </div>
                <div class="sm:col-span-2">
                  <div>
                    <TextInput
                      v-model="link.to"
                      :disabled="true"
                    />
                  </div>
                </div>
              </div>
              <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div>
                  <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                    Distance
                  </label>
                </div>
                <div class="sm:col-span-2">
                  <div>
                    <TextInput
                      v-model="link.distance"
                      :disabled="true"
                      unit="m"
                    />
                  </div>
                </div>
              </div>
              <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div>
                  <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                    Cost
                  </label>
                </div>
                <div class="sm:col-span-2">
                  <div>
                    <TextInput
                      v-model="link.cost"
                      unit="€/m"
                    />
                  </div>
                </div>
              </div>
              <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div>
                  <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                    Depth
                  </label>
                </div>
                <div class="sm:col-span-2">
                  <div>
                    <TextInput
                      v-model="link.depth"
                      unit="m"
                    />
                  </div>
                </div>
              </div>
            </DisclosurePanel>
          </transition>
        </Disclosure>
      </div>
    </div>

    <pre>{{ form.errors }}</pre>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        :disabled="form.processing"
        @click="onCancel"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton
        @click="submit"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import { useStore } from "vuex";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { DatabaseIcon, ChevronDownIcon } from "@heroicons/vue/outline";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    DatabaseIcon,
    ChevronDownIcon,
  },

  setup() {
    const store = useStore();
    const form = useForm({
      name: "",
      description: "",
      segments: [],
    });

    let linkId = "";

    // watch(form, (value) => console.log(value));

    const submit = () => {
      if (form.segments.length) {
        return createSegment();
      }

      form.segments = linkList.value;

      form
        .transform((data) => {
          console.log("Form data is:", data);
          return data;
        })
        .post(route("objects.links.store"), {
          onSuccess: () => {
            linkId = route().params.link;
            store.dispatch("map/saveLink", true);
            store.dispatch("map/refreshMap");
            store.commit("objects/closeSlide");
            //store.dispatch("objects/showSlide", { route: "objects.list" });
          },
          onError: (e) => console.log(e),
        });
    };

    const createSegment = () => {
      
      form.segments = linkList.value.filter((el, index) => index > form.segments.length -1);

      form
        .transform((data) => {
          console.log("Form data is:", data);
          return data;
        })
        .patch(route("objects.links.update", linkId), {
          onSuccess: () => {
            store.dispatch("map/saveLink", true);
            store.dispatch("map/refreshMap");
            store.commit("objects/closeSlide");
          },
          onError: (e) => console.log(e),
        });
    };

    const links = computed(() => store.getters["map/currentLinks"]);
    const linkList = computed(() => Object.values(links.value));

    watch(
      linkList,
      (value) => {
        if(!value.length) {
          form.name = "";
          form.description = "";
          form.segments = [];
        }
      }, 
      {
        deep: true,
        immediate: true,
      }
    );
  
    return {
      form,
      submit,
      onCancel: () =>
        store.dispatch("objects/showSlide", { route: "objects.list" }),
      linkList,
    };
  },
};
</script>
