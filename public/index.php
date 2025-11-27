<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritmes Tester</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>🔬 Algoritmes Tester</h1>
            <p class="subtitle">Test verschillende algoritmes met datasets</p>
        </header>

        <main>
            <section class="control-panel">
                <div class="panel dataset-panel">
                    <h2>📊 Dataset</h2>
                    <div class="form-group">
                        <label for="dataset-select">Kies een dataset:</label>
                        <select id="dataset-select">
                            <optgroup label="ListDataset">
                                <option value="integers">Integers (10 items)</option>
                                <option value="strings">Strings (10 items)</option>
                                <option value="sorted_integers">Gesorteerde Integers</option>
                                <option value="sorted_strings">Gesorteerde Strings</option>
                                <option value="duplicates">Array met Duplicaten</option>
                                <option value="large_integers">Grote Integer Dataset</option>
                            </optgroup>
                            <optgroup label="PrimeDataset">
                                <option value="primes_100">Eerste 100 Priemgetallen</option>
                                <option value="primes_1000">Eerste 1000 Priemgetallen</option>
                            </optgroup>
                            <optgroup label="Custom">
                                <option value="custom">Eigen invoer...</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group" id="custom-input-group" style="display: none;">
                        <label for="custom-data">Voer data in (komma-gescheiden):</label>
                        <textarea id="custom-data" placeholder="1, 5, 3, 8, 2 of appel, banaan, peer"></textarea>
                    </div>

                    <div class="form-group" id="size-input-group" style="display: none;">
                        <label for="dataset-size">Aantal items:</label>
                        <input type="number" id="dataset-size" min="10" max="10000" value="1000">
                    </div>

                    <button id="load-dataset" class="btn btn-primary">Dataset Laden</button>
                </div>

                <div class="panel algorithm-panel">
                    <h2>⚡ Algoritme</h2>
                    <div class="form-group">
                        <label for="algorithm-select">Kies een algoritme:</label>
                        <select id="algorithm-select">
                            <optgroup label="Sorteren">
                                <option value="quicksort">QuickSort</option>
                                <option value="mergesort">MergeSort</option>
                            </optgroup>
                            <optgroup label="Zoeken">
                                <option value="binarysearch">Binary Search</option>
                                <option value="binarysearch_left">Binary Search (Leftmost)</option>
                                <option value="binarysearch_right">Binary Search (Rightmost)</option>
                            </optgroup>
                            <optgroup label="Data Structuren">
                                <option value="arraylist">ArrayList</option>
                                <option value="linkedlist">LinkedList</option>
                                <option value="priorityqueue">PriorityQueue</option>
                                <option value="hashtable">HashTable</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group" id="search-target-group" style="display: none;">
                        <label for="search-target">Zoekwaarde:</label>
                        <input type="text" id="search-target" placeholder="Voer zoekwaarde in">
                    </div>

                    <button id="run-algorithm" class="btn btn-success" disabled>Uitvoeren</button>
                </div>
            </section>

            <section class="results-panel">
                <div class="panel">
                    <h2>📈 Resultaten</h2>

                    <div class="stats-bar">
                        <div class="stat">
                            <span class="stat-label">Dataset grootte:</span>
                            <span class="stat-value" id="stat-size">-</span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Uitvoertijd:</span>
                            <span class="stat-value" id="stat-time">-</span>
                        </div>
                        <div class="stat">
                            <span class="stat-label">Algoritme:</span>
                            <span class="stat-value" id="stat-algorithm">-</span>
                        </div>
                    </div>

                    <div class="visualization-container">
                        <div class="visualization" id="visualization">
                            <p class="placeholder">Laad een dataset om te beginnen</p>
                        </div>
                    </div>

                    <div class="data-preview">
                        <div class="preview-section">
                            <h3>Input Data</h3>
                            <div class="data-box" id="input-data">
                                <p class="placeholder">Geen data geladen</p>
                            </div>
                        </div>
                        <div class="preview-section">
                            <h3>Output Data</h3>
                            <div class="data-box" id="output-data">
                                <p class="placeholder">Voer een algoritme uit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>Algoritmes Tester &copy; 2025 | QuickSort, MergeSort, BinarySearch, en meer</p>
        </footer>
    </div>

    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <p>Bezig met verwerken...</p>
    </div>

    <script src="js/app.js"></script>
</body>

</html>