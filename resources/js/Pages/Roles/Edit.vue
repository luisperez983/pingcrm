<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link 
                class="text-indigo-400 hover:text-indigo-600" 
                :href="route('roles')">
                Roles
            </inertia-link>
            <span class="text-indigo-400 font-medium">/</span> {{form.name}}
        </h1>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="store">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="form.errors.name" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
          <text-input v-model="form.guard_name" :error="form.errors.guard_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Guard Name" />
          <label class="block w-full">Permissions:</label>
          <span v-for="assignedPermission in assignedPermissions"
                :key="assignedPermission.id"
                :value="assignedPermission.name"
                @click="unassignPermission(assignedPermission)"
                class="inline-flex items-center justify-center px-2 py-1 mr-2 mt-2 mb-2  text-xs text-white font-bold leading-none bg-red-600 rounded-full"
          >
            {{assignedPermission.name}} X
          </span>          
        </div>
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
            <h3 class="block font-bold w-full">Available Permissions</h3>
            <span v-for="unassignedPermission in unassignedPermissions"
                :key="unassignedPermission.id"
                :value="unassignedPermission.name"
                @click="assignPermission(unassignedPermission)"
                class="inline-flex items-center justify-center px-2 py-1 mr-2 mt-2 mb-2  text-xs text-white font-bold leading-none bg-indigo-600 rounded-full"
          >
            {{unassignedPermission.name}} +
          </span>          
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
         <button v-if="!role.deleted_at" 
                 class="text-red-600 hover:underline" 
                 tabindex="-1" 
                 type="button" 
                 @click="destroy">
                 Eliminar Role
          </button>            
          <loading-button :loading="form.processing"
                          class="btn-indigo ml-auto" 
                          type="submit">Guardar Role
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import FileInput from '@/Shared/FileInput'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'
import TrashedMessage from '@/Shared/TrashedMessage'
import assignPermission from '@/Mixins/assignPermission'

export default{
    metainfo(){
        return{
            title:`${this.form.name}`
        }
    },
    mixins:[assignPermission],
    components: {
        FileInput,
        LoadingButton,
        SelectInput,
        TextInput,
        TrashedMessage,
    },
    layout: Layout,
    remember: 'form',
    props:{
        role:Object,
        unassignedPermissions:Array,
        assignedPermissions:Array,        
    },
    data() {
        return {
        form: this.$inertia.form({
            name: this.role.name,
            guard_name: this.role.guard_name,
            
        }),
        }
    },    
  methods: {

    store() {        
        this.form.put(this.route('roles.update',[this.role.id,this.assignedPermissions]))
    },
    destroy() {
      if (confirm('Are you sure you want to delete this role?')) {
        this.$inertia.delete(this.route('roles.destroy', this.role.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this role?')) {
        this.$inertia.put(this.route('roles.restore', this.role.id))
      }
    },
  },      

}
</script>
