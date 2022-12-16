<template>
    <SiteHead title="Challenge Create"/>

    <AppLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Challenge
                </h2>
            </template>
            <div class="grid grid-cols-1 p-12 gap-y-10">
                <div>
                    <div class="grid grid-cols-3">
                        <div>
                            <div class="flex items-center gap-1">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    Challenge
                                </h3>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div
                                class="bg-white overflow-hidden shadow sm:rounded-lg"
                            >
                                <div class="px-4 py-5 sm:p-6">
                                    <!-- Project Name -->
                                    <div
                                        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <div class="sm:col-span-3">
                                            <TextInput
                                                v-model="form.name"
                                                description="The name that this Challennge is going to have."
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
                                    <div
                                        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <div class="sm:col-span-3">
                                            <TextInput
                                                v-model="form.description"
                                                description="The description that this Challenge is going to have."
                                                label="Description"
                                            />
                                        </div>
                                        <JetInputError
                                            v-show="form.errors.description"
                                            :message="form.errors.description"
                                            class="mt-2"
                                        />
                                    </div>
                                    <div
                                        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <div class="sm:col-span-3">
                                            <SelectMenu label="Project" v-model="form.project" :options="projects"></SelectMenu>
                                        </div>
                                        <JetInputError
                                            v-show="form.errors.project"
                                            :message="form.errors.project"
                                            class="mt-2"
                                        />
                                    </div>
                                    <!-- Project Description -->
                                    <div
                                        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <div class="col-span-6 sm:col-span-4">
                                            <SelectMenu
                                                v-model="form.goal"
                                                :options="goals"
                                                label="Goal"
                                                :required="true"
                                            ></SelectMenu>
                                            <jet-input-error
                                                :message="form.errors.goal"
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>
                                    <div

                                        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <template v-for="(item, index) in form.restrictions">
                                            <div class="flex gap-2  col-span-6 sm:col-span-4">
                                                <SelectMenu
                                                    class="w-1/3"
                                                    v-model="item.restriction"
                                                    :options="restrictions"
                                                    label="Restriction"
                                                ></SelectMenu>
                                                <TextInput
                                                    class="w-1/3"
                                                    v-model="item.value"
                                                    label="Value"
                                                />
                                                <button
                                                    v-tippy="'Delete'"
                                                    class="focus:outline-none mr-4"
                                                    @click="removeRestriction(index)"
                                                >
                                                    <TrashIcon class="text-red-500 font-medium text-sm w-5"/>
                                                </button>
                                            </div>
                                        </template>
                                        <div class="flex sm:col-span-3 justify-start">
                                            <PrimaryButton
                                                class="mr-4"
                                                @click="addRestriction">
                                                Add Restriction
                                            </PrimaryButton>
                                        </div>

                                    </div>
                                    <div class="flex sm:col-span-3 justify-end">
                                        <SecondaryOutlinedButton
                                            class="mx-2"
                                            type="button"
                                            :disabled="form.processing"
                                            @click="onCancel"
                                        >
                                            Cancel
                                        </SecondaryOutlinedButton>
                                        <PrimaryButton
                                            class="mr-4"
                                            @click="onSubmit"
                                            :disabled="form.processing">
                                            Save
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/inertia-vue3";
import SelectMenu from "../../Components/Forms/SelectMenu";
import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import {useStore} from "vuex";
import JetInputError from "@/Jetstream/InputError";
import TrashIcon from "../../Components/Icons/TrashIcon";


const props = defineProps({
    goals: {
        type: Array,
        default: []
    },
    restrictions: {
        type: Array,
        default: []
    },
    projects: {
        type: Array,
        default: []
    }
})
const store = useStore();

const form = useForm({
    name: "",
    description: "",
    project: {},
    goal: {},
    restrictions: [
        {
            restriction: {},
            value: ''
        }
    ]
});


const onCancel = () => {
    Inertia.visit(route("challenges.index"));
};

const addRestriction = () => {
    form.restrictions.push(
        {
            restriction: {},
            value: ''
        }
    )
}

const removeRestriction = (index) => {
    form.restrictions.splice(index, 1)
}

const onSubmit = () => {
    console.log(form);
    form.post(route("challenges.store"), {
        onError: (error) => {
            store.commit("objects/setNotify", {...error});
        },
        onSuccess: () => {
            Inertia.visit(route("challenges.index"));
        },
    });
};
</script>
