<template>
    <app-layout :title="`${region.region} - Предприятия`">

        <div class="w-full max-w-screen-2xl">
            <div class="grid sm:grid-cols-4 items-center">
                <breadcrumbs :data="bread" class="my-4 sm:col-span-3"/>
                <Link class="btn-secondary ml-auto" :href="route('regions.enterprises.edit', [region.id, enterprise.id])"
                v-if="$page.props.access.can.includes('enterprise.create') || $page.props.access.role.includes('super-admin') || $page.props.access_region.includes(region.id)">
                    <span>Редактировать</span>
                    <span class="hidden md:inline"> предприятие</span>
                </Link>
            </div>

            <div class="card p-4">
                <div class="p-4">
                    <div class="text-3xl dark:text-slate-300">{{ enterprise.name }}</div>
                    <h3 class="text-lg leading-6 font-medium text-slate-900 dark:text-slate-300">{{ enterprise.okvd_name || "ОКВЭД неизвестен" }}</h3>
                    <p class="mt-1 max-w-2xl text-sm">{{ enterprise.status ? enterprise.status.name : "Статус неизвестен"}}</p>
                </div>
                <div class="border-t border-gray-200 dark:border-slate-600">
                <dl>
                    <div class="bg-white dark:bg-slate-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ent.inn`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ enterprise.inn || "-"  }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-600/40 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ent.ogrn`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ enterprise.ogrn || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ent.rns`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ enterprise.rns || "-" }}</dd>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-600/40 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ent.okvd`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ enterprise.okvd || "-"  }}</dd>
                    </div>
                    <div class="bg-white dark:bg-slate-700/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium">{{ $t(`inputs.ent.address`) }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ enterprise.address || "-" }}</dd>
                    </div>
                </dl>
                </div>

                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-300">Статистика</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-slate-600">
                <dl>
                    <template v-for="(item, index) in enterprise" :key="item">
                        <div class="bg-white dark:bg-slate-700/50 odd:bg-gray-50 dark:odd:bg-slate-600/40 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="!except.includes(index)" v-show="item">
                            <dt class="text-sm font-medium">{{ $t(`inputs.ent.${index}`) }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">{{ item || "-"  }}</dd>
                        </div>
                    </template>

                </dl>
                </div>

                <template v-if="enterprise.safety">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-300">Охрана труда и социальное партнерство</h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-slate-600">
                    <dl>
                        <template v-for="(item, index) in enterprise.safety" :key="item">
                            <div class="bg-white dark:bg-slate-700/50 odd:bg-gray-50 dark:odd:bg-slate-600/40 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="!except.includes(index)" v-show="item">
                                <dt class="text-sm font-medium">{{ $t(`inputs.safety.${index}`) }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-slate-300 sm:mt-0 sm:col-span-2">

                                    <template v-if="typeof isArray(item) === 'object'">
                                        <div class="flex" v-for="r in isArray(item)" :key="r">
                                            <div class="mr-4" v-for="(it, i) in r" :key="it">
                                                {{ $t(`inputs.safety.${i}`) }}: {{ it }}
                                            </div>
                                        </div>
                                        <div class="flex mt-3">
                                            <div class="mr-4">
                                                Последний случай: {{ enterprise.safety[index.replace('_list', '_at')] }}
                                            </div>
                                            Кол-во в сумме: {{ enterprise.safety[index.replace('_list', '')] }}
                                        </div>
                                    </template>
                                    <template v-else>
                                        {{ item || "-"  }}
                                    </template>

                                </dd>
                            </div>
                        </template>

                    </dl>
                    </div>
                </template>
            </div>
            </div>

    </app-layout>
</template>


<script>
    import { defineComponent } from 'vue';
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import AppLayout from '@/Layouts/AppLayout';
    import Breadcrumbs from '@/Shared/Breadcrumbs'

    export default defineComponent({
        components: {
            AppLayout,
            Head,
            Link,
            Breadcrumbs
        },
        props: {
            region: Array,
            enterprise: Object,
            access_region: Array
        },
        data() {
            return {
                bread: [
                    {
                        title: 'Районы',
                        url: route('regions.index'),
                    },
                    {
                        title: this.region.region,
                        url: route('regions.show', this.region.id),
                    },
                    {
                        title: 'Предприятия',
                        url: route('regions.enterprises.index', this.region.id),
                    },
                    {
                        title: 'Полная информация',
                        current: true,
                    },
                ],
                except: ['safety', 'inn', 'created_at', 'updated_at', 'status_id', 'status', 'id', 'enterprise_id','area_id', 'enterprises_id', 'address', 'name', 'ogrn', 'okvd', 'okvd_name', 'rns', 'accidents_group_at', 'accidents_group', 'accidents_heavy_at', 'accidents_heavy', 'accidents_deadly_at', 'accidents_deadly']
            }
        },
        methods:{
            isArray(e){
                if(e){
                    try {
                        let o = JSON.parse(e);
                        if (o && typeof o === "object") {
                            return o;
                        }
                    }
                    catch (error) { }

                    return e;
                }
            }
        }
    })
</script>
