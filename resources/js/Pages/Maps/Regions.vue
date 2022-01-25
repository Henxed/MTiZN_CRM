<template>
    <app-layout title="Карта регионов">

        <div class="w-full grid gap-8 gap-y-2 text-sm grid-cols-1 2xl:grid-cols-3 ">
            <div class="2xl:col-span-2 max-w-screen-2xl">



                <svg version="1.1" class="hidden sm:flex" id="orenburg" viewBox="0 0 1030 599">
                    <path class="st1"
                    v-for="region in regions"
                    :key="region.id"
                    :d='region.d'
                    :ref="setItemRef"
                    :id="region.id"
                    @mousemove="moveOnLocation"
                    @mouseout="unpointLocation"
                    @mouseover="pointLocation(region.id +' - '+region.region)"
                    @click="getInfo(region, $event)" />

                    <g class="text-svg" v-if="listDistante.length > 1">
                        <text class="text-slate-500" x="470" y="60" fill="currentColor" alignment-baseline="central" text-anchor="left" font-size="24">
                            {{ listDistante[0] }}
                        </text>
                        <text v-for="(item, index) in listDistante.slice(1)" :key="item" class="text-slate-400" x="560" :y="90 + (25*index)" fill="currentColor" alignment-baseline="central" text-anchor="right">
                            {{ item }}
                        </text>
                    </g>
                    <g class="text-svg" v-else>
                        <text class="text-slate-600 dark:text-slate-300" x="500" y="40" fill="currentColor" text-anchor="left" font-size="32">
                            Оренбургская область
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="500" y="70" fill="currentColor" text-anchor="left" font-size="14">
                            ПЛОЩАДЬ ТЕРРИТОРИИ — 123700 кв.км
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="500" y="90" fill="currentColor" text-anchor="left" font-size="14">
                            НАСЕЛЕНИЕ — 1963007 чел.
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="500" y="110" fill="currentColor" text-anchor="left" font-size="14">
                            РАБОТОДАТЕЛИ — 83284 ед., в том числе:
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="626" y="130" fill="currentColor" text-anchor="left" font-size="14">
                            37383 — предприятия и организации;
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="626" y="150" fill="currentColor" text-anchor="left" font-size="14">
                            45901 — индивидуальные предприниматели.
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="500" y="170" fill="currentColor" text-anchor="left" font-size="14">
                            ВАКАНСИИ — 10819 мест
                        </text>
                        <text class="text-slate-600 dark:text-slate-400" x="500" y="190" fill="currentColor" text-anchor="left" font-size="14">
                            УРОВЕНЬ БЕЗРАБОТИЦЫ — 7,59%
                        </text>
                    </g>
                </svg>

                <div class="absolute px-2 py-1 text-sm bg-slate-800 text-slate-100 dark:text-slate-800 dark:bg-slate-100 rounded-md" :style="tooltipStyle">
                    {{ pointedLocation }}
                </div>

                <div class="flex justify-center mx-auto mt-10 ">

                    <div class="flex items-center bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 shadow-md h-10">
                        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-white dark:bg-slate-700  rounded-full"
                        type="text"
                        placeholder="Поиск региона..."
                        v-model="keyword"
                        @input="search">
                        <i class="fi fi-rr-search pt-1 mr-1"></i>
                    </div>
                    <!-- <div @click="setStats('lvl')">Напряженность</div> -->
                    <!-- <div class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                        <input class="tgl tgl-skewed" id="ext.name" type="checkbox" >
                        <label class="tgl-btn rounded-xl" data-tg-off="OFF" data-tg-on="ON" for="ext.name"></label>
                    </div> -->
                </div>

            </div>
            <div class="p-4">
                <div class="w-full bg-slate-300/75 p-5 dark:bg-slate-800 dark:text-slate-400 rounded-lg" v-if="reg">
                    <div class="grid gap-4 gap-y-0 text-sm grid-cols-1 sm:grid-cols-3 mb-4">
                        <div class="text-base font-bold col-span-3">{{ reg.region }}</div>
                        <div class="text-sm text-slate-500 ">{{ reg.city }}</div>
                        <div class="text-sm text-slate-500 leading-none text-right col-span-2">{{ reg.leader }}</div>
                    </div>
                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            ₽
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Средняя заработная плата</div>
                            <div class="text-slate-500">{{ reg.amw }}₽</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-building pt-1"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Население</div>
                            <div class="text-slate-500">{{ reg.population }} чел.</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-tree"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Число хозяйствующих субъектов</div>
                            <div class="text-slate-500">{{ reg.subject }} ед.</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-diploma pt-1"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Заключенные коллективные договоры</div>
                            <div class="text-slate-500">{{ reg.contract }} ед.</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-chat-arrow-down"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Уровень безработицы</div>
                            <div class="text-slate-500">{{ reg.lvl }}%</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-user-time pt-1"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Численность зарегистрированных безработных</div>
                            <div class="text-slate-500">{{ reg.unemployed }} чел.</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-briefcase pt-1"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Количество вакансий</div>
                            <div class="text-slate-500">{{ reg.vacancy }} мест</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-id-badge pt-1"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Трудоустроено</div>
                            <div class="text-slate-500">{{ reg.employed }} чел.</div>
                        </div>
                    </div>

                    <div class="flex mb-4">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-wheelchair"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Численность инвалидов</div>
                            <div class="text-slate-500">{{ reg.cripple }} чел., трудоустроено — {{ reg.cripple_worked }} чел.</div>
                        </div>
                    </div>

                </div>
            </div>

      </div>
      <!-- Нельзя -->
    </app-layout>
</template>


<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout'



    export default{
        components: {
            AppLayout,
            Head,
            Link
        },
        props: {
            regions: Array,
        },
        data() {
            return {
                pointedLocation: null,
                tooltipStyle: { display: 'none' },
                reg: null,
                itemRefs: [],
                listDistante: [],
                stats: false,
                percentColors: {
                    'U': [
                        { pct: 0, color: { r: 161, g: 208, b: 106 } },  //белый
                        { pct: 2, color: { r: 160, g: 243, b: 155 } },  //зеленый
                        { pct: 4, color: { r: 255, g: 214, b: 80 } },   //желтый
                        { pct: 6, color: { r: 255, g: 163, b: 55 } },   //оранжевый
                        { pct: 8, color: { r: 212, g: 101, b: 36 } },   //коричневый
                        { pct: 10, color: { r: 214, g: 43, b: 0 } },    //красный
                    ],
                    'T': [
                        { pct: 0, color: { r: 161, g: 208, b: 106 } },  //белый
                        { pct: 4, color: { r: 160, g: 243, b: 155 } },  //зеленый
                        { pct: 8, color: { r: 255, g: 214, b: 80 } },  //зеленый
                        { pct: 16, color: { r: 255, g: 163, b: 55 } },   //желтый
                        { pct: 22, color: { r: 212, g: 101, b: 36 } },  //оранжевый
                        { pct: 28, color: { r: 214, g: 43, b: 0 } },    //красный
                    ],
                    'C': [
                        { pct: 0, color: { r: 214, g: 43, b: 0 } },    //красный
                        { pct: 5, color: { r: 210, g: 102, b: 46 } },  //коричневый
                        { pct: 16, color: { r: 214, g: 174, b: 78 } },  //оранжевый
                        { pct: 28, color: { r: 214, g: 206, b: 78 } },   //желтый
                        { pct: 42, color: { r: 169, g: 214, b: 63 } },  //зеленый
                        { pct: 60, color: { r: 65, g: 165, b: 65 } },  //белый
                    ]
                },
                keyword: ''
            }
        },
        computed: {
            search() {
                let r;
                if(this.keyword) {
                    r = this.regions.filter((el) => {
                        return this.keyword.toLowerCase().split(' ').every(v => el.region.toLowerCase().includes(v));
                    });
                    this.getInfo(r[0], r[0].id-1)
                }else{
                    this.reg = null
                    for (let index = 0; index < this.itemRefs.length; index++) {
                        this.itemRefs[index].style = '';
                    }
                }

            }
        },
        mounted(){

        },
        methods: {
            getColorForPercentage: function(colors, pct) {
                for (var i = 1; i < colors.length - 1; i++) {
                    if (pct < colors[i].pct) {
                        break;
                    }
                }
                var lower = colors[i - 1];
                var upper = colors[i];
                var range = upper.pct - lower.pct;
                var rangePct = (pct - lower.pct) / range;
                var pctLower = 1 - rangePct;
                var pctUpper = rangePct;
                var color = {
                    r: Math.floor(lower.color.r * pctLower + upper.color.r * pctUpper),
                    g: Math.floor(lower.color.g * pctLower + upper.color.g * pctUpper),
                    b: Math.floor(lower.color.b * pctLower + upper.color.b * pctUpper)
                };
                return 'rgb(' + [color.r, color.g, color.b].join(',') + ')';
            },
            getInfo(arr, event){
                let id =  event >= 0 ? event : event.currentTarget.id -1;
                this.reg = arr;
                this.listDistante = [];
                if(!this.stats){
                    for (let index = 0; index < this.itemRefs.length; index++) {
                        this.itemRefs[index].style = '';
                    }

                    this.itemRefs[id].style.fill = '#849ab9'; //#a19cdf
                    // if (this.classList.contains('bad')) {
                    //     // The box that we clicked has a class of bad so let's remove it and add the good class
                    //     this.classList.remove('bad');
                    //     this.classList.add('good');
                    // }
                    this.listDistante.push(`От «${arr.city}» до:`)

                    this.regions[id].distance.forEach(element => {
                        this.itemRefs[element.go_id-1].style.fill = '#bdcadd'; //#d4d2f3
                        this.listDistante.push(`${this.regions[element.go_id-1].city}  —  ${element.distance} км`)
                    });

                }
            },
            pointLocation(e) {
                this.pointedLocation = e;
            },
            unpointLocation(event) {
                this.pointedLocation = null;
                this.tooltipStyle = { display: 'none' };
            },
            moveOnLocation(event) {
                this.tooltipStyle = {
                    display: 'block',
                    top: `${event.clientY + 30}px`,
                    left: `${event.clientX - 100}px`,
                }
            },
            setItemRef(el) {
                if (el) {
                    if(this.itemRefs.length <= this.regions.length) {
                        this.itemRefs.push(el)
                    }
                }
            },
            setStats(el){
                if(el == 'lvl') {
                    for (let i = 0; i < this.regions.length; i++) {
                        this.itemRefs[i].style.fill = this.getColorForPercentage(this.percentColors['U'], this.regions[i].lvl.replace(/,/gi, '.'))
                    }
                }
                if(el == 'ex') {
                    for (let i = 0; i < this.regions.length; i++) {
                        this.itemRefs[i].style.fill = ''
                    }
                }
            },
	    },
      beforeUpdate() {
          this.itemRefs = []
      },
    }
</script>

