<div class="w-full flex min-h-full h-full rounded-lg" >
    <div class=" hidden md:grid bg-inherit  dark:bg-inherit  relative w-full h-full md:w-[360px] lg:w-[400px] xl:w-[500px]  shrink-0 overflow-y-auto  ">
       <livewire:chats/> 
    </div>
    
    <main  class="  grid  w-full  grow  h-full min-h-min relative overflow-y-auto"  style="contain:content">
      <livewire:chat  conversation="{{$this->conversation->id}}"/>
    </main>

</div>