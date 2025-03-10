@extends('layouts.app')

@section('title', 'Edit Article')

@section('content')
    <form id="articleForm" method="POST" action="{{ route('articles.update', $article) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Article Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Article Title</label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" placeholder="Enter article title..." value="{{ old('title', $article->title) }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                    <input type="text" name="author" id="author" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" value="{{ old('author', $article->author) }}">
                    @error('author')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" id="category" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category', $article->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publication Date</label>
                    <input type="date" name="date_created" id="date_created" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" value="{{ old('date_created', $article->date_published->format('Y-m-d')) }}">
                    @error('date_created')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Article Position</label>
                    <select name="position" id="position" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Select Position</option>
                        <option value="news_list" {{ old('position', $article->position) == 'news_list' ? 'selected' : '' }}>News List</option>
                        <option value="sub_headline" {{ old('position', $article->position) == 'sub_headline' ? 'selected' : '' }}>Sub Headline</option>
                        <option value="headline" {{ old('position', $article->position) == 'headline' ? 'selected' : '' }}>Headline</option>
                    </select>
                    @error('position')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Editor Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Toolbar -->
            <div class="flex flex-wrap items-center gap-2 p-3 bg-gray-50 border rounded-lg mb-4">
                <div class="flex items-center gap-1">
                    <button type="button" id="bold-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-bold"></i>
                    </button>
                    <button type="button" id="italic-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-italic"></i>
                    </button>
                    <button type="button" id="underline-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-underline"></i>
                    </button>
                </div>

                <div class="w-px h-6 bg-gray-300"></div>

                <div class="flex items-center gap-1">
                    <button type="button" id="bullet-list-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-list-ul"></i>
                    </button>
                    <button type="button" id="ordered-list-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-list-ol"></i>
                    </button>
                </div>

                <div class="w-px h-6 bg-gray-300"></div>

                <div class="flex items-center gap-1">
                    <button type="button" id="image-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-image"></i>
                    </button>
                    <button type="button" id="link-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-link"></i>
                    </button>
                    <button type="button" id="read-mode-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-book-reader"></i>
                    </button>
                </div>

                <div class="w-px h-6 bg-gray-300"></div>

                <div class="flex items-center gap-2">
                    <button type="button" id="text-color-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-palette"></i>
                    </button>
                    <select id="font-size-select" class="px-3 py-2 border rounded-md bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="normal">Normal</option>
                        <option value="h1">Heading 1</option>
                        <option value="h2">Heading 2</option>
                        <option value="h3">Heading 3</option>
                    </select>
                </div>

                <div class="ml-auto flex items-center gap-2">
                    <button type="button" id="read-also-button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors text-sm font-medium">
                        <i class="fas fa-book mr-2"></i>Read Also
                    </button>
                    <button type="button" id="quote-from-button" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors text-sm font-medium">
                        <i class="fas fa-quote-right mr-2"></i>Quote
                    </button>
                </div>
            </div>

            <!-- Editor -->
            <div id="editor" class="min-h-[600px] max-h-[800px] overflow-auto border rounded-lg mb-6 p-4"></div>
            <input type="hidden" name="content" id="hiddenContent">
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- Current Image -->
            @if($article->image)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="max-w-xs rounded-lg shadow-md">
                    </div>
                </div>
            @endif

            <!-- Image Upload -->
            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors group">
                    <input type="file" id="image" name="image" accept="image/*" class="hidden">
                    <label for="image" class="cursor-pointer block">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-blue-500 transition-colors mb-3"></i>
                            <p class="text-gray-700 font-medium">Klik untuk mengunggah gambar baru (opsional)</p>
                            <p class="text-sm text-gray-500 mt-1">Ukuran file maksimal: 2MB</p>
                        </div>
                    </label>
                    <div id="imagePreview" class="mt-6 hidden">
                        <img src="" alt="Preview" class="max-w-xs mx-auto rounded-lg shadow-md">
                    </div>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <input type="text" name="figcaption" id="figcaption" placeholder="Keterangan gambar"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150" 
                    value="{{ old('figcaption', $article->image_caption) }}">
                @error('figcaption')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('articles.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Update Article
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Quill editor
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: false // We're using our custom toolbar
            }
        });

        // Set initial content 
        quill.root.innerHTML = {!! json_encode($article->content) !!};

        // Custom toolbar functionality
        $('#bold-button').click(() => {
            quill.format('bold', !quill.getFormat().bold);
        });
        
        $('#italic-button').click(() => {
            quill.format('italic', !quill.getFormat().italic);
        });
        
        $('#underline-button').click(() => {
            quill.format('underline', !quill.getFormat().underline);
        });
        
        $('#bullet-list-button').click(() => {
            quill.format('list', 'bullet');
        });
        
        $('#ordered-list-button').click(() => {
            quill.format('list', 'ordered');
        });
        
        $('#image-button').click(() => {
            const url = prompt('Enter image URL:');
            if (url) {
                quill.insertEmbed(quill.getSelection().index, 'image', url);
            }
        });
        
        $('#link-button').click(() => {
            const url = prompt('Enter link URL:');
            if (url) {
                const range = quill.getSelection();
                if (range) {
                    quill.format('link', url);
                }
            }
        });
        
        $('#font-size-select').change(function() {
            const value = $(this).val();
            
            // Remove all headers first
            quill.format('header', false);
            
            if (value === 'h1') {
                quill.format('header', 1);
            } else if (value === 'h2') {
                quill.format('header', 2);
            } else if (value === 'h3') {
                quill.format('header', 3);
            }
        });
        
        $('#read-also-button').click(() => {
            const selection = quill.getSelection();
            if (selection) {
                quill.insertText(selection.index, "\n[Read Also: Title Here]\n");
            }
        });
        
        $('#quote-from-button').click(() => {
            const selection = quill.getSelection();
            if (selection) {
                quill.insertText(selection.index, "\n\"Quote here\" - Source\n");
            }
        });

        // Handle form submission
        $('#articleForm').submit(function() {
            // Get editor content and set to hidden input
            const content = quill.root.innerHTML;
            $('#hiddenContent').val(content);
            return true;
        });

        // Image preview
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').removeClass('hidden').find('img').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush
