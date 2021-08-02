<template>
    <div>
       <h1 class="mb-8 font-bold text-3xl">Roles</h1>
       <div class="mb-6 flex justify-between items-center">
            <inertia-link class="btn-indigo" :href="route('roles.create')">
                <span>Create</span>
                <span class="hidden md:inline">Role</span>
            </inertia-link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Id</th>
          <th class="px-6 pt-6 pb-4">Roles</th>
          <th class="px-6 pt-6 pb-4">Permissions</th>
          <th class="px-6 pt-6 pb-4">Guard Name</th>          
        </tr>
        <tr v-for="role in roles" :key="role.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link 
                class="px-6 py-4 flex items-center focus:text-indigo-500" 
                :href="route('roles.edit', role.id)" tabindex="-1"
            >              
              {{ role.id }}              
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link 
                class="px-6 py-4 flex items-center focus:text-indigo-500" 
                :href="route('roles.edit', role.id)" tabindex="-1"
            >              
              {{ role.name }}              
            </inertia-link>
          </td>
          <td class="border-t">
            <span
                v-for="assignedPermission in role.assignedPermissions"
                :key="assignedPermission.id"
                :value="assignedPermission.name"
                class="inline-block items-center justify-center px-2 py-1 mr-2 mb-2 text-xs text-white font-bold leading-none bg-red-600 rounded-full"
            >
            {{assignedPermission.name}}       
            </span>
          </td>
          <td class="border-t">
            <inertia-link 
                class="px-6 py-4 flex items-center focus:text-indigo-500" 
                :href="route('roles.edit', role.id)" tabindex="-1"
            >              
              {{ role.guard_name }}              
            </inertia-link>
          </td>     
            <td class="border-t w-px">
                <inertia-link class="px-4 flex items-center" 
                    :href="route('roles.edit', role.id)" tabindex="-1">
                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </inertia-link>
            </td>                               
          </tr>                 
          <tr v-if="roles.lenght === 0">
              <td class="borde-t px-6 py-4">No Roles found.</td>                     
          </tr>
      </table>
    </div>
    </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import SearchFilter from '@/Shared/SearchFilter'

export default {
    metaInfo: {
        title:'Roles',
    },
    components:{
         Icon,
    },
    layout:Layout,
    props:{
        roles:Array,
    },
}
</script>
