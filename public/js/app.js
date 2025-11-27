/**
 * Algoritmes Tester - Main JavaScript Application
 */
class AlgorithmTester {
    constructor() {
        this.apiUrl = 'api.php';
        this.currentData = [];
        this.currentDataType = 'numeric';
        this.isDataLoaded = false;

        this.init();
    }

    init() {
        this.bindElements();
        this.bindEvents();
        this.updateUIState();
    }

    bindElements() {
        // Dataset controls
        this.datasetSelect = document.getElementById('dataset-select');
        this.customInputGroup = document.getElementById('custom-input-group');
        this.customDataInput = document.getElementById('custom-data');
        this.sizeInputGroup = document.getElementById('size-input-group');
        this.datasetSizeInput = document.getElementById('dataset-size');
        this.loadDatasetBtn = document.getElementById('load-dataset');

        // Algorithm controls
        this.algorithmSelect = document.getElementById('algorithm-select');
        this.searchTargetGroup = document.getElementById('search-target-group');
        this.searchTargetInput = document.getElementById('search-target');
        this.runAlgorithmBtn = document.getElementById('run-algorithm');

        // Results
        this.visualization = document.getElementById('visualization');
        this.inputDataBox = document.getElementById('input-data');
        this.outputDataBox = document.getElementById('output-data');
        this.statSize = document.getElementById('stat-size');
        this.statTime = document.getElementById('stat-time');
        this.statAlgorithm = document.getElementById('stat-algorithm');

        // Loading overlay
        this.loadingOverlay = document.getElementById('loading-overlay');
    }

    bindEvents() {
        // Dataset selection change
        this.datasetSelect.addEventListener('change', () => this.onDatasetChange());

        // Algorithm selection change
        this.algorithmSelect.addEventListener('change', () => this.onAlgorithmChange());

        // Load dataset button
        this.loadDatasetBtn.addEventListener('click', () => this.loadDataset());

        // Run algorithm button
        this.runAlgorithmBtn.addEventListener('click', () => this.runAlgorithm());
    }

    onDatasetChange() {
        const value = this.datasetSelect.value;

        // Show/hide custom input
        this.customInputGroup.style.display = value === 'custom' ? 'block' : 'none';

        // Show/hide size input for large datasets
        this.sizeInputGroup.style.display = value === 'large_integers' ? 'block' : 'none';
    }

    onAlgorithmChange() {
        const value = this.algorithmSelect.value;

        // Show/hide search target input
        const isSearch = value.startsWith('binarysearch');
        this.searchTargetGroup.style.display = isSearch ? 'block' : 'none';
    }

    updateUIState() {
        this.runAlgorithmBtn.disabled = !this.isDataLoaded;
    }

    showLoading() {
        this.loadingOverlay.style.display = 'flex';
    }

    hideLoading() {
        this.loadingOverlay.style.display = 'none';
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    async loadDataset() {
        const type = this.datasetSelect.value;
        const size = this.datasetSizeInput.value;

        this.showLoading();

        try {
            let url = `${this.apiUrl}?action=get_dataset&type=${type}&size=${size}`;

            let options = { method: 'GET' };

            // Handle custom data
            if (type === 'custom') {
                const customData = this.customDataInput.value.trim();
                if (!customData) {
                    throw new Error('Voer eerst custom data in');
                }

                const formData = new FormData();
                formData.append('custom_data', customData);

                options = {
                    method: 'POST',
                    body: formData
                };
            }

            const response = await fetch(url, options);
            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            this.currentData = result.data;
            this.currentDataType = result.dataType;
            this.isDataLoaded = true;

            // Update UI
            this.statSize.textContent = result.count;
            this.statTime.textContent = '-';
            this.statAlgorithm.textContent = '-';

            // Render data
            this.renderInputData(result.data);
            this.renderVisualization(result.data);
            this.outputDataBox.innerHTML = '<p class="placeholder">Voer een algoritme uit</p>';

            this.updateUIState();
            this.showToast(`Dataset geladen: ${result.count} items`, 'success');

        } catch (error) {
            this.showToast(error.message, 'error');
        } finally {
            this.hideLoading();
        }
    }

    async runAlgorithm() {
        if (!this.isDataLoaded) {
            this.showToast('Laad eerst een dataset', 'error');
            return;
        }

        const algorithm = this.algorithmSelect.value;
        const target = this.searchTargetInput.value;

        // Validate search target for binary search
        if (algorithm.startsWith('binarysearch') && !target) {
            this.showToast('Voer een zoekwaarde in', 'error');
            return;
        }

        this.showLoading();

        try {
            const payload = {
                algorithm: algorithm,
                data: this.currentData,
                target: this.currentDataType === 'numeric' ? parseFloat(target) : target
            };

            const response = await fetch(`${this.apiUrl}?action=run_algorithm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (result.error) {
                throw new Error(result.error);
            }

            // Update stats
            this.statTime.textContent = result.executionTimeFormatted;
            this.statAlgorithm.textContent = result.details.algorithm;

            // Render results based on algorithm type
            this.renderResults(algorithm, result);

            this.showToast(`Algoritme uitgevoerd in ${result.executionTimeFormatted}`, 'success');

        } catch (error) {
            this.showToast(error.message, 'error');
        } finally {
            this.hideLoading();
        }
    }

    renderInputData(data) {
        const maxDisplay = 100;
        const displayData = data.slice(0, maxDisplay);

        let html = '<div class="data-items">';
        displayData.forEach(item => {
            html += `<span class="data-item">${this.escapeHtml(String(item))}</span>`;
        });
        if (data.length > maxDisplay) {
            html += `<span class="data-item">... en ${data.length - maxDisplay} meer</span>`;
        }
        html += '</div>';

        this.inputDataBox.innerHTML = html;
    }

    renderVisualization(data, highlightIndex = -1) {
        // Only visualize numeric data as bars
        if (this.currentDataType !== 'numeric' || data.length === 0) {
            this.visualization.innerHTML = '<p class="placeholder">Visualisatie beschikbaar voor numerieke data</p>';
            return;
        }

        const maxValue = Math.max(...data);
        const maxBars = 200; // Limit number of bars for performance
        const displayData = data.length > maxBars ? data.slice(0, maxBars) : data;

        let html = '';
        displayData.forEach((value, index) => {
            const height = (value / maxValue) * 160 + 10; // Min height 10px, max 170px
            const isHighlight = index === highlightIndex;
            html += `<div class="bar ${isHighlight ? 'found' : ''}" 
                         style="height: ${height}px" 
                         title="${value}"></div>`;
        });

        this.visualization.innerHTML = html;
    }

    renderResults(algorithm, result) {
        const { result: data, details } = result;

        // Render visualization based on algorithm type
        if (algorithm === 'quicksort' || algorithm === 'mergesort') {
            this.renderSortResults(data, details);
        } else if (algorithm.startsWith('binarysearch')) {
            this.renderSearchResults(data, details);
        } else if (algorithm === 'arraylist' || algorithm === 'linkedlist') {
            this.renderListResults(data, details);
        } else if (algorithm === 'priorityqueue') {
            this.renderQueueResults(data, details);
        } else if (algorithm === 'hashtable') {
            this.renderHashTableResults(data, details);
        }

        // Render algorithm details
        this.renderAlgorithmDetails(details);
    }

    renderSortResults(data, details) {
        // Update visualization with sorted data
        if (Array.isArray(data)) {
            this.renderVisualization(data);
            this.renderOutputData(data);
        }
    }

    renderSearchResults(data, details) {
        const { found, index, value } = data;

        // Highlight found index in visualization
        this.renderVisualization(this.currentData, found ? index : -1);

        // Render search result
        let html = `<div class="search-result ${found ? 'found' : 'not-found'}">`;
        if (found) {
            html += `
                <h4>✓ Gevonden!</h4>
                <p>Waarde <strong>${value}</strong> gevonden op index <strong>${index}</strong></p>
            `;
        } else {
            html += `
                <h4>✗ Niet gevonden</h4>
                <p>De zoekwaarde is niet aanwezig in de dataset</p>
            `;
        }
        html += '</div>';

        this.outputDataBox.innerHTML = html;
    }

    renderListResults(data, details) {
        const { items, count, type } = data;

        let html = `
            <div class="structure-display">
                <div class="structure-info">
                    <div class="structure-stat">
                        <div class="label">Aantal items</div>
                        <div class="value">${count}</div>
                    </div>
                    ${type ? `
                    <div class="structure-stat">
                        <div class="label">Type</div>
                        <div class="value">${type}</div>
                    </div>
                    ` : ''}
                </div>
                <div class="data-items">
        `;

        const maxDisplay = 50;
        const displayItems = items.slice(0, maxDisplay);
        displayItems.forEach(item => {
            html += `<span class="data-item">${this.escapeHtml(String(item))}</span>`;
        });
        if (items.length > maxDisplay) {
            html += `<span class="data-item">... en ${items.length - maxDisplay} meer</span>`;
        }

        html += '</div></div>';
        this.outputDataBox.innerHTML = html;
    }

    renderQueueResults(data, details) {
        const { ordered, description } = data;

        let html = `
            <div class="structure-display">
                <p style="margin-bottom: 16px; color: var(--text-secondary);">${description}</p>
                <div class="queue-items">
        `;

        const maxDisplay = 50;
        const displayItems = ordered.slice(0, maxDisplay);
        displayItems.forEach((item, index) => {
            html += `
                <div class="queue-item">
                    ${this.escapeHtml(String(item))}
                    <span class="priority">#${index + 1}</span>
                </div>
            `;
        });
        if (ordered.length > maxDisplay) {
            html += `<div class="queue-item">... +${ordered.length - maxDisplay}</div>`;
        }

        html += '</div></div>';
        this.outputDataBox.innerHTML = html;
    }

    renderHashTableResults(data, details) {
        const { keys, values, count, collisionStats } = data;

        let html = `
            <div class="structure-display">
                <div class="structure-info">
                    <div class="structure-stat">
                        <div class="label">Entries</div>
                        <div class="value">${count}</div>
                    </div>
                    <div class="structure-stat">
                        <div class="label">Bucket Count</div>
                        <div class="value">${collisionStats.bucketCount}</div>
                    </div>
                    <div class="structure-stat">
                        <div class="label">Load Factor</div>
                        <div class="value">${collisionStats.loadFactor.toFixed(2)}</div>
                    </div>
                    <div class="structure-stat">
                        <div class="label">Collisions</div>
                        <div class="value">${collisionStats.collisionCount}</div>
                    </div>
                </div>
                <div class="hashtable-display">
                    <table>
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        const maxDisplay = 20;
        for (let i = 0; i < Math.min(keys.length, maxDisplay); i++) {
            html += `
                <tr>
                    <td>${this.escapeHtml(String(keys[i]))}</td>
                    <td>${this.escapeHtml(String(values[i]))}</td>
                </tr>
            `;
        }
        if (keys.length > maxDisplay) {
            html += `
                <tr>
                    <td colspan="2" style="text-align: center;">... en ${keys.length - maxDisplay} meer entries</td>
                </tr>
            `;
        }

        html += '</tbody></table></div></div>';
        this.outputDataBox.innerHTML = html;
    }

    renderOutputData(data) {
        const maxDisplay = 100;
        const displayData = data.slice(0, maxDisplay);

        let html = '<div class="data-items">';
        displayData.forEach((item, index) => {
            html += `<span class="data-item highlight">${this.escapeHtml(String(item))}</span>`;
        });
        if (data.length > maxDisplay) {
            html += `<span class="data-item">... en ${data.length - maxDisplay} meer</span>`;
        }
        html += '</div>';

        this.outputDataBox.innerHTML = html;
    }

    renderAlgorithmDetails(details) {
        // Create or update details section
        let detailsSection = document.querySelector('.algorithm-details');
        if (!detailsSection) {
            detailsSection = document.createElement('div');
            detailsSection.className = 'algorithm-details';
            this.outputDataBox.parentNode.appendChild(detailsSection);
        }

        let html = `<h4>📚 Algoritme Details</h4><dl>`;
        for (const [key, value] of Object.entries(details)) {
            const label = key.charAt(0).toUpperCase() + key.slice(1).replace(/([A-Z])/g, ' $1');
            const displayValue = typeof value === 'boolean' ? (value ? 'Ja' : 'Nee') : value;
            html += `<dt>${label}:</dt><dd>${displayValue}</dd>`;
        }
        html += '</dl>';

        detailsSection.innerHTML = html;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize the application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.algorithmTester = new AlgorithmTester();
});
