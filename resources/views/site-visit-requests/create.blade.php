@extends('layouts.public')

@push('styles')
<link href="{{ asset('css/client-data.css') }}?v={{ time() }}" rel="stylesheet">
<style>
    .form-section {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .form-section h5 {
        color: #2d5530;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #28a745;
    }
    .photo-preview {
        position: relative;
        display: inline-block;
        margin: 0.5rem;
    }
    .photo-preview img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }
    .photo-preview .remove-photo {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
    }
    .photo-preview .remove-photo:hover {
        background: #c82333;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-calendar-plus me-2 text-success"></i>
            Request Site Visit
        </h2>
        <a href="{{ route('client-data.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Site Data
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('site-visit-requests.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
        @csrf

        <!-- Visit Details Section -->
        <div class="form-section">
            <h5><i class="fas fa-calendar-alt me-2"></i>Visit Details</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('preferred_date') is-invalid @enderror" 
                           id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('preferred_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="preferred_time" class="form-label">Preferred Time</label>
                    <input type="time" class="form-control @error('preferred_time') is-invalid @enderror" 
                           id="preferred_time" name="preferred_time" value="{{ old('preferred_time') }}">
                    @error('preferred_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Property Information Section -->
        <div class="form-section">
            <h5><i class="fas fa-map-marker-alt me-2"></i>Property Information</h5>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="property_address" class="form-label">Property Address <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('property_address') is-invalid @enderror" 
                              id="property_address" name="property_address" rows="2" 
                              required>{{ old('property_address') }}</textarea>
                    @error('property_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="property_size" class="form-label">Property Size (e.g., 500 sqm, 1 hectare)</label>
                    <input type="text" class="form-control @error('property_size') is-invalid @enderror" 
                           id="property_size" name="property_size" value="{{ old('property_size') }}" 
                           placeholder="e.g., 500 sqm">
                    @error('property_size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="current_condition" class="form-label">Current Condition</label>
                    <select class="form-select @error('current_condition') is-invalid @enderror" 
                            id="current_condition" name="current_condition">
                        <option value="">Select condition...</option>
                        <option value="bare_land" {{ old('current_condition') == 'bare_land' ? 'selected' : '' }}>Bare Land</option>
                        <option value="with_existing_plants" {{ old('current_condition') == 'with_existing_plants' ? 'selected' : '' }}>With Existing Plants</option>
                        <option value="needs_clearing" {{ old('current_condition') == 'needs_clearing' ? 'selected' : '' }}>Needs Clearing</option>
                        <option value="partially_landscaped" {{ old('current_condition') == 'partially_landscaped' ? 'selected' : '' }}>Partially Landscaped</option>
                        <option value="other" {{ old('current_condition') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('current_condition')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Project Description Section -->
        <div class="form-section">
            <h5><i class="fas fa-clipboard-list me-2"></i>Project Description</h5>
            <div class="mb-3">
                <label for="project_description" class="form-label">Describe Your Project <span class="text-danger">*</span></label>
                <textarea class="form-control @error('project_description') is-invalid @enderror" 
                          id="project_description" name="project_description" rows="4" 
                          placeholder="Please describe what you would like to achieve with your landscaping project..." 
                          required>{{ old('project_description') }}</textarea>
                @error('project_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="special_requirements" class="form-label">Special Requirements or Concerns</label>
                <textarea class="form-control @error('special_requirements') is-invalid @enderror" 
                          id="special_requirements" name="special_requirements" rows="3" 
                          placeholder="Any specific requirements, concerns, or questions you have...">{{ old('special_requirements') }}</textarea>
                @error('special_requirements')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Photos Section -->
        <div class="form-section">
            <h5><i class="fas fa-camera me-2"></i>Property Photos (Optional)</h5>
            <p class="text-muted small">Upload photos of your property to help us better understand your needs. Maximum 5 photos, 5MB each.</p>
            <div class="mb-3">
                <input type="file" class="form-control @error('photos.*') is-invalid @enderror" 
                       id="photos" name="photos[]" accept="image/*" multiple>
                @error('photos.*')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div id="photoPreview" class="mt-3"></div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('client-data.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane me-2"></i>Submit Request
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photos');
    const photoPreview = document.getElementById('photoPreview');
    let selectedFiles = [];

    photoInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        
        // Limit to 5 photos
        if (selectedFiles.length + files.length > 5) {
            alert('You can only upload a maximum of 5 photos.');
            return;
        }

        files.forEach(file => {
            // Check file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum file size is 5MB.`);
                return;
            }

            selectedFiles.push(file);
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-photo" data-index="${selectedFiles.length - 1}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                photoPreview.appendChild(preview);
            };
            reader.readAsDataURL(file);
        });

        // Clear the input
        photoInput.value = '';
    });

    // Remove photo
    photoPreview.addEventListener('click', function(e) {
        if (e.target.closest('.remove-photo')) {
            const button = e.target.closest('.remove-photo');
            const index = parseInt(button.dataset.index);
            
            // Remove from array
            selectedFiles.splice(index, 1);
            
            // Remove preview
            button.closest('.photo-preview').remove();
            
            // Update indices
            document.querySelectorAll('.remove-photo').forEach((btn, i) => {
                btn.dataset.index = i;
            });
        }
    });

    // On form submit, create a new FileList with selected files
    document.getElementById('requestForm').addEventListener('submit', function(e) {
        if (selectedFiles.length > 0) {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            photoInput.files = dataTransfer.files;
        }
    });
});
</script>
@endpush
@endsection
