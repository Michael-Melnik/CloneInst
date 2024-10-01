<script>
export default {
    name: "Avatar",
    props: {
        src: String
    }
}
</script>

<template>
    <img v-if="src" class = "rounded-full object-fit md:w-[60px] w-[30px] cursor-pointer"
         :src="src"
    />
<!--    inline-block size-9 rounded-full-->
    <svg v-else

        class="inline-block size-9 rounded-full text-gray-300 bg-gray-100 dark:bg-gray-600"
        fill="currentColor"
        viewBox="0 0 24 24">
        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</template>

<style scoped>

</style>
