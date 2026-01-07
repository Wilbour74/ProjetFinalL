<?php

if (!function_exists('parseContent')) {
    /**
     * Transforme les hashtags et mentions en liens cliquables
     * 
     * @param string $content
     * @return string
     */
    function parseContent($content)
    {
        // Échapper le HTML existant pour éviter les injections
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
        
        // Transformer les hashtags (#tag) en liens vers la recherche
        $content = preg_replace_callback(
            '/#(\w+)/u',
            function ($matches) {
                $tag = $matches[1];
                $searchUrl = route('search.index', ['q' => '#' . $tag]);
                return '<a href="' . $searchUrl . '" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">#' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') . '</a>';
            },
            $content
        );
        
        // Transformer les mentions (@username) en liens vers le profil
        $content = preg_replace_callback(
            '/@(\w+)/u',
            function ($matches) {
                $username = $matches[1];
                // Chercher l'utilisateur par username
                $user = \App\Models\User::where('username', $username)->first();
                if ($user) {
                    // Rediriger vers le profil de l'utilisateur
                    $profileUrl = route('profile.show', $username);
                    return '<a href="' . $profileUrl . '" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium">@' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8') . '</a>';
                }
                // Si l'utilisateur n'existe pas, on laisse le texte tel quel
                return '@' . htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            },
            $content
        );
        
        // Convertir les retours à la ligne en <br>
        $content = nl2br($content);
        
        return $content;
    }
}

