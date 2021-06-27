# <h1>MEGISA - Museum Exploration GIS Application(Muziejų tyrinėjimo GIS aplikacija)</h1>
<p>Tai programa, skirta susipažinti su tam tikro miesto muziejais.</p>

Pagrindiniai kodo failai Laravel karakaso kataloge:
<ul>
  <li><em>app/Http/Controllers/MDataController.php</em> - tai <em>Laravel</em> valdiklis, kurio getMuzData() funkcija surenka muzieju adresu informacija iš lokalios aplikacijos duomenų bazės.</li>
  <li><em>app/GeoLocation.php</em> - Tai <em>Laravel</em> modelis, kuris reikalingas duomenų duomenų bazės duomenų valdymui.</li>
  <li><em>app/Muzeum.php</em> - Tai <em>Laravel</em> modelis, kuris reikalingas duomenų duomenų bazės duomenų valdymui.</li>
  <li><em>database/migrations/2019_08_19_000000_create_failed_jobs_table.php</em> - Tai <em>Laravel</em> duomenų bazės migration failas, kuris sukuria testinę duomenų bazės lentelę</li>
  <li><em>database/seeds/DatabaseSeeder.php</em> - Tai <em>Laravel</em> testinių duomenų bazės įrašų sugeneravimui seeder php failas</li>
  <li><em>resources/views/main_app_page.blade.php</em> - Tai <em>Laravel</em> blade php failas, kuris sukuria pagrindinį aplikacijos puslapį. Šiame faile yra pagrindinis ArcGIS API paremtos programos JavaScript kodas.</li>
  <li><em>resources/views/apppage.blade.php</em> - Tai <em>Laravel</em> blade php failas, kuriame testinis ArcGIS API paremtas puslapio kodas.</li>
  <li><em>routes/web.php</em> - yra aprašytas pagrindinis internetinės svetainės puslapio routas, kuris užkrauna klientui aplikacijos puslapį.</li>
</ul>

