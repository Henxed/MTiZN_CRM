<template>
    <app-layout title="Новый реестр">
        <form @submit.prevent="submit" class="sm:flex" >
        <div>
            <div class="sticky top-5 mb-5">
                <div class="bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-300 p-7 rounded-xl sm:w-72">
                    <div class=" text-lg text-center uppercase">Реестры</div>
                    <div class="mb-5 text-sm text-center">Выберите категорию реестра</div>

                    <div v-for="item in categories" :key="item.id">
                        <input type="radio" v-model="form.category_id" :id="'c_'+item.id" :value="item.id" class="peer hidden">
                        <label :for="'c_'+item.id" class="cursor-pointer block p-2 px-4 mb-4 rounded-xl bg-gray-200/50 dark:bg-slate-600/30 peer-checked:bg-gray-300 dark:peer-checked:bg-slate-600 dark:peer-checked:text-slate-300">{{ item.name }}</label>
                    </div>
                    <div v-if="errors.category_id" class="form-error">{{ errors.category_id }}</div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-6 rounded-xl bg-white text-slate-900 p-4 sm:p-9 sm:mx-6 dark:bg-slate-800 w-full max-w-screen-2xl">
            <div class="relative grid grid-cols-1 sm:grid-cols-5 gap-x-4 bg-gray-200 dark:bg-slate-600 dark:text-slate-300 p-4 rounded-xl
                before:absolute before:top-5 before:bottom-5 before:-left-0.5 before:w-1 before:rounded-full"
                :class="status_color(form.status_id)">

                    <div class="col-span-4">
                        <div class="text-base mb-3 empty:bg-slate-400/60 dark:empty:bg-slate-500 empty:h-5 empty:w-24 empty:rounded-md">{{ form.title }}</div>
                        <div class="text-sm mb-3 text-gray-500 dark:text-slate-400 empty:bg-slate-400/60 dark:empty:bg-slate-500 empty:h-4 empty:w-18 empty:rounded-md">{{ form.depart }}</div>
                    </div>
                    <div class="sm:row-start-2 sm:col-span-2 self-end text-xs text-slate-400/70 empty:bg-slate-400/60 dark:empty:bg-slate-500 empty:h-3 empty:w-12 empty:rounded-md">{{ form.npa }}</div>
                    <div class="sm:row-start-2 sm:col-start-3 sm:col-span-3 self-end sm:text-right text-sm text-slate-400"> <span class="mr-5">{{ form.term ? 'До ' + form.term : 'Бессрочный' }}</span> {{ status_name ? status_name : 'Без статуса' }}</div>            </div>

            <div class="flex flex-wrap">
                <text-input v-model="form.title" :error="errors.title" class="sm:px-3 pb-1 w-full" label="Наименование" />
                <text-input v-model="form.npa" :error="errors.npa" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Реквизит НПА" />
                <text-input v-model="form.depart" :error="errors.depart" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Ответственный отдел" />
                <text-input v-model="form.term" :error="errors.term" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Срок действия до" help='Если оставить пустым, то будет "бессрочным"' type="date" />
                <select-input v-model="form.status_id" :error="errors.status_id" class="sm:px-3 pb-1 w-full lg:w-1/2" label="Статус" @change="status_name_change($event)">
                    <option v-for="item in statuses" :key="item" :value="item.code">{{ item.name }}</option>
                </select-input>
            </div>

            <div class="rounded-lg overflow-hidden" v-cloak @drop.prevent="addFile" @dragover.prevent>
                <div class="md:flex">
                    <div class="w-full p-3">
                        <div class="relative h-48 rounded-lg border-dashed border-2 border-slate-700 dark:border-slate-500 flex justify-center items-center">
                            <div class="absolute">
                                <div class="flex flex-col items-center"> <i class="fi fi-rr-cloud-upload text-6xl text-slate-700 dark:text-slate-400"></i>
                                    <span class="block px-5 text-center text-gray-400 font-normal">Перетащите в эту область файлы или <span class="text-slate-500 dark:text-slate-400 underline">нажмите сюда</span>, чтобы загрузить свои файлы</span>
                                </div>
                            </div> <input type="file" class="h-full w-full opacity-0 hover:cursor-pointer" @change="changeFile($event)" ref="file" multiple>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative h-full text-slate-600 dark:text-slate-300 bg-slate-300/20 ckdrop-blur-sm rounded-xl p-6 mx-3" v-if="form.files.length">
                <div class="flex overflow-x-auto" >
                    <div class="block text-center hover:bg-slate-300/30 p-3 rounded-lg w-32 z-50" v-for="file in form.files" :key="file" >
                        <div class="text-4xl"><i class="fi fi-rr-file"></i></div>
                        <div class="text-xs h-6 marquee">{{ file.name }}</div>
                        <div class="text-xs text-slate-500/70 dark:text-slate-300/50">{{ filesize(file.size) }}</div>
                        <button @click="removeFile(file)" class="text-red-600/90 dark:text-red-400" title="Убирает файл перед загрузкой">Удалить</button>
                    </div>
                </div>
            </div>

            <loading-button :loading="form.processing" class="btn-green mx-auto w-full max-w-xs" type="submit">Добавить</loading-button>
        </div>

        </form>

    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import { Link } from '@inertiajs/inertia-vue3'


export default {
    components: {
        AppLayout,
        LoadingButton,
        SelectInput,
        TextInput,
        Link
    },
    props: {
        statuses: Array,
        categories: Array,
        errors: Object,
    },
    data() {
        return {
        status_name: null,
        form: this.$inertia.form({
            title: '',
            npa: '',
            depart: '',
            term: '',
            category_id: null,
            status_id: null,
            files: []
        })
        }
    },
    methods: {
        filesize(size) {
            var i = Math.floor(Math.log(size) / Math.log(1024))
            return (size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
        },
        changeFile(event) {
            this.form.files.push(...this.$refs.file.files)
        },
        addFile(e) {
            let droppedFiles = e.dataTransfer.files;
            if(!droppedFiles) return;

            this.form.files.push(...droppedFiles)
        },
        removeFile(file){
            this.form.files = this.form.files.filter(f => {
                return f != file;
            });
        },
        status_color: function(e){
            switch (e) {
                case 100:
                    return 'before:bg-emerald-400'
                break;
                case 101:
                    return 'before:bg-amber-400'
                break;
                case 102:
                    return 'before:bg-pink-400'
                break;
                case 103:
                    return 'before:bg-red-500'
                break;
                default:
                    return 'before:bg-slate-400'
                    break;
            }
        },
        status_name_change: function(e){
            this.status_name = e.target.options[e.target.options.selectedIndex].text;
        },
        submit() {
            this.$toast.open({message: 'Добавляю реестр... Ожидайте!', type: 'default'})
            this.form.post(this.route('registry.store'), {
                onSuccess: () => this.$toast.open({message: 'Реестр добавлен!'}),
            }).catch(() => {
                this.$toast.open({message: 'Возникла ошибка при добавление...', type: 'error'})
            })
        }

    },
}
</script>
