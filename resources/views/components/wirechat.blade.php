  <div 
    x-data="{
        selectedConversationId:null,
    }"
    {{ $attributes->merge(['class' => 'w-full h-[calc(100vh_-_10.0rem)]  border dark:border-gray-700 flex overflow-hidden rounded-xl']) }}>
      <div class="relative  w-full h-full   md:w-[360px] lg:w-[400px] xl:w-[450px] shrink-0 overflow-y-auto  ">
          <livewire:chats :isWidget="true" />
      </div>
      <main
          x-on:chat-selected.window="
          this.selectedConversationId= $event.detail.conversation;

          "
          class=" hidden md:grid  bg-white dark:bg-gray-900 w-full   dark:border-gray-700 h-full relative overflow-y-auto"
          style="contain:content">

          
      </main>
  </div>
