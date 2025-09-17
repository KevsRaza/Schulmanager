<x-layouts.app>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">{{ $schuler->full_name }}</h2>
                <a href="{{ route('schulers.index') }}" class="text-blue-600 hover:text-blue-800">← Retour</a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Informations Personnelles</h3>
                    <div class="space-y-2">
                        <p><strong>Nom complet:</strong> {{ $schuler->full_name }}</p>
                        <p><strong>Email:</strong> {{ $schuler->email }}</p>
                        <p><strong>Date de naissance:</strong> {{ $schuler->geburtsdatum_Schuler->format('d/m/Y') }}</p>
                        <p><strong>Âge:</strong> {{ $schuler->alter }} ans</p>
                        <p><strong>Pays:</strong> {{ $schuler->land_Shuler }}</p>
                        <p><strong>Niveau allemand:</strong> {{ $schuler->deutschniveau_Schuler }}</p>
                        <p><strong>Niveau éducation:</strong> {{ $schuler->bildungsniveau_Schuler }}</p>
                    </div>
                </div>

                <!-- Formation -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Formation</h3>
                    <div class="space-y-2">
                        <p><strong>Formation:</strong> {{ $schuler->ausbildung->name_Ausbildung ?? 'N/A' }}</p>
                        <p><strong>Début:</strong> {{ $schuler->datum_Anfang_Ausbildung->format('d/m/Y') }}</p>
                        <p><strong>Fin:</strong> {{ $schuler->datum_Ende_Ausbildung->format('d/m/Y') }}</p>
                        <p><strong>École:</strong> {{ $schuler->schule->name_Schule ?? 'N/A' }}</p>
                        <p><strong>Entreprise:</strong> {{ $schuler->firma->name_Firma ?? 'N/A' }}</p>
                        <p><strong>Dossier:</strong> {{ $schuler->dossier->name_Dossier ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>