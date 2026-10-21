@extends('layouts.app')

@section('content')
<x-city-page
    route="casablanca"
    city="Casablanca"
    office-city="Casablanca"
    image="casa-1600.webp"
    image-alt="Mosquée Hassan II à Casablanca"
    image-width="1600"
    image-height="1296"
    :image-srcset="asset('images/casa-800.webp').' 800w, '.asset('images/casa-1600.webp').' 1600w'"
    region="Casablanca · Maroc"
    :badges="['Expertise locale', 'Conseil sur mesure', 'Vision pluridisciplinaire']"
    about-lead="Dans un environnement économique exigeant, entreprises, entrepreneurs et investisseurs ont besoin d’une information claire et d’un accompagnement réactif pour avancer avec maîtrise."
    about-body="Notre équipe coordonne les expertises essentielles, explique chaque enjeu et reste présente lorsque vos projets demandent un regard fiable et une réponse concrète."
    map-heading="Retrouvez facilement notre équipe."
    address="Casablanca · Maroc"
    :services="$services"
/>
@endsection
