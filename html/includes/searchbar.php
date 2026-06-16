<div class="bg-white rounded-2xl shadow-xl p-8 text-left">
        <form action="destinations.php" method="get">

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Destination</label>
              <input type="text" name="destination" placeholder="Where to?"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Departure Date</label>
              <input type="date" name="departure"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Return Date</label>
              <input type="date" name="return"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435]" />
            </div>
            <div class="flex flex-col gap-1">
              <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide">Travelers</label>
              <select name="travelers"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#2e5435] bg-white">
                <option value="1">1 traveler</option>
                <option value="2" selected>2 travelers</option>
                <option value="3">3 travelers</option>
                <option value="4">4 travelers</option>
                <option value="5">5 travelers</option>
                <option value="6">6+ travelers</option>
              </select>
            </div>
          </div>

          <div class="mb-5">
            <label class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide block mb-2">Budget (EUR)</label>
            <div class="flex items-center gap-4">
              <input type="range" id="budget" name="budget" min="500" max="15000" value="6000"
                class="flex-1 accent-[#2e5435]" />
              <span id="budget-display" class="text-sm font-bold text-[#2e5435] w-24 text-right">€ 6.000</span>
            </div>
          </div>

          <div class="mb-6">
            <p class="text-xs font-semibold text-[#2e5435] uppercase tracking-wide mb-3">Extra Options</p>
            <div class="flex flex-wrap gap-2">
              <?php foreach ([
                'high-onboarding' => '🌿 High Onboarding',
                'hotel' => '🏨 Hotel Included',
                'transport' => '🚌 Transport Included',
                'pet-friendly' => '🐾 Pet Friendly',
                'limits' => '✋ I Know My Limits',
              ] as $val => $label): ?>
                <label
                  class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-full text-sm cursor-pointer hover:border-[#2e5435] hover:bg-[#f0f9f0] transition-colors">
                  <input type="checkbox" name="extras[]" value="<?= $val ?>" class="accent-[#2e5435]" />
                  <?= $label ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="flex justify-end">
            <button type="submit"
              class="bg-[#2e5435] hover:bg-[#1e3b24] text-white font-bold px-8 py-3 rounded-xl transition-colors text-sm">
              Search Flights →
            </button>
          </div>
        </form>
      </div>