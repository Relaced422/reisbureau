<section id="searchbar" class="px-4 py-8">
    <div class="flex justify-center">
        <form action="results.php" method="GET"
            class="bg-[#5F7A56] w-full max-w-7xl p-4 sm:p-8 md:p-10 lg:p-12 flex rounded-3xl flex-col gap-6">

            <!-- Main search fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
                <div class="flex flex-col">
                    <label for="departure" class="text-white text-center mb-1">Departure</label>
                    <select id="departure" name="departure" required
                        class="bg-[#EEF4CD] rounded-xl p-3 sm:p-4 lg:p-5 w-full">
                        <option value="">Select country</option>
                        <option value="canada">Canada</option>
                        <option value="uruguay">Uruguay</option>
                        <option value="germany">Germany</option>
                        <option value="malta">Malta</option>
                        <option value="luxembourg">Luxembourg</option>
                        <option value="czech_republic">Czech Republic</option>
                        <option value="south_africa">South Africa</option>
                        <option value="mexico">Mexico</option>
                        <option value="thailand">Thailand</option>
                        <option value="netherlands">Netherlands</option>
                        <option value="spain">Spain</option>
                        <option value="portugal">Portugal</option>
                        <option value="jamaica">Jamaica</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="destination" class="text-white text-center mb-1">Destination</label>
                    <select id="destination" name="destination" required
                        class="bg-[#EEF4CD] rounded-xl p-3 sm:p-4 lg:p-5 w-full">
                        <option value="">Select country</option>
                        <option value="canada">Canada</option>
                        <option value="uruguay">Uruguay</option>
                        <option value="germany">Germany</option>
                        <option value="malta">Malta</option>
                        <option value="luxembourg">Luxembourg</option>
                        <option value="czech_republic">Czech Republic</option>
                        <option value="south_africa">South Africa</option>
                        <option value="mexico">Mexico</option>
                        <option value="thailand">Thailand</option>
                        <option value="netherlands">Netherlands</option>
                        <option value="spain">Spain</option>
                        <option value="portugal">Portugal</option>
                        <option value="jamaica">Jamaica</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="departure_date" class="text-white text-center mb-1">Departure Date</label>
                    <input type="date" id="departure_date" name="departure_date" required
                        class="bg-[#EEF4CD] rounded-xl p-3 sm:p-4 lg:p-5 w-full">
                </div>

                <div class="flex flex-col">
                    <label for="return_date" class="text-white text-center mb-1">Return Date</label>
                    <input type="date" id="return_date" name="return_date" required
                        class="bg-[#EEF4CD] rounded-xl p-3 sm:p-4 lg:p-5 w-full">
                </div>

                <div class="flex flex-col">
                    <label for="travelers" class="text-white text-center mb-1">Travelers</label>
                    <select id="travelers" name="travelers" required
                        class="bg-[#EEF4CD] rounded-xl p-3 sm:p-4 lg:p-5 w-full">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select>
                </div>
            </div>

            <!-- Extra options -->
            <div class="bg-[#D7EB8B] p-4 sm:p-6 rounded-3xl flex flex-col gap-4">
                <div class="text-black text-center sm:text-left">
                    <h2 class="font-semibold text-lg">Extra Options</h2>
                    <p class="text-sm">Tap to select</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 justify-items-center">
                    <label class="flex flex-col items-center cursor-pointer w-full">
                        <input type="checkbox" name="high_onboarding" value="1" class="peer sr-only">
                        <span class="text-black text-center text-sm sm:text-base mb-2">High Onboarding</span>
                        <div
                            class="flex items-center justify-center rounded-full p-3 bg-red-800 peer-checked:bg-green-700 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 border-4 transition-colors duration-200">
                            <img src="img/icons/leafSelect.png" alt="" class="w-full h-full object-contain">
                        </div>
                    </label>

                    <label class="flex flex-col items-center cursor-pointer w-full">
                        <input type="checkbox" name="hotel_included" value="1" class="peer sr-only">
                        <span class="text-black text-center text-sm sm:text-base mb-2">Hotel Included</span>
                        <div
                            class="flex items-center justify-center rounded-full p-3 bg-red-800 peer-checked:bg-green-700 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 border-4 transition-colors duration-200">
                            <img src="img/icons/hotelSelect.png" alt="" class="w-full h-full object-contain">
                        </div>
                    </label>

                    <label class="flex flex-col items-center cursor-pointer w-full">
                        <input type="checkbox" name="transport_included" value="1" class="peer sr-only">
                        <span class="text-black text-center text-sm sm:text-base mb-2">Transport Included</span>
                        <div
                            class="flex items-center justify-center rounded-full p-3 bg-red-800 peer-checked:bg-green-700 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 border-4 transition-colors duration-200">
                            <img src="img/icons/transportSelect.png" alt="" class="w-full h-full object-contain">
                        </div>
                    </label>

                    <label class="flex flex-col items-center cursor-pointer w-full">
                        <input type="checkbox" name="pet_friendly" value="1" class="peer sr-only">
                        <span class="text-black text-center text-sm sm:text-base mb-2">Pet Friendly</span>
                        <div
                            class="flex items-center justify-center rounded-full p-3 bg-red-800 peer-checked:bg-green-700 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 border-4 transition-colors duration-200">
                            <img src="img/icons/petSelect.png" alt="" class="w-full h-full object-contain">
                        </div>
                    </label>

                    <label class="flex flex-col items-center cursor-pointer w-full col-span-2 sm:col-span-1">
                        <input type="checkbox" name="sensitive" value="1" class="peer sr-only">
                        <span class="text-black text-center text-sm sm:text-base mb-2">I know my limits</span>
                        <div
                            class="flex items-center justify-center rounded-full p-3 bg-red-800 peer-checked:bg-green-700 h-16 w-16 sm:h-20 sm:w-20 lg:h-24 lg:w-24 border-4 transition-colors duration-200">
                            <img src="img/icons/sensitiveSelect.png" alt="" class="w-full h-full object-contain">
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="bg-[#A3C7A7] hover:bg-[#78B183] text-white font-semibold py-3 sm:py-4 px-8 rounded-2xl transition-all duration-300 hover:scale-105 text-lg self-center w-full sm:w-auto sm:min-w-[240px]">
                Search Trips
            </button>
        </form>
    </div>
</section>