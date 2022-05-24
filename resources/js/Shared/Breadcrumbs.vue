<template>
    <nav v-if="breadcrumbs" class="bg-grey-light rounded-md w-full">
        <ol class="list-reset flex">
            <li v-for="page in breadcrumbs" :key="page">
                <div>
                    <span v-if="page ==='>'" class="text-slate-500 dark:text-slate-400 mx-2">></span>
                    <a class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300"
                        v-else-if="page.url"
                        :href="page.url"
                    >{{ page.title }}</a>
                    <div
                        v-else
                        :href="page.url"
                        :class="{ 'text-slate-400 dark:text-slate-500': page.current }"
                    >{{ page.title }}</div>
                </div>
            </li>
        </ol>
    </nav>
</template>

<script>

export default {
    props: ['data'],
    computed:{
        breadcrumbs() {
            console.log(this.data);
            return this.insertBetween(this.data || [], '>')
        }
    },
    methods: {
        insertBetween: function(items, insertion) {
            return items.flatMap(
                (value, index, array) =>
                    array.length - 1 !== index
                        ? [value, insertion]
                        : value,
            )
        }
    },
}
</script>
