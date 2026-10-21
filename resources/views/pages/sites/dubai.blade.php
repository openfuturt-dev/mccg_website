@extends('layouts.app')

@section('content')
<x-city-page
    route="dubai"
    city="Dubaï"
    office-city="Dubai"
    image="dubai.webp"
    image-alt="Vue panoramique de Dubaï"
    image-width="1920"
    image-height="1280"
    region="Dubaï · Émirats arabes unis"
    :badges="['Expertise internationale', 'Conseil sur mesure', 'Vision pluridisciplinaire']"
    about-lead="Dans un environnement international dynamique, entreprises, entrepreneurs et investisseurs ont besoin d’une information claire et d’un accompagnement réactif pour avancer avec maîtrise."
    about-body="Notre équipe coordonne les expertises essentielles, explique chaque enjeu et reste présente lorsque vos projets demandent un regard fiable et une réponse concrète."
    map-heading="Retrouvez facilement notre équipe."
    address="Dubaï · Émirats arabes unis"
    :services="$services"
/>
@endsection
