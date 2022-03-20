<template>
    <app-layout title="Карта регионов">

        <div class="w-full grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-12  lg:max-w-screen-lg xl:max-w-screen-2xl">
            <div class="col-span-12 lg:col-span-12 xl:col-span-8 relative">
                <svg version="1.1" class="hidden sm:flex" id="orenburg" viewBox="0 0 1030 599">
                    <path class="st1"
                    v-for="region in regions"
                    :key="region.id"
                    :d='region.d'
                    :ref="setItemRef"
                    :id="region.id"
                    @mousemove="moveOnLocation"
                    @mouseout="unpointLocation"
                    @mouseover="pointLocation(region.region)"
                    @click="getInfo(region, $event)" />

                    <g class="text-svg" v-if="listDistante.length > 1">
                        <text class="text-slate-600 dark:text-slate-300" x="500" y="40" fill="currentColor" alignment-baseline="central" text-anchor="left" font-size="32">
                            {{ listDistante[0] }}
                        </text>
                        <text  class="text-slate-500 dark:text-slate-400" x="500" y="60" fill="currentColor" alignment-baseline="central" text-anchor="right" font-size="18">
                            <tspan x="505" dy="1.5em" v-for="item in listDistante.slice(1)" :key="item">{{ item }}</tspan>
                        </text>
                    </g>
                    <g class="text-svg w-48" v-else-if="reg && stats === 'commissions'">
                        <text class="text-slate-600 dark:text-slate-300" x="500" y="40" fill="currentColor" text-anchor="left" font-size="32">
                            {{ reg.city }}
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="55" fill="currentColor" text-anchor="left" font-size="16">
                            <tspan x="500" dy="1em">
                                Процент исполнения контрольного показателя — {{ reg.commissions }}%
                            </tspan>
                            <tspan x="500" dy="1.7em">Контрольный показатель по снижению</tspan>
                            <tspan x="500" dy="1.2em">неформальной занятости — {{ reg.commissions_c }}</tspan>
                            <tspan x="500" dy="1.7em">Заключено трудовых договоров в рамках работы</tspan>
                            <tspan x="500" dy="1.2em">по снижению неформальной занятости — {{ reg.commissions_t }}</tspan>
                            <tspan x="500" dy="1.7em">Количество проведенных территориальных</tspan>
                            <tspan x="500" dy="1.2em">межведомственной комиссии — {{ reg.in_employment }}</tspan>
                        </text>
                    </g>
                    <g class="text-svg" v-else>
                        <text class="text-slate-600 dark:text-slate-300" x="500" y="40" fill="currentColor" text-anchor="left" font-size="32">
                            Оренбургская область
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="70" fill="currentColor" text-anchor="left" font-size="14">
                            ПЛОЩАДЬ ТЕРРИТОРИИ — 123700 кв.км
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="90" fill="currentColor" text-anchor="left" font-size="14">
                            НАСЕЛЕНИЕ — 1963007 чел.
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="110" fill="currentColor" text-anchor="left" font-size="14">
                            РАБОТОДАТЕЛИ — 83284 ед., в том числе:
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="635" y="130" fill="currentColor" text-anchor="left" font-size="14">
                            37383 — предприятия и организации;
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="635" y="150" fill="currentColor" text-anchor="left" font-size="14">
                            45901 — индивидуальные предприниматели.
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="170" fill="currentColor" text-anchor="left" font-size="14">
                            ВАКАНСИИ — 10819 мест
                        </text>
                        <text class="text-slate-500 dark:text-slate-400" x="500" y="190" fill="currentColor" text-anchor="left" font-size="14">
                            УРОВЕНЬ БЕЗРАБОТИЦЫ — {{sum}}%
                        </text>
                    </g>
                </svg>



                <div class="flex flex-col items-center justify-center mx-auto mt-10 ">

                    <div class="flex items-center bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 rounded-full max-w-sm w-full pr-3 shadow-md h-10">
                        <input class="border-none focus:outline-none focus:ring-0 flex-1 h-full w-full p-4 bg-white dark:bg-slate-700  rounded-full"
                        type="text"
                        placeholder="Поиск региона..."
                        v-model="keyword"
                        @input="search">
                        <i class="fi fi-rr-search pt-1 mr-1"></i>
                    </div>

                <div class="grid gap-4 grid-cols-1 sm:grid-cols-3 mt-6 select-none">
                    <div class="flex">
                        <input class="peer hidden" id="stats_u" type="radio" v-model="stats" value="lvl" />
                        <label for="stats_u" class=" cursor-pointer text-slate-800 dark:text-slate-200 w-full max-w-xs bg-slate-600/10 hover:bg-slate-600/20 peer-checked:bg-slate-600 peer-checked:text-slate-200 p-4 rounded-xl" @click.prevent="uncheck('lvl')">
                            Информация о ситуации на рынке труда
                        </label>
                    </div>
                    <div class="flex">
                        <input class="peer hidden" id="stats_t" type="radio" v-model="stats" value="tension" />
                        <label for="stats_t" class=" cursor-pointer text-slate-800 dark:text-slate-200 w-full max-w-xs bg-slate-600/10 hover:bg-slate-600/20 peer-checked:bg-slate-600 peer-checked:text-slate-200 p-4 rounded-xl" @click.prevent="uncheck('tension')">
                            Уровень напряженности
                        </label>
                    </div>
                    <div class="flex">
                        <input class="peer hidden" id="stats_c" type="radio" v-model="stats" value="commissions" />
                        <label for="stats_c" class=" cursor-pointer text-slate-800 dark:text-slate-200 w-full max-w-xs bg-slate-600/10 hover:bg-slate-600/20 peer-checked:bg-slate-600 peer-checked:text-slate-200 p-4 rounded-xl" @click.prevent="uncheck('commissions')">
                            Процент исполнения контрольного показателя по снижению неформальной занятости
                        </label>
                    </div>
                </div>
                    <!-- <div @click="setStats('lvl')">Напряженность</div> -->
                    <!-- <div class="relative inline-block w-10 align-middle select-none transition duration-200 ease-in">
                        <input class="tgl tgl-skewed" id="ext.name" type="checkbox" >
                        <label class="tgl-btn rounded-xl" data-tg-off="OFF" data-tg-on="ON" for="ext.name"></label>
                    </div> -->
                </div>
                <div class="hidden sm:flex absolute right-9 top-1/4 w-1 h-40 rounded-xl flex-col justify-between" ref='linePct'></div>
            </div>
            <div class="sm:p-4 col-span-12 xl:col-span-4" v-if="reg">
                <div class="w-full bg-slate-300/75 p-5 dark:bg-slate-800 dark:text-slate-400 rounded-lg">
                    <div class="grid sm:gap-x-4 text-sm grid-cols-1 sm:grid-cols-5 mb-4">
                        <div class="text-base font-bold col-span-5">{{ reg.region }}</div>
                        <div class="text-sm text-slate-500 col-span-2">{{ reg.city }}</div>
                        <div class="text-sm text-slate-500 leading-none text-right col-span-3">{{ reg.leader }}</div>
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
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Зарегистрированно безработных</div>
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

                    <div class="flex">
                        <div class="rounded-lg text-slate-600 bg-slate-400/60 dark:text-slate-300 w-9 h-9 flex items-center justify-center text-lg leading-none">
                            <i class="fi fi-rr-wheelchair"></i>
                        </div>
                        <div class="ml-4 leading-snug">
                            <div class="font-bold text-slate-600 dark:text-slate-400 stroke-current">Численность инвалидов</div>
                            <div class="text-slate-500">{{ reg.cripple }} чел., трудоустроено — {{ reg.cripple_worked }} чел.</div>
                        </div>
                    </div>

                    <Link :href="route('regions.edit', reg.id)" class="block btn-default mt-4" v-if="$page.props.access.can.includes('region.edit') || $page.props.access.role.includes('super-admin') || $page.props.access_region.includes(reg.id)">
                        Редактировать
                    </Link>
                    <Link :href="route('regions.show', reg.id)" class="block btn-default mt-4" >
                        Подробнее
                    </Link>
                </div>
                <div class="text-center cursor-pointer mt-3 dark:text-slate-400 text-slate-500" @click.prevent="close_all">Закрыть (ESC)</div>
            </div>

      </div>
        <div class="absolute px-2 py-1 text-sm bg-slate-800 text-slate-100 dark:text-slate-800 dark:bg-slate-100 rounded-md" :style="tooltipStyle">
            {{ pointedLocation }}
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
            Link,
        },
        props: {
            regions: Array,
            access_region: Array
        },
        data() {
            return {
                pointedLocation: null,
                tooltipStyle: { display: 'none' },
                reg: null,
                itemRefs: [],
                listDistante: [],
                stats: false,
                sum: this.regions.reduce((p, c) => p + parseInt(c.lvl), 0), // "p" - предыдущий, "с" - текущий. "р" != массив региона, а "с" - индекс массива
                percentColors: {
                    'lvl': [
                        { pct: 0, color: { r: 161, g: 208, b: 106 } },  //белый
                        { pct: 2, color: { r: 160, g: 243, b: 155 } },  //зеленый
                        { pct: 4, color: { r: 255, g: 214, b: 80 } },   //желтый
                        { pct: 6, color: { r: 255, g: 163, b: 55 } },   //оранжевый
                        { pct: 8, color: { r: 212, g: 101, b: 36 } },   //коричневый
                        { pct: 100, color: { r: 214, g: 43, b: 0 } },    //красный
                    ],
                    'tension': [
                        { pct: 0, color: { r: 161, g: 208, b: 106 } },  //белый
                        { pct: 4, color: { r: 160, g: 243, b: 155 } },  //зеленый
                        { pct: 8, color: { r: 255, g: 214, b: 80 } },  //зеленый
                        { pct: 16, color: { r: 255, g: 163, b: 55 } },   //желтый
                        { pct: 22, color: { r: 212, g: 101, b: 36 } },  //оранжевый
                        { pct: 100, color: { r: 214, g: 43, b: 0 } },    //красный
                    ],
                    'commissions': [
                        { pct: 0, color: { r: 214, g: 43, b: 0 } },    //красный
                        { pct: 5, color: { r: 255, g: 87, b: 34  } },  //коричневый
                        { pct: 16, color: { r: 255, g: 152, b: 0 } },  //оранжевый
                        { pct: 28, color: { r: 255, g: 235, b: 59 } },   //желтый
                        { pct: 60, color: { r: 184, g: 225, b: 119 } },  //зеленый
                        { pct: 100, color: { r: 136, g: 217, b: 139 } },  //белый
                        { pct: 500, color: { r: 136, g: 217, b: 139 } },
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
            document.body.addEventListener('keyup', e => {
                if (e.keyCode === 27) {
                    this.close_all() // how to hide any open modal?
                }
            })
        },
        methods: {
            uncheck: function (val) {

                if (val === this.stats) {
                    this.stats = false;
                    this.$refs.linePct.innerHTML = '';
                    this.$refs.linePct.style.background = '';
                    for (let i = 0; i < this.regions.length; i++) {
                        this.itemRefs[i].style.fill = '';
                    }
                } else {
                    this.stats = val
                    for (let i = 0; i < this.regions.length; i++) {
                        if(this.regions[i][val] !== null) {
                            this.itemRefs[i].style.fill = this.getColorForPercentage(this.percentColors[val], this.regions[i][val].replace(/[,.]/gi, '.'));
                        }
                    }
                    this.linePct(this.percentColors[val]);
                }

            },
            linePct: function (arr){
                let h_arr = '';
                for(var i = 0; i < arr.length; i++){
                    if (arr[i].pct !== undefined && arr[i].pct <= 100) {
                        h_arr += '<div class="ml-3 text-slate-700 dark:text-slate-300">'+arr[i].pct+'%</div>';
                    }
                }
                this.$refs.linePct.innerHTML = h_arr;

                let line = [];
                let part = 100 / arr.length;

                for (var i = 1; i < arr.length; i++) {
                    line += 'rgb(' + [arr[i].color.r, arr[i].color.g, arr[i].color.b].join(',') + ') '+ part * i +'%'
                    if(i < arr.length - 1){
                        line += ', '
                    }
                }

                this.$refs.linePct.style.background = `linear-gradient(${line})`;
            },
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
            getInfo: function(arr, event){
                let id =  event >= 0 ? event : event.currentTarget.id -1;
                this.reg = arr;
                this.listDistante = [];
                if(!this.stats){
                    for (let index = 0; index < this.itemRefs.length; index++) {
                        this.itemRefs[index].style = '';
                        this.itemRefs[index].classList.remove('region-main')
                    }

                    this.itemRefs[id].classList.add('region-main') //.style.fill = '#849ab9'; //#a19cdf
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

                }else{
                    for (let index = 0; index < this.itemRefs.length; index++) {
                        this.itemRefs[index].classList.remove('region-main')
                    }
                    this.itemRefs[id].classList.add('region-main')
                }
            },
            pointLocation: function(e) {
                this.pointedLocation = e;
            },
            unpointLocation: function(event) {
                this.pointedLocation = null;
                this.tooltipStyle = { display: 'none' };
            },
            moveOnLocation: function(event) {
                this.tooltipStyle = {
                    display: 'block',
                    top: `${event.clientY + window.scrollY +30}px`,
                    left: `${event.clientX +  window.scrollX - 100}px`,
                }
            },
            setItemRef: function(el) {
                if (el) {
                    if(this.itemRefs.length <= this.regions.length) {
                        this.itemRefs.push(el)
                    }
                }
            },
            close_all: function() {
                for (let index = 0; index < this.itemRefs.length; index++) {
                    if(!this.stats) {
                        this.itemRefs[index].style = '';
                    }
                    this.itemRefs[index].classList.remove('region-main')
                }
                this.reg = null;
                this.listDistante = []
            }
	    },
      beforeUpdate() {
          this.itemRefs = []
      },
    }
</script>

