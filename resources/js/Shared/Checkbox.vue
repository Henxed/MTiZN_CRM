<template>

        <label class="select-none flex items-center mr-4 mb-4">
            <input type="checkbox" :value="value" v-model="proxyChecked" class="opacity-0 absolute h-6 w-6" />
            <div class=" border-2 rounded-md border-yellow-400 w-6 h-6 flex flex-shrink-0 justify-center items-center mr-2 focus-within:border-yellow-500">
                <svg class="fill-current hidden w-3 h-3 text-yellow-600 pointer-events-none" version="1.1" viewBox="0 0 17 12" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <g transform="translate(-9 -11)" fill="#fbbf24" fill-rule="nonzero">
                            <path d="m25.576 11.414c0.56558 0.55188 0.56558 1.4439 0 1.9961l-9.404 9.176c-0.28213 0.27529-0.65247 0.41385-1.0228 0.41385-0.37034 0-0.74068-0.13855-1.0228-0.41385l-4.7019-4.588c-0.56584-0.55188-0.56584-1.4442 0-1.9961 0.56558-0.55214 1.4798-0.55214 2.0456 0l3.679 3.5899 8.3812-8.1779c0.56558-0.55214 1.4798-0.55214 2.0456 0z" />
                        </g>
                    </g>
                </svg>
            </div>
            {{label}} <span class="text-gray-400 ml-1" v-if="price">— ${{ price }}</span>
        </label>


</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    emits: ['update:checked'],

    props: {
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            default: null,
        },
        price: String,
        label: String,
    },

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
})
</script>
