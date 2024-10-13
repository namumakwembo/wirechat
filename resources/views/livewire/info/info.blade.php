<div 
x-data
x-init="console.log('here')"
class="space-y-4">

    {{-- Details --}}
    <header class="">
        <div class="flex  flex-col items-center gap-5">

            <x-wirechat::avatar group="{{$conversation->isGroup()}}" src="{{ $group ? $group?->cover_url : $receiver?->cover_url ?? null }}"   class="h-18 w-18 lg:w-24 lg:h-24" />

            <div class="space-y-3 grid ">
             

                @if ($group)
                {{-- Form to update Group name  --}}
                <form wire:submit="updateGroupName" x-data="{editing:false }" class="grid grid-cols-12 items-center">
                    @csrf

                    {{-- Left side input --}}
                    <span class="col-span-11">
                        <div x-show="!editing">
                         
                            <h5 class="font-medium text-2xl">{{$groupName}} </h5>

                        </div>
        
                       <input x-cloak maxlength="130" x-show="editing" id='groupName' type="text" wire:model='groupName'  
                                 class="resize-none text-2xl font-medium w-full border-0 px-0 py-0 py-0 border-b dark:border-gray-700  bg-inherit dark:text-white outline-none w-full focus:outline-none  focus:ring-0 hover:ring-0">

        
                       @error('groupName')
                       <p class="text-red-500">{{$message}}</p>
                       @enderror
                    </span>
        
                    {{-- Right Side --}}
                    <span class="col-span-1  items-center">
        
                        <button type="button" @click="editing=true" x-show="!editing">
                            {{-- pencil/edit --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6  w-5 h-5">
                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                              </svg>
                              
                        </button>
        
                        <button x-cloak @click="editing=false" x-show="editing">
                            {{-- check/submit --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg w-5 h-5" viewBox="0 0 16 16">
                                <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                              </svg>
                        </button>
        
                    </span>
        
                </form>

                {{-- Members count --}}
                <p class="mx-auto"> Members {{$conversation->participants->count()}} </p>  

                @else
                {{-- Receiver --}}
                <h5 class="text-2xl">{{$receiver?->display_name }}</h5>
                @endif
            </div>


        </div>

    </header>


    {{-- About --}}
    <section class=" px-8 py-5 ">

        @if ($conversation->isGroup())
        <div x-data="{editing:false }" @click.outside="editing=false" class="grid grid-cols-12 items-center">

            {{-- Left side input --}}
            <span class="col-span-11">
                <div x-show="!editing">
                    @if (empty($description))
                    <p class="text-sm" style="color: var(--primary-color)">Add a group description</p>
                        
                    @else
                    <p class="font-medium">{{$description}} </p>
                        
                    @endif
                </div>

               <textarea x-cloak maxlength="501" x-show="editing" id='description' type="text" wire:model.blur='description'  
                         class="resize-none font-medium w-full border-0 px-0 py-0 py-0 border-b dark:border-gray-700  bg-inherit dark:text-white outline-none w-full focus:outline-none  focus:ring-0 hover:ring-0">
               </textarea>

               @error('description')
               <p class="text-red-500">{{$message}}</p>
               @enderror
            </span>

            {{-- Right Side --}}
            <span class="col-span-1 flex items-center justify-end">

                <button  @click="editing=true" x-show="!editing">
                    {{-- pencil/edit --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6  w-5 h-5">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                      </svg>
                      
                </button>

                <button x-cloak @click="editing=false" x-show="editing">
                    {{-- check --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg w-5 h-5" viewBox="0 0 16 16">
                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                      </svg>
                </button>

            


            </span>

        </div>
        @endif

    </section>

    <x-wirechat::divider/>
    {{-- Members --}}
    @if ($conversation->isGroup())
        
    <section>
        <button class=" w-full py-5 px-8 hover:bg-gray-200 transition dark:hover:bg-gray-800 flex gap-3 items-center">
          
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 w-5 h-5">
                <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
              </svg>
              
              
            <span>Add Member</span>
        </button>
    </section>

    <x-wirechat::divider/>
    @endif 

    {{-- Footer section --}}
    <section class="flex flex-col justify-start w-full h-[900px]">

        @if ($conversation->isGroup())
        <button wire:confirm="Are you sure you want to exit Group ?" wire:click="exitGroup"  class=" w-full py-5 px-8 hover:bg-gray-200 transition dark:hover:bg-gray-700 flex gap-3 items-center text-red-500">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right w-5 h-5" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
              </svg>
            <span>Exit Group</span>
        </button>
        @endif


        <button wire:confirm="Are you sure you want to delete Chat ?" wire:click="deleteChat"   class=" w-full py-5 px-8 hover:bg-gray-200 transition dark:hover:bg-gray-700 flex gap-3 items-center text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>
              
            <span>Delete Chat</span>
        </button>

    </section>
</div>