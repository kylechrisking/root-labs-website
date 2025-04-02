                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Handle markdown preview
        function updatePreview(content) {
            const preview = document.getElementById('preview');
            if (preview) {
                preview.innerHTML = marked(content);
            }
        }

        // Handle file uploads
        function handleFileSelect(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Handle tag input
        function initTagInput() {
            const tagInput = document.getElementById('tagInput');
            const tagContainer = document.getElementById('tagContainer');
            const hiddenTagInput = document.getElementById('tags');
            
            if (!tagInput || !tagContainer || !hiddenTagInput) return;

            let tags = [];

            tagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const tag = this.value.trim();
                    if (tag && !tags.includes(tag)) {
                        tags.push(tag);
                        updateTags();
                    }
                    this.value = '';
                }
            });

            function updateTags() {
                tagContainer.innerHTML = tags.map(tag => `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        ${tag}
                        <button type="button" onclick="removeTag('${tag}')" class="ml-1 text-indigo-600 hover:text-indigo-900">×</button>
                    </span>
                `).join(' ');
                hiddenTagInput.value = tags.join(',');
            }

            window.removeTag = function(tag) {
                tags = tags.filter(t => t !== tag);
                updateTags();
            };
        }

        // Initialize components
        document.addEventListener('DOMContentLoaded', function() {
            initTagInput();
        });

        // Add any additional JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Close flash messages after 5 seconds
            const flashMessages = document.querySelectorAll('.rounded-md.p-4');
            flashMessages.forEach(function(flash) {
                setTimeout(function() {
                    flash.style.opacity = '0';
                    flash.style.transition = 'opacity 0.5s ease-in-out';
                    setTimeout(function() {
                        flash.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html> 