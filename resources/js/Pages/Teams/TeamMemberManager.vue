<template>
  <div>
    <div v-if="userPermissions.canAddTeamMembers">
      <jet-section-border />

      <!-- Add Team Member -->
      <jet-form-section @submitted="addTeamMember">
        <template #title> Add Institution Member </template>

        <template #description>
          Add a new institution member to your institution, allowing them to
          collaborate with you.
        </template>

        <template #form>
          <div class="col-span-6">
            <div class="max-w-xl text-sm text-gray-600">
              Please provide the email address of the person you would like to
              add to this institution.
            </div>
          </div>

          <!-- Member Email -->
          <div class="col-span-6 sm:col-span-4">
            <jet-label
              for="email"
              value="Email"
            />
            <jet-input
              id="email"
              type="email"
              class="mt-1 block w-full"
              v-model="addTeamMemberForm.email"
            />
            <jet-input-error
              :message="addTeamMemberForm.errors.email"
              class="mt-2"
            />
          </div>

          <!-- Role -->
          <div
            class="col-span-6 lg:col-span-4"
            v-if="roles.length > 0"
          >
            <jet-label
              for="roles"
              value="Role"
            />
            <jet-input-error
              :message="addTeamMemberForm.errors.role"
              class="mt-2"
            />

            <div class="
                relative
                z-0
                mt-1
                border border-gray-200
                rounded-lg
                cursor-pointer
              ">
              <button
                type="button"
                class="
                  relative
                  px-4
                  py-3
                  inline-flex
                  w-full
                  rounded-lg
                  focus:z-10
                  focus:outline-none
                  focus:border-blue-300
                  focus:shadow-outline-blue
                "
                :class="{
                  'border-t border-gray-200 rounded-t-none': i > 0,
                  'rounded-b-none': i != Object.keys(roles).length - 1,
                }"
                @click="addTeamMemberForm.team_role_id = role.id"
                v-for="(role, i) in roles"
                :key="role.id"
              >
                <div :class="{
                    'opacity-50':
                      addTeamMemberForm.team_role_id &&
                      addTeamMemberForm.team_role_id != role.id,
                  }">
                  <!-- Role Name -->
                  <div class="flex items-center">
                    <div
                      class="text-sm text-gray-600"
                      :class="{
                        'font-semibold':
                          addTeamMemberForm.team_role_id == role.id,
                      }"
                    >
                      {{ role.role }}
                    </div>

                    <svg
                      v-if="addTeamMemberForm.team_role_id == role.id"
                      class="ml-2 h-5 w-5 text-green-400"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
              </button>
            </div>
          </div>

          <div class="col-span-6 lg:col-span-4">
            <secondary-button
              type="button"
              @click="roleModalIsVisible = true"
            >
              Create Role
            </secondary-button>
          </div>
        </template>

        <template #actions>
          <jet-action-message
            :on="addTeamMemberForm.recentlySuccessful"
            class="mr-3"
          >
            Added.
          </jet-action-message>

          <primary-button :disabled="addTeamMemberForm.processing">
            Add
          </primary-button>
        </template>
      </jet-form-section>
    </div>

    <div v-if="
        team.team_invitations.length > 0 && userPermissions.canAddTeamMembers
      ">
      <jet-section-border />

      <!-- Team Member Invitations -->
      <jet-action-section class="mt-10 sm:mt-0">
        <template #title> Pending Institution Invitations </template>

        <template #description>
          These people have been invited to your institution and have been sent
          an invitation email. They may join the institution by accepting the
          email invitation.
        </template>

        <!-- Pending Team Member Invitation List -->
        <template #content>
          <div class="space-y-6">
            <div
              class="flex items-center justify-between"
              v-for="invitation in team.team_invitations"
              :key="invitation.id"
            >
              <div class="text-gray-600">{{ invitation.email }}</div>

              <div class="flex items-center">
                <!-- Cancel Team Invitation -->
                <button
                  class="
                    cursor-pointer
                    ml-6
                    text-sm text-red-500
                    focus:outline-none
                  "
                  @click="cancelTeamInvitation(invitation)"
                  v-if="userPermissions.canRemoveTeamMembers"
                >
                  Cancel
                </button>
              </div>
            </div>
          </div>
        </template>
      </jet-action-section>
    </div>

    <div v-if="team.users.length > 0">
      <jet-section-border />

      <!-- Manage Team Members -->
      <jet-action-section class="mt-10 sm:mt-0">
        <template #title> Institution Members </template>

        <template #description>
          All of the people that are part of this institution.
        </template>

        <!-- Team Member List -->
        <template #content>
          <div class="space-y-6">
            <div
              class="flex items-center justify-between"
              v-for="user in team.users"
              :key="user.id"
            >
              <div class="flex items-center">
                <img
                  class="w-8 h-8 rounded-full"
                  :src="user.profile_photo_url"
                  :alt="user.name"
                />
                <div class="ml-4">{{ user.name }}</div>
              </div>

              <div class="flex items-center">
                <!-- Manage Team Member Role -->
                <button
                  class="ml-2 text-sm text-gray-400 underline"
                  @click="manageRole(user)"
                  v-if="userPermissions.canAddTeamMembers && roles.length"
                >
                  {{ displayableRole(user.membership.team_role_id) }}
                </button>

                <div
                  class="ml-2 text-sm text-gray-400"
                  v-else-if="roles.length"
                >
                  {{ displayableRole(user.membership.team_role_id) }}
                </div>

                <!-- Leave Team -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500"
                  @click="confirmLeavingTeam"
                  v-if="$page.props.user.id === user.id"
                >
                  Leave
                </button>

                <!-- Remove Team Member -->
                <button
                  class="cursor-pointer ml-6 text-sm text-red-500"
                  @click="confirmTeamMemberRemoval(user)"
                  v-if="userPermissions.canRemoveTeamMembers"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </template>
      </jet-action-section>
    </div>

    <!-- Role Management Modal -->
    <jet-dialog-modal
      :show="currentlyManagingRole"
      @close="currentlyManagingRole = false"
    >
      <template #title> Manage Role </template>

      <template #content>
        <div v-if="managingRoleFor">
          <div class="
              relative
              z-0
              mt-1
              border border-gray-200
              rounded-lg
              cursor-pointer
            ">
            <button
              type="button"
              class="
                relative
                px-4
                py-3
                inline-flex
                w-full
                rounded-lg
                focus:z-10
                focus:outline-none
                focus:border-blue-300
                focus:shadow-outline-blue
              "
              :class="{
                'border-t border-gray-200 rounded-t-none': i > 0,
                'rounded-b-none': i !== Object.keys(roles).length - 1,
              }"
              @click="updateRoleForm.team_role_id = role.id"
              v-for="(role, i) in roles"
              :key="role.id"
            >
              <div :class="{
                  'opacity-50':
                    updateRoleForm.team_role_id &&
                    updateRoleForm.team_role_id !== role.id,
                }">
                <!-- Role Name -->
                <div class="flex items-center">
                  <div
                    class="text-sm text-gray-600"
                    :class="{
                      'font-semibold': updateRoleForm.team_role_id === role.id,
                    }"
                  >
                    {{ role.role }}
                  </div>

                  <svg
                    v-if="updateRoleForm.team_role_id === role.id"
                    class="ml-2 h-5 w-5 text-green-400"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>

                <!-- Role Description -->
                <!-- <div class="mt-2 text-xs text-gray-600">
                  {{ role.description }}
                </div> -->
              </div>
            </button>
          </div>
        </div>
      </template>

      <template #footer>
        <secondary-outlined-button @click="currentlyManagingRole = false">
          Cancel
        </secondary-outlined-button>

        <primary-button
          class="ml-2"
          :disabled="updateRoleForm.processing"
          @click="updateRole"
        >
          Save
        </primary-button>
      </template>
    </jet-dialog-modal>

    <!-- Leave Team Confirmation Modal -->
    <jet-confirmation-modal
      :show="confirmingLeavingTeam"
      @close="confirmingLeavingTeam = false"
    >
      <template #title> Leave Team </template>

      <template #content>
        Are you sure you would like to leave this institution?
      </template>

      <template #footer>
        <secondary-outlined-button @click="confirmingLeavingTeam = false">
          Cancel
        </secondary-outlined-button>

        <danger-button
          :disabled="leaveTeamForm.processing"
          class="ml-2"
          @click="leaveTeam"
        >
          Leave
        </danger-button>
      </template>
    </jet-confirmation-modal>

    <!-- Remove Team Member Confirmation Modal -->
    <jet-confirmation-modal
      :show="teamMemberBeingRemoved"
      @close="teamMemberBeingRemoved = null"
    >
      <template #title> Remove Team Member </template>

      <template #content>
        Are you sure you would like to remove this person from the institution?
      </template>

      <template #footer>
        <secondary-outlined-button @click="teamMemberBeingRemoved = false">
          Cancel
        </secondary-outlined-button>

        <danger-button
          :disabled="removeTeamMemberForm.processing"
          class="ml-2"
          @click="removeTeamMember"
        >
          Remove
        </danger-button>
      </template>
    </jet-confirmation-modal>

    <create-role-modal
      v-model="roleModalIsVisible"
      :permissions="availablePermissions"
    ></create-role-modal>
  </div>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";

import {
  RadioGroup,
  RadioGroupDescription,
  RadioGroupLabel,
  RadioGroupOption,
} from "@headlessui/vue";

import JetActionMessage from "@/Jetstream/ActionMessage";
import JetActionSection from "@/Jetstream/ActionSection";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetSectionBorder from "@/Jetstream/SectionBorder";

import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryButton from "@/Components/NewLayout/SecondaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";
import DangerButton from "@/Components/NewLayout/DangerButton.vue";
import CreateRoleModal from "@/Components/NewLayout/Modals/CreateRoleModal.vue";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";
import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";

export default {
  components: {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
    JetActionMessage,
    JetActionSection,
    JetConfirmationModal,
    JetDialogModal,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSectionBorder,
    PrimaryButton,
    SecondaryButton,
    SecondaryOutlinedButton,
    DangerButton,
    CreateRoleModal,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    TextInput,
  },

  props: {
    team: {
      type: Object,
      required: true,
    },
    availableRoles: {
      type: Array,
      required: true,
    },
    availablePermissions: {
      type: Array,
      required: true,
    },
    userPermissions: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const roleModalIsVisible = ref(false);
    const roles = ref(props.availableRoles);

    store.watch(
      () => store.state.teamRoles.role,
      (role) => {
        roles.value.push(role);
      },
      { deep: true }
    );

    return {
      roleModalIsVisible,
      roles,
    };
  },

  data() {
    return {
      addTeamMemberForm: this.$inertia.form({
        email: "",
        team_role_id: null,
      }),

      updateRoleForm: this.$inertia.form({
        team_role_id: null,
      }),

      leaveTeamForm: this.$inertia.form(),
      removeTeamMemberForm: this.$inertia.form(),

      currentlyManagingRole: false,
      managingRoleFor: null,
      confirmingLeavingTeam: false,
      teamMemberBeingRemoved: null,
    };
  },

  methods: {
    addTeamMember() {
      this.addTeamMemberForm.post(route("team-members.store", this.team.id), {
        errorBag: "addTeamMember",
        preserveScroll: true,
        onSuccess: () => this.addTeamMemberForm.reset(),
      });
    },

    cancelTeamInvitation(invitation) {
      this.$inertia.delete(route("team-invitations.destroy", invitation), {
        preserveScroll: true,
      });
    },

    manageRole(teamMember) {
      this.managingRoleFor = teamMember;
      this.updateRoleForm.team_role_id = teamMember.membership.team_role_id;
      this.currentlyManagingRole = true;
    },

    updateRole() {
      this.updateRoleForm.put(
        route("team-members.update", [this.team, this.managingRoleFor]),
        {
          preserveScroll: true,
          onSuccess: () => (this.currentlyManagingRole = false),
        }
      );
    },

    confirmLeavingTeam() {
      this.confirmingLeavingTeam = true;
    },

    leaveTeam() {
      this.leaveTeamForm.delete(
        route("team-members.destroy", [this.team, this.$page.props.user])
      );
    },

    confirmTeamMemberRemoval(teamMember) {
      this.teamMemberBeingRemoved = teamMember;
    },

    removeTeamMember() {
      this.removeTeamMemberForm.delete(
        route("team-members.destroy", [this.team, this.teamMemberBeingRemoved]),
        {
          errorBag: "removeTeamMember",
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => (this.teamMemberBeingRemoved = null),
        }
      );
    },

    displayableRole(teamRoleId) {
      const role = this.roles.find((r) => r.id === teamRoleId);

      if (role) {
        return role.role;
      }
    },
  },
};
</script>
