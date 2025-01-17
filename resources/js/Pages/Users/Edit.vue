<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
      <h1 class="font-bold text-3xl">
        <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('users')">Users</inertia-link>
        <span class="text-indigo-400 font-medium">/</span>
        {{ form.first_name }} {{ form.last_name }}
      </h1>
      <img v-if="user.photo" class="block w-8 h-8 rounded-full ml-4" :src="user.photo" />
    </div>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      This user has been deleted.
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.first_name" :error="form.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :error="form.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :error="form.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.password" :error="form.errors.password" class="pr-6 pb-8 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />

          <select-input v-if="$page.props.auth.user.roles[0]==='ADMINISTRADOR'"
              v-model="form.organization_id" :error="form.errors.organization_id" 
              ref="organization"
              class="pr-6 pb-8 w-full lg:w-1/2" label="Empresa">
            <option :value="null"/>
            <option v-for="xorganization in organizations" :key="xorganization.id" :value="xorganization.id">{{xorganization.name}}</option>            
          </select-input> 

          <select-input v-if="$page.props.auth.user.roles[0]==='ADMINISTRADOR'"
              v-model="form.role" :error="form.errors.role" 
              ref="role"
              class="pr-6 pb-8 w-full lg:w-1/2" label="Role">
            <option :value="null"/>
            <option v-for="xrole in roles" :key="xrole.id" :value="xrole.name">{{xrole.name}}</option>            
          </select-input>

          <select-input v-if="$page.props.auth.user.roles[0]==='ADMINISTRADOR'" 
              v-model="form.owner" :error="form.errors.owner" 
              class="pr-6 pb-8 w-full lg:w-1/2" label="Owner">
            <option :value="true">Yes</option>
            <option :value="false">No</option>
          </select-input>
          <file-input v-model="form.photo" :error="form.errors.photo" class="pr-6 pb-8 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button v-if="!user.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Eliminar Usuario</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Guardar Usuario</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import FileInput from '@/Shared/FileInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return {
      title: `${this.form.first_name} ${this.form.last_name}`,
    }
  },
  components: {
    FileInput,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
    roles:Array,
    organizations:Array,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        _method: 'put',
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email, 
        password: null,
        owner: this.user.owner,
        organization_id:this.user.organization_id,
        photo: null,
        role: this.user.roles[0].name,
      }),
    }
  },
  methods: {

    update() {
        
        let data = new FormData()
          data.append('first_name', this.form.first_name || '')
          data.append('last_name', this.form.last_name || '')
          data.append('email', this.form.email || '')
          data.append('password', this.form.password || '')
          data.append('owner', this.form.owner ? '1' : '0')
          data.append('photo', this.form.photo || '')
          data.append('user',this.user.id)
          data.append('organization_id',this.$refs.organization.value)
          data.append('role',this.$refs.role.value)
          data.append('_method', 'put') 

        this.$inertia.post('/users/'+this.user.id, data, {
            forceFormData: true,
        })
      /*this.form.post(this.route('users.update', this.user.id), {
        onSuccess: () => this.form.reset('password', 'photo'),
      })*/
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(this.route('users.destroy', this.user.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this user?')) {
        this.$inertia.put(this.route('users.restore', this.user.id))
      }
    },
  },
}
</script>
