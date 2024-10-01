<script>
import ChatList from "@/Pages/Chat/ChatList.vue";
import Avatar from "@/Components/Chat/Avatar.vue";
import {Link} from "@inertiajs/vue3";

export default {
    name: "Show",
    props: [
        'conversations',
        'messages',
        'user',
        'conversation_id'
    ],
    created() {
        window.Echo.channel(`ws-chat.${this.conversation_id}`)
            .listen('.private-conversation', (res) => {
                // this.messages.push(res)

                this.messages.push(res.message);
                this.body = '';
                // console.log(res)
            });
    },
    data() {
        return {
            body: '',
        }

    },
    methods: {
        sendMessage() {
            axios.post(`/message/send/${this.conversation_id}`,
                {
                    body: this.body,
                    sender_id: this.$page.props.auth.user.id,
                    receiver_id: this.user.id
                }
            )
        }
    },
    components: {ChatList, Avatar, Link}
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

    <div
        class=" fixed  h-full  flex bg-white border  lg:shadow-sm overflow-hidden inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg">

        <div class="relative w-full md:w-[320px] xl:w-[400px] overflow-y-auto shrink-0 h-full border">
            <ChatList :conversations="conversations"/>
        </div>
        <div class="grid  w-full border-l h-full relative overflow-y-auto">
            <div class="border-b flex flex-col overflow-y-scroll grow h-full">
                <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b ">

                    <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">
                        <Link class="shrink-0" :href="route('chat.index')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/>
                            </svg>
                        </Link>


                        <div class="shrink-0">
                            <Avatar :src="user.file"/>
                            <a class="shrink-0 lg:hidden" href="#">


                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75"/>
                                </svg>


                            </a>
                        </div>


                        <h6 class="font-bold truncate"> {{ user.email }} </h6>


                    </div>


                </header>


                <main id="conversation"
                      class="flex flex-col gap-3 p-2.5 overflow-y-auto  flex-grow overscroll-contain overflow-x-hidden w-full my-auto">

                    <div v-for:="message in messages"
                         class="max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2"
                         :class="[message.sender_id === $page.props.auth.user.id ?  'ml-auto' : '']">
                        <div class="shrink-0" :class="[message.sender_id === $page.props.auth.user.id ? 'hidden' : '']">
                            <Avatar :src="user.file"/>

                        </div>

                        <!--                            Message body -->
                        <div
                            class="flex flex-wrap text-[15px]  rounded-xl p-2.5 flex flex-col text-black bg-[#f6f6f8fb]"
                            :class="[message.sender_id === $page.props.auth.user.id ?  'rounded-bl-none border  border-gray-200/40 ' : 'rounded-br-none bg-blue-500/80 text-white']">


                            <p class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
                                {{ message.body }}
                            </p>

                            <div class="ml-auto flex gap-2">

                                <p class="text-xs"
                                   :class="[message.sender_id === $page.props.auth.user.id ? 'text-gray-500' : 'text-white']">
                                    {{ message.date }}

                                </p>


                            </div>
                        </div>
                    </div>

                </main>


                <footer class="shrink-0 z-10 bg-white inset-x-0">

                    <div class=" p-2 border-t">

                        <form @submit.prevent="sendMessage" class=" justify-between">
                            <input type="hidden" autocomplete="false" style="display:none">

                            <div class="grid grid-cols-12 ">
                                <input
                                    type="text"
                                    v-model="body"
                                    autocomplete="off"
                                    autofocus
                                    placeholder="write your message here"
                                    maxlength="1700"
                                    class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg  focus:outline-none"
                                >

                                <button class="col-span-2 bg-sky-500 rounded-lg" type='submit'>Send</button>

                            </div>

                        </form>
                    </div>


                </footer>
            </div>

        </div>

    </div>
</template>

<style scoped>

</style>
