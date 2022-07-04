<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="fixed inset-0 overflow-hidden z-30" @close="$emit('update:open', !open)">
      <div class="absolute inset-0 overflow-hidden">
        <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
          <DialogOverlay class="absolute inset-0 bg-slate-300/30 dark:bg-slate-800/30 transition-opacity backdrop-blur" />
        </TransitionChild>
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
          <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
            <div class="pointer-events-auto relative w-screen max-w-screen-md">
              <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 sm:-ml-10 sm:pr-4">
                  <button type="button" class="rounded-md text-gray-300 hover:text-white focus:outline-none" @click="$emit('update:open', !open)">
                    <span class="sr-only">Закрыть</span>
                    <i class="fi fi-rr-cross h-6 w-6 focus:outline-none" aria-hidden="true"></i>
                  </button>
                </div>
              </TransitionChild>
              <div class="flex h-full flex-col overflow-y-scroll bg-white dark:bg-slate-700 py-6 shadow-xl">
                <div class="px-4 sm:px-6">
                  <DialogTitle class="text-2xl font-medium text-gray-900 dark:text-slate-200">Аналитика {{ id ? 'предприятий данного региона' : 'предприятий области' }}</DialogTitle>

                </div>
                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                  <!-- Replace with your content -->
                    <div class="h-32 flex justify-center items-center" v-if="isLoad">
                        <svg class="mr-3 h-8 w-8 animate-spin text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                            <path class="opacity-75"  fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="" v-else>
                        <div class="grid grid-cols-2 text-slate-600 dark:text-slate-400">
                            <div>
                                <div class="">Всего предприятий - <span class="rounded-xl bg-slate-300 dark:bg-slate-600 py-1 px-2">{{ all }}</span></div>
                                <div class="mt-2">Обновилось предприятий за 30 дней - <span class="rounded-xl bg-slate-300 dark:bg-slate-600 py-1 px-2">{{ mounth }}</span></div>
                                <div class="mt-2">Без ИНН предприятий - <span class="rounded-xl bg-slate-300 dark:bg-slate-600 py-1 px-2">{{ no_inn }}</span></div>
                            </div>
                            <div class="mt-3 text-3xl text-center">≈ {{ (mounth / all * 100).toFixed(2) }}%</div>
                        </div>
                        <div class="mt-4" v-if="!id">
                            <div class="my-4 rounded-xl bg-slate-300/30 dark:bg-slate-600/30 py-2 px-4" v-for="item in list" :key="item">
                                <div class="inline-block text-base mb-2 -ml-6 pl-6 pr-3 text-slate-500 dark:text-slate-400  rounded-lg bg-slate-300 dark:bg-slate-600">{{ item.region }}</div>
                                <div class="grid grid-cols-5 text-slate-500 dark:text-slate-400 leading-none">
                                    <div class="mr-3"><div class="text-lg font-semibold">{{ item.e_all }}</div> всего в регионе</div>
                                    <div class="mr-3 col-span-2"><div class="text-lg font-semibold">{{ item.mounth }}</div> обновилось за 30 дней </div>
                                    <div class="text-xl font-bold">≈ {{ (item.mounth / item.e_all * 100).toFixed(2) }}%</div>
                                    <div class="ml-3" v-if="item.no_inn"><span class="text-lg font-semibold">{{ item.no_inn }}</span> - без ИНН </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  <!-- /End replace -->
                </div>
              </div>
            </div>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

export default {
    components: {
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
    },
    props: ['id', 'open'],
    data() {
        return {
            list: [],
            isLoad: true,
            all: 0,
            mounth: 0,
            no_inn: 0,
        }
    },
    watch: {
        open: {
            deep: true,
            handler() {
                if (this.open && !this.list.length) {
                    this.load()
                }
            }
        }
    },
    methods: {
        load: function(){
            let url = this.id ? route('enterpises.analytics', this.id) : route('enterpises.analytics')
            axios.get(url).then(response => {
                    this.isLoad = !this.isLoad
                    this.list = response.data
                    this.all = this.list.reduce( (previousValue, currentValue) => previousValue + currentValue.e_all, 0);
                    this.mounth = this.list.reduce( (previousValue, currentValue) => previousValue + currentValue.mounth, 0);
                    this.no_inn = this.list.reduce( (previousValue, currentValue) => previousValue + currentValue.no_inn, 0);
                }).catch(e => {
                    console.log("Error... ")
                });
        }
    }
}
</script>
