document.addEventListener('DOMContentLoaded', () => {
    const imageTrigger = document.getElementById('imageTrigger');
    const imageInput = document.getElementById('imageInput');

    const videoTrigger = document.getElementById('videoTrigger');
    const videoInput = document.getElementById('videoInput');

    const imagePreviewContainer = document.getElementById('imagePreview');

    const previewImg = document.getElementById('previewImg');
    const previewVideo = document.getElementById('previewVideo');

    const removeImageButton = document.getElementById('removeImage');
    const removeVideoButton = document.getElementById('removeVideo');

    const postText = document.getElementById('postText');
    const charCount = document.getElementById('charCount');
    const postButton = document.getElementById('postButton');

    const imageRemovedFlag = document.getElementById('imageRemovedFlag');
    const videoRemovedFlag = document.getElementById('videoRemovedFlag');

    function updatePostButtonState() {
        const remainingChars = 280 - postText.value.length;
        if (charCount) charCount.textContent = remainingChars;

        const hasText = postText.value.length > 0;
        const hasNewImage = imageInput && imageInput.files && imageInput.files.length > 0;
        const hasNewVideo = videoInput && videoInput.files && videoInput.files.length > 0;

        const hasExistingImage = previewImg && !previewImg.classList.contains('hidden');
        const hasExistingVideo = previewVideo && !previewVideo.classList.contains('hidden');

        if (postButton) {
            if (hasText || hasNewImage || hasNewVideo || hasExistingImage || hasExistingVideo) {
                postButton.removeAttribute('disabled');
                postButton.classList.remove('opacity-50');
            } else {
                postButton.setAttribute('disabled', 'true');
                postButton.classList.add('opacity-50');
            }
        }
    }

    function activatePreview(type) {
        if (!imagePreviewContainer) return;

        imagePreviewContainer.classList.remove('hidden');

        if (type === 'image') {
            if (previewVideo) previewVideo.classList.add('hidden');
            if (removeVideoButton) removeVideoButton.classList.add('hidden');
            if (videoInput) videoInput.value = '';

            if (previewImg) previewImg.classList.remove('hidden');
            if (removeImageButton) removeImageButton.classList.remove('hidden');

            if (imageRemovedFlag) imageRemovedFlag.value = '0';
            if (videoRemovedFlag) videoRemovedFlag.value = '1';
        } else if (type === 'video') {
            if (previewImg) previewImg.classList.add('hidden');
            if (removeImageButton) removeImageButton.classList.add('hidden');
            if (imageInput) imageInput.value = '';

            if (previewVideo) previewVideo.classList.remove('hidden');
            if (removeVideoButton) removeVideoButton.classList.remove('hidden');

            if (videoRemovedFlag) videoRemovedFlag.value = '0';
            if (imageRemovedFlag) imageRemovedFlag.value = '1';
        }
        updatePostButtonState();
    }

    function clearMediaPreview(mediaType) {
        if (imageInput) imageInput.value = '';
        if (videoInput) videoInput.value = '';

        if (previewImg) {
            previewImg.src = '#';
            previewImg.classList.add('hidden');
        }
        if (removeImageButton) removeImageButton.classList.add('hidden');

        if (previewVideo) {
            previewVideo.src = '#';
            previewVideo.classList.add('hidden');
            previewVideo.pause();
            previewVideo.currentTime = 0;
        }
        if (removeVideoButton) removeVideoButton.classList.add('hidden');

        if (imagePreviewContainer) {
            if (postText && postText.value.length === 0) {
                imagePreviewContainer.classList.add('hidden');
            }
        }

        if (mediaType === 'image' && imageRemovedFlag) {
            imageRemovedFlag.value = '1';
            if (videoRemovedFlag) videoRemovedFlag.value = '0';
        } else if (mediaType === 'video' && videoRemovedFlag) {
            videoRemovedFlag.value = '1';
            if (imageRemovedFlag) imageRemovedFlag.value = '0';
        } else {
             if (imageRemovedFlag) imageRemovedFlag.value = '1';
             if (videoRemovedFlag) videoRemovedFlag.value = '1';
        }

        updatePostButtonState();
    }

    if (imageTrigger) {
        imageTrigger.addEventListener('click', () => imageInput.click());
    }
    if (videoTrigger) {
        videoTrigger.addEventListener('click', () => videoInput.click());
    }

    if (imageInput) {
        imageInput.addEventListener('change', () => {
            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                const reader = new FileReader();
                reader.onload = e => {
                    if (previewImg) {
                        previewImg.src = e.target.result;
                    }
                    activatePreview('image');
                };
                reader.readAsDataURL(file);
            } else {
                if (!previewVideo || previewVideo.classList.contains('hidden')) {
                     clearMediaPreview('image');
                }
            }
        });
    }

    if (videoInput) {
        videoInput.addEventListener('change', () => {
            const file = videoInput.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                if (previewVideo) {
                    previewVideo.src = url;
                }
                activatePreview('video');
                if (previewVideo) previewVideo.load();
            } else {
                if (!previewImg || previewImg.classList.contains('hidden')) {
                    clearMediaPreview('video');
                }
            }
        });
    }

    if (removeImageButton) {
        removeImageButton.addEventListener('click', () => clearMediaPreview('image'));
    }
    if (removeVideoButton) {
        removeVideoButton.addEventListener('click', () => clearMediaPreview('video'));
    }

    if (postText) {
        postText.addEventListener('input', updatePostButtonState);
    }
    
    const isImageInitiallyVisible = previewImg && !previewImg.classList.contains('hidden');
    const isVideoInitiallyVisible = previewVideo && !previewVideo.classList.contains('hidden');

    if (isImageInitiallyVisible) {
        if (imageRemovedFlag) imageRemovedFlag.value = '0';
        if (videoRemovedFlag) videoRemovedFlag.value = '1';
    } else if (isVideoInitiallyVisible) {
        if (videoRemovedFlag) videoRemovedFlag.value = '0';
        if (imageRemovedFlag) imageRemovedFlag.value = '1';
    } else {
        if (imageRemovedFlag) imageRemovedFlag.value = '0';
        if (videoRemovedFlag) videoRemovedFlag.value = '0';
    }

    updatePostButtonState();
});