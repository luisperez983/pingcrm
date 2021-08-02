<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('organizations')">Empresa</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Crear
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="form.errors.name" class="pr-6 pb-8 w-full lg:w-1/2" label="Nombre" />
          <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Correo" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" label="Teléfono" />
          <text-input v-model="form.address" :error="form.errors.address" class="pr-6 pb-8 w-full lg:w-1/2" label="Dirección" />
          <text-input v-model="form.city" :error="form.errors.city" class="pr-6 pb-8 w-full lg:w-1/2" label="Ciudad" />
          <text-input v-model="form.region" :error="form.errors.region" class="pr-6 pb-8 w-full lg:w-1/2" label="Provincia/Estado" />
          <select-input v-model="form.country" :error="form.errors.country" class="pr-6 pb-8 w-full lg:w-1/2" label="Pais">
            <option :value="null" />
            <option value="BOL">Bolivia</option>
            <option value="PAR">Paraguay</option>
          </select-input>
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pr-6 pb-8 w-full lg:w-1/2" label="Código Postal code" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Crear Empresa</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  metaInfo: { title: 'Crear Empresa' },
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        email: null,
        phone: null,
        address: null,
        city: null,
        region: null,
        country: null,
        postal_code: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post(this.route('organizations.store'))
    },
  },
}
</script>
