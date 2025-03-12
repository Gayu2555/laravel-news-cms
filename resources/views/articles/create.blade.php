@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">

@section('title', 'Tulis Berita')

@section('content')
    <!-- Debug Notification -->
    @if(session('debug_info'))
        <div class="mb-6 p-4 rounded-lg {{ session('debug_info.status') == 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            <div class="font-bold text-lg mb-2">Info Debug:</div>
            <div>Status: {{ session('debug_info.status') == 'success' ? 'Berhasil' : 'Gagal' }}</div>
            <div>Pesan: {{ session('debug_info.message') }}</div>
            @if(session('debug_info.article_id'))
                <div>ID Artikel: {{ session('debug_info.article_id') }}</div>
            @endif
            @if(session('debug_info.article_title'))
                <div>Judul: {{ session('debug_info.article_title') }}</div>
            @endif
            @if(session('debug_info.error'))
                <div>Error: {{ session('debug_info.error') }}</div>
            @endif
            <div>Waktu: {{ session('debug_info.timestamp') }}</div>
        </div>
    @endif

    <!-- Display general errors -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
            <div class="font-bold text-lg mb-2">Terjadi kesalahan:</div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="articleForm" method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <!-- Article Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel</label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" placeholder="Masukkan judul artikel..." value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                    <input type="text" name="author" id="author" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" value="{{ old('author') }}">
                    @error('author')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Publikasi</label>
                    <input type="date" name="date_created" id="date_created" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white" value="{{ old('date_created', date('Y-m-d')) }}">
                    @error('date_created')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Posisi Artikel</label>
                    <select name="position" id="position" required class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Pilih Posisi</option>
                        <option value="news_list" {{ old('position') == 'news_list' ? 'selected' : '' }}>Daftar Berita</option>
                        <option value="sub_headline" {{ old('position') == 'sub_headline' ? 'selected' : '' }}>Sub Headline</option>
                        <option value="headline" {{ old('position') == 'headline' ? 'selected' : '' }}>Headline</option>
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

                <div class="w-px h-6 bg-gray-300"></div>

                <div class="flex items-center gap-1">
                    <button type="button" id="read-also-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-bookmark"></i> Baca Juga
                    </button>
                    <button type="button" id="quote-from-button" class="p-2 hover:bg-gray-200 rounded-md transition-colors">
                        <i class="fas fa-quote-right"></i> Kutipan
                    </button>
                </div>
            </div>

            <!-- Editor -->
            <div id="editor" class="min-h-[600px] max-h-[800px] overflow-auto border rounded-lg mb-6 p-4"></div>
            <input type="hidden" name="content" id="hiddenContent">
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- Image Upload -->
            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition-colors group">
                    <input type="file" id="image" name="image" accept="image/*" class="hidden" required>
                    <label for="image" class="cursor-pointer block">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-blue-500 transition-colors mb-3"></i>
                            <p class="text-gray-700 font-medium">Klik untuk mengunggah gambar utama</p>
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
                    value="{{ old('figcaption') }}">
                @error('figcaption')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="preview-button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Pratinjau
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Terbitkan Artikel
                </button>
            </div>
        </div>
    </form>

    <!-- Read Also Modal -->
    <div id="readAlsoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto">
            <h3 class="text-lg font-medium mb-4">Tambah Baca Juga</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Artikel</label>
                    <input type="text" id="readAlsoTitle" class="w-full px-3 py-2 border rounded-md" placeholder="Masukkan judul artikel...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Artikel</label>
                    <input type="url" id="readAlsoUrl" class="w-full px-3 py-2 border rounded-md" placeholder="https://...">
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" id="cancelReadAlso" class="px-4 py-2 border rounded-md hover:bg-gray-100">Batal</button>
                    <button type="button" id="insertReadAlso" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quote From Modal -->
    <div id="quoteFromModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto">
            <h3 class="text-lg font-medium mb-4">Tambah Kutipan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teks Kutipan</label>
                    <textarea id="quoteText" class="w-full px-3 py-2 border rounded-md" rows="3" placeholder="Masukkan teks kutipan..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Kutipan</label>
                    <input type="text" id="quoteSource" class="w-full px-3 py-2 border rounded-md" placeholder="Nama sumber, tanggal, dll.">
                </div>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" id="cancelQuoteFrom" class="px-4 py-2 border rounded-md hover:bg-gray-100">Batal</button>
                    <button type="button" id="insertQuoteFrom" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tambahkan</button>
                </div>
            </div>
        </div>
    </div>
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
            const url = prompt('Masukkan URL gambar:');
            if (url) {
                quill.insertEmbed(quill.getSelection().index, 'image', url);
            }
        });
        
        $('#link-button').click(() => {
            const url = prompt('Masukkan URL tautan:');
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
        
        $('#read-mode-button').click(() => {
            // Toggle read mode - implementation needed
            alert('Fitur read mode akan diimplementasikan di sini.');
        });
        
        $('#text-color-button').click(() => {
            // Color picker implementation needed
            alert('Fitur text color akan diimplementasikan di sini.');
        });

        // Handle form submission
        $('#articleForm').submit(function() {
            // Get editor content and set to hidden input
            const content = quill.root.innerHTML;
            $('#hiddenContent').val(content);
            return true;
        });

        // Preview button
        $('#preview-button').click(function() {
            alert('Fitur pratinjau akan diimplementasikan di sini.');
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
        
        // Get references to buttons and modals
        const buttons = {
            readAlso: document.getElementById("read-also-button"),
            quoteFrom: document.getElementById("quote-from-button")
        };

        const modals = {
            readAlso: document.getElementById("readAlsoModal"),
            quoteFrom: document.getElementById("quoteFromModal")
        };

        // Read Also functionality
        if (buttons.readAlso && modals.readAlso) {
            buttons.readAlso.addEventListener("click", () => {
                modals.readAlso.classList.replace("hidden", "flex");
            });

            // Cancel Read Also
            const cancelReadAlso = document.getElementById("cancelReadAlso");
            if (cancelReadAlso) {
                cancelReadAlso.addEventListener("click", () => {
                    const readAlsoTitle = document.getElementById("readAlsoTitle");
                    const readAlsoUrl = document.getElementById("readAlsoUrl");
                    if (readAlsoTitle) readAlsoTitle.value = "";
                    if (readAlsoUrl) readAlsoUrl.value = "";
                    modals.readAlso.classList.replace("flex", "hidden");
                });
            }

            // Insert Read Also
            const insertReadAlso = document.getElementById("insertReadAlso");
            if (insertReadAlso) {
                insertReadAlso.addEventListener("click", () => {
                    const readAlsoTitle = document.getElementById("readAlsoTitle");
                    const readAlsoUrl = document.getElementById("readAlsoUrl");

                    if (!readAlsoTitle || !readAlsoUrl) {
                        console.error("Element readAlsoTitle atau readAlsoUrl tidak ditemukan.");
                        return;
                    }

                    const title = readAlsoTitle.value.trim();
                    const url = readAlsoUrl.value.trim();

                    if (!title || !url) {
                        alert("Harap isi judul dan URL artikel");
                        return;
                    }

                    const html = `
                        <div class="read-also p-4 bg-gray-50 rounded-lg my-4">
                            <p><strong>Baca Juga:</strong> <a href="${url}" target="_blank" class="text-blue-600 hover:underline">${title}</a></p>
                        </div>
                    `;

                    const range = quill.getSelection(true);
                    quill.clipboard.dangerouslyPasteHTML(range.index, html);

                    // Reset form dan tutup modal
                    readAlsoTitle.value = "";
                    readAlsoUrl.value = "";
                    modals.readAlso.classList.replace("flex", "hidden");
                });
            }
        }

        // Quote From functionality
        if (buttons.quoteFrom && modals.quoteFrom) {
            buttons.quoteFrom.addEventListener("click", () => {
                modals.quoteFrom.classList.replace("hidden", "flex");
            });

            // Get quote elements
            const quoteText = document.getElementById("quoteText");
            const quoteSource = document.getElementById("quoteSource");
            const cancelQuoteFrom = document.getElementById("cancelQuoteFrom");
            const insertQuoteFrom = document.getElementById("insertQuoteFrom");

            // Handle modal close
            if (cancelQuoteFrom) {
                cancelQuoteFrom.addEventListener("click", () => {
                    // Clear form fields
                    if (quoteText) quoteText.value = "";
                    if (quoteSource) quoteSource.value = "";
                    modals.quoteFrom.classList.replace("flex", "hidden");
                });
            }

            // Handle citation insertion
            if (insertQuoteFrom && quoteText && quoteSource) {
                insertQuoteFrom.addEventListener("click", () => {
                    const text = quoteText.value.trim();
                    const source = quoteSource.value.trim();

                    if (!text || !source) {
                        alert("Harap isi teks kutipan dan sumber");
                        return;
                    }

                    // Create citation HTML
                    const citationHtml = `
                        <div class="citation-wrapper p-4 bg-gray-50 border rounded-lg my-4">
                            <div class="citation-text text-gray-700">
                                <span class="font-medium">Dikutip dari:</span> 
                                <a href="#" 
                                data-source="${source}" 
                                class="text-blue-600 hover:text-blue-800"
                                >${text}</a>
                            </div>
                        </div>
                    `;

                    // Insert into editor
                    const range = quill.getSelection(true);
                    quill.insertText(range.index, "\n");
                    quill.clipboard.dangerouslyPasteHTML(range.index + 1, citationHtml);
                    quill.setSelection(range.index + 2);

                    // Reset and close modal
                    quoteText.value = "";
                    quoteSource.value = "";
                    modals.quoteFrom.classList.replace("flex", "hidden");
                });
            }

            // Handle citation source click
            quill.root.addEventListener("click", (event) => {
                const citationLink = event.target.closest("a[data-source]");
                if (citationLink) {
                    event.preventDefault();
                    const source = citationLink.getAttribute("data-source");
                    alert(`Sumber kutipan: ${source}`);
                }
            });
        }

        // Close modals when clicking outside
        Object.values(modals).forEach((modal) => {
            if (modal) {
                modal.addEventListener("click", (e) => {
                    if (e.target === modal) {
                        // Reset forms
                        if (modal === modals.readAlso) {
                            const readAlsoTitle = document.getElementById("readAlsoTitle");
                            const readAlsoUrl = document.getElementById("readAlsoUrl");
                            if (readAlsoTitle) readAlsoTitle.value = "";
                            if (readAlsoUrl) readAlsoUrl.value = "";
                        } else if (modal === modals.quoteFrom) {
                            const quoteText = document.getElementById("quoteText");
                            const quoteSource = document.getElementById("quoteSource");
                            if (quoteText) quoteText.value = "";
                            if (quoteSource) quoteSource.value = "";
                        }
                        modal.classList.replace("flex", "hidden");
                    }
                });
            }
        });
    });
</script>
@endpush