<header class="bg-[#A3C7A7] flex flex-col md:flex-row items-center justify-between px-4 py-3 gap-3 border-b-2 border-[#78B183]">
    <a href="index.php">
        <img src="img/parts/logo.png" alt="HighFlights logo" class="w-[160px] max-w-full">
    </a>

    <nav>
        <ul class="kadwa flex flex-wrap justify-center items-center gap-2 text-lg text-white">
            <li>
                <a href="index.php" class="block rounded bg-[#78B183] px-4 py-2 hover:bg-[#497F53]">
                    Home
                </a>
            </li>

            <li>
                <a href="destinations.php" class="block rounded bg-[#78B183] px-4 py-2 hover:bg-[#497F53]">
                    Destinations
                </a>
            </li>

            <li>
                <a href="aboutus.php" class="block rounded bg-[#78B183] px-4 py-2 hover:bg-[#497F53]">
                    About Us
                </a>
            </li>

            <li>
                <a href="contact.php" class="block rounded bg-[#78B183] px-4 py-2 hover:bg-[#497F53]">
                    Contact
                </a>
            </li>
        </ul>
    </nav>
    <? if (!isLoggedIn()) { ?>
        <a href="login.php">
            <div class="px-4 py-2 rounded bg-[#497F53] text-white">
                Log in
            </div>
        </a>
    <? } else { ?>
        <a href="logout.php">
            <div class="px-4 py-2 rounded bg-[#497F53] text-white">
                Log uit
            </div>
        </a>
    <? } ?>
</header>