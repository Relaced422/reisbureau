<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db/auth.php';
?>

<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HighFlights — About Us</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
  </style>
</head>

<body class="bg-[#f8f5ef]">

  <?php include __DIR__ . '/includes/header.php'; ?>

  <section class="bg-[#2e5435] min-h-[40vh] flex items-center">
    <div class="max-w-5xl mx-auto px-6 py-20 w-full text-center">
      <h1 class="text-white text-4xl md:text-5xl font-bold leading-tight mb-3" style="font-family:'Kadwa',serif;">
        About HighFlights
      </h1>
      <p class="text-[#a3c7a7] text-lg">Cannabis-friendly travel, done right.</p>
    </div>
  </section>

  <section class="py-20 px-6">
    <div class="max-w-4xl mx-auto flex flex-col gap-8">
      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-3" style="font-family:'Kadwa',serif;">Our Story</h2>
        <p class="text-gray-600 leading-relaxed">
          HighFlights started with a simple frustration: travelers who like to relax with cannabis had nowhere
          that catered to them directly. So we built it ourselves — a travel agency that partners exclusively
          with cannabis-friendly airlines and destinations, so you never have to hide or hesitate.
        </p>
      </div>

      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-3" style="font-family:'Kadwa',serif;">What We Do</h2>
        <p class="text-gray-600 leading-relaxed">
          We hand-select airlines and destinations that allow in-flight cannabis consumption, partner with local
          growers for fresh pre-rolls, and curate places built for slowing down. No itinerary pressure, no
          oversold packages — just comfortable, honest travel.
        </p>
      </div>

      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#2e5435] mb-3" style="font-family:'Kadwa',serif;">Who We Are</h2>
        <p class="text-gray-600 leading-relaxed">
          HighFlights is run by Tobi Quenum & Fero Seifert. Two students who got tired of travel agencies that
          overcomplicate things, and decided to build the agency they wished existed.
        </p>
      </div>
    </div>
  </section>

  <section class="bg-[#1e3b24] py-16 px-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
      <div>
        <div class="text-white font-bold text-3xl mb-1" style="font-family:'Kadwa',serif;">12+</div>
        <p class="text-[#a3c7a7] text-sm">Destinations</p>
      </div>
      <div>
        <div class="text-white font-bold text-3xl mb-1" style="font-family:'Kadwa',serif;">2025</div>
        <p class="text-[#a3c7a7] text-sm">Founded</p>
      </div>
      <div>
        <div class="text-white font-bold text-3xl mb-1" style="font-family:'Kadwa',serif;">100%</div>
        <p class="text-[#a3c7a7] text-sm">Cannabis-friendly</p>
      </div>
    </div>
  </section>

  <!-- ── Footer ── -->
  <?php include __DIR__ . '/includes/footer.php'; ?>

</body>

</html>