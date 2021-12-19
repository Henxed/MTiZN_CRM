<template>
<div class="h-screen md:flex" id="fonregistr" >
	<div
		class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-blue-800 to-purple-700 i justify-around items-center hidden">
		<div>
			<h1 class="text-white font-bold text-4xl font-sans">CRM система</h1>
			<p class="text-white mt-1">Цифровая система анализа и статистики МТиЗН</p>
			<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" target="_blank" class="block w-28 bg-white text-center text-indigo-800 mt-4 py-2 rounded-lg font-bold mb-2">Подробнее</a>
		</div>
		<div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
		<div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
	</div>
	<div class="flex md:w-1/2 justify-center py-10 items-center bg-white">
		<slot />
	</div>
</div>

</template>
