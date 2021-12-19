<template>
    <!-- <svg class="circle-chart" viewBox="0 0 33.83098862 33.83098862" width="140" height="140" xmlns="http://www.w3.org/2000/svg">

    <circle class="circle-chart-background" stroke="#cccccc" stroke-width="0.35" stroke-dasharray="100" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" />
    <circle class="circle-chart-circle" stroke="#4f5357" stroke-width="0.5" stroke-dashoffset="209" stroke-dasharray="315" stroke-mitterlimit="0" stroke-linecap="round" fill="none" cx="16.91549431" cy="16.91549431" r="15.91549431" transform="rotate(-90 ) translate(-100 0)"/>
    <g class="circle-chart-info">
        <text class="circle-chart-percent" x="16.91549431" y="16.5" alignment-baseline="central" text-anchor="middle" font-size="8">{{computedText}}</text>
    </g>
</svg> -->

<svg class="circle-chart" width="100" height="100" viewBox="0 0 100 100">
    <circle class="circle-chart-background" stroke="#cccccc" stroke-width="0.5" stroke-dasharray="315" fill="none" cx="50" cy="50" r="48" />
    <circle class="circle-chart-circle" stroke-linecap="round" cx="50" cy="50" r="48" stroke="#D04EC7" stroke-width="1" fill="none" stroke-dasharray="315"
    :stroke-dashoffset="'calc(315 - (315 * '+this.progress+') / 100)'" stroke-mitterlimit="0" transform="rotate(-90 ) translate(-100 0)" />
    <g class="circle-chart-info">
        <text class="circle-chart-percent" x="50" y="50" alignment-baseline="central" text-anchor="middle" font-size="18">{{computedText}}</text>
    </g>

</svg>
</template>

<script>
import { defineComponent } from 'vue'

    export default defineComponent({
        props: ['progress'],
        computed: {
            computedText: function () {
                let description = ''
                let progress = this.progress < 0 ? 0 : (this.progress > 100 ? 100 : this.progress)
                if (progress === '100') {
                    description = 'full';
                }
                else {
                    description = progress + '%';
                }
                return description
            }
        }
    })
</script>
<style lang="css">
    .circle-chart-circle {
        animation: circle-chart-fill 2s reverse;
        transform: rotate(-90deg);
        transform-origin: center;
    }
    .circle-chart-info {
        animation: circle-chart-appear 2s forwards;
        opacity: 0;
        transform: translateY(0.3em);
    }
    @keyframes circle-chart-fill {
        to {
            /* stroke-dasharray: 0 100;
            stroke-dashoffset: 0 315 */
        }
    }
    @keyframes circle-chart-appear {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
