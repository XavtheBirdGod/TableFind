<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tafel Reserveren</title>
</head>
<body class="bg-[#F4EEE0] p-10">
<div class="max-w-lg mx-auto bg-white p-8 rounded shadow-lg border-2 border-[#2C2420]">
    <h2 class="text-3xl font-bold mb-6 italic text-[#2C2420]">Reserveer uw Tafel</h2>

    <form action="/reservations/public-store" method="POST" class="space-y-4">
        <div>
            <label class="block font-bold">Naam *</label>
            <input type="text" name="customer_name" required class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div>
            <label class="block font-bold">Telefoonnummer *</label>
            <input type="text" name="customer_phone" required class="w-full border border-gray-300 p-2 rounded">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-bold">Datum *</label>
                <input type="date" name="reservation_date" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div>
                <label class="block font-bold">Tijd *</label>
                <input type="time" name="reservation_time" required class="w-full border border-gray-300 p-2 rounded">
            </div>
        </div>
        <div>
            <label class="block font-bold">Aantal Personen</label>
            <input type="number" name="guest_count" min="1" value="2" class="w-full border border-gray-300 p-2 rounded">
        </div>
        <button type="submit" class="w-full bg-[#2C2420] text-white font-bold py-3 hover:bg-opacity-90 transition">
            BEVESTIG RESERVERING
        </button>
    </form>
</div>
</body>
</html>