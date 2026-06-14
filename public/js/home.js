/**
 * JavaScript for handling home page functionality
 * - Category filtering
 * - Plant card details
 * - Search functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize existing functions
    initCategoryFilter();
    initPlantCardDetails();
    initSearchFunctionality();
    initPlantDetailsActions();
    initPlantInventorySearch();
    
    // Initialize new functions
    loadSelectedPlants();
    clearPlantsOnLogout();
    initPlantSelectionCounter();
    initAddPlantButtons();
    initEditableMeasurements();
    initModalSubmitButton();
    initAdminEditFeatures();
    // Load persisted categories first, then bind add/delete
    loadPersistedCategories().then(() => {
        initAddCategory();
        initDeleteCategory();
    });
    
    // Update plant action buttons based on selection
    selectedPlants.forEach(plant => {
        updatePlantActionButtons(plant.id, 'remove');
    });
    
    // Add toast styles if not already in CSS
    if (!document.getElementById('toastStyles')) {
        const style = document.createElement('style');
        style.id = 'toastStyles';
        style.textContent = `
            .toast {
                position: fixed;
                bottom: 80px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #333;
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 9999;
                visibility: hidden;
            }
            .toast.show {
                opacity: 1;
                visibility: visible;
            }
        `;
        document.head.appendChild(style);
    }
    
    console.log('Home page functionality initialized');
});

// Apply current category and search filters to the grid (used after inline edits)
function applyCurrentFilters() {
    const activeCat = document.querySelector('.category-icon-item.active');
    const selectedCategory = activeCat ? activeCat.getAttribute('data-category') : 'all';
    const searchInput = document.getElementById('searchInput');
    const searchTerm = (searchInput ? searchInput.value : '').toLowerCase().trim();

    document.querySelectorAll('.plant-item').forEach(plant => {
        const plantName = (plant.getAttribute('data-name') || '').toLowerCase();
        const plantCategory = plant.getAttribute('data-category') || '';

        const matchesCategory = selectedCategory === 'all' || plantCategory === selectedCategory;
        const matchesSearch = searchTerm === '' || plantName.includes(searchTerm);

        plant.style.display = (matchesCategory && matchesSearch) ? '' : 'none';
    });
}

// Reusable confirm/info modal for Home page (matches system style)
function openHomeConfirmDialog(opts) {
    const defaults = { title: 'Confirm', body: 'Are you sure?', yesText: 'Yes', noText: 'No', yesClass: 'btn-danger' };
    const o = Object.assign({}, defaults, opts || {});
    const modalEl = document.getElementById('homeConfirmModal');
    if (!modalEl) { return Promise.resolve(confirm(o.body)); }
    modalEl.querySelector('#homeConfirmTitle').textContent = o.title;
    modalEl.querySelector('#homeConfirmBody').textContent = o.body;
    const yesBtn = modalEl.querySelector('#homeConfirmYesBtn');
    const noBtn = modalEl.querySelector('#homeConfirmCancelBtn');
    const okBtn = modalEl.querySelector('#homeConfirmOkBtn');
    okBtn.classList.add('d-none');
    yesBtn.classList.remove('d-none');
    noBtn.classList.remove('d-none');
    yesBtn.classList.remove('btn-success','btn-primary','btn-danger');
    if (o.yesClass) yesBtn.classList.add(o.yesClass);
    yesBtn.textContent = o.yesText;
    noBtn.textContent = o.noText;
    return new Promise(resolve => {
        const onHide = () => { modalEl.removeEventListener('hidden.bs.modal', onHide); resolve(false); };
        modalEl.addEventListener('hidden.bs.modal', onHide);
        yesBtn.onclick = () => {
            modalEl.removeEventListener('hidden.bs.modal', onHide);
            resolve(true);
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        };
        noBtn.onclick = () => resolve(false);
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });
}

function openHomeInfoDialog(opts) {
    const defaults = { title: 'Notice', body: 'OK', okText: 'OK', okClass: 'btn-success' };
    const o = Object.assign({}, defaults, opts || {});
    const modalEl = document.getElementById('homeConfirmModal');
    if (!modalEl) { alert(o.body); return; }
    modalEl.querySelector('#homeConfirmTitle').textContent = o.title;
    modalEl.querySelector('#homeConfirmBody').textContent = o.body;
    const yesBtn = modalEl.querySelector('#homeConfirmYesBtn');
    const noBtn = modalEl.querySelector('#homeConfirmCancelBtn');
    const okBtn = modalEl.querySelector('#homeConfirmOkBtn');
    yesBtn.classList.add('d-none');
    noBtn.classList.add('d-none');
    okBtn.classList.remove('d-none');
    okBtn.classList.remove('btn-success','btn-primary','btn-danger');
    if (o.okClass) okBtn.classList.add(o.okClass);
    okBtn.textContent = o.okText;
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
}

// Load categories from backend and render extra categories + update selects
function loadPersistedCategories() {
    const baseSlugs = new Set(['shrub','herbs','palm','tree','grass','bamboo','fertilizer']);
    return fetch('/categories', { headers: { 'Accept': 'application/json' }})
        .then(r => r.json())
        .then(categories => {
            if (!Array.isArray(categories)) return;
            const grid = document.querySelector('.category-grid');

            categories.forEach(cat => {
                if (!cat || !cat.slug || !cat.name) return;
                // Update Add/Edit selects
                const addSel = document.getElementById('category');
                const editSel = document.getElementById('edit_category');
                if (addSel && !addSel.querySelector(`option[value="${cat.slug}"]`)) {
                    const opt = document.createElement('option');
                    opt.value = cat.slug; opt.textContent = cat.name;
                    addSel.appendChild(opt);
                }
                if (editSel && !editSel.querySelector(`option[value="${cat.slug}"]`)) {
                    const opt2 = document.createElement('option');
                    opt2.value = cat.slug; opt2.textContent = cat.name;
                    editSel.appendChild(opt2);
                }

                // Render only extras in the grid (base already present)
                if (!grid || baseSlugs.has(cat.slug)) return;
                if (grid.querySelector(`.category-icon-item[data-category="${cat.slug}"]`)) return; // avoid dup
                const item = document.createElement('div');
                item.className = 'category-icon-item';
                item.setAttribute('data-category', cat.slug);
                item.setAttribute('data-category-id', cat.id);
                const iconHtml = cat.icon_path ? `<img src="/storage/${cat.icon_path}" alt="${cat.name}" class="category-img">` : '<i class="fas fa-leaf"></i>';
                item.innerHTML = `<div class="icon-circle">${iconHtml}</div><span>${escapeHtml(cat.name)}</span>`;
                grid.appendChild(item);
                attachCategoryHandler(item);
            });
        })
        .catch(() => {});
}

/**
 * Initialize the category filter
 */
function initCategoryFilter() {
    const categoryItems = document.querySelectorAll('.category-icon-item');
    const plantItems = document.querySelectorAll('.plant-item');
    
    categoryItems.forEach(item => {
        attachCategoryHandler(item);
    });
    
    console.log('Category filter initialized with', categoryItems.length, 'categories');
}

// Attach click handler to a single category item (used for dynamic categories as well)
function attachCategoryHandler(item) {
    item.addEventListener('click', function() {
        // Remove active class from all category items
        document.querySelectorAll('.category-icon-item').forEach(cat => cat.classList.remove('active'));

        // Add active class to clicked category
        this.classList.add('active');

        // Get selected category
        const category = this.getAttribute('data-category');
        console.log('Category selected:', category);

        // Filter plants
        document.querySelectorAll('.plant-item').forEach(plant => {
            const plantCategory = plant.getAttribute('data-category');
            plant.style.display = (category === 'all' || plantCategory === category) ? '' : 'none';
        });
    });
}

// Initialize Add Category modal Save action (admin-only; button exists only for admins)
function initAddCategory() {
    const saveBtn = document.getElementById('saveNewCategory');
    if (!saveBtn) return; // Not visible for non-admins

    saveBtn.addEventListener('click', function() {
        const nameInput = document.getElementById('newCategoryName');
        const iconInput = document.getElementById('newCategoryIcon');
        const name = (nameInput?.value || '').trim();
        if (!name) { openHomeInfoDialog({ title: 'Notice', body: 'Please enter a category name.' }); return; }

        const fd = new FormData();
        fd.append('name', name);
        if (iconInput && iconInput.files && iconInput.files[0]) {
            fd.append('icon', iconInput.files[0]);
        }

        fetch('/categories', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: fd
        })
        .then(r => r.json())
        .then(resp => {
            const cat = resp && resp.category ? resp.category : null;
            if (!cat) { alert('Category saved but response missing data.'); return; }

            // Update selects
            const addSel = document.getElementById('category');
            const editSel = document.getElementById('edit_category');
            if (addSel && !addSel.querySelector(`option[value="${cat.slug}"]`)) {
                const opt = document.createElement('option');
                opt.value = cat.slug; opt.textContent = cat.name; addSel.appendChild(opt);
            }
            if (editSel && !editSel.querySelector(`option[value="${cat.slug}"]`)) {
                const opt2 = document.createElement('option');
                opt2.value = cat.slug; opt2.textContent = cat.name; editSel.appendChild(opt2);
            }

            // Render new extra in grid (skip if base)
            const baseSlugs = new Set(['shrub','herbs','palm','tree','grass','bamboo','fertilizer']);
            if (!baseSlugs.has(cat.slug)) {
                const grid = document.querySelector('.category-grid');
                if (grid && !grid.querySelector(`.category-icon-item[data-category="${cat.slug}"]`)) {
                    const item = document.createElement('div');
                    item.className = 'category-icon-item';
                    item.setAttribute('data-category', cat.slug);
                    item.setAttribute('data-category-id', cat.id);
                    const iconHtml = cat.icon_path ? `<img src="/storage/${cat.icon_path}" alt="${cat.name}" class="category-img">` : '<i class="fas fa-leaf"></i>';
                    item.innerHTML = `<div class="icon-circle">${iconHtml}</div><span>${escapeHtml(cat.name)}</span>`;
                    grid.appendChild(item);
                    attachCategoryHandler(item);
                }
            }

            // Close modal and reset
            const modalEl = document.getElementById('addCategoryModal');
            if (modalEl && window.bootstrap) {
                const instance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                instance.hide();
            }
            if (nameInput) nameInput.value = '';
            if (iconInput) iconInput.value = '';
        })
        .catch(() => openHomeInfoDialog({ title: 'Error', body: 'Failed to save category.' }));
    });
}

// Basic HTML escape to avoid injecting raw text
function escapeHtml(s) {
    return s.replace(/[&<>"']/g, function(c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

// Initialize Delete Category action (admin-only; button exists only for admins)
function initDeleteCategory() {
    const deleteBtn = document.getElementById('deleteCategoryBtn');
    if (!deleteBtn) return; // Not visible for non-admins

    deleteBtn.addEventListener('click', function() {
        const active = document.querySelector('.category-icon-item.active');
        if (!active) { openHomeInfoDialog({ title: 'Notice', body: 'Please select a category to delete.' }); return; }
        const cat = active.getAttribute('data-category');
        if (!cat || cat === 'all') { openHomeInfoDialog({ title: 'Notice', body: 'Cannot delete the "All" category.' }); return; }
        const name = (active.querySelector('span')?.textContent || '').trim() || cat;
        const id = active.getAttribute('data-category-id');

        if (!id) { openHomeInfoDialog({ title: 'Notice', body: 'Only extra categories can be deleted.' }); return; }
        openHomeConfirmDialog({ title: 'Delete Category', body: `Delete category "${name}"?`, yesText: 'Yes', noText: 'No', yesClass: 'btn-danger' })
        .then(confirmed => {
            if (!confirmed) return;

        fetch(`/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(() => {
            // Remove the tile
            active.remove();
            // Remove from selects
            const addSel = document.getElementById('category');
            const editSel = document.getElementById('edit_category');
            if (addSel) addSel.querySelector(`option[value="${cat}"]`)?.remove();
            if (editSel) editSel.querySelector(`option[value="${cat}"]`)?.remove();
            // Activate All and reapply filters
            const allItem = document.querySelector('.category-icon-item[data-category="all"]');
            if (allItem) allItem.classList.add('active');
            applyCurrentFilters();
        })
        .catch(() => openHomeInfoDialog({ title: 'Error', body: 'Failed to delete category.' }));
        });
    });
}

/**
 * Initialize plant card details click functionality
 */
function initPlantCardDetails() {
    // Admin plant cards
    document.querySelectorAll('.admin-plant-card .plant-main-view').forEach(mainView => {
        mainView.addEventListener('click', function(e) {
            // Get the parent card
            const card = this.closest('.admin-plant-card');
            if (card) {
                // Check if we're in selection mode
                if (document.body.classList.contains('plant-selection-mode')) {
                    return; // Don't toggle details in selection mode
                }
                
                // Toggle the details
                toggleAdminDetails(card.querySelector('.back-to-main'));
                e.stopPropagation();
                console.log('Admin plant card clicked');
            }
        });
    });
    
    // User plant cards
    document.querySelectorAll('.user-plant-card .plant-main-view').forEach(mainView => {
        mainView.addEventListener('click', function(e) {
            // Get the parent card
            const card = this.closest('.user-plant-card');
            if (card) {
                // Check if we're in selection mode
                if (document.body.classList.contains('plant-selection-mode')) {
                    return; // Don't toggle details in selection mode
                }
                
                // Toggle the details
                toggleUserDetails(card.querySelector('.back-to-main'));
                e.stopPropagation();
                console.log('User plant card clicked');
            }
        });
    });
    
    console.log('Plant card details initialized');
}

/**
 * Toggle admin plant details panel
 */
function toggleAdminDetails(element) {
    const card = element.closest('.admin-plant-card');
    if (card) {
        card.classList.toggle('show-details');
        
        // If showing details, reset scrollbar position
        if (card.classList.contains('show-details')) {
            const detailsPanel = card.querySelector('.plant-details-panel');
            if (detailsPanel) {
                detailsPanel.scrollTop = 0;
            }
        }
    }
}

/**
 * Toggle user plant details panel
 */
function toggleUserDetails(element) {
    const card = element.closest('.user-plant-card');
    if (card) {
        card.classList.toggle('show-details');
        
        // If showing details, reset scrollbar position
        if (card.classList.contains('show-details')) {
            const detailsPanel = card.querySelector('.plant-details-panel');
            if (detailsPanel) {
                detailsPanel.scrollTop = 0;
            }
        }
    }
}

/**
 * Initialize plant details functionality
 */
function initPlantDetailsActions() {
    // Handle delete plant button clicks
    document.querySelectorAll('.delete-plant-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const plantId = this.getAttribute('data-plant-id');
            const plantName = this.getAttribute('data-plant-name');
            openHomeConfirmDialog({ title: 'Remove From Display', body: `Remove "${plantName}" from display?`, yesText: 'Yes', noText: 'No', yesClass: 'btn-danger' })
            .then(confirmed => {
                if (!confirmed) return;
                fetch(`/display-plants/${plantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => { window.location.reload(); })
                .catch(error => { openHomeInfoDialog({ title: 'Error', body: 'Failed to delete plant. Please try again.' }); });
            });
        });
    });
    
    // Handle manage photo button clicks
    document.querySelectorAll('.manage-photo-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const plantId = this.getAttribute('data-plant-id');
            const photoPath = this.getAttribute('data-photo-path');
            
            // Set the plant ID in the modal form
            document.getElementById('photoPlantId').value = plantId;
            
            // Show/hide the current photo or placeholder
            const currentPhoto = document.getElementById('currentPhoto');
            const noPhotoPlaceholder = document.getElementById('noPhotoPlaceholder');
            
            if (photoPath) {
                currentPhoto.src = `/storage/${photoPath}`;
                currentPhoto.classList.remove('d-none');
                noPhotoPlaceholder.classList.add('d-none');
            } else {
                currentPhoto.classList.add('d-none');
                noPhotoPlaceholder.classList.remove('d-none');
            }
            
            // Open the photo management modal
            const photoModal = new bootstrap.Modal(document.getElementById('photoManageModal'));
            photoModal.show();
        });
    });
    
    // Handle save photo button click
    const savePhotoBtn = document.getElementById('savePhoto');
    if (savePhotoBtn) {
        savePhotoBtn.addEventListener('click', function() {
            const form = document.getElementById('photoUploadForm');
            const formData = new FormData(form);
            
            // Check if a file was selected
            if (document.getElementById('plantPhoto').files.length === 0) {
                alert('Please select a photo to upload.');
                return;
            }
            
            fetch('/display-plants/photo/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Photo uploaded:', data);
                // Close the modal
                bootstrap.Modal.getInstance(document.getElementById('photoManageModal')).hide();
                // Reload the page to show the new photo
                window.location.reload();
            })
            .catch(error => {
                console.error('Error uploading photo:', error);
                alert('Failed to upload photo. Please try again.');
            });
        });
    }
    
    // Handle remove photo button click
    const removePhotoBtn = document.getElementById('removePhoto');
    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function() {
            const plantId = document.getElementById('photoPlantId').value;
            
            if (confirm('Are you sure you want to remove this photo?')) {
                fetch(`/display-plants/photo/remove/${plantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Photo removed:', data);
                    // Close the modal
                    bootstrap.Modal.getInstance(document.getElementById('photoManageModal')).hide();
                    // Reload the page
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error removing photo:', error);
                    alert('Failed to remove photo. Please try again.');
                });
            }
        });
    }
}

/**
 * Initialize plant inventory search for adding plants
 */
function initPlantInventorySearch() {
    const searchInput = document.getElementById('plantSearchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) {
        return;
    }
    
    // Get IDs of all displayed plants to exclude them from search results
    const getDisplayedPlantIds = () => {
        const displayedPlants = [];
        document.querySelectorAll('.plant-item').forEach(item => {
            const plantName = item.getAttribute('data-name');
            if (plantName) {
                displayedPlants.push(plantName);
            }
        });
        return displayedPlants;
    };
    
    // Handle input changes for search
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        // Display results with just a single character (changed from 2)
        if (query.length < 1) {
            searchResults.classList.add('d-none');
            return;
        }
        
        // Get the currently displayed plants to exclude
        const displayedPlants = getDisplayedPlantIds();
        
        // Fetch plants from inventory that match the query
        fetch(`/plants/search?search=${encodeURIComponent(query)}&exclude=${encodeURIComponent(JSON.stringify(displayedPlants))}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Clear previous results
            searchResults.innerHTML = '';
            
            if (data.length === 0) {
                searchResults.innerHTML = '<div class="p-2 text-muted">No plants found</div>';
            } else {
                data.forEach(plant => {
                    const item = document.createElement('div');
                    item.className = 'search-result-item p-2 border-bottom';
                    // Add styles to prevent text selection while keeping a clickable appearance
                    item.style.userSelect = 'none';
                    item.style.cursor = 'pointer';
                    item.style.webkitUserSelect = 'none';
                    item.style.mozUserSelect = 'none';
                    item.style.msUserSelect = 'none';
                    
                    item.innerHTML = `
                        <div><strong>${plant.name}</strong></div>
                        <div class="small text-muted">${plant.scientific_name || ''}</div>
                    `;
                    
                    // Handle click on the plant to select it
                    item.addEventListener('click', function() {
                        // Fill in the form with the selected plant's data
                        document.getElementById('plantName').value = plant.name;
                        document.getElementById('plantCode').value = plant.code || '';
                        document.getElementById('scientificName').value = plant.scientific_name || '';
                        document.getElementById('category').value = plant.category;
                        document.getElementById('heightMm').value = plant.height_mm || '';
                        document.getElementById('spreadMm').value = plant.spread_mm || '';
                        document.getElementById('spacingMm').value = plant.spacing_mm || '';
                        
                        // Hide the search results
                        searchResults.classList.add('d-none');
                        searchInput.value = plant.name;
                    });
                    
                    searchResults.appendChild(item);
                });
            }
            
            searchResults.classList.remove('d-none');
        })
        .catch(error => {
            console.error('Error searching plants:', error);
            searchResults.innerHTML = '<div class="p-2 text-danger">Error loading plants</div>';
            searchResults.classList.remove('d-none');
        });
    });
    
    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });
    
    // Set up the Save button for the Add Plant modal
    const saveNewPlantBtn = document.getElementById('saveNewPlant');
    if (saveNewPlantBtn) {
        saveNewPlantBtn.addEventListener('click', function() {
            const formData = new FormData();
            
            // Get form data
            formData.append('name', document.getElementById('plantName').value);
            formData.append('code', document.getElementById('plantCode').value);
            formData.append('scientific_name', document.getElementById('scientificName').value);
            formData.append('category', document.getElementById('category').value);
            formData.append('height_mm', document.getElementById('heightMm').value);
            formData.append('spread_mm', document.getElementById('spreadMm').value);
            formData.append('spacing_mm', document.getElementById('spacingMm').value);
            
            // Add photo if selected
            const photoInput = document.getElementById('photo');
            if (photoInput.files.length > 0) {
                formData.append('photo', photoInput.files[0]);
            }
            
            // Submit the form
            fetch('/display-plants', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Plant added:', data);
                // Close the modal
                bootstrap.Modal.getInstance(document.getElementById('addPlantModal')).hide();
                // Reload the page
                window.location.reload();
            })
            .catch(error => {
                console.error('Error adding plant:', error);
                alert('Failed to add plant. Please try again.');
            });
        });
    }
}

/**
 * Initialize search functionality
 */
function initSearchFunctionality() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) {
        console.error('Search input not found');
        return;
    }
    
    const plantItems = document.querySelectorAll('.plant-item');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        console.log('Search term:', searchTerm);
        
        plantItems.forEach(plant => {
            const plantName = plant.getAttribute('data-name').toLowerCase();
            if (plantName.includes(searchTerm) || searchTerm === '') {
                plant.style.display = '';
            } else {
                plant.style.display = 'none';
            }
        });
    });
    
    console.log('Search functionality initialized');
}

// Add this new function to handle the RFQ form
function handleRFQForm() {
    const itemsTable = document.getElementById('rfqItemsTable');
    if (!itemsTable) {
        console.error('RFQ items table not found');
        return;
    }

    const plants = [
        { name: 'Plant 1', code: 'P1', height: '100', spread: '50', spacing: '30' },
        { name: 'Plant 2', code: 'P2', height: '120', spread: '60', spacing: '40' },
        { name: 'Plant 3', code: 'P3', height: '150', spread: '70', spacing: '50' }
    ];

    plants.forEach((plant, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="text-align: center;">${index + 1}</td>
            <td style="text-align: center;">
                <input type="number" min="1" value="${plant.quantity || 1}" class="form-control form-control-sm" style="width: 60px" 
                    onchange="updatePlantTotalPrice(this)">
            </td>
            <td>${plant.name}</td>
            <td>${plant.code || ''}</td>
            <td style="text-align: center;">
                <input type="number" value="${plant.height || ''}" class="form-control form-control-sm height-field" style="width: 80px">
            </td>
            <td style="text-align: center;">
                <input type="number" value="${plant.spread || ''}" class="form-control form-control-sm spread-field" style="width: 80px">
            </td>
            <td style="text-align: center;">
                <input type="number" value="${plant.spacing || ''}" class="form-control form-control-sm spacing-field" style="width: 80px">
            </td>
            <td style="text-align: center;">
                <input type="text" class="form-control form-control-sm remarks-field" placeholder="Add remarks">
            </td>
            <td style="text-align: center;">
                <input type="number" class="form-control form-control-sm unit-price" value="0.00" min="0" step="0.01" style="width: 80px"
                    onchange="updatePlantTotalPrice(this)">
            </td>
            <td style="text-align: center;">
                <span class="total-price">₱0.00</span>
            </td>
            <td style="text-align: center;">Pending</td>
        `;
        itemsTable.appendChild(row);
    });
    
    // Add function to calculate total price
    window.updatePlantTotalPrice = function(input) {
        const row = input.closest('tr');
        const quantity = parseFloat(row.querySelector('td:nth-child(2) input').value) || 0;
        const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
        const totalPrice = (quantity * unitPrice).toFixed(2);
        row.querySelector('.total-price').textContent = '₱' + totalPrice;
    };
    
    // Initialize total prices
    document.querySelectorAll('#rfqItemsTable tr').forEach(row => {
        const unitPriceInput = row.querySelector('.unit-price');
        if (unitPriceInput) {
            window.updatePlantTotalPrice(unitPriceInput);
        }
    });
    
    // Reset selection mode
    document.body.classList.remove('plant-selection-mode');
}

// Global variables for plant selection
let selectedPlants = [];
const MAX_PLANTS = 20;

// Initialize plant selection counter - only for regular users (not clients)
function initPlantSelectionCounter() {
    // Only show counter for regular users (not clients, not admins)
    if (!isUserAuthenticated() || isClientUser() || isAdminUser()) {
        return;
    }
    
    // Update the top bar button count
    updatePlantCounter();
}

// Check if user is authenticated
function isUserAuthenticated() {
    // We can check if the user is authenticated by looking for a specific element
    // that's only present for authenticated users
    return document.querySelector('[data-auth-check="true"]') !== null;
}

// Check if the current user is a client
function isClientUser() {
    // Look for a data attribute that identifies client users
    return document.querySelector('[data-user-role="client"]') !== null;
}

// Check if the current user is an admin
function isAdminUser() {
    // Look for a data attribute that identifies admin users
    return document.querySelector('[data-user-role="admin"]') !== null || 
           document.querySelector('[data-user-role="super_admin"]') !== null;
}

// Add a function to clear all selected plants
function clearAllSelectedPlants() {
    if (selectedPlants.length === 0) {
        return; // Nothing to clear
    }
    
    if (confirm('Are you sure you want to clear all selected plants?')) {
        // Store the plant IDs to update their buttons
        const plantIds = selectedPlants.map(plant => plant.id);
        
        // Clear the array
        selectedPlants = [];
        
        // Save the empty selection
        saveSelectedPlants();
        
        // Update the counter
        updatePlantCounter();
        
        // Update all buttons back to "Add Plant"
        plantIds.forEach(id => {
            updatePlantActionButtons(id, 'add');
        });
        
        showToast('All selected plants have been cleared');
    }
}

// Update the plant selection counter in the top bar button
function updatePlantCounter() {
    const requestCountElement = document.getElementById('requestCount');
    const viewRequestBtn = document.getElementById('viewRequestBtn');
    
    if (requestCountElement) {
        requestCountElement.textContent = selectedPlants.length;
    }
    
    // Update button color based on count
    if (viewRequestBtn) {
        if (selectedPlants.length > 0) {
            viewRequestBtn.classList.add('has-plants');
        } else {
            viewRequestBtn.classList.remove('has-plants');
        }
    }
}

// Handle add plant button click
function initAddPlantButtons() {
    // Skip for clients, admins, or non-authenticated users
    if (!isUserAuthenticated() || isClientUser() || isAdminUser()) {
        return;
    }

    document.querySelectorAll('.plant-action-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent card from toggling
            
            const plantId = this.getAttribute('data-plant-id');
            const plantName = this.getAttribute('data-plant-name');
            const plantCode = this.getAttribute('data-plant-code');
            const action = this.getAttribute('data-action');
            
            // Get measurements from data attributes (no longer editable)
            const height = this.getAttribute('data-height');
            const spread = this.getAttribute('data-spread');
            const spacing = this.getAttribute('data-spacing');
            
            if (action === 'add') {
                // Add plant to selection
                addPlantToSelection(plantId, plantName, plantCode, height, spread, spacing);
                
                // Change button to remove
                this.setAttribute('data-action', 'remove');
                this.classList.remove('btn-success');
                this.classList.add('btn-danger');
                this.innerHTML = '<i class="fas fa-minus"></i> Remove';
            } else {
                // Remove plant from selection
                removePlantFromSelection(plantId);
                
                // Change button back to add
                this.setAttribute('data-action', 'add');
                this.classList.remove('btn-danger');
                this.classList.add('btn-success');
                this.innerHTML = '<i class="fas fa-plus"></i> Add to Request';
            }
        });
    });
}

/**
 * Admin Edit modal + photo management
 */
function initAdminEditFeatures() {
    // Open Edit modal from icon button
    document.querySelectorAll('.edit-plant-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            prefillEditFormFromButton(this);
            const modal = new bootstrap.Modal(document.getElementById('editPlantModal'));
            modal.show();
        });
    });

    // Save edits
    const saveBtn = document.getElementById('saveEditPlant');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const id = document.getElementById('edit_plant_id').value;
            if (!id) return;

            const payload = {
                name: document.getElementById('edit_name').value,
                code: document.getElementById('edit_code').value || null,
                scientific_name: document.getElementById('edit_scientific_name').value || null,
                category: document.getElementById('edit_category').value || 'shrub',
                height_mm: document.getElementById('edit_height_mm').value || null,
                spread_mm: document.getElementById('edit_spread_mm').value || null,
                spacing_mm: document.getElementById('edit_spacing_mm').value || null
            };

            saveBtn.disabled = true;
            const originalHtml = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            fetch(`/display-plants/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.plant) {
                    // Update card UI inline
                    const card = document.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`)?.closest('.admin-plant-card');
                    if (card) {
                        // Also update wrapper data attributes so search/filter reflect immediately
                        const wrapper = card.closest('.plant-item');
                        if (wrapper) {
                            if (data.plant.category) wrapper.setAttribute('data-category', data.plant.category);
                            if (data.plant.name) wrapper.setAttribute('data-name', data.plant.name);
                        }
                        const title = card.querySelector('.card-title');
                        if (title) title.textContent = data.plant.name;
                        // Update details texts if present
                        const cat = card.querySelector('.section-content .value-text');
                        // Safer targeted updates
                        const detailContainer = card.querySelector('.info-section .section-content');
                        if (detailContainer) {
                            const ensurePara = (label, text, options = {}) => {
                                const { italic = false } = options;
                                const p = Array.from(detailContainer.querySelectorAll('p')).find(el => el.textContent.trim().toLowerCase().startsWith(label.toLowerCase()));
                                if (!text) {
                                    if (p) p.remove();
                                    return;
                                }
                                if (p) {
                                    const span = p.querySelector('.value-text, em.value-text');
                                    if (span) span.textContent = text;
                                } else {
                                    const newP = document.createElement('p');
                                    newP.innerHTML = `<small class="text-muted">${label}</small> ${italic ? '<em class="value-text"></em>' : '<span class="value-text"></span>'}`;
                                    const vt = newP.querySelector('.value-text, em.value-text');
                                    vt.textContent = text;
                                    const measurements = detailContainer.querySelector('.measurements');
                                    if (measurements) {
                                        detailContainer.insertBefore(newP, measurements);
                                    } else {
                                        detailContainer.appendChild(newP);
                                    }
                                }
                            };

                            const cap = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
                            ensurePara('Category:', cap(data.plant.category || ''));
                            ensurePara('Code:', data.plant.code || 'N/A');
                            ensurePara('Scientific Name:', data.plant.scientific_name || '', { italic: true });

                            // Measurements block
                            const anyMeas = !!(data.plant.height_mm || data.plant.spread_mm || data.plant.spacing_mm);
                            let measurements = detailContainer.querySelector('.measurements');
                            if (!measurements && anyMeas) {
                                measurements = document.createElement('div');
                                measurements.className = 'measurements mt-2';
                                measurements.innerHTML = '<ul class="list-unstyled mb-0"></ul>';
                                detailContainer.appendChild(measurements);
                            }
                            if (measurements && !anyMeas) {
                                measurements.remove();
                            }
                            if (measurements) {
                                const ul = measurements.querySelector('ul');
                                const ensureLi = (label, val) => {
                                    let li = Array.from(ul.querySelectorAll('li')).find(el => el.textContent.trim().startsWith(label));
                                    if (val) {
                                        if (!li) {
                                            li = document.createElement('li');
                                            li.innerHTML = `<small class="text-muted">${label}</small> <span class="value-text"></span>`;
                                            ul.appendChild(li);
                                        }
                                        const span = li.querySelector('.value-text');
                                        if (span) span.textContent = `${val} mm`;
                                    } else if (li) {
                                        li.remove();
                                    }
                                };
                                ensureLi('Height:', data.plant.height_mm);
                                ensureLi('Spread:', data.plant.spread_mm);
                                ensureLi('Spacing:', data.plant.spacing_mm);
                            }
                        }

                        // Update data-* on edit button for future edits
                        const editBtn = card.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`);
                        if (editBtn) {
                            editBtn.setAttribute('data-name', data.plant.name || '');
                            editBtn.setAttribute('data-code', data.plant.code || '');
                            editBtn.setAttribute('data-scientific-name', data.plant.scientific_name || '');
                            editBtn.setAttribute('data-category', data.plant.category || 'shrub');
                            editBtn.setAttribute('data-height-mm', data.plant.height_mm || '');
                            editBtn.setAttribute('data-spread-mm', data.plant.spread_mm || '');
                            editBtn.setAttribute('data-spacing-mm', data.plant.spacing_mm || '');
                        }

                        // Re-apply current category and search filters so visibility updates without page refresh
                        if (typeof applyCurrentFilters === 'function') {
                            applyCurrentFilters();
                        }
                    }

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('editPlantModal')).hide();
                } else {
                    alert((data && data.message) ? data.message : 'Failed to update plant.');
                }
            })
            .catch(err => {
                console.error('Error updating plant:', err);
                alert('Error updating plant.');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalHtml;
            });
        });
    }

    // Upload photo
    const uploadBtn = document.getElementById('editUploadPhoto');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            const fileInput = document.getElementById('edit_photo_file');
            const id = document.getElementById('edit_plant_id').value;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!fileInput || fileInput.files.length === 0) {
                alert('Please select a photo to upload.');
                return;
            }
            if (!id) return;

            const formData = new FormData();
            formData.append('plant_id', id);
            formData.append('photo', fileInput.files[0]);

            const originalHtml = uploadBtn.innerHTML;
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

            fetch('/display-plants/photo/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data && data.path) {
                    updateEditPhotoPreview(data.path);
                    // Update main card photo
                    const card = document.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`)?.closest('.admin-plant-card');
                    if (card) {
                        const container = card.querySelector('.plant-image-container');
                        if (container) {
                            let img = container.querySelector('.plant-main-photo');
                            const placeholder = container.querySelector('.no-photo-placeholder');
                            if (!img) {
                                img = document.createElement('img');
                                img.className = 'plant-main-photo';
                                img.alt = 'Plant Photo';
                                container.innerHTML = '';
                                container.appendChild(img);
                            }
                            img.src = `/storage/${data.path}`;
                            if (placeholder) placeholder.remove();
                        }
                        // Update button dataset
                        const btn = card.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`);
                        if (btn) btn.setAttribute('data-photo-path', data.path);
                    }
                } else {
                    alert('Upload failed.');
                }
            })
            .catch(err => {
                console.error('Upload error:', err);
                alert('Error uploading photo.');
            })
            .finally(() => {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = originalHtml;
                // Clear selected file
                fileInput.value = '';
            });
        });
    }

    // Remove photo
    const removeBtn = document.getElementById('editRemovePhoto');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const id = document.getElementById('edit_plant_id').value;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            if (!id) return;
            if (!confirm('Remove current photo?')) return;

            const originalHtml = removeBtn.innerHTML;
            removeBtn.disabled = true;
            removeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Removing...';

            fetch(`/display-plants/photo/remove/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                updateEditPhotoPreview(null);
                // Update card
                const card = document.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`)?.closest('.admin-plant-card');
                if (card) {
                    const container = card.querySelector('.plant-image-container');
                    if (container) {
                        const img = container.querySelector('.plant-main-photo');
                        if (img) img.remove();
                        let placeholder = container.querySelector('.no-photo-placeholder');
                        if (!placeholder) {
                            placeholder = document.createElement('div');
                            placeholder.className = 'no-photo-placeholder';
                            placeholder.innerHTML = '<i class="fas fa-image"></i><p class="small">No Photo Available</p>';
                            container.appendChild(placeholder);
                        }
                        placeholder.classList.remove('d-none');
                    }
                    const btn = card.querySelector(`.edit-plant-btn[data-plant-id="${id}"]`);
                    if (btn) btn.setAttribute('data-photo-path', '');
                }
            })
            .catch(err => {
                console.error('Remove photo error:', err);
                alert('Error removing photo.');
            })
            .finally(() => {
                removeBtn.disabled = false;
                removeBtn.innerHTML = originalHtml;
            });
        });
    }
}

function prefillEditFormFromButton(btn) {
    const d = (name) => btn.getAttribute(name) || '';
    document.getElementById('edit_plant_id').value = d('data-plant-id');
    document.getElementById('edit_name').value = d('data-name');
    document.getElementById('edit_code').value = d('data-code');
    document.getElementById('edit_scientific_name').value = d('data-scientific-name');
    document.getElementById('edit_category').value = d('data-category') || 'shrub';
    document.getElementById('edit_height_mm').value = d('data-height-mm');
    document.getElementById('edit_spread_mm').value = d('data-spread-mm');
    document.getElementById('edit_spacing_mm').value = d('data-spacing-mm');

    const photoPath = d('data-photo-path');
    updateEditPhotoPreview(photoPath);
}

function updateEditPhotoPreview(photoPath) {
    const img = document.getElementById('edit_current_photo');
    const placeholder = document.getElementById('edit_no_photo');
    if (!img || !placeholder) return;
    if (photoPath) {
        img.src = photoPath.startsWith('http') ? photoPath : `/storage/${photoPath}`;
        img.classList.remove('d-none');
        placeholder.classList.add('d-none');
    } else {
        img.classList.add('d-none');
        placeholder.classList.remove('d-none');
    }
}

// Add plant to selection
function addPlantToSelection(plantId, plantName, plantCode, height, spread, spacing) {
    // Convert plantId to integer to ensure consistency
    plantId = parseInt(plantId);
    
    console.log('Adding plant to selection:', {
        originalId: arguments[0],
        convertedId: plantId,
        name: plantName
    });
    
    // Check if already at maximum plants
    if (selectedPlants.length >= MAX_PLANTS) {
        alert(`You can only select up to ${MAX_PLANTS} plants for a request.`);
        return;
    }
    
    // Check if plant already exists in selection
    const existingIndex = selectedPlants.findIndex(p => p.id === plantId);
    
    if (existingIndex >= 0) {
        // Update existing plant
        selectedPlants[existingIndex].height = height;
        selectedPlants[existingIndex].spread = spread;
        selectedPlants[existingIndex].spacing = spacing;
        showToast(`Updated measurements for ${plantName}`);
    } else {
        // Add new plant
        selectedPlants.push({
            id: plantId,
            name: plantName,
            code: plantCode,
            height: height,
            spread: spread,
            spacing: spacing,
            quantity: 1
        });
        showToast(`Added ${plantName} to your request`);
    }
    
    console.log('Selected plants after add:', selectedPlants);
    
    // Save to session storage
    saveSelectedPlants();
    
    // Update counter
    updatePlantCounter();
    
    // Update the button state for this plant
    updatePlantActionButtons(plantId, 'remove');
}

// Remove plant from selection
function removePlantFromSelection(plantId) {
    // Convert to integer to ensure consistency
    plantId = parseInt(plantId);
    
    console.log('removePlantFromSelection called with plantId:', plantId);
    console.log('Current selectedPlants:', selectedPlants.map(p => ({ id: p.id, name: p.name })));
    
    // Find and remove from array
    const index = selectedPlants.findIndex(p => parseInt(p.id) === plantId);
    
    console.log('Found index:', index);
    
    if (index !== -1) {
        const plantName = selectedPlants[index].name;
        selectedPlants.splice(index, 1);
        saveSelectedPlants();
        updatePlantCounter();
        showToast(`Removed ${plantName} from your request`);
        console.log('Plant removed successfully. New selectedPlants:', selectedPlants);
    } else {
        console.log('Plant not found in selectedPlants array');
    }
    
    // Update the button state for this plant
    updatePlantActionButtons(plantId, 'add');
}

// Update all action buttons for a specific plant
function updatePlantActionButtons(plantId, action) {
    document.querySelectorAll(`.plant-action-btn[data-plant-id="${plantId}"]`).forEach(btn => {
        btn.setAttribute('data-action', action);
        
        if (action === 'add') {
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="fas fa-plus"></i> Add to Request';
        } else {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-danger');
            btn.innerHTML = '<i class="fas fa-minus"></i> Remove';
        }
    });
}

// View selected plants (opens request form)
function viewSelectedPlants() {
    if (selectedPlants.length === 0) {
        alert('You have not selected any plants yet.');
        return;
    }
    
    // Open the modal instead of navigating
    populateRequestFormModal();
    const requestModal = new bootstrap.Modal(document.getElementById('requestFormModal'));
    requestModal.show();
}

// Populate the request form modal with selected plants
function populateRequestFormModal() {
    const tableBody = document.getElementById('modalPlantsTableBody');
    const emptySelection = document.getElementById('modalEmptySelection');
    const submitButton = document.getElementById('modalSubmitButton');
    
    // Clear the current table
    tableBody.innerHTML = '';
    
    // Update UI based on selection
    if (selectedPlants.length === 0) {
        emptySelection.classList.remove('d-none');
        submitButton.disabled = true;
    } else {
        emptySelection.classList.add('d-none');
        submitButton.disabled = false;
        
        // Populate table with selected plants
        selectedPlants.forEach((plant, index) => {
            const row = document.createElement('tr');
            // Ensure ID is an integer
            const plantId = parseInt(plant.id);
            row.dataset.id = plantId;
            
            row.innerHTML = `
                <td>${plant.name}
                    <input type="hidden" name="plants[${index}][id]" value="${plantId}">
                    <input type="hidden" name="plants[${index}][name]" value="${plant.name}">
                </td>
                <td>${plant.code || 'N/A'}</td>
                <td>
                    <input type="number" class="form-control form-control-sm modal-qty-input" 
                           name="plants[${index}][quantity]" 
                           value="${plant.quantity}" 
                           min="1" 
                           max="100"
                           required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="plants[${index}][height]" 
                           value="${plant.height || ''}" 
                           placeholder="mm">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="plants[${index}][spread]" 
                           value="${plant.spread || ''}" 
                           placeholder="mm">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" 
                           name="plants[${index}][spacing]" 
                           value="${plant.spacing || ''}" 
                           placeholder="mm">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger modal-remove-plant" data-id="${plantId}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Initialize event listeners for the modal's remove buttons
        initModalRemoveButtons();
        
        // Initialize quantity input handlers in the modal
        initModalQuantityInputs();
    }
}

// Handle remove plant button in the modal
function initModalRemoveButtons() {
    document.querySelectorAll('.modal-remove-plant').forEach(button => {
        button.addEventListener('click', function() {
            const plantId = parseInt(this.getAttribute('data-id'));
            const row = this.closest('tr');
            
            console.log('Removing plant from modal:', plantId);
            console.log('Selected plants before removal:', selectedPlants);
            
            // Remove from selectedPlants array FIRST
            removePlantFromSelection(plantId);
            
            console.log('Selected plants after removal:', selectedPlants);
            
            // Remove from UI
            row.remove();
            
            // Show empty state if no plants left
            if (selectedPlants.length === 0) {
                document.getElementById('modalEmptySelection').classList.remove('d-none');
                document.getElementById('modalSubmitButton').disabled = true;
            }
            
            // Update quantity input names to maintain consecutive indexing
            document.querySelectorAll('#modalPlantsTableBody tr').forEach((row, index) => {
                row.querySelectorAll('input[name*="plants["]').forEach(input => {
                    const name = input.name;
                    const newName = name.replace(/plants\[\d+\]/, `plants[${index}]`);
                    input.name = newName;
                });
            });
            
            // Update UI buttons for this plant
            updatePlantActionButtons(plantId, 'add');
        });
    });
}

// Initialize quantity inputs in the modal// Initialize quantity inputs in the modal
function initModalQuantityInputs() {
    document.querySelectorAll('.modal-qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const plantId = this.closest('tr').dataset.id;
            const newQty = parseInt(this.value);
            
            // Update in selectedPlants array
            const index = selectedPlants.findIndex(p => p.id === plantId);
            if (index !== -1) {
                selectedPlants[index].quantity = newQty;
                saveSelectedPlants();
            }
        });
    });
}

// Handle the modal's submit button
function initModalSubmitButton() {
    const submitButton = document.getElementById('modalSubmitButton');
    const modal = document.getElementById('requestFormModal');
    
    if (!submitButton) return;
    
    // Add event listener for when modal is hidden (closed)
    if (modal) {
        modal.addEventListener('hidden.bs.modal', function() {
            // Update counter and button states when modal closes
            updatePlantCounter();
            
            // Update all plant action buttons based on current selection
            const allPlantIds = Array.from(document.querySelectorAll('.plant-action-btn')).map(btn => 
                parseInt(btn.getAttribute('data-plant-id'))
            );
            
            allPlantIds.forEach(plantId => {
                const isSelected = selectedPlants.some(p => parseInt(p.id) === plantId);
                updatePlantActionButtons(plantId, isSelected ? 'remove' : 'add');
            });
        });
    }
    
    submitButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        const form = document.getElementById('modalRequestForm');
        
        // Validate form
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        if (selectedPlants.length === 0) {
            alert('Please select at least one plant for your request.');
            return;
        }
        
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Submit the form through AJAX
        const formData = new FormData(form);
        
        // Show domino loading
        LoadingManager.show('Submitting Your Request...', 'Please wait while we process your plant request');
        
        // Show loading state on button
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        fetch('/request-form', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response received:', response);
            console.log('Response status:', response.status);
            console.log('Response content-type:', response.headers.get('content-type'));
            
            // Check if response is OK (status 200-299)
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Check if response is JSON or HTML
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    console.log('JSON data received:', data);
                    return { success: data.success, message: data.message, isJson: true };
                });
            } else {
                console.log('Non-JSON response, redirecting to:', response.url);
                // If it's HTML, that means it's a redirect
                window.location.href = response.url;
                return { success: true, isJson: false };
            }
        })
        .then(data => {
            console.log('Processing data:', data);
            
            if (data && data.isJson) {
                if (data.success) {
                    // Hide loading
                    LoadingManager.hide();
                    
                    // Show success message with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Submitted!',
                        html: '<p>Your plant request has been submitted successfully!</p><p>A confirmation email has been sent to your email address.</p>',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        // Store the plant IDs to update their buttons
                        const plantIds = selectedPlants.map(plant => plant.id);
                        
                        // Clear selected plants
                        selectedPlants = [];
                        saveSelectedPlants();
                        updatePlantCounter();
                        
                        // Reset all plant action buttons to "Add"
                        plantIds.forEach(id => {
                            updatePlantActionButtons(id, 'add');
                        });
                        
                        // Close the modal
                        bootstrap.Modal.getInstance(document.getElementById('requestFormModal')).hide();
                        
                        // Reset button state
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
                    });
                } else {
                    // Hide loading
                    LoadingManager.hide();
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred while submitting your request.',
                        confirmButtonColor: '#dc3545'
                    });
                    
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
                }
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            
            // Hide loading
            LoadingManager.hide();
            
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'An error occurred while submitting your request. Please try again.',
                confirmButtonColor: '#dc3545'
            });
            
            // Reset button state
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
        });
    });
}

// Show toast notification
function showToast(message) {
    // Create toast if it doesn't exist
    if (!document.getElementById('toast')) {
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Initialize editable measurements
function initEditableMeasurements() {
    document.querySelectorAll('.editable-measurement').forEach(input => {
        // Restore original value when escape key is pressed
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = this.getAttribute('data-original');
                this.blur();
            }
        });
    });
}

// Save selected plants to session storage
function saveSelectedPlants() {
    sessionStorage.setItem('selectedPlants', JSON.stringify(selectedPlants));
}

// Load selected plants from session storage
function loadSelectedPlants() {
    const saved = sessionStorage.getItem('selectedPlants');
    if (saved) {
        selectedPlants = JSON.parse(saved);
        
        // Convert all IDs to integers to fix validation issues
        selectedPlants = selectedPlants.map(plant => ({
            ...plant,
            id: parseInt(plant.id)
        }));
        
        // Save the cleaned data back to sessionStorage
        saveSelectedPlants();
    }
}

// Add a function to clear plants on logout
function clearPlantsOnLogout() {
    // Find logout links
    document.querySelectorAll('a[href*="logout"]').forEach(link => {
        link.addEventListener('click', function(e) {
            // Clear selected plants from session storage
            sessionStorage.removeItem('selectedPlants');
            selectedPlants = [];
        });
    });
    
    // Also clear plants if user is not authenticated
    if (!isUserAuthenticated() && sessionStorage.getItem('selectedPlants')) {
        sessionStorage.removeItem('selectedPlants');
        selectedPlants = [];
    }
}
