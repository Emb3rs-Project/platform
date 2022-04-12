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
