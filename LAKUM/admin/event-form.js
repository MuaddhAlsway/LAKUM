// Event Form JavaScript

// Cover Image Upload
const coverPreview = document.getElementById('coverPreview');
const coverInput = document.getElementById('cover_image');
const coverImg = document.getElementById('coverImg');
const removeCoverBtn = document.getElementById('removeCover');

if (coverPreview && coverInput) {
    coverPreview.addEventListener('click', () => {
        coverInput.click();
    });

    coverInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                coverImg.src = e.target.result;
                coverImg.style.display = 'block';
                removeCoverBtn.style.display = 'flex';
                coverPreview.querySelector('.cover-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    if (removeCoverBtn) {
        removeCoverBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            coverInput.value = '';
            coverImg.style.display = 'none';
            coverImg.src = '';
            removeCoverBtn.style.display = 'none';
            coverPreview.querySelector('.cover-placeholder').style.display = 'block';
        });
    }
}

// Character Count
const descriptionTextarea = document.getElementById('description');
const charCount = document.getElementById('charCount');

if (descriptionTextarea && charCount) {
    descriptionTextarea.addEventListener('input', () => {
        charCount.textContent = descriptionTextarea.value.length;
    });
}

// Date & Time Validation
const eventDate = document.getElementById('event_date');
const eventTime = document.getElementById('event_time');
const endDate = document.getElementById('end_date');
const endTime = document.getElementById('end_time');
const dateValidation = document.getElementById('dateValidation');

// Set minimum date to today
if (eventDate) {
    const today = new Date().toISOString().split('T')[0];
    eventDate.setAttribute('min', today);
    
    eventDate.addEventListener('change', () => {
        if (endDate) {
            endDate.setAttribute('min', eventDate.value);
        }
        validateDates();
    });
}

if (endDate) {
    endDate.addEventListener('change', validateDates);
}

if (eventTime) {
    eventTime.addEventListener('change', validateDates);
}

if (endTime) {
    endTime.addEventListener('change', validateDates);
}

function validateDates() {
    if (!eventDate || !endDate || !dateValidation) return;
    
    const startDate = eventDate.value;
    const finishDate = endDate.value;
    const startTime = eventTime ? eventTime.value : '';
    const finishTime = endTime ? endTime.value : '';
    
    // Check if end date is provided
    if (!finishDate) {
        dateValidation.className = 'date-validation-msg';
        dateValidation.textContent = '';
        return;
    }
    
    // Check if end date is before start date
    if (finishDate < startDate) {
        dateValidation.className = 'date-validation-msg error';
        dateValidation.innerHTML = '<i class="ri-error-warning-line"></i> End date cannot be before start date';
        return;
    }
    
    // If same date, check times
    if (finishDate === startDate && startTime && finishTime) {
        if (finishTime <= startTime) {
            dateValidation.className = 'date-validation-msg error';
            dateValidation.innerHTML = '<i class="ri-error-warning-line"></i> End time must be after start time';
            return;
        }
    }
    
    // Calculate duration
    const start = new Date(startDate + ' ' + (startTime || '00:00'));
    const end = new Date(finishDate + ' ' + (finishTime || '23:59'));
    const diffMs = end - start;
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    const diffHours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    
    let durationText = '';
    if (diffDays > 0) {
        durationText = `${diffDays} day${diffDays > 1 ? 's' : ''}`;
        if (diffHours > 0) {
            durationText += ` and ${diffHours} hour${diffHours > 1 ? 's' : ''}`;
        }
    } else if (diffHours > 0) {
        durationText = `${diffHours} hour${diffHours > 1 ? 's' : ''}`;
    } else {
        const diffMinutes = Math.floor(diffMs / (1000 * 60));
        durationText = `${diffMinutes} minute${diffMinutes > 1 ? 's' : ''}`;
    }
    
    dateValidation.className = 'date-validation-msg success';
    dateValidation.innerHTML = `<i class="ri-checkbox-circle-line"></i> Event duration: ${durationText}`;
}

// Gallery Images Upload
const galleryUploadArea = document.getElementById('galleryUploadArea');
const galleryInput = document.getElementById('event_images');
const galleryPreview = document.getElementById('galleryPreview');
let galleryFiles = [];

if (galleryUploadArea && galleryInput) {
    galleryUploadArea.addEventListener('click', () => {
        galleryInput.click();
    });

    galleryInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        files.forEach(file => {
            if (!galleryFiles.find(f => f.name === file.name && f.size === file.size)) {
                galleryFiles.push(file);
            }
        });
        updateGalleryPreview();
    });

    // Drag and drop
    galleryUploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        galleryUploadArea.style.borderColor = '#000';
        galleryUploadArea.style.background = '#e9ecef';
    });

    galleryUploadArea.addEventListener('dragleave', () => {
        galleryUploadArea.style.borderColor = '#dee2e6';
        galleryUploadArea.style.background = '#f8f9fa';
    });

    galleryUploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        galleryUploadArea.style.borderColor = '#dee2e6';
        galleryUploadArea.style.background = '#f8f9fa';
        
        const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
        files.forEach(file => {
            if (!galleryFiles.find(f => f.name === file.name && f.size === file.size)) {
                galleryFiles.push(file);
            }
        });
        updateGalleryPreview();
    });
}

function updateGalleryPreview() {
    if (!galleryPreview) return;
    
    galleryPreview.innerHTML = '';
    
    galleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'gallery-item-preview';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Gallery">
                <button type="button" class="remove-gallery-item" data-index="${index}">
                    <i class="ri-close-line"></i>
                </button>
            `;
            galleryPreview.appendChild(div);
            
            // Add remove functionality
            div.querySelector('.remove-gallery-item').addEventListener('click', () => {
                removeGalleryItem(index);
            });
        };
        reader.readAsDataURL(file);
    });
    
    // Update file input
    updateFileInput();
}

function removeGalleryItem(index) {
    galleryFiles.splice(index, 1);
    updateGalleryPreview();
}

function updateFileInput() {
    if (!galleryInput) return;
    
    const dataTransfer = new DataTransfer();
    galleryFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    galleryInput.files = dataTransfer.files;
}

// Form Submission Validation
const eventForm = document.getElementById('eventForm');
if (eventForm) {
    eventForm.addEventListener('submit', (e) => {
        // Check date validation
        if (dateValidation && dateValidation.classList.contains('error')) {
            e.preventDefault();
            alert('Please fix the date/time errors before submitting');
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="ri-loader-4-line"></i> Creating Event...';
            submitBtn.disabled = true;
        }
    });
}

// Initialize character count if editing
if (descriptionTextarea && charCount) {
    charCount.textContent = descriptionTextarea.value.length;
}

// Initialize date validation if editing
if (eventDate && eventDate.value) {
    validateDates();
}

// Video Link Validation
const videoInput = document.getElementById('video_link');
const videoValidation = document.getElementById('videoValidation');

if (videoInput && videoValidation) {
    videoInput.addEventListener('input', validateVideoLink);
    videoInput.addEventListener('blur', validateVideoLink);
    
    // Validate on page load if there's a value
    if (videoInput.value) {
        validateVideoLink();
    }
}

function validateVideoLink() {
    const url = videoInput.value.trim();
    
    // If empty, clear validation
    if (!url) {
        videoInput.classList.remove('valid', 'invalid');
        videoValidation.className = 'video-validation-msg';
        videoValidation.innerHTML = '';
        return true;
    }
    
    // Check for YouTube
    const youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[\w-]+/;
    
    // Check for Vimeo
    const vimeoRegex = /^(https?:\/\/)?(www\.)?vimeo\.com\/\d+/;
    
    if (youtubeRegex.test(url)) {
        videoInput.classList.remove('invalid');
        videoInput.classList.add('valid');
        videoValidation.className = 'video-validation-msg success';
        videoValidation.innerHTML = '<i class="ri-checkbox-circle-line"></i> Valid YouTube link';
        return true;
    } else if (vimeoRegex.test(url)) {
        videoInput.classList.remove('invalid');
        videoInput.classList.add('valid');
        videoValidation.className = 'video-validation-msg success';
        videoValidation.innerHTML = '<i class="ri-checkbox-circle-line"></i> Valid Vimeo link';
        return true;
    } else {
        videoInput.classList.remove('valid');
        videoInput.classList.add('invalid');
        videoValidation.className = 'video-validation-msg error';
        videoValidation.innerHTML = '<i class="ri-error-warning-line"></i> Only YouTube and Vimeo links are allowed';
        return false;
    }
}

// Update form submission to validate video
if (eventForm) {
    const originalSubmitHandler = eventForm.onsubmit;
    eventForm.addEventListener('submit', (e) => {
        // Check video validation
        if (videoInput && videoInput.value && !validateVideoLink()) {
            e.preventDefault();
            alert('Please enter a valid YouTube or Vimeo link, or leave the field empty');
            videoInput.focus();
            return false;
        }
    });
}
