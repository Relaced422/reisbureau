<?
require_once __DIR__ . '/db/auth.php';
requireAdmin();
if (!isset($_SESSION['userId'])) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>HighFlights — Admin</title>
    <link rel="stylesheet" href="style.css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kadwa:wght@400;700&display=swap');
    </style>
</head>

<body class="bg-[#F8FAF5]">

    <div class="bg-[#2e5435] p-5 flex items-center justify-between">
        <span class="text-[#D6F8BB] font-bold text-lg">🌿 HighFlights — Admin</span>
        <a href="/logout.php" class="text-[#A3C7A7] text-sm hover:text-white">Uitloggen →</a>
    </div>

    <div class="flex justify-center py-10">
    <div class="w-[90%] max-w-[1100px] flex flex-col gap-12">


        <!-- VLUCHTEN -->
        <section>
            <h2 class="text-xl font-bold mb-4 text-[#2e5435]">✈️ Vluchten</h2>

            <!-- foreach -->
            <div class="flex flex-col gap-3">

                <div class="bg-white border border-[#C8DFC9] rounded-xl overflow-hidden">

                    <!-- vlucht info -->
                    <div class="grid grid-cols-4 items-center gap-4 p-5">
                        <div>
                            <div class="font-bold text-[#2e5435]">Amsterdam → Finland</div>
                            <div class="text-xs text-[#6B7F6D]">GreenAir · 20-06-2025</div>
                        </div>
                        <div class="text-sm">€634,00 p.p.</div>
                        <div class="text-sm">180 stoelen</div>
                        <div><span class="booking-status status-confirmed">Actief</span></div>
                    </div>

                    <!-- edit formulier -->
                    <form method="post" class="border-t border-[#C8DFC9] bg-[#EEF4CD] p-5 flex flex-col gap-3">
                        <input type="hidden" name="action" value="edit_flight" />
                        <input type="hidden" name="id" value="" />

                        <div class="grid grid-cols-3 gap-3">
                            <div class="form-group">
                                <label>Bestemming</label>
                                <input type="text" name="destination_name" value="Finland" class="bg-white text-center"/>
                            </div>
                            <div class="form-group">
                                <label>Luchtvaartmaatschappij</label>
                                <input type="text" name="airline" value="GreenAir" class="bg-white text-center"/>
                            </div>
                            <div class="form-group">
                                <label>Prijs p.p. (€)</label>
                                <input type="number" name="price" step="0.01" value="634.00" class="bg-white text-center"/>
                            </div>
                            <div class="form-group">
                                <label>Vertrekdatum heen</label>
                                <input type="datetime-local" name="departure_date" value="2025-06-20T10:25" class="bg-white text-center"/>
                            </div>
                            <div class="form-group">
                                <label>Returdatum</label>
                                <input type="datetime-local" name="return_date" value="2025-06-27T18:00" class="bg-white text-center"/>
                            </div>
                            <div class="form-group">
                                <label>Stoelen</label>
                                <input type="number" name="seats" value="180" class="bg-white text-center"/>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="active" value="1" checked /> Actief
                            </label>
                            <button type="submit" class="btn btn-primary bg-green-500">💾 Opslaan</button>
                        </div>
                    </form>

                    <!-- delete -->
                    <form method="post" class="border-t border-[#C8DFC9] p-3 flex justify-end">
                        <input type="hidden" name="action" value="delete_flight" />
                        <input type="hidden" name="id" value="" />
                        <button type="submit" class="cancel-btn bg-[#FF7459] rounded-xl p-2">Vlucht verwijderen</button>
                    </form>

                </div>
                <!-- einde foreach -->

            </div>
        </section>


        <!-- VLUCHT TOEVOEGEN -->
        <section>
            <h2 class="text-xl font-bold mb-4 text-[#2e5435]">+ Vlucht toevoegen</h2>

            <div class="bg-white border border-[#C8DFC9] rounded-xl p-7 max-w-[680px]">
                <form method="post" class="flex flex-col gap-3">
                    <input type="hidden" name="action" value="add_flight" />

                    <div class="grid grid-cols-2 gap-3">
                        <div class="form-group">
                            <label>Bestemming</label>
                            <input type="text" name="destination_name" placeholder="Finland" required class="bg-gray-200 text-center"/>
                        </div>
                        <div class="form-group">
                            <label>Luchtvaartmaatschappij</label>
                            <input type="text" name="airline" placeholder="GreenAir" required class="bg-gray-200 text-center"/>
                        </div>
                        <div class="form-group">
                            <label>Vertrekdatum heen</label>
                            <input type="datetime-local" name="departure_date" required class="bg-gray-200 text-center"/>
                        </div>
                        <div class="form-group">
                            <label>Returdatum</label>
                            <input type="datetime-local" name="return_date" required class="bg-gray-200 text-center"/>
                        </div>
                        <div class="form-group">
                            <label>Prijs p.p. (€)</label>
                            <input type="number" name="price" step="0.01" min="0" placeholder="634.00" required class="bg-gray-200 text-center"/>
                        </div>
                        <div class="form-group">
                            <label>Stoelen</label>
                            <input type="number" name="seats" min="1" value="180" class="bg-gray-200 text-center"/>
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="active" value="1" checked /> Meteen actief
                    </label>

                    <div>
                        <button type="submit" class="btn btn-primary bg-[#C8DFC9] rounded-xl p-2">+ Toevoegen</button>
                    </div>
                </form>
            </div>
        </section>


        <!-- BOEKINGEN -->
        <section>
            <h2 class="text-xl font-bold mb-4 text-[#2e5435]">📋 Boekingen</h2>

            <div class="bg-white border border-[#C8DFC9] rounded-xl overflow-hidden">
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-[#EEF4CD]">
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Referentie</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Klant</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Bestemming</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Vertrek</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Reizigers</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Prijs</th>
                            <th class="p-3 text-left text-xs uppercase text-[#6B7F6D]">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- For each -->
                        <tr class="border-t border-[#C8DFC9]">
                            <td class="p-3 font-bold text-[#2e5435]">HF-001</td>
                            <td class="p-3">Tobi Quenum</td>
                            <td class="p-3">Finland</td>
                            <td class="p-3">20-06-2025</td>
                            <td class="p-3 text-center">2</td>
                            <td class="p-3 font-bold">€1.268,00</td>
                            <td class="p-3">
                                <form method="post" class="flex gap-2">
                                    <input type="hidden" name="action" value="update_booking" />
                                    <input type="hidden" name="id" value="" />
                                    <select name="status" class="border border-[#C8DFC9] rounded-md px-2 py-1 text-xs font-[Kadwa] bg-[#F8FAF5]">
                                        <option value="pending">In afwachting</option>
                                        <option value="confirmed" selected>Bevestigd</option>
                                        <option value="cancelled">Geannuleerd</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary" style="padding:4px 10px;font-size:.78rem;">✓</button>
                                </form>
                            </td>
                        </tr>
                        <!-- einde foreach -->

                    </tbody>
                </table>
            </div>
        </section>


    </div>
    </div>

</body>
</html>