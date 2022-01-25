<template>
<div class="h-screen md:flex" id="fonregistr" >
	<div class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-blue-800 to-purple-700 i justify-around items-center hidden">
		<div>
			<h1 class="text-white font-bold text-4xl font-sans"> Информационно аналитическая система</h1>
			<p class="text-white mt-1">Министерства труда и занятости населения Оренбургской области</p>
			<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" class="block w-28 bg-white text-center text-indigo-800 mt-4 py-2 rounded-lg font-bold mb-2">Подробнее</a>
		</div>
		<div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
	</div>
    <div class="text-xl my-4 mx-2 text-slate-800 dark:text-slate-300 font-bold leading-none sm:hidden block text-center">
        Информационно аналитическая система
        <div class="text-sm font-normal sm:block text-slate-500">Министерства труда и занятости населения Оренбургской области</div>
    </div>
	<div class="flex md:w-1/2 justify-center py-10 items-center bg-white m-4 rounded-md shadow-md sm:m-0">

		<div class="">

            <slot />

        </div>
	</div>
</div>

</template>
