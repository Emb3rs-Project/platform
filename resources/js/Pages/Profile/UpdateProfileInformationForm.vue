<template>
  <JetFormSection @submitted="updateProfileInformation">
    <template #title>
      Profile Information
    </template>

    <template #description>
      Update your account's profile information and email address.
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div
        class="col-span-6 sm:col-span-4"
        v-if="$page.props.jetstream.managesProfilePhotos"
      >
        <!-- Profile Photo File Input -->
        <input
          type="file"
          class="hidden"
          ref="photo"
          @change="updatePhotoPreview"
        >

        <JetLabel
          for="photo"
          value="Photo"
        />

        <!-- Current Profile Photo -->
        <div
          class="mt-2"
          v-show="! photoPreview"
        >
          <img
            :src="user.profile_photo_url"
            :alt="user.name"
            class="rounded-full h-20 w-20 object-cover"
          >
        </div>

        <!-- New Profile Photo Preview -->
        <div
          class="mt-2"
          v-show="photoPreview"
        >
          <span
            class="block rounded-full w-20 h-20"
            :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"
          >
          </span>
        </div>

        <SecondaryButton
          class="mt-2 mr-2"
          type="button"
          @click.prevent="selectNewPhoto"
        >
          Select A New Photo
        </SecondaryButton>

        <SecondaryButton
          type="button"
          class="mt-2"
          @click.prevent="deletePhoto"
          v-if="user.profile_photo_path"
        >
          Remove Photo
        </SecondaryButton>

        <JetInputError
          :message="form.errors.photo"
          class="mt-2"
        />
      </div>

      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <TextInput
          v-model="form.name"
          label="Name"
        />
        <JetInputError
          :message="form.errors.name"
          class="mt-2"
        />

      </div>

      <!-- Email -->
      <div class="col-span-6 sm:col-span-4">
        <TextInput
          v-model="form.email"
          label="Email"
        />
        <JetInputError
          :message="form.errors.email"
          class="mt-2"
        />
      </div>

        <div class="col-span-6 sm:col-span-4">
            <TextInput
                v-model="form.allow_email_notification"
                label="Receive notification by Email"
            />
            <JetInputError
                :message="form.errors.allow_email_notification"
                class="mt-2"
            />

        </div>
    </template>

    <template #actions>
      <JetActionMessage
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Saved.
      </JetActionMessage>

      <PrimaryButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </JetFormSection>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  components: {
    PrimaryButton,
    SecondaryButton,
    TextInput,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
  },

  props: ["user"],

  data() {
    return {
      form: this.$inertia.form({
        _method: "PUT",
        name: this.user.name,
        email: this.user.email,
        photo: null,
      }),

      photoPreview: null,
    };
  },

  methods: {
    updateProfileInformation() {
      if (this.$refs.photo) {
        this.form.photo = this.$refs.photo.files[0];
      }

      this.form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
      });
    },

    selectNewPhoto() {
      this.$refs.photo.click();
    },

    updatePhotoPreview() {
      const reader = new FileReader();

      reader.onload = (e) => {
        this.photoPreview = e.target.result;
      };

      reader.readAsDataURL(this.$refs.photo.files[0]);
    },

    deletePhoto() {
      this.$inertia.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => (this.photoPreview = null),
        onError: (error) => {
            store.commit("objects/setNotify", {...error});
        },
      });
    },
  },
};
</script>
