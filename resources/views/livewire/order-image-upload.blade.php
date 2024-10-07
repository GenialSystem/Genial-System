<div>
    <!-- Normal Image Upload Section -->
    <div class="mb-5" x-data>
        <h3>Upload Normal Images</h3>

        <!-- Drag and Drop Area for Normal Images -->
        <div class="border border-dashed p-5" @click="$refs.normalInput.click()">
            <p>Drag and drop or click to upload images</p>
            <!-- File Input -->
            <input type="file" wire:model="normalImages" multiple hidden x-ref="normalInput" />
        </div>

        <!-- Show Validation Error for Normal Images -->
        @error('normalImages')
            <span class="text-red-500">{{ $message }}</span>
        @enderror

        <!-- Show Preview/Uploaded Images -->
        @if ($normalImages)
            <div class="mt-2">
                @foreach ($normalImages as $image)
                    <div>
                        <p>{{ $image->getClientOriginalName() }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Upload Progress -->
            <div wire:loading wire:target="normalImages">
                Uploading...
            </div>
        @endif
    </div>

    <!-- Disassembly Image Upload Section -->
    <div x-data>
        <h3>Upload Disassembly Images</h3>

        <!-- Drag and Drop Area for Disassembly Images -->
        <div class="border border-dashed p-5" @click="$refs.disassemblyInput.click()">
            <p>Drag and drop or click to upload images</p>
            <!-- File Input -->
            <input type="file" wire:model="disassemblyImages" multiple hidden x-ref="disassemblyInput" />
        </div>

        <!-- Show Validation Error for Disassembly Images -->
        @error('disassemblyImages')
            <span class="text-red-500">{{ $message }}</span>
        @enderror

        <!-- Show Preview/Uploaded Images -->
        @if ($disassemblyImages)
            <div class="mt-2">
                @foreach ($disassemblyImages as $image)
                    <div>
                        <p>{{ $image->getClientOriginalName() }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Upload Progress -->
            <div wire:loading wire:target="disassemblyImages">
                Uploading...
            </div>
        @endif
    </div>
</div>
