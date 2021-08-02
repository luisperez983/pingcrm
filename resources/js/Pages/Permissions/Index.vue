<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Users</h1>
    <div class="mb-6 flex justify-between items-center">
        <inertia-link class="btn-indigo" :href="route('permissions.create')">
            <span>Create</span>
            <span class="hidden md:inline">Permission</span>
        </inertia-link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Id</th>          
          <th class="px-6 pt-6 pb-4">Permission</th>
          <th class="px-6 pt-6 pb-4">Guard Name</th>
        </tr>
        <tr v-for="permission in permissions" :key="permission.id" 
            class="hover:bg-gray-100 focus-within:bg-gray-100">
            <td class="border-t">
                <inertia-link 
                    class="px-6 py-4 flex items-center focus:text-indigo-500" 
                    :href="route('permissions.edit', permission.id)" tabindex="-1">              
                    {{ permission.id }}              
                </inertia-link>
            </td>   
            <td class="border-t">
            <inertia-link 
                class="px-6 py-4 flex items-center focus:text-indigo-500" 
                :href="route('permissions.edit', permission.id)" tabindex="-1">              
                {{ permission.name }}              
            </inertia-link>
            <td class="border-t">
                <inertia-link 
                    class="px-6 py-4 flex items-center focus:text-indigo-500" 
                    :href="route('permissions.edit', permission.id)" tabindex="-1">              
                    {{ permission.guard_name }}              
                </inertia-link>            
             </td>  
            <td class="border-t w-px">
                <inertia-link class="px-4 flex items-center" 
                    :href="route('permissions.edit', permission.id)" tabindex="-1">
                    <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </inertia-link>
            </td>                               
        </tr>
        <tr v-if="permissions.lenght === 0">
            <td class="borde-t px-6 py-4">No Permissions found.</td>                     
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

export default {
    metaInfo: {
        title:'Permissions',
    },
    components:{
         Icon,
    },
    layout:Layout,
    props:{
        permissions:Array,
    },
}
</script>