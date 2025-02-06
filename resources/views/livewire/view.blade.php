<div class="w-full h-[calc(100vh_-_0.0rem)]  flex rounded-lg" >
    <div class=" hidden md:grid bg-inherit  dark:bg-inherit  relative w-full h-full md:w-[360px] lg:w-[400px] xl:w-[500px]  shrink-0 overflow-y-auto  ">
       <livewire:chats/> 
    </div>
    
    <main  class="  grid  w-full  grow  h-full relative overflow-y-auto"  style="contain:content">
      <livewire:chat  conversation="{{$this->conversation->id}}"/>
    </main>

</div>