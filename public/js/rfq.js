/**
 * RFQ (Request for Quotation) functionality for plant selection and submission
 */
document.addEventListener('DOMContentLoaded', function() {
    // Store the selected plants
    let selectedPlants = [];
    let userEmail = '';
    let userName = '';

    // Initialize date objects
    const today = new Date();
    const twoWeeksLater = new Date(today);
    twoWeeksLater.setDate(today.getDate() + 14);

    // Formatted dates for display
    const formattedToday = formatDate(today);
    const formattedDueDate = formatDate(twoWeeksLater);

    // Elements
    const requestPlantsBtn = document.getElementById('requestPlantsBtn');
    const requestPlantsModal = document.getElementById('requestPlantsModal');
    const selectPlantsBtn = document.getElementById('selectPlantsBtn');
    const rfqFormModal = document.getElementById('rfqFormModal');
    const sendRfqBtn = document.getElementById('submitRequest');
    const successModal = document.getElementById('successModal');

    // Initialize event listeners
    initEventListeners();

    // Initialize all event listeners
    function initEventListeners() {
        // Request Plants button
        if (requestPlantsBtn) {
            requestPlantsBtn.addEventListener('click', function() {
                if (requestPlantsModal) {
                    const modalInstance = new bootstrap.Modal(requestPlantsModal);
                    modalInstance.show();
                }
            });
        }

        // Select Plants button in the email modal
        if (selectPlantsBtn) {
            selectPlantsBtn.addEventListener('click', startSelectionMode);
        }

        // Send RFQ button in the RFQ form modal
        if (sendRfqBtn) {
            sendRfqBtn.addEventListener('click', submitRfqForm);
        }
    }

    // Start the plant selection mode
    function startSelectionMode() {
        // Get the email from the form
        userEmail = document.getElementById('requestEmail').value;
        
        // Validate email
        if (!userEmail || !isValidEmail(userEmail)) {
            alert('Please enter a valid email address.');
            return;
        }

        // Get the name if authenticated
        if (document.querySelector('.profile-btn span')) {
            userName = document.querySelector('.profile-btn span').textContent.trim();
        } else {
            userName = 'Guest User';
        }

        // Close the email modal
        const modalInstance = bootstrap.Modal.getInstance(requestPlantsModal);
        if (modalInstance) {
            modalInstance.hide();
        }

        console.log('Starting selection mode');
        
        // Reset selection state
        selectedPlants = [];
        
        // Remove selection mode class first (in case it's already there)
        document.body.classList.remove('plant-selection-mode');
        
        // Then add it back
        document.body.classList.add('plant-selection-mode');
        
        // Add selection mode UI
        setupSelectionMode();
    }

    // Set up the selection mode UI
    function setupSelectionMode() {
        console.log('Setting up selection mode');
        
        // Store original search controls
        const searchControlsContainer = document.querySelector('.search-controls-container');
        if (searchControlsContainer) {
            searchControlsContainer.setAttribute('data-original-content', searchControlsContainer.innerHTML);
        }
        
        // Replace with selection controls - preserve the existing search box
        const topControls = `
            <button id="sendPlantsBtn" class="btn btn-secondary me-2" disabled>
                <i class="fas fa-paper-plane me-1"></i>Send Plants (0)
            </button>
            <button id="cancelSelectionBtn" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i>Cancel
            </button>
        `;
        
        if (searchControlsContainer) {
            searchControlsContainer.innerHTML = topControls;
        }
        
        // Create selection bar to add below the search controls
        // Find the row that contains the search controls
        const searchRow = searchControlsContainer.closest('.row');
        if (searchRow) {
            // Create the selection header bar
            const selectionBar = document.createElement('div');
            selectionBar.id = 'selectionBar';
            selectionBar.className = 'selection-bar mt-3 mb-4';
            selectionBar.innerHTML = `
                <div class="selection-header">
                    <h2><i class="fas fa-leaf me-2"></i>Select Plants for Your Request</h2>
                    <p>Click on plants to select them for your RFQ</p>
                </div>
            `;
            
            // Insert after the search row
            searchRow.parentNode.insertBefore(selectionBar, searchRow.nextSibling);
        }
        
        // Setup cancel button
        const cancelBtn = document.getElementById('cancelSelectionBtn');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', cancelSelectionMode);
        }
        
        // Setup send button
        const sendBtn = document.getElementById('sendPlantsBtn');
        if (sendBtn) {
            sendBtn.addEventListener('click', showRfqForm);
            // Initially disabled
            sendBtn.disabled = true;
        }
        
        // Apply selection mode class to body
        document.body.classList.add('plant-selection-mode');
        
        // Reset the selectedPlants array
        selectedPlants = [];
        
        // Setup plant cards for selection
        setupPlantCards();
    }

    // Set up plant cards for selection
    function setupPlantCards() {
        // Only setup cards if in selection mode
        if (!document.body.classList.contains('plant-selection-mode')) {
            return;
        }
        
        // Look for both admin and user plant cards
        document.querySelectorAll('.admin-plant-card, .user-plant-card').forEach(card => {
            // IMPORTANT: For selection mode, we only want to handle checkbox clicks, not card clicks
            // This avoids conflict with the detail panel slide functionality
            
            // Create or get the selection checkbox
            let checkbox = card.querySelector('.selection-checkbox');
            
            // If checkbox doesn't exist, create it
            if (!checkbox) {
                // Create a selection checkbox
                checkbox = document.createElement('div');
                checkbox.className = 'selection-checkbox';
                checkbox.style.width = '30px';
                checkbox.style.height = '30px';
                checkbox.style.position = 'absolute';
                checkbox.style.top = '10px';
                checkbox.style.right = '10px';
                checkbox.style.borderRadius = '50%';
                checkbox.style.backgroundColor = 'white';
                checkbox.style.border = '2px solid #ddd';
                checkbox.style.display = 'flex';
                checkbox.style.alignItems = 'center';
                checkbox.style.justifyContent = 'center';
                checkbox.style.zIndex = '1000';
                checkbox.style.pointerEvents = 'auto';
                checkbox.style.cursor = 'pointer';
                checkbox.style.boxShadow = '0 2px 5px rgba(0,0,0,0.1)';
                checkbox.innerHTML = '<i class="fas fa-check" style="font-size: 16px;"></i>';
                card.appendChild(checkbox);
                
                // Add click listener to checkbox only (not the whole card)
                checkbox.addEventListener('click', e => {
                    // Stop event propagation
                    e.stopPropagation();
                    
                    // Toggle selection for this card
                    toggleCardSelection(card);
                });
            }
        });
    }

    // Toggle selection for a card
    function toggleCardSelection(card) {
        // Toggle the selected class
        card.classList.toggle('selected');
        
        // Check if selected after toggling
        const isSelected = card.classList.contains('selected');
        
        // Get plant data
        const plantData = extractPlantData(card);
        
        console.log('Plant toggled:', plantData.name, 'Selected:', isSelected, 'ID:', plantData.id);
        
        // Update selected plants array
        if (isSelected) {
            // Check if already in array first to avoid duplicates
            const existingIndex = selectedPlants.findIndex(p => p.id === plantData.id);
            if (existingIndex === -1) {
                selectedPlants.push(plantData);
                console.log('Added plant to selectedPlants array, now has', selectedPlants.length, 'items');
            }
            
            // Update visual style of the checkbox
            const checkbox = card.querySelector('.selection-checkbox');
            if (checkbox) {
                checkbox.style.backgroundColor = '#198754';
                checkbox.style.border = '2px solid #198754';
                checkbox.querySelector('i').style.color = 'white';
            }
            
            // Add border highlight - applied with !important
            card.style.setProperty('border', '2px solid #198754', 'important');
            card.style.setProperty('transform', 'scale(1.02)', 'important');
        } else {
            // Remove from array if exists
            const initialLength = selectedPlants.length;
            selectedPlants = selectedPlants.filter(p => p.id !== plantData.id);
            console.log('Removed plant from selectedPlants array, had', initialLength, 'now has', selectedPlants.length);
            
            // Update visual style of the checkbox
            const checkbox = card.querySelector('.selection-checkbox');
            if (checkbox) {
                checkbox.style.backgroundColor = 'white';
                checkbox.style.border = '2px solid #ddd';
                checkbox.querySelector('i').style.color = 'transparent';
            }
            
            // Remove border highlight - reset to default
            card.style.removeProperty('border');
            card.style.removeProperty('transform');
        }
        
        // Verify the array integrity
        console.log('Current selectedPlants array:', selectedPlants);
        
        // Update button text
        updateSelectionCount();
    }

    // Update the selection count in the button
    function updateSelectionCount() {
        const count = selectedPlants.length;
        const sendBtn = document.getElementById('sendPlantsBtn');
        
        if (sendBtn) {
            // Update text
            sendBtn.innerHTML = `<i class="fas fa-paper-plane me-1"></i>Send Plants (${count})`;
            
            // Update button state
            if (count > 0) {
                sendBtn.disabled = false;
                sendBtn.classList.remove('btn-secondary');
                sendBtn.classList.add('btn-success');
            } else {
                sendBtn.disabled = true;
                sendBtn.classList.remove('btn-success');
                sendBtn.classList.add('btn-secondary');
            }
            
            console.log('Updated selection count to:', count);
        }
    }

    // Cancel selection mode
    function cancelSelectionMode() {
        console.log('Canceling selection mode');
        
        // Remove selection mode class
        document.body.classList.remove('plant-selection-mode');
        
        // Clear selected plants
        selectedPlants = [];
        
        // Remove selection bar
        const selectionBar = document.getElementById('selectionBar');
        if (selectionBar) {
            selectionBar.remove();
        }
        
        // Restore original search controls
        const searchControlsContainer = document.querySelector('.search-controls-container');
        if (searchControlsContainer) {
            const originalContent = searchControlsContainer.getAttribute('data-original-content');
            if (originalContent) {
                searchControlsContainer.innerHTML = originalContent;
                
                // Re-attach event listener to the Request Plants button
                const requestPlantsBtn = document.getElementById('requestPlantsBtn');
                if (requestPlantsBtn) {
                    requestPlantsBtn.addEventListener('click', function() {
                        const modal = document.getElementById('requestPlantsModal');
                        if (modal) {
                            const modalInstance = new bootstrap.Modal(modal);
                            modalInstance.show();
                        }
                    });
                }
            }
        }
        
        // Remove selected class from all cards
        document.querySelectorAll('.admin-plant-card.selected, .user-plant-card.selected').forEach(card => {
            card.classList.remove('selected');
            // Also reset any inline styles
            card.style.removeProperty('border');
            card.style.removeProperty('transform');
        });
        
        // Remove all checkboxes
        document.querySelectorAll('.selection-checkbox').forEach(checkbox => {
            checkbox.remove();
        });
        
        // Remove all selection overlays
        document.querySelectorAll('.selection-overlay').forEach(overlay => {
            overlay.remove();
        });
    }

    // Show RFQ form
    function showRfqForm() {
        // Check if there are selected plants based on the DOM
        const selectedCards = document.querySelectorAll('.admin-plant-card.selected, .user-plant-card.selected');
        console.log('showRfqForm found', selectedCards.length, 'selected cards');
        
        if (selectedCards.length === 0) {
            alert('Please select at least one plant to continue.');
            return;
        }
        
        // Rebuild the selectedPlants array from the DOM to ensure it's accurate
        selectedPlants = [];
        selectedCards.forEach(card => {
            const plantData = extractPlantData(card);
            selectedPlants.push(plantData);
        });
        
        console.log('Rebuilt selectedPlants array with', selectedPlants.length, 'plants');
        
        // Show loading with LoadingManager
        if (typeof LoadingManager !== 'undefined') {
            LoadingManager.show('Preparing RFQ Form...', 'Please wait');
        }
        
        // Process selected plants and show form
        setTimeout(() => {
            // Populate the form
            populateRfqForm();
            
            // Hide loading
            if (typeof LoadingManager !== 'undefined') {
                LoadingManager.hide();
            }
            
            // Show the RFQ form modal
            showRfqModal();
            
            // DON'T exit selection mode yet - wait until form is submitted
            // The selectedPlants array needs to stay populated for submission
        }, 1000);
    }

    // Populate RFQ form
    function populateRfqForm() {
        // Set dates
        document.getElementById('rfqDate').textContent = formattedToday;
        document.getElementById('rfqDueDate').textContent = formattedDueDate;
        
        // Set user info
        document.getElementById('buyerName').textContent = userName;
        document.getElementById('buyerEmail').textContent = userEmail;
        
        // Populate items table
        const itemsTable = document.getElementById('rfqItemsTable');
        itemsTable.innerHTML = '';
        
        // Log the number of plants to be processed
        console.log(`Processing ${selectedPlants.length} plants for RFQ form`);
        
        // Use forEach to avoid any issues with large numbers of plants
        selectedPlants.forEach((plant, index) => {
            const row = document.createElement('tr');
            row.dataset.plantIndex = index; // Store the original index
            
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <input type="number" min="1" value="1" class="rfq-input" 
                        onchange="updateTotalPrice(this)">
                </td>
                <td style="text-align: left;">${plant.name}</td>
                <td style="text-align: left;">${plant.code || ''}</td>
                <td>
                    <input type="number" class="rfq-input height-field">
                </td>
                <td>
                    <input type="number" class="rfq-input spread-field">
                </td>
                <td>
                    <input type="number" class="rfq-input spacing-field">
                </td>
                <td>
                    <textarea class="rfq-textarea remarks-field" placeholder="Add remarks"></textarea>
                </td>
                <td>
                    <input type="number" class="rfq-input unit-price" value="" min="0" step="0.01"
                        onchange="updateTotalPrice(this)">
                </td>
                <td>
                    <input type="number" class="rfq-input total-price-input" value="" min="0" step="0.01">
                </td>
                <td>
                    <input type="text" class="rfq-input availability-field" value="">
                </td>
            `;
            
            // Append the row to the table
            itemsTable.appendChild(row);
            
            // Log for debug
            console.log(`Added plant row ${index + 1}: ${plant.name}`);
        });
        
        // Reset scroll position of the table
        const tableWrapper = document.querySelector('#rfqFormModal .rfq-table-wrapper');
        if (tableWrapper) {
            setTimeout(() => {
                tableWrapper.scrollTop = 0;
                tableWrapper.scrollLeft = 0;
                console.log('Reset table scroll position');
            }, 100);
        }
        
        // Add function to calculate total price
        window.updateTotalPrice = function(input) {
            const row = input.closest('tr');
            const quantity = parseFloat(row.querySelector('td:nth-child(2) input').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const totalPrice = (quantity * unitPrice).toFixed(2);
            
            // Update the total price input field
            const totalPriceInput = row.querySelector('.total-price-input');
            if (totalPriceInput && !totalPriceInput.hasAttribute('user-edited')) {
                totalPriceInput.value = totalPrice;
            }
        };
        
        // Flag when user manually edits total price
        document.querySelectorAll('.total-price-input').forEach((input) => {
            input.addEventListener('input', function() {
                this.setAttribute('user-edited', 'true');
            });
        });
    }

    // Submit RFQ form with updated data
    let isSubmitting = false; // Flag to prevent duplicate submissions
    
    function submitRfqForm(event) {
        // Prevent default form submission if this is from a form
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        console.log('submitRfqForm called');
        console.log('selectedPlants array:', selectedPlants);
        console.log('selectedPlants length:', selectedPlants.length);
        
        // Prevent duplicate submissions
        if (isSubmitting) {
            console.log('Already submitting, ignoring duplicate click');
            return false;
        }
        
        // Check if we have plants to submit
        if (!selectedPlants || selectedPlants.length === 0) {
            console.error('No plants in selectedPlants array!');
            alert('No plants selected. Please try again.');
            return false;
        }
        
        isSubmitting = true;
        console.log('Starting submission process');
        
        // Disable the submit button to prevent double-clicks
        const submitBtn = document.getElementById('submitRequest');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        }
        
        // Show loading with LoadingManager
        if (typeof LoadingManager !== 'undefined') {
            LoadingManager.show('Submitting Request...', 'Please wait while we process your request');
        } else {
            console.warn('LoadingManager not found');
        }
        
        // Gather all data from form inputs
        const rows = document.querySelectorAll('#rfqItemsTable tr');
        const updatedPlants = [];
        
        console.log(`Found ${rows.length} rows in #rfqItemsTable`);
        console.log(`Processing ${rows.length} rows for submission`);
        
        try {
        // Process all rows, even if order is mixed
        rows.forEach((row) => {
            // Get the original plant data index
            const plantIndex = parseInt(row.dataset.plantIndex);
            const originalPlant = plantIndex >= 0 && plantIndex < selectedPlants.length ? 
                                  selectedPlants[plantIndex] : null;
            
            if (!originalPlant) {
                console.error(`Missing original plant data for row with index ${plantIndex}`);
                console.error(`selectedPlants array:`, selectedPlants);
                return;
            }
            
            // Get values from form fields
            const quantity = parseInt(row.querySelector('td:nth-child(2) input').value) || 1;
            const name = originalPlant.name;
            const code = originalPlant.code || '';
            
            // For the optional fields, get directly from the input
            const height = row.querySelector('.height-field').value || '';
            const spread = row.querySelector('.spread-field').value || '';
            const spacing = row.querySelector('.spacing-field').value || '';
            const remarks = row.querySelector('.remarks-field').value || '';
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const totalPrice = parseFloat(row.querySelector('.total-price-input').value) || 0;
            const availability = row.querySelector('.availability-field').value || '';
            
            // Add to the list of plants to submit
            updatedPlants.push({
                id: originalPlant.id,
                name: name,
                code: code,
                quantity: quantity,
                height: height,
                spread: spread,
                spacing: spacing,
                remarks: remarks,
                unit_price: unitPrice,
                total_price: totalPrice,
                availability: availability
            });
            
            console.log(`Processed plant: ${name}`);
        });
        
        console.log(`Submitting ${updatedPlants.length} plants`);
        console.log('User email:', userEmail);
        console.log('User name:', userName);
        
        // Prepare data for submission
        const requestData = {
            email: userEmail,
            name: userName,
            items_json: JSON.stringify(updatedPlants)
        };
        
        console.log('Request data:', requestData);
            
            // Set a timeout to ensure loading doesn't stay indefinitely
            const timeoutId = setTimeout(() => {
                if (typeof LoadingManager !== 'undefined') {
                    LoadingManager.hide();
                }
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Request';
                }
                alert('Request is taking longer than expected. Please try again.');
                isSubmitting = false;
            }, 15000); // 15-second timeout
        
        console.log('Sending fetch request to /client-request');
        
        // Submit via AJAX
        fetch('/client-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
                console.log('Received response:', response);
                // Clear the timeout since we got a response
                clearTimeout(timeoutId);
                
            if (!response.ok) {
                    console.error('Response not OK:', response.status, response.statusText);
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response data:', data);
            // Reset submission flag
            isSubmitting = false;
            
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Request';
            }
            
            // Hide loading
            if (typeof LoadingManager !== 'undefined') {
                LoadingManager.hide();
            }
            
            // Close the RFQ form modal
            closeRfqModal();
            
            // NOW exit selection mode and clear the array
            cancelSelectionMode();
            
            // Show success message
            const successModalElement = document.getElementById('successModal');
            if (successModalElement) {
                // Set success message
                const messageContainer = successModalElement.querySelector('.success-message');
                if (messageContainer) {
                    messageContainer.textContent = data.message || 'Your request has been submitted successfully!';
                }
                
                // Show the success modal
                const successModal = new bootstrap.Modal(successModalElement);
                successModal.show();
            } else {
                // Fallback to alert
                alert(data.message || 'Your request has been submitted successfully!');
            }
        })
        .catch(error => {
                console.error('Fetch error:', error);
                // Reset submission flag
                isSubmitting = false;
                
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Request';
                }
                
                // Clear the timeout since we got a response
                clearTimeout(timeoutId);
                
            // Hide loading
            if (typeof LoadingManager !== 'undefined') {
                LoadingManager.hide();
            }
            
            // Show error message
            alert('Error submitting request: ' + error.message);
            console.error('Error:', error);
        });
        } catch (error) {
            console.error('Try-catch error:', error);
            // Reset submission flag
            isSubmitting = false;
            
            // Handle any errors that might occur during data processing
            if (typeof LoadingManager !== 'undefined') {
                LoadingManager.hide();
            }
            alert('Error preparing request data: ' + error.message);
            console.error('Error preparing data:', error);
        }
    }

    // Helper functions
    function formatDate(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }

    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Extract plant data from card
    function extractPlantData(card) {
        // Basic plant info
        const plantName = card.querySelector('.card-title')?.textContent?.trim() || 'Unknown Plant';
        const plantId = card.getAttribute('data-id') || 'plant_' + Math.random().toString(36).substr(2, 9);
        
        // Default values
        let plantData = {
            id: plantId,
            name: plantName,
            quantity: 1,
            code: '',
            height: '',
            spread: '',
            spacing: ''
        };
        
        // Try to extract details from the details panel
        const detailsPanel = card.querySelector('.plant-details-panel');
        if (detailsPanel) {
            console.log('Found details panel for', plantName);
            
            // Look for code - try different ways of finding it
            const codeElement = detailsPanel.querySelector('[data-field="code"]');
            if (codeElement) {
                const codeText = codeElement.textContent.trim();
                const codeMatch = codeText.match(/Code:\s*(.*)/);
                if (codeMatch && codeMatch[1]) {
                    plantData.code = codeMatch[1].trim();
                    console.log('Found code:', plantData.code);
                }
            }
            
            // Extract measurements if available
            // Try to find height information
            const heightElement = detailsPanel.querySelector('[data-field="height_mm"]');
            if (heightElement) {
                const heightText = heightElement.textContent.trim();
                const heightMatch = heightText.match(/Height:\s*([0-9]+)/);
                if (heightMatch && heightMatch[1]) {
                    plantData.height = heightMatch[1].trim();
                    console.log('Found height:', plantData.height);
                }
            }
            
            // Try to find spread information
            const spreadElement = detailsPanel.querySelector('[data-field="spread_mm"]');
            if (spreadElement) {
                const spreadText = spreadElement.textContent.trim();
                const spreadMatch = spreadText.match(/Spread:\s*([0-9]+)/);
                if (spreadMatch && spreadMatch[1]) {
                    plantData.spread = spreadMatch[1].trim();
                    console.log('Found spread:', plantData.spread);
                }
            }
            
            // Try to find spacing information
            const spacingElement = detailsPanel.querySelector('[data-field="spacing_mm"]');
            if (spacingElement) {
                const spacingText = spacingElement.textContent.trim();
                const spacingMatch = spacingText.match(/Spacing:\s*([0-9]+)/);
                if (spacingMatch && spacingMatch[1]) {
                    plantData.spacing = spacingMatch[1].trim();
                    console.log('Found spacing:', plantData.spacing);
                }
            }
            
            // Alternative approach: look for measurement elements more broadly
            if (!plantData.height || !plantData.spread || !plantData.spacing) {
                console.log('Trying alternative approach for measurements');
                
                // Look for elements containing measurement info
                const measurementItems = detailsPanel.querySelectorAll('.measurements li');
                measurementItems.forEach(item => {
                    const text = item.textContent.trim();
                    
                    if (text.includes('Height:') && !plantData.height) {
                        const match = text.match(/Height:\s*([0-9]+)/);
                        if (match && match[1]) {
                            plantData.height = match[1].trim();
                            console.log('Found height (alt):', plantData.height);
                        }
                    }
                    
                    if (text.includes('Spread:') && !plantData.spread) {
                        const match = text.match(/Spread:\s*([0-9]+)/);
                        if (match && match[1]) {
                            plantData.spread = match[1].trim();
                            console.log('Found spread (alt):', plantData.spread);
                        }
                    }
                    
                    if (text.includes('Spacing:') && !plantData.spacing) {
                        const match = text.match(/Spacing:\s*([0-9]+)/);
                        if (match && match[1]) {
                            plantData.spacing = match[1].trim();
                            console.log('Found spacing (alt):', plantData.spacing);
                        }
                    }
                });
            }
            
            // Super alternative approach: just look for any element with the text
            if (!plantData.code) {
                const allElements = detailsPanel.querySelectorAll('p, span, div');
                for (const element of allElements) {
                    const text = element.textContent.trim();
                    if (text.includes('Code:')) {
                        const match = text.match(/Code:\s*(.*)/);
                        if (match && match[1]) {
                            plantData.code = match[1].trim();
                            console.log('Found code (alt):', plantData.code);
                            break;
                        }
                    }
                }
            }
        } else {
            console.log('No details panel found for', plantName);
        }
        
        // Log the extracted data
        console.log('Extracted plant data:', plantData);
        
        return plantData;
    }
}); 

    // Custom modal functions for RFQ
    window.showRfqModal = function() {
        const modal = document.getElementById('rfqFormModal');
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeRfqModal = function() {
        const modal = document.getElementById('rfqFormModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    };

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('rfqFormModal');
        if (modal && event.target === modal) {
            closeRfqModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('rfqFormModal');
            if (modal && modal.style.display === 'block') {
                closeRfqModal();
            }
        }
    });