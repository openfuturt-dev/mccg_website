@extends('layouts.app')

@section('content')
<x-city-page
    route="marrakech"
    city="Marrakech"
    office-city="Marrakech"
    image="marrakech.webp"
    image-alt="La Koutoubia et la place Jemaa el-Fna à Marrakech"
    image-width="1260"
    image-height="840"
    region="Guéliz · Marrakech · Maroc"
    :badges="['Expertise locale', 'Conseil sur mesure', 'Vision pluridisciplinaire']"
    about-lead="Entreprises, entrepreneurs et investisseurs évoluent dans un environnement qui exige précision et réactivité. Nous réunissons les expertises essentielles pour vous donner une lecture juste de votre activité."
    about-body="Notre rôle ne s’arrête pas aux obligations. Nous expliquons, anticipons et restons présents lorsque vos projets demandent un regard fiable et une réponse concrète."
    map-heading="Venez rencontrer notre équipe à Guéliz."
    address="92 Boulevard Zerktouni, 2ème et 3ème étage,<br> appartements 6, 12 et 13 · Marrakech 40000"
    :services="$services"
/>
@endsection
