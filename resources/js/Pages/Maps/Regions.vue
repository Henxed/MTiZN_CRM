<template>
    <app-layout title="Карта регионов">

        <div class="w-full grid gap-8 gap-y-2 text-sm grid-cols-1 2xl:grid-cols-3 ">
            <div class="2xl:col-span-2 max-w-screen-2xl">
                <svg version="1.1" class="svg-auto map" id="orenburg" viewBox="0 0 1030 599">
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
                        <text class="text-slate-500" x="500" y="20" fill="currentColor" alignment-baseline="central" text-anchor="left" font-size="24">
                            {{ listDistante[0] }}
                        </text>
                        <text v-for="(item, index) in listDistante.slice(1)" :key="item" class="text-slate-400" x="600" :y="50 + (25*index)" fill="currentColor" alignment-baseline="central" text-anchor="right">
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
            </div>
            <div class="p-4">
                <div class="w-full bg-slate-300 p-5 dark:bg-slate-800 dark:text-slate-400 rounded-lg" v-if="reg">
                    <div class="grid gap-4 gap-y-0 text-sm grid-cols-1 sm:grid-cols-2">
                        <div class="text-base font-bold col-span-2">{{ reg.region }}</div>
                        <div class="text-sm text-slate-500 ">{{ reg.city }}</div>
                        <div class="text-sm text-slate-500 leading-none text-right">{{ reg.leader }}</div>
                    </div>
                </div>
            </div>

        <div @click="setStats('lvl')">Напряженность</div>
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
                let id = event.currentTarget.id - 1;
                this.reg = arr;
                this.listDistante = [];
                if(!this.stats){
                    for (let index = 0; index < this.itemRefs.length; index++) {
                        this.itemRefs[index].style = '';
                    }

                    this.itemRefs[id].style.fill = '#849ab9'; //#a19cdf
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

