<script  setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {Link, usePage} from "@inertiajs/vue3";
import Avatar from "@/Components/Chat/Avatar.vue";
import SidebarItem from "@/Components/Chat/SidebarItem.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    conversations: {
        required: true,
    }
})


const chatList = ref([...props.conversations])


onMounted(() => {
    updateChats();
    newChat();
});

function  updateChats() {
            chatList.value.forEach((chat) => {
                window.Echo.channel(`chats.${chat.id}`)
                    .listen('.chat-update', (res) => {
                        console.log(res.chat)
                       const UpdateChat =  res.chat;
                       const index =  chatList.value.findIndex(c => c.id === UpdateChat.id);
                        if (index !== -1) {
                            chatList.value[index].last_message = UpdateChat.last_message;
                            chatList.value[index].unread_message_count = UpdateChat.unread_message_count;
                            chatList.value = [
                                chatList.value[index],
                                ...chatList.value.filter(c => c.id !== UpdateChat.id)
                            ];
                        }
                    })
            })

        }

function newChat() {
    window.Echo.channel(`private-App.Models.User.${usePage().props.auth.user.id}`)
        .listen('.new.chat', (e) => {
            chatList.value.push(e.chat)
        });
}



// export default {
//     name: "ChatList",
//     components: {DropdownLink, Dropdown, Link, Avatar},
//     props: [
//         'conversations'
//     ],
//     created() {
//
//         // window.Echo.channel(`tetwrew`)
//         //     .listen('.private-conversation', (res) => {
//         //         // this.messages.push(res)
//         //
//         //     });
//         // window.Echo.channel(`private-App.Models.User.${this.$page.props.auth.user.id}`)
//         //     .listen('.new.chat', (res) => {
//         //         this.conversations.push(res.chat);
//         //         console.log(res.chat)
//         //     });
//         // window.Echo.channel('chats.10')
//         //     .listen('.private-conversation', (res) => {
//         //         // this.updateChat(res.chat.id)
//         //     });
//     },
//     mounted() {
//         // this.createChat();
//         this.updateChat()
//         // this.newChat();
//     },
//     methods: {
//         showChat(id) {
//             route.get(`chat/${id}`)
//         },
//         createChat() {
//
//         },
//         updateChat() {
//             // console.log(this.conversations);
//             this.conversations.forEach((conversation) => {
//
//                 window.Echo.channel(`chats.${conversation.id}`)
//                     .listen('.chat-update', (res) => {
//                        const UpdateChat =  res.chat;
//
//                         const index =  this.conversations.findIndex(c => c.id === UpdateChat.id)
//                         // console.log(index);
//                         // console.log(this.conversations[index]);
//                         if (index !== -1) {
//                             this.conversations[index] = UpdateChat;
//                             // console.log(this.conversations);
//                         //     //
//                         //     // // // Move updated chat to the top
//                             let arr = [ this.conversations[index],
//                                 ...this.conversations.filter(c => c.id !== UpdateChat.id)]
//                             // this.conversations = [
//                             //     this.conversations[index],
//                             //     ...this.conversations.filter(c => c.id !== UpdateChat.id)
//                             // ];
//                             console.log('новый')
//                             console.log(arr);
//                             this.conversations = arr
//                             console.log('полученынй')
//                             console.log(this.conversations);
//                             //     // console.log(this.conversation);
//                         //
//                         }
//                     })
//             })
//
//         }
//     },
//     computed: {
//         NotEmptyChat() {
//             return this.conversations
//             let user_id = this.$page.props.auth.user.id;
//             return this.conversations.filter(function (conv) {
//                 // console.log({user: user_id, conv: conv.user.id})
//                 return conv.receiver_id !== user_id
//                     || conv.last_message !== null;
//                 // return conv;
//             })
//             // return this.conversations.filter((conversation) => conversation.last_message == null && conversation.user_id !== this.$page.props.auth.user.id);
//         }
//     },
// }
</script>

<template>
    <div class="flex flex-col transition-all h-full overflow-hidden">

        <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">

            <div class="border-b justify-between flex items-center pb-2">

                <div class="flex items-center gap-2">
                    <h5 class="font-extrabold text-2xl">Chats</h5>
                </div>

                <button>

                    <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                         viewBox="0 0 16 16">
                        <path
                            d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"/>
                    </svg>

                </button>

            </div>



            <div class="flex gap-3 items-center overflow-x-scroll p-2 bg-white">

                <button @click="type='all'" :class="{'bg-blue-100 border-0 text-black':type=='all'}"
                        class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border ">
                    All
                </button>

            </div>

        </header>


        <main class=" overflow-y-scroll overflow-hidden grow  h-full relative " style="contain:content">

            <!--            {{-- chatlist  --}}-->

            <ul class="p-2 grid w-full spacey-y-2">
                <li v-for:="conversation in chatList"
                    id="conversation-{{conversation.id}}"
                    class="py-3 hover:bg-gray-50 rounded-2xl dark:hover:bg-gray-700/70 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2 ">


                    <a href="#" class="shrink-0">

                        <Avatar :src="conversation.user.file"/>

                    </a>

                    <aside class="grid grid-cols-12 w-full">



                        <Link :href="route('chat.show', conversation.id)"  class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">

                            <!--  User name and date-->

                            <div class="flex justify-between w-full items-center">
                                <h6 class="truncate font-medium tracking-wider text-gray-900">
                                    {{ conversation.user.name }}
                                </h6>
                                <small v-if="conversation.last_message" class="text-gray-700">
                                    {{conversation.last_message.date}}
                                </small>
                            </div>

                            <div class="flex gap-x-2 items-center">
                                <p v-if="conversation.last_message" class="grow truncate text-sm font-[100]">
                                    {{ conversation.last_message.body }}
                                </p>


                                <span v-if="conversation.unread_message_count > 0" class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-blue-500 text-white">
                                 {{ conversation.unread_message_count }}
                                </span>
                            </div>


                        </Link>

                    </aside>

                </li>
            </ul>

        </main>
    </div>

</template>

<style scoped>

</style>
