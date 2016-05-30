<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Tags are the most important a part of the applications search engine.
 * Each post has many tags and users can search posts by tags.
 * @package App
 */
class Tag extends Model
{
    /**
     * Predefined tags
     * @var array
     */
    public static $predefinedTags = [
      "Fachlich" => [
        "Andere Fremdsprachen",
        "Beruforientierung",
        "Bewegung und Sport",
        "Bildnerische Erziehung",
        "Biologie und Umweltkunde",
        "Chemie",
        "Detusch",
        "Englisch",
        "Ernährung und Haushalt",
        "Geographie und Wirstschaftskunde",
        "Geometrisches Zeichnen",
        "Geschichte und Sozialkunde",
        "Informatik",
        "Lerncoaching",
        "Mathematik",
        "Musikerziehung",
        "Physik",
        "Religion",
        "Technisches Werken",
        "Textiles Werken"
      ],

      "Inhaltliches" => [
        "CRM",
        "Didaktik",
        "Jahresplan",
        "Lesson plan",
        "Soziale Kompetenz",
        "Themenplan",
        "Unterrichtsmaterial",
        "Wochenplan"
      ],

      "Programmbezogenes" => [
        "Sommerakademie",
        "Workshop",
        "Seminar",
        "Leadership",
        "TfA-Vorlagen",
        "TfA-Hinweisen"
      ],

      "Schulsystem" => [
        "Arbeitsrechht",
        "Blog-Eintrag",
        "Fördermöglichkeiten",
        "Schulrecht",
        "Studie",
        "Zeitungsartikel"
      ]
    ];

    public $timestamps = false;
    protected $fillable = ['value'];

    /**
     * Returns a relationship to all posts related with this tag.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
