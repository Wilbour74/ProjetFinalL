<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Paramètres du profil</h1>
            
            <div class="space-y-6">
                <!-- Informations du profil -->
                <div class="bg-white rounded-xl shadow p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Mise à jour du mot de passe -->
                <div class="bg-white rounded-xl shadow p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Suppression du compte -->
                <div class="bg-white rounded-xl shadow p-6 sm:p-8">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
