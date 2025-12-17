<?php
// Initialisation variables
$trip_type_selected = $criteria['trip_type'] ?? 'round_trip';
$departure_value = htmlspecialchars($criteria['departure'] ?? '');
$arrival_value = htmlspecialchars($criteria['arrival'] ?? '');
$departure_date_value = htmlspecialchars($criteria['departure_date'] ?? '');
$return_date_value = htmlspecialchars($criteria['return_date'] ?? '');
$passengers_value = htmlspecialchars($criteria['passengers'] ?? 1);
$travel_class_selected = $criteria['travel_class'] ?? 'economy';
?>

<div class="bg-white p-8 rounded-xl shadow-lg">
    <div class="flex space-x-6 mb-6">
        <label class="inline-flex items-center text-gray-700">
            <input type="radio" name="trip_type_radio" id="round_trip_radio" value="round_trip" class="form-radio text-blue-600" <?php echo ($trip_type_selected === 'round_trip') ? 'checked' : ''; ?>>
            <span class="ml-2">Aller-retour</span>
        </label>
        <label class="inline-flex items-center text-gray-700">
            <input type="radio" name="trip_type_radio" id="one_way_radio" value="one_way" class="form-radio text-blue-600" <?php echo ($trip_type_selected === 'one_way') ? 'checked' : ''; ?>>
            <span class="ml-2">Aller Simple</span>
        </label>
    </div>

    <form action="index.php?action=search-flights" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="search-input-group col-span-1 md:col-span-2 lg:col-span-1">
            <label for="departure_airport" class="sr-only">Départ</label>
            <i class="fas fa-plane-departure search-input-icon"></i>
            <input type="text" id="departure_airport" name="departure" placeholder="Départ (Ville ou Aéroport)" class="search-input" required value="<?php echo $departure_value; ?>">
            <ul id="departure_airport-results" class="autocomplete-results hidden"></ul>
        </div>

        <div class="search-input-group col-span-1 md:col-span-2 lg:col-span-1">
            <label for="arrival_airport" class="sr-only">Arrivée</label>
            <i class="fas fa-plane-arrival search-input-icon"></i>
            <input type="text" id="arrival_airport" name="arrival" placeholder="Arrivée (Ville ou Aéroport)" class="search-input" required value="<?php echo $arrival_value; ?>">
            <ul id="arrival_airport-results" class="autocomplete-results hidden"></ul>
        </div>
        
        <div class="search-input-group col-span-1">
            <label for="departure_date" class="sr-only">Date Aller</label>
            
            <input type="date" id="departure_date" name="departure_date" placeholder="jj/mm/aaaa" class="search-input" required value="<?php echo $departure_date_value; ?>">
        </div>

        <div class="search-input-group col-span-1" id="return_date_group" style="<?php echo ($trip_type_selected === 'one_way') ? 'display: none;' : 'display: block;'; ?>">
            <label for="return_date" class="sr-only">Date Retour</label>
            
            <input type="date" id="return_date" name="return_date" placeholder="jj/mm/aaaa" class="search-input" value="<?php echo $return_date_value; ?>" <?php echo ($trip_type_selected === 'round_trip') ? 'required' : ''; ?>>
        </div>

        <div class="search-input-group col-span-1">
            <label for="passengers" class="sr-only">Passagers</label>
            <i class="fas fa-users search-input-icon"></i>
            <input type="number" id="passengers" name="passengers" min="1" class="search-input" required value="<?php echo $passengers_value; ?>">
        </div>

        <div class="search-input-group col-span-1">
            <label for="travel_class" class="sr-only">Classe</label>
            <i class="fas fa-chair search-input-icon"></i>
            <select id="travel_class" name="travel_class" class="search-input appearance-none bg-white pr-8" required>
                <option value="economy" <?php echo ($travel_class_selected === 'economy') ? 'selected' : ''; ?>>Économique</option>
                <option value="business" <?php echo ($travel_class_selected === 'business') ? 'selected' : ''; ?>>Affaires</option>
                <option value="first" <?php echo ($travel_class_selected === 'first') ? 'selected' : ''; ?>>Première</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 pointer-events-none"></i>
        </div>
        
        <input type="hidden" name="trip_type" id="hidden_trip_type" value="<?php echo $trip_type_selected; ?>">

        <div class="col-span-full flex justify-end">
            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-200 shadow-md">
                <i class="fas fa-search mr-2"></i> Rechercher Des Vols
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tripTypeRadios = document.querySelectorAll('input[name="trip_type_radio"]'); 
        const returnDateGroup = document.getElementById('return_date_group');
        const returnDateInput = document.getElementById('return_date');
        const hiddenTripType = document.getElementById('hidden_trip_type');

        function toggleReturnDate() {
            const isOneWay = document.querySelector('input[name="trip_type_radio"]:checked').value === 'one_way';
            
            if (hiddenTripType) hiddenTripType.value = document.querySelector('input[name="trip_type_radio"]:checked').value;
            
            if (isOneWay) {
                if(returnDateGroup) returnDateGroup.style.display = 'none';
                if(returnDateInput) {
                    returnDateInput.removeAttribute('required'); 
                    returnDateInput.value = ''; 
                }
            } else {
                if(returnDateGroup) returnDateGroup.style.display = 'block';
                if(returnDateInput) returnDateInput.setAttribute('required', 'required'); 
            }
        }

        tripTypeRadios.forEach(radio => {
            radio.addEventListener('change', toggleReturnDate);
        });

        toggleReturnDate();

        const departureInput = document.getElementById('departure_airport');
        const arrivalInput = document.getElementById('arrival_airport');

        function handleAutocomplete(event) {
            const inputElement = event.target;
            const searchTerm = inputElement.value; 
            const resultsContainerId = inputElement.id + '-results';
            let resultsContainer = document.getElementById(resultsContainerId);

            resultsContainer.innerHTML = ''; 

            if (searchTerm.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            fetch(`index.php?action=search-airport&term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(airport => {
                            const listItem = document.createElement('li');
                            const displayValue = `${airport.city} (${airport.iata_code})`;

                            listItem.innerHTML = `
                                <div data-value="${displayValue}" class="flex items-center">
                                    <i class="fas fa-plane mr-2 text-gray-500"></i>
                                    <span><strong>${airport.iata_code}</strong> - ${airport.name}, ${airport.city} (${airport.country})</span>
                                </div>
                            `;
                            
                            listItem.querySelector('div').addEventListener('click', function() {
                                inputElement.value = this.dataset.value; 
                                resultsContainer.style.display = 'none';
                            });

                            resultsContainer.appendChild(listItem);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<li class="p-3 text-gray-500 text-sm">Aucun aéroport trouvé.</li>';
                        resultsContainer.style.display = 'block';
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }

        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        if (departureInput) departureInput.addEventListener('input', debounce(handleAutocomplete, 300));
        if (arrivalInput) arrivalInput.addEventListener('input', debounce(handleAutocomplete, 300));

        document.addEventListener('click', function(e) {
            const hideResults = (input, resultsId) => {
                const results = document.getElementById(resultsId);
                if (results && input && !input.contains(e.target) && !e.target.closest(`#${resultsId}`)) {
                        results.style.display = 'none';
                }
            };
            hideResults(departureInput, 'departure_airport-results');
            hideResults(arrivalInput, 'arrival_airport-results');
        });
        
        const departureDateInput = document.getElementById('departure_date');
        const searchForm = document.querySelector('form');
        const loaderOverlay = document.getElementById('loader-overlay');
        
        if (departureDateInput && returnDateInput) {
            const today = new Date().toISOString().split('T')[0];
            departureDateInput.setAttribute('min', today);
            returnDateInput.setAttribute('min', today);
            
            departureDateInput.addEventListener('change', function() {
                returnDateInput.setAttribute('min', this.value);
                if (returnDateInput.value && returnDateInput.value < this.value) {
                    returnDateInput.value = this.value;
                }
            });
        }
        
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                 const isRoundTrip = document.querySelector('input[name="trip_type_radio"]:checked').value === 'round_trip';
                 if (isRoundTrip && departureDateInput && returnDateInput) {
                    if (returnDateInput.value < departureDateInput.value) {
                        e.preventDefault();
                        alert("La date de retour ne peut pas être antérieure à la date de départ.");
                        return;
                    }
                 }
                 if (loaderOverlay) loaderOverlay.classList.remove('hidden');
            });
        }
    });
</script>
