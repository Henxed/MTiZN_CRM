<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="fixed inset-0 overflow-hidden" @close="$emit('update:open', !open)">
      <div class="absolute inset-0 overflow-hidden">
        <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
          <DialogOverlay class="absolute inset-0 bg-slate-300/30 dark:bg-slate-800/30 transition-opacity backdrop-blur" />
        </TransitionChild>
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
          <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
            <div class="pointer-events-auto relative w-screen max-w-md">
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
                  <DialogTitle class="text-lg font-medium text-gray-900 dark:text-slate-200">История изменений</DialogTitle>

                </div>
                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                  <!-- Replace with your content -->
                    <div class="text-base mt-4" v-for="item in data" :key="item.id">
                        <div class="text-xs text-slate-400 flex justify-between mx-3">
                            <div>{{ item.user.name  }}</div>
                            <div>{{ $moment(item.created_at).format('LLL')}}</div>
                        </div>
                        <div class="rounded-lg bg-slate-200 dark:bg-slate-600 p-4 dark:text-slate-300 text-sm" >
                            Были данные:
                            <div class="text-xs p-2 bg-red-600/20" v-for="(el, i) in JSON.parse(item.log).old" :key="el">
                                <strike>{{ $t(`inputs.${i}`) }}: {{  el || 'Пусто' }}</strike>

                            </div>
                            <div class="my-2"> </div>
                            Стали данные:
                            <div class="text-xs p-2 bg-emerald-500/20" v-for="(el, i) in JSON.parse(item.log).new" :key="el">
                               {{ $t(`inputs.${i}`) }}: {{  el || 'Пусто' }}
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
    props: ['data', 'open'],
}
</script>
