<template>
  <AppLayout>
    <div class="bg-white">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
          <div class="mt-12 lg:mt-0 lg:col-span-3">
            <div class="mt-10 sm:mt-0 p-10">
              <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                  <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-5">
                      Simulation
                    </h3>

                    <nav aria-label="Progress">
                      <ol class="overflow-hidden">
                        <li
                          class="relative"
                          v-bind:class="{ 'pb-10': i < steps.length - 1 }"
                          v-for="(step, i) of steps"
                          :key="step.id"
                        >
                          <div
                            v-if="i < steps.length - 1"
                            class="-ml-px absolute mt-0.5 top-4 left-4 w-0.5 h-full bg-indigo-600"
                            aria-hidden="true"
                          ></div>
                          <a
                            href="#"
                            class="relative flex items-start group"
                            @click="setStep(step.id)"
                          >
                            <!-- Complete -->
                            <span
                              class="h-9 flex items-center"
                              v-if="step.completed"
                            >
                              <span class="relative z-10 w-8 h-8 flex items-center justify-center bg-indigo-600 rounded-full group-hover:bg-indigo-800">
                                <!-- Heroicon name: solid/check -->
                                <svg
                                  class="w-5 h-5 text-white"
                                  xmlns="http://www.w3.org/2000/svg"
                                  viewBox="0 0 20 20"
                                  fill="currentColor"
                                  aria-hidden="true"
                                >
                                  <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                  />
                                </svg>
                              </span>
                            </span>
                            <!-- Upcoming -->
                            <span
                              class="h-9 flex items-center"
                              aria-hidden="true"
                              v-if="!step.completed && !step.active"
                            >
                              <span class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-gray-300 rounded-full group-hover:border-gray-400">
                                <span class="h-2.5 w-2.5 bg-transparent rounded-full group-hover:bg-gray-300"></span>
                              </span>
                            </span>
                            <!-- Active -->
                            <span
                              class="h-9 flex items-center"
                              aria-hidden="true"
                              v-if="step.active"
                            >
                              <span class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-indigo-600 rounded-full">
                                <span class="h-2.5 w-2.5 bg-indigo-600 rounded-full"></span>
                              </span>
                            </span>
                            <span class="ml-4 min-w-0 flex flex-col">
                              <span class="text-xs font-semibold tracking-wide uppercase">
                                {{ step.title }}
                              </span>
                              <span class="text-sm text-gray-500">
                                {{ step.subtitle }}
                              </span>
                            </span>
                          </a>
                        </li>
                      </ol>
                    </nav>
                  </div>
                </div>

                <!-- Form Step 1 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 1"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <!-- Simulation Name -->
                        <div class="col-span-12">
                          <input-row
                            desc="Enter a name for the Simulation"
                            v-model="form.name"
                          >
                            Name
                          </input-row>
                        </div>
                        <!-- Simulation Type -->
                        <div class="col-span-12">
                          <select-row
                            desc="Select the simulation type"
                            :options="simulation_types_select"
                            v-model="form.type"
                          >
                            Simulation Type
                          </select-row>
                        </div>
                        <!-- Simulation Target -->
                        <div class="col-span-12">
                          <select-row
                            desc="Select the simulation target"
                            :options="targets"
                            v-model="form.target"
                          >
                            Simulation Target
                          </select-row>
                        </div>
                        <!-- Simulation Location -->
                        <div class="col-span-12">
                          <select-row
                            desc="Select the simulation location"
                            :options="locationSelect"
                            v-model="form.location"
                          >
                            Simulation Location
                          </select-row>
                        </div>
                        <!-- Notifications -->
                        <div class="col-span-12">
                          <select-row
                            desc="Select how you want to be notified"
                            :options="notifications"
                            v-model="form.notification"
                          >
                            Notification
                          </select-row>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(2)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Step 2 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 2"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <div
                          class="col-span-12"
                          v-for="i of [1, 2, 3]"
                          :key="i"
                        >
                          <!-- Constraints -->
                          <div class="col-span-12">
                            <select-row
                              desc="Select a simulation Constraint"
                              :options="constraints"
                              v-model="form.constraint_type[i]"
                            >
                              Constraint
                            </select-row>
                          </div>
                          <!-- Simulation Name -->
                          <div class="col-span-12">
                            <input-row
                              desc="Above Constraint value"
                              v-model="form.constraint_value[i]"
                            >
                              Constraint Value
                            </input-row>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(3)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Step 3 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 3"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-12">
                          <h3 class="font-bold">Sources</h3>
                        </div>
                        <!-- Source -->
                        <div
                          class="col-span-12"
                          v-for="source of sources"
                          :key="source.id"
                        >
                          <div class="flex items-center justify-between">
                            <span
                              class="flex-grow flex flex-col"
                              id="availability-label"
                            >
                              <span class="text-sm font-medium text-gray-900">{{
                                source.name
                              }}</span>
                              <span class="text-sm text-gray-500">Template : {{ source.template.name }}</span>
                            </span>
                            <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                            <button
                              @click="checkSource(source.id)"
                              type="button"
                              v-bind:class="{
                                'bg-indigo-600': form.sources[source.id],
                                'bg-gray-200': !form.sources[source.id],
                              }"
                              class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                              aria-pressed="false"
                              aria-labelledby="availability-label"
                            >
                              <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                              <span
                                v-bind:class="{
                                  'translate-x-5': form.sources[source.id],
                                  'translate-x-0': !form.sources[source.id],
                                }"
                                aria-hidden="true"
                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                              ></span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(4)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Step 4 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 4"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-12">
                          <h3 class="font-bold">Sinks</h3>
                        </div>
                        <!-- Source -->
                        <div
                          class="col-span-12"
                          v-for="sink of sinks"
                          :key="sink.id"
                        >
                          <div class="flex items-center justify-between">
                            <span
                              class="flex-grow flex flex-col"
                              id="availability-label"
                            >
                              <span class="text-sm font-medium text-gray-900">{{
                                sink.name
                              }}</span>
                              <span class="text-sm text-gray-500">Template : {{ sink.template.name }}</span>
                            </span>
                            <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                            <button
                              @click="checkSink(sink.id)"
                              type="button"
                              v-bind:class="{
                                'bg-indigo-600': form.sinks[sink.id],
                                'bg-gray-200': !form.sinks[sink.id],
                              }"
                              class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                              aria-pressed="false"
                              aria-labelledby="availability-label"
                            >
                              <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                              <span
                                v-bind:class="{
                                  'translate-x-5': form.sinks[sink.id],
                                  'translate-x-0': !form.sinks[sink.id],
                                }"
                                aria-hidden="true"
                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                              ></span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(5)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Step 5 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 5"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-12">
                          <h3 class="font-bold">Links</h3>
                        </div>
                      </div>

                      <!-- Source -->
                      <div
                        class="col-span-12"
                        v-for="link of links"
                        :key="link.id"
                      >
                        <div class="flex items-center justify-between my-2">
                          <span
                            class="flex-grow flex flex-col"
                            id="availability-label"
                          >
                            <span class="text-sm font-medium text-gray-900">{{
                              link.name
                            }}</span>
                            <span class="text-sm text-gray-500">{{
                              link.description
                            }}</span>
                          </span>
                          <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                          <button
                            @click="checkLink(link.id)"
                            type="button"
                            v-bind:class="{
                              'bg-indigo-600': form.links[link.id],
                              'bg-gray-200': !form.links[link.id],
                            }"
                            class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            aria-pressed="false"
                            aria-labelledby="availability-label"
                          >
                            <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                            <span
                              v-bind:class="{
                                'translate-x-5': form.links[link.id],
                                'translate-x-0': !form.links[link.id],
                              }"
                              aria-hidden="true"
                              class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                            ></span>
                          </button>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(6)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Form Step 6 -->
                <div
                  class="mt-5 md:mt-0 md:col-span-2"
                  v-if="currentStep.id === 6"
                >
                  <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                      <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-12">
                          <h3 class="font-bold">Simulators</h3>
                        </div>
                        <!-- Source -->
                        <div
                          class="col-span-12"
                          v-for="simulator of simulators"
                          :key="simulator.id"
                        >
                          <div class="flex items-center justify-between">
                            <span
                              class="flex-grow flex flex-col"
                              id="availability-label"
                            >
                              <span class="text-sm font-medium text-gray-900">{{
                                simulator.name
                              }}</span>
                              <span class="text-sm text-gray-500">{{
                                simulator.description
                              }}</span>
                            </span>
                            <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                            <button
                              @click="checkSimulator(simulator.id)"
                              type="button"
                              v-bind:class="{
                                'bg-indigo-600': form.simulators[simulator.id],
                                'bg-gray-200': !form.simulators[simulator.id],
                              }"
                              class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                              aria-pressed="false"
                              aria-labelledby="availability-label"
                            >
                              <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                              <span
                                v-bind:class="{
                                  'translate-x-5':
                                    form.simulators[simulator.id],
                                  'translate-x-0': !form.simulators[
                                    simulator.id
                                  ],
                                }"
                                aria-hidden="true"
                                class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                              ></span>
                            </button>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <jet-button
                          :disabled="form.processing"
                          @click="setStep(4)"
                          class="mt-5"
                        >
                          Next
                        </jet-button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import { useForm } from "@inertiajs/inertia-vue3";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import CheckboxRow from "@/Components/CheckboxRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import { ref } from "@vue/reactivity";
import { computed } from "vue";

export default {
  components: {
    AppLayout,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
    CheckboxRow,
  },
  props: {
    simulationTypes: Array,
    sources: Array,
    sinks: Array,
    links: Array,
    locations: Array,
  },
  setup(props) {
    const form = useForm({
      name: "",
      type: null,
      target: null,
      location: null,
      notification: null,
      constraint_type: {},
      constraint_value: {},
      sources: {},
      sinks: {},
      simulators: {},
      links: {},
    });

    const currentStep = computed({
      get() {
        return steps.value.find((s) => s.active === true);
      },
      set(nextStep) {
        const cStep = steps.value.find((s) => s.active === true);
        cStep.active = false;
        cStep.completed = true;

        nextStep.active = true;
        nextStep.completed = false;
      },
    });

    const steps = ref([
      {
        id: 1,
        title: "Configurations",
        subtitle: "Define the simulations configurations",
        active: true,
        completed: false,
      },
      {
        id: 2,
        title: "Constraints",
        subtitle: "Define Simulation Constraints",
        active: false,
        completed: false,
      },
      {
        id: 3,
        title: "Sources",
        subtitle: "Define the sources to be used in the simulation",
        active: false,
        completed: false,
      },
      {
        id: 4,
        title: "Sinks",
        subtitle: "Define the sinks to be used in the simulation ",
        active: false,
        completed: false,
      },
      {
        id: 5,
        title: "Links",
        subtitle: "Define the links to be used in the simulation",
        active: false,
        completed: false,
      },
      {
        id: 6,
        title: "Simulators",
        subtitle: "Select which simulators to use",
        active: false,
        completed: false,
      },
    ]);

    const notifications = [
      { key: "email", value: "Notify me by email" },
      { key: "sms", value: "Notify me by SMS" },
      { key: "dashboard", value: "Notify me on Emb3rs Dashboard" },
    ];

    const constraints = [
      { key: "budget", value: "Budget" },
      { key: "roi", value: "RoI" },
      { key: "time", value: "Building Time" },
    ];

    const targets = [
      { key: "LTR", value: "Long Term Revenue" },
      { key: "ROI", value: "Minimize ROI" },
      { key: "CO2", value: "Minimize CO2 emissions" },
    ];

    const locationSelect = props.locations.map((l) => ({
      key: l.id,
      value: l.name,
    }));

    const simulation_types_select = props.simulationTypes.map((s) => ({
      key: s.id,
      value: s.name,
    }));

    const simulators = [
      {
        id: "prc",
        name: "Process",
        description: "Small description about the simulator",
      },
      {
        id: "gis",
        name: "GIS",
        description: "Small description about the simulator",
      },
      {
        id: "teo",
        name: "Techno-Economic",
        description: "Small description about the simulator",
      },
      {
        id: "mkt",
        name: "Market",
        description: "Small description about the simulator",
      },
      {
        id: "bus",
        name: "Business",
        description: "Small description about the simulator",
      },
    ];

    const submit = () => {
      console.log("submitting");
    };

    return {
      form,
      steps,
      submit,
      currentStep,
      notifications,
      constraints,
      locationSelect,
      simulation_types_select,
      targets,
      simulators,
    };
  },
  methods: {
    setStep(id) {
      this.currentStep = this.steps.find((s) => s.id === id);
    },
    checkSource(id) {
      this.form.sources[id] = !this.form.sources[id];
    },
    checkSink(id) {
      this.form.sinks[id] = !this.form.sinks[id];
    },
    checkSimulator(id) {
      this.form.simulators[id] = !this.form.simulators[id];
    },
    checkLink(id) {
      this.form.links[id] = !this.form.links[id];
    },
  },
};
</script>



 <!-- Simulation Steps -->
                    <h2 class="pt-2">
                        Simulation Steps
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-green-100 text-gray-800"
                        >
                            Start
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-gray-100 text-gray-800"
                        >
                            Middle
                        </span>

                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-red-100 text-gray-800"
                        >
                            Finish
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-orange-100 text-gray-800"
                        >
                            User
                        </span>
                    </h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs"
                        :class="{
                            'bg-gray-50':
                                index != 0 && index != stepInfo.length - 1,
                            'bg-green-100': index == 0,
                            'bg-red-100': index == stepInfo.length - 1,
                        }"
                        v-for="(step, index) of stepInfo"
                        :key="step"
                    >
                        {{ step.step }} - {{ step.module }} (<b>{{
                            step.function
                        }}</b
                        >)
                    </div>

                    <h2 class="text-md font-semibold">Modules Inputs</h2>
                    <!-- CF MODULE INPUTS -->
                    <!-- cf:sink:convert_sinks -->
                    <div
                        v-if="hasCFConvertSink"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">cf:sink:convert_sinks</p>
                        <p class="my-2 text-center">Doesn't need Inputs</p>
                    </div>
                    <!-- cf:source_detailed:convert_sources -->
                    <div
                        v-if="hasCFConvertSource"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">
                            cf:source_detailed:convert_sources
                        </p>
                        <p class="my-2 text-center">Doesn't need Inputs</p>
                    </div>
                    <!-- GIS MODULE INPUTS -->
                    <!-- gis:create_network -->
                    <div
                        v-if="hasGISCreateNetwork"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">gis:create_network</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Network Resolution
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['low', 'high']"
                                            label="name"
                                            value="id"
                                            v-model="
                                                form.extra.input_data
                                                    .network_resolution
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Network Resolution
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- gis:optimize_network -->
                    <div
                        v-if="hasGISOptimizeNetwork"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">gis:optimize_network</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            v-for="{
                                path,
                                label,
                                description,
                            } of GISOptimizeNetworkProps"
                            :key="path"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data[path]"
                                    :description="description"
                                    :label="label"
                                    :required="true"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- TEO MODULE INPUTS -->
                    <!-- teo:buildmodel -->
                    <div
                        v-if="hasTEOBuildModel"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">teo:buildmodel</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Region
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="COUNTRIES"
                                            label="name"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.REGION
                                            "
                                            :multiple="true"
                                            :reduce="(country) => country.name"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It sets the regions to be modelled, e.g.
                                        different countries, cities, counties
                                        etc. For the prupose of this analysis it
                                        is enough to have one region name. For
                                        each of them, the supply-demand balances
                                        for all the energy vectors are ensured.
                                        In some occasions it might be
                                        computationally more convenient to model
                                        different countries within the same
                                        region and differentiate them simply by
                                        creating ad hoc fuels and technologies
                                        for each of them.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Emission
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['co2']"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.EMISSION
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It includes any kind of emission
                                        potentially deriving from the operation
                                        of the defined technologies. Typical
                                        examples would include atmospheric
                                        emissions of greenhouse gasses, such as
                                        CO2. The user must fill in 'co2' as a
                                        mandatory entry. Other entries are also
                                        allowed
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="teo_timeslice"
                                    description="It represents the time split of each modelled year, therefore the time resolution of the model. between [1-8784]"
                                    label="Timeslice"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Year
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.YEAR
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                            :reduce="(a) => Number(a)"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It represents the time frame of the
                                        model, it contains all the years to be
                                        considered in the analysis.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Mode of operation
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
                                                11, 12, 13, 14, 15, 16, 17, 18,
                                            ]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets
                                                    .MODE_OF_OPERATION
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                            :reduce="(a) => Number(a)"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It defines the number of modes of
                                        operation that the technologies can
                                        have. If a technology can have various
                                        input or output fuels and it can choose
                                        the mix (i.e. any linear combination) of
                                        these input or output fuels, each mix
                                        can be accounted as a separate mode of
                                        operation. The user must input atleast 1
                                        mode of operation. There muts be two
                                        modes of operation if storage is used in
                                        the model
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Storage
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.STORAGE
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It includes storage facilities in the
                                        model.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                form.extra.input_data.platform_sets.EMISSION
                                    .length > 0
                            "
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Annual emission limit
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        v-for="emission of form.extra.input_data
                                            .platform_sets.EMISSION"
                                        :key="emission"
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <div
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="
                                                    teo_platform_annual_emission_limit[
                                                        emission
                                                    ]
                                                "
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                placeholder="15000"
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ emission }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Annual upper limit for a specific
                                        emission generated in the whole modelled
                                        region.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                form.extra.input_data.platform_sets.STORAGE
                                    .length > 0
                            "
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Platform Storages
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        v-for="storage of form.extra.input_data
                                            .platform_sets.STORAGE"
                                        :key="storage"
                                        class="my-5 relative rounded-md shadow-sm"
                                    >
                                        <h2 class="font-bold">
                                            for Storage : {{ storage }}
                                        </h2>
                                        <div
                                            v-for="prop of teo_platform_storages_props"
                                            :key="prop"
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="
                                                    teo_platform_storages[
                                                        `${storage}_${prop.path}`
                                                    ]
                                                "
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                :placeholder="prop.default"
                                                required
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ prop.path }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    ></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- MM MODULE INPUTS -->
                    <!-- market:shortterm -->
                    <div
                        v-if="hasMMShortTerm"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">market:shortterm</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Market Design
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'pool',
                                                'p2p',
                                                'community',
                                            ]"
                                            v-model="form.extra.input_data.md"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Market design to simulate.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Offer Type
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'simple',
                                                'block',
                                                'energyBudget',
                                            ]"
                                            v-model="
                                                form.extra.input_data.offer_type
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Offer type that the market allows.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Product Differentiation Option
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'noPref',
                                                'co2Emissions',
                                                'networkDistance',
                                            ]"
                                            v-model="
                                                form.extra.input_data.prod_diff
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Option to influence bilateral market
                                        according to a pre-defined preference.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.md == 'pool'"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Network type considered
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['none', 'direction']"
                                            v-model="
                                                form.extra.input_data.network
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        If "direction" is chosen, the dispatch
                                        will respect flow directions in the
                                        district heating network pipelines
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Electricity price dependence of bids
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['true', 'false']"
                                            v-model="
                                                form.extra.input_data
                                                    .el_dependent
                                            "
                                            :reduce="(a) => a == 'true'"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        If this option is chosen, the price bids
                                        of Combined heat and power plants will
                                        be generated based on a given
                                        electricity price signal
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.nr_of_hours"
                                    description="The market will be run hourly, with the number of hours equal to the selected number of hours here. between [1 - 48]"
                                    label="Number of time periods"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.md == 'community'"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Community objective
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'autonomy',
                                                'peakShaving',
                                            ]"
                                            v-model="
                                                form.extra.input_data.objective
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        If "autonomy" is chosen, the community
                                        will strive to be as autonomous as
                                        possible, i.e. to minimize heat imports.
                                        If "peakShaving" is chosen, the
                                        community will strive to minimize its
                                        peak heat import.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.md == 'community'"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.community_settings
                                    "
                                    description="'g_imp', and 'g_exp' are respectively the price the community gets paid and pays for importing/exporting heat. g_peak is the penalty per unit of peak import"
                                    label="Community Settings"
                                    :required="true"
                                    placeholder="{'g_peak': 100 , 'g_exp': -40, 'g_imp': 50}"
                                />
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.offer_type == 'block'"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.block_offer"
                                    description="Block offer bids for generators"
                                    label="Block offer"
                                    :required="true"
                                    placeholder="{'prosumer_1': [[0, 1]], 'producer_1': [[3, 4, 5, 6], [10, 11]]}"
                                />
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.md == 'community'"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Community members
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 flex rounded-md shadow-sm"
                                        v-for="sink of form.extra.sinks"
                                        :key="sink"
                                    >
                                        <VSelect
                                            class="flex-1"
                                            :options="['true', 'false']"
                                            v-model="
                                                form.extra.input_data
                                                    .is_in_community[sink.id]
                                            "
                                            :reduce="(a) => a == 'true'"
                                        />
                                        <span
                                            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                        >
                                            {{ sink.name }}
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 flex rounded-md shadow-sm"
                                        v-for="source of form.extra.sources"
                                        :key="source"
                                    >
                                        <VSelect
                                            class="flex-1"
                                            :options="['true', 'false']"
                                            v-model="
                                                form.extra.input_data
                                                    .is_in_community[source.id]
                                            "
                                            :reduce="(a) => a == 'true'"
                                        />
                                        <span
                                            class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                        >
                                            {{ source.name }}
                                        </span>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Select the agents that are part of the
                                        community
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.block_offer"
                                    description="Provide parameters for CHPs."
                                    label="Parameters for CHP model"
                                    :required="true"
                                    placeholder="{'agent_1' : {'rho' : 1.0, 'r' : 0.15, ...}, 'agent_2' : {'rho' : 0.8, 'r' : 0.10, ...} }"
                                />
                            </div>
                        </div>

                        <div
                            v-if="form.extra.input_data.el_dependent"
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Electricity price (forecast)
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data.el_price
                                            "
                                            :reduce="(a) => Number(a)"
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        The electricity price. A forecast can be
                                        used, or a historic electricity price.
                                        This is used to generate electricity
                                        price dependent bids for CHPs
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.start_datetime
                                    "
                                    description="Start date for the simulation. Used to select the right heat load data from CF, and other parameters from TEO."
                                    label="Start date"
                                    :required="true"
                                    placeholder="31-01-2002"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Utility of demands
                                        </label>
                                    </div>
                                    <div
                                        v-for="sink of form.extra.sinks"
                                        :key="sink"
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <div
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="mm_util[sink.id]"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                placeholder="[1,2,3,4,5,6,7,8,9]"
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ sink.name }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        The maximum price sources are willing to
                                        pay for their heat
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- market:longterm -->
                    <div
                        v-if="hasMMLongTerm"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">market:longterm</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Market Design
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'centralized',
                                                'decentralized',
                                            ]"
                                            v-model="form.extra.input_data.md"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Market design to simulate.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Horizon Basis
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'weeks',
                                                'months',
                                                'years',
                                            ]"
                                            v-model="
                                                form.extra.input_data
                                                    .horizon_basis
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Simulation horizon period.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Data Profile
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['hourly', 'daily']"
                                            v-model="
                                                form.extra.input_data
                                                    .data_profile
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Defines the level of data aggregation
                                        and simulation time slot between
                                        iterations.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.recurrence"
                                    description="Number of instances to simulate according to Horizon Basis. weeks recurrence < 52, months recurrence < 12"
                                    label="Recurrence"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.yearly_demand_rate
                                    "
                                    description="Expected yearly demand rate. It can be positive or negative. [-1,1]"
                                    label="Yearly Demand Rate"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Product Differentiation Option
                                        </label>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                'noPref',
                                                'co2Emissions',
                                                'networkDistance',
                                            ]"
                                            v-model="
                                                form.extra.input_data
                                                    .prod_diff_option
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Option to influence bilateral market
                                        according to a pre-defined preference.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Agent IDs
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data.agent_ids
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Agent IDs.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                form.extra.input_data.prod_diff_option ==
                                'co2Emissions'
                            "
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.co2_emissions
                                    "
                                    description="CO2 emissions by each agent."
                                    label="CO2 Emissions"
                                    :required="true"
                                    placeholder="[1,1.1,0,1.8]"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.gmax"
                                    description="Maximum capacity one source is willing to offer in the market."
                                    label="Maximum Production"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.lmax"
                                    description="Maximum capacity one sinks is willing to offer in the market."
                                    label="Maximum Demand"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.cost"
                                    description="Monetary bid from sources."
                                    label="Cost"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Utility
                                        </label>
                                    </div>
                                    <div
                                        v-for="sink of form.extra.sinks"
                                        :key="sink"
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <div
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="mm_util[sink.id]"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                placeholder="[1,2,3,4,5,6,7,8,9]"
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ sink.name }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Monetary bid by sinks.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BM MODULE INPUTS -->
                    <!-- business:bm -->
                    <div
                        v-if="hasBMBase"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">business:bm</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data.rls"
                                    description="A relationship matrix to determine the ownership structure of the different actors."
                                    label="Relationship matrix for ownership structures."
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.discount_rate
                                    "
                                    description=""
                                    label="Socio-economic and private business discount rate."
                                    :required="true"
                                    placeholder="[4, 5]"
                                />
                            </div>
                        </div>
                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.project_duration
                                    "
                                    description=""
                                    label="Life time in years of the project."
                                    :required="true"
                                    placeholder="10"
                                />
                            </div>
                        </div>
                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="
                                        form.extra.input_data.co2_intensity
                                    "
                                    description="The co2 intensity of the heat supply being used at sinks before the excess heat utilization project"
                                    label="CO2 intensity of the existing supply at the sink. (kg/kWh)"
                                    :required="true"
                                    placeholder="25"
                                />
                            </div>
                        </div>
                    </div>
