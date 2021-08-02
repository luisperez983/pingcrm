<template>
  <div>
    <div class="mb-8 flex justify-start max-w-3xl">
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link 
                class="text-indigo-400 hover:text-indigo-600" 
                :href="route('permissions')">
                Permissions
            </inertia-link>
            <span class="text-indigo-400 font-medium">/</span> {{form.name}}
        </h1>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="update">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :error="form.errors.name" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
          <text-input v-model="form.guard_name" :error="form.errors.guard_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Guard Name" />                
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
         <button v-if="!permission.deleted_at" 
                 class="text-red-600 hover:underline" 
                 tabindex="-1" 
                 type="button" 
                 @click="destroy">
                 Eliminar Permission
          </button>            
          <loading-button :loading="form.processing"
                          class="btn-indigo ml-auto" 
                          type="submit">Guardar Permission
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


export default{
    metainfo(){
        return{
            title:`${this.form.name}`
        }
    },
    components: {
        LoadingButton,        
        TextInput,
        TrashedMessage,
    },
    layout: Layout,
    remember: 'form',
    props:{
        permission:Object,       
    },
    data() {
        return {
        form: this.$inertia.form({
            name: this.permission.name,
            guard_name: this.permission.guard_name,
            
        }),
        }
    },    
  methods: {

    update() {        
        this.form.post(this.route('permissions.update',this.permission.id))
    },
    destroy() {
      if (confirm('Are you sure you want to delete this permission?')) {
        this.$inertia.delete(this.route('permissions.destroy', this.permission.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this permission?')) {
        this.$inertia.put(this.route('permissions.restore', this.permission.id))
      }
    },
  },      

}
</script>
