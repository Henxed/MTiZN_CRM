<template>
    <div :class="style + animation"
        @click="click"
        @mouseover="toggleTimer(true)"
        @mouseleave="toggleTimer(false)"
        role="alert">

         <div class="p-4">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <div v-if="type === 'success'" class="border-2 border-green-200 dark:border-green-800 rounded-full p-1 w-8 h-8 text-center">
                <i class="fi fi-rr-check text-green-400 dark:text-green-600"></i>
              </div>
              <div v-if="type === 'info'" class="border-2 border-blue-200 dark:border-blue-800 rounded-full p-1 w-8 h-8 text-center">
                <div class="text-blue-400 dark:text-blue-600 text-lg -mt-1">i</div>
              </div>
              <div v-if="type === 'warning'" class="border-2 border-yellow-200 dark:border-yellow-800 rounded-full p-1 w-8 h-8 text-center">
                <div class="text-yellow-400 dark:text-yellow-600 text-lg -mt-1">!</div>
              </div>
              <div v-if="type === 'error'" class="border-2 border-red-200 dark:border-red-800 rounded-full p-1 w-8 h-8 text-center">
                <div class="text-red-400 dark:text-red-600 text-lg -mt-1">!</div>
              </div>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p v-if="title" class="text-sm leading-5 font-bold dark:text-slate-200">{{ title }}</p>
                <p class="text-sm leading-5 dark:text-slate-300" :class="{'mt-1': !title}" >{{ message }}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
              <button class="inline-flex text-gray-500 text-xs transition ease-in-out duration-150 focus:outline-none hover:text-gray-600" @click="close">
                <i class="fi fi-rr-cross"></i>
              </button>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
import Timer from "./timer";
import events from "./events";
import elements from "./elements";

export default {
  name: "toast",
  props: {
    title: {
      type: [Boolean, String],
      required: false,
      default: false,
    },
    message: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: "default",
    },
    delay: {
      type: Number,
      default: 5000,
    },
    maxToasts: {
      type: [Number, Boolean],
      default: 5,
    },
  },
  data: () => ({
    parent: null,
    timer: null,
    queueTimer: null,
    transition: "toast-in",
    timeLeft: false,
  }),
  computed: {
    timeLeftPercent () {
      return Math.round(((this.timeLeft * 100 / (this.timeout * 1000)) * 100) / 100)
    },
    progressStyle () {
      return `width: ${this.timeLeftPercent}%; transition: width 0.1s linear;`
    },
    animation() {
      return " animation-" + this.transition;
    },
    style() {
      return "self-end bg-white dark:bg-slate-700 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto relative mb-4 overflow-hidden";
    },
  },
  beforeMount() {
    this.createParent();
    this.setupContainer();
  },
  mounted() {
    this.notify();
    events.$on("toast-clean", this.close);
  },
  methods: {
    createParent() {
      this.parent = document.getElementById("_toast_container");
      if (!this.parent) {
        this.parent = document.createElement("div");
        this.parent.id = "_toast_container";
        this.parent.className =
          "fixed flex flex-col-reverse w-full h-full inset-0 p-10 z-50 pointer-events-none overflow-hidden";
      }
    },
    setupContainer() {
      const container = document.body;
      container.appendChild(this.parent);
    },
    shouldNotify() {
      if (this.maxToasts !== false) {
        return this.maxToasts <= this.parent.childElementCount;
      }

      return !this.maxToasts;
    },
    notify() {
      if (this.shouldNotify()) {
        this.queueTimer = setTimeout(this.notify, 250);
        return;
      }

      this.parent.insertAdjacentElement("afterbegin", this.$el);
      this.timer = new Timer(this.close, this.delay);
    },
    click() {
      this.close();
    },
    toggleTimer(state) {
      if (this.timer) {
        state ? this.timer.pause() : this.timer.resume();
      }
    },
    close() {
      this.timer && this.timer.stop();
      clearTimeout(this.queueTimer);
      this.transition = "toast-out";

      setTimeout(() => {
        elements.removeElement(this.$el);
      }, 250);
    },
  },
  beforeUnmount() {
    events.$off("toast-clear", this.close);
  },
};
</script>

<style>
.animation-toast-in {
  -webkit-animation: slide-top 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)
    forwards;
  animation: slide-top 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}

.animation-toast-out {
  -webkit-animation: fade-out 0.2s ease-out both;
  animation: fade-out 0.2s ease-out both;
}

@-webkit-keyframes slide-top {
  0% {
    -webkit-transform: translateY(60px);
    transform: translateY(60px);
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
}
@keyframes slide-top {
  0% {
    -webkit-transform: translateY(60px);
    transform: translateY(60px);
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
  }
}

@-webkit-keyframes fade-out {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
@keyframes fade-out {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}
</style>
