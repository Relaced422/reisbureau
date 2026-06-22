<div class="bg-white border border-gray-300 p-5 text-left rounded-xl">
        <form action="destinations.php" method="get">

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <div class="flex flex-col gap-1">
              <label class="text-sm font-semibold text-[#2e5435]">Destination</label>
              <input type="text" name="destination" placeholder="Where to?"
                class="border border-gray-300 px-3 py-2 text-sm" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-sm font-semibold text-[#2e5435]">Departure Date</label>
              <input type="date" name="departure"
                class="border border-gray-300 px-3 py-2 text-sm" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-sm font-semibold text-[#2e5435]">Return Date</label>
              <input type="date" name="return"
                class="border border-gray-300 px-3 py-2 text-sm" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-sm font-semibold text-[#2e5435]">Travelers</label>
              <select name="travelers" class="border border-gray-300 px-3 py-2 text-sm bg-white">
                <option value="1">1 traveler</option>
                <option value="2" selected>2 travelers</option>
                <option value="3">3 travelers</option>
                <option value="4">4 travelers</option>
                <option value="5">5 travelers</option>
                <option value="6">6+ travelers</option>
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label class="text-sm font-semibold text-[#2e5435] block mb-1">Budget (EUR)</label>
            <div class="flex items-center gap-3">
              <input type="range" id="budget" name="budget" min="500" max="15000" value="6000"
                class="flex-1 accent-[#2e5435]" />
              <span id="budget-display" class="text-sm font-bold text-[#2e5435]">€ 6.000</span>
            </div>
          </div>

          <div class="mb-4">
            <p class="text-sm font-semibold text-[#2e5435] mb-2">Extra Options</p>
            <div class="flex flex-wrap gap-2">
              <?php foreach ([
                'high-onboarding' => 'High Onboarding',
                'hotel' => 'Hotel Included',
                'transport' => 'Transport Included',
                'pet-friendly' => 'Pet Friendly',
                'limits' => 'I Know My Limits',
              ] as $val => $label): ?>
                <label class="flex items-center gap-1 border border-gray-300 px-2 py-1 text-sm cursor-pointer">
                  <input type="checkbox" name="extras[]" value="<?= $val ?>" class="accent-[#2e5435]" />
                  <?= $label ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold px-6 py-2 rounded text-sm">
              Search Flights →
            </button>
          </div>
        </form>
      </div>