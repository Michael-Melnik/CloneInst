<script>
import ChatList from "@/Pages/Chat/ChatList.vue";
import {Link} from "@inertiajs/vue3";
import MenuItem from "@/Components/MenuItem.vue";
import Magnify from "vue-material-design-icons/Magnify.vue";
import HeartOutline from "vue-material-design-icons/HeartOutline.vue";
export default {
    name: "Index",
    props: [
        'conversations',
    ],
    components: {HeartOutline, Magnify, MenuItem, Link, ChatList }
}
</script>

<template>
    <div id="TopNavHome" class="fixed z-30 block w-full bg-white h-[61px] border-b border-b-gray-300">
        <div class="flex items-center justify-between h-full">
            <Link href="/">
                <img class="w-[105px] ml-6 cursor-pointer" src="/insta-logo.png">
            </Link>
        </div>
    </div>

    <div class=" fixed  h-full  flex bg-white border  lg:shadow-sm overflow-hidden inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">
<!--        //////////-->



<!--        ///////-->



        <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border" >

            <ChatList :conversations="conversations"/>
        </div>

        <div class="hidden md:grid   w-full border-l h-full relative overflow-y-auto">

            <div class="m-auto text-center justify-center flex flex-col gap-3">

                <h4 class="font-medium text-lg"> Choose a conversation to start chatting </h4>

            </div>

        </div>

    </div>
</template>

<style scoped>

</style>
