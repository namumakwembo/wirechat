<div 
x-cloak
x-show="isRecording" 

x-data="{
  //  isRecording: false,
    isPaused: false,
    startTime: 0,
    pauseStart: 0,
    pauseOffset: 0,
    recordingTime: 0,
    mediaRecorder: null,
    isCancelling: false,
    mediaStream: null,
    audioChunks: [],
    audioUrl: null,
    recordingFile: null,
    audioContext: null,
    analyser: null,
    canvas: null,
    canvasContext: null,
    dataArray: null,
    animationFrame: null,
    barCount: 32,
    startRecording() {
        // If recording is already in progress and paused, resume it; otherwise, pause it
        if (isRecording) {
            if (this.isPaused) {
                return this.resumeRecording();
            } else {
                return this.pauseRecording();
            }
        }
    
        // Request access to the microphone
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then((stream) => {
                this.mediaStream = stream;
                this.mediaRecorder = new MediaRecorder(stream);
    
                this.audioChunks = [];
                this.mediaRecorder.ondataavailable = (event) => {
                    if (event.data && event.data.size > 0) {
                        this.audioChunks.push(event.data);
                    }
                };
    
                // Handle the stop event to convert .webm to .wav
                this.mediaRecorder.onstop = () => {
                    const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
    
                    // Convert the .webm blob to .wav format
                    const audioContext = new AudioContext();
                    const fileReader = new FileReader();
    
                    fileReader.onload = () => {
                        audioContext.decodeAudioData(fileReader.result, (buffer) => {
                            // Encode the audio data as WAV
                            const wavBlob = this.encodeWAV(buffer.getChannelData(0), buffer.sampleRate);
    
                            // Create a File object for the WAV audio
                            this.recordingFile = new File([wavBlob], 'recording.wav', { type: 'audio/wav' });
                            this.audioUrl = URL.createObjectURL(wavBlob);
    
                            // Only send if it's NOT being cancelled
                            if (!this.isCancelling) {
                                this.isCancelling = false;
                                this.sendRecording();
                            }
                        });
                    };
    
                    fileReader.readAsArrayBuffer(blob);
                };
    
                // Start recording
                this.mediaRecorder.start();
                isRecording = true;
                this.isPaused = false;
                this.startTime = Date.now();
                this.pauseOffset = 0;
    
                // Set up audio visualization
                this.audioContext = new AudioContext();
                this.analyser = this.audioContext.createAnalyser();
                this.analyser.fftSize = 256;
                const source = this.audioContext.createMediaStreamSource(stream);
                source.connect(this.analyser);
                this.dataArray = new Uint8Array(this.analyser.frequencyBinCount);
                this.canvas = document.getElementById('waveCanvas');
                this.canvasContext = this.canvas.getContext('2d');
                this.drawWaves();
            })
            .catch((error) => {
                alert('Could not access the microphone. Please check your permissions.');
            });
    },
    
    // Add the encodeWAV function to your component
    encodeWAV(samples, sampleRate) {
        const buffer = new ArrayBuffer(44 + samples.length * 2);
        const view = new DataView(buffer);
    
        // Write WAV header
        const writeString = (view, offset, string) => {
            for (let i = 0; i < string.length; i++) {
                view.setUint8(offset + i, string.charCodeAt(i));
            }
        };
    
        const floatTo16BitPCM = (output, offset, input) => {
            for (let i = 0; i < input.length; i++, offset += 2) {
                const s = Math.max(-1, Math.min(1, input[i]));
                output.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
            }
        };
    
        writeString(view, 0, 'RIFF'); // RIFF header
        view.setUint32(4, 36 + samples.length * 2, true); // File length
        writeString(view, 8, 'WAVE'); // WAVE header
        writeString(view, 12, 'fmt '); // fmt chunk
        view.setUint32(16, 16, true); // fmt chunk length
        view.setUint16(20, 1, true); // PCM format
        view.setUint16(22, 1, true); // Mono
        view.setUint32(24, sampleRate, true); // Sample rate
        view.setUint32(28, sampleRate * 2, true); // Byte rate
        view.setUint16(32, 2, true); // Block align
        view.setUint16(34, 16, true); // Bits per sample
        writeString(view, 36, 'data'); // data chunk
        view.setUint32(40, samples.length * 2, true); // data chunk length
    
        // Write audio samples
        floatTo16BitPCM(view, 44, samples);
    
        return new Blob([view], { type: 'audio/wav' });
    },
    pauseRecording() {
        if (this.mediaRecorder && isRecording && !this.isPaused) {
            this.mediaRecorder.pause();
            this.isPaused = true;
            this.pauseStart = Date.now();
        }
    },
    resumeRecording() {
        if (this.mediaRecorder && isRecording && this.isPaused) {
            this.mediaRecorder.resume();
            this.isPaused = false;
            this.pauseOffset += (Date.now() - this.pauseStart);
        }
    },
    drawWaves() {
        this.animationFrame = requestAnimationFrame(() => this.drawWaves());
        if (isRecording) {
            if (!this.isPaused) {
                this.recordingTime = Math.floor((Date.now() - this.startTime - this.pauseOffset) / 1000);
                this.analyser.getByteFrequencyData(this.dataArray);
            }
            const width = this.canvas.width;
            const height = this.canvas.height;
            this.canvasContext.clearRect(0, 0, width, height);
            const barWidth = (width / this.dataArray.length) - 1;
            let x = 0;
            for (let i = 0; i < this.dataArray.length; i++) {
                const barHeight = this.isPaused ? 0 : this.dataArray[i];
                const y = height - barHeight;
                this.canvasContext.fillStyle = '#4f46e5';
                this.canvasContext.fillRect(x, y, barWidth, barHeight);
                x += barWidth + 1;
            }
        }
    },
    stopRecording() {
        if (this.mediaRecorder && isRecording) {
            this.mediaRecorder.stop();
            if (this.mediaStream) {
                this.mediaStream.getTracks().forEach(track => track.stop());
                this.mediaStream = null;
            }
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
                this.animationFrame = null;
            }
            if (this.audioContext) {
                this.audioContext.close();
                this.audioContext = null;
            }
            isRecording = false;

        }
    },
    cancelRecording() {
        if (this.mediaRecorder && isRecording) {
            this.mediaRecorder.stop();
            if (this.mediaStream) {
                this.mediaStream.getTracks().forEach(track => track.stop());
                this.mediaStream = null;
            }
            if (this.animationFrame) {
                cancelAnimationFrame(this.animationFrame);
                this.animationFrame = null;
            }
            if (this.audioContext) {
                this.audioContext.close();
                this.audioContext = null;
            }
        }
        // Reset recording state but do not set recordingFile
        isRecording = false;
        this.isPaused = false;
        this.recordingTime = 0;
        this.audioChunks = [];
        this.audioUrl = null;
        this.recordingFile = null; // Ensures no submission
    },
    deleteRecording() {
        this.isCancelling = true; // Mark as canceling

        if (isRecording) {
            this.mediaRecorder.stop();
        }

        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
            this.mediaStream = null;
        }

        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
            this.animationFrame = null;
        }

        if (this.audioContext) {
            this.audioContext.close();
            this.audioContext = null;
        }

        if (this.audioUrl) {
            URL.revokeObjectURL(this.audioUrl);
            this.audioUrl = null;
        }

        // Reset states
        isRecording = false;
        this.isPaused = false;
        this.recordingTime = 0;
        this.audioChunks = [];
        this.recordingFile = null;

        // Reset isCancelling after cleanup
        setTimeout(() => this.isCancelling = false, 100);
    },

    sendRecording() {

        this.isCancelling = false;
        if (!this.recordingFile) {
            alert('No recording to send.');
            return;
        }
        @this.upload(
            'audioFile',
            this.recordingFile,
            () => {
                $wire.saveRecorded();

                this.deleteRecording();

            },
            (error) => {
                console.error('Upload error:', error);
            }
        );
    }
}"

x-on:start-recording.window="startRecording()"

class="w-full mx-auto flex items-center gap-4 p-5 dark:bg-gray-800 bg-gray-50 z-[50] ">

    <input hidden wire:model="audioFile" clas="hidden" type="file">

    <div class="flex justify-center items-center w-full  gap-4 ">


        {{-- Delete recording --}}
        <button x-cloak x-show="isRecording" @click="deleteRecording()" class="">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                class="size-6 w-7 h-7 dark:text-gray-400">
                <path fill-rule="evenodd"
                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        {{-- Canvas wave form  --}}
        <canvas x-cloak x-show="isRecording" id="waveCanvas" class="w-full h-2 text-[var(--primary-color)] dark:bg-gray-700/20"></canvas>


        {{-- time --}}
        <div  x-cloak class="flex text-gray-700 dark:text-gray-300  justify-center items-center space-x-2" x-show="isRecording">
            <span class="font-semibold sr-only">Recording time:</span>
            <span x-text="recordingTime" class=""></span>
            <span>sec</span>
        </div>



        {{-- remsume recording --}}
        <button x-cloak x-show="isPaused ||!isRecording" @click="startRecording()" :disabled="!isPaused && isRecording"
            class="text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor" class="size-6 w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z" />
            </svg>

        </button>

        {{-- Pause recording button --}}
        <button x-cloak @click="pauseRecording()" x-show="isRecording && !isPaused" :disabled="!isRecording || isPaused"
            class=" text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                stroke="currentColor" class="size-6 w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.25 9v6m-4.5 0V9M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </button>


        {{-- Stop recording and send button --}}
        <button x-cloak @click="stopRecording()" :disabled="!isRecording" x-show="isRecording" wire:loading.attr="disabled"
            type="button" class=" ml-auto disabled:cursor-progress font-bold">

            <svg class="w-7 h-7   dark:text-gray-200" xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round" class="ai ai-Send">
                <path
                    d="M9.912 12H4L2.023 4.135A.662.662 0 0 1 2 3.995c-.022-.721.772-1.221 1.46-.891L22 12 3.46 20.896c-.68.327-1.464-.159-1.46-.867a.66.66 0 0 1 .033-.186L3.5 15" />
            </svg>

        </button>
    </div>





</div>
