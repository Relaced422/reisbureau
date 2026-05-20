<section id="searchbar">
    <div class="flex justify-center">
        <form class="bg-[#5F7A56] max-w-[75vw] p-12 flex rounded-3xl justify-evenly gap-6 flex-col">
            <div class="flex gap-6">
                <div class="flex flex-col items-center">
                    <h2 class="text-white">Departure</h2>
                    <select id="departure" name="departure" class="bg-[#EEF4CD] rounded-xl p-5">
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

                <div class="flex flex-col items-center">
                    <h2 class="text-white">Destination</h2>
                    <select id="destination" name="destination" class="bg-[#EEF4CD] rounded-xl p-5">
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

                <div class="flex flex-col items-center">
                    <h2 class="text-white">Departure Date</h2>
                    <input type="text" class="bg-[#EEF4CD] rounded-xl p-6" placeholder="DD/MM/YYYY">
                </div>

                <div class="flex flex-col items-center">
                    <h2 class="text-white">Return Date</h2>
                    <input type="text" class="bg-[#EEF4CD] rounded-xl p-6" placeholder="DD/MM/YYYY">
                </div>

                <div class="flex flex-col items-center">
                    <h2 class="text-white">Travelers</h2>
                    <select id="travelers" name="travelers" class="bg-[#EEF4CD] rounded-xl p-5">
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
            <div class="bg-[#D7EB8B] flex items-center p-6 rounded-3xl gap-6 justify-evenly">
                <div class="text-black flex flex-col">
                    <h2>Extra Options</h2>
                    <h2>Tap to select</h2>
                </div>
                <div class="flex flex-col items-center">
                    <h2>High Onboarding</h2>
                    <div
                        class="flex flex-col items-center justify-center rounded-full p-3 bg-red-800 h-[8vw] w-[8vw] border-4">
                        <img src="img/icons/leafSelect.png" alt="Weed travel toggle">
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <h2>Hotel Included</h2>
                    <div
                        class="flex flex-col items-center justify-center rounded-full p-3 bg-red-800 h-[8vw] w-[8vw] border-4">
                        <img src="img/icons/hotelSelect.png" alt="Hotel travel toggle">
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <h2>Transport Included</h2>
                    <div
                        class="flex flex-col items-center justify-center rounded-full p-3 bg-red-800 h-[8vw] w-[8vw] border-4">
                        <img src="img/icons/transportSelect.png" alt="Transport travel toggle">
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <h2>Pet Friendly</h2>
                    <div
                        class="flex flex-col items-center justify-center rounded-full p-3 bg-red-800 h-[8vw] w-[8vw] border-4">
                        <img src="img/icons/petSelect.png" alt="Pet friendly toggle">
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <h2>I know my limits</h2>
                    <div
                        class="flex flex-col items-center justify-center rounded-full p-3 bg-red-800 h-[8vw] w-[8vw] border-4">
                        <img src="img/icons/sensitiveSelect.png" alt="Easily Nauseous  toggle">
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>