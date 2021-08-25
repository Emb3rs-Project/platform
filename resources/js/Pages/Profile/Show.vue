<template>
  <AppLayout>

    <SiteHead title="Profile" />

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
      <div v-if="$page.props.jetstream.canUpdateProfileInformation">
        <UpdateProfileInformationForm :user="$page.props.user" />

        <JetSectionBorder />
      </div>

      <div v-if="$page.props.jetstream.canUpdatePassword">
        <UpdatePasswordForm class="mt-10 sm:mt-0" />

        <JetSectionBorder />
      </div>

      <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
        <TwoFactorAuthenticationForm class="mt-10 sm:mt-0" />

        <JetSectionBorder />
      </div>

      <LogoutOtherBrowserSessionsForm
        :sessions="sessions"
        class="mt-10 sm:mt-0"
      />

      <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
        <JetSectionBorder />

        <DeleteUserForm class="mt-10 sm:mt-0" />
      </template>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import SiteHead from "@/Components/SiteHead.vue";
import DeleteUserForm from "./DeleteUserForm";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import LogoutOtherBrowserSessionsForm from "./LogoutOtherBrowserSessionsForm";
import TwoFactorAuthenticationForm from "./TwoFactorAuthenticationForm";
import UpdatePasswordForm from "./UpdatePasswordForm";
import UpdateProfileInformationForm from "./UpdateProfileInformationForm";

export default {
  props: ["sessions"],

  components: {
    AppLayout,
    SiteHead,
    DeleteUserForm,
    JetSectionBorder,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
  },
};
</script>
