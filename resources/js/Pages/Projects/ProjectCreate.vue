<template>
  <SiteHead title="Project Create" />

  <AppLayout>
    <AmazingMap
      ref="map"
      :preview="true"
    />

    <SlideOver
      title="Create a Project"
      headerBackground="bg-purple-600"
      subtitleTextColor="text-gray-100"
      alwaysOpen
    >
      <!-- {{ form.errors }} -->
      <!-- Project Name -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="sm:col-span-3">
          <TextInput
            v-model="form.name"
            description="The name that this Project is going to have."
            label="Name"
            :required="true"
          />
        </div>
        <JetInputError
          v-show="form.errors.name"
          :message="form.errors.name"
          class="mt-2"
        />
      </div>

      <!-- Project Description -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="sm:col-span-3">
          <TextInput
            v-model="form.description"
            description="The description that this Project is going to have."
            label="Description"
          />
        </div>
        <JetInputError
          v-show="form.errors.description"
          :message="form.errors.description"
          class="mt-2"
        />
      </div>

      <!-- Project Location -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="sm:col-span-3">
          <SelectMenu
            v-model="location"
            :options="locations"
            description="The Location that this Project is going to have."
            :required="true"
            label="Locations"
          />
        </div>
        <JetInputError
          v-show="form.errors.location_id"
          :message="form.errors.location_id"
          class="mt-2"
        />
      </div>

      <template #actions>
        <SecondaryOutlinedButton
          type="button"
          :disabled="form.processing"
          @click="onCancel"
        >
          Cancel
        </SecondaryOutlinedButton>
        <PrimaryButton
          @click="onSubmit"
          :disabled="form.processing"
        >
          Save
        </PrimaryButton>
      </template>

    </SlideOver>
  </AppLayout>
</template>

<script>
import { ref, computed, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import route from "ziggy";

import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

export default {
  components: {
    SiteHead,
    AppLayout,
    AmazingMap,
    SlideOver,
    TextInput,
    SelectMenu,
    JetInputError,
    PrimaryButton,
    SecondaryOutlinedButton,
  },

  props: {
    locations: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const map = ref(null);

    const form = useForm({
      name: "",
      description: "",
      location_id: "",
    });

    const location = ref({});

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );

    const onCancel = () => {
      Inertia.visit(route("projects.index"));
    };

    watch(
      location,
      (location) => {
        if (location.key) form.location_id = location.key;
      },
      { deep: true }
    );

    const onSubmit = () => {
      form.post(route("projects.store"), {
        onError: (e) => console.log(e),
        onSuccess: () => {
          Inertia.visit(route("projects.index"));
        },
      });
    };

    return {
      map,
      form,
      location,
      locations,
      onCancel,
      onSubmit,
    };
  },
};
</script>
