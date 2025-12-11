<?php

if (!function_exists('getTypeIcon')) {
    function getTypeIcon($typeName)
    {
        $icons = [
            'Article' => 'fas fa-newspaper',
            'Video' => 'fas fa-play-circle',
            'Audio' => 'fas fa-microphone',
            'Image' => 'fas fa-image',
            'Document' => 'fas fa-file-alt',
            'PDF' => 'fas fa-file-pdf',
            'Présentation' => 'fas fa-presentation-screen',
            'Livre' => 'fas fa-book',
            'Cours' => 'fas fa-graduation-cap',
            'Quiz' => 'fas fa-question-circle',
            // Ajoutez d'autres types au besoin
        ];

        return $icons[$typeName] ?? 'fas fa-file'; // icône par défaut
    }
}