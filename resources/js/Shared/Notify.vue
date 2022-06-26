<template>
    <div class="relative">
        <button @click.prevent="toggleOpen" v-tippy='"Уведомления"' class="fix-svg-btn relative">
            <svg class="outline-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg>
            <div class="absolute top-0 left-5 p-1 h-4 flex items-center rounded-full text-xs leading-none bg-red-500 text-white" v-if="n_count">
                {{ count }}
            </div>
        </button>
        <!-- При нажатие на пустую область, будет закрывать -->
        <div v-show="isOpen" class="fixed inset-0 z-50" @click="isOpen = false"></div>

        <transition v-scroll-lock="isOpen"
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div class="absolute z-50 mt-2 rounded-md shadow-lg w-80 bg-white dark:bg-slate-700 top-10 right-0" v-show="isOpen" >
                <div class="px-4 p-3 font-bold text-center">Уведомления</div>
                <perfect-scrollbar class="px-4 max-h-60 h-full pb-4" v-if="data.length" :options="{suppressScrollX: true}">
                    <div class="relative group px-3 py-2 my-2 rounded-xl leading-none" v-for="item in data" :key="item.id" :ref="item.id"
                    :class="item.read_at ? 'bg-slate-200 dark:bg-slate-600 text-slate-800/40 dark:text-slate-400' : 'bg-gray-200 dark:bg-slate-600 dark:text-slate-300'">
                        <div class="flex items-center">
                            <div class="text-sm font-bold truncate flex-1">{{ JSON.parse(item.data).title }}</div>
                            <div class="text-micro group-hover:hidden" :class="item.read_at ? 'text-slate-800/30 dark:text-slate-500' : 'text-slate-500 dark:text-slate-400'">{{ $moment(item.created_at).format('DD/MM/YY, h:mm') }}</div>
                        </div>
                        <div class="text-xs leading-4">{{ JSON.parse(item.data).message }}</div>
                        <div class="absolute right-0 top-0 hidden text-xs group-hover:flex items-center justify-end h-full"
                        >
                            <div class="flex items-center justify-center leading-none
                             px-2 rounded-md bg-slate-500/10 text-slate-700 h-5 w-5 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500"
                            v-tippy='"Прочитать"'
                            @click="read(item.id)" v-if="!item.read_at">
                                <i class="fi fi-rr-check"></i>
                            </div>
                            <div class="flex items-center justify-center leading-none mx-2
                             px-2 rounded-md bg-slate-500/10 text-slate-700 h-5 w-5 hover:bg-slate-300 dark:text-slate-300/80 dark:hover:bg-slate-500"
                            v-tippy='"Удалить"'
                            @click="remove(item.id)">
                                <i class="fi fi-rr-trash"></i>
                            </div>
                        </div>
                    </div>
                <div @click.prevent="loadDataFromServer" class="mx-auto my-2 px-3 py-1 text-xs max-w-max cursor-pointer text-center bg-slate-200 hover:bg-slate-300 dark:bg-slate-500 dark:hover:bg-slate-600 rounded-full" v-if="data.length > 15 && isLoadBtn">Показать ещё...</div>

                </perfect-scrollbar>
                <div class="h-32 sm:w-44 text-base text-slate-500 leading-none text-center whitespace-pre-wrap mx-auto flex justify-center items-center" v-else-if="!isLoad">
                    Сейчас у вас нет новых уведомлений
                </div>
                <div class="h-32 flex justify-center items-center" v-if="isLoad">
                    <svg class="mr-3 h-5 w-5 animate-spin text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

export default {
    components: {
        PerfectScrollbar,
    },
    data() {
        return{
            isOpen: false,
            isLoad: false,
            isLoadBtn: false,
            page: 2,
            n_count: this.$page.props.n_count,
            data: []
        }
    },
    mounted(){
        Echo.private(`notify.${this.$page.props.user.id}`)
            .listen('Notify', (e) => {
                this.n_count++
                this.data.unshift(Object.values(e)[0]);
            })
        document.addEventListener('keydown', this.closeOnEscape) // закрытие по esc
    },
    computed: {
        count: function () {
            if(this.n_count > 99) {
                return '99+'
            }else if(this.n_count < 1){
                this.n_count = 0
                return 0
            }else{
                return this.n_count
            }
        }
    },
    unmounted(){
        document.removeEventListener('keydown', this.closeOnEscape)
    },
    methods: {
        closeOnEscape: function (e) {
            if (this.isOpen && e.keyCode === 27) {
                this.isOpen = false
            }
        },
        toggleOpen: function ()  {
            this.isOpen = !this.isOpen

            if(!this.data.length) {
                this.isLoad = !this.isLoad
                this.isLoadBtn = !this.isLoadBtn
                axios.get(route('notification.get.popup')).then(response => {
                        this.isLoad = !this.isLoad
                        this.data = response.data.data
                    }).catch(e => {
                        console.log("Error... ")
                    });
            }
        },
        loadDataFromServer: function ()  {
            this.isLoad = !this.isLoad
            axios.get(route('notification.get.popup', {
                page : this.page
            })).then(response => {
                this.isLoad = !this.isLoad
                this.data.push(...response.data.data)
                if(response.data.last_page < this.page){
                    this.isLoadBtn = !this.isLoadBtn
                }
            });
            this.page++;
        },
        read: function (id) {
            axios.get(route('notification.set.read', id)).then(response => {
                this.n_count--
                var foundIndex = this.data.findIndex(x => x.id == id);
                this.data[foundIndex]['read_at'] = response.data;
            });
        },
        remove: function (id) {
            axios.get(route('notification.remove', id)).then(response => {
                if(response.data){
                    let f = this.data.filter(x => x.id == id )[0];

                    if(f.read_at === null){
                        this.n_count--
                    }
                    this.data = this.data.filter(x => x.id != id);
                }
            });
        }
    }
}
</script>
<style src="vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css"/>
